<?
function updatesubject($listtype , $listid , $flstno,$money,$type,$organid){
	$db  = new DB_erp;
	
	if($money!=0){
	   if($type==0){
	   	$endmoney = $money;
	   }else{
	     $endmoney = -$money;
     }
     
     $query = "select * from tb_fiscalsubject where fd_flst_no = '$flstno' and fd_flst_organid = '$organid'";
     $db->query($query);
     if($db->nf()){
     	$db->next_record();
     	$flstname = $db->f(fd_flst_name);
     	$oldnowmoney = $db->f(fd_flst_nowmoney);
     	$oldmonthmoney = $db->f(fd_flst_monthmoney);
     	
     	$newnowmoney = $oldnowmoney + $endmoney;
     	$newmonthmoney = $oldmonthmoney + $endmoney;
     	
     	$query = "update tb_fiscalsubject set 
                fd_flst_nowmoney   = '$newnowmoney'    ,
                fd_flst_monthmoney = '$newmonthmoney'  
                where fd_flst_no = '$flstno' and fd_flst_organid = '$organid'";
      $db->query($query);  //修改科目金额
     }  //查出科目名称
     
     $query = "insert into tb_subjectdetail(
               fd_stdl_listtype , fd_stdl_listid , fd_stdl_flstno ,
               fd_stdl_flstname , fd_stdl_money  , fd_stdl_organid
               )values(
               '$listtype'      , '$listid'      , '$flstno'      ,
               '$flstname'      , '$endmoney'    , '$organid'
               )";
	   $db->query($query);  //插入科目详情
  }
}

?>