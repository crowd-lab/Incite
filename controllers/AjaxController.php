<?php

/**
 * Incite
 *
 */

/**
 * Plugin "Incite"
 *
 * @package Incite
 * Ajax controller for responding different ajax requests
 */
class Incite_AjaxController extends Omeka_Controller_AbstractActionController
{
    public function init() {
        //Since this is for ajax purpose, we don't need to render any views!
        $this->_helper->viewRenderer->setNoRender(TRUE);
        require_once('Incite_Helpers.php');
        require_once("DiscoverController.php");
        require_once("DocumentsController.php");
        require_once("Incite_Search.php");
        require_once("Incite_System_Log.php");
        require_once("Incite_Session.php");
        require_once("Incite_Env_Setting.php");
        require_once("Email.php");

    }

    /**
     * Ajax function to check if a username and password does exist in the database and if they are valid.
     * A cookie is created when the login is valid
     * If a guest account was being used previously, it is mapped to the logged in account
     */
    public function loginAction() {
        if ($this->getRequest()->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $isGuest = false;
            $guestID = -1;
            $db = get_db();
            $userTable = $db->getTable("InciteUser");
            $user = $userTable->findUserByEmailAndPassword($username, md5($password));
            
            if (!is_null($user)) {
                //If there is already a guest session, then combine the guest session with the verified user
                if (isset($_SESSION['Incite']['Guest']) && $_SESSION['Incite']['Guest'] == true) {
                    $guestID = $_SESSION['Incite']['USER_DATA']->id;
                    $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
                    $_SESSION['Incite']['Guest'] = false;
                    $_SESSION['Incite']['USER_DATA'] = $user;
                    mapAccounts($guestID, $_SESSION['Incite']['USER_DATA']['id']);
                } else {
                    system_log('not a guest before login!');
                }
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    /**
     * This method edits user's information. Calls editAccount in
     * Incite_Users_Table class and updates username, password, First name,
     * and the Last name.
     */
    public function editaccountAction() {
        if ($this->getRequest()->isPost()) {
            $id = $_SESSION['Incite']['USER_DATA']['id'];
            $password = $_POST['password'];
            $firstName = $_POST['fName'];
            $lastName = $_POST['lName'];

            $user = $this->_helper->db->getTable('InciteUser')->findUserById($id);
            if (isset($user)) {
                $user->password = md5($password);
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->save();
                echo 'true';
            } else {
                echo 'false';
            }


        }
    }

    /**
     * Ajax function that creates accounts. This can be invoked in 2 ways
     * 1) An action is done and the user is not logged in, an account is automatically created for said user.
     * This account is a 'guest' account only meant for tracking any changes on the website
     *
     * 2) The user wants to create an account on the website that is not a guest account. If a guest account was used,
     * it is mapped back to the new account. On completion of making a new account, the user is automatically signed in.
     *
     * This method will throw 'false' if an account already exists in the system
     */
    public function createaccountAction() {

        if ($this->getRequest()->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstName = $_POST['fName'];
            $lastName = $_POST['lName'];
            $priv = $_POST['priv'];
            $exp = $_POST['exp'];
            $isGuest = false;
            $user = $this->_helper->db->getTable('InciteUser')->findUserByEmail($username);
            if (isset($user)) {
                echo 'exists';
            } else  {
                $user = new InciteUser;
                $user->password = md5($password);
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->email = $username;
                $user->privilege_level = $priv;
                $user->experience_level = $exp;
                $user->is_active = 1;
                $user->working_group_id = 0;
                $user->save();
                if (isset($user->id)) {
                    if (isset($_SESSION['Incite']['Guest']) && $_SESSION['Incite']['Guest'] == true) {
                        $guestId = $_SESSION['Incite']['USER_DATA']->id;
                        $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
                        $_SESSION['Incite']['Guest'] = false;
                        $_SESSION['Incite']['USER_DATA'] = $user;

                        $userGuest = new InciteUsersGuests;
                        $userGuest->user_id = $_SESSION['Incite']['USER_DATA']->id;
                        $userGuest->guest_id = $guestId;
                        $userGuest->save();
                    } else {
                        system_log('not a guest before create account!');
                    }
                    echo 'true';
                    //success
                } else {
                    system_log('failed to create account');
                    echo 'false';
                }
            }
        }
    }

    /**
     * Ajax function that sends emails to the user.
     * Email::send will return true if the email has been sent. false if it
     * didn't.
     */
    public function sendemailAction(){
        if ($this->getRequest()->isPost()) {
            $email = $_POST['username'];
            $body = $_POST['body'];
            $subject = $_POST['subject'];

            if(Email::send($email, $body, $subject)){
                echo 'true';

            } else {
                system_log('failed to send email');
                echo 'false';
            }
        }
    }

    /**
     * Ajax function that generates a new password and send email to the user.
     * It will create new password using uniqid() method.
     *
     * It checks for whether the user exists or not first.
     * If it does, ask the database to update the password with the new
     * password. After the database gets updated properly, it will send an
     * email to the user including the currently updated password.
     *
     * Will not send an email if the database failed to update. and echo false
     */
    public function newpwAction(){
        if ($this->getRequest()->isPost()) {
            $email = $_POST['username'];
            $pw = uniqid();
            $subject="Here is your new password";
            $body="Your password is now ".$pw.". Please log in with this password and change the password";

            if (!userExists($email)) {
                echo 'notexist';
            }
            else {
                if(changePassword($email, $pw)){
                    Email::send($email, $body, $subject);
                    echo 'true';
                }
                else{
                    echo 'false';
                }
            }
        }
    }

    /**
     * Ajax function that collect the search options and find out all the files available.
     */
    public function getallavailabledocsAction(){
        if ($this->getRequest()->isGet()) {
            $current_page = $_GET['current_page'];
            $items_per_page = $_GET['items_per_page'];
            if(isset($_GET['location'])){
                $loction = $_GET['location'];
            }
            if(isset($_GET['date'])){
                $date = $_GET['date'];
            }
            if(isset($_GET['keywords'])){
                $keywords = $_GET['keywords'];
            }

            $records = array();
            $item_ids = Incite_DocumentsController::populateViewSearchResults();

            if (count($item_ids) > 0 ) {
                $total_pages = ceil(count($item_ids) / $items_per_page);
                $data['total_pages'] = $total_pages;
                for ($i = ($current_page - 1) * $items_per_page; $i < $items_per_page * $current_page; $i++) {
                    if ($i >= count($item_ids)){
                        break;
                    }

                    $record = get_record_by_id('item', $item_ids[$i]);
                    $file = $record->getFile();

                    if($file != null){
                        $records[] = array('id' => $item_ids[$i],
                                'date' => trim(metadata($record, array('Dublin Core', 'Date'))),
                                'desc' => metadata($record, array('Dublin Core','Description')),
                                'name' => metadata($record, array('Dublin Core','Title')),
                                'loc' => metadata($record, array('Item Type Metadata', 'Location')),
                                'contr'=> metadata($record, array('Dublin Core', 'Contributor')),
                                'rights' =>metadata($record, array('Dublin Core', 'Rights')),
                                'src' => metadata($record, array('Dublin Core', 'Rights')),
                                'url'=> get_image_url_for_item($record, true),
                                'taskinfo'=>Incite_DocumentsController::populateProgress($item_ids[$i]),
                                'lat_long' => loc_to_lat_long(metadata($record, array('Item Type Metadata', 'Location'))));

                    }
                }
                $data['records'] = $records;
                echo json_encode($data);
            }else{
                echo 'false';
            }
        }
    }

    /**
     * Ajax function that collect the search options and find out all the files which is ready to be transcribed.
     */
    public function getdocsfortranscribeAction(){

        if ($this->getRequest()->isGet()) {
            $current_page = $_GET['current_page'];
            $items_per_page = $_GET['items_per_page'];
            if(isset($_GET['location'])){
                $loction = $_GET['location'];
            }
            if(isset($_GET['date'])){
                $date = $_GET['date'];
            }
            if(isset($_GET['keywords'])){
                $keywords = $_GET['keywords'];
            }

            $records = array();
            $item_ids = Incite_DocumentsController::populateTranscribeSearchResults();

            if (count($item_ids) > 0 ) {
                $total_pages = ceil(count($item_ids) / $items_per_page);
                $data['total_pages'] = $total_pages;
                for ($i = ($current_page - 1) * $items_per_page; $i < $items_per_page * $current_page; $i++) {
                    if ($i >= count($item_ids)){
                        break;
                    }

                    $record = get_record_by_id('item', $item_ids[$i]);
                    if ($record != null) {
                        $file = $record->getFile();
                        if($file != null) {
                            $records[] = array('id' => $item_ids[$i],
                                    'date' => trim(metadata($record, array('Dublin Core', 'Date'))),
                                    'desc' => metadata($record, array('Dublin Core','Description')),
                                    'name' => metadata($record, array('Dublin Core','Title')),
                                    'loc' => metadata($record, array('Item Type Metadata', 'Location')),
                                    'contr'=> metadata($record, array('Dublin Core', 'Contributor')),
                                    'rights' =>metadata($record, array('Dublin Core', 'Rights')),
                                    'src' => metadata($record, array('Dublin Core', 'Rights')),
                                    'url'=> get_image_url_for_item($record, true),
                                    'lat_long' => loc_to_lat_long(metadata($record, array('Item Type Metadata', 'Location'))));
                        }
                    }
                }
                $data['records'] = $records;
                echo json_encode($data);

            }else{
                echo 'false';
            }

        }
    }

    /**
     * Ajax function that collect the search options and find out all the files which is ready to be tagged.
     */
    public function getdocsfortagAction(){
        if ($this->getRequest()->isGet()) {
            $current_page = $_GET['current_page'];
            $items_per_page = $_GET['items_per_page'];
            if(isset($_GET['location'])){
                $loction = $_GET['location'];
            }
            if(isset($_GET['date'])){
                $date = $_GET['date'];
            }
            if(isset($_GET['keywords'])){
                $keywords = $_GET['keywords'];
            }
            $records = array();
            $item_ids = Incite_DocumentsController::populateTagSearchResults();

            if (count($item_ids) > 0 ) {
                $total_pages = ceil(count($item_ids) / $items_per_page);
                $data['total_pages'] = $total_pages;
                for ($i = ($current_page - 1) * $items_per_page; $i < $items_per_page * $current_page; $i++) {
                    if ($i >= count($item_ids)){
                        break;
                    }

                    $record = get_record_by_id('item', $item_ids[$i]);
                    $file = $record->getFile();

                    if($file != null){
                        $records[] = array('id' => $item_ids[$i],
                                'date' => trim(metadata($record, array('Dublin Core', 'Date'))),
                                'desc' => metadata($record, array('Dublin Core','Description')),
                                'name' => metadata($record, array('Dublin Core','Title')),
                                'loc' => metadata($record, array('Item Type Metadata', 'Location')),
                                'contr'=> metadata($record, array('Dublin Core', 'Contributor')),
                                'rights' =>metadata($record, array('Dublin Core', 'Rights')),
                                'src' => metadata($record, array('Dublin Core', 'Rights')),
                                'url'=> get_image_url_for_item($record, true),
                                'lat_long' => loc_to_lat_long(metadata($record, array('Item Type Metadata', 'Location'))));

                    }
                }
                $data['records'] = $records;
                echo json_encode($data);
            }else{
                echo 'false';
            }
        }
    }

    /**
     * Ajax function that collect the search options and find out all the files which is ready to be connected.
     */
    public function getdocsforconnectAction(){

        if ($this->getRequest()->isGet()) {
            $current_page = $_GET['current_page'];
            $items_per_page = $_GET['items_per_page'];
            if(isset($_GET['location'])){
                $loction = $_GET['location'];
            }
            if(isset($_GET['date'])){
                $date = $_GET['date'];
            }
            if(isset($_GET['keywords'])){
                $keywords = $_GET['keywords'];
            }

            $records = array();
            $item_ids = Incite_DocumentsController::populateConnectSearchResults();

            if (count($item_ids) > 0 ) {
                $total_pages = ceil(count($item_ids) / $items_per_page);
                $data['total_pages'] = $total_pages;
                for ($i = ($current_page - 1) * $items_per_page; $i < $items_per_page * $current_page; $i++) {
                    if ($i >= count($item_ids)){
                        break;
                    }

                    $record = get_record_by_id('item', $item_ids[$i]);
                    $file = $record->getFile();

                    if($file != null){
                        $records[] = array('id' => $item_ids[$i],
                                'date' => trim(metadata($record, array('Dublin Core', 'Date'))),
                                'desc' => metadata($record, array('Dublin Core','Description')),
                                'name' => metadata($record, array('Dublin Core','Title')),
                                'loc' => metadata($record, array('Item Type Metadata', 'Location')),
                                'contr'=> metadata($record, array('Dublin Core', 'Contributor')),
                                'rights' =>metadata($record, array('Dublin Core', 'Rights')),
                                'src' => metadata($record, array('Dublin Core', 'Rights')),
                                'url'=> get_image_url_for_item($record, true),
                                'lat_long' => loc_to_lat_long(metadata($record, array('Item Type Metadata', 'Location'))));
                    }
                }

                $data['records'] = $records;
                echo json_encode($data);
            }else{
                echo 'false';
            }

        }
    }

    /**
     * Ajax function that creates a new group, returns the new group's ID
     */
    public function creategroupAction() {
        if ($this->getRequest()->isPost()) {
            $groupName = $_POST['groupName'];
            $groupType = $_POST['groupType'];
            $user = $_SESSION['Incite']['USER_DATA'];

            $group = new InciteGroup;
            $group->name = $groupName;
            $group->creator_id = $user->id;
            $group->group_type = $groupType;
            $group->instructions = "";
            $group->save();

            $groups_users = new InciteGroupsUsers;
            $groups_users->user_id = $user->id;
            $groups_users->group_id = $group->id;
            $groups_users->group_privilege = 0;
            $groups_users->seen_instruction = 1;
            $groups_users->save();

            $groupId = $group->id;

            if ($groupId > 0) {
                echo $groupId;
            } else {
                system_log('failed to create group');
                echo 'false';
            }
        }
    }

    /**
     * Marks group instructions as seen by a user
     *
     */
    public function addseeninstructionsAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_SESSION['Incite']['USER_DATA']['id'];
            $groupId = $_POST['groupId'];
            $groupuser = $this->_helper->db->getTable('InciteGroupsUsers')->findGroupUserByUserAndGroupIds($userId, $groupId);

            if (isset($groupuser) && $groupuser->privilege >= 0) {
                $groupuser->seen_instruction = 1;
                $groupuser->save();
                return true;
            }

            return false;
        }
    }

    /**
     * Ajax function that searchs for groups with names similiar to the search term
     *
     * @return a list of group ids
     */
    public function searchgroupsAction() {
        if ($this->getRequest()->isPost()) {
            $groupName = $_POST['searchTerm'];

            $groups = $this->_helper->db->getTable('InciteGroup')->findGroupByPartialName($groupName);
            $groups_arr = array();
            foreach ((array) $groups as $group) {
                $groups_arr[] = array('id' => $group->id, 'name' => $group->name);
            }

            echo json_encode($groups_arr);
        }
    }

    /**
     * Ajax function sets a user's working group
     *
     * @return output of setWorkingGroup
     */
    public function setworkinggroupAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_SESSION['Incite']['USER_DATA']['id'];
            $groupId = $_POST['groupId'];

            if ($groupId == 0) {
                $user = $this->_helper->db->getTable("InciteUser")->findUserById($userId);
                $user->working_group_id = $groupId;
                unset($_SESSION['Incite']['USER_DATA']['working_group']);

                echo 'true';
            } else {
                $groupuser = $this->_helper->db->getTable('InciteGroupsUsers')->findGroupUserByUserAndGroupIds($userId, $groupId);
                if (isset($groupuser) && $groupuser->privilege >= 0) {
                    $user = $this->_helper->db->getTable("InciteUser")->findUserById($groupuser->user_id);
                    $user->working_group_id = $groupId;
                    $_SESSION['Incite']['USER_DATA']['working_group'] = $this->_helper->db->getTable('InciteGroup')->findGroupById($groupId);

                    echo 'true';

                } else {
                    echo 'false';
                }
            }
        }
    }

    /**
     * Ajax function that adds the currently logged in user to a group with the privilege specified
     * in the ajax request
     *
     * @return output of addGroupMember
     */
    public function requestgroupmembershipAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_SESSION['Incite']['USER_DATA']['id'];
            $groupId = $_POST['groupId'];
            $privilege = -1;

            $groupsUsers = new InciteGroupsUsers;
            $groupsUsers->group_id = $groupId;
            $groupsUsers->user_id = $userId;
            $groupsUsers->group_privilege = $privilege;
            $groupsUsers->seen_instruction = 0;
            $groupsUsers->save();

            echo json_encode('true');
        }
    }

    /**
     * Ajax function that adds the currently logged in user to a group with the privilege specified
     * in the ajax request
     *
     * @return output of addGroupMember
     */
    public function removeuserfromgroupAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_POST['userId'];
            $groupId = $_POST['groupId'];
            $group = $this->_helper->db->getTable('InciteGroup')->findGroupById($groupId);

            //prevent non group owners from changing people's privilege levels to banned or added
            if (isset($group) && ($_SESSION['Incite']['USER_DATA']['id'] == $userId || $_SESSION['Incite']['USER_DATA']['id'] == $group['creator_id'])) {
                $groupuser = $this->_helper->db->getTable("InciteGroupsUsers")->findGroupUserByUserAndGroupIds($userId, $groupId);
                if (isset($groupuser)) {
                    $groupuser->delete();
                }
                echo json_encode('true');
            }
            else {
                echo "false";
            } 
        }
    }

    /**
     * Ajax function that remove the selected group
     *
     * @return true/false
     */
    public function removeselectedgroupAction() {
        if ($this->getRequest()->isPost()) {
            $groupId = $_POST['groupId'];
            $group = getGroupInfoByGroupId($groupId);
            //prevent non group owners from changing people's privilege levels to banned or added
            if ($_SESSION['Incite']['USER_DATA']['id'] == $group['creator']['id']) {
                echo json_encode(removeGroup($groupId));
            }
            else {
                echo "false";
            }
        }
    }

    /**
     * Ajax function that updates the privilege of a member of a group
     *
     * @return output of changeGroupMemberPrivilege
     */
    public function changegroupmemberprivilegeAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_POST['userId'];
            $groupId = $_POST['groupId'];
            $privilege = $_POST['privilege'];
            $groupTable = $this->_helper->db->getTable("InciteGroup");
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');

            $group = $groupTable->findGroupById($groupId);
            $groupUser = $groupsUsersTable->findGroupUserByUserAndGroupIds($userId, $groupId);

            //prevent non group owners from changing people's privilege levels to banned or added
            if (($privilege == 0 || $privilege == -2) && $_SESSION['Incite']['USER_DATA']['id'] != $group['creator_id']) {
                echo false;
                return;
            }
            $groupUser->group_privilege = $privilege;
            $groupUser->save();

            echo json_encode('true');
        }
    }
    /**
     * Ajax function that sets the instructions for a group
     *
     * @return output of setGroupInstructions
     */
    public function setgroupinstructionsAction() {
        if ($this->getRequest()->isPost()) {
            $instructions = $_POST['instructions'];
            $groupId = $_POST['groupId'];
            $groupTable = $this->_helper->db->getTable('InciteGroup');
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');
        
            $group = $groupTable->findGroupById($groupId);

            //prevent non group owners from changing the instructions
            if ($_SESSION['Incite']['USER_DATA']['id'] != $group['creator_id']) {
                echo json_encode('false');
                return false;
            }
            $group->instructions = $instructions;
            $group->save();
            
            $groupsUsers = $groupsUsersTable->findGroupsUsersByGroupId($group->id);
            foreach ((array) $groupsUsers as $groupUser) {
                $groupUser->seen_instruction = 0;
                $groupUser->save();
            }

            echo json_encode('true');
        }
    }
    /**
     * Ajax function that gets the privilege of a group member
     *
     * @return output of getGroupMemberPrivilege
     */
    public function getgroupmemberprivilegeAction() {
        if ($this->getRequest()->isPost()) {
            $userId = $_POST['userId'];
            $groupId = $_POST['groupId'];
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');
            $group = $groupsUsersTable->findGroupUserByUserAndGroupIds($userId, $groupId);
            if (isset($group)) {
                $privilege = $group->group_privilege;
                echo json_encode($privilege);
            } else {
                echo json_encode('null');
            }

        }
    }

    /**
     * Logs a user out of the website. This kills the cookie
     */
    public function logoutAction() {
        $_SESSION['Incite'] = array();
        setup_session();
        die();
    }
    /**
     * This gets the data of a specific user
     */
    public function getdataAction() {
        echo json_encode($_SESSION['Incite']['USER_DATA']);
    }
    /**
     * This gets a comment from a specific user
     */
    public function postcommentAction() {
        if ($this->getRequest()->isPost())
        {
            $documentID = $_POST['documentId'];
            $text = $_POST['commentText'];
            $type = $_POST['type'];
            $userId = $_SESSION['Incite']['USER_DATA']['id'];

            $workingGroupId = 0;

            if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
                $workingGroupId = $_SESSION['Incite']['USER_DATA']['working_group']['id'];
            }
            $discussion = new InciteDiscussion;
            $discussion->user_id = $userId;
            $discussion->working_group_id = $workingGroupId;
            $discussion->discussion_text = $text;
            $discussion->discussion_type = $type;
            $discussion->is_active = 1;
            $discussion->save();

            $itemDiscussion = new InciteItemsDiscussions;
            $itemDiscussion->item_id = $documentID;
            $itemDiscussion->discussion_id = $discussion->id;
            $itemDiscussion->save();

            return true;
        }
    }

    /**
     * This saves comments to database
     */
    public function postreplyAction()
    {
        if ($this->getRequest()->isPost())
        {
            $text = $_POST['replyText'];
            $discussionId = $_POST['originalQuestionId'];
            $itemId = $_POST['documentId'];
            $userId = $_SESSION['Incite']['USER_DATA']['id'];

            $comment = new InciteComment;
            $comment->user_id = $userId;
            $comment->discussion_id = $discussionId;
            $comment->is_active = 1;
            $comment->comment_text = $text;
            $comment->save();
            return true;
        }
    }

    /**
     * This checks whether this is the first time that the user post
     */
    public function cmp($a, $b)
    {
        $firstime = strtotime($a['question_timestamp']);
        $secondtime = strtotime($b['question_timestamp']);
        if ($firstime == $secondtime)
        {
            return 0;
        }
        return ($firstime < $secondtime) ? -1 : 1;
    }

    /**
     * This returns comments of a document
     */
    public function getcommentsdocAction()
    {
        if ($this->getRequest()->isPost())
        {
            $text = array();
            $itemId = $_POST['documentId'];
            $itemTypes = array(0, 1, 2, 3); //transcribe, tag, connect and view
            $discussions = $this->_helper->db->getTable('InciteDiscussion')->findDiscussionsByItemIdWithTypes($itemId, $itemTypes);
            $counter = 0;
            for ($i = count($discussions) - 1; $i >= 0; $i--)
            {
                $text[$counter]['discussion_text'] = $discussions[$i]->discussion_text;
                $text[$counter]['discussion_type'] = $discussions[$i]->discussion_type;
                $text[$counter]['discussion_id'] = $discussions[$i]->id;
                $text[$counter]['discussion_timestamp'] = $discussions[$i]->timestamp_creation;

                $user = $this->_helper->db->getTAble('InciteUser')->findUserById($discussions[$i]->user_id);
                $user_arr = array($user->first_name, $user->last_name, $user->email, $user->privilege_level, $user->experience_level, $user->id);

                $text[$counter]['user_info'] = $user_arr;
                $text[$counter]['discussion_comments'] = array();
                $text[$counter]['discussion_comment_timestamps'] = array();
                $text[$counter]['discussion_comment_users'] = array();
                $comments = $this->_helper->db->getTable('InciteComment')->findCommentsByDiscussionId($discussions[$i]->id);
                for ($j = 0; $j < sizeof($comments); $j++)
                {
                    $text[$counter]['discussion_comments'][] = $comments[$j]->comment_text;
                    $text[$counter]['discussion_comment_timestamps'][] = $comments[$j]->timestamp_creation;
                    $user = $this->_helper->db->getTable('InciteUser')->findUserById($comments[$j]->user_id);
                    $user_arr = array($user->first_name, $user->last_name, $user->email, $user->privilege_level, $user->experience_level, $user->id);
                    $text[$counter]['discussion_comment_users'][] = $user_arr;
                }
                $counter++;
            }
            echo json_encode($text);
        }
    }

    /**
     * This checks whether the user has signed
     */
    public function issignedinAction()
    {
        if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID']) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /**
     * Ajax function that search the files with the specified keyword.
     */
    public function searchkeyword2Action()
    {
        //$documentID is item id
        $urlData = array();
        $x = getAllDocumentsContainKeyword($_POST['keyword']);
        $documentID = array_values(array_unique($x));
        for ($i = 0; $i < count($documentID); $i++)
        {
            $record = get_record_by_id('item', $documentID[$i]);
            $file = $record->getFile();
            if ($file != null)
            {
                $urlData[] = array('uri' => $file->getProperty('uri'), 'id' => $documentID[$i], 'description' => metadata($record, array('Dublin Core', 'Description')), 'title' => metadata($record, array('Dublin Core', 'Title')));
            }
        }
        echo json_encode($urlData);
    }
}
