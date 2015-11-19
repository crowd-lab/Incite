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
    public function loginAction() {
        if ($this->getRequest()->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $isGuest = false;
            $guestID = -1;
            if (isset($_SESSION['Incite']) && strpos($_SESSION['Incite']['USER_DATA'][1], "guest") !== false)
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

    public function logoutAction() {
        $_SESSION['Incite'] = array();
        die();
    }

    public function getdataAction() {
        echo json_encode($_SESSION['Incite']['USER_DATA']);
    }
}
