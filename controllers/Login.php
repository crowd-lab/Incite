<?php
include(dirname(__FILE__).'/../../../controllers/DB_Connect.php');
include(dirname(__FILE__).'/../../../controllers/Incite_Users_Table.php');

if (isset($_POST['username']) && isset($_POST['password']))
{
    if (verifyUser($_POST['user'], $_POST['password']))
    {
        session_start();
        $_SESSION['IS_LOGIN_VALID'] = true;
        $_SESSION['USER_DATA'] = getUserData($_POST['user']);
        return true;
    }
    else
    {
        return false;
    }
}
else
{
    return false;
}

?>
