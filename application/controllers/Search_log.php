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
class Search_log extends ES_Controller {

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }


    public function boo() {
		$data['index'] = 'stay';
		$data['keyword'] = '강남';
		$this->load->model('m_log','mlog', true);

		$check = $this->mlog->select_logdb($data);
		if($check->cnt > 0){		
			$type= 'update';
			$data['seq'] = $check->row->seq;
 		} else {
			$type= 'insert';
		}
		$data = $this->mlog->upsert_logdb($data, $type);	
	}




}
