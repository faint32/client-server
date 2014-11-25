<?php
/*
 * 
 * 银联交易调用类
 * 
 */
class BankPayInfoV2 {
	//读取银联交易流水号
	public static function bankpayorder($authorid, $paycardid, $money, $cardinfo = '') {
		global $weburl;
		global $g_sdcrid;
		$ErrorReponse = new ErrorReponse();
		// 1. 初始化
		

		$arr_merid = BankPayInfoV2 :: getpaymerinfo($authorid);

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
        $randoms = rand(1000,9999);
		$orderNumber = "tfb" . date("YmdHiss").$randoms;

		BankPayInfoV2 :: getpaymerinfo($authorid);
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
		//echo $output;
		if ($output === FALSE) {
			$Error = array (
				'rettype' => '800',
				'retcode' => '800',
				'retmsg' => curl_error($ch)
			);
			$ErrorReponse->reponError($Error);
			exit ();
		}
        $file = "../../tfb_log/银联" . date('md')."-" .$sdcrid. $authorid."log" . ".txt";
        $filehandle = fopen($file, "a");
        $now = date('Y-m-d H:i:s');
        fwrite($filehandle, $now . "\r\n======银联查询内容：\r\n" . $output . "\r\n\r\n" . $payurl);
        fclose($filehandle);
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

    //查询银联订单状态
    public static function bankorderquery($authorid, $paycardid, $orderNumber, $orderTime) {
        global $weburl;
        global $g_sdcrid;
        $ErrorReponse = new ErrorReponse();
        // 1. 初始化
        $arr_merid = BankPayInfoV2 :: getpaymerinfo($authorid);
        $merid = $arr_merid['merid'];
        $securitykey = $arr_merid['securitykey'];
        $tradeurl = $arr_merid['tradeurl'];
        $queryurl = $arr_merid['queryurl'];
        $sdcrid = $arr_merid['sdcrid'];

        $payurl = $weburl . "third_api/upmp_query_v2.php?orderTime=$orderTime&orderNumber=$orderNumber&merid=$merid&securitykey=$securitykey&queryurl=$queryurl&tradeurl=$tradeurl";
        //	echo $payurl;
        $ch = curl_init();
        // 2. 设置选项，包括URL

        curl_setopt($ch, CURLOPT_URL, $payurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 3. 执行并获取HTML文档内容
        //$arr_output[] = curl_exec($ch);
        $output = curl_exec($ch);

        $file = "../../tfb_log/" . date('md')."-" .$sdcrid. "log" . ".txt";
        $filehandle = fopen($file, "a");
        $now = date('Y-m-d H:i:s');
        fwrite($filehandle, $now . "\r\n======银联查询内容：\r\n" . $output . "\r\n\r\n" . $payurl);
        fclose($filehandle);


        if ($output === FALSE) {
            $Error = array (
                'rettype' => '700',
                'retcode' => '700',
                'retmsg' => curl_error($ch)
            );
            $ErrorReponse->reponError($Error);
            exit ();
        }
        $arr_ofpayinfo = $output;
        if($output==true)
        {
            return $arr_ofpayinfo;
            exit;
        }

        // 4. 释放curl句柄
        curl_close($ch);

        if ($arr_ofpayinfo['respCode'] != 0) {
            $Error = array (
                'rettype' => '700',
                'retcode' => '700',
                'retmsg' => $arr_ofpayinfo['respMsg'] . "[" . $arr_ofpayinfo['respCode'] . "]"
            );
            $ErrorReponse->reponError($Error);
            exit;
        } else {
            return $arr_ofpayinfo;
        }

    }


}
