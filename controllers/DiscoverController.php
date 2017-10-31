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
    /**
    * Init function of this class
    *
    */
    public function init()
    {
        require_once("Incite_Helpers.php");
        require_once("Incite_Search.php");
    }

    /**
    * Index page 
    *
    */
    public function indexAction()
    {
		if ($this->getRequest()->isGet()) {
            //Assume there is session already because query is stored in session
            $query = "?";
            //Process location search
            if (isset($_GET['location']) && $_GET['location'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'location='.$_GET['location'];
            }

            //Process time search
            if (isset($_GET['time']) && $_GET['time'] != "") { //for advanced search. We also have dates
                $query .= (strlen($query) > 1 ? '&' : '').'time='.$_GET['time'];
            } else if (isset($_GET['time_from']) &&  //from landing page. We only have years.
                       isset($_GET['time_to']) &&
                       $_GET['time_from'] != "" &&
                       $_GET['time_to'] != "") {
                if (strlen($_GET['time_from']) == 4 && strlen($_GET['time_to']) == 4)
                    $query .= (strlen($query) > 1 ? '&' : '').'time='.$_GET['time_from'].'-01-01 - '.$_GET['time_to'].'-12-31';
                else if (strlen($_GET['time_from']) == 10 && strlen($_GET['time_to']) == 10)
                    $query .= (strlen($query) > 1 ? '&' : '').'time='.$_GET['time_from'].' - '.$_GET['time_to'];
                else //unknown format
                    $query .= "";
            }

            //Process keyword search
            if (isset($_GET['keywords']) && $_GET['keywords'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'keywords='.$_GET['keywords'];
            }

            $redirect_action = '';
            //Go to the desired task based on the above result
			if (isset($_GET['task'])) {
				if ($_GET['task'] == "transcribe") {
                    $redirect_action = 'documents/transcribe';
				} else if ($_GET['task'] == "tag") {
                    $redirect_action = 'documents/tag';
				} else if ($_GET['task'] == "connect") {
                    $redirect_action = 'documents/connect';
				} else if ($_GET['task'] == "discuss") {
                    $redirect_action = 'discussions/discuss';
				} else { //then...random! but currently need more transcriptions
                    $redirect_action = 'documents/view';
				}
			}

            if (strlen($query) > 1)  //more than "?"
                $this->_redirect('/incite/'.$redirect_action.$query);
            else
                $this->_redirect('/incite/'.$redirect_action);
		}
    }

}
