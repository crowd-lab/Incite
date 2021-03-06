<?php

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
    //potential places to look for keywords: title, description, transcription, creator, publisher
    $element_id_for_title = 50;
    $element_id_for_description = 41;
    $element_id_for_creator = 39;
    $element_id_for_publisher = 45;
    $element_id_for_contributor = 37;
    $element_id_for_source = 48;
    $item_ids = array();
    $keyword_query = "%".$keyword."%";
    $stmt = $db->prepare('SELECT `record_id` FROM `omeka_element_texts` WHERE (`element_id` = ? OR `element_id` = ? OR `element_id` = ? OR `element_id` = ? OR `element_id` = ? OR `element_id` = ?) AND `text` LIKE ?');
    $stmt->bind_param("iiiiiis", $element_id_for_title, $element_id_for_description, $element_id_for_creator, $element_id_for_publisher, $element_id_for_source, $element_id_for_contributor, $keyword_query);
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


function getSearchResultsViaGetQuery()
{

  $item_ids = array();
  $further_search = true;
  //Process location search
  if ($further_search && isset($_GET['location']) && $_GET['location'] != "") {
    $item_ids = getAllDocumentsContainLocation($_GET['location']);
    if (count($item_ids) == 0)
    $further_search = false;
  }

  //Process time search
  if ($further_search && isset($_GET['time']) && $_GET['time'] != "") {
    //format of time: "yyyy-mm-dd - yyyy-mm-dd"
    $time_segs = explode(' - ', $_GET['time']);
    if (count($time_segs) != 2) {
      echo 'wrong time format';
      die();
    }
    $start_time = $time_segs[0];
    $end_time   = $time_segs[1];
    if (count($item_ids) != 0)
    $item_ids = array_intersect($item_ids, getAllDocumentsBetweenDates($start_time, $end_time));
    else
    $item_ids = getAllDocumentsBetweenDates($start_time, $end_time);

    if (count($item_ids) == 0)
    $further_search = false;
  }

  //Process keyword search
  if ($further_search && isset($_GET['keywords']) && $_GET['keywords'] != "") {
    $keywords = explode(' ', $_GET['keywords']);
    if (count($item_ids) != 0)
    $item_ids = array_intersect($item_ids, getAllDocumentsContainKeywords($keywords));
    else
    $item_ids = getAllDocumentsContainKeywords($keywords);

    if (count($item_ids) == 0)
    $further_search = false;
  }

  return $item_ids;
}

function isSearchQuerySpecifiedViaGet()
{
// $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
// debug_to_console($current_page);
// die();

  return (isset($_GET['location']) && $_GET['location'] != "") || (isset($_GET['time']) && $_GET['time'] != "") ||  (isset($_GET['keyword']) && $_GET['keywords'] != "");
}


function getCurrentPage(){

  // return $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  // return
}


function getSearchQuerySpecifiedViaGetAsString()
{

  $query = "";
  if (isset($_GET['location']) && $_GET['location'] != "")
  $query .= 'location='.$_GET['location'];

  if (isset($_GET['time']) && $_GET['time'] != "")
  if ($query == "")
  $query .= 'time='.$_GET['time'];
  else
  $query .= '&time='.$_GET['time'];

  if (isset($_GET['keywords']) && $_GET['keywords'] != "")
  if ($query == "")
  $query .= 'keywords='.$_GET['keywords'];
  else
  $query .= '&keywords='.$_GET['keywords'];

  return $query;
}


function getSearchQuerySpecifiedViaGetAsArray()
{
    $results = array();
    if (isset($_GET['location']) && $_GET['location'] != "")
        $results['location'] = $_GET['location'];

    if (isset($_GET['time']) && $_GET['time'] != "") {
        $times = explode(' - ', $_GET['time']);
        if (count($times) == 2 && strlen($times[0]) == 10 && strlen($times[1]) == 10) {
            $results['time_from'] = $times[0];
            $results['time_to']   = $times[1];
        }
        $results['time'] = $_GET['time'];
    }

    if (isset($_GET['keywords']) && $_GET['keywords'] != "")
        $results['keywords'] = $_GET['keywords'];

    return $results;
}

?>
