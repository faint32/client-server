<?

//更正仓库数量和数量，是统一机构的所有仓库的平均单价都是一样。
function updatestorage($commid,$quantity,$cost,$storageid,$type){
	//$type =0表示增加数量 ， 1表示减少数量
	$db  = new DB_test;
	if($type==0){
		$endquantity = $quantity;
		
		$sqlfield = " , fd_skqy_indate ";
		$sqlvalue = " , now() ";
		
		$updatesql = " , fd_skqy_indate = now()";
	}else{
	  $sqlfield = " , fd_skqy_outdate ";
	  $sqlvalue = " , now() ";
	  
	  $updatesql = " , fd_skqy_outdate = now()";
		$endquantity = -$quantity;
	}
  
  $allquantity = 0;
  $query = "select * from tb_paycardstockquantity where fd_skqy_commid = '$commid' ";
	$db->query($query);
	if($db->nf()){
		while($db->next_record()){
			$allquantity += $db->f(fd_skqy_quantity);
		} //所有本机构所有仓库的总数量
	}
	
	$query = "select * from tb_paycardstockquantity where fd_skqy_commid = '$commid' ";
	$db->query($query);  //修改仓库商品的数量
	if($db->nf()){
		$db->next_record();
		$oldquantity = $db->f(fd_skqy_quantity);
		$listid = $db->f(fd_skqy_id);		
		$newquantity = $oldquantity + $endquantity;  //算出最后的总数
    
    if($newquantity==0){
    	 $query = "delete from tb_paycardstockquantity where fd_skqy_id = '$listid'";
    	 $db->query($query);
    }else{
       //修改仓库的数量
		   $query ="update tb_paycardstockquantity set fd_skqy_quantity = '$newquantity' $updatesql
		            where fd_skqy_id = '$listid' ";
		  
		   $db->query($query);
	  }
	}else{  //插入该仓库的数量
	  $query = "insert into tb_paycardstockquantity(
	            fd_skqy_storageid , fd_skqy_commid , fd_skqy_quantity 
	            $sqlfield
	            )values(
	            '$storageid' , '$commid'      , '$endquantity'   
	            $sqlvalue
	            )";
	  $db->query($query);
  }
  
	$allnewquantity = $allquantity + $endquantity;  //计算总数量
	
  $query = "select * from tb_storagecost where fd_sect_commid = '$commid'";
	$db->query($query);   //查出机构的平均价格
	if($db->nf()){
		$db->next_record();
		$oldcost = $db->f(fd_sect_cost);  //平均价格
		
		if($allnewquantity!=0){

	  	$newcost = ($oldcost*$allquantity+$cost*$endquantity)/$allnewquantity;
	  }else{
	    $newcost = $oldcost ;
    }//算出最后的平均价格
    
    $query ="update tb_storagecost set fd_sect_cost = '$newcost'
		         where fd_sect_commid = '$commid' ";
		$db->query($query);  //修改平均价格
	}else{
	  $oldcost = 0;
	  if($allnewquantity!=0){
	  	$newcost = ($oldcost*$allquantity+$cost*$endquantity)/$allnewquantity;
	  }else{
	    $newcost = $oldcost ;
    }//算出最后的平均价格
	  $query = "insert into tb_storagecost(
	            fd_sect_commid , fd_sect_cost 
	            )values(
	            '$commid'      , '$newcost'  
	            )";
	  $db->query($query);   //插入平均价格 
  }
 
}
?>