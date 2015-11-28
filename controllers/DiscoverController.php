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
            //Process location search

            //Process time search

            //Process keyword search

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
				} else { //then...random!
				}
			}
		}	
    }

}
