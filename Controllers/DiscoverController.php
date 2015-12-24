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
require_once("DB_Connect.php");

function getAllDocumentsBetweenDates($start, $end, $order='ASC')
{

    $db = DB_Connect::connectDB();
    $item_ids = array();  
    //40: Date
    $element_id_for_date = 40;
    $stmt = $db->prepare("SELECT record_id FROM `omeka_element_texts` WHERE `element_id` = ? AND `text` BETWEEN ? AND ? ORDER BY `text` ".$order);
    $stmt->bind_param("iss", $element_id_for_date, $start, $end);
    $stmt->bind_result($item_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $item_ids[] = $item_id;
    }
    $stmt->close();
    $db->close();
    
    return $item_ids;
}
function getAllDocumentsContainLocation($location)
{

    $db = DB_Connect::connectDB();
    $item_ids = array();  
    $element_id_for_location = 4;
    $location_query = "%".$location."%";
    $stmt = $db->prepare('SELECT `record_id` FROM `omeka_element_texts` WHERE `element_id` = ? AND `text` LIKE ?');
    $stmt->bind_param("is", $element_id_for_location, $location_query);
    $stmt->bind_result($item_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $item_ids[] = $item_id;
    }
    $stmt->close();
    $db->close();
    
    return $item_ids;
}
function getAllDocumentsContainKeyword($keyword)
{
    $db = DB_Connect::connectDB();
    //potential places to look for keywords: title, description, transcription?
    $element_id_for_title = 50;
    $element_id_for_description = 41;
    $item_ids = array();  
    $keyword_query = "%".$keyword."%";
    $stmt = $db->prepare('SELECT `record_id` FROM `omeka_element_texts` WHERE (`element_id` = ? OR `element_id` = ?) AND `text` LIKE ?');
    $stmt->bind_param("iis", $element_id_for_title, $element_id_for_description, $keyword_query);
    $stmt->bind_result($item_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $item_ids[] = $item_id;
    }
    $stmt->close();
    $db->close();
    
    return $item_ids;
}
function getAllDocumentsContainKeywords($keywords)
{
    $item_ids = array();
    foreach ((array)$keywords as $keyword) {
        if (count($item_ids) == 0) 
            $item_ids = getAllDocumentsContainKeyword($keyword);
        else
            $item_ids = array_intersect($item_ids, getAllDocumentsContainKeyword($keyword));

        if (count($item_ids) == 0)
            break;
    }
    return $item_ids;
}

class Incite_DiscoverController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
    }

    public function indexAction()
    {
		if ($this->getRequest()->isGet()) {
            //Assume there is session already because query is stored in session
            $_SESSION['Incite']['search'] = array();
            $item_ids = array();
            $is_search_used = false;
            //Process location search
            if (isset($_GET['location']) && $_GET['location'] != "") {
                $is_search_used = true;
                $item_ids = getAllDocumentsContainLocation($_GET['location']);
                $_SESSION['Incite']['search']['location'] = array('query' => $_GET['location'], 'item_ids' => $item_ids);
            }

            //Process time search
            if (isset($_GET['time']) && $_GET['time'] != "") {
                $is_search_used = true;
                $time_segs = explode(' - ', $_GET['time']);
                if (count($time_segs) != 2) {
                    echo 'wrong time format';
                    die();
                }
                $start_time = $time_segs[0];
                $end_time   = $time_segs[1];
                if (count($item_ids) == 0)
                    $item_ids = getAllDocumentsBetweenDates($start_time, $end_time);
                else
                    $item_ids = array_intersect($item_ids, getAllDocumentsBetweenDates($start_time, $end_time));

                $_SESSION['Incite']['search']['time'] = array('query' => $_GET['time'], 'item_ids' => $item_ids);
            }

            //Process keyword search
            if (isset($_GET['keywords']) && $_GET['keywords'] != "") {
                $is_search_used = true;
                $keywords = explode(' ', $_GET['keywords']);
                if (count($item_ids) == 0)
                    $item_ids = getAllDocumentsContainKeywords($keywords);
                else
                    $item_ids = array_intersect($item_ids, getAllDocumentsContainKeywords($keywords));

                $_SESSION['Incite']['search']['keywords'] = array('query' => $_GET['keywords'], 'item_ids' => $item_ids);

            }

            if ($is_search_used)
                $_SESSION['Incite']['search']['final_items'] = $item_ids;



            //Go to the desired task based on the above result
			if (isset($_GET['task'])) {
				if ($_GET['task'] == "transcribe") {
					$this->_redirect('/incite/documents/transcribe');
				} else if ($_GET['task'] == "tag") {
					$this->_redirect('/incite/documents/tag');
				} else if ($_GET['task'] == "connect") {
					$this->_redirect('/incite/documents/connect');
				} else if ($_GET['task'] == "discuss") {
					$this->_redirect('/incite/discussions');
				} else { //then...random! but currently need more transcriptions
					$this->_redirect('/incite/documents/transcribe');
				}
			}
		}	
    }

}
