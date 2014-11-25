<?
//检测是否要补库存支出。
function checkstorage($batches,$commid,$endquantity,$cost,$listid,$listno,$listtype,$memo ){
	$db  = new DB_test;
	$query = "select fd_sect_cost from tb_storagecost where fd_sect_commid = '$commid'  ";
	$db->query($query);   //查出机构的平均价格
	if($db->nf()){
		$db->next_record();
		$oldcost = $db->f(fd_sect_cost);  //平均价格
	}
	$query = "select sum(fd_skqy_quantity) as allquantity from tb_paycardstockquantity 
	          where fd_skqy_commid = '$commid' ";
	$db->query($query);  //计算该机构或者配送中心的库存数量。
	if($db->nf()){
		$db->next_record();
		$oldquantity = $db->f(allquantity);
		$newquantity = $oldquantity + $endquantity;
		if($newquantity==0){
			$newmoney = $oldcost*$oldquantity+$cost*$endquantity;
		}else{
		  $newmoney = 0;
	  }
	}else{
	  $newmoney = 0;
  }
  if($newmoney<>0){
  	$formermoney = $oldcost*$oldquantity;
  	$aftermoney = $cost*$endquantity;
  	$query = "insert into tb_intocompensatory (
  	          fd_iocy_date       ,  fd_iocy_listid          ,  fd_iocy_listtype       , 
  	          fd_iocy_paycardid     ,  fd_iocy_formermoney     ,  fd_iocy_aftermoney     , 
  	          fd_iocy_memo       ,  fd_iocy_formercost      ,  fd_iocy_aftercost      ,
  	          fd_iocy_listno     ,  fd_iocy_formerquantity  ,  fd_iocy_afterquantity   
  	          )values(
  	          now()            ,  '$listid'           ,  '$listtype'        ,
  	          '$paycardid'        ,  '$formermoney'      ,  '$aftermoney'      ,
  	          '$memo'          ,  '$oldcost'          ,  '$cost'            ,
  	          '$listno'        ,  '$oldquantity'      ,  '$endquantity'     
			
  	          )";
  	$db->query($query);
  }
  return $newmoney;
}
?>