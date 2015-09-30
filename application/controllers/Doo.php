<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Elastica\Client;
use Elastica\Document;
use Elastica\Hangle_cho;
use Elastica\Filter\Term as TermFilter;
use Elastica\Query;
use Elastica\Query\MatchAll as MatchAllQuery;
use Elastica\Request;
use Elastica\Type\Mapping;
use Elastica\Index\IndexSettingArray;
use Elastica\Index\IndexMappingArray;

#elastic search test server
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';

class Doo extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }




    public function coo() {





		$data['searchKeyword'] = 'ㄱ';
		$client	= $this->client();		
		$index			= $client->getIndex("staylog");
		$type				= $index->getType('staylogType');

		$query['timeout']					= '5s';
		$query['from']						= 0;
		$query['size']						= 10;



		$query['query']['bool']['should'][0]['prefix']['keyword']	= $data['searchKeyword'];  
		$query['query']['bool']['should'][1]['prefix']['hangle']	= $data['searchKeyword'];  


		//$query['query']['query_string']['query']	= "keyword:".$data['searchKeyword'];  


		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();




		print_r($responseArray);






	}






/*



    public function boo() {


$curl = new Curl;


$data = array();





$a = $curl->get('http://14.63.192.97:9888/search_log/boo');


print_r($a);


//$aaa = proc_close(proc_open ("http://14.63.192.97:9888/search_log/boo ", $data, $foo));

//print_r($foo);


echo "end";

	}


    public function aoo() {


				$hangle		= new Hangle_cho();

				print_r($hangle);


				exit;



		if( ! ini_get('date.timezone') )
		{
			date_default_timezone_set('asia/seoul');
		}

		$nowdate = date('Y-m-d');

		$data['searchKeyword'] = '오징어';
		$client	= $this->client();		
		$index			= $client->getIndex("staylog");
		$type				= $index->getType('staylogType');

		$query['timeout']					= '5s';
		$query['from']						= 0;
		$query['size']						= 1;
		$query['query']['query_string']['query']	= "keyword:".$data['searchKeyword'];  
		$path			= $index->getName() . '/' . $type->getName() . '/_search';
		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();

		if($responseArray['hits']['total'] >0 ) {
			
			$id = $responseArray['hits']['hits'][0]['_id'];
			$data['keyword'] = $data['searchKeyword'];
			$data['date']		 = $nowdate;
			$data['count']	 = $responseArray['hits']['hits'][0]['_source']['count'] + 1;
			$type->updateDocument(new Document($id, $data));
		} else {	
			$data['keyword'] = $data['searchKeyword'];			
			$data['date']		= $nowdate;
			$data['count']	= 1;	
			$id = ceil(microtime('YmdHis')).rand(10000,99999);
			$type->addDocument(new Document($id, $data));
		}

		$updatedDoc = $type->getDocument($id)->getData();


		 print_r($updatedDoc);


	
	}








    public function han() {

		$this->load->library('hangle_cho');		
		echo $this->hangle_cho->hangul_split("전봉근이랑 영양사랑 그렇고 그런 구멍친구");

	}






    public function index() {
		$client	= $this->client();		
		$index	= $client->getIndex("stay");
	    $type		= $index->getType("stayType");


		$query['size'] =10;
		$query['query']['function_score']['query']['query_string']['fields']	[0] = 'name';  
		$query['query']['function_score']['query']['query_string']['query']= '*방배*';

		
		$query['query']['function_score']['functions'][0]['script_score']['script']= "_score * _source.recentWeekClickCount";
		$query['query']['function_score']['functions'][1]['script_score']['script']= "_score * _source.adPrice";
		$query['query']['function_score']['functions'][2]['script_score']['script']= "_score - (_source.order * 0.0001)";
		
		//$query['query']['function_score']['functions'][0]['script_score']['script']= "_score * doc.['recentWeekClickCount'].value";
		//$query['query']['function_score']['functions'][0]['script_score']['lang']= 'python';
		//$query['query']['function_score']['functions'][0]['script_score']['lang']= 'javascript';
		//$query['query']['function_score']['functions'][0]['script_score']['script']= "_score * 10";

		$query['query']['function_score']['score_mode'] = 'avg';
		$query['sort'][0]['_score'] = 'desc';

		$path			= $index->getName() . '/' . $type->getName() . '/_search';


		$response = $client->request($path, Request::GET, $query);
		$responseArray = $response->getData();


//print_r($responseArray);
//exit;





		foreach( $responseArray['hits']['hits'] as $val):
			echo $val['_source']['name']." : ".$val['_score']."(".$val['_source']['reviewsCount']." : ".$val['_source']['adPrice']." : ".$val['_source']['order'].")"."\n";
		endforeach;




		exit;


	}


	public function insert() {
    
		$client	= $this->client();		
		$index	= $client->getIndex("doo");
	    $type		= $index->getType("dooType");

		$val[0]['title'] = "우리집 오징어";	
		$val[0]['bus'] = array("1123","22","858");	
		$val[0]['name']['firstname'] = "두현";	
		$val[0]['name']['lastname'] = "이";	
		$val[0]['level']= 1;	

		$val[1]['title'] = "봉근이네 오징어";	
		$val[1]['bus'] = array("556","4545","965");	
		$val[1]['name']['firstname'] = "봉근";	
		$val[1]['name']['lastname'] = "전";	
		$val[1]['level']= 1;	

		$val[2]['title'] = "봉근 고치미";	
		$val[2]['bus'] = array("407","22","12");	
		$val[2]['name']['firstname'] = "봉근";	
		$val[2]['name']['lastname'] = "전";	
		$val[2]['level']= 2;	

		$val[3]['title'] = "현돈 고치미";	
		$val[3]['bus'] = array("998","5225","585");	
		$val[3]['name']['firstname'] = "현돈";	
		$val[3]['name']['lastname'] = "유";	
		$val[3]['level']= 5;	

		$val[4]['title'] = "봉근 문어";	
		$val[4]['bus'] = array("4887","9","44");	
		$val[4]['name']['firstname'] = "봉근";	
		$val[4]['name']['lastname'] = "전";	
		$val[4]['level']= 7;	

		$val[5]['title'] = "현돈꼴뚜기";	
		$val[5]['bus'] = array("4175","782","546");	
		$val[5]['name']['firstname'] = "현돈";	
		$val[5]['name']['lastname'] = "유";	
		$val[5]['level']= 7;	

		$val[6]['title'] = "돈가스돼지";	
		$val[6]['bus'] = array("332","385","5");	
		$val[6]['name']['firstname'] = "효성";	
		$val[6]['name']['lastname'] = "강";	
		$val[6]['level']= 3;	

		$val[7]['title'] = "큐피";	
		$val[7]['bus'] = array("888","345","546");	
		$val[7]['name']['firstname'] = "현호";	
		$val[7]['name']['lastname'] = "최";	
		$val[7]['level']= 3;	

		$val[8]['title'] = "쿼리돼지";	
		$val[8]['bus'] = array("556","899","875");	
		$val[8]['name']['firstname'] = "현호";	
		$val[8]['name']['lastname'] = "최";	
		$val[8]['level']= 3;	

		$val[9]['title'] = "쿼리피그";	
		$val[9]['bus'] = array("4","315","45");	
		$val[9]['name']['firstname'] = "현호";	
		$val[9]['name']['lastname'] = "최";	
		$val[9]['level']= 3;	

		for($i=0;$i<10;$i++){
			$type->addDocument(new Document($i, $val[$i]));
		}

echo "end";


	}

	public function get() {
		$this->load->library('elastica_test');
		$data = 	$this->elastica_test->getData();
		print_r($data);
	}
    

	public function update() {
		$this->load->library('elastica_test');
		$data = 	$this->elastica_test->upsertData();
		print_r($data);
	}


*/


}
