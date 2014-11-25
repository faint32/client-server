<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

if($datetime)
{
	switch($type)
	{
		case 'day':
		$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y-%m-%d')='$datetime'";
		break;
		case 'month':
		$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')='$datetime'";
		break;
		case 'year':
		$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y')='$datetime'";
		break;
	}
	
	switch($collect)
	{
		case 'day':
		$arr_datetime=explode("@@",$datetime);
		if(count($arr_datetime)>1)
		{
			$start=$arr_datetime[0];
			$end=$arr_datetime[1];
			$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')>='$start' and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')<='$end'";
		}else{
			$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')='$datetime'";
		}
		break;
		case 'month':
		$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y')='$datetime'";
		break;
	}
}




$aColumns = array("","fd_paycard_key");

$sSearch=u2g($sSearch);
$sWhere = "";
if ($sSearch != "" )
{
	$sWhere = "and  (";
	for ( $i=1 ; $i<count($aColumns) ; $i++ )
	{
		$sWhere .= $aColumns[$i]." LIKE '%".trim($sSearch)."%' OR ";
	}
	$sWhere = substr_replace( $sWhere, "", -3 );
	$sWhere .= ')';
}

/* Individual column filtering */
for ( $i=1 ; $i<count($aColumns) ; $i++ )
{
	$b_s="bSearchable_".$i;
	$s_s="sSearch_".$i;
	if ( $$b_s == "true" && $$s_s != '' )
	{
		if ( $sWhere == "" )
		{
			$sWhere = "AND ";
		}
		else
		{
			$sWhere .= " AND ";
		}
		$sWhere .= $aColumns[$i]." LIKE '%".trim($$s_s)."%' ";
	}
}

if($paytype)
{
	$paytypewhere="and fd_agpm_paytype='$paytype'";
	
}
$query = "select
				fd_appmnu_name  as	 paytype,
				fd_agpm_paytype as paytypee,
				fd_agpm_paydate as paydate,
				fd_agpm_paymoney as paymoney,
				fd_agpm_agentmoney as  agentmoney,
				fd_paycard_key as showname ,
				fd_agpm_bkordernumber as bkordernumber,
				(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as  linru,
				fd_agpm_payfee  as  payfee,
				fd_agpm_arrivedate as arrivedate
				from tb_agentpaymoneylist 
				left join tb_paycard on fd_paycard_id = fd_agpm_paycardid 
				left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
				where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1  $datewhere  $paytypewhere   $sWhere  order by fd_agpm_paydate desc  ";
$db->query($query);

$arr_result = $db->getFiledData();
foreach($arr_result as $value)
{
		$tempallpaymoney += $value['paymoney'];
		$tempallagentmoney +=$value['agentmoney'];
		$tempallpayfee +=$value['payfee'];
		$tempalllinru +=$value['linru'];
}
$totoalcount=$db->nf()+0;
$count=0;
$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);


if($db->nf())
{
	while($db->next_record())
	{
		
		$showname = $db->f(showname);
		$paymoney = $db->f(paymoney);
		$paytype = $db->f(paytype);
		$paydate = $db->f(paydate);
		$bkordernumber = $db->f(bkordernumber);
		$linru = $db->f(linru);
		$payfee = $db->f(payfee);
		$arrivedate = $db->f(arrivedate);
		$paytypee = $db->f(paytypee);
		$agentmoney = $db->f(agentmoney);


		$arr_list[] = array(
				"DT_RowId" => $vid ,
				"DT_RowClass" => "",
				g2u($showname),
				"<a herf=\"#\" onclick=\"s_addpro(this);\" paytype=\"$paytypee\">".$bkordernumber."</a>",
				g2u($paytype),
				$paydate,
				"<span style='text-align:right;display:block;'>".$paymoney."</span>",
				"<span style='text-align:right;display:block;'>".$agentmoney."</span>",
				"<span style='text-align:right;display:block;'>".$payfee."</span>",
				"<span style='text-align:right;display:block;'>".$linru."</span>",
				$arrivedate
		);
	}

	$arr_list[count($arr_list)] = array(
		"DT_RowId" => "" ,
		"DT_RowClass" => "",
		g2u('合共'),
		"",
		"",
		"",
		"<span style='text-align:right;display:block;'>".$tempallpaymoney."</span>",
		"<span style='text-align:right;display:block;'>".$tempallagentmoney."</span>",
		"<span style='text-align:right;display:block;'>".$tempallpayfee."</span>",
		"<span style='text-align:right;display:block;'>".$tempalllinru."</span>",
		""
	);
}
else
{
	 $arr_list[] = array(
	 	"DT_RowId" => "" ,
		"DT_RowClass" => "",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		""
	 );
	$vmember = "暂无数据";
}
$returnarray['sEcho']=intval($sEcho);
$returnarray['iTotalRecords']=$totoalcount;
$returnarray['iTotalDisplayRecords']=$totoalcount;
$returnarray['aaData']=$arr_list;
$returnvalue = json_encode($returnarray);
echo json_encode($returnarray);
?>