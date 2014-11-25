<?
$thismenucode = "2k226";
require ("../include/common.inc.php");
$db = new DB_test ;
$db1 = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("paycardvalue","paycardvalue.html");
$t->set_block("paycardvalue", "BXBK", "bxbks");


//查询所有供应商应付款
$query = "select fd_product_id , fd_product_name , count(fd_paycard_key) as paycardnum ,fd_sect_cost from tb_product 
          left join tb_paycard on fd_paycard_product = fd_product_id
		  left join tb_storagecost on fd_sect_commid = fd_paycard_product
          group by  fd_paycard_product,fd_product_name order by fd_product_id asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$productid    = $db->f(fd_product_id);
		$productname  = $db->f(fd_product_name);
	  	$paycardnum   = $db->f(paycardnum);
		$paycardcost  =$db->f(fd_sect_cost);
		$allyspaycardnum += $db->f(paycardnum);
		$t->set_var("productid",$productid);
		$t->set_var("productname",$productname);
		$t->set_var("paycardnum",$paycardnum);
		$t->set_var("paycardcost",$paycardcost);
		$t->parse("bxbks", "BXBK", true);
	}
}

$byear = date("Y", mktime()) ;
$bmonth = date("m", mktime()) - 1 ;
$bday = date("d", mktime()) ;

$eyear = date("Y", mktime()) ;
$emonth = date("m", mktime()) ;
$eday = date("d", mktime()) ;

if($bmonth==0){
  $bmonth = 12;
  $byear = $byear-1;
}

$allyspaycardnum = number_format($allyspaycardnum, 2, ".", "");

$begindate = $byear."-".$bmonth ."-".$bday;
$enddate = $eyear."-".$emonth ."-".$eday;

$t->set_var("allyspaycardnum",$allyspaycardnum);

$t->set_var("begindate",$begindate);
$t->set_var("enddate",$enddate);
$t->set_var("checked1",$checked1);
$t->set_var("checked2",$checked2);
$t->set_var("checked3",$checked3);

$t->set_var("allyspaycardnum",$allyspaycardnum);
$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("skin",$loginskin);
$t->pparse("out", "paycardvalue");    # 最后输出页面


?>