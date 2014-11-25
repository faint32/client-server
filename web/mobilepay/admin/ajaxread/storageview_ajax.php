<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


$aColumns = array("fd_product_no","fd_skqy_quantity","fd_skqy_indate","fd_skqy_outdate");

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
	
     $query = "select fd_skqy_id,fd_product_id,fd_product_name,fd_product_no ,
         fd_skqy_quantity, fd_skqy_commid  ,
         fd_skqy_indate , fd_skqy_outdate 
         from tb_paycardstockquantity 
	      
	       left join tb_product on fd_product_id = fd_skqy_commid
	       where fd_skqy_quantity <> 0 $sWhere  ";
   $db->query($query);
   $totoalcount=$db->nf()+0;
   
   				  
$query = "select fd_skqy_id,fd_product_id,fd_product_name,fd_product_no ,
         fd_skqy_quantity, fd_skqy_commid  ,
         fd_skqy_indate , fd_skqy_outdate 
         from tb_paycardstockquantity 
	      
	     left join tb_product on fd_product_id = fd_skqy_commid
		 where fd_skqy_quantity <> 0 $sWhere limit $iDisplayStart,$iDisplayLength  ";
$db->query($query);
//echo $query;
if($db->nf())
{
	while($db->next_record())
	{         
	   	$vid         = $db->f(fd_skqy_id) ;
		$barcode     = $db->f(fd_product_no) ;
		$proname	 = $vid."-".$db->f(fd_product_name) ;
		$quantity    = $db->f(fd_skqy_quantity)+0;
		$commid 	 = $db->f(fd_skqy_commid);
		$indate      = $db->f(fd_skqy_indate);
		$outdate     = $db->f(fd_skqy_outdate);
		
		
/*		$tmptrademarkid = $db->f(fd_product_trademarkid);
		$tmpstorageid   = $db->f(fd_skqy_storageid);
	   	$cost = $arr_cost[$commid];
		$money = $quantity*$cost;
		$money = number_format($money, 2, ".", "");
		$cost = number_format($cost, 4, ".", "");
		$quantity = round($quantity, 4);
		$alldunquantity = number_format($alldunquantity, 4, ".", "");
		$allmoney = number_format($allmoney, 2, ".", "");*/
		$quantity = "<span color='#FF0000' style='border-bottom:1px #999 solid;' onClick=javascript:showquantity('".$commid."',this)>".$quantity."</span>";
		$caozuo = "<span color='#FF0000' style='border-bottom:1px #999 solid;' onClick=javascript:showstorage('".$commid."',this)>".g2u(商品流水账)."</span>";
	   	$count++;   
	   $arr_list[] = array("DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$count,
						$barcode,
						g2u($proname),
						$quantity,
						$indate,
						$outdate,
						$caozuo
						);
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
						$barcode,
						g2u($proname),
						$quantity,
						$indate,
						$outdate,
						$caozuo
						);
   }
      
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>