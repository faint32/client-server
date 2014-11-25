<?
//----------往来对帐单---------------- 
//$cacttype = 0; //0为增加 ， 1为减少
function currentaccount($ctatlinktype , $companyid , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate  ){    
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_currentaccount where fd_ctat_linkid = '$companyid' and fd_ctat_linktype = '$ctatlinktype'
	          and fd_ctat_listid = '$listid' and fd_ctat_listtype = '$cactlisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
  
     $endctatbalance = 0;
     $query = "insert into tb_currentaccount(
               fd_ctat_date   ,  fd_ctat_linktype  , fd_ctat_linkid   , 
               fd_ctat_listno ,  fd_ctat_listtype  , fd_ctat_addmoney ,
               fd_ctat_lessen ,  fd_ctat_balance   , fd_ctat_memo     ,
               fd_ctat_listid ,  fd_ctat_makename  , fd_ctat_datetime ,
               fd_ctat_listdate
               )values(
               now()          ,  '$ctatlinktype'   , '$companyid'     ,
               '$listno'      ,  '$cactlisttype'   , '$addmoney'      ,
               '$lessenmoney' ,  '$endctatbalance' , '$ctatmemo'      ,
               '$listid'      ,  '$loginstaname'   , 'now()'            ,
               '$listdate'
               )";    
     $db->query($query);
  }
  //------------------------------------
}

//$cacttype = 0; //0为增加 ， 1为减少
function kickbackcurrentaccount($ctatlinktype , $companyid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno  , $kickbackid , $listdate ){    
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_currentaccount where fd_ctat_linkid = '$companyid' and fd_ctat_linktype = '$ctatlinktype'
	          and fd_ctat_listid = '$kickbackid' and fd_ctat_listtype = '$cactlisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
     $endctatbalance = 0;
     $query = "insert into tb_currentaccount(
               fd_ctat_date     ,  fd_ctat_linktype  , fd_ctat_linkid     , 
               fd_ctat_listno   ,  fd_ctat_listtype  , fd_ctat_addmoney   ,
               fd_ctat_lessen   ,  fd_ctat_balance   , fd_ctat_memo       ,
               fd_ctat_listid   ,  fd_ctat_makename  , fd_ctat_iskickback ,
               fd_ctat_datetime ,  fd_ctat_listdate
               )values(
               now()          ,  '$ctatlinktype'   , '$companyid'     ,
               '$listno'      ,  '$cactlisttype'   , '$addmoney'      ,
               '$lessenmoney' ,  '$endctatbalance' , '$ctatmemo'      ,
               '$kickbackid'  ,  '$loginstaname'   , '2'              ,
               now()          ,  '$listdate'
               )";    
     $db->query($query);
    
    $query = "update tb_currentaccount set fd_ctat_iskickback = 1 
              where fd_ctat_listtype = '$cactlisttype' and fd_ctat_listid = '$listid' ";
    $db->query($query);//修改该单据的反冲状态
    //------------------------------------
  }
}
?>