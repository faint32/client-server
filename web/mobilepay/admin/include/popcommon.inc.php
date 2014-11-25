<?
ob_start(); 
session_start();
$loginskin="../skin/skin0/";
require ("../include/get.variable.inc.php");
require ("../function/function.php");
include ("../include/popconfig.inc.php");
/*
if(empty($loginuser) || empty($loginname) ){
  Header("Location: http://www.papersystem.cn/mstest");
}else
{
	if (isset($_COOKIE["user"])){
			setcookie("user", $loginuser, time()+1800);
	}
}
if($thismenucode != "sys"){	// 不是系统程序
// 判断有无权限使用
	if(!empty($thismenucode)){
		if($loginusermenu[$thismenucode][item] !=1){
			Header("Location: http://www.papersystem.cn/mstest/login/qxerror.html");
		}else{
			$thismenuqx = $loginuserqx[$thismenucode];
		}
	}
	include ("../include/popconfig.inc.php");
}else{
	include ("../include/popconfig.inc.php");
}*/
require ("../include/template.inc.php");


?>