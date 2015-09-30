<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Work 	 {



	public function index(){
	




		$getData = $this->data_filter->checkAdultKeyWord();
		echo $getData;

	
	}	

}
