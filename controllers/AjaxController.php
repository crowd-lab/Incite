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
        include("Incite_Users_Table.php");
        include("Incite_Replies_Table.php");
        include("Incite_Questions_Table.php");
        include("DiscoverController.php");
        include("Incite_Search.php");
        require_once("Incite_Tag_Table.php");
        require_once("Incite_Transcription_Table.php");
        require_once("Incite_System_Log.php");
        require_once("Incite_Subject_Concept_Table.php");
        require_once("Incite_Session.php");
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
            createQuestion($text, $_SESSION['Incite']['USER_DATA']['id'], array($documentID), $type);
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
}
