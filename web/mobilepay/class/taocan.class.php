<?php

/*
 * 支付产生流水相关类
 */
class AgentPayglist {
	public static function insertPayglist($xml, $bkntno, $listid, $listno, $paytype, $method, $arr_feeinfo) {
		$Publiccls = new PublicClass(); //初始化类实例 
		$arr_xml = $Publiccls->xml_to_array($xml);

		$arr_channelinfo = $arr_xml['operation_request']['msgheader']['channelinfo'];
		$arr_body = $arr_xml['operation_request']['msgbody'];
		$authorid = trim($arr_channelinfo['authorid']);
		//$paytype = trim ( $arr_body ['paytype'] );
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
		$paycardid = trim(GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'])); //刷卡器设备号
		$payfee = trim($arr_body['payfee']); //authorid
		$money = trim($arr_body['money']); //币种

		$arrivedate = $arr_feeinfo['arrivedate'];
		$paydate = $arr_feeinfo['paydate'];
		$bkmoney = $arr_feeinfo['bkmoney'];
		$payfeedirct = $arr_feeinfo['payfeedirct'];
		$bkordernumber = $arr_feeinfo['bkordernumber'];
		$sdcrid = $arr_feeinfo['sdcrid'];
		$sdcrpayfeemoney = $arr_feeinfo['sdcrpayfeemoney'];
		$payfee = $arr_feeinfo['feemoney'];
		$paymoney = $arr_feeinfo['paymoney'];
		$money = round(($paymoney + $payfee), 2);
		

		if ($paytype == 'coupon') {
			$vare = self :: getcouponSale($xml, $listno, $listid, $paytype, $bkntno, $arr_feeinfo);
		} else
			if ($paytype == 'recharge') {
				$vare = self :: getrechargeSale($xml, $listno, $listid, $paytype, $bkntno, $arr_feeinfo);
			} else {
				$db = new DB_test();
				$query = "insert into tb_agentpaymoneylist(
												fd_agpm_no			,fd_agpm_paycardid		,fd_agpm_authorid	 ,
												fd_agpm_paydate	    ,fd_agpm_shoucardno	    ,fd_agpm_fucardno	 ,
												fd_agpm_bkntno		,fd_agpm_payrq          ,fd_agpm_paytype     ,
												fd_agpm_current	    ,fd_agpm_paymoney		,fd_agpm_payfee		 ,
												fd_agpm_money		,fd_agpm_shoucardbank   ,fd_agpm_shoucardman ,
												fd_agpm_shoucardmobile,fd_agpm_fucardbank	,fd_agpm_fucardmobile,
												fd_agpm_fucardman   ,fd_agpm_feebankid      ,fd_agpm_state       ,
												fd_agpm_listid      , fd_agpm_listno         ,fd_agpm_method      ," .
				"fd_agpm_payfeedirct ,fd_agpm_bkordernumber  ,fd_agpm_datetime," .
				"fd_agpm_arrivedate  ,fd_agpm_bkmoney ,fd_agpm_sdcrid      ," .
				"fd_agpm_sdcrpayfeemoney)values
												('$ccgno'		,'$paycardid'	,'$authorid'		,
												'$paydate'		,'$shoucardno'	,'$fucardno'		,
												'$bkntno'		,'01'			,'$paytype'			,
												'$current'		,'$paymoney'	,'$payfee'			,
												'$money'			,'$shoucardbank','$shoucardman'		,
												'$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
												'$fucardman'		,'$feebankid'	,'0'	            ,		
												'$listid'		,'$listno'	    ,'$method'	        ," .
				"'$payfeedirct'   ,'$bkordernumber', now()       		," .
				"'$arrivedate'	,'$bkmoney'  ,'$sdcrid' ,'$sdcrpayfeemoney'   
																															  )";
				$db->query($query);
			}
		return true;
	}
	static function getcouponSale($xml, $listno, $listid, $paytype = 'coupon', $bkntno, $arr_feeinfo) {
		$db = new DB_test();
		$Publiccls = new PublicClass(); //初始化类实例 
		$arr_xml = $Publiccls->xml_to_array($xml);

		$arr_channelinfo = $arr_xml['operation_request']['msgheader']['channelinfo'];
		$arr_body = $arr_xml['operation_request']['msgbody'];
		$authorid = $arr_channelinfo['authorid'];
		$couponid = trim($arr_body['couponid']);
		$paymoney = trim($arr_body['couponmoney']);
		$paycardid = trim(GetPayCalcuInfo :: readpaycardid($arr_body['paycardid']));
		$fucardno = trim($arr_body['creditcardno']);
		$fubank = trim(u2g($arr_body['creditbank']));
		$fucardman = trim(u2g($arr_body['creditcardman']));
		$fucardphone = trim(u2g($arr_body['creditcardphone']));

		$arrivedate = $arr_feeinfo['arrivedate'];
		$paydate = $arr_feeinfo['paydate'];
		$bkmoney = $arr_feeinfo['bkmoney'];
		$payfeedirct = $arr_feeinfo['payfeedirct'];
		$bkordernumber = $arr_feeinfo['bkordernumber'];
		$sdcrid = $arr_feeinfo['sdcrid'];
		$sdcrpayfeemoney = $arr_feeinfo['sdcrpayfeemoney'];
		$payfee = $arr_feeinfo['feemoney'];
		$paymoney = $arr_feeinfo['paymoney'];
		$shoucardno = $arr_feeinfo['shoucardno'];
		$shoucardmobile = $arr_feeinfo['shoucardmobile'];
		$shoucardbank   = $arr_feeinfo['shoucardbank'];
		$shoucardman = $arr_feeinfo['shoucardman'];
		$money = round(($paymoney + $payfee), 2);
		$method = 'in';
		$method = u2g($method);
		$query1 = "insert into tb_agentpaymoneylist(
					fd_agpm_no			,fd_agpm_paycardid		,fd_agpm_authorid	 ,
					fd_agpm_paydate	    ,fd_agpm_shoucardno	    ,fd_agpm_fucardno	 ,
					fd_agpm_bkntno		,fd_agpm_payrq          ,fd_agpm_paytype     ,
					fd_agpm_current	    ,fd_agpm_paymoney		,fd_agpm_payfee		 ,
					fd_agpm_money		,fd_agpm_shoucardbank   ,fd_agpm_shoucardman ,
					fd_agpm_shoucardmobile,fd_agpm_fucardbank	,fd_agpm_fucardmobile,
					fd_agpm_fucardman   ,fd_agpm_feebankid      ,fd_agpm_state       ,
					fd_agpm_listid      ,fd_agpm_listno         ,fd_agpm_method ,
					fd_agpm_datetime    ,fd_agpm_payfeedirct    ,fd_agpm_arrivedate  ,
					fd_agpm_bkordernumber,fd_agpm_bkmoney       ,fd_agpm_sdcrid      ,
		            fd_agpm_sdcrpayfeemoney)values
					('$listno'		,'$paycardid'	,'$authorid'		,
					 now()		,'$shoucardno'	,'$fucardno'		    ,
					'$bkntno'		,'01'			,'$paytype'			,
				    '$current'		,'$paymoney'	,'$payfee'			,
					'$paymoney'			,'$shoucardbank','$shoucardman' ,
					'$shoucardmobile','$fucardbank'	,'$fucardphone'  	,
					'$fucardman'		,'$feebankid'	,'0'	        ,		
					'$listid'		,'$listno'	    ,'$method'	,now()  ," .
				   "'$payfeedirct'   ,'$arrivedate'  , '$bkordernumber' , " .
				   "'$bkmoney'    ,'$sdcrid' ,'$sdcrpayfeemoney'   			)";

		$db->query($query1);
		return 1;
	}

	static function getrechargeSale($xml, $listno, $listid, $paytype = 'recharge', $bkntno, $arr_feeinfo) {
		$db = new DB_test();
		$Publiccls = new PublicClass(); //初始化类实例 
		$arr_xml = $Publiccls->xml_to_array($xml);

		$arr_channelinfo = $arr_xml['operation_request']['msgheader']['channelinfo'];
		$arr_body = $arr_xml['operation_request']['msgbody'];
		$paycardid = trim(GetPayCalcuInfo :: readpaycardid($arr_body['paycardid']));
		$authorid = trim($arr_channelinfo['authorid']); //操作者
		$paydate = trim(date("Y-m-d")); //交易日期
		$fucardbank = u2g(trim($arr_body['bankname'])); //银行名
		$paymoney = (trim(($arr_body['paymoney'] + 0))); //交易摘要
		$fucardno = trim($arr_body['cardno']); //银行号
		$banktype = trim($arr_body['banktype']); //银行号
		$fucardphone = trim($arr_body['cardmobile']); //银行号
		$fucardman = u2g(trim($arr_body['cardman'])); //银行号

		$arrivedate = $arr_feeinfo['arrivedate'];
		$paydate = $arr_feeinfo['paydate'];
		$bkmoney = $arr_feeinfo['bkmoney'];
		$payfeedirct = $arr_feeinfo['payfeedirct'];
		$bkordernumber = $arr_feeinfo['bkordernumber'];
		$sdcrid = $arr_feeinfo['sdcrid'];
		$sdcrpayfeemoney = $arr_feeinfo['sdcrpayfeemoney'];
		$payfee = $arr_feeinfo['feemoney'];
		$paymoney = $arr_feeinfo['paymoney'];
		$money = round(($paymoney + $payfee), 2);
		$method = 'in';
		$method = u2g($method);
		$paytype = 'recharge';
		$query1 = "insert into tb_agentpaymoneylist(
					fd_agpm_no			,fd_agpm_paycardid		,fd_agpm_authorid	 ,
					fd_agpm_paydate	    ,fd_agpm_shoucardno	    ,fd_agpm_fucardno	 ,
					fd_agpm_bkntno		,fd_agpm_payrq          ,fd_agpm_paytype     ,
					fd_agpm_current	    ,fd_agpm_paymoney		,fd_agpm_payfee		 ,
					fd_agpm_money		,fd_agpm_shoucardbank   ,fd_agpm_shoucardman ,
					fd_agpm_shoucardmobile,fd_agpm_fucardbank	,fd_agpm_fucardmobile,
					fd_agpm_fucardman   ,fd_agpm_feebankid      ,fd_agpm_state       ,
					fd_agpm_listid      ,fd_agpm_listno         ,fd_agpm_method      ,
					fd_agpm_datetime    ,fd_agpm_payfeedirct    ,fd_agpm_arrivedate  ," .
					"fd_agpm_bkordernumber,fd_agpm_bkmoney ,fd_agpm_sdcrid      ," .
					"fd_agpm_sdcrpayfeemoney)values
					('$listno'		,'$paycardid'	,'$authorid'		,
					now()		,'$shoucardno'	,'$fucardno'		,
					'$bkntno'		,'01'			,'$paytype'			,
					'$current'		,'$paymoney'	,'$payfee'			,
					'$paymoney'			,'$shoucardbank','$shoucardman'		,
					'$shoucardmobile','$fucardbank'	,'$fucardphone'  	,
					'$fucardman'		,'$feebankid'	,'0'	            ,		
					'$listid'		,'$listno'	    ,'$method'	,now()  ," .
					"'$payfeedirct'   ,'$arrivedate'  , '$bkordernumber'  , " .
					"'$bkmoney'   ,'$sdcrid' ,'$sdcrpayfeemoney'   			)";
		$db->query($query1);
		return 1;
	}
	
	public static function savePayCardinfo($paycardid)
	{
		$db = new DB_test();
		$datetime  = date("Y-m-d H:i:s");
		$dataArray = array('fd_paycard_newspaydata'=>$datetime);
		$where     = " fd_paycard_id = '$paycardid'"; 
		$db->update("tb_paycard", $dataArray, $where);
		
		return "1";
		
	}

}

/*
 * 手续费计算 到帐周期计算 刷卡器检测
 * 
 * 
 */

class GetPayCalcuInfo {
	//读取信用卡还款/转账汇款/还贷款手续费
	public static function readPayFee($authorid, $bankid, $money, $arriveid, $type,$paytype ='') {
		$db = new DB_test();
		$ErrorReponse = new ErrorReponse();
		// $authorid 商户id $bankid 银行id   $money 金额   $type 交易类型:1为信用卡还款,2为转账汇款,3为信贷还款
		$query = "select fd_author_bkcardscdmsetid as bkcardscdmsetid , fd_author_bkcardpayfsetid as bkcardpayfsetid," .
		" fd_author_slotscdmsetid as slotscdmsetid, fd_author_slotpayfsetid as slotpayfsetid from tb_author " .
		" where fd_author_id='$authorid'";
		//echo $query;
		if ($db->execute($query)) {
			$array_taocan = $db->get_one($query);
		}
		$arr_returnvalue = GetPayCalcuInfo :: getTaocanfee($authorid, $array_taocan, $type, $money,$paytype);

		return $arr_returnvalue;
	}
	public static function getTaocanfee($authorid, $array_taocan, $type, $money,$paytype) {
		$db = new DB_test();
		$ErrorReponse = new ErrorReponse();
		$bkcardscdmsetid = $array_taocan['bkcardscdmsetid'];
		$bkcardpayfsetid = $array_taocan['bkcardpayfsetid'];
		$slotscdmsetid = $array_taocan['slotscdmsetid'];
		$slotpayfsetid = $array_taocan['slotpayfsetid'];
		// echo var_dump($array_taocan);
		switch ($type) {
			case "1" : //  信用卡还款
				$listid = $bkcardpayfsetid;
				$arr_feeset = self :: getcreditcardsetinfo($money);
				$getfeemoney = $arr_feeset['feemoney']; //信用卡还款固定自己的费率设置 
				$getdefeedirct = $arr_feeset['defeedirct']; //信用卡还款付款方扣还是收款方扣
				$getarriveid = $arr_feeset['arriveid']; //信用卡还款付款方扣还是收款方扣 
				$slotcardmoney = $arr_feeset['slotcardmoney']; //每笔最大金额
				if ($money > $slotcardmoney) //支付金额跟最大金额判断 
					{
					$Error = array (
						'rettype' => '200',
						'retcode' => '200',
						'retmsg' => '刷卡额度受限。最大额度为:' . $slotcardmoney . "元"
					);
					$ErrorReponse->reponError($Error);
					exit ();
				}
				break;
			case "2" : // 转账汇款
				$listid = $bkcardpayfsetid;
				$arr_feeset = self :: gettransfermoneysetinfo($money,$paytype);
				$getfeemoney = $arr_feeset['feemoney']; //信用卡还款固定自己的费率设置 
				$getdefeedirct = $arr_feeset['defeedirct']; //信用卡还款付款方扣还是收款方扣
				$getarriveid = $arr_feeset['arriveid']; //信用卡还款付款方扣还是收款方扣 
				$slotcardmoney = $arr_feeset['slotcardmoney']; //每笔最大金额
				if ($money > $slotcardmoney) //支付金额跟最大金额判断 
					{
					$Error = array (
						'rettype' => '200',
						'retcode' => '200',
						'retmsg' => '刷卡额度受限。最大额度为:' . $slotcardmoney . "元"
					);
					$ErrorReponse->reponError($Error);
					exit ();
				}
				break;
			case "3" : // 还贷款
				$listid = $bkcardpayfsetid;
                foreach ($array_taocan as $value) {
			if ($value == '0') {
				$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '请补充完整商家套餐,错误的代码[200]'
				);
				$ErrorReponse->reponError($Error); //出错反馈 
			}
		}
				break;
		  case "5" : // 抵用券
				$listid = $bkcardpayfsetid;
				//$arr_feeset = self :: getcouponysetinfo($money);
			    $getfeemoney = "0"; //信用卡还款固定自己的费率设置 
				$getdefeedirct = "s"; //信用卡还款付款方扣还是收款方扣
				$getarriveid = $arr_feeset['arriveid']; //信用卡还款付款方扣还是收款方扣 
				//$slotcardmoney = $arr_feeset['slotcardmoney']; //每笔最大金额
				
				break;
			default : //银行卡
				foreach ($array_taocan as $value) {
			if ($value == '0') {
				$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '请补充完整商家套餐,错误的代码[200]'
				);
				$ErrorReponse->reponError($Error); //出错反馈 
			}
		}
				$listid = $bkcardpayfsetid;
				break;

		}

		$query = "select fd_payfset_maxfee as maxfee ,fd_payfset_minfee as minfee ,fd_payfset_fee as fee ," .
		"fd_payfset_defeedirct as defeedirct  ,fd_payfset_arriveid as arriveid ,fd_payfset_fixfee as fixfee " .
		"from tb_payfeeset where fd_payfset_id='$listid' ";
		if ($db->execute($query)) {
			$array_taocan = $db->get_one($query);
			if ($array_taocan['fixfee'] > 0) {
				$feemoney = $array_taocan['fixfee'];

			} else {
				//$fee = $db->f(fd_transfermoneyset_sqperfee)
				$fee = substr($array_taocan['fee'], 0, -1); //浮动手续费 
				$feemoney = round(($fee / 100 * $money), 2);
				$feemoney = $feemoney < $array_taocan['minfee'] ? $array_taocan['minfee'] : $feemoney;
				$feemoney = $feemoney > $array_taocan['maxfee'] ? $array_taocan['maxfee'] : $feemoney;
			}

		}
		// $feemoney = $array_taocan['fee'];
		$defeedirct = $array_taocan['defeedirct'];
       
		if ($getfeemoney || $type =='5') {
			$feemoney = $getfeemoney +0; //固定功能替换成功能手续费	
		}
		if ($getdefeedirct) {
			$defeedirct = $getdefeedirct; //固定功能替换成功能手续费	
		}
		$arriveid = $array_taocan['arriveid'] ? $array_taocan['arriveid'] : 3;

		if ($getarriveid) {
			$arriveid = $getarriveid; //固定功能替换成功能手续费	
		}
       
		$arr_addday = self :: readarrive($arriveid);
		//$feemoney =  $bkcardpayfsetid;
		$arrvalue = array (
			'feemoney' => $feemoney,
			'defeedirct' => $defeedirct,
			'arriveid' => $arriveid,
			'paymoney' => $money,
			'addday' => $arr_addday['addday']
		);
		return $arrvalue;

	}
		public static function readarrive($arriveid) {
		$db = new DB_test();
		$query = "select fd_arrive_addday as addday,fd_arrive_name as name from tb_arrive where fd_arrive_id = '$arriveid'";
		if ($db->execute($query)) {
			$array_return = $db->get_one($query);
		}

		return $array_return;
	}
	public static function readpaycardid($paycardkey) {
		global $authorid;
		global $arr_limitauthorid;
		$ErrorReponse = new ErrorReponse();
		$db = new DB_test();
		$paycardkey = strtolower($paycardkey);
		$paycardkey = str_replace("fff","",$paycardkey);
		// 需要开通-cai 
		if(!in_array($authorid, $arr_limitauthorid))
		{
        	return false;
		}
		$query = "select fd_paycard_id as paycardid  from tb_paycard where fd_paycard_key = '$paycardkey'";
		//echo $query;
		if ($db->execute($query)) {
			$array_return = $db->get_one($query);
		}
		if ($array_return['paycardid'] == '') {
			if(!in_array($authorid, $arr_limitauthorid))
			{
			return $array_return['paycardid']; // 需要开通-cai
			}
			$Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => '刷卡器设备号未找到，客户端请求错误[200]'
			);
			$ErrorReponse->reponError($Error); //出错反馈 
		}

		if (self :: CheckpayCard($array_return['paycardid'])) {
			return $array_return['paycardid'];
		} else {
			$Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => '未登记刷卡器，请先激活刷卡器[200]'
			);
			$ErrorReponse->reponError($Error); //出错反馈 
		}
	}
	//插卡器检测是否可以使用		    
	public static function CheckpayCard($paycardid) {
		global $authorid;
		global $arr_limitauthorid;
		$ErrorReponse = new ErrorReponse();
		$db = new DB_test();
		global $authorid;
		$query = "select fd_paycard_id,fd_paycard_active,fd_paycard_posstate from tb_paycard where  fd_paycard_authorid = '$authorid' 
									      and fd_paycard_id = '$paycardid' and  fd_paycard_active = '1' and fd_paycard_posstate <> '3' and fd_paycard_posstate <>'0' ";
		$db->query($query);

		if ($db->execute($query)) {
			return true;
		} else {
			if(!in_array($authorid, $arr_limitauthorid))
		{
        	return true;
		}
			$Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => '刷卡器异常，请先激活刷卡器[200]'.$paycardid
			);
			$ErrorReponse->reponError($Error); //出错反馈 
			exit;

		}
		//return true;
	}
	private static function getcreditcardsetinfo($money) {
		$db = new DB_test();
		$query = "select * from tb_creditcardset ";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$listid = $db->f(fd_creditcset_id);
			$slotcardmoney = $db->f(fd_creditcset_slotcardmoney); //每笔最大额度 
			$dayslotcard = $db->f(fd_creditcset_dayslotcard);
			$minfee = $db->f(fd_creditcset_minfee);
			$fee = substr($db->f(fd_creditcset_sqfee), 0, -1); //浮动费率
			$maxmoney = $db->f(fd_creditcset_maxmoney);
			$fixfee = $db->f(fd_creditcset_fee); //固定费率
			$maxfee = $db->f(fd_creditcset_maxfee);
			$monthslotcard = $db->f(fd_creditcset_monthslotcard);
			$mode = $db->f(fd_creditcset_mode);
			$arriveid = $db->f(fd_creditcset_arriveid);
		}
		switch ($mode) //费率计算方式
			{
			case "fix" :
				$feemoney = $fixfee;
				break;
			default :
				$feemoney = round(($fee / 100 * $money), 2);
				$feemoney = $feemoney < $minfee ? $minfee : $feemoney;
				$feemoney = $feemoney > $maxfee ? $maxfee : $feemoney;
				break;
		}
		$defeedirct = 'f'; //信用卡还款是收方扣款 
		$arr_credcardset = array (
			"feemoney" => $feemoney,
			"defeedirct" => $defeedirct,
			"slotcardmoney" => $slotcardmoney,
			"arriveid" => $arriveid
		);
		return $arr_credcardset;
	}
	private static function getcouponsetinfo($money) {
		$db = new DB_test();
		$query = "select * from tb_couponset ";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$fee = $db->f(fd_couponcset_fee); //固定费率
			$minfee = $db->f(fd_couponcset_minfee); //固定费率
			$maxfee = $db->f(fd_couponcset_maxfee); //固定费率
		}
      
		$feemoney = round(($fee / 100 * $money), 2);
		$feemoney = $feemoney < $minfee ? $minfee : $feemoney;
		$feemoney = $feemoney > $maxfee ? $maxfee : $feemoney;
		
	
		$defeedirct = 'f'; //信用卡还款是收方扣款 
		$arr_credcardset = array (
			"feemoney" => $feemoney
		);
		return $arr_credcardset;
	}

	private static function gettransfermoneysetinfo($money,$paytype) {
		$db = new DB_test();
		$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype = '$paytype'";
		$db->query($query);
		//echo $query;
		if ($db->nf()) {
			$db->next_record();
			$listid = $db->f(fd_transfermoneyset_id);
			$slotcardmoney = $db->f(fd_transfermoneyset_slotcardmoney); //每笔最大额度 
			$dayslotcardcount = $db->f(fd_transfermoneyset_dayslotcardcount);
			$minfee = $db->f(fd_transfermoneyset_minperfee);
			$fee = substr($db->f(fd_transfermoneyset_sqperfee), 0, -1); //浮动手续费 
			$datemaxcount = $db->f(fd_transfermoneyset_datemaxcount);
			$fixfee = $db->f(fd_transfermoneyset_perfee); //固定手续费
			$maxfee = $db->f(fd_transfermoneyset_maxperfee);
			$mode = $db->f(fd_transfermoneyset_mode);
			$defeedirct = $db->f(fd_transfermoneyset_defeedirct); // s  or  f
			$arriveid = $db->f(fd_transfermoneyset_arriveid);
		}
		switch ($mode) //费率计算方式
			{
			case "fix" :
				$feemoney = $fixfee;
				break;
			default :
				$feemoney = round(($fee / 100 * $money), 2);
				$feemoney = ($feemoney < $minfee) ? $minfee : $feemoney;
				$feemoney = ($feemoney > $maxfee) ? $maxfee : $feemoney;
				break;
		}
		$arr_credcardset = array (
			"feemoney" => $feemoney,
			"defeedirct" => $defeedirct,
			"arriveid" => $arriveid,
			"slotcardmoney" => $slotcardmoney
		);
		return $arr_credcardset;
	}

	//获得T+N的工作日期
	public static function getfeedate($now, $n) {

		$diff = strtotime($now) + ($n * 86400);

		$dayweek = date("w", $diff);
		if ($dayweek == "6") {
			$res = date("Y-m-d", ($diff +172800));
		}
		elseif ($dayweek == "0") {
			$res = date("Y-m-d", ($diff +86400));
		} else {
			$res = date("Y-m-d", $diff);
			$computeWeek = GetPayCalcuInfo :: computeWeek($now, $res);
			if ($computeWeek > 0) {
				$res = strtotime($res) + ($computeWeek * 172800);
				$dayweek2 = date("w", $res);
				if ($dayweek2 == "6") {
					$res = date("Y-m-d", ($res +172800));
				}
				elseif ($dayweek2 == "0") {
					$res = date("Y-m-d", ($res +86400));
				} else {
					$res = date("Y-m-d", $res);
				}
			} else {
				$res = date("Y-m-d", $diff);
			}
		}
		//echo $res;
		return $res;

	}
	//获得T+N的工作日期调用
	public static function computeWeek($date1, $date2) {
		$diff = strtotime($date2) - strtotime($date1);
		$res = floor($diff / (24 * 60 * 60 * 7));
		return $res;
	}

}

/*
 * 
 * 银联交易调用类
 * 
 */
class BankPayInfo {
	//读取银联交易流水号
	public static function bankpayorder($authorid, $paycardid, $money, $cardinfo = '') {
		global $weburl;
		global $g_sdcrid;
		$ErrorReponse = new ErrorReponse();
		// 1. 初始化


		$arr_merid = BankPayInfo :: getpaymerinfo($authorid);

		$merid = $arr_merid['merid'];
		$securitykey = $arr_merid['securitykey'];
		$tradeurl = $arr_merid['tradeurl'];
		$queryurl = $arr_merid['queryurl'];
		$sdcrid = $arr_merid['sdcrid'];
		if($sdcrid>100)
		{
		$cardinfo = "{6226440123456785}";
		}else
		{
		$cardinfo = "{" . $cardinfo . "}";
		}
		$money = round($money * 100, 0) + 0;
		$orderNumber = "tfb" . date("YmdHiss");

		BankPayInfo :: getpaymerinfo($authorid);
		$payurl = $weburl . "third_api/upmp_purchase.php?money=$money&cardinfo=$cardinfo&orderNumber=$orderNumber&merid=$merid&securitykey=$securitykey&queryurl=$queryurl&tradeurl=$tradeurl";
	//	echo $payurl;
		$ch = curl_init();
		// 2. 设置选项，包括URL

		curl_setopt($ch, CURLOPT_URL, $payurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// 3. 执行并获取HTML文档内容
		//$arr_output[] = curl_exec($ch);
		$output = curl_exec($ch);
		if ($output === FALSE) {
			$Error = array (
				'rettype' => '800',
				'retcode' => '800',
				'retmsg' => curl_error($ch)
			);
			$ErrorReponse->reponError($Error);
			exit ();
		}
		//echo $output;
		$str = str_replace("(", "", str_replace(")", "", str_replace("Array", "", str_replace(" ", "", $output))));

		$arr_value = explode("[", $str);
		for ($i = 0; $i < count($arr_value); $i++) {
			$arr_valuetmp = explode("]=>", $arr_value[$i]);
			$arr_bankpay[$arr_valuetmp[0]] = $arr_valuetmp[1];
		}
		// 4. 释放curl句柄
		curl_close($ch);

		if ($arr_bankpay['respCode'] != 0) {
			$Error = array (
				'rettype' => '800',
				'retcode' => '800',
				'retmsg' => $arr_bankpay['respMsg'] . "[" . $arr_bankpay['respCode'] . "]"
			);
			$ErrorReponse->reponError($Error);
			exit;
		} else {

			$bkntno = $arr_bankpay['tn'];
			$sdcrpayfee = $arr_merid['sdcrpayfee'];
			$minsdcrpayfee = $arr_merid['minsdcrpayfee'];
			$sdcrid = $arr_merid['sdcrid'];
			$g_sdcrid  = $sdcrid;  //公户号码
			$arr_bkinfo = array (
				"bkntno" => $bkntno,
				"bkorderNumber" => $orderNumber,
				"minsdcrpayfee" => $minsdcrpayfee,
				"sdcrpayfee" => $sdcrpayfee,
				"sdcrid" => $sdcrid
			);

			return $arr_bkinfo;
		}

	}


    //查询银联订单状态
    public static function bankorderquery($authorid, $paycardid, $orderNumber, $orderTime) {
        global $weburl;
        global $g_sdcrid;
        $ErrorReponse = new ErrorReponse();
        // 1. 初始化

        $arr_merid = BankPayInfo :: getpaymerinfo($authorid);

        $merid = $arr_merid['merid'];
        $securitykey = $arr_merid['securitykey'];
        $tradeurl = $arr_merid['tradeurl'];
        $queryurl = $arr_merid['queryurl'];
        $sdcrid = $arr_merid['sdcrid'];

        $payurl = $weburl . "third_api/upmp_query.php?orderTime=$orderTime&orderNumber=$orderNumber&merid=$merid&securitykey=$securitykey&queryurl=$queryurl&tradeurl=$tradeurl";
        //	echo $payurl;
        $ch = curl_init();
        // 2. 设置选项，包括URL

        curl_setopt($ch, CURLOPT_URL, $payurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 3. 执行并获取HTML文档内容
        //$arr_output[] = curl_exec($ch);
        $output = curl_exec($ch);
        if ($output === FALSE) {
            $Error = array (
                'rettype' => '800',
                'retcode' => '800',
                'retmsg' => curl_error($ch)
            );
            $ErrorReponse->reponError($Error);
            exit ();
        }
        //echo $output;
        $str = str_replace("(", "", str_replace(")", "", str_replace("Array", "", str_replace(" ", "", $output))));

        $arr_value = explode("[", $str);
        for ($i = 0; $i < count($arr_value); $i++) {
            $arr_valuetmp = explode("]=>", $arr_value[$i]);
            $arr_bankpay[$arr_valuetmp[0]] = $arr_valuetmp[1];
        }
        // 4. 释放curl句柄
        curl_close($ch);

        if ($arr_bankpay['respCode'] != 0) {
            $Error = array (
                'rettype' => '800',
                'retcode' => '800',
                'retmsg' => $arr_bankpay['respMsg'] . "[" . $arr_bankpay['respCode'] . "]"
            );
            $ErrorReponse->reponError($Error);
            exit;
        } else {
            return $arr_ofpayinfo;
        }

    }


    private static function getpaymerinfo($authorid) {
		$db = new DB_test();
		$ErrorReponse = new ErrorReponse();
		$query = "select fd_sdcr_merid as merid,fd_sdcr_securitykey as securitykey,fd_sdcr_id as sdcrid," .
				"fd_sdcr_payfee as 		  sdcrpayfee,fd_sdcr_tradeurl as tradeurl,fd_sdcr_queryurl as queryurl," .
				"fd_sdcr_minpayfee as minsdcrpayfee," .
				"fd_sdcr_agentfee as sdcragentfee from tb_author join tb_sendcenter " .
		"on fd_sdcr_id = fd_author_sdcrid where fd_author_id = '$authorid'";

		if ($db->execute($query)) {
			$arr_merinfo = $db->get_one($query);
			return $arr_merinfo;
		} else {
			$Error = array (
				'rettype' => '100',
				'retcode' => '100',
				'retmsg' => '商户未审核，不允许操作该功能。'
			);

			$ErrorReponse->reponError($Error);
			exit;

		}

	}
}

/*
 * 商户刷卡套餐配额管理 
 * 
 * 
 */
 class CheckPayQuota
{
	public $ErrorReponse; // 出错处理 
	function __construct() {
		$this->ErrorReponse = new ErrorReponse();
	}
	
	public static function readPayQuota($authorid, $paycardid,$paytype,$paymoney) 
	{
		global $arr_limitauthorid;
		if(!in_array($authorid, $arr_limitauthorid))
		{
        	return true;
		}
		if($paytype=='coupon' || $paytype=='creditcard' || $paytype=='tfmg' || $paytype=='suptfmg' || $paytype=='order' || $paytype=='recharge')
		{
			return true;
		}
		$db = new DB_test();
		$ErrorReponse = new ErrorReponse();
		$query = "select fd_author_bkcardscdmsetid as bkcardscdmsetid , fd_author_bkcardpayfsetid as bkcardpayfsetid," .
		" fd_author_slotscdmsetid as slotscdmsetid, fd_author_slotpayfsetid as slotpayfsetid from tb_author " .
		" where fd_author_id='$authorid'";
		//echo $query;
		if ($db->execute($query)) {
			$array_taocan = $db->get_one($query);
		}
		$bkcardscdmsetid = $array_taocan['bkcardscdmsetid'];
		$slotscdmsetid = $array_taocan['slotscdmsetid'];
		$paydate = date("Y-m-d");
		$query = "select fd_scdmset_sallmoney as sallmoney,fd_scdmset_nallmoney as nallmoney, fd_scdmset_everymoney as everymoney," .
				 "fd_scdmset_everycounts as everycounts , fd_scdmset_severymoney as severymoney,fd_scdmset_neverymoney as neverymoney " .
				 "from tb_slotcardmoneyset where fd_scdmset_id='$bkcardscdmsetid' "; //额度都以元 
		if ($db->execute($query)) {
			$arr_sysquota = $db->get_one($query);
		}
		$arr_monthreqmoney = CheckPayQuota::readpmreqmoney($authorid); //申请上来的审批额度信息
	    $arr_monthquota = CheckPayQuota::readmonthnmoney($authorid, $paycardid,$paytype); //每月已使用额度信息
	    $arr_datequota  = CheckPayQuota::readdatenmoney($authorid, $paycardid,$paydate,$paytype); //本日已使用额度问题 
	    
	    
	    $sysmonthallmoney = $arr_sysquota['nallmoney']+$arr_monthreqmoney['paymoney']; //当前月份总共可以使用额度 
	    $monthallmoney = $paymoney+$arr_monthquota['paymoney']; //当前月份总共可以使用额度
	    
	    if($paymoney>$arr_sysquota['neverymoney'])
	    {
	    	$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '每笔交易额最大限制'.$arr_sysquota['neverymoney']."元"
				);
			$ErrorReponse->reponError($Error); //出错反馈 
	    }
	    if($monthallmoney>$sysmonthallmoney)  //当前月已使用额度 
	    {
	    	$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '每月可使用额度已用完，请登录PC端个人平台申请额外额度！'
				);
			$ErrorReponse->reponError($Error); //出错反馈 
	    	
	    }  
	     
	    if($arr_datequota['datecount']>=$arr_sysquota['everycounts'])  //每日刷卡次数大于套餐次数
	    {
	    	$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '您每日的刷卡次数'.$arr_sysquota['everycounts'].'已经用完，请明天再试！'
				);
			$ErrorReponse->reponError($Error); //出错反馈 
	    	
	    }        
	    if($arr_datequota['paymoney']>=$arr_sysquota['everymoney'])  //每日刷卡额度大于套餐额度
	    {
	    	$Error = array (
					'result' => 'failure',
					'retcode' => '200',
					'retmsg' => '您每日的刷卡金额'.$arr_sysquota['everymoney'].'元已经用完，请明天再试！'
				);
			$ErrorReponse->reponError($Error); //出错反馈 
	    	
	    }     
	    
		return true;
	}
	public static function readmonthnmoney($authorid, $paycardid,$paytype='')    //获取每月总额度
	{
		$db = new DB_test();
		$query = "select count(1) as monthcount,sum(fd_agpm_bkmoney) as paymoney,month(fd_agpm_paydate) as months,year(fd_agpm_paydate) as years " .
				"from tb_agentpaymoneylist where fd_agpm_authorid = '$authorid' and fd_agpm_payrq = '00' group by years,months ";
		if ($db->execute($query)) {
			$arr_monthquota = $db->get_one($query);
		}
		return $arr_monthquota;
	}
	public static function readdatenmoney($authorid, $paycardid,$paydate,$paytype='')    //获取每日总额度
	{
		$db = new DB_test();
		$query = "select count(1) as datecount,sum(fd_agpm_bkmoney) as paymoney,date_format(fd_agpm_paydate,'%y-%m-%d') as paydate " .
				"from tb_agentpaymoneylist where fd_agpm_authorid = '$authorid' and fd_agpm_payrq = '00' and fd_agpm_paydate='$paydate' and fd_agpm_paytype in ('repay','recharge') group by paydate ";
		if ($db->execute($query)) {
			$arr_datequota = $db->get_one($query);
		}
		return $arr_datequota;
	}
	public static function readpmreqmoney($authorid)
	{
		$db = new DB_test();
		$query = "select sum(fd_pmreq_repmoney) as repmoney,month(fd_pmreq_reqdatetime) as months," .
				 "year(fd_pmreq_reqdatetime) as years " .
				 "from tb_slotcardmoneyreq where fd_pmreq_authorid = '$authorid' and fd_pmreq_state = '9' " .
				 "group by years,months ";
		if ($db->execute($query)) {
			$arr_monthreqmoney = $db->get_one($query);
		}
		return $arr_monthreqmoney;
	}
}
?>