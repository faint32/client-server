<?

//�����ֿ���������������ͳһ���������вֿ��ƽ�����۶���һ����
function updatestorage($commid,$quantity,$cost,$storageid,$type){
	//$type =0��ʾ�������� �� 1��ʾ��������
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
		} //���б��������вֿ��������
	}
	
	$query = "select * from tb_paycardstockquantity where fd_skqy_commid = '$commid' ";
	$db->query($query);  //�޸Ĳֿ���Ʒ������
	if($db->nf()){
		$db->next_record();
		$oldquantity = $db->f(fd_skqy_quantity);
		$listid = $db->f(fd_skqy_id);		
		$newquantity = $oldquantity + $endquantity;  //�����������
    
    if($newquantity==0){
    	 $query = "delete from tb_paycardstockquantity where fd_skqy_id = '$listid'";
    	 $db->query($query);
    }else{
       //�޸Ĳֿ������
		   $query ="update tb_paycardstockquantity set fd_skqy_quantity = '$newquantity' $updatesql
		            where fd_skqy_id = '$listid' ";
		  
		   $db->query($query);
	  }
	}else{  //����òֿ������
	  $query = "insert into tb_paycardstockquantity(
	            fd_skqy_storageid , fd_skqy_commid , fd_skqy_quantity 
	            $sqlfield
	            )values(
	            '$storageid' , '$commid'      , '$endquantity'   
	            $sqlvalue
	            )";
	  $db->query($query);
  }
  
	$allnewquantity = $allquantity + $endquantity;  //����������
	
  $query = "select * from tb_storagecost where fd_sect_commid = '$commid'";
	$db->query($query);   //���������ƽ���۸�
	if($db->nf()){
		$db->next_record();
		$oldcost = $db->f(fd_sect_cost);  //ƽ���۸�
		
		if($allnewquantity!=0){

	  	$newcost = ($oldcost*$allquantity+$cost*$endquantity)/$allnewquantity;
	  }else{
	    $newcost = $oldcost ;
    }//�������ƽ���۸�
    
    $query ="update tb_storagecost set fd_sect_cost = '$newcost'
		         where fd_sect_commid = '$commid' ";
		$db->query($query);  //�޸�ƽ���۸�
	}else{
	  $oldcost = 0;
	  if($allnewquantity!=0){
	  	$newcost = ($oldcost*$allquantity+$cost*$endquantity)/$allnewquantity;
	  }else{
	    $newcost = $oldcost ;
    }//�������ƽ���۸�
	  $query = "insert into tb_storagecost(
	            fd_sect_commid , fd_sect_cost 
	            )values(
	            '$commid'      , '$newcost'  
	            )";
	  $db->query($query);   //����ƽ���۸� 
  }
 
}
?>