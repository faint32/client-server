<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk'); 
require("../include/common.inc.php");
require_once('../include/json.php');

$db  = new DB_test;
$show_title = array(
				"fd_paycard_id"=>"����",
				"fd_paycard_batches"=>"���κ�",
				"fd_paycard_key"=>"ˢ�����豸��",
				"fd_product_suppname"=>"��Ӧ��"
				);
if($showtype=="sale")
{
	$strpaycardid =getshowpaycradid("tb_salelistdetail","stdetail",$stdetail_id);//��ȡҪ��ʾˢ����ID
}

if($showtype=="saleback")
{
	$show_title["fd_paycard_saleprice"]="���ۼ۸�";
	$strpaycardid= getshowpaycradid("tb_salelistbackdetail","stdetail",$stdetail_id);//��ȡҪ��ʾˢ����ID
}

if($showtype=="stockback")
{
	$show_title["fd_paycard_stockprice"]="ˢ�������۸�";
	$stdetail_id=$skdetail_id;
	$strpaycardid= getshowpaycradid("tb_paycardstockbackdetail","skdetail",$stdetail_id);//��ȡҪ��ʾˢ����ID
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
					$showcontent .= ' <td '.$hidded.'><a href="#" onClick="delete_one(\''.$db->f($key).'\',\''.$stdetail_id.'\')">ɾ��</a></td>';
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

function getshowpaycradid($tabname,$filename,$id)//��ȡҪ��ʾˢ����ID
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