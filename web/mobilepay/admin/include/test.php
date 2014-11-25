<?php
require ("../include/common.inc.php");
$db = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;

$dberp = new DB_erptest;

$query = "select * from web_order where fd_order_seltdate='2012-12-04' and fd_order_zf=0";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$orderid = $db->f(fd_order_id);
		$arr_web[$orderid]  = $db->f(fd_order_allcost);
			$sumweb+=number_format($db->f(fd_order_allcost),2,".","");
	}
}

$query = "select * from tb_salelist where fd_selt_date='2012-12-04' and fd_selt_organid = 1 ";
$dberp->query($query);
if($dberp->nf()){
	while($dberp->next_record()){
		$orderid = $dberp->f(fd_selt_weborderid);
		$arr_erp[$orderid] = number_format($dberp->f(fd_selt_allcost),2,".","");
		
		$sumerp+=number_format($dberp->f(fd_selt_allcost),2,".","");
	}
}
echo $sumweb."##".$sumerp."<br>";
while(list($orderid,$val)=@each($arr_web)){
	if($arr_web[$orderid]<>$arr_erp[$orderid]){
		echo $orderid."&&".$arr_web[$orderid]."!=".$arr_erp[$orderid]."<br>";
	}
}


?>