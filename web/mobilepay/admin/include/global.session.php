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
session_register("g_shop_path");   //�̼��ļ��� 
session_register("g_page_num");
session_register("g_weburl");
session_register("session_sdcrid");
session_register("g_lprice_point");
session_register("g_dprice_point");
session_register("g_a_blank");
session_register("g_tmp_paycardkey");//ˢ������ʱkey
session_register("g_tmp_paycardid");//ˢ������ʱid


$g_propic   	= "http://14.18.207.56/mobilepay/admin/";   //ͼƬ���·�� 
$g_uppic   	  = "http://14.18.207.56/mobilepay/admin/uplodefile/upload.php";//�ϴ�ͼƬ�����ַ 
$g_upbackurl  = "http://14.18.207.56/mobilepay/admin/function/uploadfeedback.php";//�ϴ�ͼƬ��ֵ�����ݴ汾�ص�url��ַ  
$g_showpic    = "http://14.18.207.56/managementfile/";   //�ϴ�ͼƬ�����ַ  
$g_weburl   	= "http://14.18.207.56/mobilepay/";//ͼƬ���·�� 




?>