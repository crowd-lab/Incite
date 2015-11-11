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
class Incite_DiscoverController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
    }

    public function indexAction()
    {
		if ($this->getRequest()->isGet()) {
			if (isset($_GET['task'])) {
				if ($_GET['task'] == "transcribe") {
					//$this->_helper->viewRenderer('tagid');
					$this->_redirect('/incite/documents/transcribe');
				} else if ($_GET['task'] == "tag") {
					$this->_redirect('/incite/documents/tag');
				} else if ($_GET['task'] == "connect") {
					$this->_redirect('/incite/documents/connect');
				} else if ($_GET['task'] == "discuss") {
					$this->_redirect('/incite/discussions');
				} else { //then...random!
				}
			}
		}	
    }

}
