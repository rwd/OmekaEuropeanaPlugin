<?php
/**
 * Europeana
 *
 * @copyright Copyright 2014 Richard Doe
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Search controller.
 */
class Europeana_EuropeanaController extends Omeka_Controller_AbstractActionController
{
    protected $_cache = null;
    
    /**
     * Searches Europeana.
     */
    public function searchAction()
    {
        $records = array();
        $totalResults = 0;
        $errorMessage = null;
        
        $searchParams = $this->getApiSearchParams();
        
        if (!empty($searchParams['query'])) {
            
            $response = $this->getApiSearchResponse($searchParams);
            
            if ($response['success']) {
                if ($response['itemsCount'] > 0) {
                    foreach ($response['items'] as $item) {
                        $er = new EuropeanaRecord();
                        $er->setArray($item);
                        $records[] = $er;
                    }
                }
                
                $totalResults = $response['totalResults'];
                if ($totalResults) {
                    Zend_Registry::set('pagination', array(
                        'page' => $this->getCurrentPage(),
                        'per_page' => $searchParams['rows'],
                        'total_results' => $totalResults,
                    ));
                }
            } else {
                $errorMessage = $response['error'];
                _log("Europeana API error: " . $errorMessage, Zend_Log::ERR);
            }
        }
        
        $this->view->assign(array(
            'query' => $this->getParam('q'),
            'records' => $records,
            'totalResults' => $totalResults,
            'error' => $errorMessage,
        ));
    }
    
    public function getCache()
    {
        if (!isset($this->_cache)) {
            $cacheManager = $this->getFrontController()->getParam('bootstrap')->getResource('cachemanager');
            if ($cacheManager->hasCache('europeana')) {
                $this->_cache = $cacheManager->getCache('europeana');
            }
        }
        return $this->_cache;
    }
    
    private function getApiSearchQueryParam()
    {
        $userQuery = $this->getParam('q');
        
        if ($siteQuery = get_option('europeana_query')) {
            $apiSearchQueryParam = $siteQuery;
            if ($userQuery) {
                $apiSearchQueryParam = "({$apiSearchQueryParam}) AND $userQuery";
            }
        } else {
            $apiSearchQueryParam = $userQuery;
        }
        return $apiSearchQueryParam;
    }
    
    private function getApiSearchParams()
    {
        $currentPage = $this->getCurrentPage();
        
        $perPage = get_option('per_page_public');
        $firstResult = (($currentPage - 1) * $perPage) + 1;
        
        return array(
            'query' => $this->getApiSearchQueryParam(),
            'rows'  => $perPage,
            'start' => $firstResult,
        );
    }
    
    private function getCurrentPage()
    {
        static $currentPage;
        
        if (!isset($currentPage)) {
            $currentPage = (int) $this->getParam('page');
            $currentPage = ($currentPage > 0) ? $currentPage : 1;
        }
        
        return $currentPage;
    }
    
    private function getApiSearchResponse($searchParams = array())
    {
        debug(http_build_query($searchParams));
        
        if ($cache = $this->getCache()) {
            $cacheKey = "search_" . md5(http_build_query($searchParams));
            $data = $cache->load($cacheKey);
        } else {
            $data = false;
        }
        
        if ($data === false) {
            $client = new Zend_Http_Client('http://europeana.eu/api/v2/search.json', array(
                'maxredirects' => 0,
                'timeout'      => 30));
            $client->setParameterGet(array_merge($searchParams, array('wskey' => get_option('europeana_api_key'))));
            $data = $client->request()->getBody();
        }
        
        $response = json_decode($data, true);

        if ($response['success'] && $cache && !$cache->test($cacheKey)) {
            $cache->save($data);
        }
        
        return $response;
    }
}

