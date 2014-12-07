<?php

/**
 * Europeana
 * 
 * @copyright Copyright 2014 Richard Doe
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Europeana plugin.
 * 
 * @package Omeka\Plugins\Europeana
 */
class EuropeanaPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'uninstall',
        'config_form',
        'config',
        'initialize',
        'define_routes',
    );
    
    protected $_filters = array(
        'public_navigation_main',
    );
    
    public function hookInstall()
    {
        set_option('europeana_api_key', '');
        set_option('europeana_query', '');
    }

    public function hookUninstall()
    {
        delete_option('europeana_api_key');
        delete_option('europeana_query');
    }
    
    public function hookConfigForm()
    {
        include 'config_form.php';
    }
    
    public function hookConfig($args)
    {
        set_option('europeana_api_key', $_POST['europeana_api_key']);
        set_option('europeana_query', $_POST['europeana_query']);
    }
    
    public function hookInitialize()
    {
        add_shortcode('europeana_search_form', array($this, 'searchForm'));
    }
    
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];
        
        $searchRoute = new Zend_Controller_Router_Route('europeana',
            array(
                'module'     => 'europeana',
                'controller' => 'europeana',
                'action'     => 'search',
            )
        );
        $router->addRoute('europeana_search', $searchRoute);
    }
    
    public function searchForm($args, $view) {
        return $view->partial('europeana/search-form.php', array('query' => ''));
    }
    
    public function filterPublicNavigationMain($navArray)
    {
        $navArray['Search Europeana'] = array(
            'label'=>__('Search Europeana'),
            'uri' => url('europeana')
        );
        return $navArray;
    }
}

