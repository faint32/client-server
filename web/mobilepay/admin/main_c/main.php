<?
require ("../include/common.inc.php");
$db = new DB_test ;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("main","main.html"); 
$t->set_block("main", "MAILBK", "mailbks");  //ʹ��һ����

session_register("loginorganqx");
session_register("openmain");
session_register("loginoverkgflstid");   //�����ؿ�Ŀ
session_register("logintabshow");   //��������
session_register("loginimoneyflstid");   //Ӧ���˿�
session_register("loginomoneyflstid");   //Ӧ���˿�
session_register("loginibillflstid");   //Ӧ��Ʊ��
session_register("loginobillflstid");   //Ӧ��Ʊ��
session_register("loginpreimoneyflstid");   //Ԥ���˿�
session_register("loginpreomoneyflstid");   //Ԥ���˿�
session_register("loginvoucher");  
session_register("loginycostflstid");     //Ӧ��Ӧ��������Ŀ

session_register("loginoimoneyflstid");   //����Ӧ���˿�
session_register("loginoomoneyflstid");   //����Ӧ���˿�

session_register("loginoverkgflstid");   //�����ؿ�Ŀ

session_register("loginselpayflstid");        //Ӫҵ��֧��
session_register("loginselincomeflstid");     //Ӫҵ������

session_register("loginprocostflstid");        //��Ӫҵ�ɱ�
session_register("loginproincomeflstid");     //��Ӫҵ����

session_register("loginlirunflstid");        //��������	
session_register("loginboshouruflstid");     //����ҵ������

session_register("loginstorageflstid");   //�����Ʒ
session_register("loginzaituflstid");   //�����Ʒ
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
//    $loginimoneyflstid     = $db->f(fd_baseinfo_inmoneyflstid);   //Ӧ���˿�
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
//	$loginoimoneyflstid     = $db->f(fd_baseinfo_oinmoneyflstid);   //Ӧ���˿�
//	$loginoomoneyflstid     = $db->f(fd_baseinfo_ooutmoneyflstid);
//	$loginstorageflstid     = $db->f(fd_baseinfo_storageflstid);
//	$loginoverkgflstid      = $db->f(fd_baseinfo_overkgflstid);
//	//echo $loginstorageflstid;
//	$loginproincomeflstid      = $db->f(fd_baseinfo_proincomeflstid);   //Ӧ���˿�
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

$t->set_var("menutypephp",$menutypephp);  // ������ҳ����
$t->set_var("ishavekey",$loginisusekey);  // �Ƿ�ʹ���ܳ�

$t->set_var("indexpagetype",$indexpagetype);  // ������ҳ����
$t->set_var("skin",$loginskin);  // ����Ƥ��
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ
$t->set_var("loginuser",$loginuser); 
$t->pparse("out", "main");    # ������ҳ��
?>