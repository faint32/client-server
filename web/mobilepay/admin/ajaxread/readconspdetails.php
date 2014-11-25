<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;
$tempallpaymoney = "";
$tempallpayfee = "";
$tempalllinru = "";

if($type=='paycard' or $type=='payfee' or $type=='checkdetail' )
{
	$search="fd_paycard_key";
	$showsqlid='fd_agpm_paycardid';
	$joinid='fd_paycard_id';
	$showtab='tb_paycard';
}

if($type=='author')
{
	$search="fd_author_truename";
	$showsqlid='fd_agpm_authorid';
	$joinid='fd_author_id';
	$showtab='tb_author';
}

if($type=='sdcr')
{
	$search="fd_sdcr_name";
	$showsqlid='fd_agpm_sdcrid';
	$joinid='fd_sdcr_id';
	$showtab='tb_sendcenter';
}

if($type=='checkdetail')
{
	$nowdate=date("Y-m",time());
	$datewhere="and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')='$nowdate'";
}

$aColumns = array("",$search);

$sSearch=u2g($sSearch);
$sWhere = "";

if ($sSearch != "" )
{
	$sWhere = "and  (";
	for ( $i=1 ; $i<count($aColumns) ; $i++ )
	{
		$sWhere .= $aColumns[$i]." LIKE '%".trim($sSearch)."%' OR ";
	}
	$sWhere = substr_replace( $sWhere , "" , -3 );
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
	$paytypewhere = "and fd_agpm_paytype='$paytype'";
}

if($listid)
{
	$query = "select
		fd_appmnu_name  as	 paytype,
		fd_agpm_paytype as paytypee,
		fd_agpm_paydate as paydate,
		fd_agpm_paymoney as paymoney,
		$search as showname ,
		fd_agpm_bkordernumber as bkordernumber,
		(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as  linru,
		fd_agpm_payfee  as  payfee,
		fd_agpm_arrivedate as arrivedate
		from tb_agentpaymoneylist 
		left join $showtab on $joinid = $showsqlid 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
		where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1  and  $showsqlid='$listid' $datewhere  $paytypewhere   $sWhere   ";
}
else
{
	$query = "select
		fd_appmnu_name  as	 paytype,
		fd_agpm_paytype as paytypee,
		fd_agpm_paydate as paydate,
		fd_agpm_paymoney as paymoney,
		$search as showname ,
		fd_agpm_bkordernumber as bkordernumber,
		(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as  linru,
		fd_agpm_payfee  as  payfee,
		fd_agpm_arrivedate as arrivedate
		from tb_agentpaymoneylist 
		left join $showtab on $joinid = $showsqlid 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
		where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1  $datewhere  $paytypewhere   $sWhere   ";
}
$db->query($query);
$totoalcount = $db->nf()+0;

$arr_result = $db->getFiledData();
foreach($arr_result as $value)
{
		$tempallpaymoney += $value['paymoney'];
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

		$arr_list[] = array(
				"DT_RowId" => $vid ,
				"DT_RowClass" => "",
				g2u($showname),
				"<a herf=\"#\" onclick=\"s_addpro(this);\" paytype=\"$paytypee\">" . $bkordernumber . "</a>",
				g2u($paytype),
				$paydate,
				"<span style='text-align:right;display:block;'>" . $paymoney . "</span>",
				"<span style='text-align:right;display:block;'>" . $payfee . "</span>",
				"<span style='text-align:right;display:block;'>" . $linru . "</span>",
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
		"<span style='text-align:right;display:block;'>" . $tempallpaymoney . "</span>",
		"<span style='text-align:right;display:block;'>" . $tempallpayfee . "</span>",
		"<span style='text-align:right;display:block;'>" . $tempalllinru . "</span>",
		""
	);
}
else
{
	$arr_list[] = array(
		"DT_RowId" => $vid ,
		"DT_RowClass" => "",
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
$returnarray['sEcho'] = intval($sEcho);
$returnarray['iTotalRecords'] = $totoalcount;
$returnarray['iTotalDisplayRecords'] = $totoalcount;
$returnarray['aaData'] = $arr_list;
$returnvalue = json_encode($returnarray);
echo json_encode($returnarray);
?>