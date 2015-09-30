<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#elastic search test server
//http://211.253.8.108:9200/_plugin/head/   
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
class Login extends ES_Controller {
    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
    }
	public function index()
	{	
		$this->login();
	}
	
	public function login()
	{	
		$this->load->view("v_login");
	}

}

