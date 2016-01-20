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
                $this->view->title = "Test Title!";
                $this->view->discussions = array(array('id'=>1, 'username'=>'user1', 'content'=>'content1'),
                                                 array('id'=>2, 'username'=>'user2', 'content'=>'content2'));
                $this->view->references  = array(array('id'=>1, 'uri'=>'http://localhost/m4j/files/original/9f26d259f721383a12a6ee670046ba12.jpg', 'description'=>'des1'),
                                                 array('id'=>2, 'uri'=>'http://localhost/m4j/files/original/6bd33929bfa9813453cf6eda0cb57912.jpg', 'description'=>'des2'));

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
