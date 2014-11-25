<?php
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require ("../class/tfbrequest.class.php");
require ("../class/tfbslotcard.class.php");
require ("../class/tfbxmlResponse.class.php");
//include_once ("../third_api/kuaidi.class.php");
//include_once ("../third_api/mssale.class.php");

error_reporting(E_ERROR);

$reqcontext = file_get_contents("php://input");

  		
$TfbAuthRequest = new TfbAuthRequest();
set_error_handler('my_error_handler');
$reqxmlcontext = $TfbAuthRequest->getReqContext($reqcontext); //解密并获得请求数据
$apiAutoken = $TfbAuthRequest->apiAutoken($reqxmlcontext); //授权码等信息验证 
$file="starkey".date('y-m-dH-i-s').".txt"; 
  		
if (true === $apiAutoken) // 验证通过 
	{

	spl_autoload_register(array (
		'Loader',
		'loadClass'
	));
	$classname = $api_name;
	$ApiClass = new $classname ();
	$ApiClass-> $api_name_func ();
}
?>