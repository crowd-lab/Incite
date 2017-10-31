<?php
/*
*   Group Controller
*
*/



class Incite_GroupsController extends Omeka_Controller_AbstractActionController {

    public function init() {
        require_once("Incite_Search.php");
        require_once("Incite_Session.php");
        require_once("Incite_Env_Setting.php");
        require_once('Incite_Helpers.php');
        setup_session();
    }

    public function indexAction() {
    }

    public function viewAction() {
        if ($this->_hasParam('id')) {
            //Parameters and tables
            $groupId = $this->_getParam('id');
            $groupTable = $this->_helper->db->getTable('InciteGroup');
            $groupsUsersTable = $this->_helper->db->getTable('InciteGroupsUsers');
            $userTable = $this->_helper->db->getTable('InciteUser');

            //Get group users and their activities
            $groupsUsers = $groupsUsersTable->findUsersByGroupId($groupId);
            $users = array();
            foreach ((array)$groupsUsers as $groupsUser) {
                $user = $userTable->findUserById($groupsUser->user_id);
                $user['transcribed_item_count'] = $userTable->findTranscribedItemCountByUserId($user->id);
                $user['tagged_item_count'] = $userTable->findTaggedItemCountByUserId($user->id);
                $user['connected_item_count'] = $userTable->findConnectedItemCountByUserId($user->id);
                $user['discussion_count'] = $userTable->findDiscussionCountByUserId($user->id);
                $user->group_privilege = $groupsUser->group_privilege;
                $users[] = $user;
            }

            //Set parameters for the view
            $this->_helper->viewRenderer('viewid');
            $this->view->users = $users;
            $this->view->group = $groupTable->findGroupById($groupId);
            $this->view->groupOwner = $userTable->findUserById($this->view->group->creator_id);
        } else {
        }
    }
}



?>
