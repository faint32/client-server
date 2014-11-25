<?
 header("Content-type: text/html; charset=gbk"); 
require ("../include/common.inc.php");
require ("../function/changekg.php"); 

$db = new DB_test;
$db2 = new DB_test;
$db3 = new DB_test;
//当月天数
$arr = explode("-",$cxdate);
$days = date("t", mktime(0,0,0,$arr[1],'01',$arr[0]));

$contect = "<table width='100%' border='1' cellspacing='0' cellpadding='0'  bordercolor='#d1d1d1' style=' border-collapse:collapse'>";
$contect .="<tr align='center' height='30' bgcolor='#8caae7' style='color:#fff;font-weight:bold;position:relative;z-index:1;top: expression(this.offsetParent.scrollTop)'>";
$contect .="<td nowrap >商品名称</td>";
for($i=1;$i<=$days;$i++){
$contect .="<td nowrap width='70'>".$i."号</td>";
}
$contect .="</tr>";

$query = "select fd_organmem_comnpany from tb_organmem where fd_organmem_id = '$memid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
		$memname = $db->f(fd_organmem_comnpany);
}

$query = "select fd_orderdetail_productid,fd_orderdetail_productname,DATE_FORMAT(fd_order_date,'%d') as sday,
          fd_orderdetail_quantity,fd_unit_name,fd_produre_relation3
          from web_orderdetail
          left join web_order on fd_order_id = fd_orderdetail_orderid
          left join tb_produre on fd_produre_id = fd_orderdetail_productid
          left join tb_normalunit on fd_unit_id = fd_produre_unit
          where (fd_order_state = 6 or fd_order_state = 7) and fd_order_memeberid = '$memid' and fd_order_date like '$cxdate%'
";

$db->query($query);
if($db->nf()){
 while($db->next_record()){
 	 
 	$commid = $db->f(fd_orderdetail_productid);
  $arr_commname[$commid] = $db->f(fd_orderdetail_productname);
  $sday = $db->f(sday);
  $quantity = $db->f(fd_orderdetail_quantity);
  $unit = $db->f(fd_unit_name);
  $relation3 = $db->f(fd_produre_relation3);
  $dunshu = changekg($relation3 , $unit , $quantity);
  
  $arr_dun[$commid][$sday] += $dunshu;
  $arr_cs[$commid][$sday]++;
}
}else{
	$contect .="<td nowrap colspan=32 height=50>没有任何数据……</td>";
}

while(list($commid,$val)=@each($arr_commname)){
	$commname = $arr_commname[$commid];
	
	$contect .="<tr align='center' height='30'>";
	$contect .="<td nowrap>".$commname."</td>";
	for($i=1;$i<=$days;$i++){
		if($i<10){
			$a="0".$i;
		}else{
			$a = $i;
		}
		$dun = $arr_dun[$commid][$a];
		$cs = $arr_cs[$commid][$a];
		if($cs==""){
			$contect .="<td nowrap width='70'>&nbsp;</td>";
		}else{
		$contect .="<td nowrap width='70'>".number_format($dun,4,".","")."吨<br> /".$cs."次</td>";
	}
	}
	$contect .="</tr>";
}

if($contect<>""){
$contect .="</table>";
}

$pevdate = date("Y-m", mktime(0,0,0,$arr[1],'0',$arr[0]));
$nextdate = date("Y-m", mktime(0,0,0,$arr[1]+1,'01',$arr[0]));

$showpages.="</select>";
$showpage = "<table width='100%' border='0' cellspacing='0' cellpadding='0' >";
$showpage .="<tr align='center' height='30' bgcolor='#e4e4e4' style='color:#000;'>";
$showpage .="<td nowrap align='left' style='padding-right:10px'><a href='javascript:readmembuydetail(\"".$memid."\",\"".$pevdate."\")'><< $pevdate</a></td>";
$showpage .="<td align='center'>".$arr[0]."年".$arr[1]."月".$memname."购买明细&nbsp;&nbsp;&nbsp;&nbsp;<a id=fh href='#none'>返回</a></td>";
$showpage .="<td nowrap align='right' style='padding-right:10px'><a href='javascript:readmembuydetail(\"".$memid."\",\"".$nextdate."\")'>$nextdate >></a></td>";
$showpage .="</tr>";
$showpage .="</table>";

echo $contect."@@".$showpage;
?>