<?   
require("../include/common.inc.php");
$db = new db_test;
$db1= new db_test;

header('Content-Type:text/html;charset=GB2312'); 

$flagcomm = 0;
$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid'";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$paycardid    = $db->f(fd_stdetail_paycardid);
		$productid    = $db->f(fd_stdetail_productid);
		$saleprice = $db->f(fd_stdetail_price);
		$salequantity = $db->f(fd_stdetail_quantity);
		$query = "select * from tb_paycardstockquantity 
		          where fd_skqy_commid = '$productid'";
    $db1->query($query);  //查询仓库数量
    if($db1->nf()){
    	$db1->next_record();
    	$storagequantity = $db1->f(fd_skqy_quantity)+0;
    	if($storagequantity < $salequantity){
    		//$tmpquantity = $salequantity - $storagequantity;
    		$returnquantitypaycardid = $returnquantitypaycardid."“".$productid."”当库存数量为：".$storagequantity."\n"; 
    		$flagcomm = 1;
    	}
    }
    $query = "select * from tb_storagecost 
		          where fd_sect_commid = '$productid'";
    $db1->query($query);  //查询平均价格
    if($db1->nf()){
    	$db1->next_record();
    	$storagecost = $db1->f(fd_sect_cost)+0;

    	if($storagecost >= $saleprice){
    		//$tmpcost = $storagecost - $saleprice;
    		$returnpaycardid = $returnpaycardid."“".$productid."”相差单价为：".$storagecost."\n"; 
    		$flagcomm = 1;
    	}
    }
    $query = "select * from tb_inpricenote 
		          where fd_inpene_commid = '$productid'";
    $db1->query($query);  //查询最后一次进货价
    if($db1->nf()){
    	$db1->next_record();
    	$inprice     = $db1->f(fd_inpene_price)+0;
    	if($saleprice <= $inprice){
    		//$tmpprice = $inprice - $saleprice;
    		$returnpricepaycardid = $returnpricepaycardid."“".$productid."”最进一次进货价：".$inprice."\n"; 
    		$flagcomm = 1;
    	}
    }
	}
}
if($flagcomm==0){
	 echo "";
}else{
   if(!empty($returnpaycardid)){
   	  $returnvalue .="以下商品是库存单价大于销售单价：\n".$returnpaycardid;
   }
   if(!empty($returnquantitypaycardid)){
   	  $returnvalue .="\n以下商品是销售数量大于库存数量：\n".$returnquantitypaycardid;
   }
   if(!empty($returnpricepaycardid)){
   	  $returnvalue .="\n以下商品是销售单价小于最近一次进货单价：\n".$returnpricepaycardid;
   }
   echo $returnvalue;
}
?>