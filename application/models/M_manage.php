<?
defined('BASEPATH') OR exit('No direct script access allowed');

class M_manage extends ES_Model {
	function __construct()
    {
        parent::__construct();
    }
#Travel 정보,  리스트 
	public function getTravelList(){
		$query = $this->getTravelQuery();
		$query = $this->db->query($query)->result_array();
		return $query;
	}
	public function getTravelData($id){
		$query = $this->getTravelQuery($id);
		$query = $this->db->query($query)->row_array();
		return $query;
	}
#Cast 정보 ,리스트 
	public function getCastList(){
		$query = $this->getCastQuery();
		$query = $this->db->query($query)->result_array();
		return $query;
	}
	public function getCastData($id){
		$query = $this->getCastQuery($id);
		$query = $this->db->query($query)->row_array();
		return $query;
	}
#Photo 정보 ,리스트 
	public function getPhotoList(){
		$query = $this->getPhotoQuery();
		$query = $this->db->query($query)->result_array();
		return $query;
	}
	public function getPhotoData($id){
		$query = $this->getPhotoQuery($id);
		$query = $this->db->query($query)->row_array();
		return $query;
	}
#Stay 정보 ,리스트 
	public function getStayList(){
		$query = $this->getStayQuery();
		$query = $this->db->query($query)->result_array();
		return $query;
	}
	public function getStayData($id){
		$query = $this->getStayQuery($id);
		$query = $this->db->query($query)->row_array();
		return $query;
	}	

}


?>