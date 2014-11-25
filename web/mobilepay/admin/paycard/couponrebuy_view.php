<?
$thismenucode = "8n004";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
$gourl = "tb_couponrebuy_his_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "pass":     //编辑记录

		  $query="update tb_couponrebuy set 
						 fd_couponrebuy_state='9'            ,  fd_couponrebuy_czman='$loginstaname'     , 
						 fd_couponrebuy_czdatetime=now() 		,  fd_couponrebuy_memo='$memo'		
						 where fd_couponrebuy_id ='$listid'"; 
		  $db->query($query);
		  
		  
		    $query = "select fd_couponrebuy_id,fd_couponrebuy_no,
	            fd_couponrebuy_money,fd_couponrebuy_couponid,
	            fd_couponrebuy_authorid,fd_couponrebuy_couponno
	            from tb_couponrebuy
               where fd_couponrebuy_id ='$listid'
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
	      $listid       = $db->f(fd_couponrebuy_id); 
	      $couponid       = $db->f(fd_couponrebuy_couponid);
	      $no       = $db->f(fd_couponrebuy_no);               //编号  
       	$couponno       = $db->f(fd_couponrebuy_couponno);             //部门名              
        $money       = $db->f(fd_couponrebuy_money);  
		    $authorid	 = $db->f(fd_couponrebuy_authorid);
		    $memo = "回购优惠劵".$couponno."支付金额".$money."元";
		    changecoupon($listid,$no,"rebuy",$money,$couponno,$authorid,"",$memo);
	    }
		  
		  $query = "update tb_couponsale set fd_couponsale_rebuy = 1 where fd_couponsale_id = '$couponid'";
		  $db->query($query);
		  
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("couponrebuy","couponrebuy_view.html");

if(!empty($listid)){
	
	 // 编辑
    $query = "select fd_couponrebuy_id,fd_couponrebuy_no, fd_couponrebuy_datetime,fd_couponsale_money,
	                             fd_author_username,fd_author_truename,fd_bank_name,fd_couponrebuy_bankcardno,
	                             fd_author_idcard,fd_couponrebuy_couponno ,fd_couponrebuy_sxfmoney,
	                             fd_couponrebuy_money,fd_couponsale_czman,fd_couponsale_czdatetime,
	                             fd_couponsale_memo
	                            from tb_couponrebuy
	                            left join tb_couponsale on fd_couponsale_id = fd_couponrebuy_couponid
	                            left join tb_bank on fd_couponrebuy_bankid = fd_bank_id
	                           left join tb_paycard on fd_couponsale_paycardid = fd_paycard_id
	                           left join tb_author on fd_author_id = fd_couponsale_authorid
                             where fd_couponrebuy_id ='$listid'
                              " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
	      $listid          = $db->f(fd_couponrebuy_id); 
       	$no              = $db->f(fd_couponrebuy_no);               //编号  
       	$couponno        = $db->f(fd_couponrebuy_couponno);             //部门名              
        $couponmoney     = $db->f(fd_couponsale_money);  
        $sxfmoney        = $db->f(fd_couponrebuy_sxfmoney); 
        $money           = $db->f(fd_couponrebuy_money);  
		    $bankname		     = $db->f(fd_bank_name); 
		    $bankcardno		   = $db->f(fd_couponrebuy_bankcardno);
		    $authorusername	 = $db->f(fd_author_username);
		    $authorname	     = $db->f(fd_author_truename);
		    $idcard		       = $db->f(fd_author_idcard);
		    $datetime	       = $db->f(fd_couponrebuy_datetime);   
		    $czman	 = $db->f(fd_couponsale_czman);
		    $czdatetime	 = $db->f(fd_couponsale_czdatetime);
		    $memo	 = $db->f(fd_couponsale_memo);         
  }  	
}


$t->set_var("listid"          ,$listid         );
$t->set_var("no"              ,$no       );
$t->set_var("datetime"        ,$datetime        );

$t->set_var("couponno"          ,$couponno        );
$t->set_var("money"       	    ,$money        );
$t->set_var("sxfmoney"          ,$sxfmoney        );
$t->set_var("couponmoney"       ,$couponmoney        );
$t->set_var("authorusername"    ,$authorusername        );
$t->set_var("authorname"        ,$authorname        );
$t->set_var("idcard"            ,$idcard        );
$t->set_var("bankname"          ,$bankname        );
$t->set_var("bankcardno"        ,$bankcardno        );
$t->set_var("czman"          ,$czman        );
$t->set_var("czdatetime"          ,$czdatetime        );
$t->set_var("memo"          ,$memo        );

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "couponrebuy"); //最后输出界面

?>