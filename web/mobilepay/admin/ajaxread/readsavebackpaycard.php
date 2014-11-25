<?
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk'); 
require("../include/common.inc.php");
require_once('../include/json.php');

$db  = new DB_test;

$query = "select  fd_stdetail_paycardid from tb_salelistbackdetail where fd_stdetail_id='$stdetail_id'";
$db->query($query); 
if($db->nf())
{
	while($db->next_record()){		     
     $paycardid     = $db->f(fd_stdetail_paycardid); 
  }
}
$arr_paycardid1=explode(",",$paycardid);
foreach($arr_paycardid1 as $va)
{
	if($strpaycardid)
{
	$strpaycardid .=","."'$va'";
}else{
	$strpaycardid="'$va'";
}
}
if($type=="check")
{
$hidded="style=display:none";
}
$showcontent='
<tr id="'.$paycard_id.'">
<td colspan="6">
<table width="100%" calss="table">
<thead>
<th width="8%" height="30">&nbsp;</th>
<th width="32%" class="borbtm borrtm">批次号</th>
<th width="18%" class="borbtm borrtm">刷卡器设备号</th>
<th width="18%" class="borbtm borrtm">销售价格</th>
<th width="19%" class="borbtm borrtm">供应商</th>
<th width="8%" class="borbtm borrtm" '.$hidded.' >操作</th>
</thead>';


$comm_checked = "";
if($ischeck=='1'){
  $comm_checked = " checked='checked'";  
}
$query = "select  fd_product_suppname,fd_paycard_batches,fd_paycard_key,fd_paycard_id fd_paycard_saleprice from tb_paycard 
		left join tb_product on  fd_paycard_product=fd_product_id
where fd_paycard_id in ($strpaycardid)";
$db->query($query);
$allcount =  $db->nf(); 
if($db->nf())
{
	while($db->next_record()){		     
     $suppname  = $db->f(fd_product_suppname); 
	  $batches         = $db->f(fd_paycard_batches);
	   $paycardkey     = $db->f(fd_paycard_key);
	   $paycardid     = $db->f(fd_paycard_id);
	   $saleprice     = $db->f(fd_paycard_saleprice);
		 $commcount++;  
		$showcontent .='<tbody>
		   <td>&nbsp;'.$commcount.'</td>
		    <td>'.$batches.'</td>
		   <td>'.$paycardkey.'</td>
		   <td>'.$saleprice.'</td>
		   <td>'.$suppname.'</td>
		   <td '.$hidded.'><a href="#" onClick="delete_one(\''.$paycardid.'\',\''.$stdetail_id.'\')">删除</a></td>
          </tbody>';
  }
  $succ=1;
}else{ $succ=0;}
$showcontent.='</table></td></tr>';
echo $showcontent."@@".$succ;


	
	


?>