<?php


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
            $_SESSION['Incite']['IS_LOGIN_VALID'] = false;
            $_SESSION['Incite']['Guest'] = true;
            $_SESSION['Incite']['USER_DATA'] = $user;
        } else {
            system_log('failed to create a guest account');
        }
    }
} 



?>
