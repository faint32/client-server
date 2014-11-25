<?
ob_start(); 
session_start();
session_cache_limiter('nocache');
$lifeTime = 18800;  
session_set_cookie_params($lifeTime);
date_default_timezone_set('Etc/GMT-8');
error_reporting(0);



 				
//自定义
define("CONST_ILS_HTML",     "./tpl/");        //HTML目录
define("CONST_ILS_MOD",      "./module/");         //php程序目录
define("CONST_ILS_AJAX",     "./ajax/");          //ajax目录
define("CONST_ILS_FUNC",     "./function/");          //function目录
define("CONST_MEM_HTML",     "./mem/");          //function目录

define("CONST_UPLOAD_HTML",     "http://localhost/ilesong/ilspic/index.php");          //上传文件的地址
define("CONST_UPFILE_URL",     "http://localhost/ilesong/ilspic/");          //显示上传文件的地址
define("CONST_WEB_URL",     "http://localhost/ilesong/");          //显示上传文件的地址
define("CONST_UPCAT_URL",     "http://localhost/ilesong/admin/");          //显示上传文件的地址

//define("CONST_MPM_NAV",    "nav/"); 

?>