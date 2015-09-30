<?

class M_search extends CI_Model {


	public function checkKeyword($keyword){
		$query =  $this->db->where('keyword', $keyword)->get('keyword')->num_rows();
		return $query;
	}

}


?>