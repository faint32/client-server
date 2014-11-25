<?
//----------往来对帐单---------------- 
//$cacttype = 0; //0为增加 ， 1为减少
function zbcurrent($ctatlinktype , $companyid , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid ){
	/*$db  = new DB_test;   
	$query = "select fd_supp_fid from tb_supplier where fd_supp_id = '$companyid'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$fsuppid   = $db->f(fd_supp_fid);
		*/
		zbcurrentaccount($ctatlinktype , $companyid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid );
		/*if($fsuppid!=0){
  		//zbcurrent($ctatlinktype , $fsuppid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid );
	  }
	}*/
}

function zbcurrentaccount($ctatlinktype , $companyid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid ){    
	$db  = new DB_erp;   
	$ishaverecord = 0;
	$query = "select * from tb_zbcurrentaccount where fd_ctat_linkid = '$companyid' and fd_ctat_linktype = '$ctatlinktype'
	          and fd_ctat_listid = '$listid' and fd_ctat_listtype = '$cactlisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
     /*$query = "SELECT MAX(fd_ctat_id) as ctat_id FROM tb_zbcurrentaccount where 
               fd_ctat_linktype = '$ctatlinktype' and fd_ctat_linkid = '$companyid' 
               and fd_ctat_iskickback <> 1 and fd_ctat_mscid = '$mscid'"; 
     $db->query($query);
     if($db->nf()){
     	$db->next_record();
     	$maxctatid = $db->f(ctat_id);
     	
     	$query = "select fd_ctat_balance from tb_zbcurrentaccount 
                 where fd_ctat_id = '$maxctatid' ";
       $db->query($query);
       if($db->nf()){
       	$db->next_record();
       	$ctatbalance = $db->f(fd_ctat_balance);
       }else{
         $ctatbalance = 0 ;
       }
     }else{
       $ctatbalance = 0 ;
     }*/
     $endctatbalance = $ctatbalance+$addmoney-$lessenmoney;
     $query = "insert into tb_zbcurrentaccount(
               fd_ctat_date     ,  fd_ctat_linktype  , fd_ctat_linkid   , 
               fd_ctat_listno   ,  fd_ctat_listtype  , fd_ctat_addmoney ,
               fd_ctat_lessen   ,  fd_ctat_balance   , fd_ctat_memo     ,
               fd_ctat_listid   ,  fd_ctat_makename  , fd_ctat_datetime ,
               fd_ctat_listdate ,  fd_ctat_sdcrid    , fd_ctat_mscid
               )values(
               now()          ,  '$ctatlinktype'   , '$companyid'     ,
               '$listno'      ,  '$cactlisttype'   , '$addmoney'      ,
               '$lessenmoney' ,  '$endctatbalance' , '$ctatmemo'      ,
               '$listid'      ,  '$loginstaname'   , now()            ,
               '$listdate'    ,  '$sdcrid'         , '$mscid'
               )";    
     $db->query($query);
  }
  //------------------------------------
}


?>