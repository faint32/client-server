<?
session_start();
////require ("../include/log.php");
//require ("../include/get.variable.inc.php");

if(empty($loginuser) || empty($loginname)){
  //Header("Location: ../index.php");
  echo "<script>parent.location='../index.php';</script>";
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
	include ("../include/config.inc.php");
}else{
	include ("../include/config.inc.php");
}
require ("../include/template.inc.php");


$loginonlinedb = new DB_test ;
$query = "select * from tb_teller  where fd_tel_id = '$loginuser' 
          and fd_tel_onlinetime = '$loginonlinetime' ";
$loginonlinedb->query($query);
if($loginonlinedb->nf()<=0){
  echo "<script>alert('ϵͳ����ص�¼���㱻ǿ�����ߣ�');</script>";
  echo "<script>parent.location='../index.php';</script>";
}


$query = "select * from tb_opendate where fd_opendate_date <> '$loginopendate'";
$loginonlinedb->query($query);
if($loginonlinedb->nf()){
	$loginonlinedb->next_record();
	$loginopendate = $loginonlinedb->f(fd_opendate_date);
	
}

?>