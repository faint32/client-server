<?php
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
include ("httpclient.php");

$db = new DB_test();
$now = strtotime(date("Y-m-d H:i:s"));
$au_token = desencrypt($now, 'E', 'mstongfubao'); //����

$query = "select * from tb_version  where fd_version_apptype ='1'" ;
$db->query($query);
if($db->nf()){                                            //判断查询到的记录是否为空
    $db->next_record();                                   //读取记录数据
    $versionno            = $db->f(fd_version_no);               //编号
}
switch($paytype)
{
    case "qqrecharge":
        $api_name_func = 'checkRechaMoneyStatus';
        $api_name      = 'ApiQQRechangeInfo';
        break;
    case "mobilerecharge":
        $api_name_func = 'CheckTransStatus';
        $api_name      = 'ApiMobileRechargeInfoV2';
        break;
    default:
        break;


}
$xmlcontent="<?xml version=1.0 encoding=UTF-8 standalone=yes ?>
<operation_request>
<msgheader version=1.0>
<req_bkenv>01</req_bkenv>
<req_appenv>1</req_appenv>
<req_time>20140429051128</req_time>
<req_token>9bmeJASBBDGwq5d9r4oSWlTqi5XIG17AByJ4kMn3q0gj4yi+meD5aUnSq/TSUMsj+lOfvv1I91PYdK06OBWkqqQ==</req_token>
<channelinfo>
<authorid>".$authorid."</authorid>
<api_name_func>".$api_name_func."</api_name_func>
<api_name>".$api_name."</api_name>
</channelinfo>
<au_token>".$au_token."</au_token>
<req_version>".$versionno."</req_version>
</msgheader>
<msgbody>
<result>success</result>
<bkntno>".$bkntno."</bkntno>
</msgbody>
</operation_request>
";

$client = new HttpClient('127.0.0.1');  // create a client  
$client->post('/mobilepay/sever/androidapi.php',trim($xmlcontent));   // get
$fp = $client->getContent();
echo $fp."-".$xmlcontent;

function desencrypt($string, $operation, $key = '') //des���ܽ��ܷ��������ڶ�̬��
{
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array ();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a +1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'D') {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    } else {
        return str_replace('=', '', base64_encode($result));
    }
}


function unescape($str) {
    preg_match_all ( "/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $str, $matches ); //prt($matches);
    $ar = &$matches [0];
    $c = "";
    foreach ( $ar as $val ) {
        if (substr ( $val, 0, 1 ) != "%") { //如果是字母数�?-_.的ascii�?
            $c .= $val;
        } elseif (substr ( $val, 1, 1 ) != "u") { //如果是非字母数字+-_.的ascii�?
            $x = hexdec ( substr ( $val, 1, 2 ) );
            $c .= chr ( $x );
        } else { //如果是大�?xFF的码
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

?>