<?
ob_start(); 
session_start();
session_cache_limiter('nocache');
$lifeTime = 18800;  
session_set_cookie_params($lifeTime);


session_register("g_arr_areatype");
session_register("g_mem_path");
session_register("g_saler_path");
session_register("g_server_url");
session_register("g_public_path");
session_register("g_tpl_path");
session_register("g_propic");
session_register("g_uppic");
session_register("g_showpic");
session_register("g_showpic");
session_register("g_upbackurl");
session_register("g_shop_path");   //商家文件夹 
session_register("g_page_num");
session_register("g_weburl");
session_register("session_sdcrid");
session_register("g_lprice_point");
session_register("g_dprice_point");
session_register("g_a_blank");
session_register("g_tmp_paycardkey");//刷卡器临时key
session_register("g_tmp_paycardid");//刷卡器临时id


$g_propic   	= "http://14.18.207.56/mobilepay/admin/";   //图片存放路径 
$g_uppic   	  = "http://14.18.207.56/mobilepay/admin/uplodefile/upload.php";//上传图片组件地址 
$g_upbackurl  = "http://14.18.207.56/mobilepay/admin/function/uploadfeedback.php";//上传图片后传值回来暂存本地的url地址  
$g_showpic    = "http://14.18.207.56/managementfile/";   //上传图片组件地址  
$g_weburl   	= "http://14.18.207.56/mobilepay/";//图片存放路径 




?>