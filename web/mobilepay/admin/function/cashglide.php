<?
//�ʻ���ˮ��
//$cogetype = 0; //0Ϊ���� �� 1Ϊ����
function cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype , $listdate ){   
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_cashglide where fd_chge_accountid = '$accountid' 
	          and fd_chge_listid = '$listid' and fd_chge_listtype = '$chgelisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($allmoney<>0 && $ishaverecord == 0){
    $query = "SELECT MAX(fd_chge_id) as chge_id FROM tb_cashglide where 
              fd_chge_accountid = '$accountid' and fd_chge_iskickback <> 1"; 
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$maxchgeid = $db->f(chge_id);
    	
    	$query = "select fd_chge_balance from tb_cashglide 
                where fd_chge_id = '$maxchgeid' ";
      $db->query($query);
      if($db->nf()){
      	$db->next_record();
      	$chgebalance = $db->f(fd_chge_balance);
      }else{
        $chgebalance = 0 ;
      }
    }else{
      $chgebalance = 0 ;
    }
    if($allmoney!=0){  //������0 �ĲŲ�������
      if($cogetype==0){
         $endchgebalance = $chgebalance+$allmoney;
         $query = "insert into tb_cashglide(
                   fd_chge_date     ,  fd_chge_accountid , fd_chge_listid   , 
                   fd_chge_listno   ,  fd_chge_listtype  , fd_chge_addmoney ,
                   fd_chge_lessen   ,  fd_chge_balance   , fd_chge_memo     ,
                   fd_chge_makename ,  fd_chge_datetime  , fd_chge_listdate
                   )values(
                   now()          ,  '$accountid'      , '$listid'        ,
                   '$listno'      ,  '$chgelisttype'   , '$allmoney'      ,
                   '0'            ,  '$endchgebalance' , '$chgememo'      ,
                   '$loginstaname',  now()             , '$listdate'
                   )";    
         $db->query($query);
      }else{
         $endchgebalance = $chgebalance-$allmoney;
         $query = "insert into tb_cashglide(
                   fd_chge_date     ,  fd_chge_accountid , fd_chge_listid   , 
                   fd_chge_listno   ,  fd_chge_listtype  , fd_chge_addmoney ,
                   fd_chge_lessen   ,  fd_chge_balance   , fd_chge_memo     ,
                   fd_chge_makename ,  fd_chge_datetime  , fd_chge_listdate
                   )values(
                   now()          ,  '$accountid'      , '$listid'        ,
                   '$listno'      ,  '$chgelisttype'   , '0'              ,
                   '$allmoney'    ,  '$endchgebalance' , '$chgememo'      ,
                   '$loginstaname',  now()             , '$listdate'
                   )";    
         $db->query($query);
      }
    }
  }
  //------------------------------------
}

//$cogetype = 0; //0Ϊ���� �� 1Ϊ����
//$chgememo = "���ݺ����ʻ����ӽ�".$ielidlcost."Ԫ";
//$chgelisttype = "14";
function kickbackcashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype , $kickbackid , $listdate){    
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_cashglide where fd_chge_accountid = '$accountid' 
	          and fd_chge_listid = '$kickbackid' and fd_chge_listtype = '$chgelisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($allmoney<>0 && $ishaverecord == 0){ 
    //----------�ʻ���ˮ��----------------          
      $query = "SELECT MAX(fd_chge_id) as chge_id FROM tb_cashglide where 
                fd_chge_accountid = '$accountid' "; 
      $db->query($query);
      if($db->nf()){
      	$db->next_record();
      	$maxchgeid = $db->f(chge_id);
      	
      	$query = "select fd_chge_balance from tb_cashglide 
                  where fd_chge_id = '$maxchgeid' ";
        $db->query($query);
        if($db->nf()){
        	$db->next_record();
        	$chgebalance = $db->f(fd_chge_balance);
        }else{
          $chgebalance = 0 ;
        }
      }else{
        $chgebalance = 0 ;
      }
      if($allmoney!=0){  //������0 �ĲŲ��ȥ��
        if($cogetype==0){
          $endchgebalance = $chgebalance+$allmoney;
          $query = "insert into tb_cashglide(
                    fd_chge_date   ,  fd_chge_accountid , fd_chge_listid     , 
                    fd_chge_listno ,  fd_chge_listtype  , fd_chge_addmoney   ,
                    fd_chge_lessen ,  fd_chge_balance   , fd_chge_memo       ,
                    fd_chge_makename, fd_chge_datetime  , fd_chge_iskickback ,
                    fd_chge_listdate
                    )values(
                    now()          ,  '$accountid'      , '$kickbackid'    ,
                    '$listno'      ,  '$chgelisttype'   , '$allmoney'      ,
                    '0'            ,  '$endchgebalance' , '$chgememo'      ,
                    '$loginstaname',  now()             , '2'              ,
                    '$listdate'
                    )";    
          $db->query($query);
        }else{
          $endchgebalance = $chgebalance-$allmoney;
          $query = "insert into tb_cashglide(
                    fd_chge_date   ,  fd_chge_accountid , fd_chge_listid     , 
                    fd_chge_listno ,  fd_chge_listtype  , fd_chge_addmoney   ,
                    fd_chge_lessen ,  fd_chge_balance   , fd_chge_memo       ,
                    fd_chge_makename, fd_chge_datetime  , fd_chge_iskickback ,
                    fd_chge_listdate
                    )values(
                    now()          ,  '$accountid'      , '$kickbackid'    ,
                    '$listno'      ,  '$chgelisttype'   , '0'              ,
                    '$allmoney'    ,  '$endchgebalance' , '$chgememo'      ,
                    '$loginstaname',  now()             , '2'              ,
                    '$listdate'
                    )";    
          $db->query($query);
        }
        $query = "update tb_cashglide set fd_chge_iskickback = 1 
                   where fd_chge_listtype = '$chgelisttype' and fd_chge_listid = '$listid' 
                   and fd_chge_accountid = '$accountid'";
        $db->query($query);//�޸ĸõ��ݵķ���״̬
      }
  }
  //------------------------------------
}
?>