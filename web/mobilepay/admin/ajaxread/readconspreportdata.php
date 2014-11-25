<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

if($type=='paycard')
{
	$search="fd_paycard_key";
	$showsqlid='fd_agpm_paycardid';
	$joinid='fd_paycard_id';
	$showtab='tb_paycard';
	$leftjion='';
	$newspaydata='fd_paycard_newspaydata as newspaydata,';
}

if($type=='sdcr')
{
	$search="fd_sdcr_name";
	$showsqlid='fd_agpm_sdcrid';
	$joinid='fd_sdcr_id';
	$showtab='tb_sendcenter';

	$query="select max(fd_paycard_newspaydata) as newspaydata , fd_sdcr_id from tb_agentpaymoneylist 
	left join tb_paycard on fd_paycard_id = fd_agpm_paycardid 
	left join tb_sendcenter on fd_sdcr_id = fd_agpm_sdcrid 
	left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
	where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1 
	group by fd_paycard_authorid ";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$fd_sdcr_id = $db->f(fd_sdcr_id);
			$arr_newspaydata[$fd_sdcr_id] = $db->f(newspaydata);
		}
	}
}

if($type=='author')
{
	$search="fd_author_truename";
	$showsqlid='fd_agpm_authorid';
	$joinid='fd_author_id';
	$showtab='tb_author';

	$query='select max(fd_paycard_newspaydata) as newspaydata , fd_author_id from tb_author left join tb_paycard on fd_paycard_authorid = fd_author_id group by fd_paycard_authorid ';
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$fd_author_id = $db->f(fd_author_id);
			$arr_newspaydata[$fd_author_id] = $db->f(newspaydata);
		}
	}
}

$arr_appmnu=getauthorpaycardmenu();

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

$arr_orderby = array("showname","allpaymoney");

if ( $iSortCol_0 == "0" or $iSortCol_0 == "1")
{
	$orderby = "order by ".$arr_orderby[$iSortCol_0]." ".$sSortDir_0;
}
else
{
	$orderby = "order by showname desc";
}

$query="select sum(fd_agpm_paymoney) as paymoney, fd_agpm_paytype as paytype ,$showsqlid as id from tb_agentpaymoneylist 
left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1  group by $showsqlid ,fd_agpm_paytype ";
$db->query($query);

if ($db->nf())
{
	while ($db->next_record())
	{
		$id = $db->f(id);
		$paytype = $db->f(paytype);
		$arr_data[$id][$paytype]['paymoney'] = $db->f(paymoney);
	}
}

if ($authorid)
{
	$queryauthorid="and fd_agpm_authorid='$authorid'";
}

if ($sdcrid)
{
	$querysdcrid="and fd_agpm_sdcrid='$sdcrid'";
}

$query = "select
		group_concat(fd_agpm_paytype) as paytype,
		sum(fd_agpm_paymoney) as allpaymoney,
		$newspaydata
		$search as showname ,
		$showsqlid as vid
		from tb_agentpaymoneylist
		left join $showtab on $joinid = $showsqlid
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype	
		where fd_agpm_payrq='00' and  fd_appmnu_istabno = 1 $queryauthorid $querysdcrid $sWhere group by $showsqlid $orderby ";
$db->query($query);
$arr_result = $db->getFiledData();
foreach($arr_result as $value)
{
		$tempallpaymoney += $value['allpaymoney'];
		$arr_paytype = explode( "," , $value['paytype'] );
		$arr_paytype = array_unique( $arr_paytype );
		$vid=$value['vid'];
		foreach( $arr_paytype as $v )
		{
			$new_alldata[$vid][$v]['paymoney'] = $arr_data[$vid][$v]['paymoney'];
		}
	
		foreach($arr_appmnu as $key=>$v1)
		{
			$vpaymoney=$new_alldata[$vid][$key]['paymoney'];
			$arr_allpaymoney[$key] +=$vpaymoney;
		}

}

$totoalcount = $db->nf() + 0;
$count = 0;

if ($db->nf())
{
	while ( $db->next_record() )
	{
		$vid = $db->f(vid);
		$showname = $db->f(showname);
		$paytype = $db->f(paytype);
		$allpaymoney = $db->f(allpaymoney);
		$tempallpaymoney += $allpaymoney;

		$arr_paytype = explode( "," , $paytype );
		$arr_paytype = array_unique( $arr_paytype );

		foreach( $arr_paytype as $value )
		{
			$new_data[$vid][$value]['paymoney'] = $arr_data[$vid][$value]['paymoney'];
		}

		foreach($arr_appmnu as $key=>$v)
		{
			$paymoney = $new_data[$vid][$key]['paymoney'];
			$temp[$key] += $paymoney;
			$temp2[$key] = "<a href='consp_data.php?type=$type&paytype=$key' style='text-align:right;display:block;'>".$temp[$key]."</a>";
		}
	}
}

$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);



if($db->nf())
{
	while ( $db->next_record() )
	{
		$vid = $db->f(vid);
		$showname = $db->f(showname);
		$allpaymoney = $db->f(allpaymoney);
		$paytype = $db->f(paytype);


		if ( $type == 'paycard' )
		{
			$allpaymoney="<a href='consp_data.php?type=paycard&listid=$vid' style='text-align:right;display:block;'>$allpaymoney</a>";
			$newspaydata = $db->f(newspaydata);
		}

		if ( $type == 'author' )
		{
			$allpaymoney="<a href='consp_data.php?type=author&listid=$vid' style='text-align:right;display:block;'>$allpaymoney</a>";
			$newspaydata =$arr_newspaydata[$vid];
		}

		if ( $type == 'sdcr' )
		{
			$allpaymoney="<a href='consp_data.php?type=sdcr&listid=$vid' style='text-align:right;display:block;'>$allpaymoney</a>";
			$newspaydata =$arr_newspaydata[$vid];
		}

		$arr_list[$count] = array(
				"DT_RowId" => $vid ,
				"DT_RowClass" => "",
				g2u($showname),
				$allpaymoney,
				$newspaydata
		);

		$arr_paytype = explode( "," , $paytype );
		$arr_paytype = array_unique( $arr_paytype );

		foreach( $arr_paytype as $value )
		{
			$new_data[$vid][$value]['paymoney'] = $arr_data[$vid][$value]['paymoney'];
		}
	
		foreach($arr_appmnu as $key=>$v)
		{
			$paymoney=$new_data[$vid][$key]['paymoney'];
			$arr_list[$count][]="<a href='consp_data.php?type=$type&paytype=$key&listid=$vid' style='text-align:right;display:block;'>$paymoney</a>";
		}
		$count++;
	}

	$listcount = count($arr_list);
	$arr_list[$listcount] = array(
		"DT_RowId" => "" ,
		"DT_RowClass" => "",
		g2u('合共'),
		"<a href='consp_data.php?type=$type' style='text-align:right;display:block;'>".$tempallpaymoney."</a>",
		"",
	);
	$num=count($arr_list)-1;
	 foreach($arr_appmnu as $key=> $value)
	{
		
		$arr_list[$num][]="<a href='consp_data.php?type=$type&paytype=$key' style='text-align:right;display:block;'>".$arr_allpaymoney[$key]."</a>";
	}
	

	foreach ($temp2 as $value)
	{
		$arr_list[$listcount][] = $value;
	}
}
else
{
	$vmember = "暂无数据";
}

	$returnarray['sEcho']=intval($sEcho);
	$returnarray['iTotalRecords']=$totoalcount;
	$returnarray['iTotalDisplayRecords']=$totoalcount;
	$returnarray['aaData']=$arr_list;
	$returnvalue = json_encode($returnarray);
	echo json_encode($returnarray);
?>