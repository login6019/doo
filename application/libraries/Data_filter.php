<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Data_filter 
{

	private $CI;

   public function __construct()
    {
		$this->CI =& get_instance();
		$this->CI->load->model('m_search', 'search', true);
    }

	public function checkAdultKeyWord($searchData){
		if(empty($searchData['searchKeyword'])){
			$searchOn = 'on';
		} else {	
			$data = $this->CI->search->checkKeyword($searchData['searchKeyword']);		
			if($data > 0){		
				if(@$searchData['adultYN'] == "Y"){
					$searchOn = 'on';
				} else {
					$searchOn = 'off';				
				}		
			} else {
				$searchOn = 'on';
			}		
		}		
		return $searchOn;	
	}

}



