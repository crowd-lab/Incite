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
        include("Incite_Users_Table.php");
        include("Incite_Replies_Table.php");
        include("Incite_Questions_Table.php");
        
    }

    public function indexAction()
    {
        $this->forward('discuss');

    }

    public function createAction()
    {
        if ($this->getRequest()->isPost()) {
            //Discussion type is 4 (between-document)
            print_r($_POST);
            if (empty($_POST['references']))
                $discussionID = createQuestion($_POST['title'], $_SESSION['Incite']['USER_DATA'][0], array(), 4);
            else
                $discussionID = createQuestion($_POST['title'], $_SESSION['Incite']['USER_DATA'][0], explode(',', $_POST['references']), 4);

            replyToQuestion($_POST['content'], $_SESSION['Incite']['USER_DATA'][0], $discussionID, array());
            /*
            $discussionID = createQuestion($_POST['title'], $_SESSION['Incite']['USER_DATA'][0], explode(',', $POST['references']), 4);
            replyToQuestion($_POST['content'], $_SESSION['Incite']['USER_DATA'][0], $discussionID, array());
            //*/
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
                //view probably needs the "discussion" and its references
            } else {
                $this->redirect('incite/discussions');
            }
        } else {
            //Get all discussions and list them!
            //$this->view->Discussions = ???
        }

    }
	
}
