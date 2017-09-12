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
    $stmt = $db->prepare("SELECT `id` FROM `study22` WHERE `is_completed` = 0");
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
    $stmt = $db->prepare("SELECT COUNT(*) FROM `study22` WHERE `worker_id` = ?");
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
    $stmt = $db->prepare("SELECT `id`, `technique` FROM `study22` WHERE `is_completed` = 0 LIMIT 1");
    $stmt->bind_result($trial_id, $technique);
    $stmt->execute();
    $result = $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($result != null) {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("UPDATE `study22` SET is_completed = ?, assignment_id = ?, worker_id = ?, attempts = attempts + 1 WHERE `id` = ?");
        $is_completed = 1; //job taken=>working
        $stmt->bind_param("issi", $is_completed, $assignment_id, $worker_id, $trial_id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        return array('trial_id' => $trial_id, 'technique' => $technique);
    } else {
        return null;
    }
}

function completeTasks($id, $task_type, $content) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE study22 SET ".$task_type."_start = ?, ".$task_type."_baseline = ?, ".$task_type."_condition = ?, ".$task_type."_revised = ?, ".$task_type."_end = ?  WHERE id = ?");

    $stmt->bind_param("sssssi", $content['start'], $content['baseline'], $content['condition'], $content['revised'], $content['end'], $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function completeTests($id, $test_type, $content) {
/*
    echo '<pre>';
    echo $id."\n";
    echo $test_type."\n";
    print_r($content);
    echo '</pre>';
    die();
*/
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE study22 SET ".$test_type."_start = ?, ".$test_type."_response = ?, ".$test_type."_end = ?  WHERE id = ?");

    $stmt->bind_param("sssi", $content['start'], $content['response'], $content['end'], $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function completeStudy($id) {
    $is_completed = 2; //done
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE study22 SET is_completed = ?  WHERE id = ?");

    $stmt->bind_param("ii", $is_completed, $id);

    $stmt->execute();
    $stmt->close();
    $db->close();
}

function urlGenerator($task_type)
{
    $action = '';
    switch($task_type) {
        case 0:
            $action = 'presurvey'; break;
        case 1:
            $action = 'pretest'; break;
        case 2:
            $action = 'summarytone'; break;
        case 3:
            $action = 'tag'; break;
        case 4:
            $action = 'connect'; break;
        case 5:
            $action = 'posttest'; break;
        case 6:
            $action = 'postsurvey'; break;
        case 7:
            $action = 'complete'; break;
        default:
            $action = 'error'; break;
    }
    return getFullInciteUrl().'/documents/'.$action.'/';
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
        if (!isset($_SESSION)) {
            $is_session_on = session_start();
        } else {
            $is_session_on = TRUE;
        }

        if ($is_session_on === FALSE) {
            echo 'Something went wrong. Our system does not seem to work correctly on your device. Please return the HIT. Sorry for any inconvenience!';
            die();
        }

        //Check if there is task available. If not, redirect to a page to notify the user.
        $isAnyTrialAvailable = isAnyTrialAvailable();
        //testing so we assuming there is trial available
        //$isAnyTrialAvailable = true;
        if (!$isAnyTrialAvailable)  {
            $this->_helper->viewRenderer('taskless');
            return;
        }

        //Get an available task. Default: AMT
        $is_hit_accepted = !(!isset($_GET['assignmentId']) || (isset($_GET['assignmentId']) && $_GET['assignmentId'] == "ASSIGNMENT_ID_NOT_AVAILABLE"));

        //testing so we can assume hit is accepted
        //$is_hit_accepted = true;
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
            //testing with a particular technique: baseline, scim, shepherd, rvd (review vs. doing)
            //$trial = array('trial_id' => 0, 'technique' => 'rvd');
            //if (isset($_GET['condition'])) {
                //$trial['technique'] = $_GET['condition'];
            //}
            if ($trial != null) {
                //Initialization
                //Docs
                $_SESSION['study2']['pretest_doc'] = 1125;
                $_SESSION['study2']['work_doc'] = 1126;
                $_SESSION['study2']['posttest_doc'] = 1127;

                //AMT stuff
                $_SESSION['study2']['assignment_id'] = $assignment_id;
                $_SESSION['study2']['worker_id'] = $worker_id;
                $_SESSION['study2']['id'] = $trial['trial_id'];
                $_SESSION['study2']['technique'] = $trial['technique'];
                $_SESSION['study2']['task_seq'] = 0;
                //0: presurvey, 1: pretest, 5: posttest, 6: postsurvey, 7: complete 
                $_SESSION['study2']['urls'] = array(urlGenerator(1), urlGenerator(2), urlGenerator(3), urlGenerator(4), urlGenerator(5), urlGenerator(6), urlGenerator(7));
                $_SESSION['study2']['num_tasks'] = count($_SESSION['study2']['urls'])-1;

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
            //$this->_helper->viewRenderer('example2');
        }
        }

        public function summarytoneAction() {
            $this->_helper->db->setDefaultModelName('Item');
            $work_doc = $_SESSION['study2']['work_doc'];
            $this->view->document_metadata = $this->_helper->db->find($work_doc);
            $newestTranscription = getFirstTranscription($work_doc);
            $this->view->transcription_id = $newestTranscription['id'];
            $this->view->transcription = $newestTranscription['transcription'];

            //Error handling
            if (!isset($_SESSION['study2']['work_doc'])) {
                $this->view->error_reason = 'no working doc';
                $this->_helper->viewRenderer('error');
                return;
            }
            if (!isset($_SESSION['study2']['technique'])) {
                $this->view->error_reason = 'no condition specified';
                $this->_helper->viewRenderer('error');
                return;
            }

            //Task handling
            if ($this->getRequest()->isPost()) {
                $this->saveSummaryTone();
            } else {
                $this->showSummaryToneTask();
            }

        }

        public function saveSummaryTone() {
            $workingGroupId = $this->getWorkingGroupID();

            //Save results of current task
            $content = array();
            $content['start'] = $_POST['start'];
            $content['baseline'] = $_POST['baseline'];
            $content['condition'] = $_POST['condition'];
            $content['revised'] = $_POST['revised'];
            $content['end'] = $_POST['end'];
            completeTasks($_SESSION['study2']['id'], "summarytone", $content);

            //All set. Move to next task!
            $_SESSION['study2']['task_seq']++;
            $task_seq = $_SESSION['study2']['task_seq'];
            $urls = $_SESSION['study2']['urls'];
            $this->redirect($urls[$task_seq]);
        }

        public function showSummaryToneTask() {
            $this->view->document_metadata = $this->_helper->db->find($_SESSION['study2']['work_doc']);

            if ($this->view->document_metadata != null) {
                if ($this->view->document_metadata->getFile() == null) {
                    $this->view->error_reason = 'working doc file not found';
                    $this->_helper->viewRenderer('error');
                    return;
                }
                $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
            } else {
                $this->view->error_reason = 'working doc not found';
                $this->_helper->viewRenderer('error');
            }
            $this->_helper->viewRenderer('summarytone'.$_SESSION['study2']['technique']);
        }

        public function tagAction() {
            $this->_helper->db->setDefaultModelName('Item');

            //Error handling
            if (!isset($_SESSION['study2']['work_doc'])) {
                $this->view->error_reason = 'no working doc';
                $this->_helper->viewRenderer('error');
                return;
            }
            if (!isset($_SESSION['study2']['technique'])) {
                $this->view->error_reason = 'no condition specified';
                $this->_helper->viewRenderer('error');
                return;
            }

            //Task handling
            if ($this->getRequest()->isPost()) {
                $this->saveTags();
            } else {
                $this->showTagTask();
            }
        }

        public function saveTags() {
            $content = array();
            $content['start'] = $_POST['start'];
            $content['baseline'] = $_POST['baseline'];
            $content['condition'] = $_POST['condition'];
            $content['revised'] = $_POST['revised'];
            $content['end'] = $_POST['end'];
            completeTasks($_SESSION['study2']['id'], "tag", $content);

            //All set. Move to next task!
            $_SESSION['study2']['task_seq']++;
            $task_seq = $_SESSION['study2']['task_seq'];
            $urls = $_SESSION['study2']['urls'];
            $this->redirect($urls[$task_seq]);
        }

        public function showTagTask() {
            $tag_id_counter = 0;
            $this->view->document_metadata = $this->_helper->db->find($_SESSION['study2']['work_doc']);

            //error handling
            if ($this->view->document_metadata != null) {
                if ($this->view->document_metadata->getFile() == null) {
                    $this->view->error_reason = 'working doc file not found';
                    $this->_helper->viewRenderer('error');
                    return;
                }
                $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
            } else {
                $this->view->error_reason = 'working doc not found';
                $this->_helper->viewRenderer('error');
                return;
            }
            //task handling
            $work_doc = $_SESSION['study2']['work_doc'];
            $this->view->document_metadata = $this->_helper->db->find($work_doc);
            $newestTranscription = getFirstTranscription($work_doc);
            $this->view->transcription_id = $newestTranscription['id'];
            $this->view->transcription = $newestTranscription['transcription'];
            $this->_helper->viewRenderer('tag'.$_SESSION['study2']['technique']);
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
            //Start NER
                $this->view->is_being_edited = false;
                $ner_entity_table = array();
                $oldwd = getcwd();
                chdir('./plugins/Incite/stanford-ner-2015-04-20/');

                $this->view->file = 'not exist';
                $ner_input = fopen('../tmp/ner/' . $work_doc, "w") or die("unable to open transcription");
                fwrite($ner_input, $this->view->transcription);
                fclose($ner_input);
                system("java -mx600m -cp stanford-ner.jar edu.stanford.nlp.ie.crf.CRFClassifier -loadClassifier classifiers/english.muc.7class.distsim.crf.ser.gz -outputFormat inlineXML -textFile " . '../tmp/ner/' . $work_doc . ' > ' . '../tmp/ner/' . $work_doc . '.ner');

                $nered_file = fopen('../tmp/ner/' . $work_doc . '.ner', "r");
                $nered_file_size = filesize('../tmp/ner/' . $work_doc . '.ner');
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
        }

        public function connectAction() {
            $this->_helper->db->setDefaultModelName('Item');
            $this->view->category_colors = array('ORGANIZATION' => 'blue', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'green', 'UNKNOWN' => 'red');

            //Error handling
            if (!isset($_SESSION['study2']['work_doc'])) {
                $this->view->error_reason = 'no working doc';
                $this->_helper->viewRenderer('error');
                return;
            }
            if (!isset($_SESSION['study2']['technique'])) {
                $this->view->error_reason = 'no condition specified';
                $this->_helper->viewRenderer('error');
                return;
            }

            //Task handling
            if ($this->getRequest()->isPost()) {
                $this->saveConnections();
            } else {
                $this->showConnectTask();
            }
        }

        public function saveConnections() {
            $all_subject_ids = getAllSubjectConceptIds();
            $content = array();
            $content['start'] = $_POST['start'];
            $content['baseline'] = $_POST['baseline'];
            $content['condition'] = $_POST['condition'];
            $content['revised'] = $_POST['revised'];
            $content['end'] = $_POST['end'];
            completeTasks($_SESSION['study2']['id'], "connect", $content);


                //All set. Move to next task!
                $_SESSION['study2']['task_seq']++;
                $task_seq = $_SESSION['study2']['task_seq'];
                $urls = $_SESSION['study2']['urls'];
                $this->redirect($urls[$task_seq]);

            }

            public function showConnectTask() {
                $this->view->document_metadata = $this->_helper->db->find($_SESSION['study2']['work_doc']);

                //error handling
                if ($this->view->document_metadata != null) {
                    if ($this->view->document_metadata->getFile() == null) {
                        $this->view->error_reason = 'working doc file not found';
                        $this->_helper->viewRenderer('error');
                        return;
                    }
                    $this->view->image_url = get_image_url_for_item($this->view->document_metadata);
                } else {
                    $this->view->error_reason = 'working doc not found';
                    $this->_helper->viewRenderer('error');
                    return;
                }
                //task handling
                $work_doc = $_SESSION['study2']['work_doc'];
                $this->view->document_metadata = $this->_helper->db->find($work_doc);
                $newestTranscription = getFirstTranscription($work_doc);
                $this->view->transcription_id = $newestTranscription['id'];
                $this->view->transcription = $newestTranscription['transcription'];
                $this->view->subjects = getAllSubjectConcepts();

                $taggedTranscription = getNewestTaggedTranscriptionFromUserId($work_doc, $_SESSION['Incite']['USER_DATA']['id']);
                if (!empty($taggedTranscription)) {
                    $this->view->error_reason = 'no tagged transcription';
                    $this->_helper->viewRenderer('error');
                    return;
                }
                $this->_helper->viewRenderer('connect'.$_SESSION['study2']['technique']);
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

            public function presurveyAction() {
                if ($this->getRequest()->isPost()) {

                    //All set. Move to next task!
                    $_SESSION['study2']['task_seq']++;
                    $task_seq = $_SESSION['study2']['task_seq'];
                    $urls = $_SESSION['study2']['urls'];
                    $this->redirect($urls[$task_seq]);
                }
            }

            public function pretestAction() {
                $this->_helper->db->setDefaultModelName('Item');
                $pretest_doc = $_SESSION['study2']['pretest_doc'];
                $this->view->document_metadata = $this->_helper->db->find($pretest_doc);
                $newestTranscription = getFirstTranscription($pretest_doc);
                $this->view->transcription_id = $newestTranscription['id'];
                $this->view->transcription = $newestTranscription['transcription'];
                if ($this->getRequest()->isPost()) {
                    /*
                       $name   = $_POST['name'];
                       $class   = $_POST['class'];
                       $age    = $_POST['age'];
                       $gender = $_POST['gender'];
                       $majors = $_POST['majors'];

                    //Save demographics
                    $db = DB_Connect::connectDB();
                    $stmt = $db->prepare("UPDATE study2 SET name = ?, class= ?, age = ?, gender = ?, majors = ?, time1_start = NOW() WHERE id = ?");
                    $stmt->bind_param("ssissi", $name, $class, $age, $gender, $majors, $_SESSION['study2']['id']);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                     */
                    $content = array();
                    $content['start'] = $_POST['start'];
                    $content['response'] = $_POST['response'];
                    $content['end'] = $_POST['end'];
                    completeTests($_SESSION['study2']['id'], "pretest", $content);

                    //All set. Move to next task!
                    $_SESSION['study2']['task_seq']++;
                    $task_seq = $_SESSION['study2']['task_seq'];
                    $urls = $_SESSION['study2']['urls'];
                    $this->redirect($urls[$task_seq]);
                }
            }

            public function posttestAction() {
                $this->_helper->db->setDefaultModelName('Item');
                $pretest_doc = $_SESSION['study2']['posttest_doc'];
                $this->view->document_metadata = $this->_helper->db->find($pretest_doc);
                $newestTranscription = getFirstTranscription($pretest_doc);
                $this->view->transcription_id = $newestTranscription['id'];
                $this->view->transcription = $newestTranscription['transcription'];
                if ($this->getRequest()->isPost()) {
                    /*
                       $name   = $_POST['name'];
                       $class   = $_POST['class'];
                       $age    = $_POST['age'];
                       $gender = $_POST['gender'];
                       $majors = $_POST['majors'];

                    //Save demographics
                    $db = DB_Connect::connectDB();
                    $stmt = $db->prepare("UPDATE study2 SET name = ?, class= ?, age = ?, gender = ?, majors = ?, time1_start = NOW() WHERE id = ?");
                    $stmt->bind_param("ssissi", $name, $class, $age, $gender, $majors, $_SESSION['study2']['id']);
                    $stmt->execute();
                    $stmt->close();
                    $db->close();
                     */
                    $content = array();
                    $content['start'] = $_POST['start'];
                    $content['response'] = $_POST['response'];
                    $content['end'] = $_POST['end'];
                    completeTests($_SESSION['study2']['id'], "posttest", $content);

                    //All set. Move to next task!
                    $_SESSION['study2']['task_seq']++;
                    $task_seq = $_SESSION['study2']['task_seq'];
                    $urls = $_SESSION['study2']['urls'];
                    $this->redirect($urls[$task_seq]);
                }
            }

            public function postsurveyAction() {
                if ($this->getRequest()->isPost()) {

                    $content = array();
                    $content['start'] = $_POST['start'];
                    $content['response'] = $_POST['response'];
                    $content['end'] = $_POST['end'];
                    completeTests($_SESSION['study2']['id'], "postsurvey", $content);
                    completeStudy($_SESSION['study2']['id']);

                    //All set. Move to next task!
                    $_SESSION['study2']['task_seq']++;
                    $task_seq = $_SESSION['study2']['task_seq'];
                    $urls = $_SESSION['study2']['urls'];
                    $this->redirect($urls[$task_seq]);
                }
            }

            public function completeAction() {
            }
            public function complete2Action() {
            }
            public function errorAction() {
                echo 'Something went wrong!';
                die();
            }
            public function wfdatadumpingtothisAction() {
                /*
                   echo '<pre>';
                //1125 -> 4; 1126 -> 5; 1127 -> 15 (item_id -> document_id)
                print_r(check_task_performance(1, 1125, 54));
                print_r(check_task_performance(2, 1125, 54));
                //print_r(check_task_performance(3, 1125, 54));
                echo '</pre>';
                die();
                 */
                $db = DB_Connect::connectDB();
                //Original
                //$stmt = $db->prepare("SELECT `id`, `workflow`, doc1, doc2, doc3, task1, task2, task3, TIME_TO_SEC(TIMEDIFF(time1_end, time1_start)), TIME_TO_SEC(TIMEDIFF(time2_end, time2_start)), TIME_TO_SEC(TIMEDIFF(time3_end, time3_start)), attempts, age, q1, q2, q3, q4, q5, q6, q71, q72, q73, q74, q81, q82, q83, q84, tlx_men, tlx_phy, tlx_tem, tlx_per, tlx_eff, tlx_fru, tlx_int, user_feedback FROM `study2` WHERE id > 1 AND id <= 16");
                //Regan's class
                $stmt = $db->prepare("SELECT `id`, `workflow`, name, doc1, doc2, doc3, task1, task2, task3, TIME_TO_SEC(TIMEDIFF(time1_end, time1_start)), TIME_TO_SEC(TIMEDIFF(time2_end, time2_start)), TIME_TO_SEC(TIMEDIFF(time3_end, time3_start)), attempts, age, q1, q2, q3, q4, q5, q6, q71, q72, q73, q74, q81, q82, q83, q84, tlx_men, tlx_phy, tlx_tem, tlx_per, tlx_eff, tlx_fru, tlx_int, user_feedback FROM `study2` WHERE id >= 24 AND id <= 42");
                $stmt->bind_result($trial_id, $workflow, $name, $doc1, $doc2, $doc3, $task1, $task2, $task3, $timediff1, $timediff2, $timediff3, $ttempts, $age, $q1, $q2, $q3, $q4, $q5, $q6, $q71, $q72, $q73, $q74, $q81, $q82, $q83, $q84, $tlx_men, $tlx_phy, $tlx_tem, $tlx_per, $tlx_eff, $tlx_fru, $tlx_int, $user_feedback);

                $stmt->execute();
                echo 'trial_id,workflow,name,doc1,doc2,doc3,task1,task2,task3,timediff1,timediff2,timediff3,atempts,age,q1,q2,q3,q4,q5,q6,q71,q72,q73,q74,q81,q82,q83,q84,tlx_men,tlx_phy,tlx_tem,tlx_per,tlx_eff,tlx_fru,tlx_int,user_feedback'."\n";
                while (($result = $stmt->fetch()) != null) {
                    echo $trial_id.",".$workflow.",".$name.",".$doc1.",".$doc2.",".$doc3.",".$task1.",".$task2.",".$task3.",".$timediff1.",".$timediff2.",".$timediff3.",".$ttempts.",".$age.",".$q1.",".$q2.",".$q3.",".$q4.",".$q5.",".$q6.",".$q71.",".$q72.",".$q73.",".$q74.",".$q81.",".$q82.",".$q83.",".$q84.",".$tlx_men.",".$tlx_phy.",".$tlx_tem.",".$tlx_per.",".$tlx_eff.",".$tlx_fru.",".$tlx_int.",\"".str_replace('"', '""', $user_feedback)."\"\n";
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
