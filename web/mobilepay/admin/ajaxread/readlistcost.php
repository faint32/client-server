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
    $db1->query($query);  //��ѯ�ֿ�����
    if($db1->nf()){
    	$db1->next_record();
    	$storagequantity = $db1->f(fd_skqy_quantity)+0;
    	if($storagequantity < $salequantity){
    		//$tmpquantity = $salequantity - $storagequantity;
    		$returnquantitypaycardid = $returnquantitypaycardid."��".$productid."�����������Ϊ��".$storagequantity."\n"; 
    		$flagcomm = 1;
    	}
    }
    $query = "select * from tb_storagecost 
		          where fd_sect_commid = '$productid'";
    $db1->query($query);  //��ѯƽ���۸�
    if($db1->nf()){
    	$db1->next_record();
    	$storagecost = $db1->f(fd_sect_cost)+0;

    	if($storagecost >= $saleprice){
    		//$tmpcost = $storagecost - $saleprice;
    		$returnpaycardid = $returnpaycardid."��".$productid."������Ϊ��".$storagecost."\n"; 
    		$flagcomm = 1;
    	}
    }
    $query = "select * from tb_inpricenote 
		          where fd_inpene_commid = '$productid'";
    $db1->query($query);  //��ѯ���һ�ν�����
    if($db1->nf()){
    	$db1->next_record();
    	$inprice     = $db1->f(fd_inpene_price)+0;
    	if($saleprice <= $inprice){
    		//$tmpprice = $inprice - $saleprice;
    		$returnpricepaycardid = $returnpricepaycardid."��".$productid."�����һ�ν����ۣ�".$inprice."\n"; 
    		$flagcomm = 1;
    	}
    }
	}
}
if($flagcomm==0){
	 echo "";
}else{
   if(!empty($returnpaycardid)){
   	  $returnvalue .="������Ʒ�ǿ�浥�۴������۵��ۣ�\n".$returnpaycardid;
   }
   if(!empty($returnquantitypaycardid)){
   	  $returnvalue .="\n������Ʒ�������������ڿ��������\n".$returnquantitypaycardid;
   }
   if(!empty($returnpricepaycardid)){
   	  $returnvalue .="\n������Ʒ�����۵���С�����һ�ν������ۣ�\n".$returnpricepaycardid;
   }
   echo $returnvalue;
}
?>