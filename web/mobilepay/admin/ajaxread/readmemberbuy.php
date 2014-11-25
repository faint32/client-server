<?
 header("Content-type: text/html; charset=gbk"); 
require ("../include/common.inc.php");

$db = new DB_test;
$db2 = new DB_test;
$db3 = new DB_test;


if($sorttype=="desc"){
	$$sort="▼";
}else{
  $$sort="▲";
}

$contect = "<table  width='100%' border='1' cellspacing='0' cellpadding='0'  bordercolor='#d1d1d1' style='border-collapse:collapse;'>";
$contect .="<tr   align='center' height='30' bgcolor='#8caae7' style='color:#fff;font-weight:bold;position:relative;'>";
$contect .="<td nowrap width='40' >序号</td>";
$contect .="<td nowrap width='270'>客户名称</td>";
$contect .="<td nowrap style='cursor:hand' onclick=loadmemberbuy('resort','ds',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>单数<span id=ds>$ds</span></td>";
$contect .="<td  nowrap style='cursor:hand' onclick=loadmemberbuy('resort','alldunshu',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>总吨数<span id=alldunshu>$alldunshu</span></td>";
$contect .="<td  nowrap style='cursor:hand' onclick=loadmemberbuy('resort','beginbuy',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>初次购买时间<span id=beginbuy>$beginbuy</span></td>";
$contect .="<td  nowrap style='cursor:hand' onclick=loadmemberbuy('resort','endbuy',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>最近购买时间<span id=endbuy>$endbuy</span></td>";
$contect .="<td  nowrap style='cursor:hand' onclick=loadmemberbuy('resort','xcday',document.getElementById('nowpage').value,document.getElementById('pagecount').value)>超过几天没有购买<span id=xcday>$xcday</span></td>";
$contect .="<td nowrap>所属网导员</td>";
$contect .="<td nowrap>所属网导经理</td>";
$contect .="<td nowrap>操作区</td>";
$contect .="</tr>";





		$beginpage=($page-1)*$pagecount;
	
	
	$query = "select count(*) as ds,fd_order_memeberid,sum(fd_order_alldunshu) as alldunshu,fd_order_memeberid,min(fd_order_date) as beginbuy,max(fd_order_date) as endbuy,datediff(NOW() , max(fd_order_date)) as xcday from web_order where (fd_order_state = 6 or fd_order_state = 7) group by fd_order_memeberid order by $sort $sorttype limit $beginpage,$pagecount";

	$db->query($query);
	if($db->nf()){
		$a=0;
		while($db->next_record()){
			$memid = $db->f(fd_order_memeberid);
		if($a==0){
			$qq = " and (fd_organmem_id='$memid'";
		}else{
			$qq .= " or fd_organmem_id='$memid'";
		}
		$arr_ds[$memid] = $db->f(ds);
		$arr_dunshu[$memid] = $db->f(alldunshu);
		$arr_beginbuy[$memid] = $db->f(beginbuy);
		$arr_endbuy[$memid] = $db->f(endbuy);
		$arr_xcday[$memid] = $db->f(xcday);
		$a++;
	}
	if($qq<>"")$qq.=")";
	}
	if($sort=="ds"){
		$arr_show = 	$arr_ds;
  }
  if($sort=="alldunshu"){
		$arr_show = 	$arr_dunshu;
  }
  if($sort=="beginbuy"){
		$arr_show = 	$arr_beginbuy;
  }
  if($sort=="endbuy"){
		$arr_show = 	$arr_endbuy;
  }
 if($sort=="xcday"){
		$arr_show = 	$arr_xcday;
 }

//会员资料
$query = "select fd_organmem_id,fd_organmem_comnpany,dg.fd_saler_truename as sdg,jl.fd_saler_truename as sjl from tb_organmem 
left join web_salercard on fd_salercard_id = fd_organmem_mcardid
left join web_saler as dg on dg.fd_saler_id =fd_salercard_salerid
left join web_saler as jl on jl.fd_saler_id =dg.fd_saler_sharesalerid
where 1 $qq";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$memid = $db->f(fd_organmem_id);
		$arr_memname[$memid]=$db->f(fd_organmem_comnpany);
		$arr_dg[$memid]=$db->f(sdg);
		$arr_jl[$memid]=$db->f(sjl);
	}
}

//输出
$s=$beginpage+1;

while(list($memid,$val)=@each($arr_show)){
 $memname = $arr_memname[$memid];
 $ds = $arr_ds[$memid];
 $dunshu = $arr_dunshu[$memid];
 $dg = $arr_dg[$memid];
 $jl = $arr_jl[$memid];
 $beginbuy = $arr_beginbuy[$memid];
 $endbuy = $arr_endbuy[$memid];
 $xcday = $arr_xcday[$memid];
 

$contect .="<tr align='center' height='30'>";
$contect .="<td nowrap>".$s."</td>";
$contect .="<td nowrap>".$memname."</td>";
$contect .="<td nowrap>".$ds."</td>";
$contect .="<td nowrap>".$dunshu."</td>";
$contect .="<td nowrap>".$beginbuy."</td>";
$contect .="<td nowrap>".$endbuy."</td>";
$contect .="<td nowrap>".$xcday."</td>";
$contect .="<td nowrap>".$dg."</td>";
$contect .="<td nowrap>".$jl."</td>";
$contect .="<td nowrap><a href='javascript:showdetaillayer($memid,\"$memname\")'>查看月明细</a></td>";
$contect .="</tr>";


$s++;

}
if($s==""){

	$contect .="<td nowrap colspan=32 height=50>没有任何数据……</td>";

}

	$query = "select count(*) as ds,fd_order_memeberid,sum(fd_order_alldunshu) as alldunshu,fd_order_memeberid,min(fd_order_date) as beginbuy,max(fd_order_date) as endbuy,datediff(max(fd_order_date), min(fd_order_date)) as xcday from web_order where (fd_order_state = 6 or fd_order_state = 7) group by fd_order_memeberid ";
	$db->query($query);
	$zpage = ceil($db->nf()/$pagecount);


if($act=="init"){
	$arr_name="";
$arr_id="";

$arr_id=array("15","30","50","100","200");	
$arr_name=array("-15-","-30-","-50-","-100-","-200-");	

$pc = makeselect($arr_name,$pagecount,$arr_id);
	
$showpages.="</select>";
$showpage = "<table width='100%' border='0' cellspacing='0' cellpadding='0' >";
$showpage .="<tr align='center' height='30' bgcolor='#e4e4e4' style='color:#000;'>";
$showpage .="<td align='left' style='padding-left:10px'>每页记录数：<select id='pagecount' onchange=loadmemberbuy('cc',document.getElementById('sort').value,1,this.value)>".$pc."</select></td>";
$showpage .="<td nowrap align='right' style='padding-right:10px'>第<span id=nowpage2></span>页 <a id=b1  href='#none'>首&nbsp;&nbsp;页</a> <a id=b2 href='#none'>上一页</a> <a id=b3 href='#none'>下一页</a>  <a id=b4 href='#none'>末&nbsp;&nbsp;页</a></td>";
$showpage .="</tr>";
$showpage .="</table>";
}

if($contect<>""){
$contect .="</table>";

}
echo $contect."@@".$showpage."@@".$zpage;
?>