<?
//计算商品数量的吨数
function readmember($memid){
	 $db = new DB_test;
	 
	 $query = "select fd_organmem_phone,fd_organmem_mobile,fd_organmem_address,fd_organmem_comnpany,fd_organmem_linkman,fd_organmem_email,
	                  fd_organmem_province,fd_organmem_city,fd_organmem_county,fd_organmem_username,fd_organmem_cusname,fd_organmem_cusid,
	                  fd_organmem_cusno,fd_organmem_isms
	           from tb_organmem 
	           where fd_organmem_id = '$memid'";
	 $db->query($query);
	 if($db->nf()){
	   $db->next_record();
	   $phone    = $db->f(fd_organmem_phone);
	   $mobile   = $db->f(fd_organmem_mobile);
	   $address  = $db->f(fd_organmem_address);
	   $comnpany = $db->f(fd_organmem_comnpany);
	   $linkman  = $db->f(fd_organmem_linkman);
	   $email    = $db->f(fd_organmem_email);    	   
	   $province = $db->f(fd_organmem_province);
	   $city     = $db->f(fd_organmem_city);
	   $county   = $db->f(fd_organmem_county);    
	   $username = $db->f(fd_organmem_username);
	   $cusname  = $db->f(fd_organmem_cusname);
	   $cusid    = $db->f(fd_organmem_cusid);
	   $cusno    = $db->f(fd_organmem_cusno);
	   $isms    = $db->f(fd_organmem_isms);
	 }
	 
	 return $phone."@@@".$mobile."@@@".$address."@@@".$comnpany."@@@".$linkman."@@@".$email."@@@".$province."@@@".$city."@@@".$county."@@@".$username."@@@".$cusid."@@@".$cusno."@@@".$cusname."@@@".$isms;
}


?>