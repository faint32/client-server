<?php
class ApiPayinfo extends TfbxmlResponse {

	//信用卡		    
	function creditCardMoneyRq() {
		global $weburl;
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$paytype = 'creditcard';
		$paymoney = trim($arr_body['paymoney']);
		$shoucardno = trim($arr_body['shoucardno']); //收款卡号
		$shoucardmobile = trim($arr_body['shoucardmobile']); //收款人手机
		$shoucardman = trim(u2g($arr_body['shoucardman'])); //收款人姓名
		$shoucardbank = trim(u2g($arr_body['shoucardbank'])); //收款银行
		$fucardno = trim($arr_body['fucardno']); //付款卡号
		$fucardbank = trim(u2g($arr_body['fucardbank'])); //付款银行
		$fucardmobile = trim($arr_body['fucardmobile']); //付款人手机
		$fucardman = trim(u2g($arr_body['fucardman'])); //付款人姓名
		$current = trim($arr_body['current']); //币种
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$feebankid = getbankid($shoucardbank); //获得银行id返回string
       
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $feebankid, $paymoney, $arriveid, 1); //获取手续费信息返回array
		$paytype = 'creditcard';
        CheckPayQuota::readPayQuota($authorid, $paycardid,$paytype,$paymoney); //调用判断额度使用情况   
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];
		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$allmoney = round(($paymoney + $feemoney), 2);

		$payfeedirct = $arr_feeinfo['defeedirct'];
		if ($arr_feeinfo['defeedirct'] == 's') //付款方承担手续费 
			{
			$bkmoney = $paymoney;

		} else {
			$bkmoney = $allmoney;
		}
		$arr_feeinfo['paydate'] = $paydate;
		$arr_feeinfo['arrivedate'] = $arrivedate;
		$arr_feeinfo['bkmoney'] = $bkmoney;
		$arr_feeinfo['payfeedirct'] = $payfeedirct;
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $fucardno);
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		
		
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$arr_feeinfo['bkordernumber']=$bkorderNumber = trim($arr_bkinfo['bkorderNumber']);
		$arr_arrive = GetPayCalcuInfo :: readarrive($arr_feeinfo['arriveid']);
		$ccgno = makeorderno("creditcardglist", "ccglist", "ccg");
		$query = "insert into tb_creditcardglist(
							fd_ccglist_no			,fd_ccglist_paycardid		,fd_ccglist_authorid	,
				            fd_ccglist_paydate		,fd_ccglist_shoucardno		,fd_ccglist_fucardno	,
				            fd_ccglist_bkntno		,fd_ccglist_payrq           ,fd_ccglist_paytype     ,
				            fd_ccglist_current		,fd_ccglist_paymoney		,fd_ccglist_payfee		,
				            fd_ccglist_money		,fd_ccglist_shoucardbank    ,fd_ccglist_shoucardman ,
				            fd_ccglist_shoucardmobile,fd_ccglist_fucardbank		,fd_ccglist_fucardmobile,
				            fd_ccglist_fucardman    ,fd_ccglist_feebankid       ,fd_ccglist_state  	    ,
				            fd_ccglist_arriveid     ,fd_ccglist_arrivedate      ,fd_ccglist_payfeedirct ," .
		"fd_ccglist_bkordernumber,fd_ccglist_sdcrid,fd_ccglist_sdcrpayfeemoney)values
						   ('$ccgno'		,'$paycardid'	,'$authorid'		,
						   '$paydate'		,'$shoucardno'	,'$fucardno'		,
						   '$bkntno'		,'01'			,'$paytype'			,
						   '$current'		,'$paymoney'	,'$feemoney'		,
						   '$allmoney'		,'$shoucardbank','$shoucardman'		,
						   '$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
						   '$fucardman'		,'$feebankid'	,'0'   				," .
		"'$arriveid','$arrivedate','$payfeedirct','$bkorderNumber','$sdcrid','$sdcrpayfeemoney')";
		$db->query($query);
		$listid = $db->insert_id();
		//$listid = $db->insert_id();
		$method = 'in';
		$method = u2g($method);
	

		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);

		$arr_message = array (
			"result" => "success",
			"message" => "获取成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bkntno'] = $bkntno;
		$arr_msg['msgbody']['paymoney'] = $paymoney;
		$arr_msg['msgbody']['feemoney'] = $feemoney;
		$arr_msg['msgbody']['allmoney'] = $allmoney;
		$returnvalue = array (
			
				
				"msgbody" => $arr_msg['msgbody']
		
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//插入信用卡还款记录		    
	public function insertcreditCardMoney() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bkntno = trim($arr_body['bkntno']); //交易流水号
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
		$authorid = trim($arr_channelinfo['authorid']); //authorid
		$result = $arr_body['result'];
		$nowdate = date("Y-m-d H:i:s");
		if ($result == 'success' && $bkntno !="") {
			$query = "update tb_creditcardglist set fd_ccglist_payrq ='00',fd_ccglist_paydate = '$nowdate' where fd_ccglist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		      );
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else
		{
			$query = "update tb_creditcardglist set fd_ccglist_payrq ='03',fd_ccglist_paydate = '$nowdate' where fd_ccglist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='03'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "failure",
			"message" => "支付失败!"
		      );
		$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		  
		//echo $query;

		$returnvalue = array (
			
				"msgbody" => array (
					'result' =>$arr_message['result'],
					"message" => $arr_message['message']
				)
		
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}

	//信用卡还款历史记录		    
	public function readcreditCardglist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		$msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;

		$arr_state = array("请求交易","交易成功","交易取消","无效状态");
		$arr_state = auto_charset($arr_state, 'utf-8', 'gbk');

		if ($msgstart < 0)
			$msgstart = 0;

		$query = "select  1 from  tb_creditcardglist where (fd_ccglist_payrq='00' or fd_ccglist_payrq='03') and fd_ccglist_authorid = '$authorid'";
		$db->query($query);
		$msgallcount = $db->nf();
		$query = "select fd_ccglist_bkordernumber as ccgno, fd_ccglist_paydate as ccgtime,fd_ccglist_shoucardno as huancardno,
					                 fd_ccglist_fucardno as fucardno,fd_ccglist_shoucardbank as huancardbank,fd_ccglist_fucardbank as fucardbank, fd_ccglist_paymoney as paymoney,fd_ccglist_payfee as feemoney,
									 fd_ccglist_money as allmoney," .
									"case 
        when fd_ccglist_payrq ='01' then  '".$arr_state[0]."'
        when fd_ccglist_payrq ='00' then  '".$arr_state[1]."'" .
       "when fd_ccglist_payrq ='03' then  '".$arr_state[2]."'
        else  '".$arr_state[3]."' END  state from  tb_creditcardglist where 
									 (fd_ccglist_payrq='00' or fd_ccglist_payrq='03')   and fd_ccglist_authorid = '$authorid' order by fd_ccglist_paydate desc limit $msgstart,$msgdisplay";
		$db->query($query);
		$msgdiscount = $db->nf();
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
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
	//转账汇款手续费 
	public function getTransferPayfee() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		
		$bankid = trim($arr_body['bankid']);
		$paymoney = trim($arr_body['money']);
		$arriveid = trim($arr_body['arriveid']);
        $paytype ='tfmg';
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $bankid, $paymoney, $arriveid, 2,$paytype); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];

		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$money = round(($paymoney + $feemoney), 2);
		$arr_message = array (
			"result" => "success",
			"message" => "读取成功!"
		);
       $retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arriveid = $arr_feeinfo['arriveid'];
		$query = "select fd_arrive_id as arriveid , fd_arrive_name as arrivetime, '1' as activearriveid,'' as activememo from tb_arrive where fd_arrive_id = '$arriveid'  ";
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['payfee'] = $feemoney;
		
		
		//$arr_msg ['msgbody'] ['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;

	}
	//转账汇款		    
	public function transferMoneyRq() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$paymoney = trim($arr_body['paymoney']);
		$shoucardno = trim($arr_body['shoucardno']); //收款卡号
		$shoucardmobile = trim($arr_body['shoucardmobile']); //收款人手机
		$shoucardman = trim(u2g($arr_body['shoucardman'])); //收款人姓名
		$shoucardbank = trim(u2g($arr_body['shoucardbank'])); //收款银行
		$fucardno = trim($arr_body['fucardno']); //付款卡号
		$fucardbank = trim(u2g($arr_body['fucardbank'])); //付款银行
		$fucardmobile = trim($arr_body['fucardmobile']); //付款人手机
		$fucardman = trim(u2g($arr_body['fucardman'])); //付款人姓名
		$current = trim($arr_body['current']); //币种
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$payfee = trim($arr_body['payfee']); //authorid
		$money = trim($arr_body['money']); //币种
		$shoucardmemo = trim(u2g($arr_body['shoucardmemo'])); //刷卡器设备号
		$sendsms = trim($arr_body['sendsms']); //authorid
		$arriveid = trim($arr_body['arriveid']); //币种
		$paytype = 'tfmg';

		$feebankid = getbankid($shoucardbank); //获得银行id返回string
        
        
        CheckPayQuota::readPayQuota($authorid, $paycardid,$paytype,$paymoney); //调用判断额度使用情况 
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $feebankid, $paymoney, $arriveid, 2,$paytype); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];
		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$allmoney = round(($paymoney + $feemoney), 2);
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
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $fucardno);
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$arr_feeinfo['bkordernumber']=$bkorderNumber = $arr_bkinfo['bkorderNumber'];
		$ccgno = makeorderno("transfermoneyglist", "tfmglist", "tfs");
		//$query = "select 1 from tb_transfermoneyglist where fd_tfmglist_bkntno = '$bkntno'";
		//if ($db->execute($query)) {
		$query = "insert into tb_transfermoneyglist(
							fd_tfmglist_no			,fd_tfmglist_paycardid		,fd_tfmglist_authorid	,
				            fd_tfmglist_paydate		,fd_tfmglist_shoucardno		,fd_tfmglist_fucardno	,
				            fd_tfmglist_bkntno		,fd_tfmglist_payrq          ,fd_tfmglist_paytype     ,
				            fd_tfmglist_current		,fd_tfmglist_paymoney		,fd_tfmglist_payfee		 ,
				            fd_tfmglist_money		,fd_tfmglist_shoucardbank   ,fd_tfmglist_shoucardman ,
				            fd_tfmglist_shoucardmobile,fd_tfmglist_fucardbank	,fd_tfmglist_fucardmobile,
				            fd_tfmglist_fucardman   ,fd_tfmglist_feebankid      ,fd_tfmglist_state       ,	
				            fd_tfmglist_shoucardmemo,fd_tfmglist_sendsms        ,fd_tfmglist_arriveid    ,     	
				            fd_tfmglist_arrivedate  ,fd_tfmglist_payfeedirct    ,fd_tfmglist_bkordernumber," .
				           "fd_tfmglist_sdcrid,fd_tfmglist_sdcrpayfeemoney)values
						   ('$ccgno'		,'$paycardid'	,'$authorid'		,
						   '$paydate'		,'$shoucardno'	,'$fucardno'		,
						   '$bkntno'		,'01'			,'$paytype'			,
						   '$current'		,'$paymoney'	,'$feemoney'			,
						   '$allmoney'			,'$shoucardbank','$shoucardman'		,
						   '$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
						   '$fucardman'		,'$feebankid'	,'0'				,
						   '$shoucardmemo'	,'$feebankid'   ,'$arriveid'        , " .
		"'$arrivedate' ,'$payfeedirct'    ,'$bkorderNumber' ,'$sdcrid' ,'$sdcrpayfeemoney'  )";
		$db->query($query);
		$listid = $db->insert_id();
		//$listid = $db->insert_id();
		$method = 'in';
		$method = u2g($method);
		
		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);
		$arr_message = array (
			"result" => "success",
			"message" => "获取成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
//		}else
//		{
//			$arr_message = array (
//			"result" => "failure",
//			"message" => $bkntno."重复提交申请，请重新操作!"
//			);
//		$retcode = "200";  //反馈状态 0 成功 200 自定义错误
//		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bkntno'] = $bkntno;
		$arr_msg['msgbody']['feemoney'] = $feemoney;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//转账汇款支付成功反馈		    
	public function insertTransferMoney() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bkntno = trim($arr_body['bkntno']); //银行交易流水号
		$nowdate = date("Y-m-d H:i:s");
		$result = $arr_body['result'];
        $authorid = $arr_channelinfo['authorid'];

        $query = "select fd_tfmglist_bkordernumber as bkordernumber,fd_tfmglist_authorid as authorid ,
                  DATE_FORMAT(fd_tfmglist_paydate,'%Y%m%d') as orderTime
                  from tb_transfermoneyglist
                  where 1  and  fd_tfmglist_bkntno = '$bkntno' and fd_tfmglist_authorid = '$authorid'  limit 1";
        $arr_val= $db->get_one($query);


        $orderNumber =  $arr_val['bkordernumber'];
        $orderTime   =  $arr_val['orderTime'];

        $arr_returninfo = BankPayInfo :: bankorderquery($authorid,'',$orderNumber, $orderTime);


        $arr_returninfo =$this->Publiccls->xml_to_array($arr_returninfo);
        
		if ($result == 'success' && $bkntno !="") {
			$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='00' ,fd_tfmglist_paydate ='$nowdate' 
					          where fd_tfmglist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else
		{
//			$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='03' ,fd_tfmglist_paydate ='$nowdate'
//					          where fd_tfmglist_bkntno = '$bkntno'";
//			$db->query($query);
//			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '03'  where fd_agpm_bkntno = '$bkntno'";
//			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付失败!"
		);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => array (
					'result' => $arr_message['result'],
					"message" => $arr_message['message'],
                    $arr_returninfo
				)
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//转账汇款历史记录		    
	public function readTransferMoneyglist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		
		$msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;
		$paytype    = trim($arr_body['paytype']);
		$arr_state = array("请求交易","交易成功","交易取消","无效状态");
		$arr_state = auto_charset($arr_state, 'utf-8', 'gbk');

		if ($msgstart < 0)
			$msgstart = 0;

		$query = "select  1 from  tb_transfermoneyglist where  fd_tfmglist_authorid = '$authorid' and" .
				"  (fd_tfmglist_payrq = '00' or fd_tfmglist_payrq = '03')";
		$db->query($query);
		$msgallcount = $db->nf();
        
		$query = "select fd_tfmglist_bkordernumber as ccgno, fd_tfmglist_paydate as ccgtime,fd_tfmglist_shoucardno as huancardno,
				  fd_tfmglist_fucardno as fucardno, fd_tfmglist_fucardbank as fucardbank,fd_tfmglist_shoucardbank as  huancardbank,
				  fd_tfmglist_paymoney as paymoney,fd_tfmglist_payfee as feemoney,
				  fd_tfmglist_money as allmoney,case 
        when fd_tfmglist_payrq ='01' then '".$arr_state[0]."'
        when fd_tfmglist_payrq ='00' then '".$arr_state[1]."'" .
       "when fd_tfmglist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[4]."' END  state from  tb_transfermoneyglist where 
									 fd_tfmglist_authorid = '$authorid'  and (fd_tfmglist_paytype ='$paytype' or fd_tfmglist_paytype = 'zhuan')
				and  (fd_tfmglist_payrq = '00' or fd_tfmglist_payrq = '03') order by fd_tfmglist_id desc limit $msgstart,$msgdisplay ";
		$db->query($query);
		$msgdiscount = $db->nf();
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
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

	//还贷款手续费计算 
	public function getRepayMoneyPayfee() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
	
		$bankid = trim($arr_body['bankid']);
		$paymoney = trim($arr_body['money']);
		$arriveid = trim($arr_body['arriveid']);
		$authorid = trim($arr_channelinfo['authorid']);
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $bankid, $paymoney, $arriveid, 3); //获取手续费信息返回array
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$feemoney = $arr_feeinfo['feemoney']; //手续费比例   ‘30’ %

		if ($arr_feeinfo['arriveid'] == "") {
			$Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => '未设置到帐周期,错误的代码[200]'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
		}
		$arr_message = array (
			"result" => "success",
			"message" => "读取成功!"
		);
       $retcode = "0";  //反馈状态 0 成功 200 自定义错误
		//$arriveid= $arr_feeinfo['arriveid'];
		$query = "select fd_arrive_id as arriveid , fd_arrive_name as arrivetime, '1' as activearriveid from tb_arrive where fd_arrive = '$arriveid'  ";
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'utf-8', 'utf-8');
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['payfee'] = $feemoney;
		//$arr_msg ['msgbody'] ['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;

	}
	//还贷款获得交易流水号请求		    
	public function RepayMoneyRq() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid   = trim($arr_channelinfo['authorid']);
		
		$paymoney   = trim($arr_body['paymoney']);
		$shoucardno = trim($arr_body['shoucardno']); //收款卡号
		$shoucardmobile = trim($arr_body['shoucardmobile']); //收款人手机
		$shoucardman = trim(u2g($arr_body['shoucardman'])); //收款人姓名
		$shoucardbank = trim(u2g($arr_body['shoucardbank'])); //收款银行
		$fucardno = trim($arr_body['fucardno']); //付款卡号
		$fucardbank = trim(u2g($arr_body['fucardbank'])); //付款银行
		$fucardmobile = trim($arr_body['fucardmobile']); //付款人手机
		$fucardman = trim(u2g($arr_body['fucardman'])); //付款人姓名
		$current = trim($arr_body['current']); //币种
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$payfee = trim($arr_body['payfee']); //authorid
		$money = trim($arr_body['money']); //币种
		$arriveid = trim($arr_body['arriveid']); //到帐周期
        $paytype = 'repay';
		$feebankid = getbankid($shoucardbank); //获得银行id返回string
        CheckPayQuota::readPayQuota($authorid, $paycardid,$paytype,$paymoney); //调用判断额度使用情况 
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $feebankid, $paymoney, $arriveid, 3); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];

		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$allmoney = round(($paymoney + $feemoney), 2);
		$arr_arrive = GetPayCalcuInfo :: readarrive($arr_feeinfo['arriveid']);
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
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $fucardno);
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$arr_feeinfo['bkordernumber']=$bkorderNumber = $arr_bkinfo['bkorderNumber'];
		$ccgno = makeorderno("repaymoneyglist", "repmglist", "rpl");
		$query = "insert into tb_repaymoneyglist(
							fd_repmglist_no			,fd_repmglist_paycardid		,fd_repmglist_authorid	,
				            fd_repmglist_paydate	,fd_repmglist_shoucardno	,fd_repmglist_fucardno	,
				            fd_repmglist_bkntno		,fd_repmglist_payrq         ,fd_repmglist_paytype     ,
				            fd_repmglist_current	,fd_repmglist_paymoney		,fd_repmglist_payfee		 ,
				            fd_repmglist_money		,fd_repmglist_shoucardbank   ,fd_repmglist_shoucardman ,
				            fd_repmglist_shoucardmobile,fd_repmglist_fucardbank	,fd_repmglist_fucardmobile,
				            fd_repmglist_fucardman   ,fd_repmglist_feebankid      ,fd_repmglist_state     ,       	
				            fd_repmglist_arrivedate , fd_repmglist_payfeedirct  ,fd_repmglist_bkordernumber," .
				           "fd_repmglist_sdcrid		,fd_repmglist_sdcrpayfeemoney)values
						   ('$ccgno'		,'$paycardid'	,'$authorid'		,
						   '$paydate'		,'$shoucardno'	,'$fucardno'		,
						   '$bkntno'		,'01'			,'$paytype'			,
						   '$current'		,'$paymoney'	,'$feemoney'			,
						   '$allmoney'			,'$shoucardbank','$shoucardman'		,
						   '$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
						   '$fucardman'		,'$feebankid'	,'0'		        ,		
						   '$arrivedate'    ,'$payfeedirct' ,'$bkorderNumber'   ," .
						   "'$sdcrid'       ,'$sdcrpayfeemoney'  )";
		$db->query($query);
		$listid = $db->insert_id();
		//$listid = $db->insert_id();
		$method = 'in';
		$method = u2g($method);
		

		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);

		$arr_message = array (
			"result" => "success",
			"message" => "获取成功!"
		);
		  $retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bkntno'] = $bkntno;
		$arr_msg['msgbody']['feemoney'] = $feemoney;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);

		return $returnval;
	}
	//还贷款支付成功反馈		    
	public function insertRepayMoney() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bkntno = trim($arr_body['bkntno']); //银行交易流水号
		$nowdate = date("Y-m-d H:i:s");
		$result = $arr_body['result'];
		if ($result == 'success' && $bkntno !="") {
			$query = "update  tb_repaymoneyglist set fd_repmglist_payrq ='00' ,fd_repmglist_paydate ='$nowdate' 
					          where fd_repmglist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else 
		{
//			$query = "update  tb_repaymoneyglist set fd_repmglist_payrq ='03' ,fd_repmglist_paydate ='$nowdate'
//					          where fd_repmglist_bkntno = '$bkntno'";
//			$db->query($query);
//			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '03'  where fd_agpm_bkntno = '$bkntno'";
//			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付失败!"
		);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => array (
					'result' => $arr_message['result'],
					"message" => $arr_message['message']
				)
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//还贷款历史记录		    
	public function readRepayMoneyglist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		$paytype = 'repay';
		$msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;

		$arr_state = array("请求交易","交易成功","交易取消","无效状态");
		$arr_state = auto_charset($arr_state, 'utf-8', 'gbk');

		if ($msgstart < 0)
			$msgstart = 0;

		$query = "select  1 from  tb_repaymoneyglist where (fd_repmglist_payrq = '00' or fd_repmglist_payrq = '03')  and fd_repmglist_authorid = '$authorid'";
		$db->query($query);
		$msgallcount = $db->nf();

		$query = "select fd_repmglist_bkordernumber as ccgno, fd_repmglist_paydate as ccgtime,fd_repmglist_shoucardno as huancardno,fd_repmglist_shoucardbank as huancardbank,fd_repmglist_fucardbank as fucardbank ,
					                 fd_repmglist_fucardno as fucardno, fd_repmglist_paymoney as paymoney,fd_repmglist_payfee as feemoney,
									 fd_repmglist_money as allmoney,case 
        when fd_repmglist_payrq ='01' then '".$arr_state[0]."'
        when fd_repmglist_payrq ='00' then '".$arr_state[1]."'" .
       "when fd_repmglist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[0]."' END  state  from  tb_repaymoneyglist where 
		(fd_repmglist_payrq = '00' or fd_repmglist_payrq = '03')  and fd_repmglist_authorid = '$authorid' order by fd_repmglist_id desc limit $msgstart,$msgdisplay";
		$db->query($query);
		$msgdiscount = $db->nf();
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
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

	//充值接口		    
	public function rechargeReq() {
		$db = new DB_test();
		$db2 = new DB_test();
		
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		$paydate = trim(date("Y-m-d")); //交易日期
		$bankname = u2g(trim($arr_body['bankname'])); //银行名
		$paymoney = (trim(($arr_body['paymoney'] + 0))); //交易摘要
		$fucardno = trim($arr_body['cardno']); //银行号
		$banktype = trim($arr_body['banktype']); // 'creditcard（信用卡）或者 depositcard(储蓄卡)充值
		$cardmobile = trim($arr_body['cardmobile']); //银行手机号码
		$cardman = u2g(trim($arr_body['cardman'])); //执卡人
		$sendsms = u2g(trim($arr_body['sendsms'])); //是否发送短信
		//$paycardid = u2g(trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid']))); //银行类型
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$typeno = 'norepay';
		$paydate = date("Y-m-d H:i:s");
		$bkmoney = $paymoney;
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$paymoney, $fucardno);
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$arr_feeinfo['bkordernumber']=$bkordernumber = $arr_bkinfo['bkorderNumber'];
		$arr_feeinfo['bkmoney'] = $paymoney;
		$ccgno = makeorderno("rechargeglist", "rechargelist", "rcg");
		//echo $ccgno;
		$query = "insert into tb_rechargeglist(fd_rechargelist_no,fd_rechargelist_banktype,fd_rechargelist_bankname,
				             fd_rechargelist_bankcardno,fd_rechargelist_money,fd_rechargelist_datetime,
				             fd_rechargelist_bankcardman,fd_rechargelist_bankcardphone,fd_rechargelist_authorid,
				             fd_rechargelist_paycardid,fd_rechargelist_payrq,fd_rechargelist_bkntno,fd_rechargelist_bkordernumber," .
				            "fd_rechargelist_sdcrid		,fd_rechargelist_sdcrpayfeemoney,fd_rechargelist_sendsms )values
							 ('$ccgno','$banktype','$bankname',
							 '$fucardno','$paymoney',now(),
							 '$cardman','$cardmobile','$authorid',
							 '$paycardid','01','$bkntno','$bkordernumber' ," .
							 "'$sdcrid','$sdcrpayfeemoney','$sendsms')";
		$db->query($query);
		$listid = $db->insert_id();
        
		$method = 'in';
		$method = u2g($method);
		$paytype = 'recharge';

		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);

		//echo $query;
		//$bkntno = 
		$returnvalue = array (
				"msgbody" => array (
					'result' => "success",
					"bkntno" => $bkntno,
					"message" => "请求交易信息成功!"
				)
			
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//充值接口支付成功		    
	public function rechargePay() {
		$db = new DB_test();
		$db2 = new DB_test();

		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$paycardid = u2g(trim($arr_body['banktype'])); //银行类型
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		$bkntno = trim($arr_body['bkntno']); //银行号
		$result = $arr_body['result'];
		if ($result == 'success' && $bkntno !="") {
			$query = "update  tb_rechargeglist set fd_rechargelist_payrq = '00' where fd_rechargelist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else
		{
//			$query = "update  tb_rechargeglist set fd_rechargelist_payrq = '03' where fd_rechargelist_bkntno = '$bkntno'";
//			$db->query($query);
//			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '03'  where fd_agpm_bkntno = '$bkntno'";
//			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付失败!"
		);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => array (
					'result' => $arr_message['result'],
					"message" => $arr_message['message']
				)
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
    //转账汇款手续费 
	public function getSupTransferPayfee() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		
		$bankid = trim($arr_body['bankid']);
		$paymoney = trim($arr_body['money']);
		$arriveid = trim($arr_body['arriveid']);
         $paytype ='suptfmg';
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $bankid, $paymoney, $arriveid, 2,$paytype); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];

		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$money = round(($paymoney + $feemoney), 2);
		$arr_message = array (
			"result" => "success",
			"message" => "读取成功!"
		);
       $retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arriveid = $arr_feeinfo['arriveid'];//
		if(date('H')>=19 or date('H')<8)
		{
			$arriveid = '1';
		}else
		{
			$arriveid = '8';
		}
		$query = "select fd_arrive_id as arriveid , fd_arrive_name as arrivetime, '1' as activearriveid,fd_arrive_memo as activememo from tb_arrive where fd_arrive_id = '$arriveid'  "; //T+0 计算 
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['payfee'] = $feemoney;
		
		
		//$arr_msg ['msgbody'] ['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array (	
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;

	}
	//转账汇款		    
	public function SuptransferMoneyRq() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$paymoney = trim($arr_body['paymoney']);
		$shoucardno = trim($arr_body['shoucardno']); //收款卡号
		$shoucardmobile = trim($arr_body['shoucardmobile']); //收款人手机
		$shoucardman = trim(u2g($arr_body['shoucardman'])); //收款人姓名
		$shoucardbank = trim(u2g($arr_body['shoucardbank'])); //收款银行
		$fucardno = trim($arr_body['fucardno']); //付款卡号
		$fucardbank = trim(u2g($arr_body['fucardbank'])); //付款银行
		$fucardmobile = trim($arr_body['fucardmobile']); //付款人手机
		$fucardman = trim(u2g($arr_body['fucardman'])); //付款人姓名
		$current = trim($arr_body['current']); //币种
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
		$payfee = trim($arr_body['payfee']); //authorid
		$money = trim($arr_body['money']); //币种
		$shoucardmemo = trim(u2g($arr_body['shoucardmemo'])); //刷卡器设备号
		$sendsms = trim($arr_body['sendsms']); //authorid
		$arriveid = trim($arr_body['arriveid']); //币种
		$paytype = 'suptfmg';

		$feebankid = getbankid($shoucardbank); //获得银行id返回string
        
        
        CheckPayQuota::readPayQuota($authorid, $paycardid,$paytype,$paymoney); //调用判断额度使用情况 
		$arr_feeinfo = GetPayCalcuInfo :: readPayFee($authorid, $feebankid, $paymoney, $arriveid, 2,$paytype); //获取手续费信息返回array
		if (is_array($arr_feeinfo)) {
			$feemoney = $arr_feeinfo['feemoney'];
		}
		$paydate = date("Y-m-d H:i:s");
		$arrivedate = GetPayCalcuInfo :: getfeedate($paydate, $arr_feeinfo['addday']);
		$allmoney = round(($paymoney + $feemoney), 2);
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
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $fucardno);
		$bkntno = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$arr_feeinfo['bkordernumber']=$bkorderNumber = $arr_bkinfo['bkorderNumber'];
		$ccgno = makeorderno("transfermoneyglist", "tfmglist", "tfs");
		$query = "insert into tb_transfermoneyglist(
							fd_tfmglist_no			,fd_tfmglist_paycardid		,fd_tfmglist_authorid	,
				            fd_tfmglist_paydate		,fd_tfmglist_shoucardno		,fd_tfmglist_fucardno	,
				            fd_tfmglist_bkntno		,fd_tfmglist_payrq          ,fd_tfmglist_paytype     ,
				            fd_tfmglist_current		,fd_tfmglist_paymoney		,fd_tfmglist_payfee		 ,
				            fd_tfmglist_money		,fd_tfmglist_shoucardbank   ,fd_tfmglist_shoucardman ,
				            fd_tfmglist_shoucardmobile,fd_tfmglist_fucardbank	,fd_tfmglist_fucardmobile,
				            fd_tfmglist_fucardman   ,fd_tfmglist_feebankid      ,fd_tfmglist_state       ,	
				            fd_tfmglist_shoucardmemo,fd_tfmglist_sendsms        ,fd_tfmglist_arriveid    ,     	
				            fd_tfmglist_arrivedate  ,fd_tfmglist_payfeedirct    ,fd_tfmglist_bkordernumber," .
				           "fd_tfmglist_sdcrid,fd_tfmglist_sdcrpayfeemoney)values
						   ('$ccgno'		,'$paycardid'	,'$authorid'		,
						   '$paydate'		,'$shoucardno'	,'$fucardno'		,
						   '$bkntno'		,'01'			,'$paytype'			,
						   '$current'		,'$paymoney'	,'$feemoney'			,
						   '$allmoney'			,'$shoucardbank','$shoucardman'		,
						   '$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
						   '$fucardman'		,'$feebankid'	,'0'				,
						   '$shoucardmemo'	,'$feebankid'   ,'$arriveid'        , " .
		"'$arrivedate' ,'$payfeedirct'    ,'$bkorderNumber' ,'$sdcrid' ,'$sdcrpayfeemoney'  )";
		$db->query($query);
		$listid = $db->insert_id();
		//$listid = $db->insert_id();
		$method = 'in';
		$method = u2g($method);
		
		$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);
		$arr_message = array (
			"result" => "success",
			"message" => "获取成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bkntno'] = $bkntno;
		$arr_msg['msgbody']['feemoney'] = $feemoney;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//转账汇款支付成功反馈		    
	public function insertSupTransferMoney() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bkntno = trim($arr_body['bkntno']); //银行交易流水号
        $authorid = trim($arr_channelinfo['authorid']);
		$nowdate = date("Y-m-d H:i:s");
		$result = $arr_body['result'];

        $query = "select fd_tfmglist_bkordernumber as bkordernumber,fd_tfmglist_authorid as authorid ,
                  DATE_FORMAT(fd_tfmglist_paydate,'%Y%m%d') as orderTime
                  from tb_transfermoneyglist
                  where 1  and  fd_tfmglist_bkntno = '$bkntno' and fd_tfmglist_authorid = '$authorid'  limit 1";
        $arr_val= $db->get_one($query);


        $orderNumber =  $arr_val['bkordernumber'];
        $orderTime   =  $arr_val['orderTime'];

        $arr_returninfo = BankPayInfo :: bankorderquery($authorid,'',$orderNumber, $orderTime);


        $arr_returninfo =$this->Publiccls->xml_to_array($arr_returninfo);

		if ($result == 'success' && $bkntno !="") {
			$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='00' ,fd_tfmglist_paydate ='$nowdate' 
					          where fd_tfmglist_bkntno = '$bkntno'";
			$db->query($query);
			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else if($result == 'cancel')
		{
//			$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='03' ,fd_tfmglist_paydate ='$nowdate'
//					          where fd_tfmglist_bkntno = '$bkntno'";
//			$db->query($query);
//			$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '03'  where fd_agpm_bkntno = '$bkntno'";
//			$db->query($query);
			$arr_message = array (
			"result" => "success",
			"message" => "支付失败!"
		);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => array (
					'result' => $arr_message['result'],
					"message" => $arr_message['message'],
                    $arr_returninfo
				)
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//转账汇款历史记录		    
	public function readSupTransferMoneyglist() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		
		$msgstart = trim($arr_body['msgstart']) + 0;
		$msgdisplay = trim($arr_body['msgdisplay']) + 0;
		$paytype    = trim($arr_body['paytype']);
		$arr_state = array("请求交易","交易成功","交易取消","无效状态");
		$arr_state = auto_charset($arr_state, 'utf-8', 'gbk');

		if ($msgstart < 0)
			$msgstart = 0;

		$query = "select  1 from  tb_transfermoneyglist where  fd_tfmglist_authorid = '$authorid' and" .
				"  (fd_tfmglist_payrq = '00' or fd_tfmglist_payrq = '03')";
		$db->query($query);
		$msgallcount = $db->nf();
        
		$query = "select fd_tfmglist_bkordernumber as ccgno, fd_tfmglist_paydate as ccgtime,fd_tfmglist_shoucardno as huancardno,
				  fd_tfmglist_fucardno as fucardno, fd_tfmglist_fucardbank as fucardbank,fd_tfmglist_shoucardbank as  huancardbank,fd_tfmglist_paymoney as paymoney,fd_tfmglist_payfee as feemoney,
				  fd_tfmglist_money as allmoney,case 
        when fd_tfmglist_payrq ='01' then '".$arr_state[0]."'
        when fd_tfmglist_payrq ='00' then '".$arr_state[1]."'" .
       "when fd_tfmglist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[4]."' END  state from  tb_transfermoneyglist where 
									 fd_tfmglist_authorid = '$authorid' and fd_tfmglist_paytype ='$paytype' 
				and  (fd_tfmglist_payrq = '00' or fd_tfmglist_payrq = '03') order by fd_tfmglist_id desc limit $msgstart,$msgdisplay ";
		$db->query($query);
		$msgdiscount = $db->nf();
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
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
	//读取银行账户历史记录		    
	public function readshoucardList() {
		$db = new DB_test;
		
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$paytype  = trim($arr_body['paytype']);
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		//$appversion = trim($arr_body['appversion']);
    
		$query = "select ''  as shoucardid,fd_bank_id  as bankid,fd_agpm_shoucardno as shoucardno,fd_agpm_shoucardbank as shoucardbank,fd_agpm_shoucardman as shoucardman,fd_agpm_shoucardmobile as shoucardmobile, '".$paytype."' as paytype from 
			      tb_agentpaymoneylist left join tb_bank on fd_bank_name = fd_agpm_shoucardbank where fd_agpm_paytype = '$paytype' and fd_agpm_authorid = '$authorid'  and fd_agpm_shoucardno <>'' group by fd_agpm_shoucardno,fd_agpm_shoucardbank, fd_agpm_shoucardman,fd_agpm_shoucardmobile"; //只显示激活的银行列表 
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');

		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "银行账户历史记录为空!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
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
	$year = trim(date("Y", mktime()));
	$month = trim(date("m", mktime()));
	$day = trim(date("d", mktime()));

	$nowdate = $year . $month . $day;
	//echo $nowdate;
	$query = "select fd_" . $fieldname . "_no as no from tb_" . $tablename . "   order by fd_" . $fieldname . "_id  desc";
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