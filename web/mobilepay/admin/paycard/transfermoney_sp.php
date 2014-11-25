<?
$thismenucode = "2n403";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
if($type=="check")
{
	$gourl = "tb_transfermoney_h_b.php" ;
   $titlename="转账汇款支付历史";
}elseif($type=="error"){
	$gourl = "tb_transfermoney_error_b.php" ;
	$titlename="转账汇款支付异常";	
}else{
	$gourl = "tb_transfermoney_sp_b.php" ;
	$titlename="转账汇款支付复核";
	$note="<div style='margin-top:10px; font-size:12px; color:#060'>注意：银联代付还没有完成前，不能提交审核，请先通过代付款申请付款这笔单据！</div> ";	
}
if($gotype=="sp"){
	$gourl = "tb_transfermoney_sp_b.php" ;
	$titlename="转账汇款支付异常";	
	}
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "pass":     //编辑记录
		$query="update tb_transfermoneyglist set 
		fd_tfmglist_state='$isok',fd_tfmglist_qrdatetime=now(), fd_tfmglist_qrman='$loginstaname' 
		where fd_tfmglist_id=$listid";
		   $db->query($query);
		   $query1="select * from tb_transfermoneyglist 
		   left join tb_paycard on fd_tfmglist_paycardid = fd_paycard_id
		   where fd_tfmglist_id=$listid ";
		   $db->query($query1);
		   while($db->next_record())
		   {
			$paycardid  = $db->f(fd_paycard_no);
			$memo	    = $db->f(fd_tfmglist_memo);
			$paymode	= $db->f(fd_tfmglist_arrivemode);
			$payfee	    = $db->f(fd_tfmglist_payfee);
		   }
		 changeaccountno('in',$payfee,$paycardid,$memo,$paymode,'tb_transfermoneyglist',$listid);
	    echo "<script>alert('已成功确定转账!');location.href='$gourl'</script>";
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("transfermoney_sp","transfermoney_sp.html");

if(!empty($listid)){$where = "fd_tfmglist_id ='$listid'";}
if(!empty($bkordernumber)){$where = "fd_tfmglist_bkordernumber ='$bkordernumber'";}
//if(!empty($listid)){	
	 // 编辑
    $query = "select * from tb_transfermoneyglist
	            left join tb_paycard on fd_tfmglist_paycardid = fd_paycard_id
	            left join tb_author on fd_author_id = fd_tfmglist_authorid
				left join tb_arrive on fd_arrive_id = fd_tfmglist_arriveid	
               where $where
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
			$arrdata['no']             = $db->f(fd_tfmglist_no);               //编号  
			$arrdata['paycardno']      = $db->f(fd_paycard_no); 
			$arrdata['bkntno']         = $db->f(fd_tfmglist_bkntno); 			
			$arrdata['authorname']	   = $db->f(fd_author_truename);
			$arrdata['paydate']	       = $db->f(fd_tfmglist_paydate);
			
			$arrdata['shoucardno']	   = $db->f(fd_tfmglist_shoucardno);
			$arrdata['shoucardbank']   = $db->f(fd_tfmglist_shoucardbank);
			$arrdata['shoucardmobile']	   = $db->f(fd_tfmglist_shoucardmobile);
			$arrdata['shoucardman']   = $db->f(fd_tfmglist_shoucardman);
			
		    $arrdata['fucardno']	   = $db->f(fd_tfmglist_fucardno);
			$arrdata['fucardbank']	   = $db->f(fd_tfmglist_fucardbank);
		    $arrdata['fucardman']	   = $db->f(fd_tfmglist_fucardman);
			$arrdata['fucardmobile']   = $db->f(fd_tfmglist_fucardmobile);			
			
			$arrdata['current']	       = $db->f(fd_tfmglist_current);
			$arrdata['paymoney']	   = $db->f(fd_tfmglist_paymoney);
			$arrdata['payfee']	       = $db->f(fd_tfmglist_payfee);
			$arrdata['money']	       = $db->f(fd_tfmglist_money);
			$arrdata['smsphone']	   = $db->f(fd_tfmglist_smsphone);
			$arrdata['memo']	       = $db->f(fd_tfmglist_memo);	
			$arrdata['arrivetime']	   = $db->f(fd_tfmglist_arrivedate);
			$arrdata['payrq']	   = $db->f(fd_tfmglist_payrq);
			
			//$arrdata['sendsms']	       = $db->f(fd_tfmglist_sendsms);
			//$arrdata['shoucardmemo']   = $db->f(fd_tfmglist_shoucardmemo);
			$arrdata['arrvename']      = $db->f(fd_arrive_name);
			$arrdata['agentdate']      = $db->f(fd_repmglist_agentdate);
			if($arrdata['agentdate']=="0000-00-00 00:00:00"){
				$arrdata['agentdate']="-";
			}else{
				$arrdata['agentdate']=date("Y-m-d H:i:s",mktime($arrdata['agentdate']));
			}
			$arrdata['isagentpay']      = $db->f(fd_tfmglist_isagentpay);
			if($arrdata['isagentpay']==1)
			{
				$arrdata['isagentpay']="是";
				if($type==""){
				$tj="
				<input type=button name=save3 class=button_save {isshow} value='提交' AccessKey=s  onClick='javascript:submit_save()' style='font-size:9pt'  onMouseOver='this.className='button_save_on'' onMouseOut='this.className='button_save''>";
				}
			}else{
				$arrdata['isagentpay']="否";
				$tj="";
				}			
/* 			if($arrdata['shoucardmemo']==1)
			{
				$checksendsms1="checked";
			}
			if($arrdata['shoucardmemo']==0){
				$checksendsms0="checked";
			} */
			if($arrdata['payrq']==01)
			{
				$arrdata['payrq']="请求交易";
			}elseif($arrdata['payrq']==00){
				$arrdata['payrq']="交易完成";
			}else{
				$arrdata['payrq']="交易取消";
			}
			if($type=="check")
			{
				$arrdata['qrman']	       = $db->f(fd_tfmglist_qrman); 
				$arrdata['qrdatetime']	   = $db->f(fd_tfmglist_qrdatetime);
				$checked="checked disabled";
				$isshow="style='display:none'";
			}else{
				$arrdata['qrdatetime']=date("Y-m-d H:i:s");
				$arrdata['qrman']=$loginstaname;
				 
			}
			if($type=="error")
			{
				$isshow="style='display:none'";
				$isshowqrmsg="style='display:none'";
			}

  }  	
//}
$t->set_var("isshow"       ,$isshow         );
$t->set_var("isshowqrmsg"  ,$isshowqrmsg    );
$t->set_var("check"        ,$checked         );
//$t->set_var("checksendsms1"        ,$checksendsms1         );
//$t->set_var("checksendsms0"        ,$checksendsms0         );
$t->set_var("listid"        ,$listid         );
$t->set_var("tj"        ,$tj         );
$t->set_var("note"        ,$note         );
$t->set_var($arrdata);

$t->set_var("titlename",$titlename);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "transfermoney_sp"); //最后输出界面

?>