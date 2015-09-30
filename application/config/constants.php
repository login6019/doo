<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']); 

define('DEV_CIP', "10.17.139.143"); 
define('DEV_MANAGEMENT_IP', "14.63.192.97"); 

define('DEV_CRON_IP', "172.27.213.96"); 

define('QA_MANAGEMENT_IP', "211.253.8.108"); 
define('QA_PUBLIC_IP', "172.27.127.123"); 

//define('CRON_IP', "14.63.193.59"); 




/*SERVER별 HOST 분기*/
if( substr($_SERVER['HTTP_HOST'], 0,13) == DEV_CIP || substr($_SERVER['HTTP_HOST'], 0,13) == DEV_CRON_IP || substr($_SERVER['HTTP_HOST'], 0,12) == DEV_MANAGEMENT_IP || preg_match('/dev\./', $_SERVER['HTTP_HOST'])){
	/***** 개발서버 공통 *****/
		define('ESLIB_IP', '10.17.139.144'); 
		define('CORE_IP', '10.17.139.144'); 

		define('ES_ROOT', 'http://172.27.249.240:9200/'); 
		define('DB_HOST', '10.17.139.131'); //개발서버 CIP
		define('DB_USER_NAME', 'root'); //개발서버 네임
		define('DB_USER_PW', 'yanolja'); //개발서버 패스워드
		define('THIS_SERVER', 'DEV'); //SERVER ZONE
		define('DB_PORT', '3306'); //운영서버 패스워드
		define('SERVER_COLOR', '#ccc'); //server color
		define('PORT_ES_001', '9200'); 
		define('PORT_ES_002', '9200'); 

		define('LOG_DB_HOST', '14.63.193.2'); //로그디비 IP
		define('LOG_DB_USER_NAME', 'elastic');   //로그디비 네임
		define('LOG_DB_USER_PW', 'Lkd9^gsG7');   //로그디비 패스워드
		define('LOG_DB_PORT', '1500'); //DB PORT - billon master

	} else if( substr($_SERVER['HTTP_HOST'], 0,13) == QA_MANAGEMENT_IP || substr($_SERVER['HTTP_HOST'], 0,14) == QA_PUBLIC_IP || preg_match('/qa\./', $_SERVER['HTTP_HOST'])){
	/***** QA 서버 공통 *****/
		define('HOST_ES_001', '172.27.249.240'); 
		define('HOST_ES_002', '172.27.249.240'); 
		define('HOST_ESC_001', '172.27.249.240'); //관리자용 Core
		define('HOST_ESC_002', '172.27.249.240'); 

		define('ESLIB_IP', '172.27.249.240'); 
		define('CORE_IP', '172.27.249.240'); 

		define('ES_ROOT', 'http://172.27.249.240:9200/'); 
		define('DB_HOST', '172.27.179.245'); //운영서버 공인아이피
		define('DB_USER_NAME', 'dev'); //운영서버 네임
		define('DB_USER_PW', 'yanolja'); //운영서버 패스워드
		define('THIS_SERVER', 'QA'); //SERVER ZONE
		define('DB_PORT', '3306'); //운영서버 패스워드
		define('SERVER_COLOR', '#ccc'); //server color
		define('PORT_ES_001', '9200'); 
		define('PORT_ES_002', '9200'); 
	} else {
	/***** LIVE (운영)서버 공통 *****/
		//14.49.38.228 - LIVE  10.17.151.168 - TEST 

		#검색엔진
		//define('ESLIB_IP', '10.17.151.168');	// - test  
		define('ESLIB_IP', '14.49.38.228');	// [LIVE] 
		#관리자
		define('CORE_IP', '14.49.38.228');			// [LIVE]
		//define('CORE_IP', '10.17.151.168');  	// - test 

		define('ES_ROOT', 'http://172.27.249.240:9200/'); 
		define('DB_HOST', '10.17.151.150'); //아이피 master
		//define('DB_HOST', '10.17.151.172'); //아이피
		define('DB_USER_NAME', 'elastic-mashup'); //
		define('DB_USER_PW', 'Lkd9^gsG7'); //운영서버 패스워드
		define('DB_PORT', '1500'); //DB PORT - billon master
		//define('DB_PORT', '1550'); //DB PORT - billon
		define('THIS_SERVER', 'LIVE'); //SERVER ZONE

		define('SERVER_COLOR', '#221DB5'); //server color
		define('PORT_ES_001', '9200'); 
		define('PORT_ES_002', '9201'); 

		define('LOG_DB_HOST', '10.17.151.174'); //로그디비 IP
		define('LOG_DB_USER_NAME', 'elastic');   //로그디비 사용자 네임
		define('LOG_DB_USER_PW', 'Lkd9^gsG7');   //로그디비 패스워드
		define('LOG_DB_PORT', '1500'); //DB PORT - billon master

	}
/*SERVER별 HOST 분기*/
		define('DB_NAME', 'billion'); //운영서버 DB
		define('LOG_DB_NAME', 'log'); //LOG DB


/* LIVE 검색엔진 작업시 CORE 와 LIBRARY 를 달리해서 작업하고 넣어준다 */
/* 14.49.38.228 - LIVE     |||      10.17.151.168 - TEST    */
		define('HOST_ES_001', ESLIB_IP); //Library 용
		define('HOST_ES_002', ESLIB_IP); //Library 용
		define('HOST_ESC_001', CORE_IP); //관리자용 Core
		define('HOST_ESC_002', CORE_IP); //관리자용 Core

