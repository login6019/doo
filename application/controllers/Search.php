<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#elastic search test server
//http://211.253.8.108:9200/_plugin/head/   

use Elastica\Connection\CommonClass;

require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
class Search extends ES_Controller {
    public function __construct() {
        parent::__construct();       
		header('Content-Type: application/json');


    }

	public function index()
	{	
	
		$this->_view("search/v_index","","search");
	}



	public function aoo()
	{	
header("Content-Type: application/json; charset=UTF-8");
		$curl = new Curl;	
		$CommonClass		= new CommonClass;

		$url = $CommonClass->url(HOST_ES_001, PORT_ES_001);



$url = $url."/stay/stayType/_search?";
$query['query']['prefix']['name']= '역삼';





$json_query =  json_encode($query);



$response = $curl->post($url, $json_query);


echo $response;
exit;


print_r($resultArray);







	}



	public function doo()
	{	
header("Content-Type: application/json; charset=UTF-8");
		$curl = new Curl;	
		$CommonClass		= new CommonClass;

		$url = $CommonClass->url(HOST_ES_001, PORT_ES_001);


/*
	$url =$url."/searchtube/";
	$response = $curl->put($url);
*/

/*
$url =$url."/searchtube/video/_mapping";
$val = '{
  "video": {
    "properties": {
      "title": {
        "type": "string"

      },
      "description": {
        "type": "string"

      },
      "views": {
        "type": "integer"
      },
      "likes": {
        "type": "integer"
      },
      "created_at": {
        "type": "date"
      }
    }
  }
}';
*/


/*
$url =$url."/searchtube/video/3";
$val = '{
  title: "Sick Sad World: Avant Garde Obstetrician",
  description: "Meet the avant-garde obstetrician who has turned his cast offs into art work. Severed Umbilical cord sculpture next, on Sick, Sad World.",
  views: 100,
  likes: 130,
  created_at: "2014-04-22T23:00:00"
}';
*/







/*
$url =$url."/searchtube/_search";
$val = '{
  "query": {
    "function_score": {
      "query": {"match": {"_all": "severed"}},
      "script_score": {
        "script": "_score * log(doc[\'likes\'].value + doc[\'views\'].value + 2)"
      }
    }
  }
}';


$response = $curl->get($url, $val);
echo $response;
*/













$url = $url."/stay/stayType/_search?";
		//$url = $url."/stay/_search";
		//$query = $url."/doo/dooType/1";
		//$query = $url."/photo/_optimize";
		//$query = $url."/_optimize";
/*
		$query['query']['function_score']['query']['match']['name'] = '강남';
		$query['query']['function_score']['functions'][0]['boost_factor'] = 1.5;
		$query['query']['function_score']['functions'][0]['filter']['term']['address'] = '논현';
		//$query['query']['function_score']['boost_mode'] = '(multiply)';

*/

/*

"function_score": {
    "(query|filter)": {},
    "boost": "boost for the whole query",
    "FUNCTION": {},  
    "boost_mode":"(multiply|replace|...)"
}
*/



/*
$query['from'] = 0;
$query['size'] = 2;
$query['query']['query_string']['fields'][] = "name";
$query['query']['query_string']['fields'][] = "address";
$query['query']['query_string']['query'] = "부산";
$query['sort'][0]['_score'] = "asc";
*/


/*

$url =$url."/searchtube/_search";
$val = '{
  "query": {
    "function_score": {
      "query": {"match": {"_all": "severed"}},
      "script_score": {
        "script": "_score * log(doc[\'likes\'].value + doc[\'views\'].value + 2)"
      }
    }
  }
}';
*/



$query['query']['function_score']['query']['query_string']['query']= 'name:H';
$query['query']['function_score']['functions'][0]['script_score']['script']= "_score * doc.['reviewsCount'].value";


//$query['query']['function_score']['boost_mode'] = 'multiply';
//$query['query']['function_score']['functions'][0]['script_score']['script']= "2";
//$query['query']['function_score']['term']= '부산';
//$query['query']['function_score']['filter']= '부산';
//$query['query']['function_score']['functions'][0]['script_score']['script'] = "_score * doc.['reviewsCount'].value";

//$query['query']['function_score']['functions'][0]['script_score']['script'] = "_score * log(doc['reviewsCount'].value + doc['reviewsCount'].value)";
//$query['query']['function_score']['functions'][0]['script_score']['params']['qualityScore'] = 15;
//$query['query']['function_score']['functions'][1]['filter']['FUNCTION'] = '';

/*
$query['query']['function_score']['boost_mode'] = 'multiply';
$query['query']['function_score']['score_mode'] = 'multiply';
$query['query']['function_score']['max_boost'] = 10000;
$query['query']['function_score']['min_score'] = 1;
*/

//$query['query']['function_score']['boost'] = '';

/*
$query['query']['function_score']['filter']['term']['name'] = '부산';
$query['query']['function_score']['functions'][0]['filter']['term']['address'] = '부산';
$query['query']['function_score']['functions'][0]['filter']['weight'] = 10;

$query['query']['function_score']['functions'][1]['filter']['term']['address'] = '자갈치';
$query['query']['function_score']['functions'][1]['filter']['weight'] = 1;
$query['query']['function_score']['score_mode'] = 'sum';
*/






//$query['query']['constant_score']['query']['term']['name']= '부산';



$json_query =  json_encode($query);





		//$response = $curl->post($query);
$response = $curl->post($url, $json_query);


echo $response;
exit;
$resultArray = json_decode($response);


print_r($resultArray);




/*
		foreach( $resultArray->hits->hits as $value):

		echo "score : ". $value->_score." | ";
		echo "name : ". $value->_source->name." | ";
		echo "address : ". $value->_source->address." | ";


		echo "\n";


		print_r($value);
		exit;



		endforeach;


*/


	}




}

