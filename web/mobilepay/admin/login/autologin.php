<?php

function autologin($username,$password){
	$db = new DB_test ;
	$db1 = new DB_test ;
	$query = "select * from web_teller where fd_tel_name ='$username' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record(); 
		$pass   = $db->f(fd_tel_pass);   //����
		$error  = $db->f(fd_tel_error);  //�������
		$recsts = $db->f(fd_tel_recsts); //�Ƿ��Ѿ�������
		$isin   = $db->f(fd_tel_isin);   //�Ƿ��Ѿ���½
		$telid  = $db->f(fd_tel_id);   //id��
		
		if($pass != $password) {
			$errmsg = "�������!(��ע�����ִ�Сд)";
      $query = "update web_teller set fd_tel_error = fd_tel_error +1 where fd_tel_name ='$username'";
      $db1->query($query);
      $return = 2 ;
    } 
    if($error >= 6) {
      $errmsg = "�������������,��ֹʹ�ø��û�!";
      $return = 3 ;
    } 
    if($recsts == 1) {
      $errmsg = "���û���ͣ��!";
      $return = 4 ;
    }
    if(($pass == $password) and ($return < 2)) { // ������ȷ
    	$query = "update web_teller set 			           
	        		  fd_tel_lasttime = fd_tel_intime, fd_tel_intime = now(),fd_tel_outtime = now(),
	        		  fd_tel_error = 0,fd_tel_isin  = 1 , fd_tel_onlinetime = now()
	              where fd_tel_id ='$telid'";
      $db->query($query);
      $return = 1 ;
    }
	}else{
    $errmsg = "���û�������!";
    $return = 9 ;
  }
  return $return;
}

?>