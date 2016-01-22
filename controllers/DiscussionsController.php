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
        require_once("Incite_Session.php");
        setup_session();
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
        if ($this->getRequest()->isPost()) {
            replyToQuestion($_POST['content'], $_SESSION['Incite']['USER_DATA']['id'], $_POST['discussion_id'], array());
        }
        if ($this->_hasParam('id')) {
            //Get discussion by given id from database
            $this->_helper->db->setDefaultModelName('Item');
            $discussion_id = $this->_getParam('id');
            $discussion_title = getQuestionText($discussion_id);
            $discussion_reply_ids = getAllRepliesForQuestion($discussion_id);
            $discussion_reference_ids = getAllReferencedDocumentIdsForQuestion($discussion_id);
            $discussion_exists = true;
            $this->view->id = $discussion_id;
            if ($discussion_exists) {
                $this->_helper->viewRenderer('discussid');
                $this->view->title = $discussion_title;
                $replies = array();
                foreach ((array)$discussion_reply_ids as $reply_id) {
                    $first_name = getUserDataID(getUserIdForReply($reply_id))[0];
                    $replies[] = array('id' => $reply_id, 'first_name' => $first_name, 'content' => getReplyText($reply_id));
                }
                $this->view->discussions = $replies;

                $references = array();
                foreach ((array)$discussion_reference_ids as $reference_id) {
                    $record = $this->_helper->db->find($reference_id);
                    $approved_transcriptions = getIsAnyTranscriptionApproved($reference_id);
                    $transcription = "no transcription available";
                    if ($approved_transcriptions != null) 
                        $transcription = getTranscriptionText($approved_transcriptions[0]);
                    $references[] = array('id' => $reference_id, 'uri' => $record->getFile()->getProperty('uri'), 'title' => metadata($record, array('Dublin Core', 'Title')), 'description' => metadata($record, array('Dublin Core', 'Description')), 'transcription' => $transcription);
                }
                $this->view->references = $references;

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
