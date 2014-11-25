<?
$thismenucode = "2n011";     
require("../include/common.inc.php");
$db=new db_test;



$t = new Template(".","keep");
$t->set_file("template","account.html");




	$query = "select * from tb_account left join tb_author on fd_author_id = fd_acc_authorid
	where fd_acc_id = '$listid'";
	//echo $query;
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		   $authorid         = $db->f(fd_author_id); 
						
	}
	$query = "select * from tb_account left join tb_author on fd_author_id = fd_acc_authorid
	where fd_acc_authorid = '$authorid'";
	$db->query($query);
	if($db->nf())
	{
		  while($db->next_record())
		  {
		   $authorid         = $db->f(fd_author_id); 
		   $truename         = $db->f(fd_author_truename); 
		   $money            = $db->f(fd_acc_money); 
		   $typename         = $db->f(fd_acc_typename); 
		   $content .= "<tr>
			<th>".$typename."：</th>
			<td>$money </td>
			</tr>
			";
		  }	
		}				
	

		

$checkall= '<INPUT onclick=CheckAll() type=checkbox class=checkbox value=on name=chkall>';
$arr_text = array("流水编号","类型","刷卡器设备号","收入","支出","交易方式","时间");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	$tfoot   .=' <input type="checkbox" value="1" size="1" class="checkbox" checked="checked" onClick="fnShowHide('.$i.');">'.$arr_text[$i];
}






$t->set_var("truename"     ,$truename      );      
$t->set_var("content"         , $content        );
$t->set_var("authorid"           , authorid        );
$t->set_var("listid"           , $listid        );
$t->set_var("theadth"           , $theadth     ); 
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面

?>