<?php




class Incite_UsersController extends Omeka_Controller_AbstractActionController {

    public function init() {
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
    }

    public function indexAction() {
        
        $this->_helper->viewRenderer->setNoRender(TRUE);
        echo 'hi';
    }

    public function viewAction() {
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('viewid');
        } else {
            $this->view->users = "";
        }
    }


}



?>
