<?php
    //require ("../include/common.inc.php");


	//读取银联交易流水号
	 function bankpayorderstus($orderinfo) {
		 $weburl = 'http://14.18.207.56/mobilepay/';
		 $arr_orderinf = explode(",",$orderinfo);
		 $ordernumber = $arr_orderinf[1];
		$orderTime = substr(str_replace("tfb","",$ordernumber),0,8);
		// 1. 初始化
		$payurl = $weburl . "third_api/upmp_query.php?orderNumber=$ordernumber&$orderTime=$orderTime";

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

			//$ErrorReponse->reponError($Error);

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
			
			exit;
		} else {
			;
			$bkntno = $arr_bankpay['tn'];
            
           $arr_bkinfo = array("bkntno"=>$bkntno,"bkorderNumber"=>$orderNumber);
      
      $transStatus = $arr_bankpay["transStatus"];
      
			return $transStatus;
		}

	}

?>