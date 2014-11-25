<?php
class ApiAppAccountInfo extends TfbxmlResponse {
	//我的钱包		    
	public function readMyAccount() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = $arr_channelinfo['authorid'];
		$query = "select sum(fd_acc_money) as allaccmoney from tb_authoraccount where fd_acc_authorid = '$authorid' group by fd_acc_authorid ";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$allaccmoney = $db->f(allaccmoney);
		}
		$query = "select sum(fd_couponsale_money) as allaccmoney from tb_couponsale where fd_couponsale_authorid = '$authorid'" .
				" and fd_couponsale_payrq = '00' group by fd_couponsale_authorid ";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$allaccmoney += $db->f(allaccmoney);
		}
		$paytype = '非贷款';
		$paytype = u2g($paytype);
		
		$paytype1 = '抵用券';
		$paytype1 = u2g($paytype1);
		$query = "select 'recharge' as acctypeid ,'".$paytype."' as acctypename ,fd_acc_money as accmoney from tb_authoraccount  " .
				"where fd_acc_authorid = '$authorid'  " .
				" union all " .
				" select 'coupon' as acctypeid ,'".$paytype1."' as acctypename ,sum(fd_couponsale_money) as couponmoney from 
			       tb_couponsale where fd_couponsale_authorid = '$authorid' and fd_couponsale_payrq = '00'  and fd_couponsale_isagentpay = '0'  group by fd_couponsale_authorid ";
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		$arr_msg['msgbody']['accallmoney'] = $allaccmoney;
		$arr_message = array (
					"result" => "success",
					"message" => "读取成功！"
				);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	//我的钱包收支明细		    
	public function readAccglist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$acctypeid = trim($arr_body['acctypeid']);      // 账户类型 非贷款 贷款 0 为所有 
		$authorid = trim($arr_channelinfo['authorid']); // 账户类型 非贷款 贷款 0 为所有 
		
		if ($acctypeid == 'coupon') {
			$wherequery1 = " and 1=2";
		}
		if ($acctypeid == 'recharge') {
			$wherequery2 = " and 1=2";
		}
		$nowyear = date('Y');
	
		$query = "select sum(accincome) as accincome ,accmonth from (select  CONCAT(year(fd_accglist_datetime),month(fd_accglist_datetime)) as accmonth , " .
				" sum(fd_accglist_money) as accincome,'0' as accpayout  
							       from tb_authoraccountglist where year(fd_accglist_datetime) = '$nowyear' " .
							       "and fd_accglist_authorid='$authorid' $wherequery1 group by  year(fd_accglist_datetime), month(fd_accglist_datetime) 
				            " .
				 " union all 
				  select CONCAT(year(fd_couponsale_datetime),month(fd_couponsale_datetime)) as accmonth ,sum(fd_couponsale_money) as accincome,'0' as accpayout 
			               from 
			       tb_couponsale where fd_couponsale_authorid = '$authorid' and fd_couponsale_payrq = '00' and fd_couponsale_isagentpay = '0' and year(fd_couponsale_datetime) = '$nowyear'" .
			       " $wherequery2 group by  year(fd_couponsale_datetime), month(fd_couponsale_datetime) ) as a group by accmonth 
				             ";
		$db->query($query);
     
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		
	    $arr_message = array (
					"result" => "success",
					"message" => "读取成功！"
				);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array (

			"msgbody" => $arr_msg['msgbody']
		);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}

	//我的钱包收支详细		    
	public function readAccglistdetail() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$acctypeid = trim($arr_body['acctypeid']);
		$authorid = trim($arr_channelinfo['authorid']);
		$msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;
		//echo var_dump($arr_body);  
		$accyear = substr(trim($arr_body['accmonth']), 0, 4);
		$accmonth = str_replace($accyear, "", $arr_body['accmonth']);
		if ($acctypeid == 'coupon') {
			$wherequery1 = " and 1=2";
		}
		if ($acctypeid == 'recharge') {
			$wherequery2 = " and 1=2";
		}
		if($msgdisplay==0)
		{
			$msgdisplay=100;
		}
		$paytype = '充值';
		$paytype = u2g($paytype);
		$paytype3 = '订单支付';
		$paytype3 = u2g($paytype);
		
		$paytype1 = '购买抵用券';
		$paytype1 = u2g($paytype1);
		
		$paystate = '交易完成';
		$paystate = u2g($paystate);
		
        $query = "select * from (select fd_accglist_bkordernumber as accglistno," .
        		" case 
        when fd_accglist_paytype ='coupon' then '购买抵用券'
        when fd_accglist_paytype ='creditcard' then '信用卡还款'" .
       "when fd_accglist_paytype ='recharge' then   '".$paytype."'" .
       "when fd_accglist_paytype ='pay' then       '还贷款'" .
       "when fd_accglist_paytype ='order' then '".$paytype3."'" .
       "when fd_accglist_paytype ='tfmg' then '转账汇款'
        else '其他业务' END  accgpaymode," .
        " 
				                    fd_accglist_datetime as accglistdate , fd_accglist_id as  accglistid ,
				                    (fd_accglist_money) as accglistmoney,'".$paystate."' as accgstate,'' as accgtype,'' as accgmemo ," .
				                   " 'testbank' as accgcardbank,'testno' as accgcardno   
							        from tb_authoraccountglist where year(fd_accglist_datetime) = '$accyear' and 
									month(fd_accglist_datetime) = '$accmonth' and fd_accglist_authorid='$authorid' $wherequery1 
				              " .
				  " union all  select fd_couponsale_bkordernumber as couponno,'".$paytype1."' as accgpaymode ,fd_couponsale_datetime as coupondate," .
				  "fd_couponsale_id as couponid, CONCAT_WS('=',CONCAT_WS('*',(fd_couponsale_money/fd_coupon_money),fd_coupon_money),fd_couponsale_money+0) as couponmoney,'".$paystate."','' as accgtype,'' as accgmemo ,'testbank' as accgcardbank,'testno' as accgcardno 
			                from 
			       tb_couponsale left join tb_coupon on fd_coupon_id =fd_couponsale_couponid where fd_couponsale_authorid = '$authorid' and fd_couponsale_payrq = '00' and fd_couponsale_isagentpay = '0'" .
			       " and year(fd_couponsale_datetime) = '$accyear' and 
									month(fd_couponsale_datetime) = '$accmonth' $wherequery2) as a order by accglistdate desc ";
		$db->query($query);	
		$msgallcount = $db->nf();
		//$query = " $query  limit $msgstart,$msgdisplay ";
		//$db->query($query);		
		$msgdiscount = $db->nf();
		//  echo $query;	
		//  echo $query;
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		
		 $arr_message = array (
					"result" => "success",
					"message" => "读取成功！"
				);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['msgallcount'] = $msgallcount;
		$arr_msg['msgbody']['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}

}
?>