<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
class Auto extends REST_Controller {

	private $indexType;
	private $searchData;

    public function __construct() {
        parent::__construct();        
		$this->indexType			= $this->uri->segment(2, 0);
		$this->searchData			=  $this->input->get();
   		$this->load->library('autocomplate');
	}

	public function index_get()
	{	

	}
	public function stay_get()
	{
		header('Content-Type: application/json');
		$_GET['response'] = $this->autocomplate->prefix($this->indexType, $this->searchData);
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
	public function cast_get()
	{
		header('Content-Type: application/json');
		$_GET['response'] = $this->autocomplate->prefix($this->indexType, $this->searchData);
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
	public function photo_get()
	{
		header('Content-Type: application/json');
		$_GET['response'] = $this->autocomplate->prefix($this->indexType, $this->searchData);
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
	public function travel_get()
	{
		header('Content-Type: application/json');
		$_GET['response'] = $this->autocomplate->prefix($this->indexType, $this->searchData);
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
}
