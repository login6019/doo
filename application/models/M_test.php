<?

class M_test extends CI_Model {


	public function test_logdb(){


        $logDB = $this->load->database('log', TRUE);
		$query = "select * from searchLog";
		$result = $logDB->query($query)->result_array();

		return $result; 

	}



	public function insert_logdb($data){



        $logDB = $this->load->database('log', TRUE);

		$logDB->set('index', $data['index']);
		$logDB->set('keyword', $data['keyword']);
		$logDB->set('count', 'count + 1', false);
		$logDB->set('date', 'now()', false);
		$result = $logDB->insert('searchLog');



		return $result; 

	}


}


?>