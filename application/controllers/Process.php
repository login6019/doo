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
class Process extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }

    public function index() {
    
	}
    public function getGeoPoint($lat, $lon)
    {
        $doc = new Document();
		$returnValue = $doc->addGeoPoint('location', (float)$lat, (float)$lon);
		return $returnValue->location;
    }
	public function createIndex(){
		$client = $this->client();
		$index = $client->getIndex($this->_indexName());
		$index->create(array(), true); //인덱스 새로 생성
		$type = $index->getType($this->_typeName());
		header("location:/manage/setting?index=".$this->_indexName());
	}

	public function deleteIndex(){
		$client = $this->client();
		$index = $client->getIndex($this->_indexName());
		$index->delete();
		header("location:/manage");
	}

	public function settingIndex(){
		$client = $this->client();
		$index = $client->getIndex($this->_indexName());

		$index->close(); //인덱스 닫기 
		$IndexSettingArray		= new IndexSettingArray();
		$IndexMappingArray	= new IndexMappingArray($this->_indexName());

		$indexSetting	= $IndexSettingArray->getIndexSetting();
		$index->setSettings($indexSetting);

        $type = $index->getType($this->_typeName());
		$mapping = new Mapping();

		$mappingParam = $IndexMappingArray->getMappingParam();
        $mapping->setParam('_boost', $mappingParam);
		
		$properties	= $IndexMappingArray->getProperties();
        $mapping->setProperties($properties);
        
		$type->setMapping($mapping);

		$index->open(); //인덱스 열기 

		if (preg_match("/log/i", $this->_indexName())) {
			header("location:/manage");	
		} else {
			header("location:/manage/".$this->_indexName()."Insert");
		}
	}

#travel 정보 넣기
	public function travelInsert(){
			$client	= $this->client();		
			$index	= $client->getIndex($this->_indexName());
		    $type		= $index->getType($this->_typeName());
			$data		= $this->manage->getTravelList();
			foreach($data as $key=>$val): 
				$val['location']				= $this->getGeoPoint($val['latitude'], $val['longitude']);
				$val['searchKeyword']	= $this->elastica->deduplication($val['searchKeyword']);	
			$type->addDocument(new Document($val['id'], $val));
			endforeach;
			header("location:/manage");
	}
#travel 정보 넣기
#cast 정보 넣기
	public function castInsert(){
			$client	= $this->client();
			$index	= $client->getIndex($this->_indexName());
		    $type		= $index->getType($this->_typeName());
			$data		= $this->manage->getCastList();
			foreach($data as $key=>$val): 
				$val['content']				= $this->elastica->content_deduplication($val['content']); 
				$val['searchKeyword']	= $this->elastica->deduplication($val['searchKeyword']);	
				$val['thumbNails']	= $this->elastica->image_deduplication($val['thumbNails']);	
				$type->addDocument(new Document($val['id'], $val));
			endforeach;
			header("location:/manage");
	}
#cast 정보 넣기
#사진정보 넣기
	public function photoInsert(){
			$client	= $this->client();
			$index	= $client->getIndex($this->_indexName());
		    $type		= $index->getType($this->_typeName());
			$data		= $this->manage->getPhotoList();
			foreach($data as $key=>$val): 
				$val['searchKeyword'] = $this->elastica->deduplication($val['searchKeyword']);					
				$type->addDocument(new Document($val['id'], $val));
			endforeach;
			header("location:/manage");
	}
#사진정보 넣기
#stay정보 넣기
	public function stayInsert(){
			$client	= $this->client();
			$index	= $client->getIndex($this->_indexName());
		    $type		= $index->getType($this->_typeName());
			$data		= $this->manage->getStayList();
			foreach($data as $key=>$val): 
				$val['location']				= $this->getGeoPoint($val['latitude'], $val['longitude']);
				$val['searchKeyword']	= $this->elastica->deduplication($val['searchKeyword']);	
				$val['propertyKeyword']	= $this->elastica->deduplication($val['propertyKeyword']);	
				$val['roomsKeyword']	= $this->elastica->deduplication($val['roomsKeyword']);	
				$val['reviewsCount']		= (float)($val['reviewsCount']);	
				$val['adPrice']				= (float)($val['adPrice']);	
				$val['order']					= (float)($val['order']);	
				$val['recentWeekClickCount']	= (float)($val['recentWeekClickCount']);	

			$type->addDocument(new Document($val['id'], $val));
			endforeach;
			header("location:/manage");
	}
#stay정보 넣기


}
