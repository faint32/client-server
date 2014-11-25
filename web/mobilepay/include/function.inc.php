<?php
class PublicClass {
	//获取手机短信接口		    
	// Xml 转 数组, 包括根键 
	function xml_to_array($xml) {
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if (preg_match_all ( $reg, $xml, $matches )) {
			$count = count ( $matches [0] );
			for($i = 0; $i < $count; $i ++) {
				$subxml = $matches [2] [$i];
				$key = $matches [1] [$i];
				if (preg_match ( $reg, $subxml )) {
					$arr [$key] = $this->xml_to_array ( $subxml );
				} else {
					$arr [$key] = $subxml;
				}
			}
		}
		return $arr;
	}
	// Xml 转 数组, 不包括根键 
	function xmltoarray($xml) {
		$arr = $this->xml_to_array ( $xml );
		$key = array_keys ( $arr );
		return $arr [$key [0]];
	}
}

function Get($url) {
	if (function_exists ( 'file_get_contents' )) {
		$file_contents = file_get_contents ( $url );
	} else {
		$ch = curl_init ();
		$timeout = 5;
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		$file_contents = curl_exec ( $ch );
		curl_close ( $ch );
	}
	return $file_contents;
}
function getCode($num, $w, $h) //生成随机码 
{
	$code = "";
	for($i = 0; $i < $num; $i ++) {
		$code .= rand ( 0, 9 );
	}
	return $code;
}
// xml编码
function xml_encode($data, $encoding = 'utf-8') {
	$xml = "<?xml version='1.0' encoding='" . $encoding . "' standalone='yes' ?>";
	$xml .= data_to_xml ( $data );
	return $xml;
}

function data_to_xml($data) {
	$xml = '';
	if ($data) {
		foreach ( $data as $key => $val ) {
			is_numeric ( $key ) && $key = "msgchild";
			if($key == 'msgheader') $key = "msgheader version = \"1.0\"";
			$xml .= "<$key>";
			$xml .= (is_array ( $val ) || is_object ( $val )) ? data_to_xml ( $val ) : $val;
			list ( $key, ) = explode ( ' ', $key );
			$xml .= "</$key>";
		}
	}
	return $xml;
}
function unescape($str) {
	preg_match_all ( "/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $str, $matches ); //prt($matches);
	$ar = &$matches [0];
	$c = "";
	foreach ( $ar as $val ) {
		if (substr ( $val, 0, 1 ) != "%") { //如果是字母数字+-_.的ascii码
			$c .= $val;
		} elseif (substr ( $val, 1, 1 ) != "u") { //如果是非字母数字+-_.的ascii码
			$x = hexdec ( substr ( $val, 1, 2 ) );
			$c .= chr ( $x );
		} else { //如果是大于0xFF的码
			$val = intval ( substr ( $val, 2 ), 16 );
			if ($val <= 0x7F) { // 0000-007F
				$c .= chr ( $val );
			} elseif ($val <= 0x800) { // 0080-0800
				$c .= chr ( 0xC0 | ($val / 64) );
				$c .= chr ( 0x80 | ($val % 64) );
			} else { // 0800-FFFF
				$c .= chr ( 0xE0 | (($val / 64) / 64) );
				$c .= chr ( 0x80 | (($val / 64) % 64) );
				$c .= chr ( 0x80 | ($val % 64) );
			}
		}
	}
	return $c;
}


//上传的新增,修改,删除,$picid图片ID,$picpath图片路径,$uploadmethod上传动作,$uploadpictype,管理图片类型,$authorid上传者ID,$uploadmark上传结果,1成功,0失败
function uploadaction($picid, $picpath, $uploadmethod, $uploadpictype, $uploadmark, $authorid) {
	$dbfile = new DB_file ( );
	switch ($uploadmethod) {
		case "new" :
			$query = "insert into tb_upload_category_list (fd_cat_dateid,fd_cat_scatid,fd_cat_thumurl,fd_cat_time ) value ('$authorid','$uploadpictype','$picpath',now())";
			$dbfile->query ( $query );
			$returnvalue ['picid'] = $dbfile->insert_id ();
			$returnvalue ['uploadmethod'] = 'modi';
			break;
		case "modi" :
			$query = "update tb_upload_category_list set fd_cat_dateid='$authorid',fd_cat_thumurl='$picpath' where fd_cat_id='$picid'";
			$dbfile->query ( $query );
			$returnvalue ['picid'] = $picid;
			$returnvalue ['uploadmethod'] = 'modi';
			break;
		case "del" :
			$query = "delete from tb_upload_category_list where fd_cat_id='$picid'";
			$dbfile->query ( $query );
			$returnvalue ['picid'] = 0;
			$returnvalue ['uploadmethod'] = 'new';
			break;
	}
	// $dbfile->query($query);
	return $returnvalue;
}

//获取上传信息
function getupload($uploadpictype, $authorid) {
	
	$dbfile = new DB_file ( );
	$query = "select * from tb_upload_category_list where fd_cat_dateid='$authorid' and fd_cat_scatid='$uploadpictype' order by fd_cat_id desc";
	$dbfile->query ( $query );
	if ($dbfile->nf ()) {
		while ( $dbfile->next_record () ) 
		{
			$returnvalue [] ["pic"] = $dbfile->f ( fd_cat_id );
			$returnvalue [] ["picpath"] = $dbfile->f ( fd_cat_thumurl );
		}
	} else 
	{
		$returnvalue [] ["pic"] = "0";
		$returnvalue [] ["picpath"] = "null";
	}
	
	return $returnvalue;
}
//获取银行名
function getbankname($bankid) {
	
	$dbfile = new DB_test ( );
	$query = "select fd_bank_name from tb_bank where fd_bank_id='$bankid'";
	
	$dbfile->query ( $query );
	
	if ($dbfile->nf ()) {
		$dbfile->next_record ();
		$bankname = $dbfile->f ( fd_bank_name );
	}
	$returnvalue = array ("bankname" => $bankname );
	return $returnvalue;
}
//获取银行id
function getbankid($bankname) {
	
	$dbfile = new DB_test ( );
	$query = "select fd_bank_id from tb_bank where fd_bank_name='$bankname'";
	
	$dbfile->query ( $query );
	
	if ($dbfile->nf ()) {
		$dbfile->next_record ();
		$bankid = $dbfile->f ( fd_bank_id );
	}
	$returnvalue = $bankid;
	return $returnvalue;
}
//读取商家信息
function getauthorshop($shopid) {
	$dbshop = new DB_shop ( );
	$query = "select fd_shop_name as shopname from tb_shop where fd_shop_id='$shopid'";
	$dbshop->query ( $query );
	$arr_msg = auto_charset ( $dbshop->getFiledData ( '' ), 'gbk', 'utf-8' );
	return $arr_msg;
}

?>