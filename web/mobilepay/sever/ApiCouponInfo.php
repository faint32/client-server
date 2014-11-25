<?php
class ApiCouponInfo extends TfbxmlResponse {
	//读取抵用券设置	    
	public function readcouponinfo() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = $arr_channelinfo['authorid'];
		$query = "select fd_coupon_id as couponid ,fd_coupon_money as couponmoney,fd_coupon_limitnum as couponlimitnum from tb_coupon where fd_coupon_active = '1'
			          order by fd_coupon_no";
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');

		$query = "select fd_author_shopid from tb_author where fd_author_id = '$authorid'";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$shopid = $db->f('fd_author_shopid');

		}
		$arr_shopinfo = getauthorshop($shopid);
		$shopname = $arr_shopinfo['shopname'];
		if ($shopname) {
			$isshop = 1;
			$arr_message = array (
				"result" => "success",
				"message" => "读取抵用券信息成功！"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		} else {
			$ishop = 0;
			$arr_message = array (
				"result" => "failure",
				"message" => "还未开通商家，不允许购买抵用券！"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['shopname'] = $shopname;
		$arr_msg['msgbody']['isshop'] = $isshop;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	public function  checkshoubankinfo($authorid,$req_appenv)
	{
		$db = new DB_test();
		$ErrorReponse = new ErrorReponse();
		
		$query = "select * from tb_author  where fd_author_id = '$authorid' and (fd_author_shoucardman =''" .
					 "or fd_author_shoucardphone ='' or fd_author_shoucardno ='' or fd_author_shoucardbank =''  ) and fd_author_couponstate='1'";
		$db->query($query);
		//echo $query;
		if ($db->nf()) {
			if($req_appenv<3)
			{
			$arr_message = array (
				"result" => "failure",
				"message" => "还未申请商家银行号信息，进入“更多-》我的银行卡”功能补充资料！"
			);
			}else
			{
				$arr_message = array (
				"result" => "failure",
				"message" => "还未申请商家银行号信息，请联系客服电话补充资料！"
			);
			}
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			$Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => $arr_message['message']
			);
			$ErrorReponse->reponError($Error); //出错反馈 
			
		
			return false;
		}else
		{
			$query = "select * from tb_author  where fd_author_id = '$authorid'  and fd_author_couponstate='0'";
			$db->query($query);
			if ($db->nf()) 
			{
				$arr_message = array (
				"result" => "failure",
				"message" => "抵用券落地银行卡号等待审批中，请耐心等待！"
				
				
			);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			    $Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => $arr_message['message']
			);
			$ErrorReponse->reponError($Error); //出错反馈 
			return false;
			}
			
			$query = "select * from tb_author  where fd_author_id = '$authorid'  and fd_author_couponstate='2'";
			$db->query($query);
			if ($db->nf()) 
			{
				$arr_message = array (
				"result" => "failure",
				"message" => "抵用券相关银行卡号审批不通过，请正确填写相关资料！"
				
				
			);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			    $Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => $arr_message['message']
			);
			$ErrorReponse->reponError($Error); //出错反馈 
			return false;
			}else
			{
				return true;
			}
			
		}
	} 
	//购买抵用券	    
	public function couponSale() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = $arr_channelinfo['authorid'];
		$couponid = trim($arr_body['couponid']);
		$paymoney = trim($arr_body['couponmoney']);
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid']));
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$fucardno = trim($arr_body['creditcardno']);
		$fubank = trim(u2g($arr_body['creditbank']));
		$fucardman = trim(u2g($arr_body['creditcardman']));
		$fucardphone = trim(u2g($arr_body['creditcardphone']));
        $paytype = 'coupon';
        $req_appenv = trim($arr_header['req_appenv']);
       // echo "fdf";
        $checkbankinfo = $this->checkshoubankinfo($authorid,$req_appenv);  //检测我的银行卡信息有没填写
        
        $arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, "", $paymoney, "", 5); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];

		}
		$allmoney = round(($paymoney + $feemoney), 2);
		$arr_arrive = GetPayCalcuInfo :: readarrive($arr_feeinfo['arriveid']);
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$payfeedirct = $arr_feeinfo['defeedirct'];
		if ($arr_feeinfo['defeedirct'] == 's') //付款方承担手续费 
			{
			$bkmoney = $paymoney;

		} else {
			$bkmoney = $allmoney;
		}
		$arr_feeinfo['arrivedate'] = $arrivedate;
		$arr_feeinfo['paydate'] = $paydate;
		$arr_feeinfo['bkmoney'] = $bkmoney;
		$arr_feeinfo['payfeedirct'] = $payfeedirct;
		$payfee  = $feemoney ;
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$paymoney, $fucardno);
		
		$arr_authorinfo=AuToken::getauthorusername($authorid);
		$arr_feeinfo['shoucardno']=$shoucardno = $arr_authorinfo['shoucardno'];
		$arr_feeinfo['shoucardmobile']=$shoucardmobile = $arr_authorinfo['shoucardmobile'];
		$arr_feeinfo['shoucardbank']=$shoucardbank   = $arr_authorinfo['shoucardbank'];
		$arr_feeinfo['shoucardman']=$shoucardman = $arr_authorinfo['shoucardman'];
		
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['bkordernumber']=$bkordernumber = $arr_bkinfo['bkorderNumber'];
		$arr_feeinfo['bkmoney'] = $bkmoney = $paymoney;
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney= (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$ccgno = makeorderno("couponsale", "couponsale", "cps");
		$query = "insert into tb_couponsale
					(fd_couponsale_no	,	fd_couponsale_bkntno	,	fd_couponsale_couponno	,
					fd_couponsale_paycardid,fd_couponsale_authorid  ,	fd_couponsale_money,
					fd_couponsale_rebuy,	fd_couponsale_state,		fd_couponsale_datetime,
		            fd_couponsale_couponid,	fd_couponsale_payrq,		fd_couponsale_creditcardno,
		            fd_couponsale_creditcardbank,fd_couponsale_creditcardman,fd_couponsale_creditcardphone," .
		           "fd_couponsale_bkordernumber,fd_couponsale_sdcrid,   fd_couponsale_sdcrpayfeemoney," .
		           "fd_couponsale_paymoney , fd_couponsale_payfee   ,   fd_couponsale_bkmoney  ," .
		           "fd_couponsale_shoucardno,fd_couponsale_shoucardman,fd_couponsale_shoucardbank," .
		           "fd_couponsale_shoucardmobile
		            )values
					('$ccgno'			,	'$bkntno'				,	'$bkorderNumber',
					'$paycardid'		,	'$authorid'				,	'$paymoney',
					'0'					,	'0'						,	 now()		 ,
					'$couponid'			,	'01'  					,    '$fucardno',
					'$fubank'           ,   '$fucardman'            ,   '$fucardphone' , " .
					"'$bkordernumber'   ,   '$sdcrid'               ,   '$sdcrpayfeemoney'," .
					"'$paymoney'        ,   '$payfee'               ,   '$bkmoney'        ," .
					"'$shoucardno'      ,   '$shoucardman'          ,   '$shoucardbank'    ," .
					"'$shoucardmobile'    ) ";
		$db->query($query);
		$listid = $db->insert_id();
		$method = 'in';
		$method = u2g($method);
		

		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method,$arr_feeinfo);

		$arr_message = array (
			"result" => "success",
			"message" => "获取交易码成功，可以去刷卡支付了!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bkntno'] = trim($bkntno);
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//购买优惠券成功后更改状态	    
	public function couponSalePay() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid   = $arr_channelinfo['authorid'];
		$bkntno     = trim($arr_body['bkntno']);
		$result     = trim($arr_body['result']);
		$transtatus = trim($arr_body['transtatus']);
		if ($result == 'success' && $bkntno !="") {
			//$bkordernumber
			$query = "update  tb_couponsale set fd_couponsale_payrq = '00'  where fd_couponsale_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "恭喜你，购买抵用券成功!");
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else
		{
			//$query = "update  tb_couponsale set fd_couponsale_payrq = '03'  where fd_couponsale_bkntno = '$bkntno'";
			//$db->query($query);
			//$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '03'  where fd_agpm_bkntno = '$bkntno'";
			//$db->query($query);
//			$arr_message = array (
//			"result" => "failure",
//			"message" => "抵用券支付失败，请重新购买!");
//			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
        
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//优惠券列表		    
	public function couponSalelist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = $arr_channelinfo['authorid'];
        $msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;
		if($msgdisplay==0) $msgdisplay=100;
		$query = "select fd_couponsale_bkordernumber as couponno,CONCAT_WS('=',CONCAT_WS('*',(fd_couponsale_money/fd_coupon_money),fd_coupon_money),fd_couponsale_money+0) as couponmoney " .
				",
			                 fd_couponsale_datetime as coupondate,fd_couponsale_paycardid as paycardid,
							 fd_couponsale_id as couponid ,fd_couponsale_memo as couponmemo,fd_couponsale_creditcardbank as couponbank,fd_couponsale_creditcardno as couponcardno from 
			       tb_couponsale left join tb_coupon on fd_coupon_id =fd_couponsale_couponid where fd_couponsale_authorid = '$authorid' and fd_couponsale_payrq = '00' order by fd_couponsale_datetime desc "; //只显示购买的抵用券历史
		$db->query($query);
		$msgallcount = $db->nf();
		
		$query = " $query limit $msgstart,$msgdisplay";
		$db->query($query);
		$msgdiscount = $db->nf();
		//echo $query;
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		//echo var_dump($arr_msg);
		if ($arr_msg == "") {
			$arr_message = array (
				"result" => "failure",
				"message" => "还没有优惠券记录!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取抵用券列表成功!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['msgallcount'] = $msgallcount;
		$arr_msg['msgbody']['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
		);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
}
function makeorderno($tablename, $fieldname, $preno = "pay") {
	$db = new DB_test();
	$db2 = new DB_test();
	$year = date("Y", mktime());
	$month = date("m", mktime());
	$day = date("d", mktime());

	$nowdate = $year . $month . $day;
	$query = "select fd_" . $fieldname . "_no as no from tb_" . $tablename . "   order by fd_" . $fieldname . "_no  desc";
	$db2->query($query);
	if ($db2->nf()) {
		$db2->next_record();
		$orderno = $db2->f(no);
		$orderdate = substr($orderno, 3, 8); //截取前8位判断是否当前日期     
		if ($nowdate == $orderdate) {
			$orderno = substr($orderno, 11, 6) + 1; //是当前日期流水帐加1
			if ($orderno < 10) {
				$orderno = "00000" . $orderno;
			} else
				if ($orderno < 100) {
					$orderno = "0000" . $orderno;
				} else
					if ($orderno < 1000) {
						$orderno = "000" . $orderno;
					} else
						if ($orderno < 10000) {
							$orderno = "00" . $orderno;
						} else {
							$orderno = $orderno;
						}
			$orderno = $preno . $nowdate . $orderno;
		} else {
			$orderno = $preno . $nowdate . "000001"; //不是当前日期,为5位流水帐,开始值为1。
		}
	} else {
		$orderno = $preno . $nowdate . "000001";
	}
	return $orderno;
}
?>