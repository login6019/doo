<?
defined('BASEPATH') OR exit('No direct script access allowed');

class ES_Model extends CI_Model {

	private $query;
	private $addWhere;

    public function __construct() {
        parent::__construct();        
		$this->addWhere ="";
	}

/*Query Area */
	public function getTravelQuery($id= null)
	{
		if($id){
			$this->addWhere = "AND 	p.no='".$id."' "; 		
		}


/*
		$this->query = "SELECT 	p.no AS id,  
			p.*, 
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(searchKeyword.depthText , ' >', ''), '여행테마속성 ','')  SEPARATOR ' '),'') AS searchKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(regionKeyword.depthText , ' >', ''),'여행지역속성 ','')  SEPARATOR ' '),'') AS regionKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(characterKeyword.depthText , ' >', ''),'특징속성 ','')  SEPARATOR ' '),'') AS characterKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  searchKeyword.comment  SEPARATOR ' , '),'') AS normalProperty,
			IFNULL(GROUP_CONCAT(DISTINCT  regionKeyword.comment  SEPARATOR ' , '),'') AS regionProperty,
			CONCAT(photo.domain,'/', photo.path,'/1280/', photo.fileName) AS thumbNail 
FROM 	place p
		LEFT JOIN propertyMap m
		ON  (m.serviceType = 3 AND p.no =  m.contentsNo AND p.contentsType = m.contentsType)
		LEFT JOIN property searchKeyword
		ON (m.propertyNo = searchKeyword.no and m.propertyType = 485)
		LEFT JOIN property regionKeyword
		ON (m.propertyNo = regionKeyword.no and m.propertyType = 116)
		LEFT JOIN property characterKeyword
		ON (m.propertyNo = characterKeyword.no and m.propertyType = 5)
		LEFT JOIN photoMap photoMap
		ON (photoMap.serviceType = 3 AND p.no = photoMap.contentsNo and photoMap.category = 0 AND	photoMap.status != -1)
	 	LEFT JOIN photo photo
		ON (photoMap.photoNo = photo.no)
WHERE	p.serviceType = 3 AND
		p.published =1 
		".$this->addWhere." 
		GROUP BY p.no";
		return $this->query;
*/



		$this->query = "SELECT 	p.no AS id,  
			p.*, 
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(searchKeyword.depthText , ' >', ''), '여행테마속성 ','')  SEPARATOR ' '),'') AS searchKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(regionKeyword.depthText , ' >', ''),'여행지역속성 ','')  SEPARATOR ' '),'') AS regionKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  REPLACE(REPLACE(characterKeyword.depthText , ' >', ''),'특징속성 ','')  SEPARATOR ' '),'') AS characterKeyword,
			IFNULL(GROUP_CONCAT(DISTINCT  searchKeyword.comment  SEPARATOR ' , '),'')  AS normalProperty,
			IFNULL(GROUP_CONCAT(DISTINCT  regionKeyword.comment  SEPARATOR ' , '),'')  AS regionProperty,				
			IFNULL(     CONCAT(photo2.domain,'/', photo2.path,'/1280/', photo2.fileName),  CONCAT(photo.domain,'/', photo.path,'/1280/', photo.fileName)  )  AS thumbNail 				
FROM 	place p
		LEFT JOIN propertyMap m
		ON  (m.serviceType = 3 AND p.no =  m.contentsNo AND p.contentsType = m.contentsType)
		LEFT JOIN property searchKeyword
		ON (m.propertyNo = searchKeyword.no AND m.propertyType = 485)
		LEFT JOIN property regionKeyword
		ON (m.propertyNo = regionKeyword.no AND m.propertyType = 116)
		LEFT JOIN property characterKeyword
		ON (m.propertyNo = characterKeyword.no AND m.propertyType = 5)
		LEFT JOIN photoMap photoMap
		ON (photoMap.serviceType = 3 AND p.no = photoMap.contentsNo AND photoMap.category = 0 AND	photoMap.status != -1)
	 	LEFT JOIN photo photo
		ON (photoMap.photoNo = photo.no)
		LEFT JOIN photoMap photoMap2
		ON (photoMap2.serviceType = 3 AND p.no = photoMap2.contentsNo AND photoMap2.category = 188 AND	photoMap2.status != -1)
	 	LEFT JOIN photo photo2
		ON (photoMap2.photoNo = photo2.no)
		
WHERE	p.serviceType = 3 AND
		p.published =1  		
		".$this->addWhere." 
		GROUP BY p.no";
		return $this->query;

	}


	public function getCastQuery($id= null)
	{
		if($id){
			$this->addWhere = "AND 	a.no='".$id."' "; 		
		}
		$this->query = "SELECT 	a.boardNo,
			a.no AS id ,
			a.title,
			a.content,
			a.readCount,
			a.likeCount, 
			a.registrantNickName,
			a.containMovie,
			p.publishDate,
			a.registrantNo, 
			IFNULL(GROUP_CONCAT( REPLACE(pro.depthText, '>', ',') SEPARATOR ', '),'') AS searchKeyword,
			CONCAT( photo.domain ,'/', photo.path, '/', photo.fileName )AS thumbNail,
		    GROUP_CONCAT( CONCAT( photo.domain ,'/', photo.path, '/1280/', photo.fileName ) SEPARATOR ' | ') AS thumbNails
FROM 	article a
		LEFT JOIN articlePublish p
		ON (a.no = p.articleNo)
		LEFT JOIN propertyMap m
		ON  (m.serviceType = 2 AND a.no =  m.contentsNo )
		LEFT JOIN property pro
		ON m.propertyNo = pro.no
		LEFT JOIN photoMap photoMap
		ON (photoMap.serviceType = 2 AND a.no = photoMap.contentsNo AND photoMap.category = 51 AND photoMap.status != -1)
		LEFT JOIN photo photo
		ON (photoMap.photoNo = photo.no)
WHERE a.boardNo= 1 AND 
		a.status = 1 AND 
		p.publishType = 2 AND 
		p.publishDate < NOW() 
		".$this->addWhere." 
		GROUP BY a.no";
		return $this->query;
	}

	public function getPhotoQuery($id= null)
	{
		if($id){
			$this->addWhere = "AND 	photo.no='".$id."' "; 		
		}


/*
			$this->query = "SELECT 	CONCAT(GROUP_CONCAT(DISTINCT REPLACE(  pro.comment, '>', ',') SEPARATOR ' , ' ),' , ', IFNULL(photo.comment,'')) AS property , 
			CONCAT(GROUP_CONCAT(REPLACE( pro.depthText, '>', ',') SEPARATOR ' , ' ),' , ', IFNULL(photo.comment,'')) AS searchKeyword , 
				photo.no AS id, 
				photo.likeCount,
				photo.width,
				photo.height,
				map.serviceType,
				map.contentsType,
				map.category, 
				map.contentsNo,
				map.no AS mapNo,
				photo.path,
			CONCAT(photo.domain, '/' , photo.path,  '/1280/', photo.fileName) AS thumbNail
			FROM 	propertyMap proMap
			LEFT JOIN
			property pro
			ON ( proMap.propertyNo= pro.no)		
			LEFT JOIN
			photo photo
			ON ( photo.no = proMap.contentsNo)
			LEFT JOIN 
			photoMap map 
			ON ( photo.no = map.photoNo AND map.status != -1 )
			WHERE 	proMap.section = 4 AND 
			proMap.category = 0 
			".$this->addWhere." 
			GROUP BY photo.no";

*/


	$this->query = "SELECT 	CONCAT(GROUP_CONCAT(DISTINCT REPLACE(  pro.comment, '>', ',') SEPARATOR ' , ' ),' , ', IFNULL(photo.comment,'')) AS property , 
			CONCAT(GROUP_CONCAT(REPLACE( pro.depthText, '>', ',') SEPARATOR ' , ' ),' , ', IFNULL(photo.comment,'')) AS searchKeyword , 
				photo.no AS id, 
				photo.likeCount,
				photo.width,
				photo.height,
				map.serviceType,
				map.contentsType,
				map.category, 
				map.contentsNo,
				map.no AS mapNo,
				photo.path,
			CONCAT(photo.domain, '/' , photo.path,  '/1280/', photo.fileName) AS thumbNail
			FROM 	propertyMap proMap
			LEFT JOIN
			property pro
			ON ( proMap.propertyNo= pro.no)		
			LEFT JOIN
			photo photo
			ON ( photo.no = proMap.contentsNo)
			LEFT JOIN 
			photoMap map 
			ON ( photo.no = map.photoNo AND map.status = 1 )
			WHERE 	proMap.section = 4 AND map.serviceType in (3) and proMap.category = 0 and proMap.serviceType in (3)
   			".$this->addWhere." 
			GROUP BY photo.no";
		return $this->query;
	}
		
	public function getStayQuery($id= null)
	{
		if($id){
			$this->addWhere = "AND 	P.no='".$id."' "; 		
		}


$this->query ="SELECT SQL_NO_CACHE
  P.no AS id,
  P.name,
  PE.oldPlaceNo,
  PE.oldAdminID,
  PE.contractType,
  PE.franchiseType,
  PE.adPrice,
  PE.order,
  PE.contractDate,
  CONCAT(P.address, ' ', P.addressDetail) AS address,
  P.addressDetail AS addressDetail,
  P.address2,
  IF(PN.no IS NOT NULL, 1, 0) AS nowTextEvent,
  IF(FTE.afteIdx IS NOT NULL, 1, 0) AS nowAppFreeTicketEvent,
  P.latitude,
  P.longitude,
  P.serviceType,
  P.contentsType,
  P.category,
  P.qualityScore,
  P.reviewsCount,
  P.readCount,
  P.selectedCount,
  P.recentWeekClickCount,
  IF(
    PE.publishType = 0,
    P.published,
    IF(
      PE.publishStartDate <= CURDATE() 
      AND PE.publishEndDate >= CURDATE(),
      1,
      0
    )
  ) AS published,
  GROUP_CONCAT(
    DISTINCT IF(
      PM.propertyType = 2 
      AND section = 1,
      CONCAT(
        (SELECT 
          SPP.comment 
        FROM
          property SPP 
        WHERE SPP.no = PP.parentNo 
        LIMIT 1),
        ' ',
        PP.comment
      ),
      ''
    ) SEPARATOR ' '
  ) AS areaKeyword,
    GROUP_CONCAT(
    DISTINCT IF(
      PM.propertyType = 2 
      AND section = 1,PP.no,'') SEPARATOR ' '
  ) AS areaKeys,
  GROUP_CONCAT(
    DISTINCT IF(
      PM.propertyType = 3 
      AND section = 1,
      PP.comment,
      ''
    ) SEPARATOR ' '
  ) AS themeKeyword,
  REPLACE(PK.keyword, '/', ',') AS searchKeyword,
  GROUP_CONCAT(
    REPLACE(PP.depthText, '>', ',') SEPARATOR ' ,'
  ) AS propertyKeyword,
  CONCAT(
    GROUP_CONCAT(
      REPLACE(RT.name, '>', ',') SEPARATOR ' , '
    ),
    GROUP_CONCAT(
      REPLACE(R.name, '>', ',') SEPARATOR ' , '
    )
  ) AS roomsKeyword,
  P.registerdDate,
 CONCAT(
    photo.domain,
    replace(photo.path,'/',''),'/',
    '640/',
    photo.fileName
  ) AS thumbNail ,

 AA1.aaIdx AS motelAppAdvertMain,
 AA1.aaImage AS motelAppAdvertMainImage,
 AA1.aaLink AS motelAppAdvertMainLink ,
 AA1.aaTitle AS motelAppAdvertMainTitle,
 AA1.aaColorRed AS motelAppAdvertMainColorR,
 AA1.aaColorGreen AS motelAppAdvertMainColorG,
 AA1.aaColorBlue AS motelAppAdvertMainColorB,

 AA2.aaIdx AS motelAppAdvertArea,
 AA2.aaImage AS motelAppAdvertAreaImage,
 AA2.aaLink AS motelAppAdvertAreaLink ,
 AA2.aaTitle AS motelAppAdvertAreaTitle,
 AA2.aaColorRed AS motelAppAdvertAreaColorR,
 AA2.aaColorGreen AS motelAppAdvertAreaColorG,
 AA2.aaColorBlue AS motelAppAdvertAreaColorB, 

 AA3.aaIdx AS motelAppAdvertAround,
 AA3.aaImage AS motelAppAdvertAroundImage,
 AA3.aaLink AS motelAppAdvertAroundLink ,
 AA3.aaTitle AS motelAppAdvertAroundTitle,
 AA3.aaColorRed AS motelAppAdvertAroundColorR,
 AA3.aaColorGreen AS motelAppAdvertAroundColorG,
 AA3.aaColorBlue AS motelAppAdvertAroundColorB, 

 AA4.aaIdx AS motelAppAdvertBand,
 AA4.aaImage AS motelAppAdvertBandImage,
 AA4.aaLink AS motelAppAdvertBandLink ,
 AA4.aaTitle AS motelAppAdvertBandTitle,
 AA4.aaColorRed AS motelAppAdvertBandColorR,
 AA4.aaColorGreen AS motelAppAdvertBandColorG,
 AA4.aaColorBlue AS motelAppAdvertBandColorB, 

 AA5.aaIdx AS motelAppAdvertLarge,
 AA5.aaImage AS motelAppAdvertLargeImage,
 AA5.aaLink AS motelAppAdvertLargeLink ,
 AA5.aaTitle AS motelAppAdvertLargeTitle,
 AA5.aaColorRed AS motelAppAdvertLargeColorR,
 AA5.aaColorGreen AS motelAppAdvertLargeColorG,
 AA5.aaColorBlue AS motelAppAdvertLargeColorB, 

SMTASE.aseIdx AS specialEvents,
SMTASE.aseTitle AS specialEventsTitle,
SMTASE.aseSubTitle AS specialEventsSubTitle,
SMTASE.aseImage AS specialEventsImage,
SMTASE.aseColorRed AS specialEventsColorR,
SMTASE.aseColorGreen AS specialEventsColorG,
SMTASE.aseColorBlue AS specialEventsColorB,
SMTASE.aseMotelCounts AS specialEventsMotelCounts,
IF(SMTASE.aseSortType='M',1,0) AS specialEventsSortType,
SMTASE.aseSort AS specialEventsSort

FROM
  place P 
  LEFT JOIN placeExtendInfo PE 
    ON (P.no = PE.placeNo) 
  LEFT JOIN placeKeyword PK 
    ON (P.no = PK.placeNo) 
  LEFT JOIN propertyMap PM 
    ON (
      PM.contentsType = P.contentsType 
      AND PM.contentsNo = P.no
    ) 
  LEFT JOIN property PP 
    ON (PP.no = PM.propertyNo) 
  LEFT JOIN roomType RT 
    ON (P.no = RT.placeNo) 
  LEFT JOIN room R 
    ON (RT.no = R.roomTypeNo) 
  LEFT JOIN photoMap photoMap 
    ON (
      photoMap.serviceType = 4 
      AND P.no = photoMap.contentsNo 
      AND photoMap.category = 42
      AND photoMap.status = 1
    ) 
  LEFT JOIN photo photo 
    ON (photoMap.photoNo = photo.no) 

  LEFT JOIN placeNotice PN
    ON (PN.placeNo = P.no AND PN.noticeType=1) 
    
  LEFT JOIN motelapp.motelAppFreeTicketEvents FTE
    ON (FTE.placeNo = P.no AND FTE.afteOpen = 1 AND FTE.afteStartDate <= CURDATE() AND FTE.afteEndDate >= CURDATE()) 

    LEFT JOIN motelapp.motelAppAdverts AA1
    ON (AA1.placeNo = P.no AND AA1.aaOpen='Y' AND AA1.aaPosition = 'MAIN') 
    
        LEFT JOIN motelapp.motelAppAdverts AA2
    ON (AA2.placeNo = P.no AND AA2.aaOpen='Y' AND AA2.aaPosition = 'AREA') 
    
        LEFT JOIN motelapp.motelAppAdverts AA3
    ON (AA3.placeNo = P.no AND AA3.aaOpen='Y' AND AA3.aaPosition = 'AROUND') 
    
        LEFT JOIN motelapp.motelAppAdverts AA4
    ON (AA4.placeNo = P.no AND AA4.aaOpen='Y' AND AA4.aaPosition = 'BAND') 
    
            LEFT JOIN motelapp.motelAppAdverts AA5
    ON (AA5.placeNo = P.no AND AA5.aaOpen='Y' AND AA5.aaPosition = 'LARGE') 
    
    LEFT JOIN motelapp.motelAppSpecialEventMotels SMTASEM
    ON (SMTASEM.placeNo = P.no) 
    
    LEFT JOIN motelapp.motelAppSpecialEvents SMTASE
    ON (SMTASE.aseIdx = SMTASEM.aseIdx AND SMTASE.aseOpen = 'Y') 
     
WHERE P.serviceType = '4' 
  AND P.published = 1 
".$this->addWhere." 
GROUP BY P.no";
	return $this->query;

	}
/*Query Area End*/



}
?>