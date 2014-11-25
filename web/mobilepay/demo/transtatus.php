<?php
header('Content-Type:text/html;charset=utf-8');  
require ("../include/common.inc.php");
$db = new DB_test ;

$query = "select fd_agpm_bkntno as bkntno,fd_agpm_bkordernumber as brkordernumber 
      ,CONCAT_WS(',',fd_agpm_payrq,fd_agpm_bkordernumber) as bkorderstatus 
      from tb_agentpaymoneylist where fd_agpm_bkordernumber is not NULL 
			order by fd_agpm_id desc limit 100";
$arr_result = $db->get_all($query);

foreach($arr_result as $arr_val)
{
//	echo var_dump($arr_val);
	// foreach($arr_val as $key => $value)
	 //{
	 	//bankpayorderstus
		$transtatus = bankpayorderstus($arr_val['brkordernumber']);
		$bkordernumber = $arr_val['bkordernumber'];
		
		
		echo $arr_val['brkordernumber']."--".$transtatus."<br>";
		//exit;
		$query = "update tb_couponsale set fd_couponsale_payrq = '$transtatus' where fd_couponsale_bkordernumber = '$bkordernumber' ";
		$db->query($query);

		$query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transtatus',fd_agpm_datetime = now()  where fd_agpm_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update tb_creditcardglist set fd_ccglist_payrq ='$transtatus',fd_ccglist_paydate = '$nowdate' where fd_ccglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='$transtatus' ,fd_tfmglist_paydate ='$nowdate' 
				          where fd_tfmglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_repaymoneyglist set fd_repmglist_payrq ='$transtatus' ,fd_repmglist_paydate ='$nowdate' 
				          where fd_repmglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_rechargeglist set fd_rechargelist_payrq ='$transtatus' where fd_rechargelist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update tb_orderpayglist set fd_oplist_payrq ='$transtatus',fd_oplist_paydate = '$nowdate' where fd_oplist_bkordernumber = '$bkordernumber'";
		$db->query($query);
  //}	
}

   

	 //读取银联交易流水号
	 function bankpayorderstus($orderinfo) {
		 $weburl = 'http://14.18.207.56/mobilepay/';
		 $arr_orderinf = explode(",",$orderinfo);
		 $ordernumber = $arr_orderinf[1];
		 $orderTime = substr(str_replace("tfb","",$ordernumber),0,8);
		//echo $orderTime;
		// 1. 初始化
		$payurl = $weburl . "third_api/upmp_query.php?orderNumber=$ordernumber&orderTime=$orderTime";
   // echo $payurl;
   // exit;
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
     // echo "df";
		//	exit ();
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

		if ($arr_bankpay['respCode'] != '00') {
		 
		 $transStatus = $arr_bankpay["transStatus"];
			//return $output;
		} else {
			
			//$bkntno = $arr_bankpay['tn'];
            
     
      
      $transStatus = $arr_bankpay["transStatus"];
      
		
		}
			return $transStatus;
	}
?>