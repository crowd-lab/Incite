<?php
/**
 * Verifies if the user is still logged in
 */
session_start();
include_once ("DB_Connect.php");
if (!DB_Connect::isLoggedIn()) 
    {
    //Redirect if necessary header('Location: index.php');
    die(); //destroy the session
    }
?>
