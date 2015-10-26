<?php

require_once("DB_Connect.php");
    /**
     * Verify the username and password combination. Use a prepare object to
     * sanitize user-submitted text. Fetch the number of times where the username
     * and password combination match (Should be exactly 1)
     * If a 1 is returned, then the username and password combination worked,
     * 0 means it did not work --> return false
     * @param type $email to verify the account with (username)
     * @param type $password to verify the account with
     * @return boolean true if login successful, false otherwise
     */
    function verifyUser($email, $password)
    {
        $count = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 1)
            return true;
        return false;
        
    }
    /**
     * Gets information about the user in an array
     * array format = [ID, FIRSTNAME, LASTNAME, PRIVILEGE_LEVEL, EXPERIENCE_LEVEL, GROUP_ID]
     * @param type $email requires an email to check against
     * @return array containing information requested
     */
    function getUserData($email)
    {
        $arr = Array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, first_name, last_name, privilege_level, experience_level, group_id FROM omeka_incite_users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($result);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($data = $result->fetch_assoc())
        {
            $arr[] = $data;
        }
        $stmt->close();
        $db->close();
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
        $count = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT is_active FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($count == 1)
            return true;
        return false;
    }
    
    function changePassword($email, $newPassword)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE omeka_incite_users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $newPassword, $email);
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
    function createAccount($email, $password, $firstName, $lastName, $privilege, $experienceLevel)
    {
        $count = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->bind_result($count);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        if ($count == 0)
        {
            $stmt = $db->prepare("INSERT INTO omeka_incite_users VALUES (AUTO_INCREMENT, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP");
            $stmt->bind_param("ssssiii", $firstName, $lastName, $email, $password, $privilege, $experienceLevel);
            $stmt->execute();
            $stmt->close();
            return "SUCCESS!";
        }
        else
        {
            return "ERROR: ACCOUNT ALREADY EXISTS!";
        }
    }
    function removeAccount($userID)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("DELETE FROM omeka_incite_users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
?>
