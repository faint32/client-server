<?
require ("../include/common.inc.php");
$db = new DB_test ;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("main","main.html"); 
$t->set_block("main", "MAILBK", "mailbks");  //使用一个块

session_register("loginorganqx");
session_register("openmain");
session_register("loginoverkgflstid");   //超克重科目
session_register("logintabshow");   //公共功能
session_register("loginimoneyflstid");   //应收账款
session_register("loginomoneyflstid");   //应付账款
session_register("loginibillflstid");   //应收票据
session_register("loginobillflstid");   //应付票据
session_register("loginpreimoneyflstid");   //预收账款
session_register("loginpreomoneyflstid");   //预付账款
session_register("loginvoucher");  
session_register("loginycostflstid");     //应收应付调整科目

session_register("loginoimoneyflstid");   //其他应收账款
session_register("loginoomoneyflstid");   //其他应付账款

session_register("loginoverkgflstid");   //超克重科目

session_register("loginselpayflstid");        //营业外支出
session_register("loginselincomeflstid");     //营业外收入

session_register("loginprocostflstid");        //主营业成本
session_register("loginproincomeflstid");     //主营业收入

session_register("loginlirunflstid");        //本年利润	
session_register("loginboshouruflstid");     //其他业务收入

session_register("loginstorageflstid");   //库存商品
session_register("loginzaituflstid");   //库存商品
session_register("lgoinbmoney");   

$loginbmoney=4;
$openmain = 1;

if($loginindextype==0){
	$indexpagetype = "right.php";
}else{
  $indexpagetype = "right.php";
}
if(empty($loginuser) || empty($loginname) ){
  //Header("Location: ../index.php");
  echo "<script>parent.location='../index.php';</script>";
}else
{
	if (isset($_COOKIE["user"]))
	    {
			setcookie("user", $loginuser, time()+1800);
		}
		
}
//echo "<script>location.href='../main/main.php';</script>";
$menutype = 0;
$query = "select * from web_teller where fd_tel_name = '$loginname'";
$db->query($query);
if($db->nf()){
   $db->next_record();
   $menutype = $db->f(fd_tel_menulogintype);
   $hors            = $db->f(fd_tel_hors);
   $loginorganqx    = $db->f(fd_tel_organqx);
}

//$query = "select * from tb_baseinfo where fd_baseinfo_organid = '$loginorganid'";
//$db->query($query);
//if($db->nf())
//{
//   $db->next_record();
//    $loginimoneyflstid     = $db->f(fd_baseinfo_inmoneyflstid);   //应收账款
//	$loginomoneyflstid     = $db->f(fd_baseinfo_outmoneyflstid);
//	$loginibillflstid      = $db->f(fd_baseinfo_inbillflstid);
//	$loginobillflstid      = $db->f(fd_baseinfo_outbillflstid);
//	$loginpreimoneyflstid  = $db->f(fd_baseinfo_preimoneyflstid);
//	$loginpreomoneyflstid  = $db->f(fd_baseinfo_preomoneyflstid);
//	$loginvoucher          = $db->f(fd_baseinfo_bvoucher);
//	$loginycostflstid      = $db->f(fd_baseinfo_ycostflstid);
//	
//	$loginselpayflstid          = $db->f(fd_baseinfo_selpayflstid);
//	$loginselincomeflstid      = $db->f(fd_baseinfo_selincomeflstid);
//	
//	$loginoimoneyflstid     = $db->f(fd_baseinfo_oinmoneyflstid);   //应收账款
//	$loginoomoneyflstid     = $db->f(fd_baseinfo_ooutmoneyflstid);
//	$loginstorageflstid     = $db->f(fd_baseinfo_storageflstid);
//	$loginoverkgflstid      = $db->f(fd_baseinfo_overkgflstid);
//	//echo $loginstorageflstid;
//	$loginproincomeflstid      = $db->f(fd_baseinfo_proincomeflstid);   //应收账款
//	$loginprocostflstid        = $db->f(fd_baseinfo_procostflstid);
//	$loginzaituflstid          = $db->f(fd_baseinfo_zaituid);
//    $loginlirunflstid         = $db->f(fd_baseinfo_lirunflstid);
//	$loginboshouruflstid       = $db->f(fd_baseinfo_boshouruflstid);
//	
//   
//   
//}

if($hors == 0){
    // echo "<script>location.href='../main_c/main.php';<script>";
}


if($menutype==0){
	$menutypephp = "left.php";
}else{
  $menutypephp = "shortcut.php";
}

$t->set_var("menutypephp",$menutypephp);  // 调用首页类型
$t->set_var("ishavekey",$loginisusekey);  // 是否使用密匙

$t->set_var("indexpagetype",$indexpagetype);  // 调用首页类型
$t->set_var("skin",$loginskin);  // 调用皮肤
$t->set_var("gotourl",$gotourl);  // 转用的地址
$t->set_var("loginuser",$loginuser); 
$t->pparse("out", "main");    # 最后输出页面
?>