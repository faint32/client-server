<?
function changeaccount($authorid , $money , $type){
	//$type =0��ʾ���ӽ�Ǯ �� 1��ʾ���ٽ�Ǯ
	$db  = new DB_test;
	if($type==0){
		$endmoney = $money;
	}else{
	  $endmoney = -$money;
	}
	if($endmoney==""){
		$endmoney = 0;
	}
	/*$query = "select fd_account_money from tb_account where fd_account_id = '$accountid'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$beginmoney = $db->f(fd_account_money);	  	
		$allmoney = $beginmoney+$endmoney;
	}*/
	$query = "update tb_account set fd_acc_money = fd_acc_money + '".$endmoney."' where fd_acc_authorid = '$authorid' ";
	$db->query($query);  //���¼�¼
}
?>