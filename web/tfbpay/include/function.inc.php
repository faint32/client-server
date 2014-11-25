<?php
function is_phone($mobilephone)
{
if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[02345189]{1}[0-9]{8}$|18[6789][0-9]{8}$/",$mobilephone)){    
    //验证通过    
     return true;   
}else{    
    //手机号码格式不对    
    return false;    
} 
}

function a_array_unique($array)//写的比较好
{
   $out = array();
   foreach ($array as $key=>$value) {
       if (!in_array($value, $out))
{
           $out[$key] = $value;
       }
   }
   return $out;
} 
function multi_unique($array) {
   foreach ($array as $k=>$na)
       $new[$k] = serialize($na);
   $uniq = array_unique($new);
   foreach($uniq as $k=>$ser)
       $new1[$k] = unserialize($ser);
   return ($new1);
}
class PublicClass {
	//��ȡ�ֻ���Žӿ�		    
	// Xml ת ����, ������� 
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
	// Xml ת ����, ��������� 
	function xmltoarray($xml) {
		$arr = $this->xml_to_array ( $xml );
		$key = array_keys ( $arr );
		return $arr [$key [0]];
	}
}
//��ȡIP����
function getIParea($ip){
	  $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$ip;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ��ݷ���
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
    $loc = $ipArray[1];
    return $loc;
}

//��ȡIP
function getIP(){
	
if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) 
{ 
$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
} 
elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) 
{ 
$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
}
elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) 
{ 
$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
} 
elseif (getenv("HTTP_X_FORWARDED_FOR")) 
{ 
$ip = getenv("HTTP_X_FORWARDED_FOR"); 
} 
elseif (getenv("HTTP_CLIENT_IP")) 
{ 
$ip = getenv("HTTP_CLIENT_IP"); 
} 
elseif (getenv("REMOTE_ADDR"))
{ 
$ip = getenv("REMOTE_ADDR"); 
} 
else 
{ 
$ip = "Unknown"; 
} 
return $ip;
}
function utf_substr($str,$len){
for($i=0;$i<$len;$i++){
   $temp_str=substr($str,0,1);
   if(ord($temp_str) > 127){
    $i++;
    if($i<$len){
     $new_str[]=substr($str,0,3);
     $str=substr($str,3);
    }
   }
   else{
    $new_str[]=substr($str,0,1);
    $str=substr($str,1);
   }
}
return join($new_str);
}
//������
function makeselect($arritem,$hadselected,$arry){ 
       	for($i=0;$i<count($arritem);$i++){
       	   if($hadselected ==  $arry[$i]){
       	       $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
       	   }else{
       	       $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
       	   }
       	} 
    return $x ; 
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
function getCode($num, $w, $h) //�������� 
{
	$code = "";
	for($i = 0; $i < $num; $i ++) {
		$code .= rand ( 0, 9 );
	}
	return $code;
}
// xml����
function xml_encode($data, $encoding = 'utf-8') {
	$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
	$xml .= data_to_xml ( $data );
	return $xml;
}

function data_to_xml($data) {
	$xml = '';
	if ($data) {
		foreach ( $data as $key => $val ) {
			is_numeric ( $key ) && $key = "msgchild id=\"$key\"";
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
		if (substr ( $val, 0, 1 ) != "%") { //�������ĸ����+-_.��ascii��
			$c .= $val;
		} elseif (substr ( $val, 1, 1 ) != "u") { //����Ƿ���ĸ����+-_.��ascii��
			$x = hexdec ( substr ( $val, 1, 2 ) );
			$c .= chr ( $x );
		} else { //����Ǵ���0xFF����
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
function my_error_handler($errno, $errstr, $errfile, $errline) {
	switch ($errno) {
		case E_ERROR :
			$returns = "ERROR: [ID $errno] $errstr (Line: $errline of $errfile) \n";
			
			//����Error������ʱ�˳��ű�  
			$return = "<?xml version='1.0' encoding='UTF-8' ?>";
			$return .= "<operation_response>
					 <msgheader version='1.0'>
					 <req_seq>21</req_seq>                  
					 <ope_seq>21</ope_seq>                
                     <retinfo>                               
					 <rettype>500</rettype>                
			         <retcode>errcode</retcode>                  
			         <retmsg>$returns</retmsg>               
		             </retinfo>
	                 </msgheader>";
			$return .= "</operation_response>";
			
			echo $return;
			exit ();
			break;
		
		case E_WARNING :
			$returns = "WARNING: [ID $errno] $errstr (Line: $errline of $errfile) \n";
			$return = "<?xml version='1.0' encoding='UTF-8' ?>";
			$return .= "<operation_response>
					 <msgheader version='1.0'>
					 <req_seq>21</req_seq>                  
					 <ope_seq>21</ope_seq>                
                     <retinfo>                               
					 <rettype>500</rettype>                
			         <retcode>errcode</retcode>                  
			         <retmsg>$returns</retmsg>               
		             </retinfo>
	                 </msgheader>";
			$return .= "</operation_response>";
			
			echo $return;
			exit ();
			break;
		
		default :
			break;
	}
}
//���T+N�Ĺ�������
function getfeedate($now, $n) {
	$diff = strtotime ( $now ) + ($n * 86400);
	$dayweek = date ( "w", $diff );
	if ($dayweek == "6") {
		$res = date ( "Y-m-d", ($diff + 172800) );
	} elseif ($dayweek == "0") {
		$res = date ( "Y-m-d", ($diff + 86400) );
	} else {
		$res = date ( "Y-m-d", $diff );
		$computeWeek = computeWeek ( $now, $res );
		if ($computeWeek > 0) {
			$res = strtotime ( $res ) + ($computeWeek * 172800);
			$dayweek2 = date ( "w", $res );
			if ($dayweek2 == "6") {
				$res = date ( "Y-m-d", ($res + 172800) );
			} elseif ($dayweek2 == "0") {
				$res = date ( "Y-m-d", ($res + 86400) );
			} else {
				$res = date ( "Y-m-d", $res );
			}
		} else {
			$res = date ( "Y-m-d", $diff );
		}
	}
}
//���T+N�Ĺ������ڵ���
function computeWeek($date1, $date2) {
	$diff = strtotime ( $date2 ) - strtotime ( $date1 );
	$res = floor ( $diff / (24 * 60 * 60 * 7) );
	return $res;
}

//�м���ַ�
function str_insert($str, $i, $substr) 
{ 
for($j=0; $j<$i; $j++){ 
$startstr .= $str[$j]; 
} 
for ($j=$i; $j<strlen($str); $j++){ 
$laststr .= $str[$j]; 
} 
$str = ($startstr . $substr . $laststr); 
return $str; 
} 

function getrestmoney($authorid,$mset=null)//��ȡʣ����
{
	$db=new db_test;
	$data=date("Y-m-d",time());
	$query="select * from   tb_agentpaymoneylist
			left join tb_slotcardmoneyreq on fd_agpm_authorid = fd_pmreq_authorid
			left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid
			where fd_agpm_authorid='$authorid'  and fd_agpm_paydate = '$data'";
	$db->query($query);	
	    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      while($db->next_record()){                                   //��ȡ��¼��� 
			$sallmoney=$db->f(fd_scdmset_sallmoney);
			$allpaymoney +=$db->f(fd_agpm_paymoney);
		}
		return $sallmoney-$allpaymoney;
  }else{
  	$query="select * from   tb_slotcardmoneyset
			where fd_scdmset_id='$mset'";
				$db->query($query);	
	    if($db->nf()){                      
	    	$db->next_record();
	    	return $db->f(fd_scdmset_sallmoney)*10000;
	    }
  } 		
}


function getusemoney($authorid)//��ȡ���ý��
{
	$db=new db_test;
	$data=date("Y-m-d",time());
	$query="select * from tb_slotcardmoneyreq
			left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid 
			where fd_pmreq_authorid='$authorid' and fd_pmreq_state='9' and fd_pmreq_reqdatetime like '$data%'";
	$db->query($query);	
	    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      while($db->next_record()){                                   //��ȡ��¼��� 
			$allreqmoney +=$db->f(fd_pmreq_reqmoney);
		}
		return $allreqmoney;
  }else{
  	return "0.00";
  } 		
}
?>