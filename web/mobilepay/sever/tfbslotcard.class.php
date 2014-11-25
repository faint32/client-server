<?php
class AgentPayglist {
	public static function insertPayglist($xml, $bkntno, $listid, $listno, $paytype, $method) {
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
		$paycardid = trim($arr_body['paycardid']); //刷卡器设备号
		$payfee = trim($arr_body['payfee']); //authorid
		$money = trim($arr_body['money']); //币种
		if ($paytype == 'coupon') {
			$vare = self :: getcouponSale($xml, $listno, $listid, $paytype, $bkntno);
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
					            fd_agpm_listid     , fd_agpm_listno         ,fd_agpm_method      )values
							   ('$ccgno'		,'$paycardid'	,'$authorid'		,
							   '$paydate'		,'$shoucardno'	,'$fucardno'		,
							   '$bkntno'		,'01'			,'$paytype'			,
							   '$current'		,'$paymoney'	,'$payfee'			,
							   '$money'			,'$shoucardbank','$shoucardman'		,
							   '$shoucardmobile','$fucardbank'	,'$fucardmobile'	,
							   '$fucardman'		,'$feebankid'	,'0'	            ,		
							   '$listid'		,'$listno'	    ,'$method'	        			
							  )";
			$db->query($query);
		}
		return true;
	}
	static function getcouponSale($xml, $listno, $listid, $paytype = 'coupon', $bkntno) {
		$db = new DB_test();
		$Publiccls = new PublicClass(); //初始化类实例 
		$arr_xml = $Publiccls->xml_to_array($xml);
		$arr_channelinfo = $arr_xml['operation_request']['msgheader']['channelinfo'];
		$arr_body = $arr_xml['operation_request']['msgbody'];
		$authorid = $arr_channelinfo['authorid'];
		$couponid = trim($arr_body['couponid']);
		$paymoney = trim($arr_body['couponmoney']);
		$paycardid = trim($arr_body['paycardid']);
		$fucardno = trim($arr_body['creditcardno']);
		$fubank = trim(u2g($arr_body['creditbank']));
		$fucardman = trim(u2g($arr_body['creditcardman']));
		$fucardphone = trim(u2g($arr_body['creditcardphone']));

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
				            fd_agpm_listid     , fd_agpm_listno         ,fd_agpm_method ,
				            fd_agpm_datetime     )values
						   ('$listno'		,'$paycardid'	,'$authorid'		,
						   now()		,'$shoucardno'	,'$fucardno'		,
						   '$bkntno'		,'01'			,'$paytype'			,
						   '$current'		,'$paymoney'	,'$payfee'			,
						   '$paymoney'			,'$shoucardbank','$shoucardman'		,
						   '$shoucardmobile','$fucardbank'	,'$fucardphone'  	,
						   '$fucardman'		,'$feebankid'	,'0'	            ,		
						   '$listid'		,'$listno'	    ,'$method'	,now()        			
						  )";

		$db->query($query1);
		
		$authorid=$authorid+0;
		$file="./".date('Y-m-d')."-paylist".".txt";
		$filehandle=fopen($file, "a"); 
		fwrite($filehandle,"\r\n======响应内容：\r\n".$query1."\r\n\r\n\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n"); 
		fclose($filehandle);
		return 1;
	}

}
class GetPayFee {
	//读取信用卡还款/转账汇款/还贷款手续费
	public static function readPayFee($authorid, $bankid, $money, $arriveid, $type) {
		$db = new DB_test();
		// $authorid 商户id $bankid 银行id   $money 金额   $type 交易类型:1为信用卡还款,2为转账汇款,3为信贷还款
		$query = "select fd_author_bkcardscdmsetid as bkcardscdmsetid , fd_author_bkcardpayfsetid as bkcardpayfsetid," .
				 " fd_author_slotscdmsetid as slotscdmsetid, fd_author_slotpayfsetid as slotpayfsetid from tb_author " .
				 " where fd_author_id='$authorid'";
		if ($db->execute($query)) {
			$array_taocan = $db->get_one($query);
		}
		$arr_returnvalue =  GetPayFee::getTaocanfee($authorid, $array_taocan,$type,$money);
		
		return $arr_returnvalue;
	}
	
	public static function readarrive($arriveid) {
		$db = new DB_test();
		$query = "select fd_arrive_addday as addday,fd_arrive_name as name from tb_arrive where fd_arrive_id = '$arriveid'";
		if ($db->execute($query)) {
			$array_return = $db->get_one($query);
		}
		
		return $array_return;
	}
	
	public static function getTaocanfee($authorid, $array_taocan,$type,$money) {
		$db = new DB_test();
		$bkcardscdmsetid = $array_taocan[0]['bkcardscdmsetid'];
		$bkcardpayfsetid = $array_taocan[0]['bkcardpayfsetid'];
		$slotscdmsetid   = $array_taocan[0]['slotscdmsetid'];
		$slotpayfsetid   = $array_taocan[0]['slotpayfsetid'];
		
		switch ($type) {
			case "1" : //  信用卡还款
			    $listid =  $bkcardpayfsetid;
				break;
			case "2" : // 转账汇款
			    $listid =  $bkcardpayfsetid;
				break;
			case "3" : // 还款款
			    $listid =  $bkcardpayfsetid; 
				break;
			default :   //银行卡
			    $listid =  $bkcardpayfsetid;
				break;

		}
		$query = "select fd_payfset_maxfee as maxfee ,fd_payfset_minfee as minfee ,fd_payfset_fee as fee ," .
					 "fd_payfset_defeedirct as defeedirct  ,fd_payfset_arriveid as arriveid ,fd_payfset_fixfee as fixfee from tb_payfeeset where fd_payfset_id='$listid' ";
		$db->query($query);
		if ($db->execute($query)) 
		{
			$array_taocan = $db->get_one($query);
			 if($array_taocan[0]['fixfee']>0)
			  {
			  	$feemoney = $array_taocan[0]['fixfee'];
			  }else
			  {
			  	 $fee = $array_taocan[0]['fee'];
			     $feemoney = round ( ($fee / 100 * $money), 2 );
			     $feemoney = $feemoney<$array_taocan[0]['minfee']?$array_taocan[0]['minfee']:$feemoney;
			     $feemoney = $feemoney>$array_taocan[0]['maxfee']?$array_taocan[0]['maxfee']:$feemoney;
			  }
			$arrvalue = array('feemoney'=>$feemoney,'defeedirct'=>$array_taocan[0]['defeedirct'],'arriveid'=>$array_taocan[0]['defeedirct']);
			
			return $arrvalue;
		}else
		{
			return false;
		}
	
		
	}

}
class BankPayInfo {

	//读取银联交易流水号
	public static function bankpayorder($money, $cardinfo = '') {
		global $weburl;
		 $ErrorReponse = new ErrorReponse();
		// 1. 初始化
		$cardinfo = "{" . $cardinfo . "}";
		$money = round($money * 100, 0) + 0;
		$payurl = $weburl . "upmp/examples/purchase.php?money=$money&cardinfo=$cardinfo";

		$ch = curl_init();
		// 2. 设置选项，包括URL

		curl_setopt($ch, CURLOPT_URL, $payurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		// 3. 执行并获取HTML文档内容
		//$arr_output[] = curl_exec($ch);
		$output = curl_exec($ch);
		if ($output === FALSE) {
			
				$Error = array( 'rettype' => '800',
							'retcode' => '800',
							'retmsg'  => curl_error($ch));
		
		    $ErrorReponse-> reponError($Error) ;
		
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
			$Error = array( 'rettype' => '800',
							'retcode' => '800',
							'retmsg'  => $arr_bankpay['respMsg']."[".$arr_bankpay['respCode']."]");
		
		    $ErrorReponse-> reponError($Error) ;
		    exit;
		} else {
			;
			$bkorderno = $arr_bankpay['tn'];

			return $bkorderno;
		}

	}

}
?>