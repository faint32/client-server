<?
ob_start();
session_start();
session_cache_limiter('nocache');
$lifeTime = 18800;  
session_set_cookie_params($lifeTime);

//define("CONST_APIDIR",     "tfb_test");
define("CONST_APIDIR",     "mobilepay");
define("CONST_APIWEB",     "127.0.0.1");
define("CONST_APIIP",      "14.18.205.153");
define("CONST_LOGDIR",      "test_Log");
//const CONST_APIDIR = "tfb_test";
//const CONST_APIWEB =  "www.tfbpay.cn";
//const CONST_APIIP  = "14.18.205.153";

$g_propic   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/";   //图片存放路径
$g_uppic   	    = "http://".CONST_APIWEB."/managementfile/uplodefile/upload.php";//上传图片组件地址
$g_upbackurl   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/admin/function/uploadfeedback.php";//上传图片后传值回来暂存本地的url地址
$g_showpic      = "http://".CONST_APIWEB."/managementfile/";   //上传图片组件地址
$g_weburl   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/";//图片存放路径


?>