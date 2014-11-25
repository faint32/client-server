<?
$thismenucode = "2k103";
require ("../include/common.inc.php");

$db = new db_test ( );
$gotourl = "../jxcbasic/tb_customer_b.php";
$actionurl='teller.php';
$t = new Template ( ".", "keep" );

$t->set_file ( "template", "tellerlist.html" );


$query="select fd_cus_name from tb_customer where fd_cus_id='$listid'";
$db->query($query);
if($db->nf())
{
	$db->next_record();
	
		$cusname=$db->f(fd_cus_name);
	
}

$arr_text = array ("<INPUT onclick=CheckAll(this.form) type=checkbox value=on name=chkall>	","用户名", "用户状态", "操作" );

for($i = 0; $i < count ( $arr_text ); $i ++) {
	$theadth .= ' <th>' . $arr_text [$i] . '</th>';
}
//显示列表
$t->set_block("template", "prolist"  , "prolists"); 
$query="select * from tb_cus_teller where fd_tel_cusid='$listid'";
$count=0;//记录数
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		
		$telid=$db->f(fd_tel_id);
		$telname=$db->f(fd_tel_name);
		$recsts=$db->f(fd_tel_recsts);
		if($recsts==1){$recsts="暂停";}else{$recsts="正常";}
		$count++;
	   $t->set_var(array(
			 "telid"        => $telid         ,
			 "telname"      => $telname       ,
			 "recsts"       => $recsts    ,
			 "rowcount"     => $count         ,					             
			  ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
		$t->parse("prolists", "", true);
}

$t->set_var ("theadth", $theadth );
$t->set_var ("cusname", $cusname );
$t->set_var ("listid", $listid );
$t->set_var ("actionurl", $actionurl );

include ("../include/checkqx.inc.php");
$t->set_var ("gotourl", $gotourl );
$t->set_var ("skin", $loginskin );
$t->pparse ("out", "template" );

?>