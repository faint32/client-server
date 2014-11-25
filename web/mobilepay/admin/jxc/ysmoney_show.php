<?php
$thismenucode = "2k208";
require ("../include/common.inc.php");
$db = new DB_test ;
$db1 = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("ysmoney_show","ysmoney_show.html");
$t->set_block("ysmoney_show", "BXBK", "bxbks");

$allysmoney=0;
//查询所有应收款
$query = "select fd_ysyfm_companyid , fd_ysyfm_type , fd_ysyfm_money from tb_ysyfmoney left join tb_supplier on fd_supp_id = fd_ysyfm_companyid where fd_ysyfm_money>0 and fd_ysyfm_type = '2' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$linkid     = $db->f(fd_ysyfm_companyid);
		$linktype   = $db->f(fd_ysyfm_type);
		//if($db->f(ysyfmoney)>0){
		$arr_ysmoney[$linktype][$linkid]   = $db->f(fd_ysyfm_money);
		$arr_flagclient[$linktype][$linkid]=$db->f(fd_ysyfm_money);
		$allysmoney += $db->f(fd_ysyfm_money);
		//}
	}
}
//客户应收款
$query = "select fd_ysyfm_companyid , fd_ysyfm_type , fd_ysyfm_money from tb_ysyfmoney left join tb_customer on fd_cus_id = fd_ysyfm_companyid where fd_ysyfm_money>0  and fd_ysyfm_type = '1' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$linkid     = $db->f(fd_ysyfm_companyid);
		$linktype   = $db->f(fd_ysyfm_type);
		//if($db->f(ysyfmoney)>0){
		$arr_ysmoney[$linktype][$linkid]   = $db->f(fd_ysyfm_money);
		$arr_flagclient[$linktype][$linkid]=$db->f(fd_ysyfm_money);
		$allysmoney += $db->f(fd_ysyfm_money);
		//}
	}
}

for($i=1;$i<=2;$i++)
{
	while(list($key,$val)=@each($arr_flagclient[$i])){
		$linkid = $key;
		$linktype = $i;
		$custype  = $i;
		$ysmoney   = $arr_ysmoney[$linktype][$linkid];
		$billmoney = $arr_billmoney[$linktype][$linkid];

		$itemid = $linkid."&".$linktype;

		$arr_client[$itemid]["linktype"]  = $linktype;
		$arr_client[$itemid]["linkid"]    = $linkid;
		$arr_client[$itemid]["ysmoney"]   = $ysmoney;
	}
}


if(count($arr_client) > 0){
	foreach($arr_client as $key => $row)
	{
		$volume1[$key]  = $row['ysmoney'];
	}
	array_multisort($volume1, SORT_DESC, $arr_client);
}

$count    == 0;
$linktype  = 0;
$ysmoney   = 0;
$billmoney = 0;
$linkid    = 0;

while(list($key,$val)=@each($arr_client))
{

	$itemid =  $key;

	$linktype  = $arr_client[$itemid]["linktype"];
	$custype   = $arr_client[$itemid]["linktype"];
	$ysmoney   = $arr_client[$itemid]["ysmoney"];
	$linkid    = $arr_client[$itemid]["linkid"];

	if($linktype==1){
		$linktype = "客户";
		$query = "select * from tb_customer where fd_cus_id = '$linkid'";
		$db1->query($query);
		if($db1->nf())
		{
			$db1->next_record();
			$linkname = $db1->f(fd_cus_allname);
			$linkno   = $db1->f(fd_cus_no);
		}
	}elseif($linktype==2){
		$linktype = "供应商";
		$query = "select * from tb_supplier where fd_supp_id = '$linkid'";
		$db1->query($query);
		if($db1->nf())
		{
			$db1->next_record();
			$linkname = $db1->f(fd_supp_allname);
			$linkno   = $db1->f(fd_supp_no);
		}
	}else{
		$linktype="";
	}
	$strurl = "currentaccount_show.php?clienttype=".$custype."&cusid=".$linkid."&backurl=ys";
	$ysmoney = number_format($ysmoney, 2, ".", "");
	if($ysmoney>0 )
	{
		$count++ ;
		$t->set_var(array(
			"linkname"     =>$linkname   ,
			"linktype"     => $linktype   ,
			"billmoney"    => $billmoney  ,
			"linkno"       => $linkno     ,
			"linkid"       => $linkid     ,
			"custype"      => $custype    ,
			"strurl"       => $strurl     ,
			"tbcount"      => $count      ,
			"ysmoney"      => $ysmoney
		));
		$t->parse("bxbks", "BXBK", true);
	}
}

if($count==0){
	$t->set_var(array(
		"linkname"     => ""  ,
		"linktype"     => ""  ,
		"billmoney"    => ""  ,
		"linkno"       => ""  ,
		"linkid"       => ""  ,
		"custype"      => ""  ,
		"strurl"       => ""  ,
		"tbcount"      => ""  ,
		"ysmoney"      => ""
	));
	$t->parse("bxbks", "BXBK", true);
}


$byear = date("Y", mktime()) ;
$bmonth = date("m", mktime()) - 1 ;
$bday = date("d", mktime()) ;

$eyear = date("Y", mktime()) ;
$emonth = date("m", mktime()) ;
$eday = date("d", mktime()) ;

if($bmonth==0)
{
	$bmonth = 12;
	$byear = $byear-1;
}

$allysmoney = number_format($allysmoney, 2, ".", ",");
$allbillmoney = number_format($allbillmoney, 2, ".", ",");

$begindate = $byear."-".$bmonth ."-".$bday;
$enddate = $eyear."-".$emonth ."-".$eday;
include("../include/checkqx.inc.php") ;
$t->set_var("begindate",$begindate);
$t->set_var("enddate",$enddate);
$t->set_var("allysmoney",$allysmoney);
$t->set_var("allbillmoney",$allbillmoney);
$t->set_var("checked1",$checked1);
$t->set_var("checked2",$checked2);
$t->set_var("checked3",$checked3);
$t->set_var("count",$count);
$t->set_var("error",$error);
$t->set_var("skin",$loginskin);
$t->pparse("out", "ysmoney_show");    # 最后输出页面
?>