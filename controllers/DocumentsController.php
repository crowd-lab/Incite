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
    $this->redirect('incite/documents/transcribe');
  }

  public function showAction() {
    $this->_helper->db->setDefaultModelName('Item');

    if ($this->_hasParam('id')) {
      $record = $this->_helper->db->find($this->_getParam('id'));
      if ($record != null) {
        $this->view->assign(array('Item' => $record));
      } else {
        $this->_forward('index');
      }
    }
  }
  public function transcribeAction() {
      $this->_helper->db->setDefaultModelName('Item');


      if ($this->_hasParam('id')) {
          if (!isset($_SESSION['Incite']['tutorial_trans'])) {
              $this->_helper->viewRenderer('transcribetutorial');
              return;
          }

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

    createTranscription($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $_POST['transcription'], $_POST['summary'], $_POST['tone']);
    $_SESSION['Incite']['previous_task'] = 'transcribe';

    if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
      $_SESSION['incite']['message'] = 'Transcription successful! Tag this document now, or find another document to transcribe by clicking <a href="'.getFullInciteUrl().'/documents/transcribe?'.$_POST['query_str'].'">here</a>.';
      $this->redirect('/incite/documents/tag/'.$this->_getParam('id').'?'.$_POST['query_str']);
    } else {
      $_SESSION['incite']['message'] = 'Transcription successful! Tag this document now, or find another document to transcribe by clicking <a href="'.getFullInciteUrl().'/documents/transcribe">here</a>.';
      $this->redirect('/incite/documents/tag/'.$this->_getParam('id'));
    }
  }

  public function populateDataForTranscribeTask() {
    $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

    if ($this->view->document_metadata != null) {
      if ($this->view->document_metadata->getFile() == null) {
        $this->redirect('incite/documents/transcribe');
      }

      $this->_helper->viewRenderer('transcribeid');
      $this->view->latest_transcription = getNewestTranscription($this->_getParam('id'));
      $this->view->is_being_edited = !empty($this->view->latest_transcription);

      if ($this->view->is_being_edited) {
        $this->view->revision_history = getTranscriptionRevisionHistory($this->_getParam('id'));
      }

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
          if (!isset($_SESSION['Incite']['tutorial_tag'])) {
              //NER
              $categories = getAllCategories();
              $ner_entity_table = array();
              $tag_id_counter = 0;

              $oldwd = getcwd();
              chdir('./plugins/Incite/stanford-ner-2015-04-20/');

              $this->view->file = 'not exist';
              system("java -mx600m -cp stanford-ner.jar edu.stanford.nlp.ie.crf.CRFClassifier -loadClassifier classifiers/english.muc.7class.distsim.crf.ser.gz -outputFormat inlineXML -textFile " . '../tmp/ner/tutorial_trans > ../tmp/ner/tutorial_trans.ner');

              $nered_file = fopen('../tmp/ner/tutorial_trans.ner', "r");
              $nered_file_size = filesize('../tmp/ner/tutorial_trans.ner');
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
                          $ner_entity_table[] = array('entity' => $entity, 'category' => strtoupper($category['name']), 'subcategories' => array(), 'details' => '', 'tag_id' => $tag_id_counter++);
                      }
                  }
              }

              chdir($oldwd);

              $this->view->entities = $ner_entity_table;
              $this->view->transcription = $transformed_transcription;
              $this->_helper->viewRenderer('tagtutorial');
              return;
          }
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

    createTaggedTranscription($this->_getParam('id'), $_POST['transcription_id'], $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $_POST['tagged_doc']);
    $_SESSION['Incite']['previous_task'] = 'tag';


    if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
      $_SESSION['incite']['message'] = 'Tagging completed! Connect this document now, or find another document to tag by clicking <a href="'.getFullInciteUrl().'/documents/tag?'.$_POST['query_str'].'">here</a>.';
      $this->redirect('/incite/documents/connect/'.$this->_getParam('id').'?'.$_POST['query_str']);
    } else {
      $_SESSION['incite']['message'] = 'Tagging completed! Connect this document now, or find another document to tag by clicking <a href="'.getFullInciteUrl().'/documents/tag">here</a>.';
      $this->redirect('/incite/documents/connect/'.$this->_getParam('id'));
    }
  }

  public function populateDataForTagTask() {
    $tag_id_counter = 0;
    $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

    if ($this->view->document_metadata != null) {
      if ($this->view->document_metadata->getFile() == null) {
        echo 'no image';
      }

      //Get the transcription for the document
      $newestTranscription = getNewestTranscription($this->_getParam('id'));
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
      $category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');

      //Do we already have tags or do we need to generate them via NER
      if (hasTaggedTranscriptionForNewestTranscription($this->_getParam('id'))) {
        $this->view->is_being_edited = true;
        $this->view->revision_history = getTaggedTranscriptionRevisionHistory($this->_getParam('id'));
        $transcriptions = getAllTaggedTranscriptions($this->_getParam('id'));
        $this->view->transcription = migrateTaggedDocumentFromV1toV2($transcriptions[count($transcriptions)-1]);
      } else {
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

    //connect by multiselection
    if (isset($_POST['subjects']) || isset($_POST['no_subjects'])) {
      foreach ((array) $all_subject_ids as $subject_id) {
        if (in_array($subject_id, (isset($_POST['subjects']) ? $_POST['subjects'] : array())))
        addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 1);
        else
        addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 0);
      }
    } else { //connect by tags
      if (isset($_POST['subject']) && $_POST['connection'] == 'true')
      addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 1);
      else if (isset($_POST['subject']) && $_POST['connection'] == 'false')
      addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, getLatestTaggedTranscriptionID($this->_getParam('id')), 0);
    }
    $_SESSION['Incite']['previous_task'] = 'connect';

    if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
      $_SESSION['incite']['message'] = 'Connecting successful! You can now select a document to transcribe from the list below or find a document to <a href="'.getFullInciteUrl().'/documents/tag?'.$_POST['query_str'].'">tag</a> or <a href="'.getFullInciteUrl().'/documents/connect?'.$_POST['query_str'].'">connect</a>.';
      $this->redirect('/incite/documents/transcribe?'.$_POST['query_str']);
    } else {
      $_SESSION['incite']['message'] = 'Connecting successful! You can now select a document to transcribe from the list below or find a document to <a href="'.getFullInciteUrl().'/documents/tag">tag</a> or <a href="'.getFullInciteUrl().'/documents/connect">connect</a>.';
      $this->redirect('/incite/documents/transcribe');
    }
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
      $newestTranscription = getNewestTranscription($this->_getParam('id'));
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
      if (hasTaggedTranscriptionForNewestTranscription($this->_getParam('id'))) {
        $transcriptions = getAllTaggedTranscriptions($this->_getParam('id'));
        $this->view->transcription =  migrateTaggedDocumentFromV1toV2($transcriptions[count($transcriptions)-1]);

        $this->view->newest_n_subjects = getNewestSubjectsForNewestTaggedTranscription($this->_getParam('id'));
        $this->view->is_being_edited = !empty($this->view->newest_n_subjects);

        if ($this->view->is_being_edited) {
          $this->view->revision_history = getConnectionRevisionHistory($this->_getParam('id'));
        }

        $this->_helper->viewRenderer('connectbymultiselection');
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
}
