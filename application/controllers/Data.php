<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Elastica\Client;
use Elastica\Document;
use Elastica\Filter\Term as TermFilter;
use Elastica\Query;
use Elastica\Query\MatchAll as MatchAllQuery;
use Elastica\Request;
use Elastica\Type\Mapping;
use Elastica\Index\IndexSettingArray;
use Elastica\Index\IndexMappingArray;

#elastic search test server
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
class Data extends ES_Controller {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
	
		$this->getAllIndex();

	}
    
	public function getAllIndex() {

		$client	= $this->client();
		$index	= $client->getIndex($this->_indexName());
		$type		= $index->getType($this->_typeName());
        $query = '{"query":{"query_string":{"query":"ruflin"}}}';
        $path = $index->getName().'/'.$type->getName().'/_search';

        $response = $client->request($path, Request::GET, $query);
        $responseArray = $response->getData();
		print_r($responseArray)		
	}


}
