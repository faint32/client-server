<?
error_reporting ( E_ERROR );
require ("../include/global.session.php");
require ("../include/get.variable.inc.php");
require ("../function/function.php");
include ("../include/config.inc.php");


$loginskin="../skin/skin0/";
if(empty($loginuser) || empty($loginname) ){
  Header("Location: ../index.php");
}else
{
	if (isset($_COOKIE["user"])){
			setcookie("user", $loginuser, time()+1800);
	}
}
if($thismenucode != "sys"){	// ����ϵͳ����
// �ж�����Ȩ��ʹ��
	if(!empty($thismenucode)){
		if($loginusermenu[$thismenucode][item] !=1){
			Header("Location: ../login/qxerror.html");
		}else{
			$thismenuqx = $loginuserqx[$thismenucode];
		}
	}
	//include ("../include/config.inc.php");
}else{
	//include ("../include/config.inc.php");
}
require ("../include/template.inc.php");


?>