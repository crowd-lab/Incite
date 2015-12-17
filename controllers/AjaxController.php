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
class Incite_AjaxController extends Omeka_Controller_AbstractActionController {

    public function init() {
        //Since this is for ajax purpose, we don't need to render any views!
        $this->_helper->viewRenderer->setNoRender(TRUE);
        include("Incite_Users_Table.php");
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
        if ($this->getRequest()->isPost()) {
        }
    }
    /**
     * This returns comments of a document
     */
    public function getcommentsdocAction() {
    }
}
