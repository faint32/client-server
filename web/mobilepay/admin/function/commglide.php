<?
//----------商品流水单---------------- 
//$cogetype = 0; //0为增加 ， 1为减少
function commglide($storageid , $commid , $quantity , $cogememo , $cogelisttype , $loginstaname , $listid , $listno , $cogetype , $listdate){    
	$db  = new DB_test;
	$ishaverecord = 0;
	$query = "select * from tb_commglide where fd_coge_sotrageid = '$storageid' and fd_coge_commid = '$commid'
	          and fd_coge_listid = '$listid' and fd_coge_listtype = '$cogelisttype' 
	          and (fd_coge_addquantity = '$quantity' or fd_coge_lessen = '$quantity') ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord =0;
	}
	if($ishaverecord == 0){   
    $query = "SELECT MAX(fd_coge_id) as coge_id FROM tb_commglide where 
              fd_coge_sotrageid = '$storageid' and fd_coge_commid = '$commid' 
              and fd_coge_iskickback <> 1 "; 
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$maxcogeid = $db->f(coge_id);
    	
    	$query = "select fd_coge_balance from tb_commglide 
                where fd_coge_id = '$maxcogeid' ";
      $db->query($query);
      if($db->nf()){
      	$db->next_record();
      	$cogebalance = $db->f(fd_coge_balance);
      }else{
        $cogebalance = 0 ;
      }
    }else{
      $cogebalance = 0 ;
    }
    if($cogetype=="0"){
       $endcogebalance = $cogebalance+$quantity;
       $query = "insert into tb_commglide(
                 fd_coge_date     ,  fd_coge_sotrageid , fd_coge_commid   , 
                 fd_coge_listno   ,  fd_coge_listtype  , fd_coge_addquantity ,
                 fd_coge_lessen   ,  fd_coge_balance   , fd_coge_memo     ,
                 fd_coge_listid   ,  fd_coge_makename  , fd_coge_datetime ,
                 fd_coge_listdate
                 )values(
                 now()            ,  '$storageid'      , '$commid'        ,
                 '$listno'        ,  '$cogelisttype'   , '$quantity'      ,
                 '0'              ,  '$endcogebalance' , '$cogememo'      ,
                 '$listid'        ,  '$loginstaname'   , now()            ,
                 '$listdate'
                 )";    
       $db->query($query);
    }else{
       $endcogebalance = $cogebalance-$quantity;
       $query = "insert into tb_commglide(
                 fd_coge_date     ,  fd_coge_sotrageid , fd_coge_commid      , 
                 fd_coge_listno   ,  fd_coge_listtype  , fd_coge_addquantity ,
                 fd_coge_lessen   ,  fd_coge_balance   , fd_coge_memo        ,
                 fd_coge_listid   ,  fd_coge_makename  , fd_coge_datetime    ,
                 fd_coge_listdate
                 )values(
                 now()            ,  '$storageid'      , '$commid'         ,
                 '$listno'        ,  '$cogelisttype'   , '0'               ,
                 '$quantity'      ,  '$endcogebalance' , '$cogememo'       ,
                 '$listid'        ,  '$loginstaname'   , now()             ,
                 '$listdate'
                 )";    
       $db->query($query);
    }
    //------------------------------------
  }
}

//$cogetype = 0; //0为增加 ， 1为减少
function kickbackcommglide($storageid , $commid , $quantity , $cogememo , $cogelisttype , $loginstaname , $listid , $listno , $cogetype , $kickbackid , $listdate){    
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_commglide where fd_coge_sotrageid = '$storageid' and fd_coge_commid = '$commid'
	          and fd_coge_listid = '$kickbackid' and fd_coge_listtype = '$cogelisttype' 
	          and (fd_coge_addquantity = '$quantity' or fd_coge_lessen = '$quantity')";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
    $query = "SELECT MAX(fd_coge_id) as coge_id FROM tb_commglide where 
              fd_coge_sotrageid = '$storageid' and fd_coge_commid = '$commid' "; 
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$maxcogeid = $db->f(coge_id);
    	
    	$query = "select fd_coge_balance from tb_commglide 
                where fd_coge_id = '$maxcogeid' ";
      $db->query($query);
      if($db->nf()){
      	$db->next_record();
      	$cogebalance = $db->f(fd_coge_balance);
      }else{
        $cogebalance = 0 ;
      }
    }else{
      $cogebalance = 0 ;
    }
    if($cogetype==0){
       $endcogebalance = $cogebalance+$quantity;
       $query = "insert into tb_commglide(
                 fd_coge_date        ,  fd_coge_sotrageid , fd_coge_commid      , 
                 fd_coge_listno      ,  fd_coge_listtype  , fd_coge_addquantity ,
                 fd_coge_lessen      ,  fd_coge_balance   , fd_coge_memo        ,
                 fd_coge_listid      ,  fd_coge_makename  , fd_coge_datetime    ,
                 fd_coge_iskickback  ,  fd_coge_listdate
                 )values( 
                 now()          ,  '$storageid'      , '$commid'        ,
                 '$listno'      ,  '$cogelisttype'   , '$quantity'      ,
                 '0'            ,  '$endcogebalance' , '$cogememo'      ,
                 '$kickbackid'  ,  '$loginstaname'   , now()            ,
                 '2'            ,  '$listdate'
                 )";    
       $db->query($query);
    }else{
       $endcogebalance = $cogebalance-$quantity;
       $query = "insert into tb_commglide(
                 fd_coge_date       ,  fd_coge_sotrageid , fd_coge_commid      , 
                 fd_coge_listno     ,  fd_coge_listtype  , fd_coge_addquantity ,
                 fd_coge_lessen     ,  fd_coge_balance   , fd_coge_memo        ,
                 fd_coge_listid     ,  fd_coge_makename  , fd_coge_datetime    ,
                 fd_coge_iskickback ,  fd_coge_listdate
                 )values(
                 now()          ,  '$storageid'      , '$commid'         ,
                 '$listno'      ,  '$cogelisttype'   , '0'               ,
                 '$quantity'    ,  '$endcogebalance' , '$cogememo'       ,
                 '$kickbackid'  ,  '$loginstaname'   , now()             ,
                 '2'            ,  '$listdate'
                 )";    
       $db->query($query);
    }
    $query = "update tb_commglide set fd_coge_iskickback = 1 
              where fd_coge_listtype = '$cogelisttype' and fd_coge_listid = '$listid' 
              and fd_coge_commid = '$commid' and fd_coge_sotrageid = '$storageid'";
    $db->query($query);//修改该单据的反冲状态
  }//------------------------------------
}
?>