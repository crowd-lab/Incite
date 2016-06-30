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
    require_once("Incite_Helpers.php");
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


<<<<<<< HEAD
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
          $redirect_action = 'documents/transcribe';

        }
      }

      if (strlen($query) > 1)  //more than "?"
      $this->_redirect('/incite/'.$redirect_action.$query);
      else
      $this->_redirect('/incite/'.$redirect_action);
=======
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
>>>>>>> 5ec6405a5b1ea955f5e73392e194d43bbe039da0
    }
  }
  


}
