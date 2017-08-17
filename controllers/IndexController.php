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
class Incite_IndexController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
      require_once("DB_Connect.php");
      require_once("Incite_Env_Setting.php");
      require_once("Incite_Helpers.php");
    }

    public function indexAction()
    { 
      
    }
    public function aboutAction()
    {
        
    }

}
