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

$g_propic   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/";   //ͼƬ���·��
$g_uppic   	    = "http://".CONST_APIWEB."/managementfile/uplodefile/upload.php";//�ϴ�ͼƬ�����ַ
$g_upbackurl   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/admin/function/uploadfeedback.php";//�ϴ�ͼƬ��ֵ�����ݴ汾�ص�url��ַ
$g_showpic      = "http://".CONST_APIWEB."/managementfile/";   //�ϴ�ͼƬ�����ַ
$g_weburl   	= "http://".CONST_APIWEB."/".CONST_APIDIR."/";//ͼƬ���·��


?>