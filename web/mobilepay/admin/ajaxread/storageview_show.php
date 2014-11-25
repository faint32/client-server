<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


$aColumns = array("fd_skqy_indate","fd_skqy_outdate","fd_paycard_key","fd_paycard_batches","fd_stock_suppname","fd_cus_name");

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
	
     $query = "select * from  tb_paycard 
			left join tb_paycardstockquantity on fd_skqy_commid = fd_paycard_product
			left join tb_paycardstockdetail on fd_paycard_batches = fd_skdetail_batches
			left join tb_paycardstock on fd_stock_id = fd_skdetail_stockid
			left join tb_customer on fd_paycard_cusid = fd_cus_id
			where fd_paycard_product = '$commid'
			$sWhere  ";
   $db->query($query);
   $totoalcount=$db->nf()+0;
   
   				  
/*$query = "select * from tb_paycardstockquantity
			left join tb_commglide on fd_skqy_commid = fd_coge_commid
			left join tb_paycardstock on fd_stock_id = fd_coge_listid
			left join tb_paycardstockdetail on fd_skdetail_stockid = fd_coge_listid
			left join tb_paycard on fd_paycard_batches = fd_skdetail_batches
			left join tb_customer on fd_paycard_cusid = fd_cus_id
			where  fd_coge_commid = '$commid'   $sWhere limit $iDisplayStart,$iDisplayLength  ";*/
$query = "select * from  tb_paycard 
			left join tb_paycardstockquantity on fd_skqy_commid = fd_paycard_product
			left join tb_paycardstockdetail on fd_paycard_batches = fd_skdetail_batches
			left join tb_paycardstock on fd_stock_id = fd_skdetail_stockid
			left join tb_customer on fd_paycard_cusid = fd_cus_id
			where fd_paycard_product = '$commid'
			$sWhere limit $iDisplayStart,$iDisplayLength  ";
$db->query($query);
//echo $query;
if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_paycard_id);            //id号  
	   $key             = $db->f(fd_paycard_key);            //id号  
       $state      	 = 	$db->f(fd_paycard_posstate);        
       $batches       = $db->f(fd_paycard_batches); 
       $indate         = $db->f(fd_skqy_indate);
	   $suppname       = g2u($db->f(fd_stock_suppname));
	   $outdate       = $db->f(fd_skqy_outdate);
	   $cusname    = g2u($db->f(fd_cus_name));
	  if($state==1)
	  {
		$state="警告";
	  }elseif($state==2)
	  {
		$state="启用";
	  }elseif($state==3)
	  {
		$state="冻结";
	  }else{
		$state="停用";
	  }
	   	$count++;   
	   $arr_list[] = array("DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$count,
						$batches,
						$key,
						g2u($state),
						$indate,
						$suppname,
						$outdate,
						$cusname);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$count,
						$vid,
						$batches,
						$state,
						$indate,
						$suppname,
						$outdate,
						$cusname);
   }
      
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>