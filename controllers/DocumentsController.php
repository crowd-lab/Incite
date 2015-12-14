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
function getTextBetweenTags($string, $tagname)
{
    $pattern = "/<$tagname>(.*?)<\/$tagname>/";
    preg_match_all($pattern, $string, $matches);
    return $matches;
}
function colorTextBetweenTags($string, $tagname, $color)
{
    $result = $string;
    $result = str_replace('<'.$tagname.'>', '<span style="background-color:'.$color.';">', $result);
    $result = str_replace('</'.$tagname.'>', '</span>', $result);
    return $result;
}
function sort_strlen($str1, $str2)
{
    return strlen($str2) - strlen($str1);
}

function findRelatedDocumentsViaTags($self_id, $minimum_common_tags=3)
{
    $entity_names = getTagNamesOnId($self_id);
    //Targets
    $target_minimum_common_tags = $minimum_common_tags;
    $target_entities = $entity_names;

    //Actual values
    $actual_entities = $entity_names;
    $actual_minimum_common_tags = $target_minimum_common_tags;
    if ($actual_minimum_common_tags > count($actual_entities))
        $actual_minimum_common_tags = count($actual_entities);

    $related = array();
    while(count($related = searchClosestMatchByTagName($actual_entities, $actual_minimum_common_tags)) < 2 && $actual_minimum_common_tags > 0)
        $actual_minimum_common_tags--;
    if ($actual_minimum_common_tags > 0) {

    } else if ($actual_minimum_common_tags == 0) {
        //no documents with common tags
    } else {
        //error!
    }
    return array_values(array_diff($related, array($self_id)));
}


class Incite_DocumentsController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        require_once("Incite_Transcription_Table.php");
        require_once("Incite_Tag_Table.php");
        require_once("Incite_Subject_Concept_Table.php");
        require_once("Incite_Users_Table.php");
        require_once("Incite_Questions_Table.php");
        require_once("Incite_Replies_Table.php");
        if (!DB_Connect::isLoggedIn()) {
            $GLOBALS['USERID'] = -1; //user is anonymous
        } else {
            $userArray = $_SESSION['Incite']['USER_DATA'];
            $GLOBALS['USERID'] = $userArray[0];
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

                if ($GLOBALS['USERID'] == -1) {
                    createGuestSession();
                    $userArray = $_SESSION['Incite']['USER_DATA'];
                    $GLOBALS['USERID'] = $userArray[0];
                }
                createTranscription($this->_getParam('id'), $GLOBALS['USERID'], $_POST['transcription'], $_POST['summary']);
            }
        }

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
        } else {
            //default: fetch documents that need to be transcribed
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];
            $document_ids = array_slice(array_values(getDocumentsWithoutTranscription()), 0, 24);
            $max_records_to_show = 8;
            $total_pages = ceil(count($document_ids)/$max_records_to_show);
            $records_counter = 0;
            $records = array();

            if (count($document_ids) > 0) {
                for ($i = ($current_page-1)*$max_records_to_show; $i < count($document_ids); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($document_ids[$i]);
                }
            }
            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;

            //Assign all documents that need to be transcribed to view!
            if ($records != null) {
                $this->view->assign(array('Transcriptions' => $records));
            } else {
                //no need to transcribe
                echo 'no need to transcribe';
                die();
            }
        }
    }

    public function tagAction() {

        if ($this->getRequest()->isPost()) {
            //save a tag to database
            if ($this->_hasParam('id')) {
                if ($GLOBALS['USERID'] == -1) {
                    createGuestSession();
                    $userArray = $_SESSION['Incite']['USER_DATA'];
                    $GLOBALS['USERID'] = $userArray[0];
                }
                $entities = json_decode($_POST["entities"], true);
                removeAllTagsFromDocument($this->_getParam('id'));
                for ($i = 0; $i < sizeof($entities); $i++) {
                    createTag($GLOBALS['USERID'], $entities[$i]['entity'], $entities[$i]['category'], $entities[$i]['subcategory'], $entities[$i]['details'], $this->_getParam('id'));
                }
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
                    $this->redirect('incite/documents/transcribe/'.$this->_getParam('id'));
                }
                $this->_helper->viewRenderer('tagid');
                $this->view->tag = $record;

                //Check entities:
                //  1) is tagged already?  Yes: skip the task; No: do the following
                //  2) (to be implemented) pull similar entities in the database based on searching in transcription
                //  3) NER to get entities

                //Initialize attributes for entities
                $categories = array('ORGANIZATION', 'PERSON', 'LOCATION', 'EVENT');
                $category_colors = array('ORGANIZATION'=>'red', 'PERSON'=>'orange', 'LOCATION'=>'yellow', 'EVENT' => 'gray');
                if (isDocumentTagged($this->_getParam('id'))) {
                    //$this->view->allTags = getAllTagInformation($this->_getParam('id'));
                    $allTags = getAllTagInformation($this->_getParam('id'));
                    $entities = array();
                    $entity_names = array();
                    $entity_category = array();
                    $colored_transcription = $this->view->transcription;
                    foreach ((array)$allTags as $tag) {
                        $subs = array();
                        foreach ((array)$tag['subcategories'] as $sub) {
                            $subs[] = str_replace(' ', '', $sub);
                        }
                        $entities[] = array('entity' => $tag['tag_text'], 'category' => $tag['category_name'], 'subcategories' => $subs, 'details' => $tag['description']);
                        $entity_names[] = $tag['tag_text'];
                        $entity_category[$tag['tag_text']] = $tag['category_name'];
                    }
                    usort($entity_names, 'sort_strlen');
                    foreach ((array)$entity_names as $name) {
                        $colored_transcription = str_replace($name, '<'.strtoupper($entity_category[$name]).'>'.$name.'</'.strtoupper($entity_category[$name]).'>', $colored_transcription);
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
                    if (!file_exists('../tmp/ner/'.$this->_getParam('id'))) {
                        $this->view->file = 'not exist';
                        $ner_input = fopen('../tmp/ner/'.$this->_getParam('id'), "w") or die("unable to open transcription");
                        fwrite($ner_input, $this->view->transcription);
                        fclose($ner_input);
                        system("java -mx600m -cp stanford-ner.jar edu.stanford.nlp.ie.crf.CRFClassifier -loadClassifier classifiers/english.muc.7class.distsim.crf.ser.gz -outputFormat inlineXML -textFile ".'../tmp/ner/'.$this->_getParam('id').' > '.'../tmp/ner/'.$this->_getParam('id').'.ner');
                    }
                    $nered_file = fopen('../tmp/ner/'.$this->_getParam('id').'.ner', "r");
                    $parsed_text = fread($nered_file, filesize('../tmp/ner/'.$this->_getParam('id').'.ner'));
                    fclose($nered_file);

                    //parsing results
                    $colored_transcription = $parsed_text;

                    foreach ($categories as $category) {
                        $entities = getTextBetweenTags($parsed_text, $category);
                        $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                        if (isset($entities[1]) && count($entities[1]) > 0) {
                            $uniq_entities = array_unique($entities[1]);
                            foreach ($uniq_entities as $entity) {
                                    $ner_entity_table[] = array('entity' => $entity, 'category' => $category, 'subcategory' => '', 'details' => '', 'color' => $category_colors[$category]);
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
        } else {
            //default view without id
            $current_page = 1;
            if (isset($_GET['page']))
                $current_page = $_GET['page'];
            $document_ids = array_values(getDocumentsWithoutTag());
            $max_records_to_show = 8;
            $records_counter = 0;
            $records = array();
            $total_pages = ceil(count($document_ids)/$max_records_to_show);

            $this->view->total_pages = $total_pages;
            $this->view->current_page = $current_page;

            if (count($document_ids) > 0) {
                for ($i = ($current_page-1)*$max_records_to_show; $i < count($document_ids); $i++) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($document_ids[$i]);
                }
            }

            if ($records != null) {
                $this->view->assign(array('Tags' => $records));
            } else {
                //no need to transcribe
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
        $category_colors = array('ORGANIZATION'=>'red', 'PERSON'=>'orange', 'LOCATION'=>'yellow', 'EVENT' => 'gray');
        $this->view->category_colors = $category_colors;

        if ($this->getRequest()->isPost()) {
            //save a connection to database
            if ($this->_hasParam('id')) {
                if ($GLOBALS['USERID'] == -1) {
                    createGuestSession();
                    $userArray = $_SESSION['Incite']['USER_DATA'];
                    $GLOBALS['USERID'] = $userArray[0];
                }   //ready to connect subject to a document
                $userID = -1;
                if (isset($_POST['subject']) && $_POST['connection'] == 'true') {
                    addConceptToDocument($_POST['subject'], $this->_getParam('id'), $userID, 1);
                } else if (isset($_POST['subject']) && $_POST['connection'] == 'false') {
                    addConceptToDocument($_POST['subject'], $this->_getParam('id'), $userID, 0);
                } else {
                }
            }
        }

        if ($this->_hasParam('id')) {
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
                $category_colors = array('ORGANIZATION'=>'red', 'PERSON'=>'orange', 'LOCATION'=>'yellow', 'EVENT' => 'gray');
                if (isDocumentTagged($this->_getParam('id'))) {
                    //$this->view->allTags = getAllTagInformation($this->_getParam('id'));
                    $allTags = getAllTagInformation($this->_getParam('id'));
                    $entities = array();
                    $entity_names = array();
                    $entity_category = array();
                    $colored_transcription = $this->view->transcription;
                    $tag_ids = array();
                    foreach ((array)$allTags as $tag) {
                        $subs = array();
                        $tag_ids[] = $tag['tag_id'];
                        foreach ((array)$tag['subcategories'] as $sub) {
                            $subs[] = str_replace(' ', '', $sub);
                        }
                        $entities[] = array('entity' => $tag['tag_text'], 'category' => $tag['category_name'], 'subcategories' => $subs, 'details' => $tag['description']);
                        $entity_names[] = $tag['tag_text'];
                        $entity_category[$tag['tag_text']] = $tag['category_name'];
                    }
                    usort($entity_names, 'sort_strlen');
                    foreach ((array)$entity_names as $name) {
                        $colored_transcription = str_replace($name, '<'.strtoupper($entity_category[$name]).'>'.$name.'</'.strtoupper($entity_category[$name]).'>', $colored_transcription);
                    }
                    foreach ($categories as $category) {
                        $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                    }
                    
                    $related_documents = findRelatedDocumentsViaTags($this->_getParam('id'), 3);
                    if (count($related_documents) == 0) {
                        //no connections at all so redirect to documents with at least some connections
                        $this->redirect('incite/documents/connect');
                    }

                    //Get subject candidates
                    $subject_candidates = getBestSubjectCandidateList($related_documents);
                    $self_subjects = getAllSubjectsOnId($this->_getParam('id'));
                    $subject_related_documents = array();
                    if (count($subject_candidates) <= 0) {
                        //Need other method because there is no suggested subject
                        echo 'no connection found!';
                        die();
                    } else {
                        for ($i = 0; $i < count($subject_candidates); $i++) {
                            if (!in_array($subject_candidates[$i]['subject'], $self_subjects)) {
                                $this->view->subject_id = $subject_candidates[$i]['subject_id'];
                                $this->view->subject = $subject_candidates[$i]['subject'];
                                $this->view->subject_definition = $subject_candidates[$i]['subject_definition'];
                                $subject_related_documents = $subject_candidates[$i]['ids'];
                                break;
                            }
                        }
                    }
                    if (count($subject_related_documents) == 0) {
                        echo 'no connection needed';
                        die();
                    }
                    //fetch documents!    
                    $actual_entities = findCommonTagNames($subject_related_documents);
                    $this->view->related_documents = array();
                    for ($i = 0; $i < count($subject_related_documents); $i++) {
                        $this->view->related_documents[] = $this->_helper->db->find($subject_related_documents[$i]);
                    }
                    $this->view->entities = $actual_entities;
                    $this->view->transcription = $colored_transcription;
                    $this->view->subject_id = $subject_id;
                } else {
                    $this->redirect('incite/documents/tag/'.$this->_getParam('id'));
                }
                $this->_helper->viewRenderer('connectid');
                $this->view->connection = $record;
            } else {
                //no such document
                echo 'no such document';
            }
        } else {
            //default view without id
            $all_tagged_documents = getAllTaggedDocuments();
            $connectable_documents = array();
            for ($i = 0; $i < count($all_tagged_documents); $i++) {
                $related_documents = findRelatedDocumentsViaTags($i);
                if (count($related_documents) == 0)
                    continue;

                $subject_candidates = getBestSubjectCandidateList($related_documents);
                if (count($subject_candidates) == 0)
                    continue;

                $self_subjects = getAllSubjectsOnId($i);
                for ($j = 0; $j < count($subject_candidates); $j++) {
                    if (!in_array($subject_candidates[$j]['subject'], $self_subjects)) {
                        if (count($subject_candidates[$j]['ids']) > 0) {
                            $connectable_documents[] = $i;
                            continue 2;
                        }
                    }
                }
            }
            $records = array();
            for ($i = 0; $i < count($connectable_documents); $i++) {
                $records[] = $this->_helper->db->find($connectable_documents[$i]);
            }

            //check if there is really exacit one image file for each item
            if ($records != null) {
                $this->view->assign(array('Connections' => $records));
            } else {
                //no need to transcribe
            }
        }
    }

    public function discussAction()
    {
        if ($this->hasParam($id))
        {
            $isReply = $_POST['IS_REPLY'];
            if ($isReply == 0)
            {
                $_questionText = $POST['QUESTION_TEXT'];
            }
            else
            {
                
            }
        }
    }
}
