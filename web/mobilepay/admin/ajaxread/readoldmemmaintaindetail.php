<?
 header("Content-type: text/html; charset=gbk"); 
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_erptest;
$db2 = new DB_erptest;
$db3 = new DB_erptest;







if( $loginuserqx[4009][4]==1){
$contect .= "<table width='90%'class=InputFrameMain cellspacing='0'cellpadding='0' border='0'>";
$contect .=  "<tr class=InputFrameLine>";
$contect .=  "<td height='100' width='20%' align=right class=form_label><span class='span_label'>&nbsp;回访结果记录：</span></td>";
$contect .=  "<td height='100' width='80%'  class=span_label ><textarea id='contect' style='width:80%;height:60'></textarea></td>";
$contect .=  "</tr>";
$contect .=  "<tr class=InputFrameLine>";
$contect .=  "<td height='30' width='20%' align=right class=form_label></td>";
$contect .=  "<td height='30' width='80%'  class=span_label ><input type='button' value='提交结果' onclick='tj()'/></td>";
$contect .=  "</tr>";
$contect .=  "</table>";	
}

$beginpage=($page-1)*10;	


$contect .= "<table  width='100%' border='0' cellspacing='0' cellpadding='0'>";

$k=0;
$query= "select * from tb_memberhf where fd_memberhf_memid = '$memid' order by fd_memberhf_datetime desc limit $beginpage,10
";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	$staname = $db->f(fd_memberhf_loginstaname);	
	$hfdate = $db->f(fd_memberhf_datetime);	
	$hfcontect = $db->f(fd_memberhf_contect);	
	
$contect .="<tr  height='50'>";
$contect .="<td nowrap style='padding-left:40px' >回访客服：".$staname."</td>";
$contect .="<td nowrap >回访日期：".$hfdate."</td>";
$contect .="</tr>";	

$contect .="<tr  height='40'>";
$contect .="<td id='hfwidth'   colspan=2 style='word-break:break-all;word-wrap:break-word;border-bottom:1px dotted #d1d1d1' style='padding-left:40px;color:#0000ff'>".$hfcontect."</td>";
$contect .="</tr>";	
$k++;
	}
}

if($k==0){

	$contect .="<tr><td nowrap colspan=32 height=50>没有任何数据……</td></tr>";

}	
$contect .=  "</table>";	

if($k!=0){
$query = "select * from tb_memberhf where fd_memberhf_memid = '$memid' order by fd_memberhf_datetime";
	$db->query($query);
	$zpage = ceil($db->nf()/10);

$b1= 1;
$b3 = $page+1;
if($b3>$zpage)$b3=$zpage;
$b2 = $page-1;
if($b2<1)$b2=1;
$b4 = $zpage;

$contect .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' >";
$contect .="<tr align='center' height='30' bgcolor='#e4e4e4' style='color:#000;'>";
$contect .="<td align='left' style='padding-left:10px'></td>";
$contect .="<td nowrap align='right' style='padding-right:10px'>第<span id=nowpage2>$page</span>页 <a id=b1  href='#none' onclick='loadmemdetail(\"init\",$seltype,$b1)'>首&nbsp;&nbsp;页</a> <a id=b2 href='#none' onclick='loadmemdetail(\"init\",$seltype,$b2)'>上一页</a> <a id=b3 href='#none' onclick='loadmemdetail(\"init\",$seltype,$b3)'>下一页</a>  <a id=b4 href='#none' onclick='loadmemdetail(\"init\",$seltype,$b4)'>末&nbsp;&nbsp;页</a></td>";
$contect .="</tr>";
$contect .="</table>";
}





echo $contect;
?>