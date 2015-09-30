<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#elastic search test server
//http://211.253.8.108:9200/_plugin/head/   
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
class Manage extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }
	public function index()
	{




	}


public function close($index){
	$post_data = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://172.27.249.240:9200/'.$index.'/_close');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_exec($ch);
}


public function open($index){
	$post_data = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://172.27.249.240:9200/'.$index.'/_open');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_exec($ch);
}




	public function place()
	{

		$client = new \Elastica\Client(array(
			'servers' => array(
				array('host' => '172.27.249.240', 'port' => 9200),
				array('host' => '172.27.249.240', 'port' => 9200)

			)
		));


		$indexName = 'place';
		$typeName = "placeInfo";
		$index = $client->getIndex($indexName);
		$index->create(array(), true); //인덱스 새로 생성
		$type = $index->getType($typeName);
		$this->close($indexName);


		$indexSetting['index']['analysis']['analyzer']['korean']['type'] ="custom";
		$indexSetting['index']['analysis']['analyzer']['korean']['tokenizer'] ="mecab_ko_standard_tokenizer";
		$index->setSettings($indexSetting);
		$this->open($indexName); //인덱스 열기


##POST 명령어 하나도 안먹힘 
/*
$index->close(); //인덱스 닫기 
$index->open(); //인덱스 열기 
*/

		$data = $this->manage->getSearchData();

		foreach($data as $val): 

		$type->addDocument(new \Elastica\Document($val['no'], $val));
		exit;
		endforeach;


	}





}
