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
class Logprocess extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		date_default_timezone_set('asia/seoul');
		$this->load->model('m_log','mlog', true);
    }

    public function index() {
    
	}

    public function insertEngine(){ //검색로그 통계 넣어주기

		$hangle	  = new Hangle_cho();
		$arrIndex = array('stay', 'travel', 'cast', 'photo');
		$client	= $this->client();

		foreach($arrIndex as $indexType):
			unset($logData);	
			unset($index);	
			unset($type);	

			$logData = $this->mlog->getLogData($indexType);

				$index	= $client->getIndex($indexType."log");
				$type		= $index->getType($indexType."logType");

				$id=1;
				foreach($logData as $value):		
					$val = array();		
					$val['keyword']	 = $value->keyword;
					$val['date']		 = $value->date;
					$val['count']		 = $value->sumCount;
					$val['hangle']	 = $hangle->hangul_split($value->keyword);

					$document = new Document();
					try{
						$data = $type->getDocument($id)->hasId();
						$type->updateDocument(new Document($id, $val));										
					} catch(Exception $e){	
						$type->addDocument(new Document($id, $val));
					}

					$id++;
				endforeach;
		endforeach;
	}
}
