<?
//帐户往来对帐单
function accountcussent($accountid ,$supaccountid , $allmoney  , $listtype , $makename , $listid , $listno  , $listdate , $memo){   
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_accountcussent where fd_atct_accountid = '$accountid' and fd_atct_supaccountid = '$supaccountid'
	          and fd_atct_listid = '$listid' and fd_atct_listtype = '$listtype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($allmoney<>0 && $ishaverecord == 0 ){

    $query = "insert into tb_accountcussent(
              fd_atct_listid     ,  fd_atct_listno     , fd_atct_listtype     , 
              fd_atct_listdate   ,  fd_atct_accountid  , fd_atct_supaccountid ,
              fd_atct_date       ,  fd_atct_money      , fd_atct_makename     ,
              fd_atct_memo       ,  fd_atct_datetime  
              )values(
              '$listid'          ,  '$listno'      , '$listtype'          ,
              '$listdate'        ,  '$accountid'   , '$supaccountid'      ,
              now()              ,  '$allmoney'    , '$makename'          ,
              '$memo'            ,  now()             
              )";    
    $db->query($query);

  }
}

//帐户往来对帐单
function kickbackaccountcussent($accountid ,$supaccountid , $allmoney  , $listtype , $makename , $listid , $listno  , $listdate , $memo ,$kickbackid ){    
	$db  = new DB_test;   
	$ishaverecord = 0;
	$query = "select * from tb_accountcussent where fd_atct_accountid = '$accountid' and fd_atct_supaccountid = '$supaccountid'
	          and fd_atct_listid = '$kickbackid' and fd_atct_listtype = '$listtype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($allmoney<>0 && $ishaverecord == 0 ){
    
    $query = "insert into tb_accountcussent(
              fd_atct_listid     ,  fd_atct_listno     , fd_atct_listtype     , 
              fd_atct_listdate   ,  fd_atct_accountid  , fd_atct_supaccountid ,
              fd_atct_date       ,  fd_atct_money      , fd_atct_makename     ,
              fd_atct_memo       ,  fd_atct_datetime   , fd_atct_iskickback
              )values(
              '$kickbackid'      ,  '$listno'      , '$listtype'          ,
              '$listdate'        ,  '$accountid'   , '$supaccountid'      ,
              now()              ,  '$allmoney'    , '$makename'          ,
              '$memo'            ,  now()          , '2'
              )";    
    $db->query($query);
    
    $query = "update tb_accountcussent set fd_atct_iskickback = 1 
              where fd_atct_listtype = '$listtype' and fd_atct_listid = '$listid' 
              and fd_atct_accountid = '$accountid'";
    $db->query($query);//修改该单据的反冲状态
  }
}
?>