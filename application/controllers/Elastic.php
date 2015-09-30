<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
class Elastic extends REST_Controller {

	private $searchKeyword;
	private $indexType;
	private $searchData;

    public function __construct() {
        parent::__construct();        
		$this->load->model('m_manage','manage', true);
		$this->indexType		= $this->uri->segment(2, 0);
		$this->searchData		=  $this->input->get();
   		$this->load->library('data_filter');	
		$this->logWrite();
	}
	public function index_get()
	{	

	}
/************************************************ Photo ************************************************************************/

#DELETE method - photo /*
	public function photo_delete()
	{
		$_DELETE['response'] =  $this->elastica->deleteData($this->delete('id'), $this->indexType);
		echo json_encode($_DELETE, JSON_UNESCAPED_UNICODE);
	}
#DELETE method - photo */

#GET method - photo /*
	public function photo_get()
	{
		header('Content-Type: application/json');
		$searchOn  = $this->data_filter->checkAdultKeyWord($this->searchData);
	
		if($searchOn == 'on'){
			$_GET['response'] =  $this->elastica->getData($this->indexType, $this->searchData);
		} else {
			$_GET['response'] = "NO_ADULT_AUTH";
		}
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
#GET method - photo */

#PUT method - photo /*
	public function photo_put()
	{
		$data = $this->put('putData');
		$_PUT['response'] = $this->elastica->putData($this->put('id'), $this->indexType, $data);
		echo json_encode($_PUT, JSON_UNESCAPED_UNICODE);
	}
#PUT method - photo */

#POST method - photo /*
	public function photo_post()
	{
		$data							= $this->manage->getPhotoData($this->post('id')); 
		$_POST['response']	= $this->elastica->postData($this->post('id'), $this->indexType, $data);
		echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
	}
#POST method - photo */

/***************************************************** Stay *****************************************************************/

#DELETE method - stay /*
	public function stay_delete()
	{		
		$_DELETE['response'] =  $this->elastica->deleteData($this->delete('id'), $this->indexType);
		echo json_encode($_DELETE, JSON_UNESCAPED_UNICODE);
	}
#DELETE method - stay */

#GET method - stay /*
	public function stay_get()
	{
		header('Content-Type: application/json');
		if(@$this->searchData['QueryType'] == "direct"){
			$_GET['response'] =  $this->elastica_get->getDirectQuery($this->indexType, $this->searchData);
		} else if(@$this->searchData['QueryType'] == "event"){
			$_GET['response'] =  $this->elastica_get->getEventQuery($this->indexType, $this->searchData);
		} else if(@$this->searchData['QueryType'] == "adverts"){
			$_GET['response'] =  $this->elastica_get->getAdvertsQuery($this->indexType, $this->searchData);
		} else {
			$searchOn  = $this->data_filter->checkAdultKeyWord($this->searchData);
			if($searchOn == 'on'){
				if(@$this->searchData['searchType'] == "detail"){
					$_GET['response'] =  $this->elastica_get->getDetailData($this->indexType, $this->searchData);
				} else {
					$_GET['response'] =  $this->elastica->getData($this->indexType, $this->searchData);
				}
			} else {
				$_GET['response'] = "NO_ADULT_AUTH";
			}
		}
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
#GET method - stay */

#PUT method - stay /*
	public function stay_put()
	{
		$data = $this->put('putData');
		$_PUT['response'] = $this->elastica->putData($this->put('id'), $this->indexType, $data);
		echo json_encode($_PUT, JSON_UNESCAPED_UNICODE);

	}
#PUT method - stay */

#POST method - stay /*
	public function stay_post()
	{
		$data							= $this->manage->getStayData($this->post('id')); 
		$_POST['response']	= $this->elastica->postData($this->post('id'), $this->indexType, $data);
		echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
	}
#POST method - stay */

/******************************************************* Cast *****************************************************************/

#DELETE method - cast /*
	public function cast_delete()
	{
		$_DELETE['response'] =  $this->elastica->deleteData($this->delete('id'), $this->indexType);
		echo json_encode($_DELETE, JSON_UNESCAPED_UNICODE);
	}
#DELETE method - cast */

#GET method - cast /*
	public function cast_get()
	{
		header('Content-Type: application/json');
		$searchOn  = $this->data_filter->checkAdultKeyWord($this->searchData);
		if($searchOn == 'on'){
			$_GET['response'] =  $this->elastica->getData($this->indexType, $this->searchData);
		} else {
			$_GET['response'] = "NO_ADULT_AUTH";
		}
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
#GET method - cast */

#PUT method - cast /*
	public function cast_put()
	{
		$data = $this->put('putData');
		$_PUT['response'] = $this->elastica->putData($this->put('id'), $this->indexType, $data);
		echo json_encode($_PUT, JSON_UNESCAPED_UNICODE);
	}
#PUT method - cast */

#POST method - cast /*
	public function cast_post()
	{
		$data							= $this->manage->getCastData($this->post('id')); 
		$_POST['response']	= $this->elastica->postData($this->post('id'), $this->indexType, $data);
		echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
	}
#POST method - cast */

/******************************************************** Travel ****************************************************************/

#DELETE method - travel /*
	public function travel_delete()
	{
		$_DELETE['response'] =  $this->elastica->deleteData($this->delete('id'), $this->indexType);
		echo json_encode($_DELETE, JSON_UNESCAPED_UNICODE);
	}
#DELETE method - travel */

#GET method - travel /*
	public function travel_get()
	{
		header('Content-Type: application/json');
		$searchOn  = $this->data_filter->checkAdultKeyWord($this->searchData);		
		if($searchOn == 'on'){
			if(@$this->searchData['searchType'] == "detail"){
				$_GET['response'] =  $this->elastica_get->getDetailData($this->indexType, $this->searchData);

			} else {
				$_GET['response'] =  $this->elastica->getData($this->indexType, $this->searchData);
			}
		} else {
			$_GET['response'] = "NO_ADULT_AUTH";
		}		
		echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
	}
#GET method - travel */

#PUT method - travel /*
	public function travel_put()
	{
		$data = $this->put('putData');
		$_PUT['response'] = $this->elastica->putData($this->put('id'), $this->indexType, $data);
		echo json_encode($_PUT, JSON_UNESCAPED_UNICODE);
	}
#PUT method - travel */

#POST method - travel /*
	public function travel_post()
	{
		$data							= $this->manage->getTravelData($this->post('id')); 
		$_POST['response']	= $this->elastica->postData($this->post('id'), $this->indexType, $data);
		echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
	}
#POST method - travel */

/************************************************************************************************************************/
	/*
	public function logWrite(){ //검색엔진에 로그 바로 쌓기
		//$check = $this->elastica->indexLogWrite($this->indexType, $this->searchData['searchKeyword']);
	}
	*/

	public function logWrite(){
		try{
			$data['index']		= $this->indexType;
			if(!empty($this->searchData['searchKeyword']) && $this->searchData['searchKeyword'] !=""){
				$data['keyword']	= $this->searchData['searchKeyword'];
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
		} catch (Exception $e) {
		
		}
	}

}
