<?php

require_once("Incite_Users_Table.php");


/**
 * Set up a new guest session if there is no existing session for Incite.
 */
function setup_session() {
    if (!isset($_SESSION))
        session_start();

    if (!isset($_SESSION['Incite']['USER_DATA'])) {
        $id = generateUniqueUserId();
        $username = "guest".$id; //guest12345678900
        $password = "";
        $firstName = "guest";
        $lastName = "guest";
        $priv = 0;
        $exp = 0;
        if (createAccount($username, $password, $firstName, $lastName, $priv, $exp) != "failure") {
            $_SESSION['Incite']['IS_LOGIN_VALID'] = false;
            $_SESSION['Incite']['Guest'] = true;
            $_SESSION['Incite']['USER_DATA'] = getUserData($username);
        } else {
            system_log('failed to create a guest account');
        }
    }
} 



?>
