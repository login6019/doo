<?php defined('BASEPATH') or exit('No direct script access allowed');
use Elastica\Client;

 class ES_Controller extends CI_Controller
{
	private $server;
	private $httpHost;
	private $indexName; 
	private $typeName; 

    public function __construct()
	{
        parent::__construct();
		$this->server = THIS_SERVER;
		$this->httpHost = $_SERVER['HTTP_HOST'];
		$this->indexName	= $this->input->get_post('indexName', true); 
		$this->typeName	= $this->input->get_post('typeName', true); 
	}

	public function index(){

	}
	
	public function _indexName(){
		return $this->indexName;	
	}

	public function _typeName(){
		return $this->typeName;	
	}

	public function client(){
		$client = new Client(array(
			'servers' => array(
				array('host' => HOST_ESC_001, 'port' => PORT_ES_001),
				array('host' => HOST_ESC_002, 'port' => PORT_ES_002)				
			)
		));
		return $client;
	}

	public function _view($path, $data=null, $type=null){
		$data['zone'] = $this->server;
		$data['httpHost'] = $this->httpHost; 
		$this->load->view('include/'.$type.'/head', $data);
		$this->load->view($path, $data);
		$this->load->view('include/'.$type.'/foot', $data);	
	}
}