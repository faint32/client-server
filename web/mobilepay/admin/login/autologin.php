<?php

function autologin($username,$password){
	$db = new DB_test ;
	$db1 = new DB_test ;
	$query = "select * from web_teller where fd_tel_name ='$username' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record(); 
		$pass   = $db->f(fd_tel_pass);   //密码
		$error  = $db->f(fd_tel_error);  //错误次数
		$recsts = $db->f(fd_tel_recsts); //是否已经被禁此
		$isin   = $db->f(fd_tel_isin);   //是否已经登陆
		$telid  = $db->f(fd_tel_id);   //id号
		
		if($pass != $password) {
			$errmsg = "密码错误!(请注意区分大小写)";
      $query = "update web_teller set fd_tel_error = fd_tel_error +1 where fd_tel_name ='$username'";
      $db1->query($query);
      $return = 2 ;
    } 
    if($error >= 6) {
      $errmsg = "由于密码错误多次,禁止使用该用户!";
      $return = 3 ;
    } 
    if($recsts == 1) {
      $errmsg = "该用户被停用!";
      $return = 4 ;
    }
    if(($pass == $password) and ($return < 2)) { // 密码正确
    	$query = "update web_teller set 			           
	        		  fd_tel_lasttime = fd_tel_intime, fd_tel_intime = now(),fd_tel_outtime = now(),
	        		  fd_tel_error = 0,fd_tel_isin  = 1 , fd_tel_onlinetime = now()
	              where fd_tel_id ='$telid'";
      $db->query($query);
      $return = 1 ;
    }
	}else{
    $errmsg = "该用户不存在!";
    $return = 9 ;
  }
  return $return;
}

?>