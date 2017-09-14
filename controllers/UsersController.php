<?php





class Incite_UsersController extends Omeka_Controller_AbstractActionController {

    public function init() {
        require_once('Incite_Helpers.php');
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

        $this->_helper->viewRenderer->setNoRender(TRUE);
        echo getDiscussionCountByUserId(3);
    }

    public function viewAction() {
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('viewid');
            $user_id = $this->_getParam('id');
            $this->view->transcribed_docs = getTranscribedDocumentsByUserId($user_id);
            $this->view->tagged_docs = getTaggedDocumentsByUserId($user_id);
            $this->view->connected_docs = getConnectedDocumentsByUserId($user_id);
            $this->view->discussions = getDiscussionsByUserId($user_id);
            $this->view->groups = getGroupsByUserId($user_id);
            $this->view->user = getUserDataByUserId($user_id);

            //Get all activities together, add activity_type and sort them based on time
            $tran = getTranscribedDocumentsByUserId($user_id);
            addKeyValueToArray($tran, 'activity_type', 'Transcribe');
            $tag = getTaggedDocumentsByUserId($user_id);
            addKeyValueToArray($tag, 'activity_type', 'Tag');
            $con = getConnectedDocumentsByUserId($user_id);
            addKeyValueToArray($con, 'activity_type', 'Connect');
            $dis = getDiscussionsByUserId($user_id);
            addKeyValueToArray($dis, 'activity_type', 'Discuss');
            $activities = array_merge($tran, $tag, $con, $dis);
            usort($activities, "customizedTimeCmpFuncDESC");
            $this->view->activities = $activities;
        } else {
            $this->view->users = "";
        }
    }

    public function profileAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('profile');
            $user_id = $this->_getParam('id');
            $this->view->user = getUserDataByUserId($user_id);
        } else {
            $this->view->users = "";
        }
    }
    public function activityAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('activity');
            $user_id = $this->_getParam('id');
            $this->view->transcribed_docs = getTranscribedDocumentsByUserId($user_id);
            $this->view->tagged_docs = getTaggedDocumentsByUserId($user_id);
            $this->view->connected_docs = getConnectedDocumentsByUserId($user_id);
            $this->view->discussions = getDiscussionsByUserId($user_id);
            $this->view->groups = getGroupsByUserId($user_id);
            $this->view->user = getUserDataByUserId($user_id);

            //Get all activities together, add activity_type and sort them based on time
            $tran = getTranscribedDocumentsByUserId($user_id);
            addKeyValueToArray($tran, 'activity_type', 'Transcribe');
            $tag = getTaggedDocumentsByUserId($user_id);
            addKeyValueToArray($tag, 'activity_type', 'Tag');
            $con = getConnectedDocumentsByUserId($user_id);
            addKeyValueToArray($con, 'activity_type', 'Connect');
            $dis = getDiscussionsByUserId($user_id);
            addKeyValueToArray($dis, 'activity_type', 'Discuss');
            $activities = array_merge($tran, $tag, $con, $dis);
            usort($activities, "customizedTimeCmpFuncDESC");
            $this->view->activities = $activities;
        } else {
            $this->view->users = "";
        }
    }
    public function groupAction(){
        if ($this->_hasParam('id')) {
            $this->_helper->viewRenderer('group');
            $user_id = $this->_getParam('id');
            $this->view->groups = getGroupsByUserId($user_id);
            $this->view->user = getUserDataByUserId($user_id);

        } else {
            $this->view->users = "";
        }
    }

    public function dumpstudydataAction() {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT technique, attempts, pretest_response, TIME_TO_SEC(TIMEDIFF(pretest_end, pretest_start)), TIME_TO_SEC(TIMEDIFF(summarytone_end, summarytone_start)), TIME_TO_SEC(TIMEDIFF(tag_end, tag_start)), TIME_TO_SEC(TIMEDIFF(connect_end, connect_start)), TIME_TO_SEC(TIMEDIFF(posttest_end, posttest_start)), TIME_TO_SEC(TIMEDIFF(posttest_end, pretest_start)), posttest_response, postsurvey_response FROM `study22` WHERE is_completed = 2 AND id > 5");
        $stmt->bind_result($technique, $attempts, $pre_response, $pretest_time, $summarytone_time, $tag_time, $connect_time, $posttest_time, $total_task_time, $post_response, $postsurvey_response);
        $stmt->execute();
        $colums = array('technique', 'attempts', 'pretest_response', 'pretest_time', 'summarytone_time', 'tag_time', 'connect_time', 'posttest_time', 'total_task_time', 'total_time', 'posttest_response', 'postsurvey_response');

        $header = "";
        foreach ($columns as $column) {
            $header .= $column.$separator;
        }
        $header = rtrim($header, $separator);
        echo $header."\n";
        while ($stmt->fetch()) {
            echo $technique.",".$attempts.",\"".$pre_response."\",".$pretest_time.",".$summarytone_time.",".$tag_time.",".$connect_time.",".$posttest_time.",".$total_task_time.",\"".$post_response."\",\"".addslashes($postsurvey_response)."\"\n";
        }
        $stmt->close();
        $db->close();
        die();

    }
    public function dumpstudydata2Action() {
        $separator = "^";
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, technique, pretest_response, summarytone_baseline, summarytone_condition, summarytone_revised, tag_baseline, tag_condition, tag_revised, connect_baseline, connect_condition, connect_revised, posttest_response, postsurvey_response FROM `study22` WHERE is_completed = 2 AND id > 5");
        $stmt->bind_result($id, $technique, $pre_response, $summarytone_baseline, $summarytone_condition, $summarytone_revised, $tag_baseline, $tag_condition, $tag_revised, $connect_baseline, $connect_condition, $connect_revised, $posttest_response, $postsurvey_response);
        $stmt->execute();
        echo 'id'.$separator.'technique'.$separator.'pretest_response'.$separator.'summarytone_baseline'.$separator.'summarytone_condition'.$separator.'summarytone_revised'.$separator.'tag_baseline'.$separator.'tag_condition'.$separator.'tag_revised'.$separator.'connect_baseline'.$separator.'connect_condition'.$separator.'connect_revised'.$separator.'posttest_response'.$separator.'postsurvey_response'."\n";
        while ($stmt->fetch()) {
            unset($baseline);
            if ($technique == "scim") {
                $baseline = json_decode($summarytone_baseline, true);
                $revised = json_decode($summarytone_revised, true);
            }
            if (isset($baseline) && $baseline["response"]['summary'] != $revised['response']['summary']) {
                echo "\n\nDiff:".(strlen($revised['response']['summary'])-strlen($baseline['response']['summary']))."\n\n";
            }
            echo $id.$separator.$technique.$separator.'"'.$pre_response.'"'.$separator.'"'.$summarytone_baseline.'"'.$separator.'"'.$summarytone_condition.'"'.$separator.'"'.$summarytone_revised.'"'.$separator.'"'.$tag_baseline.'"'.$separator.'"'.$tag_condition.'"'.$separator.'"'.$tag_revised.'"'.$separator.'"'.$connect_baseline.'"'.$separator.'"'.$connect_condition.'"'.$separator.'"'.$connect_revised.'"'.$separator.'"'.$posttest_response.'"'.$separator.'"'.$postsurvey_response."'\n";
            //echo $technique.$separator.$pre_response.$separator."'".$summarytone_baseline."'".$separator.$summarytone_condition.$separator.$summarytone_revised.$separator.$tag_baseline.$separator.$tag_condition.$separator.$tag_revised.$separator.$connect_baseline.$separator.$connect_condition.$separator.$connect_revised.$separator.$posttest_response.$separator.$postsurvey_response."\n";
        }
        $stmt->close();
        $db->close();
        die();

    }
    public function dumpstudydata3Action() { //grading
        $separator = "^";
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT id, technique, pretest_response, summarytone_baseline, summarytone_condition, summarytone_revised, tag_baseline, tag_condition, tag_revised, connect_baseline, connect_condition, connect_revised, posttest_response, postsurvey_response FROM `study22` WHERE is_completed = 2 AND id > 5");
        $stmt->bind_result($id, $technique, $pre_response, $summarytone_baseline, $summarytone_condition, $summarytone_revised, $tag_baseline, $tag_condition, $tag_revised, $connect_baseline, $connect_condition, $connect_revised, $posttest_response, $postsurvey_response);
        $stmt->execute();
        echo 'id'.$separator.'technique'.$separator.'pretest_response'.$separator.'summarytone_baseline'.$separator.'summarytone_condition'.$separator.'summarytone_revised'.$separator.'tag_baseline'.$separator.'tag_condition'.$separator.'tag_revised'.$separator.'connect_baseline'.$separator.'connect_condition'.$separator.'connect_revised'.$separator.'posttest_response'.$separator.'postsurvey_response'."\n";
        while ($stmt->fetch()) {
            unset($baseline);
            if ($technique == "scim") {
                $baseline = json_decode($summarytone_baseline, true);
                $revised = json_decode($summarytone_revised, true);
            }
            if (isset($baseline) && $baseline["response"]['summary'] != $revised['response']['summary']) {
                echo "\n\nDiff:".(strlen($revised['response']['summary'])-strlen($baseline['response']['summary']))."\n\n";
            }
            echo $id.$separator.$technique.$separator.'"'.$pre_response.'"'.$separator.'"'.$summarytone_baseline.'"'.$separator.'"'.$summarytone_condition.'"'.$separator.'"'.$summarytone_revised.'"'.$separator.'"'.$tag_baseline.'"'.$separator.'"'.$tag_condition.'"'.$separator.'"'.$tag_revised.'"'.$separator.'"'.$connect_baseline.'"'.$separator.'"'.$connect_condition.'"'.$separator.'"'.$connect_revised.'"'.$separator.'"'.$posttest_response.'"'.$separator.'"'.$postsurvey_response."'\n";
            //echo $technique.$separator.$pre_response.$separator."'".$summarytone_baseline."'".$separator.$summarytone_condition.$separator.$summarytone_revised.$separator.$tag_baseline.$separator.$tag_condition.$separator.$tag_revised.$separator.$connect_baseline.$separator.$connect_condition.$separator.$connect_revised.$separator.$posttest_response.$separator.$postsurvey_response."\n";
        }
        $stmt->close();
        $db->close();
        die();

    }

    function check_tone_rating($user, $gold = null) {
        if ($gold == null) {
            $gold = array("tone1" => 0, "tone2" => 0, "tone3" => 0, "tone4" => 0, "tone5" => 0, "tone6" => 0);
        }
        $user = json_decode($user, true);
        $total_abs_diff = 0;
        $total_diff = 0;
        for ($i = 1; $i <= 6; $i++) {
            $diff = $user['tone'.$i] - $gold["tone".$i];
            $total_diff += $diff;
            $total_abs_diff += abs($diff);
        }
        return array('abs_diff' => $total_abs_diff, 'diff' => $total_diff);
    }
    function check_tag($user, $gold = null) { // currently only tag names and their catorigies
        if ($gold == null) {
            $gold = array("Bedford" => 1, "Bedford2" => 1, "Maylans Regiment" => 4, "Washington" => 3, "New Windsor" => 1, "Gen. Washington" => 3); //added Gen. Washington as an alias for Washington. This doesn't affect $total since it's an alias.
        }
        $user = json_decode($user, true);
        $seen_bedford = false;
        $total = 5; // total # of tags in gold so $misses = $total - $(xxx)_hits
        $tag_hits = 0;
        $cat_hits = 0;
        $extras = 0;
        foreach ($user as $tag) {
            //ignore non-Location(1), non-Person(3), non-Organization(4) for now
            if ($tag['category'] != 1 && $tag['category'] != 3 && $tag['category'] != 4) {
                continue;
            }
            //two if's for dealing with multiple Bedford tags
            if ($seen_bedford && $tag['entity'] == 'Bedford') {
                $tag['entity'] = 'Bedford2';
            }
            if (!$seen_bedford && $tag['entity'] == 'Bedford') {
                $seen_bedford = true;
            }
            //actual grading
            if (array_key_exists($tag['entity'], $gold)) { //tag matches a tag in gold
                $tag_hits++;
                if ($tag['category'] == $gold[$tag['entity']]) { //cat matches as well
                    $cat_hits++;
                }
            } else { //tag doesn't match any tag in gold
                $extras++;
            }
        }
        $tag_misses = $total - $tag_hits;
        $cat_misses = $total - $cat_misses;
        $precision_tag = (float)$tag_hits/($tag_hits+$extras);
        $precision_cat = (float)$tag_cat/($tag_hits+$extras);
        $recall_tag = (float)$tag_hits/$total;
        $recall_cat = (float)$cat_hits/$total;

        return array('tag' => array('precision' => $precision_tag, 'recall' => $recall_tag, 'hit' => $tag_hits, 'miss' => $tag_misses, 'total' => $total),
                     'cat' => array('precision' => $precision_cat, 'recall' => $recall_cat, 'hit' => $cat_hits, 'miss' => $cat_misses, 'total' => $total));
    }
    function check_theme_rating($user, $gold = null) {
        if ($gold == null) {
            $gold = array("subject1" => 0, "subject2" => 0, "subject3" => 0, "subject4" => 0, "subject5" => 0, "subject6" => 0, "subject7" => 0, "subject8" => 0, "subject9" => 0, "subject10" => 0, "subject11" => 0, "subject12" => 0, "subject13" => 0, "subject14" => 0);
        }
        $user = json_decode($user, true);
        $total_abs_diff = 0;
        $total_diff = 0;
        for ($i = 1; $i <= 14; $i++) {
            $diff = $user['subject'.$i] - $gold["subject".$i];
            $total_diff += $diff;
            $total_abs_diff += abs($diff);
        }
        return array('abs_diff' => $total_abs_diff, 'diff' => $total_diff);
    }



/**
* Direct to Forgot Password Page.
*/
public function forgotAction(){

    $this->_helper->viewRenderer('forgotpw');
}

}

?>
