<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Elastica\Client;
use Elastica\Script;
use Elastica\Document;
use Elastica\Filter\Term as TermFilter;
use Elastica\Query;
use Elastica\IndexTypeQuery;
use Elastica\Query\MatchAll as MatchAllQuery;
use Elastica\Request;
use Elastica\Type\Mapping;

require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
//curl -XGET '10.17.139.144:9200/cast/_search'
class Elastica_test 
{


#GET method 
	public function getData(){
		$indexName = "doo";
		$indexTypeName = "dooType";
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexName);
		$type				= $index->getType($indexTypeName);

		$query['query']['query_string']['fields']					= array("firstname");  
		$query['query']['query_string']['query']					= "두현";  
		$query['query']['query_string']['use_dis_max']		= true;
		$query['track_scores'] = true;

		$path			= $indexName. '/' .$indexTypeName. '/_search';
		$response = $client->request($path, Request::GET, $query);

		$responseArray = $response->getData();
		return $responseArray;	
	}
#GET method 


#GET method 
	public function upsertData(){

		$doc = new Document();



		$indexName = "doo";
		$indexTypeName = "dooType";
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexName);
		$type				= $index->getType($indexTypeName);

		$val['title'] = "illanare2";	
		$val['name']['firstname'] = "도끼토끼";	
		$val['level'] = 2;	


		$doc->setData($val);
		$doc->setId(2);
		$type->updateDocument($doc); 




	}
#GET method 


#SET client 
	public function createClient(){
		$client = new Client(array(
			'servers' => array(
				array('host' => HOST_ES_001, 'port' => PORT_ES_001),
				array('host' => HOST_ES_002, 'port' => PORT_ES_002)
			)
		));
		return $client;
	}	
#SET client 


}



