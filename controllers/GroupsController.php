<?php




class Incite_GroupsController extends Omeka_Controller_AbstractActionController {

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
        require_once('Incite_Groups_Table.php');
        setup_session();
    }

    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $group_id = 1;
        $g = getMembersWithActivityOverviewByGroupId($group_id);

        echo '<pre>';
        print_r($g);
        echo '</pre>';
    }

    public function viewAction() {
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('viewid');
            $group_id = $this->_getParam('id');
            $this->view->users = getMembersWithActivityOverviewByGroupId($group_id);
            $this->view->acceptedUsers = getMembersAcceptedIntoGroup($group_id);
            $this->view->group = getGroupInfoByGroupId($group_id);
        } else {
            $this->view->users = "";
        }
    }
}



?>
