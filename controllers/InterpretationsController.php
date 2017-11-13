<?php
/**
 * Incite
 *
 */

/**
 * Plugin "Incite"
 *
 * @package Incite
 */
class Incite_InterpretationsController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        require_once('graders.php');
        require_once("Incite_Transcription_Table.php");
        require_once("Incite_Tag_Table.php");
        require_once("Incite_Subject_Concept_Table.php");
        require_once("Incite_Users_Table.php");
        require_once("Incite_Questions_Table.php");
        require_once("Incite_Replies_Table.php");
        require_once("Incite_Search.php");
        require_once("Incite_Session.php");
        require_once("Incite_Env_Setting.php");
        require_once('Incite_Helpers.php');
        setup_session();

        $action = $this->_getParam('action');
        if ($action != 'login' && !isset($_SESSION['Incite']['grader'])) {
            $this->redirect(getFullInciteUrl().'/interpretations/login');
        }
    }

    public function indexAction()
    {
		//Nothing to do right now. The view is enough to handle output.
    }
    public function gradeAction(){
        if ($this->_hasParam('id')) {
            $grading_id = $this->_getParam('id');
            if ($this->getRequest()->isPost()) {
                //prepare data
                $ss = $_POST['ss'];
                $cs = $_POST['cs'];
                $is = $_POST['is'];
                $ms = $_POST['ms'];
                $sr = $_POST['sr'];
                $cr = $_POST['cr'];
                $ir = $_POST['ir'];
                $mr = $_POST['mr'];
                $os = $ss+$cs+$is+$ms;
                $or = $_POST['or'];
                $grader = $_SESSION['Incite']['grader']['name'];
                
                //save data
                if ($grader == "Robert") {
                    $postfix = "1";
                } else if ($grader == 'Michael') {
                    $postfix = "2";
                } else {
                    echo 'There is something wrong. Please contact the administrator';
                    die();
                }

                $db = DB_Connect::connectDB();
                $stmt = $db->prepare("UPDATE study22_interpretations SET grader".$postfix." = ?, overall_score".$postfix." = ?, overall_reason".$postfix." = ?, summarizing_score".$postfix." = ?, summarizing_reason".$postfix." = ?, contextualizing_score".$postfix." = ?, contextualizing_reason".$postfix." = ?, inferring_score".$postfix." = ?, inferring_reason".$postfix." = ?, monitoring_score".$postfix." = ?, monitoring_reason".$postfix." = ?, time_end".$postfix." = NOW() WHERE id = ?");
                $stmt->bind_param("sisisisisisi", $grader, $os, $or, $ss, $sr, $cs, $cr, $is, $ir, $ms, $mr, $grading_id);
                $stmt->execute();
                $stmt->close();
                $db->close();

                $this->redirect(getFullInciteUrl().'/interpretations/grade');
            } else {
                //Getting Response
                $db = DB_Connect::connectDB();
                //$stmt = $db->prepare("SELECT `id`, `item_id`, `response`, grader1, overall_score1, overall_reason1, summarizing_score1, summarizing_reason1, contextualizing_score1, contextualizing_reason1, inferring_score1, inferring_reason1, monitoring_score1, monitoring_reason1, grader2, overall_score2, overall_reason2, summarizing_score2, summarizing_reason2, contextualizing_score2, contextualizing_reason2, inferring_score2, inferring_reason2, monitoring_score2, monitoring_reason2  FROM `study22_interpretations` WHERE `id` = ?");
                $stmt = $db->prepare("SELECT `id`, `item_id`, `response`, `grader1`, `grader2`  FROM `study22_interpretations` WHERE `id` = ?");
                $stmt->bind_param("i", $grading_id);
                //$stmt->bind_result($id, $item_id, $response, $grader1, $overall_score1, $overall_reason1, $summarizing_score1, $summarizing_reason1, $contextualizing_score1, $contextualizing_reason1, $inferring_score1, $inferring_reason1, $monitoring_score1, $monitoring_reason1, $grader2, $overall_score2, $overall_reason2, $summarizing_score2, $summarizing_reason2, $contextualizing_score2, $contextualizing_reason2, $inferring_score2, $inferring_reason2, $monitoring_score2, $monitoring_reason2);
                $stmt->bind_result($id, $item_id, $response, $grader1, $grader2);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                $db->close();
                if ($grader1 != "" && $grader2 != "") { //Already graded! Currently don't allow regrading!
                    $this->redirect(getFullInciteUrl().'/interpretations/grade');
                }
                $this->view->id = $id;
                $this->view->response = $response;
                //update time
                $grader = $_SESSION['Incite']['grader']['name'];
                //save data
                if ($grader == "Robert") {
                    $postfix = "1";
                } else if ($grader == 'Michael') {
                    $postfix = "2";
                } else {
                    echo 'There is something wrong. Please contact the administrator';
                    die();
                }
                $db = DB_Connect::connectDB();
                $stmt = $db->prepare("UPDATE study22_interpretations SET time_start".$postfix." = NOW() WHERE id = ?");
                $stmt->bind_param("i", $grading_id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                //Getting Item Title
                $db = DB_Connect::connectDB();
                $stmt = $db->prepare("SELECT `text`  FROM `omeka_element_texts` WHERE `record_id` = ? AND element_id = 50");
                $stmt->bind_param("i", $item_id);
                $stmt->bind_result($title);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                $db->close();
                $this->view->title = $title;
                //Getting Item Text
                $db = DB_Connect::connectDB();
                $stmt = $db->prepare("SELECT `transcribed_text`  FROM `omeka_incite_transcriptions` WHERE `document_id` = ? ORDER BY timestamp_creation DESC LIMIT 1");
                $stmt->bind_param("i", $item_id);
                $stmt->bind_result($text);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                $db->close();
                $this->view->text = $text;
                //Historical Questions
                $test_questions = array(1125=>"What was the life of a child like during the Depression?",
                                        1126=>"What was the role of spies during the American Revolutionary War",
                                        1127=>"What was life like in the artillery during the Civil War?",
                                        1128=>"What were the conditions of life in farming communities on the great plains during the early 20th century?",
                                        1129=>"What were nineteenth century's views on women's rights?");
                $this->view->question = $test_questions[$item_id];
                
                $this->_helper->viewRenderer('gradeid');
            }
        } else {
            $grader = $_SESSION['Incite']['grader']['name'];
            if ($grader == "Robert") {
                $postfix = "1";
            } else if ($grader == 'Michael') {
                $postfix = "2";
            } else {
                echo 'There is something wrong. Please contact the administrator';
                die();
            }

            $interpretations = array();
            $db = DB_Connect::connectDB();
            $stmt = $db->prepare("SELECT `id`, `response` from study22_interpretations WHERE grader".$postfix." = ''");
            $stmt->execute();
            $stmt->bind_result($id, $response);
            while ($stmt->fetch()) {
                $interpretations[] = array('id' => $id, 'response' => $response);
            }
            $stmt->close();
            $db->close();
            $this->view->interpretations = $interpretations;
            $this->_helper->viewRenderer('grade');
        }
        
    }
    public function loginAction() {
        //Already logged in
        if (isset($_SESSION['Incite']['grader'])) {
            $this->redirect(getFullInciteUrl().'/interpretations/grade');
        }

        //Need to log in
        if ($this->getRequest()->isPost() && isset($_POST['username']) && isset($_POST['password'])) {
            if (is_valid_interpretation_grader($_POST['username'], $_POST['password'])) {
                $_SESSION['Incite']['grader'] = array('name' => $_POST['username'], 'password' => $_POST['password']);
                $this->redirect(getFullInciteUrl().'/interpretations/grade');
            }
        }
    }
    public function logoutAction() {
        unset($_SESSION['Incite']['grader']);
        $this->redirect(getFullInciteUrl().'/interpretations/grade');
    }

}
