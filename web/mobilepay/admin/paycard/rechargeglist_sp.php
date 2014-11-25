<?
$thismenucode = "2n501";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
if($type=="check")
{
	$gourl = "tb_rechargeglist_h_b.php" ;
	$titlename="充值支付历史";
}elseif($type=="error"){
	$gourl = "tb_rechargeglist_error_b.php" ;
	$titlename="充值支付异常";
}else{
	$gourl = "tb_rechargeglist_sp_b.php" ;
	$titlename="充值支付复核";
}


if($type=='error')
{
$payrpwhere="fd_rechargelist_payrq='01'";
}else{

$payrpwhere="fd_rechargelist_payrq='00'";
}
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
	

$t = new Template(".","keep");
$t->set_file("rechargeglist_sp","rechargeglist_sp.html");
echo $action."2";
switch($action)
{
	case 'pass':
	$query="update tb_rechargeglist set 
		fd_rechargelist_state='$isok',fd_rechargelist_qrdatetime=now(), fd_rechargelist_qrman='$loginstaname' 
		where fd_rechargelist_id=$listid";
	$db->query($query);
	require("../include/alledit.2.php");
	echo "<script>alert('已成功确定转账!');location.href='$gourl'</script>";
	break;
}

if(!empty($listid)){
	$where = "and fd_rechargelist_id ='$listid'";
}
if(!empty($bkordernumber)){
	$where = "and fd_rechargelist_bkordernumber ='$bkordernumber'";
}


	 // 编辑
    $query = "select * from tb_rechargeglist
	            left join tb_paycard on fd_rechargelist_paycardid = fd_paycard_id
	            left join tb_author on fd_author_id = fd_rechargelist_authorid
               where $payrpwhere  $where 
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
			$arrdata['no']             = $db->f(fd_rechargelist_no);               //编号  
			$arrdata['bankname']       = $db->f(fd_rechargelist_bankname); 
			$arrdata['bankcardno']     = $db->f(fd_rechargelist_bankcardno); 			
			$arrdata['bankname']	   = $db->f(fd_rechargelist_bankname);
			$arrdata['bkntno']	       = $db->f(fd_rechargelist_bkntno);
			
			$arrdata['money']	   = $db->f(fd_rechargelist_money);
			$arrdata['banktype']   = $db->f(fd_rechargelist_banktype);
			$arrdata['datetime'] = $db->f(fd_rechargelist_datetime);
			
			$arrdata['qrdatetime'] = date("Y-m-d H:i:s",time());
			$arrdata['qrman'] = $loginstaname;
			
			if($arrdata['banktype']=="creditcard"){$arrdata['banktype']="信用卡";}
			if($arrdata['banktype']=="depositcard"){$arrdata['banktype']="储蓄卡";}
			
			$arrdata['agentdate']      = $db->f(fd_rechargeglist_agentdate);
			if($arrdata['agentdate']=="0000-00-00 00:00:00"){
				$arrdata['agentdate']="-";
			}else{
				$arrdata['agentdate']=date("Y-m-d H:i:s",mktime($arrdata['agentdate']));
			}
			$arrdata['isagentpay']      = $db->f(fd_rechargeglist_isagentpay);
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
			
			if($type=="check")
			{
				$checked="checked disabled";
				$isshow="style='display:none'";
				$arrdata['qrdatetime'] = $db->f(fd_rechargelist_qrdatetime);
				$arrdata['qrman'] = $db->f(fd_rechargelist_qrman);
			}
			if($type=="error")
			{
				$isshow="style='display:none'";
				$isshowqrmsg="style='display:none'";
				$arrdata['qrdatetime'] = $db->f(fd_rechargelist_qrdatetime);
				$arrdata['qrman'] = $db->f(fd_rechargelist_qrman);
			}

  }  	

$t->set_var("isshowqrmsg"  ,$isshowqrmsg    );
$t->set_var("isshow"       ,$isshow         );
$t->set_var("check"        ,$checked         );

$t->set_var("listid"        ,$listid         );
$t->set_var($arrdata);

$t->set_var("titlename",$titlename);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "rechargeglist_sp"); //最后输出界面

?>