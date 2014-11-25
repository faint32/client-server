<?
function changecoupon($listid,$no,$type,$money,$couponno,$authorid,$paycardid=null,$memo){
$db=new db_test;
switch ($type){ 
	case "sale":
	$query = "insert into tb_couponglide  (
		          fd_couponglide_no , fd_couponglide_type , fd_couponglide_addmoney ,
		          fd_couponglide_lessmoney  , fd_couponglide_datetime , fd_couponglide_memo ,
		          fd_couponglide_man , fd_couponglide_couponno , fd_couponglide_authorid ,
		          fd_couponglide_paycardid ,fd_couponglide_listid
		          )values(
		          '$no' , '$type' , '0' , 
		          '$money' , now() , '$memo' , 
		          '$loginstaname' , '$couponno' , '$authorid' ,
		          '$paycardid' , '$listid'
		          )
		    ";
		  $db->query($query);
	break;
	case "rebuy":
	$query = "insert into tb_couponglide  (
		          fd_couponglide_no , fd_couponglide_type , fd_couponglide_addmoney ,
		          fd_couponglide_lessmoney  , fd_couponglide_datetime , fd_couponglide_memo ,
		          fd_couponglide_man , fd_couponglide_couponno , fd_couponglide_authorid ,
		          fd_couponglide_paycardid ,fd_couponglide_listid
		          )values(
		          '$no' , '$type' , '$money' , 
		          '0' , now() , '$memo' , 
		          '$loginstaname' , '$couponno' , '$authorid' ,
		          '$paycardid' , '$listid'
		          )
		    ";
		  $db->query($query);
	break;
	
		case "use":
	$query = "insert into tb_couponglide  (
		          fd_couponglide_no , fd_couponglide_type , fd_couponglide_addmoney ,
		          fd_couponglide_lessmoney  , fd_couponglide_datetime , fd_couponglide_memo ,
		          fd_couponglide_man , fd_couponglide_couponno , fd_couponglide_authorid ,
		          fd_couponglide_paycardid ,fd_couponglide_listid
		          )values(
		          '$no' , '$type' , '0' , 
		          '0' , now() , '$memo' , 
		          '$loginstaname' , '$couponno' , '$authorid' ,
		          '$paycardid' , '$listid'
		          )
		    ";
		  $db->query($query);
	break;
}
}

/*
$inorout in为增加,out未减少
$payfee 手续费
$tabname 交易类型表 
$transid 交易ID
*/
function changeaccountno($inorout,$payfee,$paycardid,$memo,$paymode,$tabname,$transid)
{
	$db  = new DB_test;
	$no=makeorderno("tb_payfeelist","payfee","payfee");
	if($inorout=="in")
	{
		$money="fd_payfee_addmoney";
	}
	if($inorout=="out")
	{
		$money="fd_payfee_lessmoney";
	}
	$query="insert into tb_payfeelist 
	(fd_payfee_no,fd_payfee_inorout,$money,fd_payfee_datetime,fd_payfee_paycardid,
	fd_payfee_memo,fd_payfee_paymode,fd_payfee_tabname,fd_payfee_transid)
	values
	('$no','$inorout','$payfee',now(),'$paycardid','$memo','$paymode','$tabname','$transid')";
	
	 $db->query($query);
}
function makeorderno($tablename,$fieldname,$preno="pay")
{
	 $db  = new DB_test;
	  $db2  = new DB_test;
	  $year  = trim(date( "Y", mktime())) ;   
       $month = trim(date( "m", mktime())) ;   
       $day   = trim(date( "d", mktime())) ;   
		$strlenght=strlen($preno);
       $nowdate = $year.$month.$day  ;  
       $query = "select fd_".$fieldname."_no as no from ".$tablename."   order by fd_".$fieldname."_no  desc";
       $db2->query($query);
       if($db2->nf()){
	     $db2->next_record();
         $orderno   = $db2->f(no);		 
         $orderdate = substr($orderno,$strlenght,8);       //截取前8位判断是否当前日期   
         if($nowdate == $orderdate){
			$newlenght=$strlenght+11;
        	 $orderno = substr($orderno,$newlenght,14) + 1 ;	  //是当前日期流水帐加1
        	 if($orderno < 10){
        	   $orderno="00000".$orderno  ;      	  	
        	 }else if($orderno < 100){
        	   $orderno="0000".$orderno   ;     	  	
        	 }else if($orderno < 1000){
        	   $orderno="000".$orderno   ;     	  	
        	 }else if($orderno < 10000){
        	   $orderno="00".$orderno   ;     	  	
        	 }else{
        	   $orderno=$orderno;
         	 }        	  
        	 $orderno=$preno.$nowdate.$orderno;       	  
        }else{

        	$orderno = $preno.$nowdate."000001" ;       	    //不是当前日期,为5位流水帐,开始值为1。
        }         
       }else{
	       $orderno  = $preno.$nowdate."000001" ;      	
       }
	   return $orderno;
}
?>