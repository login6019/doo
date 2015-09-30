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
	use Elastica\Query\SortQuery;
	use Elastica\Filter\FilterQuery;


require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
//curl -XGET '10.17.139.144:9200/cast/_search'
class Autocomplate 
{

#GET method 
	public function prefix( $indexType, $searchData ){

			$client			= $this->createClient();			
			$index			= $client->getIndex($indexType."log");
			$type				= $index->getType($indexType.'logType');

			$query['timeout']					= '5s';
			$query['from']						= 0; // offset
			$query['size']						= empty($searchData['limit'])? 7 : $searchData['limit']; // 가져올 개수
			$query['query']['bool']['should'][0]['prefix']['keyword']	= $searchData['searchKeyword'];  // 자동완성 
			$query['query']['bool']['should'][1]['prefix']['hangle']		= $searchData['searchKeyword'];  // 초성검색
			$query['sort'][0]['count']	= 'desc';  
			$query['sort'][1]['_score'] = 'desc';  

		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();
		return $responseArray;	
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

