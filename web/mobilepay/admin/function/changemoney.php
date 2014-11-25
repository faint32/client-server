<?
function changemoney($unittype,$clientid,$money,$type){
	//$type =0表示应收 ， 1表示应付
	//$unittype = 2 表示供应商，1代表客户
	//$clientid 往来单位id号
	$db  = new DB_test;
	if($type==0){
		$endmoney = $money;
	}else{
		$endmoney = -$money;
	}
	$query = "select * from tb_ysyfmoney where fd_ysyfm_type = '$unittype'
	          and fd_ysyfm_companyid = '$clientid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$oldmoney = $db->f(fd_ysyfm_money);
		$listid = $db->f(fd_ysyfm_id);

		$newmoney = $oldmoney + $endmoney;

		$query ="update tb_ysyfmoney set fd_ysyfm_money = '$newmoney'
		         where fd_ysyfm_id = '$listid' ";
		$db->query($query);
	}else{
	  $query = "insert into tb_ysyfmoney(
	            fd_ysyfm_type     , fd_ysyfm_companyid , fd_ysyfm_money       
	            )values(
	            '$unittype'   , '$clientid'        , '$endmoney'    
	            )";
	  $db->query($query);
  }
}

?>