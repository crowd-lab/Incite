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
        require_once("Incite_Session.php");
        require_once('Incite_Helpers.php');
        require_once('Incite_Env_Setting.php');
        setup_session();
    }

    public function indexAction()
    {
        $this->forward('discuss');

    }
    
    /**
    * Create discussion
    *
    */
    public function createAction()
    {
        if ($this->getRequest()->isPost()) {
            $workingGroupId = 0;
            $userId = $_SESSION['Incite']['USER_DATA']['id'];

            if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
                $workingGroupId = $_SESSION['Incite']['USER_DATA']['working_group']['id'];
            }

            //Discussion type is 4 (between-document)
            $discussion = new InciteDiscussion;
            $discussion->discussion_type = 4;
            $discussion->user_id = $userId;
            $discussion->working_group_id = $workingGroupId;
            $discussion->discussion_text = $_POST['title'];
            $discussion->is_active = 1;
            $discussion->save();
            if (!empty($_POST['references'])) {
                //$discussionID = createQuestion($_POST['title'], , $workingGroupId, array(), 4);
                $refs = explode(',', $_POST['references']);
                foreach ((array) $refs as $ref) {
                    $item_discussion = new InciteItemsDiscussions;
                    $item_discussion->discussion_id = $discussion->id;
                    $item_discussion->item_id = $ref;
                    $item_discussion->save();
                }
            } 

            $comment = new InciteComment;
            $comment->user_id = $userId;
            $comment->discussion_id = $discussion->id;
            $comment->comment_text = $_POST['content'];
            $comment->is_active = 1;
            $comment->save();
            //replyToQuestion($_POST['content'], $_SESSION['Incite']['USER_DATA']['id'], $discussionID, array());
            $this->redirect('incite/discussions/discuss');
        } else {
            //show create discussion page
        }
    }

    /**
    * form the metadata of the discuss page
    *
    */
	public function discussAction()
    {
        $userId = $_SESSION['Incite']['USER_DATA']['id'];
        if ($this->getRequest()->isPost()) {
            $comment = new InciteComment;
            $comment->user_id = $userId;
            $comment->discussion_id = $_POST['discussion_id'];
            $comment->comment_text = $_POST['content'];
            $comment->is_active = 1;
            $comment->save();
        }
    
        $discussionTable = $this->_helper->db->getTable('InciteDiscussion');
        $commentTable = $this->_helper->db->getTable('InciteComment');
        $itemsDiscussionsTable = $this->_helper->db->getTable('InciteItemsDiscussions');

        if ($this->_hasParam('id')) {
            //Get discussion by given id from database
            $this->_helper->db->setDefaultModelName('Item');
            $discussion_id = $this->_getParam('id');
            $discussionId = $this->_getParam('id');

            $discussion = $discussionTable->findDiscussionById($discussionId);
            if ($discussion) {
                $this->view->id = $discussion->id;
                $discussion_comments = $commentTable->findCommentsWithUserInfoByDiscussionId($discussionId);
                $discussion_reference_items = $itemsDiscussionsTable->findItemsByDiscussionId($discussionId);
                
                $this->_helper->viewRenderer('discussid');
                $this->view->title = $discussion->discussion_text;
                $this->view->discussions = $discussion_comments;

                $references = array();
                $transcriptionTable = $this->_helper->db->getTable('InciteTranscription');
                foreach ((array)$discussion_reference_items as $reference_item) {
                    $record = $this->_helper->db->find($reference_item->id);
                    if ($record == null)
                        continue;
                    $transcription = $transcriptionTable->findNewestByItemId($reference_item->id);
                    if ($transcription != null) 
                        $transcribed_text = $transcription->transcribed_text;
                        if (!isset($transcribed_text)) {
                            $transcribed_text = "No transcription available. Please consider helping <a href='".getFullInciteUrl()."/documents/transcribe/".$reference_item->id."'>transcribe</a> it!";
                        }
                        $references[] = array('id' => $reference_item->id, 'uri' => get_image_url_for_item($record), 'title' => metadata($record, array('Dublin Core', 'Title')), 'description' => metadata($record, array('Dublin Core', 'Description')), 'transcription' => $transcribed_text, 'date' => metadata($record, array('Dublin Core', 'Date')), 'location' => metadata($record, array('Item Type Metadata', 'Location')));
                }
                $this->view->references = $references;

                //view probably needs the "discussion" and its references
            } else {
                $this->redirect('incite/discussions');
            }
        } else {
            //Get all discussions and list them!
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];
            $discussions = $discussionTable->findAllDiscussionsWithUserAndCommentInfo();

            $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
            $total_pages = ceil(count($discussions) / $max_records_to_show);
            $records_counter = 0;
            $records = array();

            if (count($discussions) > 0) {
                for ($i = ($current_page - 1) * $max_records_to_show; $i < count($discussions); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $discussions[$i];
                }
            }
            $this->view->Discussions = $records;
            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;
        }

    }
	
}
