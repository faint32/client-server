<?
 header("Content-type: text/html; charset=gbk"); 
require ("../include/common.inc.php");

$db = new DB_test;
$db2 = new DB_test;
$count=0;
$s=0;

$show = "<table width='700' border='0' cellspacing='1'  class=tableborder  >
<tr class=listtitle >
<td align='center' class='white_word' nowrap>时间</td> 
<td align='center' class='white_word' nowrap>操作人</td>
<td align='center' class='white_word' nowrap>备注</td>
</tr>
	";

$query = "select * from web_fpsaveglide 
          where fd_fpsaveglide_listid = '$listid' 
          order by fd_fpsaveglide_datetime desc limit $start,$pagecount ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		   $datetime = $db->f(fd_fpsaveglide_datetime);
		   $man = $db->f(fd_fpsaveglide_man);
       $memo      = $db->f(fd_fpsaveglide_memo);
          
       $show .= "<tr onMouseOver='this.style.backgroundColor=\"#ededed\"' onMouseOut='this.style.backgroundColor=\"#ffffff\"' bgcolor='#ffffff'  style='cursor:pointer' >";		   		   
		   $show .= "<td align='center'><font style='font-size:12px' nowrap>".$datetime."</font></td>";
		   $show .= "<td align='center'><font style='font-size:12px' nowrap>".$man."</font></td>";
		   $show .= "<td align='left'><font style='font-size:12px'>".$memo."</font></td>"; 
       $show .= "</tr>";
       
       
	}
}
$show .= "</table>";
$query = "select * from web_fpsaveglide 
where fd_fpsaveglide_listid = '$listid' order by fd_fpsaveglide_datetime desc ";
$db->query($query);
$count = $db->nf();

$havepage = ceil($count/$pagecount);

//翻页
$pevb=$start-$pagecount;
if($pevb<0)$pevb=0;
$nextb=$start+$pagecount;
if($nextb>=$count){
	$nextb=$start;
}
$pg.="<a href='#none' onclick='readglide($pevb,$pagecount><font style='font-size:12px'>上一页</font></a>&nbsp;&nbsp;<a href='#none' onclick='readglide($nextb,$pagecount)' ><font style='font-size:12px'>下一页</font></a>";



echo $show."@@@".$pg;
?>