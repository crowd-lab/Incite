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
        //$this->_helper->viewRenderer->setNoRender(TRUE);
    }

    public function viewAction() {
        $this->forward('activity');
    }


    public function profileAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('profile');
            $user_id = $this->_getParam('id');
            $this->view->user = getUserDataByUserId($user_id);
        } else {
            $this->view->users = "";
        }
    }
    public function activityAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('activity');
            $userId = $this->_getParam('id');
            $userTable = $this->_helper->db->getTable('InciteUser');
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');

            $this->view->transcribed_docs = $userTable->findTranscribedItemsByUserId($userId);
            $this->view->tagged_docs = $userTable->findTaggedItemsByUserId($userId);
            $this->view->connected_docs = $userTable->findConnectedItemsByUserId($userId);
            $this->view->discussions = $userTable->findDiscussionsByUserId($userId);
            $this->view->groups = $groupsUsersTable->findGroupsByUserId($userId);
            $this->view->user = $userTable->findUserById($userId);

            //Get all activities together, add activity_type and sort them based on time
            $this->view->activities = $userTable->findActivitiesByUserId($userId);
        } else {
            $this->view->users = "";
        }
    }
    public function groupAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('group');
            $userId = $this->_getParam('id');
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');
            $userTable = $this->_helper->db->getTable('InciteUser');


            $this->view->groups = $groupsUsersTable->findGroupsByUserId($userId);
            $this->view->user = $userTable->findUserById($userId);

        } else {
            $this->view->users = "";
        }
    }


/**
* Direct to Forgot Password Page.
*/
public function forgotAction(){

    $this->_helper->viewRenderer('forgotpw');
}

}

?>
