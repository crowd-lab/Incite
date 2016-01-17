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
            $query = "?";
            //Process location search
            if (isset($_GET['location']) && $_GET['location'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'location='.$_GET['location'];
            }

            //Process time search
            if (isset($_GET['time']) && $_GET['time'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'time='.$_GET['time'];
            }

            //Process keyword search
            if (isset($_GET['keywords']) && $_GET['keywords'] != "") {
                $query .= (strlen($query) > 1 ? '&' : '').'keywords='.$_GET['keywords'];
            }

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
