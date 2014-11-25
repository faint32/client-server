<?php
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");

include ("httpclient.php");
if(defined("CONST_APIDIR")){
    echo "true";
}else
{
    echo "false";
}

$xmlcontent = $_POST['xmlcontent'];
$getAndroidcontent = unescape($xmlcontent);
$client = new HttpClient('127.0.0.1');  // create a client  
$client->post('/'.CONST_APIDIR.'/sever/getapi.php',$getAndroidcontent);   // get
$fp = $client->getContent();
echo $fp;
?>