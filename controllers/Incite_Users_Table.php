<?php
/**
 * API for Incite_Users_Table
 */
require_once("DB_Connect.php");
require_once("Incite_Groups_Table.php");
    /**
     * Verify the username and password combination. Use a prepare object to
     * sanitize user-submitted text. Fetch the number of times where the username
     * and password combination match (Should be exactly 1)
     * If a 1 is returned, then the username and password combination worked,
     * 0 means it did not work --> return false
     * Account must be active in order to login
     * @param type $email to verify the account with (username)
     * @param type $password to verify the account with
     * @return boolean true if login successful, false otherwise
     */
    function verifyUser($email, $password)
    {
        $count = 0;
        $hashedPassword = md5($password);
        //var_dump($email);
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_users WHERE email = ? AND password = ? AND is_active = 1");
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 1)
            return true;
        else if ($count == 0)
            return false;
        else {
            system_log('Incite_Users_Table:verifyUser:wrong $count @line: '.__LINE__.' in '.__FILE__.'.');
        }
        
    }
    function userExists($email)
    {
        $count = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 1)
            return true;
        else if ($count == 0)
            return false;
        else {
            system_log('Incite_Users_Table:userExists:wrong $count @line: '.__LINE__.' in '.__FILE__.'.');
        }
        
    }
    /**
     * Gets information about the user in an array
     * array format = [ID, FIRSTNAME, LASTNAME, PRIVILEGE_LEVEL, EXPERIENCE_LEVEL, WORKING_GROUP]
     * @param type $email requires an email to check against
     * @return array containing information requested
     */
    function getUserData($email)
    {
        $arr = Array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, first_name, last_name, privilege_level, experience_level, working_group_id FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($id, $firstname, $lastname, $priv, $exp, $groupId);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        
        $arr['id'] = $id;
        $arr['first_name'] = $firstname;
        $arr['last_name'] = $lastname;
        $arr['privilege'] = $priv;
        $arr['experience'] = $exp;
        $arr['email'] = $email;
        $arr['working_group'] = getGroupInfoByGroupId($groupId);
        return $arr;
    }

   
    function getUserDataOld($email)
    {
        $arr = Array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, first_name, last_name, privilege_level, experience_level FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($id, $firstname, $lastname, $priv, $exp);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        
        $arr[0] = $id;
        $arr[1] = $firstname;
        $arr[2] = $lastname;
        $arr[3] = $priv;
        $arr[4] = $exp;
        return $arr;
    }
    /**
     * Gets information about the user in an array
     * array format = [ID, FIRSTNAME, LASTNAME, PRIVILEGE_LEVEL, EXPERIENCE_LEVEL]
     * @param type $id requires an id to check against
     * @return array containing information requested
     */
    function getUserDataID($id)
    {
        $arr = Array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT first_name, last_name, email, privilege_level, experience_level FROM omeka_incite_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($firstname, $email, $lastname, $priv, $exp);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        
        $arr[0] = $firstname;
        $arr[1] = $lastname;
        $arr[2] = $email;
        $arr[3] = $priv;
        $arr[4] = $exp;
        $arr[5] = $id;
        return $arr;
    }
    function getUserDataByUserId($id)
    {
        $arr = Array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT first_name, last_name, email, privilege_level, experience_level FROM omeka_incite_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($firstname, $lastname, $email, $priv, $exp);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        
        $arr['first_name'] = $firstname;
        $arr['last_name'] = $lastname;
        $arr['email'] = $email;
        $arr['privilege'] = $priv;
        $arr['experience'] = $exp;
        $arr['id'] = $id;
        return $arr;
    }
    /**
     * Check if the user is active. If the user is inactive, return false else
     * return true. We check this by selecting the 'is_active' column in the
     * database
     * @param type $email of the user to check if active
     * @return boolean true if active, false otherwise
     */
    function isUserActive($email)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT is_active FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 1)
        {
            return true;
        }
        return false;
    }
    /**
     * Change a user's password
     * @param string $email associated with account
     * @param string $newPassword to set for the account
     * @return boolean true if it worked, false otherwise
     */
    function changePassword($email, $newPassword)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", md5($newPassword), $email);
        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
    }

/**
* Update the user's profile information.  
* @param int $id is the user's id number in the database
* @param string $username is the username, email
* @param string password is the user's new password. It will be hashed 
* before the update
* @param string $firstname is user's first name
* @param string $lastname is user's last name
* @return true if successfully updated, false if not.
*/
    function editAccount($id, $username, $password, $firstName, $lastName){

        $db = DB_Connect::connectDB();
        $hashedPassword = md5($password);
        $stmt = $db->prepare("UPDATE omeka_incite_users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $username, $hashedPassword, $id);

        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
    }
    /**
     * Upgrade or Downgrade user's experience level
     * @param string $email associated with account
     * @param int $experienceLevel to change to 
     * @return boolean true if successful, false otherwise
     */
    function changeExperienceLevel($email, $experienceLevel)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET experience_level = ? WHERE email = ?");
        $stmt->bind_param("ss", $experienceLevel, $email);
        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
    }
    /**
     * Set a user's working group
     *
     * @param int $userId the id of the user
     * @param int $groupId the id of the working group to change to
     * @return boolean true if successful, false otherwise
     */
    function setWorkingGroup($userId, $groupId)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET working_group_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $groupId, $userId);
        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
    }
    /*
     * REMOVE AND ADD TO API FOR GROUPS
    public function addGroupID($userID, $groupID, $privilege)
    {
        $count = 0;
        $db = parent::getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_group_members WHERE user_id = ? AND group_id = ?");
        $stmt->bind_param("ii", $userID, $groupID);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 0)
        {
            $db = parent::getDB();
            $stmt = $db->prepare("INSERT INTO omeka_incite_group_members VALUES (AUTO_INCREMENT, ?, ?, ?)");
        $stmt->bind_param("iii", $userID, $groupID, $privilege);
        $stmt->execute();
        $stmt->close();
        $db->close();
        }
        else
        {
            var_dump("ERROR: You are already added");
        }        
    }
    public function removeGroupID($userID, $groupID)
    {
        
        
    }
     *
     */
    /**
     * Safe way to 'remove' an account by setting it's active status to '0'
     * @param string $email associated with the account to deactivate
     * @return boolean true if worked, false otherwise 
     */
    function deactivateAccount($email)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET is_active = 0 WHERE email = ?");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
        
    }
    /**
     * If account is inactive, reactivate the account
     * @param string $email associated with the account to activate
     * @return boolean true if successful, false otherwise (could be account
     * does not exist)
     */
    function reactivateAccount($email)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET is_active = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute())
        {
            var_dump($stmt->error);
            $stmt->close();
            $db->close();
            return false;
        }
        $stmt->close();
        $db->close();
        return true;
    }
    /**
     * Create a new account. If the same email exists for another account, an
     * error will be thrown
     * @param string $email to create account
     * @param string $password desired password
     * @param string $firstName first name
     * @param string $lastName last name
     * @param string $privilege type of user
     * @param int $experienceLevel user experience level
     * @return string "Success" or "Failure"
     */
    function createAccount($email, $password, $firstName, $lastName, $privilege, $experienceLevel)
    {
        $count = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_users WHERE UPPER(email) = UPPER(?)");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        if ($count == 0)
        {
            $hashedPassword = md5($password);
            $stmt = $db->prepare("INSERT INTO omeka_incite_users VALUES (NULL, ?, ?, ?, ?, ?, ?, 1, 0, CURRENT_TIMESTAMP)");
            $stmt->bind_param("ssssii", $firstName, $lastName, $email, $hashedPassword, $privilege, $experienceLevel);
            $stmt->execute();
            $stmt->close();
            return "success";
        }
        else
        {
            return "failure";
        }
    }
    /**
     * Remove account from database
     * WARNING! This will have cascading errors if used improperly
     * @param type $userID to remove from the database
     */
    function removeAccount($userID)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("DELETE FROM omeka_incite_users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
    /**
     * Generate an 11 digit random user id. Used only for making an 
     * anonymous cookie for unlogged-in users
     * @return int 11 digit random number
     */
    function generateRandomUserId()
    {
        $temp = "";
        for ($i = 0; $i < 11; $i++)
        {
            $temp .= rand(0, 9);
        }
        return (int)$temp;
    }
    /**
     * Generate a unique string by timestamps (microsecond). E.g. 0.738238001453434310
     * anonymous cookie for unlogged-in users
     * @return a 20-digit random string
     */
    function generateUniqueUserId()
    {
        return implode(explode(" ", microtime()));
    }
    
    function createGuestSession()
    {
        $id = generateRandomUserId();
        $username = "guest".$id; //guest12345678900
        $password = "";
        $firstName = "guest";
        $lastName = "guest";
        $priv = 0;
        $exp = 0;
        if (createAccount($username, $password, $firstName, $lastName, $priv, $exp) != "failure") {
                //destroy previous session and then map it to the new session ==> store in new table
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['Incite']['IS_LOGIN_VALID'] = false;
                $_SESSION['Incite']['Guest'] = true;
                $_SESSION['Incite']['USER_DATA'] = getUserData($username);
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
    }
    /**
     * Maps guest and user accounts to make sure work is always related to a real
     * user
     * The data in this table should be unique
     * @param type $guestID to map the user id to
     * @param type $userID to map the guest id to
     */
    function mapAccounts($guestID, $userID)
    {
        $db = DB_Connect::connectDB();
        $isNewEntry = true;
        $checkStmt = $db->prepare("SELECT * FROM omeka_incite_users_map");
        $checkStmt->bind_result($tempUser, $tempGuest);
        $checkStmt->execute();
        while ($checkStmt->fetch())
        {
            if ($tempGuest == $guestID && $tempUser == $userID)
            {
                $isNewEntry = false;
                break;
            }
        }
        $checkStmt->close();
        $db->close();
        if ($isNewEntry)
        {
            $db = DB_Connect::connectDB();
            $stmt = $db->prepare("INSERT INTO omeka_incite_users_map VALUES (?, ?)");
            $stmt->bind_param("ii", $userID, $guestID);
            $stmt->execute();
            $stmt->close();
            $db->close();
        }
    }

    function getAllUsers() {
        $users = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, first_name, last_name FROM omeka_incite_users");
        $stmt->bind_result($id, $fname, $lname);
        $stmt->execute();
        while ($stmt->fetch()) {
            $users[] = array('id' => $id, 'first_name' => $fname, 'last_name' => $lname);
        }
        $stmt->close();
        $db->close();
        return $users;
    }

    function getAllActiveUsers() {
        $users = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, first_name, last_name FROM omeka_incite_users WHERE is_active = 1");
        $stmt->bind_result($id, $fname, $lname);
        $stmt->execute();
        while ($stmt->fetch()) {
            $users[] = array('id' => $id, 'first_name' => $fname, 'last_name' => $lname);
        }
        $stmt->close();
        $db->close();
        return $users;
    }


    function getTranscribedDocumentsByUserId($userid) {
        $docs = array();
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT document_id, timestamp_creation, text, working_group_id from omeka_incite_transcriptions INNER JOIN omeka_element_texts ON document_id = record_id WHERE element_id = ? AND user_id = ? GROUP BY document_id");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($doc, $time, $doc_title, $working_group_id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $docs[] = array('time' => $time, 'document_id' => $doc, 'document_title' => $doc_title, 'working_group_id' => $working_group_id);
        }
        $db->close();
        return $docs;
    }

    function getTranscribedDocumentCountByUserId($userid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT document_id) from omeka_incite_transcriptions INNER JOIN omeka_element_texts ON document_id = record_id WHERE element_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getTranscribedDocumentCountByUserIdAndGroupId($userid, $groupid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT document_id) from omeka_incite_transcriptions INNER JOIN omeka_element_texts ON document_id = record_id WHERE element_id = ? AND user_id = ? AND working_group_id = ?");
        $stmt->bind_param("iii", $element_id_for_title, $userid, $groupid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getTaggedDocumentsByUserId($userid) {
        $docs = array();
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT DISTINCT item_id, created_timestamp, text, working_group_id FROM omeka_incite_documents doc, omeka_incite_documents_tags_conjunction tag_con, omeka_incite_tags tag, omeka_element_texts WHERE doc.id = tag_con.document_id AND tag_con.tag_id = tag.id AND item_id = record_id AND element_id = ? AND tag.user_id = ?");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($doc, $time, $doc_title, $working_group_id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $docs[] = array('time' => $time, 'document_id' => $doc, 'document_title' => $doc_title, 'working_group_id' => $working_group_id);
        }
        $db->close();
        return $docs;
    }

    function getTaggedDocumentCountByUserId($userid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT item_id, created_timestamp, text) FROM omeka_incite_documents doc, omeka_incite_documents_tags_conjunction tag_con, omeka_incite_tags tag, omeka_element_texts WHERE doc.id = tag_con.document_id AND tag_con.tag_id = tag.id AND item_id = record_id AND element_id = ? AND tag.user_id = ?");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

     function getTaggedDocumentCountByUserIdAndGroupId($userid, $groupid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT item_id, created_timestamp, text) FROM omeka_incite_documents doc, omeka_incite_documents_tags_conjunction tag_con, omeka_incite_tags tag, omeka_element_texts WHERE doc.id = tag_con.document_id AND tag_con.tag_id = tag.id AND item_id = record_id AND element_id = ? AND tag.user_id = ? AND tag.working_group_id = ?");
        $stmt->bind_param("iii", $element_id_for_title, $userid, $groupid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getConnectedDocumentsByUserId($userid) {
        $docs = array();
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT DISTINCT item_id, created_time, text, working_group_id from omeka_incite_documents_subject_conjunction sub, omeka_element_texts, omeka_incite_documents doc WHERE doc.id = document_id AND item_id = record_id AND element_id = ? AND sub.user_id = ?");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($doc, $time, $doc_title, $working_group_id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $docs[] = array('time' => $time, 'document_id' => $doc, 'document_title' => $doc_title, 'working_group_id' => $working_group_id);
        }
        $db->close();
        return $docs;
    }

    function getConnectedDocumentCountByUserId($userid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT item_id, created_time, text) from omeka_incite_documents_subject_conjunction sub, omeka_element_texts, omeka_incite_documents doc WHERE doc.id = document_id AND item_id = record_id AND element_id = ? AND sub.user_id = ?");
        $stmt->bind_param("ii", $element_id_for_title, $userid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getConnectedDocumentCountByUserIdAndGroupId($userid, $groupid) {
        $db = DB_Connect::connectDB();
        $element_id_for_title = 50;
        $stmt = $db->prepare("SELECT COUNT(DISTINCT item_id, created_time, text) from omeka_incite_documents_subject_conjunction sub, omeka_element_texts, omeka_incite_documents doc WHERE doc.id = document_id AND item_id = record_id AND element_id = ? AND sub.user_id = ? AND sub.working_group_id = ?");
        $stmt->bind_param("iii", $element_id_for_title, $userid, $groupid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getDiscussionsByUserId($userid) {
        $diss = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, timestamp, question_text, working_group_id from omeka_incite_questions WHERE user_id = ? AND question_type = 4");
        $stmt->bind_param("i", $userid);
        $stmt->bind_result($dis, $time, $dis_title, $working_group_id);
        $stmt->execute();
        while ($stmt->fetch()) {
            $diss[] = array('time' => $time, 'discussion_id' => $dis, 'discussion_title' => $dis_title, 'working_group_id' => $working_group_id);
        }
        $db->close();
        return $diss;
    }

    function getDiscussionCountByUserId($userid) {
        $diss = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) from omeka_incite_questions WHERE user_id = ? AND question_type = 4");
        $stmt->bind_param("i", $userid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getDiscussionCountByUserIdAndGroupId($userid, $groupid) {
        $diss = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) from omeka_incite_questions WHERE user_id = ? AND working_group_id = ? AND question_type = 4");
        $stmt->bind_param("ii", $userid, $groupid);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        return $count;
    }

    function getGroupsByUserId($userid) {
        $groups = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT group_id from omeka_incite_group_members WHERE user_id = ? AND privilege = 0");
        $stmt->bind_param("i", $userid);
        $stmt->bind_result($group);
        $stmt->execute();
        while ($stmt->fetch()) {
            $group_info = getGroupInfoByGroupId($group);
            $groups[] = $group_info;
        }
        $db->close();
        return $groups;
    }
    function getGroupInstructionsSeenByUserId($userid) {
        $groups = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT group_id from omeka_incite_group_instructions_seen_by WHERE user_id = ?");
        $stmt->bind_param("i", $userid);
        $stmt->bind_result($group);
        $stmt->execute();
        while ($stmt->fetch()) {
            $groups[] = $group;
        }
        $db->close();
        return $groups;
    }
?>
