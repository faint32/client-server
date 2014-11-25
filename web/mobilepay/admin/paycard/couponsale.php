<?
$thismenucode = "2n302";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
$gourl = "tb_couponsale_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "pass":     //编辑记录

		  $query="update tb_couponsale set 
						 fd_couponsale_state='9'            ,  fd_couponsale_czman='$loginstaname'     , 
						 fd_couponsale_czdatetime=now() 		,  fd_couponsale_memo='$memo'		
						 where fd_couponsale_id ='$listid'"; 
		  $db->query($query);
		  
		  
		    $query = "select fd_couponsale_id,fd_couponsale_no,
	            fd_couponsale_money,fd_couponsale_paycardid,
	            fd_couponsale_authorid,fd_couponsale_couponno
	            from tb_couponsale
               where fd_couponsale_id ='$listid'
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
	      $listid       = $db->f(fd_couponsale_id); 
	      $no       = $db->f(fd_couponsale_no);               //编号  
       	$couponno       = $db->f(fd_couponsale_couponno);             //部门名              
        $money       = $db->f(fd_couponsale_money);   
		    $paycardid		   = $db->f(fd_couponsale_paycardid); 
		    $authorid	 = $db->f(fd_couponsale_authorid);
		    $memo = "购买优惠劵".$couponno."消费金额".$money."元";
		    changecoupon($listid,$no,"sale",$money,$couponno,$authorid,$paycardid,$memo);
	    }
		  
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("couponsale","couponsale.html");

if(!empty($listid)){
	
	 // 编辑
    $query = "select fd_couponsale_id,
					fd_couponsale_no,
					fd_couponsale_bkntno,
					fd_couponsale_datetime,
					fd_couponsale_creditcardno,
					fd_couponsale_creditcardbank,
					fd_couponsale_creditcardman,
					fd_couponsale_creditcardphone,
	            	fd_couponsale_money,
					fd_paycard_no,
					fd_author_username,
					fd_author_truename,
	            	fd_author_idcard,
					fd_couponsale_couponno,
					fd_couponsale_isagentpay,
					fd_couponsale_agentdate
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
		    $arrdata['paycardno']		   = $db->f(fd_paycard_no); 
		    $arrdata['authorusername']	 = $db->f(fd_author_username);
		    $arrdata['authorname']	   = $db->f(fd_author_truename);
		    $arrdata['idcard']		   = $db->f(fd_author_idcard);
		    $arrdata['datetime']	 = $db->f(fd_couponsale_datetime);    
			$arrdata['creditcardno']	 = $db->f(fd_couponsale_creditcardno);   
			$arrdata['creditcardbank']	 = $db->f(fd_couponsale_creditcardbank);   
			$arrdata['creditcardman']	 = $db->f(fd_couponsale_creditcardman);   
			$arrdata['creditcardphone']	 = $db->f(fd_couponsale_creditcardphone); 
			$arrdata['payrq']	   = $db->f(fd_couponsale_payrq);
			$arrdata['agentdate']      = $db->f(fd_couponsale_agentdate);
			if($arrdata['agentdate']=="0000-00-00 00:00:00"){
				$arrdata['agentdate']="-";
			}else{
				$arrdata['agentdate']=date("Y-m-d H:i:s",mktime($arrdata['agentdate']));
			}
			$arrdata['isagentpay']      = $db->f(fd_couponsale_isagentpay);
			if($arrdata['isagentpay']==1)
			{
				$arrdata['isagentpay']="是";
				$tj="
				<input type=button name=save3 class=button_save value='审批' AccessKey=s  onClick='javascript:submit_save()' style='font-size:9pt'  onMouseOver='this.className='button_save_on'' onMouseOut='this.className='button_save''>";
			}else{
				$arrdata['isagentpay']="否";
				$tj="";
				}
			if($arrdata['payrq']==01)
			{
				$arrdata['payrq']="请求交易";
			}elseif($arrdata['payrq']==00){
				$arrdata['payrq']="交易完成";
			}else{
				$arrdata['payrq']="交易取消";
			}  
  }  	
}

$t->set_var($arrdata);
$t->set_var("listid"          ,$listid         );
$t->set_var("tj"        ,$tj         );
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "couponsale"); //最后输出界面

?>