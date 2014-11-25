<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;

//选择销售退货刷卡器

if(empty($tmpid))
{

	$tmpid=$g_tmp_paycardid;
}

$query="select * from tb_salelist
		left join tb_salelistdetail on fd_stdetail_seltid=fd_selt_id
		where  fd_selt_state=9";
$db->query($query);
if($db->nf())
{
while($db->next_record())
{
	$str_paycardid= $db->f(fd_stdetail_paycardid);  //id号
	
	if($str_salepaycardid)
	{
		$str_salepaycardid .=",".$str_paycardid;
	}else{
		$str_salepaycardid=$str_paycardid;
	}
}
	
		$arr_salsepaycardid=explode(",",$str_salepaycardid);
		foreach($arr_salsepaycardid as $va0)
		{
			if($strsalsepaycardid)
		{
			$strsalsepaycardid .=","."'$va0'";
		}else{
			$strsalsepaycardid="'$va0'";
		}
		}
	
	$wherepaycard .="and fd_paycard_id in($strsalsepaycardid)";
}else{
	$wherepaycard .="and fd_paycard_id in('')";
}
	

if($vid)
{
 $qeurychoose="where fd_stdetail_seltid <> '$seltid'";
}
$query = "SELECT * FROM `tb_salelistbackdetail` $qeurychoose ";
$db->query($query);
if($db->nf())
{
while($db->next_record())
{
	$arr_paycardid[]= $db->f(fd_stdetail_paycardid);  //id号
	
}


foreach($arr_paycardid as $value)
{
	$arr_paycardid1=explode(",",$value);
	foreach($arr_paycardid1 as $va)
	{
		if($strpaycardid)
	{
		$strpaycardid .=","."'$va'";
	}else{
		$strpaycardid="'$va'";
	}
	}
}	

$wherepaycard .="and fd_paycard_id not in($strpaycardid)";

}

$aColumns = array("","fd_paycard_key","fd_paycard_batches","fd_product_suppno","fd_product_suppname");



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

if(!empty($tmpid))	
{	
$query="select fd_tmpsale_paycardid from tb_salelist_tmp where fd_tmpsale_id='$tmpid'";
$db->query($query);

	if($db->nf())
	{
		$db->next_record();
		$tmp_paycardid= $db->f(fd_tmpsale_paycardid);
		  
	}

	$arr_tmp_paycardid=explode(",",$tmp_paycardid);
	
	foreach($arr_tmp_paycardid as $value)
	{
		$arr_checked[$value]="checked";
	}
}
$query = "SELECT * FROM `tb_paycard`
left join tb_product on fd_product_id=fd_paycard_product 
where fd_paycard_product='$productid' and fd_paycard_cusid='$cusid' $wherepaycard  $sWhere";
$db->query($query);
$totoalcount=$db->nf()+0;	
$count=0;
$query = "$query limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		
	   $vpaycardid          = $db->f(fd_paycard_id);            //id号  
	   $vpaycardkey          = trim($db->f(fd_paycard_key));            //id号
       $vsuppname            = g2u($db->f(fd_product_suppname));       
       $vbatches             = $db->f(fd_paycard_batches); 
	   $saleprice             = $db->f(fd_paycard_saleprice); 
	   if($arr_checked)
	   {
		$check=$arr_checked[$vpaycardid];
	   }
	
	   $vedit='<input type="checkbox" name="arr_paycardid[]"  value="'.$vpaycardid.'" '.$check.' onclick="checkone(\''.$vpaycardid.'\',this)" class="checkpaycard" >';
	   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",					
		                $vbatches,
						$vpaycardkey,
						$saleprice,
						$vsuppname,						
						$vedit
						);
     }
   }
   else
   {     
     $vmembernum  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $vbatches,
						$vpaycardid,
						$saleprice,
						$vsuppname,						
						$vedit);
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
?>