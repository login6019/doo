<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
상세검색을 위한 라이브라리
*/
use Elastica\Client;
use Elastica\Script;
use Elastica\Document;
use Elastica\Filter\Term as TermFilter;
use Elastica\Query;
use Elastica\IndexTypeQuery;
use Elastica\Query\MatchAll as MatchAllQuery;
use Elastica\Request;
use Elastica\Type\Mapping;
use Elastica\Connection\CommonClass;
use Elastica\Query\SortQuery;
use Elastica\Filter\FilterQuery;

require(APPPATH.'/libraries/lib/curl.php');
require(APPPATH.'/libraries/lib/curl_response.php');
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
//curl -XGET '10.17.139.144:9200/cast/_search'
class Elastica_get 
{

#GET method 
	public function getDetailData( $indexType, $searchData ){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		$query['timeout']					= '5s';
		$query['from']						= $searchData['sequence']; // offset
		$query['size']						= $searchData['limit']; // 가져올 개수
	
		$indexTypeQuery = new IndexTypeQuery($indexType, $searchData);

			$query['query']['query_string']['query']					= $indexTypeQuery->getDetailQuery();  		
			$query['query']['query_string']['fields']					= $indexTypeQuery->getQueryScore();  
			$query['query']['query_string']['default_operator']	= "and";
			$query['query']['query_string']['use_dis_max']		= true;
			$query['track_scores'] = true;

		switch($indexType){
			case "stay":
			case "travel":
				$filterQuery		= new FilterQuery($searchData);
				if(isset($searchData['lat']) && isset($searchData['lon']) && isset($searchData['distance']) ) {
					$query	= array_merge($query, $filterQuery->geoDistanceFilter());  	// 거리필터
				}
			break;
			case "cast":
			case "photo":				
					$searchData['sort'] = 'score';				
			break;
			default:		
		}

		$sortQuery		= new SortQuery($indexType, $searchData);
		$query			= array_merge($query, $sortQuery->orderBySort());  	//기본정렬 거리순	
		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();
		return $responseArray;	
	}
#GET method 

#DirectQuery for JsonQuery
	public function getDirectQuery( $indexType, $searchData ){
		$curl = new Curl;	
		$CommonClass		= new CommonClass;
		$url = $CommonClass->url(HOST_ES_001, PORT_ES_001);
		$endPoint = $url."/".$indexType."/".$indexType."Type/_search";
		$response = $curl->post($endPoint, $searchData['jsonQuery']);
		return $response;		
	}

	public function getAdvertsQuery( $indexType, $searchData ){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		$sortQuery		= new SortQuery($indexType, $searchData);
		$filterQuery		= new FilterQuery($searchData);

		$query['timeout']		= '5s';
		$query['from']			= $searchData['sequence']; // offset
		$query['size']			= $searchData['limit']; // 가져올 개수
		$field										= '';
		$advertsPosition = (empty($searchData['advertsPosition']))? "MAIN": $searchData['advertsPosition'];			
		$asgIdx				= (empty($searchData['asgIdx']))? "": $searchData['asgIdx'];
		//검색조건(광고 위치)
		if( $advertsPosition ){
			$field													   = 'motelAppAdvert' . ucfirst(strtolower($advertsPosition));
			$query['query']['query_string']['query'] = '** +' . $field . ':>0';
			//검색조건(지역)
			if( $advertsPosition == 'AREA' || $asgIdx ){
				$query['query']['query_string']['query'] .= ' +areaKeys:'.$asgIdx;
			}
		}
		//검색조건(거리)
		if( $searchData['distance'] ){
			$query	= array_merge($query, $filterQuery->geoDistanceFilter());  	// 거리필터
		}

		//정렬
		$query		= array_merge($query, $sortQuery->orderBySort());  	//기본정렬 Score

		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();
		return $responseArray;	
	}

	public function getEventQuery( $indexType, $searchData ){

		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		$query['timeout']		= '5s';
		if(!empty($searchData['searchKeyword'])){
			$query['query']['filtered']['filter']['term']['id']		= $searchData['searchKeyword'];
		}
		$query['post_filter']['not']['term']['specialEvents']		= '0';
		$query['aggs']['by_field']['terms']['field']		= 'specialEvents';
		$query['aggs']['by_field']['terms']['size']		= 100;

		$query['aggs']['by_field']['terms']['order']['specialEventsSort'] = "ASC";
		$query['aggs']['by_field']['terms']['order']['specialEventsSortType'] = "DESC";

		$query['aggs']['by_field']['aggs']['specialEventsSort']['avg']['field'] = "specialEventsSort";
		$query['aggs']['by_field']['aggs']['specialEventsSortType']['avg']['field'] = "specialEventsSortType";
		$query['aggs']['by_field']['aggs']['top_tag_hits']['top_hits']['_source']['include']= array('specialEvents','specialEventsTitle','specialEventsSubTitle','specialEventsImage','specialEventsColorR','specialEventsColorG','specialEventsColorB','specialEventsSort');

		$path			= $index->getName() . '/' . $type->getName() . '/_search?search_type=count';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();
		return $responseArray;	
	}

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



