<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
//选择销售刷卡器


if(empty($tmpid))
{

	$tmpid=$g_tmp_paycardid;
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
	

//找出正在入库退货的刷卡器
$query = "select * from tb_paycardstockbackdetail";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$stockbackpaycardkey = $db->f(fd_skdetail_paycardid); //id号

		if ($str_paycardkey) {
			$str_paycardkey .= "," . $stockbackpaycardkey;
		} else {
			$str_paycardkey = $stockbackpaycardkey;
		}
	}
}

if($vid)
{
 $qeurychoose="where fd_stdetail_seltid <> '$seltid'";
}else{
  $wherepaycard .="and fd_paycard_state='1'";
}
//找出正在销售刷卡器
$query = "SELECT * FROM `tb_salelistdetail` $qeurychoose ";
$db->query($query);

if($db->nf())
{
while($db->next_record())
{
	$salepaycardkey= $db->f(fd_stdetail_paycardid);  //id号
	
	if ($str_paycardkey) {
	$str_paycardkey .= "," .$salepaycardkey;
	} else {
	$str_paycardkey = $salepaycardkey;
	}
	
}
}



  //找出已经退货销售刷卡器
$query="select * from tb_salelistback
left join tb_salelistbackdetail on fd_selt_id=fd_stdetail_seltid
where fd_selt_state='9'";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$salebackpaycardkey = $db->f(fd_stdetail_paycardid); //id号
		if($str_backpaycardkey) {
			$str_backpaycardkey .= "," . $salebackpaycardkey;
		}else{
			$str_backpaycardkey = $salebackpaycardkey;
		}
	}
	$arr_backpaycardkey = explode(",", $str_backpaycardkey);
}  


if($str_paycardkey)
{
	$arr_paycardkey = explode(",", $str_paycardkey);
	if($str_backpaycardkey)
	{
		$arr_paycardkey=array_merge(array_diff($arr_paycardkey,$arr_backpaycardkey),array_diff($arr_backpaycardkey,$arr_paycardkey));
	}
		
	foreach ($arr_paycardkey as $va) {
			if ($strpaycardkey) {
				$strpaycardkey .= "," . "'$va'";
			} else {
				$strpaycardkey .= "'$va'";
			}
		}
		
	$wherepaycard .= "and fd_paycard_id not in($strpaycardkey)";
}




if(!empty($tmpid))	
{	
$query="select fd_tmpsale_paycardid from tb_salelist_tmp where fd_tmpsale_id='$tmpid'";
$db->query($query);

if($db->nf())
{
	$db->next_record();
	$tmp_paycardkey= $db->f(fd_tmpsale_paycardid);
}

	$arr_tmp_paycardkey=explode(",",$tmp_paycardkey);
	
	foreach($arr_tmp_paycardkey as $value)
	{
		$arr_checked[$value]="checked";
	}
}
$query = "SELECT * FROM `tb_paycard`
left join tb_product on fd_product_id=fd_paycard_product 
where fd_paycard_product='$productid'   $wherepaycard  $sWhere";
$db->query($query);
//echo $query;
$totoalcount=$db->nf()+0;	
$count=0;
$query = "$query limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
	   $vpaycardid          = $db->f(fd_paycard_id);            //id号  
	   $vpaycardkey          = $db->f(fd_paycard_key);            //id号  
       $suppname             = g2u($db->f(fd_product_suppname));       
       $vbatches             = $db->f(fd_paycard_batches); 
	   $vstockprice             = $db->f(fd_paycard_stockprice);
	   if($arr_checked)
	   {
		$check=$arr_checked[$vpaycardid];
	   }
		$vpaycardkey=trim($vpaycardkey);
	   $vedit='<input type="checkbox" name="arr_paycardkey[]"  value="'.$vpaycardid.'" '.$check.' onclick="checkone(\''.$vpaycardid.'\',this)" class="checkpaycard" >';
	   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",					
		                $vbatches,
						$vpaycardkey,
						$vstockprice,
						$suppname,						
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
						$vpaycardkey,
						$vstockprice,
						$suppname,						
						$vedit);
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
?>