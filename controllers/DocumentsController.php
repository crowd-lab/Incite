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
            $document_ids = getDocumentsWithoutTranscription();
            $max_records_to_show = 8;
            $records_counter = 0;
            $records = array();

            if (count($document_ids) > 0) {
                foreach ($document_ids as $id) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($id);
                }
            }

            //Assign all documents that need to be transcribed to view!
            if ($records != null) {
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
                    foreach ((array)$allTags as $tag) {
                        $subs = array();
                        foreach ((array)$tag['subcategories'] as $sub) {
                            $subs[] = str_replace(' ', '', $sub);
                        }
                        $entities[] = array('entity' => $tag['tag_text'], 'category' => $tag['category_name'], 'subcategories' => $subs, 'details' => $tag['description']);
                    }
                    $this->view->entities = $entities;
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
                            foreach ($entities[1] as $entity) {
                                if ($category == 'PERSON')
                                    $ner_entity_table[] = array('entity' => $entity, 'category' => 'PEOPLE', 'subcategory' => '', 'details' => '', 'color' => $category_colors[$category]);
                                else
                                    $ner_entity_table[] = array('entity' => $entity, 'category' => $category, 'subcategory' => '', 'details' => '', 'color' => $category_colors[$category]);
                            }
                        }
                    }

                    chdir($oldwd);
                    //NER:end

                    $this->view->entities = $ner_entity_table;
                    $this->view->transcription = $colored_transcription;
                    $this->view->category_colors = $category_colors;
                } //end of isDocumentTagged
            } else {
                //no such document
                echo 'no such document';
            }
        } else {
            //default view without id
            $document_ids = getDocumentsWithoutTag();
            $max_records_to_show = 5;
            $records_counter = 0;
            $records = array();

            if (count($document_ids) > 0) {
                foreach ($document_ids as $id) {
                    if ($records_counter++ >= $max_records_to_show)
                        break;
                    $records[] = $this->_helper->db->find($id);
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
        $subjectName = getSubjectConceptOnId($randomSubjectInt);
        $subjectDef = getDefinition($subjectName[0]);

        //Choosing a subject to test with some fake data to test view
        $this->view->subject = $subjectName[0];
        $this->view->subject_definition = $subjectDef;
        $this->view->entities = array('liberty', 'independence');
        $this->view->related_documents = array($this->_helper->db->find(15), $this->_helper->db->find(77), $this->_helper->db->find(22));

        //From tagAction
        $category_colors = array('ORGANIZATION'=>'red', 'PERSON'=>'orange', 'LOCATION'=>'yellow');
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
                addConceptToDocument($randomSubjectInt, $this->_getParam('id'), $userID);
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
                $oldwd = getcwd();
                chdir('./plugins/Incite/stanford-ner-2015-04-20/');
                $nered_file = fopen('../tmp/ner/'.$this->_getParam('id').'.ner', "r");
                $parsed_text = fread($nered_file, filesize('../tmp/ner/'.$this->_getParam('id').'.ner'));
                fclose($nered_file);
                //parsing results
                $categories = array('ORGANIZATION', 'PERSON', 'LOCATION');
                $category_colors = array('ORGANIZATION'=>'red', 'PERSON'=>'orange', 'LOCATION'=>'yellow');
                $colored_transcription = $parsed_text;

                foreach ($categories as $category) {
                    $entities = getTextBetweenTags($parsed_text, $category);
                    $colored_transcription = colorTextBetweenTags($colored_transcription, $category, $category_colors[$category]);
                    if (isset($entities[1]) && count($entities[1]) > 0) {
                        foreach ($entities[1] as $entity) {
                            if ($category == 'PERSON')
                                $ner_entity_table[] = array('entity' => $entity, 'category' => 'PEOPLE', 'subcategory' => '', 'details' => '', 'color' => $category_colors[$category]);
                            else
                                $ner_entity_table[] = array('entity' => $entity, 'category' => $category, 'subcategory' => '', 'details' => '', 'color' => $category_colors[$category]);
                        }
                    }
                }
                $this->view->transcription = $colored_transcription;

                chdir($oldwd);
                $this->_helper->viewRenderer('connectid');
                $this->view->connection = $record;
            } else {
                //no such document
                echo 'no such document';
            }
        } else {
            //default view without id
            $records[] = $this->_helper->db->find(15);
            $records[] = $this->_helper->db->find(18);
            $records[] = $this->_helper->db->find(77);

            //check if there is really exacit one image file for each item
            if ($records != null) {
                $this->view->assign(array('Connections' => $records));
            } else {
                //no need to transcribe
            }
        }
    }

    public function discussAction() {
        //Possibly for in-document discussions
    }

    public function fooAction() {
        
    }
}
