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

/**
* Tag String Extraction
**/

function tag_extraction($str) {
    preg_match_all('/<em([^>]*)>([^<]*)<\/em>/', $str, $matches);
    $attributes = $matches[1];
    $tag_names = $matches[2];
    preg_match_all('/class="([^\s]*)[^"]*"/', implode('?', $attributes), $matches);
    $categories = $matches[1];
    $tags = array();
    for ($i = 0; $i < count($categories); $i++) {
        $tags[] = array('category' => $categories[$i], 'tag_name' => $tag_names[$i]);
    }
    return array('categories' => $categories, 'tag_names' => $tag_names);
}

/**
* Word Edit Distance Calculation
*/
function word_edit_distance($str1_arr, $str2_arr) {
    $len1 = count($str1_arr);
    $len2 = count($str2_arr);

    if ($len1 == 0)
        return $len2;
    else if ($len2 == 0)
        return $len1;

    $dis = array();
    $dis[0] = array_fill(0, $len2+1, 0);
    for ($i = 1; $i <= $len1; $i++) {
        $dis[$i] = array_fill(0, $len2+1, 0);
    }
    $dis[0][0] =  0;

    for ($i = 1; $i < $len2; $i++)
        $dis[0][$i] = $i;

    for ($i = 1; $i < $len1; $i++)
        $dis[$i][0] = $i;

    for ($i = 0; $i < $len1; $i++) {
        for ($j = 0; $j < $len2; $j++) {
            if ($str1_arr[$i] == $str2_arr[$j])
                $dis[$i+1][$j+1] = $dis[$i][$j];
            else
                $dis[$i+1][$j+1] = min($dis[$i][$j], $dis[$i][$j+1], $dis[$i+1][$j])+1;
        }
    }
    return $dis[$len1][$len2];
}


function getTextBetweenTags($string, $tagname) {
  $pattern = "/<$tagname>(.*?)<\/$tagname>/";
  preg_match_all($pattern, $string, $matches);
  return $matches;
}

function colorTextBetweenTags($string, $tagname, $color) {
  $result = $string;
  $result = str_replace('<' . $tagname . '>', '<em style="background-color:' . $color . ';">', $result);
  $result = str_replace('</' . $tagname . '>', '</em>', $result);
  return $result;
}

function classifyTextWithinTagWithId($string, $tagname, $id) {
  $result = $string;
  $pos = strpos($result, '<' . $tagname . '>');
  if ($pos !== false) {
    $result = substr_replace($result, '<em id="tag_id_'.$id.'" class="' . strtolower($tagname) . ' tagged-text">', $pos, strlen('<' . $tagname . '>'));
  }
  $pos = strpos($result, '</' . $tagname . '>');
  if ($pos !== false) {
    $result = substr_replace($result, '</em>', $pos, strlen('</' . $tagname . '>'));
  }
  //$result = str_replace('<' . $tagname . '>', '<span id="tag_id_'.$id.'" class="' . strtolower($tagname) . '">', $result, 1);
  //$result = str_replace('</' . $tagname . '>', '</span>', $result, 1);
  return $result;
}

function sort_strlen($str1, $str2) {
  return strlen($str2) - strlen($str1);
}

/**
* Upgrade V1 (using span) to V2 (using em)
*/
function migrateTaggedDocumentFromV1toV2($text) {
  $tmp_result = str_replace('<span id', '<em id', $text);
  $result = str_replace('</span>', '</em>', $tmp_result);
  return $result;
}

function isAnyTrialAvailable()
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT `id` FROM `study2` WHERE `is_completed` = 0");
    $stmt->bind_result($trial_id);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($result != null) {
        return true;
    } else {
        return false;
    }
}

function hasParticipated($worker_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM `study2` WHERE `worker_id` = ?");
    $stmt->bind_param('s', $worker_id);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function getNextTrial($assignment_id, $worker_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT `id`, `workflow`, `doc1`, `task1`, `doc2`, `task2`, `doc3`, `task3` FROM `study2` WHERE `is_completed` = 0");
    $stmt->bind_result($trial_id, $workflow, $doc1, $task1, $doc2, $task2, $doc3, $task3);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($result != null) {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE `study2` SET is_completed = ?, assignment_id = ?, worker_id = ?, time0_start = NOW(), attempts = attempts + 1 WHERE `id` = ?");
        $is_completed = 1; //job taken=>working
        $stmt->bind_param("issi", $is_completed, $assignment_id, $worker_id, $trial_id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        return array('trial_id' => $trial_id, 'workflow' => $workflow, 'doc1' => $doc1, 'task1' => $task1, 'doc2' => $doc2, 'task2' => $task2, 'doc3' => $doc3, 'task3' => $task3);
    } else {
        return null;
    }
}

function completeTask($id, $task_seq, $worker_id, $submission_id, $user_id)
{
    $db = DB_Connect::connectDB();

    if ($task_seq < 3) {
        $stmt = $db->prepare("UPDATE study2 SET is_completed = ?, worker_id = ?, incite_user_id = ?, time".$task_seq."_end = NOW(), time".($task_seq+1)."_start = NOW(), submission".$task_seq." = ? WHERE id = ?");
    } else {
        $stmt = $db->prepare("UPDATE study2 SET is_completed = ?, worker_id = ?, incite_user_id = ?, time".$task_seq."_end = NOW(), submission".$task_seq." = ? WHERE id = ?");
    }

    $stmt->bind_param("isiii", $task_seq, $worker_id, $user_id, $submission_id, $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function urlGenerator($doc_id, $task_type)
{
    $action = '';
    switch($task_type) {
        case 0:
            $action = 'presurvey'; break;
        case 1:
            $action = 'transcribe'; break;
        case 2:
            $action = 'tag'; break;
        case 3:
            $action = 'connect'; break;
        case 4:
            $action = 'postsurvey'; break;
        case 5:
            $action = 'complete'; break;
        default:
            $action = 'error'; break;
    }
    return getFullInciteUrl().'/documents/'.$action.'/'.$doc_id;
}


class Incite_DocumentsController extends Omeka_Controller_AbstractActionController {

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

  public function getWorkingGroupID() {
    if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
      return $_SESSION['Incite']['USER_DATA']['working_group']['id'];
    } else {
      return 0;
    }
  }

  public function createSearchResultPages($document_ids, $task_name) {
    if (count($document_ids) <= 0) {
      if (isSearchQuerySpecifiedViaGet()) {
        $_SESSION['incite']['message'] = 'Unfortunately, we found no documents related to your search criteria. Please change your search criteria and try again.';
      } else {
        $_SESSION['incite']['message'] = 'Unfortunately, there are no documents for your selected task right now. Please come back later or find a document to <a href="'.getFullInciteUrl().'/documents/transcribe">transcribe</a>, <a href="'.getFullInciteUrl().'/documents/tag">tag</a> or <a href="'.getFullInciteUrl().'/documents/connect">connect</a>!';
      }

      return;
    }

    $current_page = 1;
    if (isset($_GET['page'])) {
      $current_page = $_GET['page'];
    }

    $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
    $records_counter = 0;
    $records = array();
    $total_pages = ceil(count($document_ids) / $max_records_to_show);

    if (count($document_ids) > 0) {
      for ($i = ($current_page - 1) * $max_records_to_show; $i < count($document_ids); $i++) {
        if ($records_counter++ >= $max_records_to_show)
        break;
        $records[] = $this->_helper->db->find($document_ids[$i]);
      }
    }

    $this->view->total_pages = $total_pages;
    $this->view->current_page = $current_page;
    $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

    if ($records != null && count($records) > 0) {
      $this->view->assign(array($task_name => $records));
    }
  }

  public function indexAction() {
    //Since we don't have document lists, redirect to the transcribe task page.
    //$this->redirect('incite/documents/transcribe');
  }

  public function showAction() {
        //Session initialization
        $is_session_on = FALSE;
        if (!isset($_SESSION))
            $is_session_on = session_start();
        else
            $is_session_on = TRUE;

        if ($is_session_on === FALSE) {
            echo 'Something went wrong. Our system does not seem to work correctly on your device. Please return the HIT. Sorry for any inconvenience!';
            die();
        }

        //Check if there is task available. If not, redirect to a page to notify the user.
        if (!isAnyTrialAvailable())  {
            $this->_helper->viewRenderer('taskless');
            return;
        }

        //Get an available task. Default: AMT
        $is_hit_accepted = !(!isset($_GET['assignmentId']) || (isset($_GET['assignmentId']) && $_GET['assignmentId'] == "ASSIGNMENT_ID_NOT_AVAILABLE"));

        //Classroom use so we can assume hit is accepted
        $is_hit_accepted = true;
        if ($is_hit_accepted) {
            $this->_helper->viewRenderer('taskless');
            $worker_id = '';
            $assignment_id = '';
            if (isset($_GET['workerId'])) {
                $worker_id = $_GET['workerId']; 
            }
            if (isset($_GET['assignmentId'])) {
                $assignment_id = $_GET['assignmentId'];
            }
            if ($worker_id != '' and hasParticipated($_GET['workerId']))  {
                $this->_helper->viewRenderer('participated');
                return;
            }
            $trial = getNextTrial($assignment_id, $worker_id);
            if ($trial != null) {
                //Mapping between testing materials and qa-set
                $doc_qa_mapping = array('1125' => 1, '1126' => 2, '1127' => 3);
                //Initialization
                $_SESSION['study2']['worker_id'] = $worker_id;
                $_SESSION['study2']['id'] = $trial['trial_id'];
                $_SESSION['study2']['workflow'] = $trial['workflow'];
                $_SESSION['study2']['task_seq'] = 0;
                $_SESSION['study2']['qa-set'] = $doc_qa_mapping[$trial['doc3']];
                //0: presurvey, 4: postsurvey, 5: complete
                $_SESSION['study2']['urls'] = array(urlGenerator('', 0), urlGenerator($trial['doc1'], $trial['task1']), urlGenerator($trial['doc2'], $trial['task2']), urlGenerator($trial['doc3'], $trial['task3']), urlGenerator('', 4), urlGenerator('', 5));

                //All set. Redirec the user to the first task!
                $this->redirect($_SESSION['study2']['urls'][0]);
            } else {
                //No valid trial so we assume it's because there is no task available.
                $this->_helper->viewRenderer('taskless');
            }
        } else { //if ($is_hit_accepted) {
            //This should happen for classroom use!
            echo 'Something went wrong (1).'; 
            die();
            $this->_helper->viewRenderer('example2');
        }
  }

  public function transcribeAction() {
    $this->_helper->db->setDefaultModelName('Item');

    if ($this->_hasParam('id')) {
      if ($this->getRequest()->isPost()) {
        $this->saveTranscription();
      }

      $this->populateDataForTranscribeTask();
    } else {
      $this->populateTranscribeSearchResults();
    }
  }

  public function saveTranscription() {
    $workingGroupId = $this->getWorkingGroupID();

    //Save results of current task
    $trans_id = createTranscription($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $_POST['transcription'], $_POST['summary'], $_POST['tone']);

    //Update the status of the task 
    completeTask($_SESSION['study2']['id'], $_SESSION['study2']['task_seq'], $_SESSION['study2']['worker_id'], $trans_id, $_SESSION['Incite']['USER_DATA']['id']);

    //All set. Move to next task!
    $_SESSION['study2']['task_seq']++;
    $task_seq = $_SESSION['study2']['task_seq'];
    $urls = $_SESSION['study2']['urls'];
    $this->redirect($urls[$task_seq]);
  }

  public function populateDataForTranscribeTask() {
    $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

    if ($this->view->document_metadata != null) {
      if ($this->view->document_metadata->getFile() == null) {
        $this->redirect('incite/documents/transcribe');
      }

      $this->_helper->viewRenderer('transcribeid');
        /*
      $this->view->latest_transcription = getNewestTranscription($this->_getParam('id'));
      $this->view->is_being_edited = !empty($this->view->latest_transcription);

      if ($this->view->is_being_edited) {
        $this->view->revision_history = getTranscriptionRevisionHistory($this->_getParam('id'));
      }
        */

      $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
      $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
    } else {
      $_SESSION['incite']['message'] = 'Unfortunately, we can not find the specified document. Please select another document to transcribe from the list below.';

      if (isset($this->view->query_str) && $this->view->query_str !== "")
      $this->redirect('/incite/documents/transcribe?'.$this->view->query_str);
      else
      $this->redirect('/incite/documents/transcribe');
    }
  }

  public function populateTranscribeSearchResults() {
    if (isSearchQuerySpecifiedViaGet()) {
      $searched_item_ids = getSearchResultsViaGetQuery();
      $document_ids = array_slice(array_intersect(array_values(getDocumentsWithoutTranscription()), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

    } else {
      $document_ids = array_slice(array_values(getDocumentsWithoutTranscription()), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = "";
      debug_to_console("no queries");

    }


      return $document_ids;
  }

  public function tagAction() {
    $this->_helper->db->setDefaultModelName('Item');
    $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

    if ($this->_hasParam('id')) {
      if ($this->getRequest()->isPost()) {
        $this->saveTags();
      }

      $this->populateDataForTagTask();
    } else {
      $this->populateTagSearchResults();
    }
  }

  public function saveTags() {
    $entities = json_decode($_POST["entities"], true);
    removeAllTagsFromDocument($this->_getParam('id'));

    $workingGroupId = $this->getWorkingGroupID();

    for ($i = 0; $i < sizeof($entities); $i++) {
      createTag($_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $entities[$i]['entity'], $entities[$i]['category'], $entities[$i]['subcategory'], $entities[$i]['details'], $this->_getParam('id'));
    }

    $trans_id = createTaggedTranscription($this->_getParam('id'), $_POST['transcription_id'], $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $_POST['tagged_doc'], $_POST['time_prod'], $_POST['loc_prod'], $_POST['time_cont'], $_POST['loc_cont']);


    //Update the status of the task 
    completeTask($_SESSION['study2']['id'], $_SESSION['study2']['task_seq'], $_SESSION['study2']['worker_id'], $trans_id, $_SESSION['Incite']['USER_DATA']['id']);

    //All set. Move to next task!
    $_SESSION['study2']['task_seq']++;
    $task_seq = $_SESSION['study2']['task_seq'];
    $urls = $_SESSION['study2']['urls'];
    $this->redirect($urls[$task_seq]);
  }

  public function populateDataForTagTask() {
    $tag_id_counter = 0;
    $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

    if ($this->view->document_metadata != null) {
      if ($this->view->document_metadata->getFile() == null) {
        echo 'no image';
      }

      //Get the transcription for the document
      //1. If workflow = 0 (artisan), find newest transcription from self
      if ($_SESSION['study2']['workflow'] == 0) {
        $newestTranscription = getNewestTranscriptionFromUserId($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id']);
      } else { //workflow = 1 (assembly): find first transcription => pre-set transcription
        $newestTranscription = getFirstTranscription($this->_getParam('id'));
      }
      //$newestTranscription = getNewestTranscription($this->_getParam('id'));
      if (!empty($newestTranscription)) {
        $this->view->transcription_id = $newestTranscription['id'];
        $this->view->transcription = $newestTranscription['transcription'];
      } else {
        $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe this document first. Or if you want to find another document to tag, please click <a href="'.getFullInciteUrl().'/documents/tag">here</a>.';

        if (isset($this->view->query_str) && $this->view->query_str !== "")
        $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id').'?'.$this->view->query_str);
        else
        $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id'));
      }

      $this->_helper->viewRenderer('tagid');
      $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
      $categories = getAllCategories();
      $category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red', 'TIME' => 'purple');

      //Do we already have tags or do we need to generate them via NER
        /*
      if (hasTaggedTranscriptionForNewestTranscription($this->_getParam('id'))) {
        $this->view->is_being_edited = true;
        $this->view->revision_history = getTaggedTranscriptionRevisionHistory($this->_getParam('id'));
        $transcriptions = getAllTaggedTranscriptions($this->_getParam('id'));
        $this->view->transcription = migrateTaggedDocumentFromV1toV2($transcriptions[count($transcriptions)-1]);
      } else 
        */
        {
        $this->view->is_being_edited = false;

        $ner_entity_table = array();

        $oldwd = getcwd();
        chdir('./plugins/Incite/stanford-ner-2015-04-20/');

        $this->view->file = 'not exist';
        $ner_input = fopen('../tmp/ner/' . $this->_getParam('id'), "w") or die("unable to open transcription");
        fwrite($ner_input, $this->view->transcription);
        fclose($ner_input);
        system("java -mx600m -cp stanford-ner.jar edu.stanford.nlp.ie.crf.CRFClassifier -loadClassifier classifiers/english.muc.7class.distsim.crf.ser.gz -outputFormat inlineXML -textFile " . '../tmp/ner/' . $this->_getParam('id') . ' > ' . '../tmp/ner/' . $this->_getParam('id') . '.ner');

        $nered_file = fopen('../tmp/ner/' . $this->_getParam('id') . '.ner', "r");
        $nered_file_size = filesize('../tmp/ner/' . $this->_getParam('id') . '.ner');
        $parsed_text = "";
        if ($nered_file_size != 0)
        $parsed_text = fread($nered_file, $nered_file_size);

        fclose($nered_file);

        //parsing results
        $transformed_transcription = $parsed_text;

        foreach ($categories as $category) {
          $entities = getTextBetweenTags($parsed_text, strtoupper($category['name']));
          $repitition = substr_count($parsed_text, '<'.strtoupper($category['name']).'>');

          for ($i = 0; $i < $repitition; $i++) {
            $transformed_transcription = classifyTextWithinTagWithId($transformed_transcription, strtoupper($category['name']), $tag_id_counter++);
          }
          $tag_id_counter -= $repitition;
          if (isset($entities[1]) && count($entities[1]) > 0) {
            //$uniq_entities = array_unique($entities[1]);
            $uniq_entities = $entities[1];
            foreach ($uniq_entities as $entity) {
              $ner_entity_table[] = array('entity' => $entity, 'category' => strtoupper($category['name']), 'subcategories' => array(), 'details' => '', 'color' => $category_colors[strtoupper($category['name'])], 'tag_id' => $tag_id_counter++);
            }
          }
        }

        chdir($oldwd);

        $this->view->entities = $ner_entity_table;
        $this->view->transcription = $transformed_transcription;
      }

      $this->view->category_colors = $category_colors;
      $this->view->tag_id_counter = $tag_id_counter;
    } else {
      //no such document
      $_SESSION['incite']['message'] = 'Unfortunately, we can not find the specified document. Please select a new document from the taggable document list below.';

      if (isset($this->view->query_str) && $this->view->query_str !== "")
      $this->redirect('/incite/documents/tag?'.$this->view->query_str);
      else
      $this->redirect('/incite/documents/tag');
    }
  }

  public function populateTagSearchResults() {
    if (isSearchQuerySpecifiedViaGet()) {
      $searched_item_ids = getSearchResultsViaGetQuery();
      $document_ids = array_slice(array_intersect(array_values(getDocumentsWithoutTagsForLatestTranscription()), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
    } else {
      $document_ids = array_slice(array_values(getDocumentsWithoutTagsForLatestTranscription()), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = "";
    }
    return $document_ids;
    // $this->createSearchResultPages($document_ids, 'Tags');
  }

  public function connectAction() {
    $this->_helper->db->setDefaultModelName('Item');
    $this->view->category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');

    if ($this->_hasParam('id')) {
      if ($this->getRequest()->isPost()) {
        $this->saveConnections();

      }
      $this->populateDataForConnectTask();
    }
  }

  public function saveConnections() {
    $all_subject_ids = getAllSubjectConceptIds();
    $workingGroupId = $this->getWorkingGroupID();

    //connect by multiscale
    if (isset($_POST['connection_type'])) {
      foreach ((array) $all_subject_ids as $subject_id) {
        //if (in_array($subject_id, (isset($_POST['subjects']) ? $_POST['subjects'] : array()))) {
        if (isset($_POST['subject'.$subject_id])) {
            addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), $_POST['subject'.$subject_id], $_POST['reasoning']);
        } else {
            addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), $_POST['subject'.$subject_id], $_POST['reasoning']);
        }
      }
    } else { //connect by tags
      if (isset($_POST['subject']) && $_POST['connection'] == 'true')
      addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 1);
      else if (isset($_POST['subject']) && $_POST['connection'] == 'false')
      addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 0);
    }

    //Update the status of the task 
    completeTask($_SESSION['study2']['id'], $_SESSION['study2']['task_seq'], $_SESSION['study2']['worker_id'], 0, $_SESSION['Incite']['USER_DATA']['id']);

    //All set. Move to next task!
    $_SESSION['study2']['task_seq']++;
    $task_seq = $_SESSION['study2']['task_seq'];
    $urls = $_SESSION['study2']['urls'];
    $this->redirect($urls[$task_seq]);

  }

  public function populateDataForConnectTask() {
    $is_connectable_by_tags = true;
    $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

    $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();



    if ($this->view->document_metadata != null) {
      if ($this->view->document_metadata->getFile() == null) {
        echo 'no image';
      }

      $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
      $this->view->subjects = getAllSubjectConcepts();

      //Filter out untranscribed documents
      //$newestTranscription = getNewestTranscription($this->_getParam('id'));
      //1. If workflow = 0 (artisan), find newest transcription from self
      if ($_SESSION['study2']['workflow'] == 0) {
        $newestTranscription = getNewestTranscriptionFromUserId($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id']);
      } else { //workflow = 1 (assembly): find first transcription => pre-set transcription
        $newestTranscription = getFirstTranscription($this->_getParam('id'));
      }
      if (empty($newestTranscription)) {
        if (isset($this->view->query_str) && $this->view->query_str !== "") {
          $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
          $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id').'?'.$this->view->query_str);
        } else {
          $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
          $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id'));
        }
      }

      //Gets the latest tagged transcription and the most recently marked subjects, if they exist
      //1. If workflow = 0 (artisan), find newest transcription from self
      if ($_SESSION['study2']['workflow'] == 0) {
        $taggedTranscription = getNewestTaggedTranscriptionFromUserId($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id']);
      } else { //workflow = 1 (assembly): find first transcription => pre-set transcription
        $taggedTranscription = getFirstTaggedTranscription($this->_getParam('id'));
      }
      //if (hasTaggedTranscriptionForNewestTranscription($this->_getParam('id'))) {
      if (!empty($taggedTranscription)) {
        //$transcriptions = getAllTaggedTranscriptions($this->_getParam('id'));
        $this->view->transcription =  migrateTaggedDocumentFromV1toV2($taggedTranscription['transcription']);

        //$this->view->newest_n_subjects = getNewestSubjectsForNewestTaggedTranscription($this->_getParam('id'));
        /*
        $this->view->is_being_edited = !empty($this->view->newest_n_subjects);

        if ($this->view->is_being_edited) {
          $this->view->revision_history = getConnectionRevisionHistory($this->_getParam('id'));
        }
        */

        $this->_helper->viewRenderer('connectbymultiscale');
      } else {
        if (isset($this->view->query_str) && $this->view->query_str !== "") {
          $_SESSION['incite']['message'] = 'Unfortunately, the document has not been tagged yet. Please help tag the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
          $this->redirect('/incite/documents/tag/'.$this->_getParam('id').'?'.$this->view->query_str);
        } else {
          $_SESSION['incite']['message'] = 'Unfortunately, the document has not been tagged yet. Please help tag the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
          $this->redirect('/incite/documents/tag/'.$this->_getParam('id'));
        }
      }
    } else {
      $_SESSION['incite']['message'] = 'Unfortunately, we can not find the specified document. Please select another document from the connectable document list below.';

      if (isset($this->view->query_str) && $this->view->query_str !== "")
        $this->redirect('/incite/documents/connect?'.$this->view->query_str);
      else
        $this->redirect('/incite/documents/connect');
    }
  }

  public function populateConnectSearchResults() {
    $connectable_documents = getDocumentsWithoutConnectionsForLatestTaggedTranscription();
    $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

    if (isSearchQuerySpecifiedViaGet()) {
      $searched_item_ids = getSearchResultsViaGetQuery();
      $document_ids = array_slice(array_intersect(array_values($connectable_documents), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
    } else {
      $document_ids = array_slice(array_values($connectable_documents), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = "";
    }

    // $this->createSearchResultPages($document_ids, 'Connections');
    return $document_ids;
  }

  public function discussAction() {
    //testing controller
  }

  public function redirectAction() {
    if (isset($_SESSION['incite']['redirect'])) {
      $this->view->redirect = $_SESSION['incite']['redirect'];
      unset($_SESSION['incite']['redirect']);
    } else {
      //unknown error occur so we set default message
      $this->view->redirect = array('status' => 'error',
      'message' => 'The server could not complete the request. You will be redirected to homepage',
      'url' => '/m4j/incite',
      'time' => '5');
    }
  }

  public function viewAction() {
    $this->_helper->db->setDefaultModelName('Item');

    $this->view->category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');

    if ($this->_hasParam('id')) {
      $this->populateDataForViewTask();
    } else {
      $this->populateViewSearchResults();
    }
  }

  public function populateDataForViewTask() {
    $this->_helper->viewRenderer('viewid');

    //make sure the document is valid
    $document_id = $this->_getParam('id');
    $this->view->documentId = $document_id;
    $document = $this->_helper->db->find($document_id);

    if ($document != null) {
      $this->view->document = $document;
      $this->view->image_url = get_image_url_for_item($document);
    }

    //find the transcription for the document
    $transcription = getNewestTranscription($document_id);
    $this->view->hasTranscription = false;

    if (!empty($transcription)) {
      $this->view->hasTranscription = true;
      $this->view->transcription_id = $transcription['id'];
      $this->view->transcription = $transcription['transcription'];
    }

    //find the tagged transcription of the document
    $this->view->hasTaggedTranscriptionForNewestTranscription = false;

    if (hasTaggedTranscriptionForNewestTranscription($document_id)) {
      $taggedTranscriptions = getAllTaggedTranscriptions($document_id);
      $this->view->taggedTranscription = $taggedTranscriptions[count($taggedTranscriptions)-1];
      $this->view->hasTaggedTranscriptionForNewestTranscription = true;
    }

    //find if a document has been connected
    $this->view->hasBeenConnected = false;
    $subjectsForDocument = getAllSubjectsOnId($document_id);

    $pos_subs = array();
    $neg_subs = array();
    $distinct_subNames = array();
    foreach ((array) $subjectsForDocument as $subject) {
      if (!isset($distinct_subNames[$subject['subject_name']]))
      $distinct_subNames[$subject['subject_name']] = $subject['subject_name'];

      if ($subject['is_positive']) {
        if (!isset($pos_subs[$subject['subject_name']]))
        $pos_subs[$subject['subject_name']] = array();

        array_push($pos_subs[$subject['subject_name']], $subject['user_id']);
      } else {
        if (!isset($neg_subs[$subject['subject_name']]))
        $neg_subs[$subject['subject_name']] = array();

        array_push($neg_subs[$subject['subject_name']], $subject['user_id']);
      }
    }

    if (!empty($subjectsForDocument)) {
      $this->view->hasBeenConnected = true;
      $this->view->subjectNames = $distinct_subNames;
      $this->view->positive_subjects = $pos_subs;
      $this->view->negative_subjects = $neg_subs;
    }
  }


  public function populateViewSearchResults() {
    $all_doc_ids = getTranscribableDocuments();
    $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

    if (isSearchQuerySpecifiedViaGet()) {
      $searched_item_ids = getSearchResultsViaGetQuery();
      $document_ids = array_slice(array_intersect(array_values($all_doc_ids), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
    } else {
      $document_ids = array_slice(array_values($all_doc_ids), 0, MAXIMUM_SEARCH_RESULTS);
      $this->view->query_str = "";
    }
    return $document_ids;
    // $this->createSearchResultPages($document_ids, 'Documents');
  }


  public function contributeAction() {
    $task = 'transcribe';
    if (isset($_GET['task'])) {
      if ($_GET['task'] === 'tag') {
        $task = 'tag';
      } else if ($_GET['task'] === 'connect') {
        $task = 'connect';
      }
    }
    $this->view->task_type = $task;
  }
  public function presurveyAction() {
        if ($this->getRequest()->isPost()) {
            $name   = $_POST['name'];
            $age    = $_POST['age'];
            $gender = $_POST['gender'];
            $majors = $_POST['majors'];

            //Save demographics
            $db = DB_Connect::connectDB();
            $stmt = $db->prepare("UPDATE study2 SET name = ?, age = ?, gender = ?, majors = ?, time1_start = NOW() WHERE id = ?");
            $stmt->bind_param("sissi", $name, $age, $gender, $majors, $_SESSION['study2']['id']);
            $stmt->execute();
            $stmt->close();
            $db->close();

            //All set. Move to next task!
            $_SESSION['study2']['task_seq']++;
            $task_seq = $_SESSION['study2']['task_seq'];
            $urls = $_SESSION['study2']['urls'];
            $this->redirect($urls[$task_seq]);
        }
  }

  public function postsurveyAction() {
        if ($this->getRequest()->isPost()) {
            
            //Save 
            $db = DB_Connect::connectDB();
            $stmt = $db->prepare("UPDATE study2 SET q1 = ?, q2 = ?, q3 = ?, q4 = ?, q5 = ?, q6 = ?, q71 = ?, q72 = ?, q73 = ?, q74 = ?, q81 = ?, q82 = ?, q83 = ?, q84 = ?, tlx_men = ?, tlx_phy = ?, tlx_tem = ?, tlx_per = ?, tlx_eff = ?, tlx_fru = ?, tlx_int = ?, user_feedback = ? WHERE id = ?");
            $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiisi", $_POST['q1'], $_POST['q2'], $_POST['q3'], $_POST['q4'], $_POST['q5'], $_POST['q6'], $_POST['q71'], $_POST['q72'], $_POST['q73'], $_POST['q74'], $_POST['q81'], $_POST['q82'], $_POST['q83'], $_POST['q84'], $_POST['tlx_men'], $_POST['tlx_phy'], $_POST['tlx_tem'], $_POST['tlx_per'], $_POST['tlx_eff'], $_POST['tlx_fru'], $_POST['tlx_int'], $_POST['user_feedback'], $_SESSION['study2']['id']);
            $stmt->execute();
            $stmt->close();
            $db->close();

            //All set. Move to next task!
            $_SESSION['study2']['task_seq']++;
            $task_seq = $_SESSION['study2']['task_seq'];
            $urls = $_SESSION['study2']['urls'];
            $this->redirect($urls[$task_seq]);
        }
  }

  public function completeAction() {
  }
  public function errorAction() {
        echo 'Something went wrong!';
        die();
  }
    public function wfdatadumpingtothisAction() {

        echo '<pre>';
        //print_r(check_task_performance(1, 1125, 54));
        //print_r(check_task_performance(2, 1125, 54));
        //1125 -> 4; 1126 -> 5; 1127 -> 15 (item_id -> document_id)
        print_r(check_task_performance(3, 1125, 54));
        echo '</pre>';
        die();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT `id`, `workflow`, doc1, doc2, doc3, task1, task2, task3, TIME_TO_SEC(TIMEDIFF(time1_end, time1_start)), TIME_TO_SEC(TIMEDIFF(time2_end, time2_start)), TIME_TO_SEC(TIMEDIFF(time3_end, time3_start)), attempts, age, q1, q2, q3, q4, q5, q6, q71, q72, q73, q74, q81, q82, q83, q84, tlx_men, tlx_phy, tlx_tem, tlx_per, tlx_eff, tlx_fru, tlx_int, user_feedback FROM `study2` WHERE id > 1 AND id <= 16");
        $stmt->bind_result($trial_id, $workflow, $doc1, $doc2, $doc3, $task1, $task2, $task3, $timediff1, $timediff2, $timediff3, $ttempts, $age, $q1, $q2, $q3, $q4, $q5, $q6, $q71, $q72, $q73, $q74, $q81, $q82, $q83, $q84, $tlx_men, $tlx_phy, $tlx_tem, $tlx_per, $tlx_eff, $tlx_fru, $tlx_int, $user_feedback);
        
        $stmt->execute();
        echo 'trial_id,workflow,doc1,doc2,doc3,task1,task2,task3,timediff1,timediff2,timediff3,atempts,age,q1,q2,q3,q4,q5,q6,q71,q72,q73,q74,q81,q82,q83,q84,tlx_men,tlx_phy,tlx_tem,tlx_per,tlx_eff,tlx_fru,tlx_int,user_feedback'."\n";
        while (($result = $stmt->fetch()) != null) {
            echo $trial_id.",".$workflow.",".$doc1.",".$doc2.",".$doc3.",".$task1.",".$task2.",".$task3.",".$timediff1.",".$timediff2.",".$timediff3.",".$ttempts.",".$age.",".$q1.",".$q2.",".$q3.",".$q4.",".$q5.",".$q6.",".$q71.",".$q72.",".$q73.",".$q74.",".$q81.",".$q82.",".$q83.",".$q84.",".$tlx_men.",".$tlx_phy.",".$tlx_tem.",".$tlx_per.",".$tlx_eff.",".$tlx_fru.",".$tlx_int.",\"".str_replace('"', '""', $user_feedback)."\"\n";
        }
        $stmt->close();
        $db->close();
        die();
    }
}

function check_transcription_performance($doc, $user_id)
{
    //Get user's answer
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT transcribed_text FROM omeka_incite_transcriptions WHERE document_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $doc, $user_id);
    $stmt->bind_result($user_transcription);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();

    //Get gold standard
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT transcribed_text FROM omeka_incite_transcriptions WHERE document_id = ? AND user_id = 26');
    $stmt->bind_param('i', $doc);
    $stmt->bind_result($gold_transcription);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();

    //Calculate performance
    $u_word_array = preg_split('/\s+/', $user_transcription);
    $g_word_array = preg_split('/\s+/', $gold_transcription);

    $diff = array();
    for ($i = 0; $i < count($g_word_array); $i++) {
        if ($g_word_array[$i]  != (isset($u_word_array[$i]) ? $u_word_array[$i] : "")) {
            $diff[] = $i;
        }
    }
    return array('gold' => preg_split('/\s+/', $gold_transcription), 'user' => preg_split('/\s+/', $user_transcription), 'diff' => $diff, 'word_edit_distance' => word_edit_distance($u_word_array, $g_word_array));
}

function check_tag_performance($doc, $user_id)
{
    //Get user's answer
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT tagged_transcription FROM omeka_incite_tagged_transcriptions WHERE item_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $doc, $user_id);
    $stmt->bind_result($user_transcription);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();

    //Get gold standard
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT tagged_transcription FROM omeka_incite_tagged_transcriptions WHERE item_id = ? AND user_id = 26');
    $stmt->bind_param('i', $doc);
    $stmt->bind_result($gold_transcription);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();

    //Calculate performance
    $user_tags = tag_extraction($user_transcription);
    $gold_tags = tag_extraction($gold_transcription);
    $missing_tags = array_diff($gold_tags['tag_names'], $user_tags['tag_names']);
    $extra_tags = array_diff($user_tags['tag_names'], $gold_tags['tag_names']);

    $num_correct_tags = 0;
    $num_correct_names = 0;
    for ($i = 0; $i < count($user_tags['tag_names']); $i++) {
        if (($idx = array_search($user_tags['tag_names'][$i], $gold_tags['tag_names'])) != FALSE) {
            if ($gold_tags['categories'][$idx] == $user_tags['categories'][$i]) {
                $num_correct_tags++;
            }
            $num_correct_names++;
        }
    }
    $num_error_tags = count($user_tags['tag_names'])-$num_correct_tags+count($missing_tags);
    return array('gold' => $gold_tags, 'user' => $user_tags, 'mistakes' => $num_error_tags, 'cat_mistakes' => $num_correct_names-$num_correct_tags);
}

function check_connect_performance($doc, $user_id)
{
    $doc_mapping = array(1125 => 4, 1126 => 5, 1127 => 29);
    $doc_id = $doc_mapping[$doc];
    //Get user's answer
    $user_subjects_relevance = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT subject_concept_id, is_positive FROM omeka_incite_documents_subject_conjunction WHERE document_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $doc_id, $user_id);
    $stmt->bind_result($subject, $relevance);
    $stmt->execute();
    while ($stmt->fetch()) {
        $user_subjects_relevance[$subject] = $relevance;
    }
    $stmt->close();
    $db->close();

    //Get gold standard
    $gold_subjects_relevance = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare('SELECT subject_concept_id, is_positive FROM omeka_incite_documents_subject_conjunction WHERE document_id = ? AND user_id = 26');
    $stmt->bind_param('i', $doc_id);
    $stmt->bind_result($subject, $relevance);
    $stmt->execute();
    while ($stmt->fetch()) {
        $gold_subjects_relevance[$subject] = $relevance;
    }
    $stmt->close();
    $db->close();

    //Calculate performance
    $relevance_diff = array();
    $revelance_total_diff = 0;
    $revelance_diff_threshold = 2;
    foreach (array_keys($user_subjects_relevance) as $subject) {
        $relevance_diff[$subject] = abs($gold_subjects_relevance[$subject] - $user_subjects_relevance[$subject]);
        $relevance_total_diff += ($relevance_diff[$subject] > $relevance_diff_threshold ? abs($relevance_diff[$subject]-$relevance_diff_threshold) : 0);
    }

    return array('relevance_diff' => $relevance_diff, 'relevance_total_diff' => $relevance_total_diff);
}

function check_task_performance($task, $doc, $user_id)
{
    switch($task) {
        case 1: return check_transcription_performance($doc, $user_id);
        case 2: return check_tag_performance($doc, $user_id);
        case 3: return check_connect_performance($doc, $user_id);
        default: return "n/a";
    }
}
