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
    return $result;
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

    public function createSearchResultPages($item_ids, $task_name) {
        if (count($item_ids) <= 0) {
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
        $total_pages = ceil(count($item_ids) / $max_records_to_show);

        if (count($item_ids) > 0) {
            for ($i = ($current_page - 1) * $max_records_to_show; $i < count($item_ids); $i++) {
                if ($records_counter++ >= $max_records_to_show)
                    break;
                $records[] = $this->_helper->db->find($item_ids[$i]);
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

    /**
     * Form the transcribe page matadata and recieve the posted data form the view
     */
    public function transcribeAction() {
        $this->_helper->db->setDefaultModelName('Item');
        $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

        if ($this->_hasParam('id')) {
            $this->view->doc_id = $this->_getParam('id');
            if (!isset($_SESSION['Incite']['tutorial_trans'])) {
                $this->_helper->viewRenderer('transcribetutorial');
                $this->view->doc_id = $this->_getParam('id');
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

    /**
     * Save the posted transcription to the database
     */
    public function saveTranscription() {
        $workingGroupId = getWorkingGroupID();

        $trans = new InciteTranscription;
        $trans->item_id = $this->_getParam('id');
        $trans->user_id = $_SESSION['Incite']['USER_DATA']['id'];
        $trans->working_group_id = $workingGroupId;
        $trans->transcribed_text = $_POST['transcription'];
        $trans->summarized_text = $_POST['summary'];
        $trans->tone = $_POST['tone'];
        $trans->type = 1; //1: default user input
        $trans->save();

        $_SESSION['Incite']['previous_task'] = 'transcribe';

        if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
            $_SESSION['incite']['message'] = 'Transcription successful! Tag this document now, or find another document to transcribe by clicking <a href="'.getFullInciteUrl().'/documents/transcribe?'.$_POST['query_str'].'">here</a>.';
        } else {
            $_SESSION['incite']['message'] = 'Transcription successful! Tag this document now, or find another document to transcribe by clicking <a href="'.getFullInciteUrl().'/documents/transcribe">here</a>.';
        }
        $itemID = $this->_getParam('id');
        $ready_tag = 1;
        $ready_connect = 0;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT `id` FROM `omeka_incite_available_list` WHERE `item_id` = ?");
        $stmt->bind_param("i", $itemID );
        $stmt->bind_result($id);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $db->close();
        if ($id != null) //the transcription is updated
        {
            $db = DB_Connect::connectDB();
            $stmt1 = $db->prepare("UPDATE `omeka_incite_available_list` SET `ready_tag`=?,`ready_connect`=? WHERE `item_id` = $itemID");
            $stmt1->bind_param("ii", $ready_tag, $ready_connect);
            $stmt1->execute();
            $stmt1->close();
            $db->close();
        }
        else // the transcription is newly added
        {
            $db = DB_Connect::connectDB();
            $stmt2 = $db->prepare("INSERT INTO omeka_incite_available_list VALUES (NULL, ?, ?, ?)");
            $stmt2->bind_param("iii", $itemID, $ready_tag, $ready_connect);
            $stmt2->execute();
            $stmt2->close();
            $db->close();
        }

        if ($_POST['link'] == 1) {
            if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
                $this->redirect('/incite/documents/tag/'.$this->_getParam('id').'?'.$_POST['query_str']);
            }
            else {
                $this->redirect('/incite/documents/tag/'.$this->_getParam('id'));
            }
        }
        else if ($_POST['link'] == 2) {
            $this->redirect('/incite/documents/transcribe');
        }
    }

    /**
     * Populate the data for the transcribe task
     * Get the metadata according to the id
     * Get the lastes transcription 
     */
    public function populateDataForTranscribeTask() {
        $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));
        $item_id = $this->_getParam('id');

        if ($this->view->document_metadata != null) {
            if ($this->view->document_metadata->getFile() == null) {
                $this->redirect('incite/documents/transcribe');
            }

            $this->_helper->viewRenderer('transcribeid');
            $trans = $this->_helper->db->getTable('InciteTranscription')->findNewestByItemId($item_id);
            $this->view->latest_transcription = $trans;
            $this->view->is_being_edited = !empty($this->view->latest_transcription);

            if ($this->view->is_being_edited) {
                $this->view->revision_history = $this->_helper->db->getTable('InciteTranscription')->findKNewestWithUserInfoByItemId($item_id);
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

    /**
     * Get all the transcribe documents according to the posted query
     */
    public function populateTranscribeSearchResults() {
        $item_wo_trans_ids = $this->_helper->db->getTable('InciteTranscription')->findFirstKItemIdsWithoutTranscriptions();

        if (isSearchQuerySpecifiedViaGet()) {
            $searched_item_ids = getSearchResultsViaGetQuery();
            $item_ids = array_slice(array_intersect(array_values($item_wo_trans_ids), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

        } else {
            $item_ids = array_slice(array_values($item_wo_trans_ids), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = "";

        }


        return $item_ids;
    }

    /**
     * Form the tag matadata and recieve the posted data form the view
     */
    public function tagAction() {
        $this->_helper->db->setDefaultModelName('Item');
        $tagcategory_table = $this->_helper->db->getTable('InciteTagcategory');
        $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
        $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));
        if ($this->_hasParam('id')) {
            $this->view->doc_id = $this->_getParam('id');
            if (!isset($_SESSION['Incite']['tutorial_tag'])) {
                //NER
                //$categories = getAllCategories();
                $categories = $tagcategory_table->findAllCategoriesWithSubcategories();
                $ner_entity_table = array();
                $tag_id_counter = 0;

                $oldwd = getcwd();
                chdir('./plugins/Incite/stanford-ner-2015-04-20/');
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

                    if (isset($entities[1]) && count($entities[1]) > 0) {
                        $uniq_entities = $entities[1];
                        foreach ($uniq_entities as $entity) {
                        }
                    }
                }

                chdir($oldwd);
                $this->view->entities = $ner_entity_table;
                $this->view->transcription = $transformed_transcription;
                $this->_helper->viewRenderer('tagtutorial');
                $this->view->doc_id = $this->_getParam('id');
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

    /**
     * Save the posted tags to the database
     */
    public function saveTags() {
        $entities = json_decode($_POST["entities"], true);
        $workingGroupId = getWorkingGroupID();
        $item_id = $this->_getParam('id');

        $trans = new InciteTaggedTranscription;
        $trans->item_id = $item_id;
        $trans->transcription_id = $_POST['transcription_id'];
        $trans->user_id = $_SESSION['Incite']['USER_DATA']['id'];
        $trans->working_group_id = $workingGroupId;
        $trans->tagged_transcription = $_POST['tagged_doc'];
        $trans->type = 1; //1: default user input
        $trans->save();

        //$tagged_trans_id = createTaggedTranscription($this->_getParam('id'), $_POST['transcription_id'], $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $_POST['tagged_doc']);

        for ($i = 0; $i < sizeof($entities); $i++) {
            //createTag($_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $entities[$i]['entity'], $entities[$i]['category'], $entities[$i]['subcategory'], $entities[$i]['details'], $this->_getParam('id'), $trans->id, 1);

            //$userID, $groupID, $tag_text, $category, $subcategory, $description, $itemID, $taggedTransID, $type
            $tag = new InciteTag;
            $tag->item_id = $item_id;
            $tag->user_id = $_SESSION['Incite']['USER_DATA']['id'];
            $tag->working_group_id = $workingGroupId;
            $tag->tag_text = $entities[$i]['entity'];
            $tag->category_id = $entities[$i]['category'];
            $tag->description = $entities[$i]['details'];
            $tag->tagged_trans_id = $trans->id;
            $tag->type = 1; //default type
            $tag->save();

            $item_tag = new InciteItemsTags;
            $item_tag->item_id = $item_id;
            $item_tag->tag_id = $tag->id;
            $item_tag->save();
            for ($j = 0; $j < count($entities[$i]['subcategory']); $j++) {
                $tag_tagsubcategory = new InciteTagsTagsubcategory;
                $tag_tagsubcategory->tag_id = $tag->id;
                $tag_tagsubcategory->subcategory_id = $entities[$i]['subcategory'][$j];
                $tag_tagsubcategory->save();
            }
        }
        $question_arr = json_decode($_POST["questions"], true);
        for ($i = 0; $i < sizeof($question_arr); $i++) {
            saveQuestions($tagged_trans_id, $i + 1, $question_arr[$i], 1);
        }
        $_SESSION['Incite']['previous_task'] = 'tag';


        if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
            $_SESSION['incite']['message'] = 'Tagging completed! Connect this document now, or find another document to tag by clicking <a href="'.getFullInciteUrl().'/documents/tag?'.$_POST['query_str'].'">here</a>.';
        } else {
            $_SESSION['incite']['message'] = 'Tagging completed! Connect this document now, or find another document to tag by clicking <a href="'.getFullInciteUrl().'/documents/tag">here</a>.';
        }
        $itemID = $this->_getParam('id');
        $ready_tag = 0;
        $ready_connect = 1;
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE `omeka_incite_available_list` SET `ready_tag`=?,`ready_connect`=? WHERE `item_id` = $itemID");
        $stmt->bind_param("ii", $ready_tag, $ready_connect);
        $stmt->execute();
        $stmt->close();
        $db->close();
        if ($_POST['link'] == 1) {
            if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
                $this->redirect('/incite/documents/connect/'.$this->_getParam('id').'?'.$_POST['query_str']);
            }
            $this->redirect('/incite/documents/connect/'.$this->_getParam('id'));
        }
        else if ($_POST['link'] == 2) {
            $this->redirect('/incite/documents/tag');
        }
    }

    /**
     * Populate the data for the tag task
     * Get the transcription of this document
     * Generate the tags 
     */
    public function populateDataForTagTask() {
        $tag_id_counter = 0;
        $tagcategory_table = $this->_helper->db->getTable('InciteTagcategory');
        $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));

        $item_id = $this->_getParam('id');

        if ($this->view->document_metadata != null) {
            if ($this->view->document_metadata->getFile() == null) {
                echo 'no image';
            }


            //Get the transcription for the document
            $newestTranscription = $this->_helper->db->getTable('InciteTranscription')->findNewestByItemId($item_id);
            if (!empty($newestTranscription)) {
                $this->view->transcription_id = $newestTranscription->id;
                $this->view->transcription = $newestTranscription->transcribed_text;
            } else {
                $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe this document first. Or if you want to find another document to tag, please click <a href="'.getFullInciteUrl().'/documents/tag">here</a>.';

                if (isset($this->view->query_str) && $this->view->query_str !== "")
                    $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id').'?'.$this->view->query_str);
                else
                    $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id'));
            }

            $this->_helper->viewRenderer('tagid');
            $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
            //$categories = getAllCategories();
            $categories = $tagcategory_table->findAllCategoriesWithSubcategories();
            $this->view->categories = $categories;
            $this->view->category_id_name_table = $tagcategory_table->getCategoryIdToNameMap();
            $this->view->category_name_it_table = $tagcategory_table->getCategoryNameToIdMap();
            $category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');

            //Do we already have tags or do we need to generate them via NER
            $newest_tagged_trans = $this->_helper->db->getTable('InciteTaggedTranscription')->findNewestByTranscriptionId($newestTranscription->id);
            if (isset($newest_tagged_trans)) {
                $this->view->is_being_edited = true;
                $this->view->revision_history = getTaggedTranscriptionRevisionHistory($this->_getParam('id'));
                $this->view->transcription = migrateTaggedDocumentFromV1toV2($newest_tagged_trans->tagged_transcription);
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

    /**
     * Find the document for the tag task according to the specified query
     */
    public function populateTagSearchResults() {
        if (isSearchQuerySpecifiedViaGet()) {
            $searched_item_ids = getSearchResultsViaGetQuery();
            $item_ids = array_slice(getDocumentsWithTranscriptions(), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
        } else {
            $item_ids = array_slice(array_values(getDocumentsWithoutTagsForLatestTranscription()), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = "";
        }
        return $item_ids;
    }

    /**
     * Form the connect page matadata and recieve the posted data form the view
     */
    public function connectAction() {
        $this->_helper->db->setDefaultModelName('Item');
        $this->view->category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');
        $this->view->document_metadata = $this->_helper->db->find($this->_getParam('id'));
        if ($this->_hasParam('id')) {
            $this->view->doc_id = $this->_getParam('id');
            if (!isset($_SESSION['Incite']['tutorial_conn'])) {
                $this->view->subjects = getAllSubjectConcepts();
                $this->view->transcription = 'The Fourth of July was celebrated in <em id="tag_id_0" class="location tagged-text">Berlin</em>, by a German Methodist Sunday school. Two or three hundred children marched from the the <em id="tag_id_1" class="location tagged-text">Methodist Chapel</em> to the house of our Minister, <em id="tag_id_0" class="person tagged-text">Mr. Wright</em>, who joined the procession and accompanied it to the public garden, where the scholars amused themselves as our Sunday school do here on similar occasions.';
                $this->_helper->viewRenderer('connecttutorial');
                $this->view->doc_id = $this->_getParam('id');
                return;
            }
            if ($this->getRequest()->isPost()) {
                $this->saveConnections();
            }
            $this->populateDataForConnectTask();
        }
    }

    /**
     * Save the ratings in the connect task to the database
     */
    public function saveConnections() {
        $item_id = $this->_getParam('id');
        $all_subject_ids = getAllSubjectConceptIds();
        $workingGroupId = getWorkingGroupID();
        $subject = "subject";
        $newest_tagged_trans = $this->_helper->db->getTable('InciteTaggedTranscription')->findNewestByTranscriptionId($item_id);
        for($i = 1; $i < 10; $i++) {
            $sub = $subject.$i;
            addConnectRating($_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $i, $_POST[$sub], $item_id, 1, $newest_tagged_trans->id);
        }
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("DELETE FROM `omeka_incite_available_list` WHERE `item_id` = $item_id");
        $stmt->execute();
        $stmt->close();
        $db->close();
        //connect by multiselection
        if (isset($_POST['subjects']) || isset($_POST['no_subjects'])) {
            foreach ((array) $all_subject_ids as $subject_id) {

                if (in_array($subject_id, (isset($_POST['subjects']) ? $_POST['subjects'] : array())))
                    addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $newest_tagged_trans->id, 1);
                else
                    addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $newest_tagged_trans->id, 0);
            }
        } 
        else { //connect by tags
            if (isset($_POST['subject']) && $_POST['connection'] == 'true')
                addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $newest_tagged_trans->id, 1);
            else if (isset($_POST['subject']) && $_POST['connection'] == 'false')
                addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $workingGroupId, $newest_tagged_trans->id, 0);
        }
        $_SESSION['Incite']['previous_task'] = 'connect';


        if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
            $_SESSION['incite']['message'] = 'Connecting successful! You can now select a document to transcribe from the list below or find a document to <a href="'.getFullInciteUrl().'/documents/tag?'.$_POST['query_str'].'">tag</a> or <a href="'.getFullInciteUrl().'/documents/connect?'.$_POST['query_str'].'">connect</a>.';
        } else {
            $_SESSION['incite']['message'] = 'Connecting successful! You can now select a document to transcribe from the list below or find a document to <a href="'.getFullInciteUrl().'/documents/tag">tag</a> or <a href="'.getFullInciteUrl().'/documents/connect">connect</a>.';
        }

        if ($_POST['link'] == 1) {
            if (isset($_POST['query_str']) && $_POST['query_str'] !== "") {
                $this->redirect('/incite/documents/transcribe?'.$_POST['query_str']);
            }
            else {
                $this->redirect('/incite/documents/transcribe');
            }
        }
        else if ($_POST['link'] == 2) {
            $this->redirect('/incite/documents/connect');
        }

    }

    /**
     * Populate the data for the connect task
     * Get the latest transcription of this document
     * Get the latest tagged transcription of this document
     */
    public function populateDataForConnectTask() {
        $item_id = $this->_getParam('id');
        $is_connectable_by_tags = true;
        $this->view->document_metadata = $this->_helper->db->find($item_id);

        $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();


        if ($this->view->document_metadata != null) {
            if ($this->view->document_metadata->getFile() == null) {
                $_SESSION['incite']['message'] = 'Unfortunately, there is no such document. Please search again!';
                return;
            }

            $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
            $this->view->subjects = getAllSubjectConcepts();

            //Filter out untranscribed documents
            $newest_trans = $this->_helper->db->getTable('InciteTranscription')->findNewestByItemId($item_id);
            $newest_tagged_trans = $this->_helper->db->getTable('InciteTaggedTranscription')->findNewestByItemId($newest_trans->id);
            if (empty($newest_trans)) {
                if (isset($this->view->query_str) && $this->view->query_str !== "") {
                    $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
                    $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id').'?'.$this->view->query_str);
                } else {
                    $_SESSION['incite']['message'] = 'Unfortunately, the document has not been transcribed yet. Please help transcribe the document first before connecting. Or if you want to find another document to connect, please click <a href="'.getFullInciteUrl().'/documents/connect?'.$this->view->query_str.'">here</a>.';
                    $this->redirect('/incite/documents/transcribe/'.$this->_getParam('id'));
                }
            }

            //Gets the latest tagged transcription and the most recently marked subjects, if they exist
            if (isset($newest_tagged_trans)) {
                $this->view->transcription =  migrateTaggedDocumentFromV1toV2($newest_tagged_trans->tagged_transcription);

                $this->view->newest_n_subjects = getNewestSubjectsForNewestTaggedTranscription($item_id);
                $this->view->is_being_edited = !empty($this->view->newest_n_subjects);

                if ($this->view->is_being_edited) {
                    $this->view->revision_history = getConnectionRevisionHistory($item_id);
                }

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

    /**
     * Get all document of connect task accorging to the specified query
     */
    public function populateConnectSearchResults() {
        $connectable_documents = getDocumentsWithoutConnectionsForLatestTaggedTranscription();
        $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();

        if (isSearchQuerySpecifiedViaGet()) {
            $searched_item_ids = getSearchResultsViaGetQuery();
            $item_ids = array_slice(array_intersect(array_values($connectable_documents), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
        } else {
            $item_ids = array_slice(array_values($connectable_documents), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = "";
        }

        return $item_ids;
    }
    /**
     * Display the overall status
     */
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
        $item_id = $this->_getParam('id');
        $this->view->documentId = $item_id;
        $document = $this->_helper->db->find($item_id);

        if ($document != null) {
            $this->view->document = $document;
            $this->view->image_url = get_image_url_for_item($document);
        }

        //find the transcription for the document
        $newest_trans = $this->_helper->db->getTable('InciteTranscription')->findNewestByItemId($item_id);
        $newest_tagged_transn = $this->_helper->db->getTable('InciteTranscription')->findNewestByItemId($newest_tran->id);
        $this->view->hasTranscription = false;

        if (!empty($newest_trans)) {
            $this->view->hasTranscription = true;
            $this->view->transcription_id = $newest_trans->id;
            $this->view->transcription = $newest_trans->transcribed_text;
        }

        //find the tagged transcription of the document
        $this->view->hasTaggedTranscriptionForNewestTranscription = false;

        if (isset($newest_tagged_trans)) {
            $this->view->taggedTranscription = $newest_tagged_trans;
            $this->view->hasTaggedTranscriptionForNewestTranscription = true;
        }

        //find if a document has been connected
        $this->view->hasBeenConnected = false;
        $subjectsForDocument = getAllSubjectsOnId($item_id);

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
            $item_ids = array_slice(array_intersect(array_values($all_doc_ids), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
        } else {
            $item_ids = array_slice(array_values($all_doc_ids), 0, MAXIMUM_SEARCH_RESULTS);
            $this->view->query_str = "";
        }
        return $item_ids;
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
