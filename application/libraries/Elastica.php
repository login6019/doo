<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Elastica\Client;
use Elastica\Script;
use Elastica\Document;
use Elastica\Hangle_cho;
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
class Elastica 
{

#DELETE method 
	public function deleteData( $id, $indexType){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');
        $type->deleteById($id);
        $index->refresh();
		return true;
	}
#DELETE method 

#GET method 
	public function getData( $indexType, $searchData ){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		$query['timeout']					= '5s';
		$query['from']						= empty($searchData['sequence'])? 0 : $searchData['sequence']; // offset
		$query['size']						= empty($searchData['limit'])? 0 : $searchData['limit']; // 가져올 개수

		$indexTypeQuery = new IndexTypeQuery($indexType, $searchData);

		switch($indexType){
			case "stay":
			
				//$query['min_score'] = 0.1;
				$query['query']['query_string']['fields']					= $indexTypeQuery->getQueryScore();  
				$query['query']['query_string']['query']					= $indexTypeQuery->getDetailQuery();  
				$query['query']['query_string']['use_dis_max']		= true;		
				$query['query']['query_string']['default_operator']	= "OR";	
				$query['track_scores'] = true;
				/*
				$query['query']['function_score']['query']['query_string']['fields']= $indexTypeQuery->getQueryScore();
				$query['query']['function_score']['query']['query_string']['query']= $indexTypeQuery->getDetailQuery();  
				$query['track_scores'] = true;				
				$query['query']['function_score']['functions'][0]['script_score']['script']= "_score * _source.recentWeekClickCount";
				$query['query']['function_score']['functions'][1]['script_score']['script']= "_score * _source.adPrice * 10000";
				$query['query']['function_score']['functions'][2]['script_score']['script']= "_score - (_source.order * 0.0001)";
				$query['query']['function_score']['score_mode'] = 'avg';
				*/

			break;
			case "travel":	
			case "cast":
			case "photo":
				$query['query']['query_string']['fields']					= $indexTypeQuery->getQueryScore();  
				$query['query']['query_string']['query']					= $indexTypeQuery->getDetailQuery();  
				$query['query']['query_string']['use_dis_max']		= true;
				$query['track_scores'] = true;
			break;
			default:		
		}

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

#PUT method 
	public function putData($id, $indexType, $data){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		if($data['searchKeyword']){ $data['searchKeyword'] = $this->deduplication($data['searchKeyword']);	}
		if($data['latitude'] && $data['longitude']){	$data['location']	= $this->getGeoPoint($data['latitude'], $data['longitude']);	}
		if($data['propertyKeyword']){ $data['propertyKeyword']	= $this->deduplication($data['propertyKeyword']);	}
		if($data['roomsKeyword']){	$data['roomsKeyword']	= $this->deduplication($data['roomsKeyword']);	}
		if($data['content']){$data['content']		= $this->content_deduplication($data['content']);	}
		if($data['reviewsCount']){$data['reviewsCount'] = (float)($val['reviewsCount']);	}
		if($data['adPrice']){$data['adPrice'] = (float)($val['adPrice']);	}
		if($data['order']){$data['order'] = (float)($val['order']);	}
		if($data['recentWeekClickCount']){$data['recentWeekClickCount'] = (float)($val['recentWeekClickCount']);	}

		$type->updateDocument(new Document($id, $data));
        $updatedDoc = $type->getDocument($id)->getData();
		return $updatedDoc;	
	}
#PUT method 

#POST method 
	public function postData($id, $indexType, $data){
		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType);
		$type				= $index->getType($indexType.'Type');

		$data['searchKeyword'] = $this->deduplication($data['searchKeyword']);	//검색어 필드 중복제거 	

		switch($indexType){
			case "stay":		
				$data['location']					= $this->getGeoPoint($data['latitude'], $data['longitude']);
				$data['propertyKeyword']	= $this->deduplication($data['propertyKeyword']);	
				$data['roomsKeyword']		= $this->deduplication($data['roomsKeyword']);	

				$val['reviewsCount']				= (float)($val['reviewsCount']);	
				$val['adPrice']						= (float)($val['adPrice']);	
				$val['order']							= (float)($val['order']);	
				$val['recentWeekClickCount']	= (float)($val['recentWeekClickCount']);	

			break;
			case "travel":
				$data['location']		= $this->getGeoPoint($data['latitude'], $data['longitude']);
			break;
			case "cast":
				$data['content']		= $this->content_deduplication($data['content']);	
			break;
			default:		
		}

		$type->addDocument(new Document($id, $data));
        $updatedDoc = $type->getDocument($id)->getData();
		return $updatedDoc;	
	}
#POST method 



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

#중복제거
	public function deduplication($txt){
		$arrTxt = explode(",", $txt);
		foreach($arrTxt as $txtVal){
			$Arr[]= trim($txtVal);
		}	
		$txt = implode(" ", array_unique($Arr));	
		return $txt;
	}
#중복제거

#중복제거
	public function image_deduplication($txt){
		$arrTxt = explode("|", $txt);
		foreach($arrTxt as $txtVal){
			$Arr[]= trim($txtVal);
		}	
		$txt = implode("|", array_unique($Arr));	
		return $txt;
	}
#중복제거

#데이터 불순물 제거
	public function content_deduplication($txt){
		$arrReplace = array("ㅫ","&nbsp;");	
		foreach($arrReplace as $val):
			$txt = str_replace($val,'',$txt);
		endforeach;	
		return strip_tags($txt);
	}
#데이터 불순물 제거

#좌표값 입력
    public function getGeoPoint($lat, $lon)
    {
        $doc = new Document();
		$returnValue = $doc->addGeoPoint('location', (float)$lat, (float)$lon);
		return $returnValue->location;
    }
#좌표값 입력

#simple get  for log
    public function indexLogWrite($indexType, $searchKeyword)
    {
		if( ! ini_get('date.timezone') )
		{
			date_default_timezone_set('asia/seoul');
		}	

		$hangle = new Hangle_cho();

		$client			= $this->createClient();			
		$index			= $client->getIndex($indexType."log");
		$type				= $index->getType($indexType.'logType');
		$nowdate		= date('Y-m-d');

		$query['timeout']					= '5s';
		$query['from']						= 0;
		$query['size']						= 1;
		$query['query']['query_string']['query']	= "keyword:".$searchKeyword;  
		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();

		if($responseArray['hits']['total'] >0 ) {			
			$id = $responseArray['hits']['hits'][0]['_id'];
			$data['keyword'] = $searchKeyword;
			$data['date']		 = $nowdate;
			$data['count']	 = $responseArray['hits']['hits'][0]['_source']['count'] + 1;
			$type->updateDocument(new Document($id, $data));
		} else {	
			$data['keyword']	= $searchKeyword;			
			$data['date']			= $nowdate;
			$data['count']		= 1;	
			$data['hangle']		= $hangle->hangul_split($searchKeyword);
			$id = ceil(microtime('YmdHis')).rand(10000,99999);
			$type->addDocument(new Document($id, $data));
		}
    }
#simple get  for log

}



