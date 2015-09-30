<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 광고와 관련된 모델
 */
class Adverts extends CI_Controller {
	private $currentCollection;

	function __construct(){
		parent::__construct();

		//현재 컨트롤러명
		$this->currentCollection = 'adverts';
		
		//다음 콜렉션이 있는경우
		$collection = $this->api_util->checkCollection( $this->currentCollection, $this->uri->uri_to_assoc(2) );
		if( !$collection ){
			if( !defined('_GATEWAY_') ) define('_GATEWAY_', TRUE);
		}

		//모델로드
		//$this->load->model('adverts_model');
	}

	function _remap(){
		$segments = $this->uri->uri_to_assoc(2);
		$this->index($segments[$this->currentCollection], $segments);
	}

	//분배
	function index( $fnc, $segments='' ){
		//호출할 method를 정의
		if( @isset($segments[$this->currentCollection]) && @$segments[$this->currentCollection] ) $fnc = $segments[$this->currentCollection];
		else $fnc = 'lists';

		//다음 collections이 있는 경우
		$collection = $this->api_util->checkCollection( $this->currentCollection, $segments );
		if( !$collection ){
			//호출할 method를 정의
			$method = '_'.$this->api_util->method.'_'.$fnc;

			//method 가 존재 하는지 체크
			if( method_exists($this, $method) ) return $this->{$method}($segments);
			else $this->api_util->checkAllowMethod( $this, $fnc );
		}else Widget::run(API_VERSION.'/_'.$this->currentCollection.'/'.$collection, array('detail',$segments));
	}

	//광고목록을 가져옴
	private function _get_lists( $segments='' ){
		//초기값
		$lng							= $this->api_util->getParam('lng', LNG);
		$lat							= $this->api_util->getParam('lat', LAT);
		$advertsPosition	= $this->api_util->getParam('advertsPosition','MAIN');
		$advertsPosition	= isset($segments['advertsPosition']) ? $segments['advertsPosition'] : $advertsPosition;
		$advertsPosition	= strtoupper($advertsPosition);

		//풍선쿠폰 구분
		$this->load->config('_balloon');
		$cfBalloon = $this->config->item('balloon');

		//프랜차이즈 구분
		$this->load->config('_franchise');
		$cfFranchise = $this->config->item('franchise');

		//제휴형태
		$this->load->config('_relation');
		$cfRelation = $this->config->item('relation');

		// 검색엔진에서 1차로 가져오고 값을 못 가져오면 rdbms 에서 가져옴
		$result = $this->_get_lists_elasticsearch( $segments );
		if( $result === FALSE ) $result = $this->_get_lists_rdbm( $segments );

		//전체개수
		$ret['counts'] = $result['counts'];

		//목록
		if( $ret['counts'] ){
			if( isset($result['lists']) ){
				foreach( $result['lists'] as $row ){
					$obj					=& $ret['lists'][];
					$obj					= new stdClass;
					$obj->key			= (string)$row['miIdx'];
					$obj->type		= strtolower($advertsPosition).'Adverts';
					$obj->title		= $row['aaTitle'];

					if( $advertsPosition == 'BAND' && $advertsPosition == 'LARGE' )	//띠배너는 썸네일이 아닌 원본을 보여줌
						$obj->thumb		= IMG1_PATH . '/motelAppAdverts/' . $row['aaImage'];
					else if( $advertsPosition == 'AROUND' )	// 내주변 광고는 별도 이미지 등록없이 업체 사진으로 대체
						$obj->thumb		= $row['miFileName2'];
					else	// 그 외엔 별도로 등록
						$obj->thumb		= IMG1_PATH . '/motelAppAdverts/640/' . $row['aaImage'];

					// 테스트용 값
					$obj->thumb2 = $obj->thumb;

					//띠 광고의 경우 모텔의 이름과 거리가 필요없음
					if( $row['aaPosition'] != 'BAND' && $row['aaPosition'] != 'LARGE' ){
						$obj->name							= strip_tags($row['miCompanyName']);

						//제휴형태
						if( isset($cfRelation[$row['miRelation']][0]) ) $obj->relation	= $cfRelation[$row['miRelation']][0];
						else $obj->relation	= '';

						//프랜차이즈
						if( isset($cfFranchise[$row['YAJA_SYSTEM']]) ) $obj->franchise	= $cfFranchise[$row['YAJA_SYSTEM']];
						else $obj->franchise	= '';

						//이용후기 개수에 따라 평점을 계산함
						if( $row['miValuationCount'] ){
							$obj->reviewStar				= (string)@round(($row['miValuation']/$row['miValuationCount'])/2);
							$obj->reviewCounts			= (string)(int)($row['miValuationCount']/4);
							if( $obj->reviewStar > 5 ) $obj->reviewStar = '5';
						}else{
							$obj->reviewStar				= '0';
							$obj->reviewCounts			= '0';
						}

						$obj->hot								= 'Y';
						$obj->addr1							= $row['aSi'].' '.$row['aGugun'];
						$obj->addr2							= $row['miAddress'];

						//풍선
						if( isset($cfBalloon[$row['mCheck4']][0]) ) $obj->coupon	= $cfBalloon[$row['mCheck4']][0];
						else $obj->coupon	= '';

						$obj->newIcon						= ( $row['miDate'] >= date('Y-m-d',strtotime('-60 days')) ) ? 'Y' : 'N';
						$obj->event							= ( ($row['miUseMenu5'] == 'Y') || ($row['miUseMenu30'] == 'Y') ) ? 'Y' : 'N';
						$obj->freeTicketEvent		= isset($ftickets[$row['miIdx']]) ? 'Y' : 'N';
						$obj->distance					= !isset( $row['distance'] ) ? distance(get_distance($lng,$lat,$row['lng'],$row['lat'])) : distance($row['distance']);


					//띠 광고 인 경우에는 링크주소가 존재
					}else{ 
						//대형배너일 경우 정렬순서가 존재
						if( $row['aaPosition'] == 'LARGE' ) $obj->order = $row['aaOrder'];

						$obj->link				= 'http://'.$row['aaLink'];
					}

					$obj->colorRed		= (string)$row['aaColorRed'];
					$obj->colorGreen	= (string)$row['aaColorGreen'];
					$obj->colorBlue		= (string)$row['aaColorBlue'];
				}
			}
		}

		if( isset($segments[$this->currentCollection]) ) return $this->api_util->send(200, $ret);
		else return $ret;
	}


	//광고목록을 검색엔진을 통해서 가져옴
	private function _get_lists_elasticsearch( $segments='' ){
		//검색엔진을 안 쓰게 설정된 경우
		if( !SEARCH_ENGINE ) return FALSE;

		//초기화
		$ret							= array();
		$result						= array();
		$where						= array();
		$whereOr					= array();
		$groupBy					= array();
		$page							= $this->api_util->getParam('page', 1);
		$page							= $page ? $page : 1;
		$limit						= $this->api_util->getParam('limit', 5);
		$lng							= $this->api_util->getParam('lng', LNG);
		$lat							= $this->api_util->getParam('lat', LAT);
		$asgIdx						= $this->api_util->getParam('area');
		$agIdx						= $this->api_util->getParam('areaGroup');
		$distance					= $this->api_util->getParam('distance');
		$advertsPosition	= $this->api_util->getParam('advertsPosition','MAIN');
		$advertsPosition	= isset($segments['advertsPosition']) ? $segments['advertsPosition'] : $advertsPosition;
		$advertsPosition	= strtoupper($advertsPosition);
		$page							= isset($segments['page']) ? $segments['page'] : $page;
		$limit						= isset($segments['limit']) ? $segments['limit'] : $limit;
		$distance					= isset($segments['distance']) ? $segments['distance'] : $distance;
		$distance					= $distance ? $distance : '';
		$offset						= ($page-1)*$limit;

		//검색엔진 라이브러리
		$this->load->library('elasticsearch');

		//쿼리생성
		$searchParams['index']						= 'ad_motel';
		$searchParams['type']							= 'ad_motel_type';
		$searchParams['body']['timeout']	= '5s';
		$searchParams['body']['from']			= $offset; // offset
		$searchParams['body']['size']			= $limit; // 가져올 개수
		$where														=& $searchParams['body']['query']['filtered']['query']['bool']['must'];
		$sort															=& $searchParams['body']['sort'];

		//검색조건(광고 위치)
		if( $advertsPosition ){
			$obj												=& $where[];
			$obj['match']['aaPosition']	= $advertsPosition;
		}

		//검색조건(거리)
		if( $distance ){
			$filter																			=& $searchParams['body']['filter'];
			$filter['geo_distance']['distance']					= $distance.'km';
			$filter['geo_distance']['location']['lat']	= $lat;
			$filter['geo_distance']['location']['lon']	= $lng;
		}

		//검색조건(지역)
		if( $advertsPosition == 'AREA' || $asgIdx ){
			$this->load->model('areas_model');
			$result = $this->areas_model->getAddress(explode(',',$asgIdx));
			$aSi			= '';
			$aGugun		= '';

			foreach($result['query']->result() as $row){
				$aSi .= $row->aSi.' ';
				$aGugun .= $row->aGugun.' ';
			}

			//시 검색
			if( $aSi ){
				$obj										=& $where[];
				$obj['match']['aSi']		= $aSi;
			}
			
			//구/군검색
			if( $aGugun ){
				$obj											=& $where[];
				$obj['match']['aGugun']		= $aGugun;
			}
		}

		//거리순 정렬
		$obj																			=& $sort[];
		$obj['_geo_distance']['location']['lat']	= $lat;
		$obj['_geo_distance']['location']['lon']	= $lng;
		$obj['_geo_distance']['order']						= "ASC";
		$obj['_geo_distance']['unit']							= "km";

		//목록을 가져옴
		$result = $this->elasticsearch->advancedquery($searchParams);
		if( $result['code'] != '200' ) return FALSE;

		//전체개수
		$ret['counts'] = '0';

		if( isset($result) && !isset($result['error']) ){
			$ret['counts'] = (string)$result['res']['hits']['total'];

			//값을 가져옴
			foreach( $result['res']['hits']['hits'] as $row ){
				$ret['lists'][] = $row['_source'];
			}
		}


		return $ret;
	}


	//광고목록을 RDBM(102)에서 가져옴
	private function _get_lists_rdbm( $segments='' ){
		//초기화
		$ret							= array();
		$result						= array();
		$where						= array();
		$whereOr					= array();
		$groupBy					= array();
		$page							= $this->api_util->getParam('page', 1);
		$page							= $page ? $page : 1;
		$limit						= $this->api_util->getParam('limit', 5);
		$lng							= $this->api_util->getParam('lng', LNG);
		$lat							= $this->api_util->getParam('lat', LAT);
		$asgIdx						= $this->api_util->getParam('area');
		$agIdx						= $this->api_util->getParam('areaGroup');
		$distance					= $this->api_util->getParam('distance');
		$advertsPosition	= $this->api_util->getParam('advertsPosition','MAIN');
		$advertsPosition	= isset($segments['advertsPosition']) ? $segments['advertsPosition'] : $advertsPosition;
		$advertsPosition	= strtoupper($advertsPosition);
		$page							= isset($segments['page']) ? $segments['page'] : $page;
		$limit						= isset($segments['limit']) ? $segments['limit'] : $limit;
		$distance					= isset($segments['distance']) ? $segments['distance'] : $distance;
		$distance					= $distance ? $distance : '';
		$offset						= ($page-1)*$limit;

		//db접속
		$this->load->model('adverts_model');

		//검색조건
		$where['aaPosition'] = $advertsPosition;

		//거리제한
		if( $distance )
			$groupBy = 'distance HAVING distance <= ' . $distance;

		//지역 2차분류로 검색
		if( $asgIdx ){
			$whereOr[] = array(
				'ADDRS.asgIdx IN ' => '('.$asgIdx.')'
			);
		}

		//지역 1차분류로 검색
		if( $agIdx ){
			$whereOr[] = array(
				'ADDRS.agIdx' => $agIdx
			);
		}

		//결과를 가져옴
		$result = $this->adverts_model->getRows( $offset, $limit, $where, $whereOr, $groupBy, $lat, $lng );

		//전체개수
		$ret['counts'] = $result['counts'];

		//값을 가져옴
		foreach( $result['query']->result_array() as $row ){
			$ret['lists'][] = $row;
		}


		return $ret;
	}
}