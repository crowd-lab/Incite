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
    require_once("Incite_Users_Table.php");
    require_once("Incite_Replies_Table.php");
    require_once("Incite_Questions_Table.php");
    require_once("DiscoverController.php");
    require_once("DocumentsController.php");
    require_once("Incite_Search.php");
    require_once("Incite_Tag_Table.php");
    require_once("Incite_Transcription_Table.php");
    require_once("Incite_Document_Table.php");
    require_once("Incite_System_Log.php");
    require_once("Incite_Subject_Concept_Table.php");
    require_once("Incite_Session.php");
    require_once("Incite_Env_Setting.php");
    require_once("DB_Connect.php");
    require_once("Email.php");
    require_once('finediff.php');
    require_once("Incite_Assessment_Table.php");

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
      if (verifyUser($username, $password))
      {
        //If there is already a guest session, then combine the guest session with the verified user
        if (isset($_SESSION['Incite']['Guest']) && $_SESSION['Incite']['Guest'] == true) {
          $guestID = $_SESSION['Incite']['USER_DATA']['id'];
          $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
          $_SESSION['Incite']['Guest'] = false;
          $_SESSION['Incite']['USER_DATA'] = getUserData($username);
          mapAccounts($guestID, $_SESSION['Incite']['USER_DATA']['id']);
        } else {
          system_log('not a guest before login!');
        }
        echo 'true';
      }
      else
      {
        echo 'false';
      }
    }
  }
  public function loginoldAction() {
    if ($this->getRequest()->isPost()) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $isGuest = false;
      $guestID = -1;
      if (isset($_SESSION['Incite']) && isset($_SESSION['Incite']['USER_DATA']) && strpos($_SESSION['Incite']['USER_DATA'][1], "guest") !== false)
      {
        //link guest and user accounts
        $isGuest = true;
        $guestID = $_SESSION['Incite']['USER_DATA'][0];
      }
      if (verifyUser($username, $password))
      {
        if (!isset($_SESSION))
        {
          session_start();
        }
        $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
        $_SESSION['Incite']['USER_DATA'] = getUserData($username);
        if ($isGuest)
        {
          mapAccounts($guestID, $_SESSION['Incite']['USER_DATA'][0]);
        }
        echo json_encode(true);
      }
      else
      {
        echo json_encode(false);
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


      if (editAccount($id, $password, $firstName, $lastName))
      {
        $_SESSION['Incite']['USER_DATA']['first_name'] = $firstName;
        echo 'true';
      }
      else
      {
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
      if (userExists($username)) {
        echo 'exists';
      } else if (createAccount($username, $password, $firstName, $lastName, $priv, $exp) != "failure") {
        if (isset($_SESSION['Incite']['Guest']) && $_SESSION['Incite']['Guest'] == true) {
          $guestID = $_SESSION['Incite']['USER_DATA']['id'];
          $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
          $_SESSION['Incite']['Guest'] = false;
          $_SESSION['Incite']['USER_DATA'] = getUserData($username);
          mapAccounts($guestID, $_SESSION['Incite']['USER_DATA']['id']);
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

  public function getcurrpageAction(){
    // $page = getCurrentPage();
    if ($this->getRequest()->isGet()) {
      // if(isSearchQuerySpecifiedViaGet()){
      $page = getCurrentPage();
      // $page = 1;
      echo $page;


    }
    else{
      echo 'false';
    }

  }
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
          // $records[] = $this->_helper->db->find($item_ids[$i]);
          $records[] = array('id' => $item_ids[$i],
          'date' => trim(metadata($record, array('Dublin Core', 'Date'))),
          'desc' => metadata($record, array('Dublin Core','Description')),
          'name' => metadata($record, array('Dublin Core','Title')),
          'loc' => metadata($record, array('Item Type Metadata', 'Location')),
          'contr'=> metadata($record, array('Dublin Core', 'Contributor')),
          'rights' =>metadata($record, array('Dublin Core', 'Rights')),
          'src' => metadata($record, array('Dublin Core', 'Rights')),
          'url'=> get_image_url_for_item($record, true),
          'taskinfo'=>getTaskCompletionInfoFor($item_ids[$i]),
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
                // $records[] = $this->_helper->db->find($document_ids[$i]);
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
            // $records[] = $this->_helper->db->find($item_ids[$i]);
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
            // $records[] = $this->_helper->db->find($item_ids[$i]);
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

      $groupId = createGroup($groupName, $_SESSION['Incite']['USER_DATA']['id'], $groupType);

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
  */
  public function addseeninstructionsAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_POST['userId'];
      $groupId = $_POST['groupId'];

      markInstructionAsSeenByUser($userId, $groupId);

      return true;
    }
  }
  /**
  * Ajax function that searchs for groups with names similiar to the search term
  *
  * Returns a list of group ids
  */
  public function searchgroupsAction() {
    if ($this->getRequest()->isPost()) {
      $groupName = $_POST['searchTerm'];

      $groups = searchGroupsByName($groupName);

      echo json_encode($groups);
    }
  }
  /**
  * Ajax function sets a user's working group
  *
  * Returns output of setWorkingGroup
  */
  public function setworkinggroupAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_POST['userId'];
      $groupId = $_POST['groupId'];

      if (setWorkingGroup($userId, $groupId)) {
        $_SESSION['Incite']['USER_DATA']['working_group'] = getGroupInfoByGroupId($groupId);

        echo 'true';

      } else {
        echo 'false';
      }
    }
  }
  /**
  * Ajax function that adds the currently logged in user to a group with the privilege specified
  * in the ajax request
  *
  * Returns output of addGroupMember
  */
  public function addgroupmemberAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_SESSION['Incite']['USER_DATA']['id'];
      $groupId = $_POST['groupId'];
      $privilege = $_POST['privilege'];

      echo json_encode(addGroupMember($userId, $groupId, $privilege));
    }
  }
  /**
  * Ajax function that adds the currently logged in user to a group with the privilege specified
  * in the ajax request
  *
  * Returns output of addGroupMember
  */
  public function removememberfromgroupAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_POST['userId'];
      $groupId = $_POST['groupId'];
      $group = getGroupInfoByGroupId($groupId);

      //prevent non group owners from changing people's privilege levels to banned or added
      if ($_SESSION['Incite']['USER_DATA']['id'] == $userId || $_SESSION['Incite']['USER_DATA']['id'] == $group['creator']['id']) {
        echo json_encode(removeMemberFromGroup($userId, $groupId));
      }
      else {
        echo "false";
      }

      
    }
  }


/**
  * Ajax function that remove the selected group
  *
  * Returns true/false
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
  * Returns output of changeGroupMemberPrivilege
  */
  public function changegroupmemberprivilegeAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_POST['userId'];
      $groupId = $_POST['groupId'];
      $privilege = $_POST['privilege'];
      $group = getGroupInfoByGroupId($groupId);

      //prevent non group owners from changing people's privilege levels to banned or added
      if (($privilege == 0 || $privilege == -2) && $_SESSION['Incite']['USER_DATA']['id'] != $group['creator']['id']) {
        echo false;
        return;
      }

      echo json_encode(changeGroupMemberPrivilege($userId, $groupId, $privilege));
    }
  }
  /**
  * Ajax function that sets the instructions for a group
  *
  * Returns output of setGroupInstructions
  */
  public function setgroupinstructionsAction() {
    if ($this->getRequest()->isPost()) {
      $instructions = $_POST['instructions'];
      $groupId = $_POST['groupId'];
      $group = getGroupInfoByGroupId($groupId);

      //prevent non group owners from changing the instructions
      if ($_SESSION['Incite']['USER_DATA']['id'] != $group['creator']['id']) {
        return false;
      }

      echo json_encode(setGroupInstructions($groupId, $instructions));
      echo json_encode(markGroupInstructionsAsNew($groupId));
    }
  }
  /**
  * Ajax function that gets the privilege of a group member
  *
  * Returns output of getGroupMemberPrivilege
  */
  public function getgroupmemberprivilegeAction() {
    if ($this->getRequest()->isPost()) {
      $userId = $_POST['userId'];
      $groupId = $_POST['groupId'];

      echo json_encode(getGroupMemberPrivilege($userId, $groupId));
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
  public function createaccountoldAction() {
    if ($this->getRequest()->isPost()) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $firstName = $_POST['fName'];
      $lastName = $_POST['lName'];
      $priv = $_POST['priv'];
      $exp = $_POST['exp'];
      $isGuest = false;
      if (isset($_SESSION['Incite']) && isset($_SESSION['Incite']['USER_DATA']) && strpos($_SESSION['Incite']['USER_DATA'][1], "guest") !== false)
      {
        //link guest and user accounts
        $isGuest = true;
        $guestID = $_SESSION['Incite']['USER_DATA'][0];
      }
      if (createAccount($username, $password, $firstName, $lastName, $priv, $exp) != "failure") {
        //destroy previous session and then map it to the new session ==> store in new table
        if (!isset($_SESSION))
        {
          session_start();
        }
        $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
        $_SESSION['Incite']['USER_DATA'] = getUserData($username);
        if ($isGuest)
        {
          mapAccounts($guestID, $_SESSION['Incite']['USER_DATA'][0]);
        }
        echo json_encode(true);
      } else {
        echo json_encode(false);
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

      $workingGroupId = 0;

      if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
        $workingGroupId = $_SESSION['Incite']['USER_DATA']['working_group']['id'];
      }

      createQuestion($text, $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, array($documentID), $type);
      return true;
    }
  }
  public function postreplyAction()
  {
    if ($this->getRequest()->isPost())
    {
      $text = $_POST['replyText'];
      $questionID = $_POST['originalQuestionId'];
      $documentID = $_POST['documentId'];
      replyToQuestion($text, $_SESSION['Incite']['USER_DATA']['id'], $questionID, $documentID);
      return true;
    }
  }
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
      $documentID = $_POST['documentId'];
      $questionIDs = pullQuestionsForDocumentOnly($documentID);
      $counter = 0;
      for ($i = sizeof($questionIDs) - 1; $i >= 0; $i--)
      {
        $text[$counter]['question_text'] = getQuestionText($questionIDs[$i]);
        $text[$counter]['question_type'] = getQuestionType($questionIDs[$i]);
        $text[$counter]['question_id'] = $questionIDs[$i];
        $text[$counter]['question_timestamp'] = getQuestionTimestamp($questionIDs[$i]);
        $text[$counter]['user_info'] = getUserDataID(getQuestionUser($questionIDs[$i]));
        $text[$counter]['question_replies'] = array();
        $text[$counter]['question_replies_timestamp'] = array();
        $text[$counter]['question_replies_user'] = array();
        for ($j = 0; $j < sizeof(getAllRepliesForQuestion($questionIDs[$i])); $j++)
        {
          $allReplies = getAllRepliesForQuestion($questionIDs[$i]);
          $text[$counter]['question_replies'][] = getReplyText($allReplies[$j]);
          $text[$counter]['question_replies_timestamp'][] = getReplyTimeStamp($allReplies[$j]);
          $text[$counter]['question_replies_user_data'][] = getUserDataID(getUserIdForReply($allReplies[$j]));
        }
        $counter++;
      }
      echo json_encode($text);
    }
  }
  public function issignedinAction()
  {
    $array = array();
    $array[0] = (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID']);
    if (isset($_POST['loopVar']))
    {
      $array[1] = $_POST['loopVar'];
      $array[2] = $_POST['commentArray'];
      $array[3] = $_POST['format'];
    }
    echo json_encode($array);
  }

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
  public function searchkeyword22Action()
  {
    //$documentID is item id
    $urlData = array();
    $x = getAllDocumentsContainKeyword($_POST['keyword']);
    $docs_w_trans = getDocumentsWithApprovedTranscription();
    $documentID = array_values(array_intersect(array_values(array_unique($x)), $docs_w_trans));
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
  public function finddiffAction()
  {
    if ($this->getRequest()->isPost()) {
      $FROM = $_POST['userTranscription'];
      $TO = "THE FOURTH OF JULY AT SHREVEPORT – We learn from the Southwestern that it is the purpose of the military companies there to celebrate the Fourth of July by a general review, grand parade and dinner. It says:
The Yankees have robbed us of too much already. We have no idea of giving up the national anniversary—not a bit of it. The Fourth of July is ours. The declaration of independence declared and reiterated the doctrine for which we are to-day fighting. It was drafted by a southern man and advocated by Washington and a host of other southern heroes. The Shreveport Sentinels have appointed a committee to consult with similar committees to be appointed by the artillery company—the Summer Grove cavalry and the Keachi company, for the purpose of carrying out this laudable purpose. Long live the Confederacy, and huzza for the old Fourth of July.
";
      $diff = new FineDiff($FROM, $TO, FineDiff::$wordGranularity);
      $htmlDiff = $diff->renderDiffToHTML();
      $htmlDiff = html_entity_decode($htmlDiff, ENT_QUOTES, 'UTF-8');
      
      echo $htmlDiff;
    }
  }

  public function savetransAction() {
    //$workingGroupId = $this->getWorkingGroupID();
    if ($this->getRequest()->isPost()) {
      $assessID = 731;
      $userID = $_SESSION['Incite']['USER_DATA']['id'];
      $groupID = 0;
      createTrans($assessID, $userID, $groupID, $_POST['transcription'], $_POST['summary'], $_POST['tone']);
    }
  }

  public function uploadtagsAction() {
    if ($this->getRequest()->isPost()) {
      $entities = $_POST['entities'];
      $question_arr = $_POST['questions'];
      $assessID = 731;
      $workingGroupId = getWorkingGroupID();
      $index = findTranscriptionId($assessID, $_SESSION['Incite']['USER_DATA']['id']);
      $taggedID = saveTaggedTranscription($assessID, $index, $_SESSION['Incite']['USER_DATA']['id'], 0, $_POST['tagged_doc']);
      for ($i = 0; $i < sizeof($entities); $i++) {
        createTag($_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $entities[$i]['entity'], $entities[$i]['category'], $entities[$i]['subcategory'], $entities[$i]['details'], $assessID, $taggedID, 3);
      }
      
      for ($i = 0; $i < sizeof($question_arr); $i++) {
        saveQuestions($taggedID, $i + 1, $question_arr[$i + 1], 3);
      }
    }
  }



  public function uploadratingsAction() {
    if ($this->getRequest()->isPost()) {
      $ratings = $_POST['ratings'];
      $assessID = 731;
      $tagged_tran_id = findTaggedTransIDFromGoldStandard($assessID);
      $workingGroupId = getWorkingGroupID();
      for ($i = 0; $i < sizeof($ratings); $i++) {
        addConnectRating($_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $ratings[$i]['concept_id'], $ratings[$i]['rank'], $assessID, 3, $tagged_tran_id);
      }

      
    }
  }

}
