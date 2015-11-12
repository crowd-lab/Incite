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

    //Demo of getting users
    public function getuserAction() {
        echo "<script type='text/javascript'>alert('hi')</script>";
        echo 'getuser!';
    }

    public function loginAction() {
        if ($this->getRequest()->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            if (verifyUser($username, $password)) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
                $_SESSION['Incite']['USER_DATA'] = getUserData($username);
                echo json_encode(true);
            } else {
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
            if (createAccount($username, $password, $firstName, $lastName, $priv, $exp) != "failure") {
                //destroy previous session and then map it to the new session ==> store in new table
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['Incite']['IS_LOGIN_VALID'] = true;
                $_SESSION['Incite']['USER_DATA'] = getUserData($username);
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }

    public function logoutAction() {
        $_SESSION = array();
        session_destroy();
        die();
    }
    
    public function getdataAction()
    {
        echo json_encode($_SESSION['Incite']['USER_DATA']);
    }

}
