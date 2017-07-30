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
      $this->view->title = get_option("title");
      $this->view->intro = get_option("intro");
      $this->view->twitter_timeline = get_option("twitter_timeline");
      $this->view->twitter_button = get_option("twitter_button");
      $this->view->fb = get_option("fb");
    }
    public function aboutAction()
    {
        
    }

}
