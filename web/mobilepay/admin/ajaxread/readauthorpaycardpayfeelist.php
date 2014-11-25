<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


$aColumns = array("","fd_paycard_key","fd_paycard_newspaydata");

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

$arr_orderby=array("paycardkey","payfee","yingli");
if($iSortCol_0=="0" or $iSortCol_0=="1" or $iSortCol_0=="2" )
{
	$orderby="order by ".$arr_orderby[$iSortCol_0]." ".$sSortDir_0;
}
else
{
	$orderby="order by paycardkey desc";
}

$arr_appmnu=getauthorpaycardmenu();


$query="select sum(fd_agpm_payfee) as payfee,sum(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as yingli ,
		fd_agpm_paytype as  paytype ,fd_agpm_paycardid as paycardid
		from tb_agentpaymoneylist 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
		where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1
		group by fd_agpm_paycardid ,fd_agpm_paytype ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paycardid  = $db->f(paycardid);
		$paytype    = $db->f(paytype);
		$arr_data[$paycardid][$paytype]['payfee']  = $db->f(payfee);
		$arr_data[$paycardid][$paytype]['yingli']  = $db->f(yingli);
	}
}

$query = "select
		group_concat(fd_agpm_paytype) as paytype,
		fd_paycard_newspaydata as newspaydata,
		sum(fd_agpm_payfee) as payfee,
		sum(fd_agpm_payfee-fd_agpm_sdcrpayfeemoney-fd_agpm_sdcragentfeemoney) as yingli,
		fd_paycard_key as paycardkey ,
		fd_agpm_paycardid as paycardid
		from tb_agentpaymoneylist  
		left join tb_paycard  on fd_paycard_id = fd_agpm_paycardid
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
		where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1 $sWhere group by fd_agpm_paycardid $orderby ";
$db->query($query);
$arr_result = $db->getFiledData();
foreach($arr_result as $value)
{
		$tempallpayfee += $value['payfee'];
		$tempallyingli += $value['yingli'];
		$arr_paytype = explode(",",$value['paytype'] );
		$arr_paytype = array_unique($arr_paytype);
		$vpaycardid=$value['paycardid'];
		foreach( $arr_paytype as $v )
		{
			$new_alldata[$vpaycardid][$v]['payfee']=$arr_data[$vpaycardid][$v]['payfee'];
			$new_alldata[$vpaycardid][$v]['yingli']=$arr_data[$vpaycardid][$v]['yingli'];
		}
		
		foreach($arr_appmnu as $key=>$v1)
		{
			
			$vpayfee=$new_alldata[$vpaycardid][$key]['payfee'];
			$arr_allmoney['payfee'][$key] +=$vpayfee;
			$arr_allmoney['yingli'][$key] +=$new_alldata[$vpaycardid][$key]['yingli'];
		}

}

$totoalcount=$db->nf()+0;
$count=0;
$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paycardid = $db->f(paycardid);
		$paycardkey = $db->f(paycardkey);
		$yingli = $db->f(yingli) ;
		$payfee = $db->f(payfee) ;
		$paytype  = $db->f(paytype) ;
		$newspaydata = $db->f(newspaydata);
		$tempallpayfee += $payfee;
		$tempallyingli += $yingli;
		$arr_paytype=explode(",",$paytype);
		$arr_paytype=array_unique($arr_paytype);
		foreach($arr_paytype as $value)
		{
			$new_data[$paycardid][$value]['payfee'] = $arr_data[$paycardid][$value]['payfee'];
			$new_data[$paycardid][$value]['yingli'] = $arr_data[$paycardid][$value]['yingli'];
		}

		foreach($arr_appmnu as $key=>$v)
		{
			$temp[$key] += $new_data[$paycardid][$key]['payfee'];
			$temp2[$key] += $new_data[$paycardid][$key]['yingli'];
			$temp3[$key] = "<a href='consp_data.php?type=payfee&paytype=creditcard' style='text-align:right;display:block;'>" . $temp[$key] . "</a>";
			$temp4[$key] = "<span style='text-align:right;display:block;'>" . $temp2[$key] . "</span>";
		}
	}
}

$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paycardid = $db->f(paycardid);
		$paycardkey = $db->f(paycardkey);
		$yingli = $db->f(yingli) ;
		$payfee = $db->f(payfee) ;
		$paytype  = $db->f(paytype) ;
		$newspaydata = $db->f(newspaydata);


		$arr_list[$count] = array("DT_RowId" => $vid ,
				"DT_RowClass" => "",
				$paycardkey,
				"<a href='consp_data.php?type=payfee&listid=$paycardid' style='text-align:right;display:block;'>".$payfee."</a>",
				"<span style='text-align:right;display:block;'>".$yingli."</span>",
				$newspaydata
		);
		$arr_paytype=explode(",",$paytype);
		$arr_paytype=array_unique($arr_paytype);

		foreach($arr_paytype as $value)
		{
			$new_data[$paycardid][$value]['payfee'] = $arr_data[$paycardid][$value]['payfee'];
			$new_data[$paycardid][$value]['yingli'] = $arr_data[$paycardid][$value]['yingli'];
		}
	
		foreach($arr_appmnu as $key=>$v)
		{
			$payfee = $new_data[$paycardid][$key]['payfee'];
			$arr_list[$count][] = "<a href='consp_data.php?type=payfee&paytype=$key&listid=$paycardid' style='text-align:right;display:block;'>$payfee</a>";
			$arr_list[$count][] = "<span style='text-align:right;display:block;'>".$new_data[$paycardid][$key]['yingli']."</span>";
			$payfee=$new_data[$paycardid][$key]['payfee'];
			$arr_list[$count][]="<a href='consp_data.php?type=payfee&paytype=$key&listid=$paycardid' style='text-align:right;display:block;'>$payfee</a>";
			$arr_list[$count][]="<a href='consp_data.php?type=payfee&paytype=$key&listid=$paycardid' style='text-align:right;display:block;'>".$new_data[$paycardid][$key]['yingli']."</a>";
			
			
			
		}
		//array_push($arr_list,);
		$strpaytype = "";
		$count++;
	}

	$listcount = count($arr_list);
	$arr_list[$listcount] = array(
		"DT_RowId" => "",
		"DT_RowClass" => "",
		g2u('合共'),
		"<a href='consp_data.php?type=payfee' style='text-align:right;display:block;'>".$tempallpayfee."</a>",
		"<span style='text-align:right;display:block;'>".$tempallyingli."</span>",
		"",
	);

	foreach ($temp3 as $key => $value)
	{
		$arr_list[$listcount][] = $value;
		$arr_list[$listcount][] = $temp4[$key];
	}
		"<a href='consp_data.php?type=payfee' style='text-align:right;display:block;'>".$tempallyingli."</a>",
		""
	);
	$num = count($arr_list)-1;
	foreach($arr_appmnu as $key=> $value)
	{
		$arr_list[$num][] = "<a href='consp_data.php?type=payfee&paytype=$key'style='text-align:right;display:block;'>".$arr_allmoney['payfee'][$key]."</a>";
		$arr_list[$num][]="<a href='consp_data.php?type=payfee&paytype=$key'style='text-align:right;display:block;'>".$arr_allmoney['yingli'][$key]."</a>";
	}
}
else
{
	$arr_list[count($arr_list)] = array(
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
		"",
		"",
		"",
		"",
		"",
		""
	);
	$vmember  = "暂无数据";
}

$returnarray['sEcho']=intval($sEcho);
$returnarray['iTotalRecords']=$totoalcount;
$returnarray['iTotalDisplayRecords']=$totoalcount;
$returnarray['aaData']=$arr_list;
$returnvalue = json_encode($returnarray);
echo json_encode($returnarray);
?>