<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hangle_cho 
{
   public function __construct()
    {


    }

	public function utf8_strlen($str) { 
		return mb_strlen($str, 'UTF-8'); 
	}

	public function utf8_charAt($str, $num) { 
		return mb_substr($str, $num, 1, 'UTF-8'); 
	}
	public function utf8_ord($ch) {
	  $len = strlen($ch);
	  if($len <= 0) return false;
	  $h = ord($ch{0});
	  if ($h <= 0x7F) return $h;
	  if ($h < 0xC2) return false;
	  if ($h <= 0xDF && $len>1) return ($h & 0x1F) <<  6 | (ord($ch{1}) & 0x3F);
	  if ($h <= 0xEF && $len>2) return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);          
	  if ($h <= 0xF4 && $len>3) return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
	  return false;
	}
	 
	public function hangul_split($str) {
	  $cho = array("ㄱ","ㄲ","ㄴ","ㄷ","ㄸ","ㄹ","ㅁ","ㅂ","ㅃ","ㅅ","ㅆ","ㅇ","ㅈ","ㅉ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ");
	  $result = "";
	  for ($i=0; $i<$this->utf8_strlen($str); $i++) {
		$code = $this->utf8_ord($this->utf8_charAt($str, $i)) - 44032;
		if ($code > -1 && $code < 11172) {
		  $cho_idx = $code / 588;      
		  $result .= $cho[$cho_idx];
		}
	  }
	  return $result;
	}


}

?>



