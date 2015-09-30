<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#elastic search test server
//http://211.253.8.108:9200/_plugin/head/   
require_once DOCUMENT_ROOT.'/elastica/vendor/autoload.php';
class Manage extends ES_Controller {
	private $login;
    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
		$this->login = $this->input->cookie('login', true);
		if(empty($this->login) || $this->login != "BBONGDOO" ){
			header('Location: /login');		
		}
    }
	public function index()
	{	
		$this->_view("manage/v_index");
	}
	public function create()
	{
		$this->_view("manage/v_create");
	}
	public function delete()
	{
		$this->_view("manage/v_delete");
	}
	public function setting()
	{
		$data['index'] = $this->input->get("index"); 
		$this->_view("manage/v_setting", $data);
	}
	public function insert()
	{
		$this->_view("manage/v_insert");
	}
	public function photoInsert()
	{
		$this->_view("manage/v_photo_insert");
	}
	public function castInsert()
	{
		$this->_view("manage/v_cast_insert");
	}
	public function stayInsert()
	{
		$this->_view("manage/v_stay_insert");
	}
	public function travelInsert()
	{
		$this->_view("manage/v_travel_insert");
	}
	public function data()
	{
		$this->_view("manage/v_data");
	}
	public function dooInsert()
	{
		$this->_view("manage/v_insert");
	}

}

