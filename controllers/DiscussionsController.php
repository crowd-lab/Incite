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
        require_once("Incite_Users_Table.php");
        require_once("Incite_Replies_Table.php");
        require_once("Incite_Questions_Table.php");
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
                $discussionID = createQuestion($_POST['title'], $_SESSION['Incite']['USER_DATA']['id'], array(), 4);
            else
                $discussionID = createQuestion($_POST['title'], $_SESSION['Incite']['USER_DATA']['id'], explode(',', $_POST['references']), 4);

            replyToQuestion($_POST['content'], $_SESSION['Incite']['USER_DATA']['id'], $discussionID, array());
            $_SESSION['incite']['redirect'] = array(
                    'status' => 'complete_create_discussion', 
                    'message' => 'Congratulations! You just created a new discussion! You will be redirected to your discussion', 
                    'url' => INCITE_PATH.'discussions/discuss/'.$this->_getParam('id'),
                    'time' => '5');
            $this->redirect('incite/discussions/discuss/'.$discussionID);
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
                    $userdata = getUserDataID(getUserIdForReply($reply_id));
                    $first_name = $userdata[0];
                    $replies[] = array('id' => $reply_id, 'first_name' => $first_name, 'content' => getReplyText($reply_id), 'time' => getReplyTimestamp($reply_id));
                }
                $this->view->discussions = $replies;

                $references = array();
                foreach ((array)$discussion_reference_ids as $reference_id) {
                    $record = $this->_helper->db->find($reference_id);
                    if ($record == null)
                        continue;
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
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];
            $discussions = getAllDiscussionsWithNumOfReplies();

            //sort discussion by number of replies
            $dis_id_to_num_of_replies = array();
            foreach ((array)$discussions as $discussion) {
                $dis_id_to_num_of_replies[$discussion['id']] = $discussion['num_of_replies'];
            }
            arsort($dis_id_to_num_of_replies);
            $sorted_discussions = array();
            foreach ((array)$dis_id_to_num_of_replies as $id => $num) {
                $sorted_discussions[] = $discussions[$id];
            }

            $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
            $total_pages = ceil(count($discussions) / $max_records_to_show);
            $records_counter = 0;
            $records = array();

            if (count($discussions) > 0) {
                for ($i = ($current_page - 1) * $max_records_to_show; $i < count($discussions); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $sorted_discussions[$i];
                }
            }
            $this->view->Discussions = $records;
            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;
        }

    }
	
}
