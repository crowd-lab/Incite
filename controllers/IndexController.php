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
       //echo '<div style="color:red">Index Controller Initialized! This is probably a good place to put the header such as <a href="./discover">discover</a> - <a href="transcribe">transcribe</a> - <a href="tag">tag</a> - <a href="connect">connect</a> - <a href="discuss">discuss</a></div>';
    }

    public function indexAction()
    {
/*
                        echo '<div style="color:black">Welcome to Homepage (index)!</div>';
    $this->_helper->db->setDefaultModelName('Item');
    //$tags = $this->_helper->db->findById(225, 'Item')->getTags();
    $record = $this->_helper->db->findById(225);
    //print_r($tags[0]->name);
    //$record = $this->_helper->db->findBy(array('type'=>18)); //6 is still image, 18 is news paper
    //$record = $this->_helper->db->getTable('Tag')->findBy();

    //print_r($record);
    $this->view->assign(array('Item' => $record));
*/
    }

    public function discoverAction()
    {

                        echo '<div style="color:red">Welcome to Discover!</div>';
			print_r($this->_getAllParams());
    }

    public function transcribeAction()
    {

                        echo '<div style="color:red">Welcome to Transcribe!</div>';
    }

    public function tagAction()
    {
                        echo '<div style="color:blue">Welcome to Tag!</div>';
    }

    public function connectAction()
    {
                        echo '<div style="color:green">Welcome to Connect!</div>';
    }

    public function discussAction()
    {
                        echo '<div style="color:black">Welcome to Discuss!</div>';
    }
}
