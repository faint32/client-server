<?php
if($_SERVER['HTTP_HOST'] == '127.0.0.1')
{
	$dxdebug = 1;
}
else
{
	$dxdebug = 0;
}

header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require ("../class/tfbrequest.class.php");
require ("../class/tfbslotcard.class.php");
require ("../class/tfbxmlResponse.class.php");
include_once ("../third_api/kuaidi.class.php");
include_once ("../third_api/mssale.class.php");

$arr_limitauthorid = array('82','102');
error_reporting(E_ERROR);
$reqcontext = file_get_contents("php://input");
$TfbAuthRequest = new TfbAuthRequest();
$Publiccls = new PublicClass(); //初始化类实例 
set_error_handler('my_error_handler');
$reqxmlcontext = $TfbAuthRequest->getReqContext($reqcontext); //解密并获得请求数据
$arr_xml = $Publiccls->xml_to_array($reqxmlcontext);

global $dxdebug; if($dxdebug) file_put_contents("/var/www/mobilepay/log/debug.log", "androidapi.php--line22 ：" . date("Y-m-d H:i:s") . "\r\n", LOCK_EX);
global $dxdebug; if($dxdebug) file_put_contents("/var/www/mobilepay/log/debug.log", "androidapi.php--line23 ：" . $reqxmlcontext . "\r\n", FILE_APPEND | LOCK_EX);
global $dxdebug; if($dxdebug) file_put_contents("/var/www/mobilepay/log/debug.log", "androidapi.php--line24 ：" . print_r($arr_xml, true) . "\r\n", FILE_APPEND | LOCK_EX);

$authorid = ($arr_xml['operation_request']['msgheader']['channelinfo']['authorid']);	
$arr_authorinfo=AuToken::getauthorusername($authorid);
$authortruename = $arr_authorinfo['username'];	
$file = "../../tfb_log/" . date('md')."-" .$authortruename. "log" . ".txt";
$filehandle = fopen($file, "a");
$now = date('Y-m-d H:i:s');
fwrite($filehandle, $now . "\r\n======请求内容：\r\n" . $reqcontext . "\r\n\r\n" . $reqxmlcontext);
fclose($filehandle);

$apiAutoken = $TfbAuthRequest->apiAutoken($reqxmlcontext); //授权码等信息验证 
if ($apiAutoken && $api_name && $api_name_func) // 验证通过 
	{
  
	spl_autoload_register(array (
		'Loader',
		'loadClass'
	));
	$classname = $api_name;
	$ApiClass = new $classname ();
	$returnvalue =  $ApiClass-> $api_name_func ();
	
	echo $returnvalue;
    
} else {
	$ErrorReponse = new ErrorReponse();
	$Error = array (
		'result' => 'failure',
		'retcode' => '404'
	);
	$ErrorReponse->reponError($Error); //出错反馈 
}
?>