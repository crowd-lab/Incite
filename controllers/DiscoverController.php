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


class Incite_DiscoverController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        require_once("Incite_Search.php");
    }

    public function indexAction()
    {
		if ($this->getRequest()->isGet()) {
            //Assume there is session already because query is stored in session
            $_SESSION['Incite']['search'] = array();
            $item_ids = array();
            $is_search_used = false;
            $query = "?";
            //Process location search
            if (isset($_GET['location']) && $_GET['location'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'location='.$_GET['location'];
                $is_search_used = true;
                $item_ids = getAllDocumentsContainLocation($_GET['location']);
                $_SESSION['Incite']['search']['location'] = array('query' => $_GET['location'], 'item_ids' => $item_ids);
            }

            //Process time search
            if (isset($_GET['time']) && $_GET['time'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'time='.$_GET['time'];
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
                $query .= (strlen($query) > 1 ? '&' : '').'keywords='.$_GET['keywords'];
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



            $redirect_action = '';
            //Go to the desired task based on the above result
			if (isset($_GET['task'])) {
				if ($_GET['task'] == "transcribe") {
                    $redirect_action = 'transcribe';
				} else if ($_GET['task'] == "tag") {
                    $redirect_action = 'tag';
				} else if ($_GET['task'] == "connect") {
                    $redirect_action = 'connect';
				} else if ($_GET['task'] == "discuss") {
                    $redirect_action = 'discussions';
				} else { //then...random! but currently need more transcriptions
                    $redirect_action = 'transcribe';
				}
			}
            if (strlen($query) > 1)  //more than "?"
                $this->_redirect('/incite/documents/'.$redirect_action.$query);
            else
                $this->_redirect('/incite/documents/'.$redirect_action);
		}	
    }

}
