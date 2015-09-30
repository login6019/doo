<?

class M_log extends CI_Model {

	private $logDB;

    public function __construct() {
        parent::__construct();        
		date_default_timezone_set('asia/seoul');
        $this->logDB = $this->load->database('log', TRUE);
    }

	public function select_logdb($data){
		$nowDate = date('Y-m-d');
		$query = $this->logDB->select('*')->from('searchLog')->where('index', $data['index'])->where('keyword', $data['keyword'])->where('date', $nowDate)->get();
		$returnData = new stdClass;
		if($query->num_rows() > 0){
			$returnData->row = $query->row();	
		} 
		$returnData->cnt = $query->num_rows();				
		return $returnData; 
	}

	public function upsert_logdb($data, $type){
		if($type == 'insert'){
			$this->logDB->set('index', $data['index']);
			$this->logDB->set('keyword', $data['keyword']);
			$this->logDB->set('count', 1);		
			$this->logDB->set('date', 'now()', false);
		} else {		
			$this->logDB->set('count', 'count + 1', false);
			$this->logDB->where('seq', $data['seq']);
		} 
		$result = $this->logDB->$type('searchLog');
		return $result; 
	}

	public function getLogData($index){
		$query = "SELECT `index`, keyword, `date`, SUM(`count`) AS sumCount FROM searchLog WHERE `date` >= DATE_ADD( NOW(), INTERVAL -1 WEEK )  AND `index` = '".$index."' GROUP BY keyword";
		return $this->logDB->query($query)->result();	
	}

}

?>