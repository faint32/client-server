<? 
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
$gourl = "tb_coupon_error_b.php" ;
if($gotype=="sp"){
	$gourl = "tb_couponsale_sp_b.php" ;
	$titlename="抵用券异常";	
	}
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

      	   

$t = new Template(".","keep");
$t->set_file("couponsale_error","couponsale_error.html");
switch ($action){  
	case "edit":
	$query="update tb_couponsale set
		fd_couponsale_payrq='$payrq'
		where fd_couponsale_id=$listid";
	$db->query($query);
	require ("../include/alledit.2.php");
	Header("Location: $gotourl");
		break;
	default:
	      break;
}

if(!empty($listid)){
	
	 // 编辑
    $query = "select *
	            from tb_couponsale
	            left join tb_paycard on fd_couponsale_paycardid = fd_paycard_id
	            left join tb_author on fd_author_id = fd_couponsale_authorid
               where fd_couponsale_id ='$listid'
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
    
		  
		$arrdata['no']       = $db->f(fd_couponsale_no);               //编号  
       	$arrdata['couponno']       = $db->f(fd_couponsale_couponno);             //部门名   
		$arrdata['bkntno']       = $db->f(fd_couponsale_bkntno); 
				
			$arrdata['money']       = $db->f(fd_couponsale_money);   
		    $arrdata['paycardkey']		   = $db->f(fd_paycard_key); 
		    $arrdata['authorusername']	 = $db->f(fd_author_username);
		    $arrdata['authorname']	   = $db->f(fd_author_truename);
		    $arrdata['idcard']		   = $db->f(fd_author_idcard);
		    $arrdata['datetime']	 = $db->f(fd_couponsale_datetime);    
			$arrdata['creditcardno']	 = $db->f(fd_couponsale_creditcardno);   
			$arrdata['creditcardbank']	 = $db->f(fd_couponsale_creditcardbank);   
			$arrdata['creditcardman']	 = $db->f(fd_couponsale_creditcardman);   
			$arrdata['creditcardphone']	 = $db->f(fd_couponsale_creditcardphone);  
			
			$arrdata['czman']	 = $db->f(fd_couponsale_czman);
		    $arrdata['czdatetime']	 = $db->f(fd_couponsale_czdatetime);
		    $arrdata['memo']	 = $db->f(fd_couponsale_memo);
			$arrdata['payrq']	 = $db->f(fd_couponsale_payrq);
			if($arrdata['payrq']==1)
			{
				$checksendsms1="checked";
			}
			if($arrdata['shoucardmemo']==0){
				$checksendsms0="checked";
			} 
			
			$rebuy	 = $db->f(fd_couponsale_rebuy); 			
		    if($rebuy==0)$arrdata['rebuy']="未使用"; 
		    if($rebuy==1)$arrdata['rebuy']="已回购"; 
		    if($rebuy==2)$arrdata['rebuy']="已使用";  
  }  	
}

$t->set_var($arrdata);
$t->set_var("listid"          ,$listid         );
$t->set_var("checksendsms1"        ,$checksendsms1         );
$t->set_var("checksendsms0"        ,$checksendsms0         );


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "couponsale_error"); //最后输出界面

?>