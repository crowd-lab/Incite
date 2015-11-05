<?php
/**
 * Incite 
 *
 */

/**
 * Plugin "Incite"
 *
 * @package Incite 
 * Ajax controller for responding different ajax requests
 */
class Incite_AjaxController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
		//Since this is for ajax purpose, we don't need to render any views!
		$this->_helper->viewRenderer->setNoRender(TRUE);
    }

	//Demo of getting users
    public function getuserAction()
    {
		echo 'getuser!';
    }

}
