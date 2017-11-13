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
class Incite_SummariesController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        include('graders.php');
        $this->view->graders = $graders['summary'];
    }

    public function indexAction()
    {
		//Nothing to do right now. The view is enough to handle output.
    }
    public function gradeAction(){
        
    }

}
