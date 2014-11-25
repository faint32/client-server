<?
$thismenucode = "6n003";     
require("../include/common.inc.php");
$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gourl = "salerreward.php";
if($money)
{
	$arr_money=explode("/",$money);
}

switch($act)
{
	case "new":
	
	$query = "insert into tb_salerrewards(fd_rewards_selfmoney,fd_rewards_tcsendtime,fd_rewards_allmoney,
										  fd_rewards_bmonth,fd_rewards_emonth,fd_rewards_sendphonemsg)
	          values('$arr_money[0]','$tcsendtime','$arr_money[1]','$bmonth','$emonth','$sendphonemsg')";
	$db->query($query); 
	echo "<script>alert('保存成功!');location.href='$gourl'</script>";
	break;
	case "edit":
	$query = "update tb_salerrewards set 
	          fd_rewards_selfmoney ='$arr_money[0]', fd_rewards_tcsendtime ='$tcsendtime',fd_rewards_allmoney ='$arr_money[1]',
			  fd_rewards_bmonth='$bmonth'       , fd_rewards_emonth='$emonth'      ,fd_rewards_sendphonemsg='$sendphonemsg'
			  where fd_rewards_id  = '$rewardsid'"; 
	$db->query($query);
	echo "<script>alert('修改成功!');location.href='$gourl'</script>";	
	break;

}

$t = new Template(".","keep");
$t->set_file("template","salerreward.html");
$query = "select * from  tb_salerrewards ";
$db->query($query);
if($db->nf()){             
$db->next_record();
$rewardsid=$db->f(fd_rewards_id);
$selfmoney=$db->f(fd_rewards_selfmoney);
$allmoney=$db->f(fd_rewards_allmoney);
$bmonth=$db->f(fd_rewards_bmonth);
$emonth=$db->f(fd_rewards_emonth);
$tcsendtime=$db->f(fd_rewards_tcsendtime);
$sendphonemsg=$db->f(fd_rewards_sendphonemsg);

if($selfmoney!="0")
{
	$money=$selfmoney."/".$allmoney;
}
$act="edit";
}else{$act="new";}

for($i=1;$i<=31;$i++)
{
	$arr_month[]=$i;
}

$tcsendtime=makeselect($arr_month,$tcsendtime,$arr_month); 

$t->set_var("rewardsid",$rewardsid);
$t->set_var("money",$money);
$t->set_var("tcsendtime",$tcsendtime);
$t->set_var("bmonth",$bmonth);
$t->set_var("emonth",$emonth);
$t->set_var("sendphonemsg",$sendphonemsg);
$t->set_var("act",$act);
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面



?>