SELECT 
  P.no AS id,
  P.name,
  PE.oldPlaceNo,
  PE.oldAdminID,
  PE.contractType,
  PE.franchiseType,
  PE.adPrice,
  CONCAT(P.address, ' ', P.addressDetail) AS address,
  P.address2,
  P.latitude,
  P.longitude,
  P.serviceType,
  P.contentsType,
  P.category,
  P.qualityScore,
  P.reviewsCount,
  P.readCount,
  P.selectedCount,
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
    '/',
    photo.path,
    '/1280/',
    photo.fileName
  ) AS thumbNail ,
  
 IF(AA1.aaPosition = 'MAIN',AA1.aaIdx,0) AS motelAppAdvertMain,
 IF(AA1.aaPosition = 'MAIN',AA1.aaImage,0) AS motelAppAdvertMainImage,
 IF(AA1.aaPosition = 'MAIN',AA1.aaLink,0) AS motelAppAdvertMainLink ,
 IF(AA1.aaPosition = 'MAIN',AA1.aaTitle,0) AS motelAppAdvertMainTitle,
 IF(AA1.aaPosition = 'MAIN',AA1.aaColorRed,0) AS motelAppAdvertMainColorR,
 IF(AA1.aaPosition = 'MAIN',AA1.aaColorGreen,0) AS motelAppAdvertMainColorG,
 IF(AA1.aaPosition = 'MAIN',AA1.aaColorBlue,0) AS motelAppAdvertMainColorB,
 IF(AA2.aaPosition = 'AREA',AA2.aaIdx,0) AS motelAppAdvertArea,
 IF(AA2.aaPosition = 'AREA',AA2.aaImage,0) AS motelAppAdvertAreaImage,
 IF(AA2.aaPosition = 'AREA',AA2.aaLink,0) AS motelAppAdvertAreaLink ,
 IF(AA2.aaPosition = 'AREA',AA2.aaTitle,0) AS motelAppAdvertAreaTitle,
 IF(AA2.aaPosition = 'AREA',AA2.aaColorRed,0) AS motelAppAdvertAreaColorR,
 IF(AA2.aaPosition = 'AREA',AA2.aaColorGreen,0) AS motelAppAdvertAreaColorG,
 IF(AA2.aaPosition = 'AREA',AA2.aaColorBlue,0) AS motelAppAdvertAreaColorB, 
 IF(AA3.aaPosition = 'AROUND',AA3.aaIdx,0) AS motelAppAdvertAround,
 IF(AA3.aaPosition = 'AROUND',AA3.aaImage,0) AS motelAppAdvertAroundImage,
 IF(AA3.aaPosition = 'AROUND',AA3.aaLink,0) AS motelAppAdvertAroundLink ,
 IF(AA3.aaPosition = 'AROUND',AA3.aaTitle,0) AS motelAppAdvertAroundTitle,
 IF(AA3.aaPosition = 'AROUND',AA3.aaColorRed,0) AS motelAppAdvertAroundColorR,
 IF(AA3.aaPosition = 'AROUND',AA3.aaColorGreen,0) AS motelAppAdvertAroundColorG,
 IF(AA3.aaPosition = 'AROUND',AA3.aaColorBlue,0) AS motelAppAdvertAroundColorB, 
 IF(AA4.aaPosition = 'BAND',AA4.aaIdx,0) AS motelAppAdvertBand,
 IF(AA4.aaPosition = 'BAND',AA4.aaImage,0) AS motelAppAdvertBandImage,
 IF(AA4.aaPosition = 'BAND',AA4.aaLink,0) AS motelAppAdvertBandLink ,
 IF(AA4.aaPosition = 'BAND',AA4.aaTitle,0) AS motelAppAdvertBandTitle,
 IF(AA4.aaPosition = 'BAND',AA4.aaColorRed,0) AS motelAppAdvertBandColorR,
 IF(AA4.aaPosition = 'BAND',AA4.aaColorGreen,0) AS motelAppAdvertBandColorG,
 IF(AA4.aaPosition = 'BAND',AA4.aaColorBlue,0) AS motelAppAdvertBandColorB, 
 IF(AA5.aaPosition = 'LARGE',AA5.aaIdx,0) AS motelAppAdvertLarge,
 IF(AA5.aaPosition = 'LARGE',AA5.aaImage,0) AS motelAppAdvertLargeImage,
 IF(AA5.aaPosition = 'LARGE',AA5.aaLink,0) AS motelAppAdvertLargeLink ,
 IF(AA5.aaPosition = 'LARGE',AA5.aaTitle,0) AS motelAppAdvertLargeTitle,
 IF(AA5.aaPosition = 'LARGE',AA5.aaColorRed,0) AS motelAppAdvertLargeColorR,
 IF(AA5.aaPosition = 'LARGE',AA5.aaColorGreen,0) AS motelAppAdvertLargeColorG,
 IF(AA5.aaPosition = 'LARGE',AA5.aaColorBlue,0) AS motelAppAdvertLargeColorB, 
IF(SMTASE.aseIdx IS NOT NULL,SMTASE.aseIdx,0) AS specialEvents,
SMTASE.aseTitle AS specialEventsTitle,
SMTASE.aseSubTitle AS specialEventsSubTitle,
SMTASE.aseImage AS specialEventsImage,
SMTASE.aseColorRed AS specialEventsColorR,
SMTASE.aseColorGreen AS specialEventsColorG,
SMTASE.aseColorBlue AS specialEventsColorB,
SMTASE.aseMotelCounts AS specialEventsMotelCounts,
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
    ) 
  LEFT JOIN photo photo 
    ON (photoMap.photoNo = photo.`no`) 
   
    LEFT JOIN motelapp.motelAppAdverts AA1
    ON (AA1.placeNo = P.no AND AA1.aaOpen='Y') 
   
    LEFT JOIN motelapp.motelAppAdverts AA2
    ON (AA2.placeNo = P.no AND AA2.aaOpen='Y') 
    
    LEFT JOIN motelapp.motelAppAdverts AA3
    ON (AA3.placeNo = P.no AND AA3.aaOpen='Y') 
    
    LEFT JOIN motelapp.motelAppAdverts AA4
    ON (AA4.placeNo = P.no AND AA4.aaOpen='Y') 
    
    LEFT JOIN motelapp.motelAppAdverts AA5
    ON (AA5.placeNo = P.no AND AA5.aaOpen='Y') 
    
    LEFT JOIN motelapp.motelAppSpecialEventMotels SMTASEM
    ON (SMTASEM.placeNo = P.no) 
    
    LEFT JOIN motelapp.motelAppSpecialEvents SMTASE
    ON (SMTASE.aseIdx = SMTASEM.aseIdx) 
     
WHERE P.serviceType = '4' 
  AND P.published = 1 
GROUP BY P.no 