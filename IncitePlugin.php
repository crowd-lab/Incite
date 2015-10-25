<?php

/**
 * The Incite plugin.
 *
 * @package Omeka\Plugins\Incite
 */

class IncitePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'config_form',
        'config',
	'define_routes',
        'public_items_show',
        'public_head',
    );

    /**
     * Display the config form.
     */
    public function hookConfigForm()
    {
        require 'config-form.php';
    }

    /**
     * Handle the config form.
     */
    public function hookConfig()
    {
        set_option('text_test', trim($_POST['text_test']));
    }

    /**
     * Controller triggering on /incite/stage/ACTIONS,
     */
    public function hookPublicHead($args)
    {
            get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'Incite_View_Helper_');
    }

    /**
     * Print out configured message on item show
     */
    public function hookPublicItemsShow()
    {
           echo '<p>'.get_option('text_test').'</p>';
    }
    public function hookDefineRoutes($args)
    {
/*
if (!defined('EXHIBIT_PLUGIN_DIR')) {
    define('EXHIBIT_PLUGIN_DIR', dirname(__FILE__));
}
*/
	    $router = $args['router'];
//    	    $router->addConfig(new Zend_Config_Ini(EXHIBIT_PLUGIN_DIR .
//        DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
///*	    
	    $route = new Zend_Controller_Router_Route(
			'incite/:controller/:action/:id',
			array(
				'module' => 'incite',
				'controller' => 'documents',
				'action' => 'index')
	    );
	    $router->addRoute('incite', $route);
//*/
	    //print_r($router);
    }
}
