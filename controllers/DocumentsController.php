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
    $result = str_replace('<' . $tagname . '>', '<span style="background-color:' . $color . ';">', $result);
    $result = str_replace('</' . $tagname . '>', '</span>', $result);
    return $result;
}

function sort_strlen($str1, $str2) {
    return strlen($str2) - strlen($str1);
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
        setup_session();
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
                //no such item
                $this->_forward('index');
            }
        } else {
            //default view without id
        }
    }

    public function transcribeAction() {

        if ($this->getRequest()->isPost()) {
            //save transcription and summary to database
            if ($this->_hasParam('id')) {

                createTranscription($this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], $_POST['transcription'], $_POST['summary'], $_POST['tone']);
                $_SESSION['Incite']['previous_task'] = 'transcribe';
                //Since we only need one copy now, we redirect the same user to next task of the same document.
                //$this->redirect('incite/documents/tag/' . $this->_getParam('id'));
                $_SESSION['incite']['redirect'] = array(
                        'status' => 'complete_transcribe', 
                        'message' => 'Congratulations! You just completed a transcription! Now, you are able to tag the document you just transcribed! You will be redirected to it', 
                        'url' => INCITE_PATH.'documents/tag/'.$this->_getParam('id'),
                        'time' => '10');

                $this->redirect('incite/documents/redirect');
            }
        }

        //Fake comments
        //getCommentsForDocOnTask(doc_id, task_id)
        $this->view->comments = array(
            array('id' => 1, 'username' => 'Kurt', 'time' => 'three week ago', 'content' => 'Interesting!'),
            array('id' => 2, 'username' => 'Amit', 'time' => 'two weeks ago', 'content' => 'Agreed!'),
            array('id' => 3, 'username' => 'Vijay', 'time' => 'two weeks ago', 'content' => 'Agreed, too!')
        );

        $this->_helper->db->setDefaultModelName('Item');
        if ($this->_hasParam('id')) {
            $record = $this->_helper->db->find($this->_getParam('id'));
            if ($record != null) {
                if ($record->getFile() == null) {
                    //no image to transcribe so redirect to documents that need to be transcribed
                    $this->redirect('incite/documents/transcribe');
                }
                $this->_helper->viewRenderer('transcribeid');
                $this->view->transcription = $record;
            } else {
                //no such document so redirect to documents that need to be transcribed
                $this->redirect('incite/documents/transcribe');
            }
        } else {  //has_param
            //default: fetch documents that need to be transcribed
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];

            if (isSearchQuerySpecifiedViaGet()) {
                $searched_item_ids = getSearchResultsViaGetQuery();
                $document_ids = array_slice(array_intersect(array_values(getDocumentsWithoutTranscription()), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
            } else {
                $document_ids = array_slice(array_values(getDocumentsWithoutTranscription()), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = "";
            }

            if (count($document_ids) <= 0 ) {
                if (isSearchQuerySpecifiedViaGet()) {
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'errorNoTranscriptionWithQuery', 
                            'message' => 'Unfortunately, there are no document that can be transcribed based on your search requirements. We are now searching documents that can be transcribed without any search requirements. You will be redirected to the search results',
                            'url' => INCITE_PATH.'documents/transcribe',
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                } else {
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'errorNoTranscription', 
                            'message' => 'Currently there are no documents to transcribe. Please come back later. You will be redirected to homepage', 
                            'url' => '/m4j/incite',
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                }
            }

            $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
            $total_pages = ceil(count($document_ids) / $max_records_to_show);
            $records_counter = 0;
            $records = array();

            if (count($document_ids) > 0) {
                for ($i = ($current_page - 1) * $max_records_to_show; $i < count($document_ids); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($document_ids[$i]);
                }
            }
            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;

            //Assign all documents that need to be transcribed to view!
            if ($records != null && count($records) > 0) {
                $this->view->assign(array('Transcriptions' => $records));
            } else {
                //no need to transcribe
            }
        }
    }

    public function tagAction() {
        if ($this->getRequest()->isPost()) {
            //save a tag to database
            if ($this->_hasParam('id')) {
                $entities = json_decode($_POST["entities"], true);
                removeAllTagsFromDocument($this->_getParam('id'));
                for ($i = 0; $i < sizeof($entities); $i++) {
                    createTag($_SESSION['Incite']['USER_DATA']['id'], $entities[$i]['entity'], $entities[$i]['category'], $entities[$i]['subcategory'], $entities[$i]['details'], $this->_getParam('id'));
                }
                $_SESSION['Incite']['previous_task'] = 'tag';
                //Since we only need one copy now, we redirect the same user to next task of the same document.
                //$this->redirect('incite/documents/connect/' . $this->_getParam('id'));
                $_SESSION['incite']['redirect'] = array(
                        'status' => 'complete_tag', 
                        'message' => 'Congratulations! You just completed tagging! Now, we are searching to see if there are any related documents via the tags you just provided! You will be redirected to the results', 
                        'url' => INCITE_PATH.'documents/connect/'.$this->_getParam('id'),
                        'time' => '10');

                $this->redirect('incite/documents/redirect');
            }
        }

        $this->_helper->db->setDefaultModelName('Item');
        if ($this->_hasParam('id')) {
            //$this->view->isTagged = isDocumentTagged($this->_getParam('id'));
            $record = $this->_helper->db->find($this->_getParam('id'));

            if ($record != null) {
                if ($record->getFile() == null) {
                    //no image to transcribe
                    echo 'no image';
                }
                $transcription = getIsAnyTranscriptionApproved($this->_getParam('id'));
                $this->view->transcription = "No transcription";
                if ($transcription != null) {
                    $this->view->transcription = getTranscriptionText($transcription[0]);
                } else {
                    //Redirect to transcribe task if there is no transcription available
                    //$this->redirect('incite/documents/transcribe/' . $this->_getParam('id'));
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'error_noTranscriptionForTag', 
                            'message' => 'Unfortunately, we could not find any transcription of this document for you to tag. Please help transcribe the document and then tag it! You will be redirected to the transcribe page',
                            'url' => INCITE_PATH.'documents/transcribe/'.$this->_getParam('id'),
                            'time' => '10');

                    $this->redirect('incite/documents/redirect');
                }
                $this->_helper->viewRenderer('tagid');
                $this->view->tag = $record;

                //Check entities:
                //  1) is tagged already?  Yes: skip the task; No: do the following
                //  2) (to be implemented) pull similar entities in the database based on searching in transcription
                //  3) NER to get entities
                //Initialize attributes for entities
                $categories = array('ORGANIZATION', 'PERSON', 'LOCATION', 'EVENT');
                $category_colors = array('ORGANIZATION' => 'red', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'gray');
                if (isDocumentTagged($this->_getParam('id'))) {
                    //$this->view->allTags = getAllTagInformation($this->_getParam('id'));
                    $allTags = getAllTagInformation($this->_getParam('id'));
                    $entities = array();
                    $entity_names = array();
                    $entity_category = array();
                    $colored_transcription = $this->view->transcription;
                    foreach ((array) $allTags as $tag) {
                        $subs = array();
                        foreach ((array) $tag['subcategories'] as $sub) {
                            $subs[] = str_replace(' ', '', $sub);
                        }
                        $entities[] = array('entity' => $tag['tag_text'], 'category' => $tag['category_name'], 'subcategories' => $subs, 'details' => $tag['description']);
                        $entity_names[] = $tag['tag_text'];
                        $entity_category[$tag['tag_text']] = $tag['category_name'];
                    }
                    usort($entity_names, 'sort_strlen');
                    foreach ((array) $entity_names as $name) {
                        $colored_transcription = str_replace($name, '<' . strtoupper($entity_category[$name]) . '>' . $name . '</' . strtoupper($entity_category[$name]) . '>', $colored_transcription);
                    }
                    foreach ($categories as $category) {
                        $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                    }
                    $this->view->entities = $entities;
                    $this->view->transcription = $colored_transcription;
                } else {
                    //NER: start
                    $ner_entity_table = array();

                    //running NER
                    $oldwd = getcwd();
                    chdir('./plugins/Incite/stanford-ner-2015-04-20/');
                    if (!file_exists('../tmp/ner/' . $this->_getParam('id'))) {
                        $this->view->file = 'not exist';
                        $ner_input = fopen('../tmp/ner/' . $this->_getParam('id'), "w") or die("unable to open transcription");
                        fwrite($ner_input, $this->view->transcription);
                        fclose($ner_input);
                        system("java -mx600m -cp stanford-ner.jar edu.stanford.nlp.ie.crf.CRFClassifier -loadClassifier classifiers/english.muc.7class.distsim.crf.ser.gz -outputFormat inlineXML -textFile " . '../tmp/ner/' . $this->_getParam('id') . ' > ' . '../tmp/ner/' . $this->_getParam('id') . '.ner');
                    }
                    $nered_file = fopen('../tmp/ner/' . $this->_getParam('id') . '.ner', "r");
                    $nered_file_size = filesize('../tmp/ner/' . $this->_getParam('id') . '.ner');
                    $parsed_text = "";
                    if ($nered_file_size != 0)
                        $parsed_text = fread($nered_file, $nered_file_size);

                    fclose($nered_file);

                    //parsing results
                    $colored_transcription = $parsed_text;

                    foreach ($categories as $category) {
                        $entities = getTextBetweenTags($parsed_text, $category);
                        $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                        if (isset($entities[1]) && count($entities[1]) > 0) {
                            $uniq_entities = array_unique($entities[1]);
                            foreach ($uniq_entities as $entity) {
                                $ner_entity_table[] = array('entity' => $entity, 'category' => $category, 'subcategories' => array(), 'details' => '', 'color' => $category_colors[$category]);
                            }
                        }
                    }

                    chdir($oldwd);
                    //NER:end

                    $this->view->entities = $ner_entity_table;
                    $this->view->transcription = $colored_transcription;
                } //end of isDocumentTagged
                $this->view->category_colors = $category_colors;
            } else {
                //no such document
                echo 'no such document';
            }
        } else { //has_param
            //default view without id
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];

            if (isSearchQuerySpecifiedViaGet()) {
                $searched_item_ids = getSearchResultsViaGetQuery();
                $document_ids = array_slice(array_intersect(array_values(getDocumentsWithoutTag()), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
            } else {
                $document_ids = array_slice(array_values(getDocumentsWithoutTag()), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = "";
            }

            if (count($document_ids) <= 0) {
                if (isSearchQuerySpecifiedViaGet()) {
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'error_noDocToTagWithQuery', 
                            'message' => 'Unfortunately, there are no document that can be tagged based on your search requirements. We are now searching documents that can be tagged without any search requirements. You will be redirected to the search results',
                            'url' => INCITE_PATH.'documents/tag',
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                } else {
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'error_noDocToTag', 
                            'message' => 'Currently there are no documents to tag. Please come back later. You will be redirected to documents that need to be transcribed', 
                            'url' => INCITE_PATH.'documents/transcribe',
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                }
            }

            $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
            $records_counter = 0;
            $records = array();
            $total_pages = ceil(count($document_ids) / $max_records_to_show);

            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;

            if (count($document_ids) > 0) {
                for ($i = ($current_page - 1) * $max_records_to_show; $i < count($document_ids); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($document_ids[$i]);
                }
            }

            if ($records != null && count($records) > 0) {
                $this->view->assign(array('Tags' => $records));
            } else {
                //no need to tag 
            }
        }
    }

    public function connectAction() {
        $this->_helper->db->setDefaultModelName('Item');
        $subjectConceptArray = getAllSubjectConcepts();
        $randomSubjectInt = rand(0, sizeof($subjectConceptArray) - 1);
        $subject_id = 1;
        $subjectName = getSubjectConceptOnId($randomSubjectInt);
        $subjectDef = getDefinition($subjectName[0]);

        //Choosing a subject to test with some fake data to test view
        $this->view->subject = $subjectName[0];
        $this->view->subject_definition = $subjectDef;
        $this->view->entities = array('liberty', 'independence');
        $this->view->related_documents = array();

        //From tagAction
        $category_colors = array('ORGANIZATION' => 'red', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'gray');
        $this->view->category_colors = $category_colors;

        if ($this->getRequest()->isPost()) {
            //save a connection to database
            if ($this->_hasParam('id')) {
                $all_subject_ids = getAllSubjectConceptIds();
                if (isset($_POST['subjects']))
                    foreach ((array) $all_subject_ids as $subject_id) {
                        if (in_array($subject_id, $_POST['subjects']))
                            addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], 1);
                        else
                            addConceptToDocument($subject_id, $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], 0);
                    }
                /*
                if (isset($_POST['subject']) && $_POST['connection'] == 'true') {
                    addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], 1);
                } else if (isset($_POST['subject']) && $_POST['connection'] == 'false') {
                    addConceptToDocument($_POST['subject'], $this->_getParam('id'), $_SESSION['Incite']['USER_DATA']['id'], 0);
                } else {
                    
                }
                */
                $_SESSION['Incite']['previous_task'] = 'connect';
                //Since we only need one copy now and connect is the final task, we redirect the same user to next document to start a new transcription
                //$this->redirect('incite/documents/transcribe');
                $_SESSION['incite']['redirect'] = array(
                        'status' => 'complete_connect', 
                        'message' => 'Congratulations! You just this document to a concept! Now, we are searching to see if there are still related documents that you can connect! You will be redirected to the results', 
                        'url' => INCITE_PATH.'documents/connect/'.$this->_getParam('id'),
                        'time' => '10');

                $this->redirect(REDIRECTOR_URL);
            }
        }

        if ($this->_hasParam('id')) {
            $is_connectable_by_tags = false;
            $record = $this->_helper->db->find($this->_getParam('id'));
            if ($record != null) {
                if ($record->getFile() == null) {
                    //no image to transcribe
                    echo 'no image';
                }
                $transcription = getIsAnyTranscriptionApproved($this->_getParam('id'));
                $this->view->transcription = "No transcription";
                if ($transcription != null) {
                    $this->view->transcription = getTranscriptionText($transcription[0]);
                } else {
                    
                }
                $categories = array('ORGANIZATION', 'PERSON', 'LOCATION', 'EVENT');
                $category_colors = array('ORGANIZATION' => 'red', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'gray');
                if (isDocumentTagged($this->_getParam('id'))) {
                    //$this->view->allTags = getAllTagInformation($this->_getParam('id'));
                    $allTags = getAllTagInformation($this->_getParam('id'));
                    $entities = array();
                    $entity_names = array();
                    $entity_category = array();
                    $colored_transcription = $this->view->transcription;
                    $tag_ids = array();
                    foreach ((array) $allTags as $tag) {
                        $subs = array();
                        $tag_ids[] = $tag['tag_id'];
                        foreach ((array) $tag['subcategories'] as $sub) {
                            $subs[] = str_replace(' ', '', $sub);
                        }
                        $entities[] = array('entity' => $tag['tag_text'], 'category' => $tag['category_name'], 'subcategories' => $subs, 'details' => $tag['description']);
                        $entity_names[] = $tag['tag_text'];
                        $entity_category[$tag['tag_text']] = $tag['category_name'];
                    }
                    /* //replaced by user selection
                    usort($entity_names, 'sort_strlen');
                    foreach ((array) $entity_names as $name) {
                        $colored_transcription = str_replace($name, '<' . strtoupper($entity_category[$name]) . '>' . $name . '</' . strtoupper($entity_category[$name]) . '>', $colored_transcription);
                    }
                    //*/
                    foreach ($categories as $category) {
                        $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                    }

                    $related_documents = findRelatedDocumentsViaAtLeastNCommonTags($this->_getParam('id'));
                    if (count($related_documents) == 0) {
                        $this->_helper->viewRenderer('connectbymultiselection');
                        $this->view->subjects = getAllSubjectConcepts();
                        $this->view->connection = $record;
                        //for this part, we can random the destination of the tasks!
                        /*
                        $_SESSION['incite']['redirect'] = array(
                                'status' => 'error_noDocToConnect', 
                                'message' => 'Unfortunately, we could not find related documents for this document at this moment. In the meanwhile, you can try connecting other documents or help transcribe/tag other documents so that we can find related documents! Now, we are searching to see if we can find documents that need connections. You will be redirected to the results', 
                                'url' => INCITE_PATH.'documents/connect/',
                                'time' => '10');

                        $this->redirect(REDIRECTOR_URL);
                        //*/
                    }

                    //Get subject candidates
                    $subject_candidates = getBestSubjectCandidateList($related_documents);
                    $self_subjects = getAllSubjectsOnId($this->_getParam('id'));
                    $subject_related_documents = array();
                    if (count($subject_candidates) <= 0) {
                        //None of the related documents have subjects!
                        $this->_helper->viewRenderer('connectbymultiselection');
                        $this->view->subjects = getAllSubjectConcepts();
                        $this->view->connection = $record;
                        /*
                        $_SESSION['incite']['redirect'] = array(
                                'status' => 'error_noSubjectCandidates', 
                                'message' => 'Unfortunately, no potential subjects were found for this document. We are searching to see if there are documents that you can help connect. You will be redirected to the results', 
                                'url' => INCITE_PATH.'documents/connect/',
                                'time' => '10');

                        $this->redirect(REDIRECTOR_URL);
                        //*/
                        //echo 'no connection found!';
                        //die();
                    } else {
                        for ($i = 0; $i < count($subject_candidates); $i++) {
                            if (!in_array($subject_candidates[$i]['subject'], $self_subjects)) {
                                $this->view->subject_id = $subject_candidates[$i]['subject_id'];
                                $this->view->subject = $subject_candidates[$i]['subject'];
                                $this->view->subject_definition = $subject_candidates[$i]['subject_definition'];
                                $subject_related_documents = $subject_candidates[$i]['ids'];
                                if (count($subject_related_documents) > 0)
                                    break;
                            }
                        }
                    }
                    if (count($subject_related_documents) == 0) {
                        $this->_helper->viewRenderer('connectbymultiselection');
                        $this->view->subjects = getAllSubjectConcepts();
                        $this->view->connection = $record;
                        /*
                        $_SESSION['incite']['redirect'] = array(
                                'status' => 'error_allSubjectsConnected', 
                                'message' => 'Unfortunately, no potential subjects were found for this document. We are searching to see if there are other documents that you can help connect. You will be redirected to the results', 
                                'url' => INCITE_PATH.'documents/connect/',
                                'time' => '10');

                        $this->redirect(REDIRECTOR_URL);
                        //*/
                    }
                    $docs_for_common_tags = array_merge($subject_related_documents, array($this->_getParam('id')));
                    //fetch documents!    
                    $actual_entities = findCommonTagNames($docs_for_common_tags);
                    $this->view->related_documents = array();
                    for ($i = 0; $i < count($subject_related_documents); $i++) {
                        $this->view->related_documents[] = $this->_helper->db->find($subject_related_documents[$i]);
                    }
                    $this->view->entities = $actual_entities;
                    $this->view->transcription = $colored_transcription;
                } else {  //if (isDocumentTagged($this->_getParam('id')))
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'error_noTag', 
                            'message' => 'Unfortunately, this document has not been tagged so we are unable to find related documents. Please help tag this document first. You will be redirected to the tag page', 
                            'url' => INCITE_PATH.'documents/tag/'.$this->_getParam('id'),
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                } //if (isDocumentTagged($this->_getParam('id')))
                if ($is_connectable_by_tags) {
                    $this->_helper->viewRenderer('connectbytags');
                    $this->view->connection = $record;
                }
            } else {
                //no such document
                echo 'no such document';
            }
        } else { //has_param
            //default view without id
            $connectable_documents = getConnectableDocuments();

            if (isSearchQuerySpecifiedViaGet()) {
                $searched_item_ids = getSearchResultsViaGetQuery();
                $document_ids = array_slice(array_intersect(array_values($connectable_documents), $searched_item_ids), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = getSearchQuerySpecifiedViaGetAsString();
            } else {
                $document_ids = array_slice(array_values($connectable_documents), 0, MAXIMUM_SEARCH_RESULTS);
                $this->view->query_str = "";
            }

            if (count($document_ids) <= 0) {
                if (isSearchQuerySpecifiedViaGet()) {
                    $_SESSION['incite']['redirect'] = array(
                            'status' => 'error', 
                            'message' => 'Unfortunately, there are no document that can be connected based on your search requirements. We are now searching documents that can be connected without any search requirements. You will be redirected to the search results',
                            'url' => INCITE_PATH.'documents/connect',
                            'time' => '10');

                    $this->redirect(REDIRECTOR_URL);
                } else
                    $document_ids = array_slice(array_values(getDocumentsWithoutTag()), 0, MAXIMUM_SEARCH_RESULTS);

                if (count($document_ids) > 0) {
                    $message = 'Currently there is no document to connect. Please come back later. You will be redirected to documents that need to be tagged';
                    $url = INCITE_PATH.'documents/tag';
                } else {
                    $message = 'Currently there is no document to connect. Please come back later. You will be redirected to documents that need to be transcribed';
                    $url = INCITE_PATH.'documents/transcribe';
                }

                //no need to transcribe
                $_SESSION['incite']['redirect'] = array(
                        'status' => 'error', 
                        'message' => $message,
                        'url' => $url,
                        'time' => '10');

                $this->redirect(REDIRECTOR_URL);
                
            }

            $records = array();
            for ($i = 0; $i < count($document_ids); $i++) {
                $records[] = $this->_helper->db->find($document_ids[$i]);
            }

            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];
            $max_records_to_show = SEARCH_RESULTS_PER_PAGE;
            $records_counter = 0;
            $total_pages = ceil(count($connectable_documents) / $max_records_to_show);

            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;

            if ($records != null && count($records) > 0) {
                $this->view->assign(array('Connections' => $records));
            } else {
            }
        }
    }

    public function discussAction() {
        if ($this->hasParam($id)) {
            $this->_helper->db->setDefaultModelName('Item');
            $subjectConceptArray = getAllSubjectConcepts();
            $randomSubjectInt = rand(0, sizeof($subjectConceptArray) - 1);
            $subject_id = 1;
            $subjectName = getSubjectConceptOnId($randomSubjectInt);
            $subjectDef = getDefinition($subjectName[0]);

            //Choosing a subject to test with some fake data to test view
            $this->view->subject = $subjectName[0];
            $this->view->subject_definition = $subjectDef;
            $this->view->entities = array('liberty', 'independence');
            $this->view->related_documents = array();

            $record = $this->_helper->db->find($this->_getParam('id'));
            $isReply = $_POST['IS_REPLY'];
            if ($isReply == 0) {
                $_questionText = $POST['QUESTION_TEXT'];
            } else {
                //do something
            }
        }//if has_param
    }//discussAction()
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
}
