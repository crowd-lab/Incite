<?php





class Incite_UsersController extends Omeka_Controller_AbstractActionController {

    public function init() {
        require_once('Incite_Helpers.php');
        require_once("Incite_Transcription_Table.php");
        require_once("Incite_Tag_Table.php");
        require_once("Incite_Subject_Concept_Table.php");
        require_once("Incite_Users_Table.php");
        require_once("Incite_Questions_Table.php");
        require_once("Incite_Replies_Table.php");
        require_once("Incite_Search.php");
        require_once("Incite_Session.php");
        require_once("Incite_Env_Setting.php");
        setup_session();
    }

    public function indexAction() {
        
        $this->_helper->viewRenderer->setNoRender(TRUE);
        echo getDiscussionCountByUserId(3);
    }

    public function viewAction() {
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('viewid');
            $user_id = $this->_getParam('id');
            $this->view->transcribed_docs = getTranscribedDocumentsByUserId($user_id);
            $this->view->tagged_docs = getTaggedDocumentsByUserId($user_id);
            $this->view->connected_docs = getConnectedDocumentsByUserId($user_id);
            $this->view->discussions = getDiscussionsByUserId($user_id);
            $this->view->groups = getGroupsByUserId($user_id);
            $this->view->user = getUserDataByUserId($user_id);

            //Get all activities together, add activity_type and sort them based on time
            $tran = getTranscribedDocumentsByUserId($user_id);
            addKeyValueToArray($tran, 'activity_type', 'Transcribe');
            $tag = getTaggedDocumentsByUserId($user_id);
            addKeyValueToArray($tag, 'activity_type', 'Tag');
            $con = getConnectedDocumentsByUserId($user_id);
            addKeyValueToArray($con, 'activity_type', 'Connect');
            $dis = getDiscussionsByUserId($user_id);
            addKeyValueToArray($dis, 'activity_type', 'Discuss');
            $activities = array_merge($tran, $tag, $con, $dis);
            usort($activities, "customizedTimeCmpFuncDESC");
            $this->view->activities = $activities;
        } else {
            $this->view->users = "";
        }
    }
}

?>
