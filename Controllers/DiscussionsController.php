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
class Incite_DiscussionsController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
                        //echo '<div style="color:red">Documents Controller Initialized! This is probably a good place to put the header such as <a href="./discover">discover</a> - <a href="transcribe">transcribe</a> - <a href="tag">tag</a> - <a href="connect">connect</a> - <a href="discuss">discuss</a></div>';
        require_once("Incite_Transcription_Table.php");
        
    }

    public function indexAction()
    {
        $this->forward('discuss');

    }

    public function createAction()
    {
        if ($this->getRequest()->isPost()) {
            //process and store posted data about discussion
        } else {
            //show create discussion page
        }
    }

	public function discussAction()
    {
        if ($this->_hasParam('id')) {
            //Get discussion by given id from database
            $discussion_exists = true;
            if ($discussion_exists) {
                $this->_helper->viewRenderer('discussid');
            } else {
                $this->redirect('incite/discussions');
            }
        } else {
            //Get all discussions and list them!
            //$this->view->Discussions = ???
        }

    }
	
}
