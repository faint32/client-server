<?php
header ('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
$db = new DB_test ( );
error_reporting ( E_ERROR );
//error_reporting(E_ERROR| E_WARNING| E_PARSE);
$Publiccls = new PublicClass ( ); //初始化类实例 
$xmlcontent = $_POST ['xmlcontent'];
$getAndroidcontent = $xmlcontent ;
//$getAndroidcontent = (str_replace("\\", "", $getAndroidcontent));
$arr_xml = $Publiccls->xml_to_array ( $getAndroidcontent );
//示例的做法  
//set_error_handler ( 'my_error_handler' );
$api_name = $arr_xml ['operation_request'] ['msgheader'] ['channelinfo'] ['api_name'];
$api_name_func = $arr_xml ['operation_request'] ['msgheader'] ['channelinfo'] ['api_name_func'];
$param = $getAndroidcontent;
//echo $xmlcontent;exit;
$xmlcontent = u2g($xmlcontent);
if($api_name){
$query = "update web_test_interface set fd_interface_demo = '$xmlcontent' where fd_interface_apinamefunc= '$api_name_func' and fd_interface_apiname = '$api_name'";
$db->query ( $query );
//echo $query;exit;

$arr_message = array ("result" => "success", "message" => "保存成功!" );

$arr_msg ['msgbody'] ['result'] = $arr_message ['result'];
$arr_msg ['msgbody'] ['message'] = $arr_message ['message'];
//echo $query;exit;
$returnvalue = array ('operation_response' => array ('msgheader' => array ('req_seq' => '21', 'req_token' => $au_token,
					'req_bkenv' => $req_bkenv, 'retinfo' => array ('rettype' => '0', 'retcode' => '0', 'retmsg' => '成功' )  ), 
"msgbody" => $arr_msg ['msgbody'] ) );
}
echo xml_encode($returnvalue,'utf-8');

?>