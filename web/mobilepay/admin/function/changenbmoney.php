<?
function changenbmoney($qkorganid,$money,$type , $organid , $sdcrid ){
	//$type =0表示应收 ， 1表示应付
	$tmpflag = 0;
	$db  = new DB_test;
	if($type==0){
		$endmoney = $money;
	}else{
		$endmoney = -$money;
	}
	$query = "select * from tb_nbysyfmoney  where fd_nbysyfm_qkorganid = '$qkorganid'
	          and fd_nbysyfm_organid = '$organid' and fd_nbysyfm_sdcrid = '$sdcrid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$oldmoney = $db->f(fd_nbysyfm_money);
		$listid   = $db->f(fd_nbysyfm_id);

		$newmoney = $oldmoney + $endmoney;

		$query ="update tb_nbysyfmoney set fd_nbysyfm_money = '$newmoney'
		         where fd_nbysyfm_id = '$listid' ";
		$db->query($query);
		
		$tmpflag = 1;
	}
	$query = "select * from tb_nbysyfmoney  where fd_nbysyfm_organid = '$qkorganid'
	          and fd_nbysyfm_qkorganid = '$organid' and fd_nbysyfm_sdcrid = '$sdcrid'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$oldmoney = $db->f(fd_nbysyfm_money);
		$listid   = $db->f(fd_nbysyfm_id);

		$newmoney = $oldmoney - $endmoney;

		$query ="update tb_nbysyfmoney set fd_nbysyfm_money = '$newmoney'
		         where fd_nbysyfm_id = '$listid' ";
		       
		$db->query($query);
		
		$tmpflag = 1;
	}
	
	if($tmpflag==0){
	  $query = "insert into tb_nbysyfmoney(
	            fd_nbysyfm_qkorganid , fd_nbysyfm_money   ,
	            fd_nbysyfm_organid   , fd_nbysyfm_sdcrid
	            )values(
	            '$qkorganid'         , '$endmoney'     ,
	            '$organid'           , '$sdcrid'
	            )";
	  $db->query($query);//应收款
  }
}

?>