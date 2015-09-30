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
class Process_test extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }

    public function index() {

	
    }


	public function insert(){
	        $doc = new Document();
			$client = $this->client();

			$index	= $client->getIndex($this->_indexName());
		    $type = $index->getType($this->_typeName());

			/*
			$no								= $this->input->post('no', true);
			$val['name']					= $this->input->post('name', true);
			$val['searchKeyword']	= $this->input->post('filter', true);
			$val['searchKeyword']['filter']	= 'filter';
			$val['searchKeyword']['state'] = 'state';
			*/

			$no = 1;
			$val['name']	=	"이두현"	;

			$val['lat']			= $this->input->post('lat', true);
			$val['lon']			= $this->input->post('lon', true);
			$returnValue		= $doc->addGeoPoint('location', (float)$val['lat'], (float)$val['lon']);
			$val['location']	= $returnValue->location;
			$type->addDocument(new Document($no, $val));
	}


}


//curl -XPOST "http://localhost:9200/doo/dooType/1" -d '{ name : "이두현"}'
