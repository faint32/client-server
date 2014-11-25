<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk'); 
require("../include/common.inc.php");
require_once('../include/json.php');

$db  = new DB_test;
$show_title = array(
				"fd_paycard_id"=>"操作",
				"fd_paycard_batches"=>"批次号",
				"fd_paycard_key"=>"刷卡器设备号",
				"fd_product_suppname"=>"供应商"
				);
if($showtype=="sale")
{
	$strpaycardid =getshowpaycradid("tb_salelistdetail","stdetail",$stdetail_id);//获取要显示刷卡器ID
}

if($showtype=="saleback")
{
	$show_title["fd_paycard_saleprice"]="销售价格";
	$strpaycardid= getshowpaycradid("tb_salelistbackdetail","stdetail",$stdetail_id);//获取要显示刷卡器ID
}

if($showtype=="stockback")
{
	$show_title["fd_paycard_stockprice"]="刷卡器入库价格";
	$stdetail_id=$skdetail_id;
	$strpaycardid= getshowpaycradid("tb_paycardstockbackdetail","skdetail",$stdetail_id);//获取要显示刷卡器ID
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
<th width="8%" height="30">&nbsp;</th>';
foreach($show_title as $key => $value)
{
	if($key=="fd_paycard_id")
	{
		$showcontent .='<th width="8%" class="borbtm borrtm" '.$hidded.' >'.$value.'</th>';
	}else{
		$showcontent .='<th width="8%" class="borbtm borrtm" >'.$value.'</th>';
	}
	if($str_sql)
	{
		$str_sql .=",".$key;
	}else{
		$str_sql =$key;
	}
}

$showcontent .='</thead>';

$query = "select  $str_sql from tb_paycard left join tb_product on  fd_paycard_product=fd_product_id where fd_paycard_id in ($strpaycardid)";
$db->query($query);
$allcount =  $db->nf(); 
if($db->nf())
{
	while($db->next_record()){
		$commcount++;
		$showcontent .='<tbody>
		<td>&nbsp;'.$commcount.'</td>';

		foreach($show_title as $key => $value)
		{
				if($key=="fd_paycard_id")
				{
					$showcontent .= ' <td '.$hidded.'><a href="#" onClick="delete_one(\''.$db->f($key).'\',\''.$stdetail_id.'\')">删除</a></td>';
				}else{
					$showcontent .= '<td>'.$db->f($key).'</td>';
				}
		}
		$showcontent .='</tbody>';
	}
	$succ=1;
}
else
{
	$succ=0;
}
$showcontent.='</table></td></tr>';
echo $showcontent."@@".$succ;

function getshowpaycradid($tabname,$filename,$id)//获取要显示刷卡器ID
{
	$db = new DB_test;

	$query = 'select  fd_'.$filename.'_paycardid  as paycardid from '.$tabname.' where fd_'.$filename.'_id="'.$id.'" ';
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$paycardid     = $db->f(paycardid);

		$arr_paycardid1=explode(",",$paycardid);
		foreach($arr_paycardid1 as $va)
		{
			if($strpaycardid)
			{
				$strpaycardid .=","."'$va'";
			}
			else
			{
				$strpaycardid="'$va'";
			}
		}
	}
	return $strpaycardid;
}
?>