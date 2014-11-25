<?   
header('Content-Type:text/html;charset=GB2312'); 
//查看是否有没有库存单价的商品
require("../include/common.inc.php");
$db=new db_test;
$db1=new db_test;

$returnflag=0;
$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid'";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$productid    = $db->f(fd_stdetail_productid);
		//查找库存数量
		$flagquantity=0;
		$query = "select * from tb_paycardstockquantity where fd_skqy_commid = '$productid' ";
		$db1->query($query);
    if($db1->nf()){
    	while($db1->next_record()){
    		if($db1->f(fd_skqy_quantity)!=0){
    			$flagquantity = 1;
    		}
      }
    }
    //查询库存成本价
		$query ="select * from tb_storagecost where fd_sect_commid = '$productid' ";
    $db1->query($query);
    if($db1->nf()){
	     $db1->next_record();
	     $cost = $db1->f(fd_sect_cost);
	     if($cost==0 and $flagquantity==0){
	     	  $returnflag = 1;  
	     }
	  }else{
	     if($flagquantity==0){
	       $returnflag = 1;
	     }
	  }
	}
}

echo $returnflag;


?>