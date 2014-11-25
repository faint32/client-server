<?php
class BankPayInfoV3
{
	static $merchantId = "898440048990007";
	static $securityKey = "gzEZPg2pSRKt9SXbPkcKS7BV7TArNzxb";
	static $queryUrl = "https://mgate.unionpay.com/gateway/merchant/query";
	static $tradeUrl = "https://mgate.unionpay.com/gateway/merchant/trade";
	
	// 获取转账手续费，20140729
	public static function GetPayFee($money)
	{
		return $money * 0.008;
	}
	
	// 请求银联交易流水号，20140729
	public static function RequestBankTransNumber($orderId, $money, $bankCardId)
	{
$logger = Logger::getLogger('upmp');
$logger->info("开始请求银联交易流水号 : orderId($orderId), money($money), bankCardId($bankCardId)");
		global $weburl;
		
		$money = round($money * 100, 0) + 0;																// 以分为单位
		
		$url = $weburl . "third_api/upmp_purchase.php?money=$money&cardinfo={" . $bankCardId . "}&orderNumber=$orderId&merid=" . BankPayInfoV3 :: $merchantId . "&securitykey=" . BankPayInfoV3 :: $securityKey . "&queryurl=" . BankPayInfoV3 :: $queryUrl . "&tradeurl=" . BankPayInfoV3 :: $tradeUrl;
$logger->info("正在请求银联交易流水号 : url($url)");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$returnValue = curl_exec($ch);
$logger->info("正在请求银联交易流水号 : 返回的数据($returnValue)");
		$returnArray = explode("\n", $returnValue);
		$transNumber = "";
		$respCode = "";
		for($i = 0; $i < count($returnArray); $i++)
		{
			$itemArray = explode("=>", $returnArray[$i]);
			if(count($itemArray) != 2) continue;
			if(strpos($itemArray[0], "[tn]") > 0) $transNumber = trim($itemArray[1]);
			if(strpos($itemArray[0], "[respCode]") > 0) $respCode = trim($itemArray[1]);
		}
$logger->info("完成请求银联交易流水号 : transNumber($transNumber), respCode($respCode)");
		if($respCode == "00") return $transNumber;
		return "";
	}
	
    // 查询银联订单状态
    public static function GetTransStatus($orderId, $orderTime)
	{
$logger = Logger::getLogger('upmp');
$logger->info("开始查询银联订单状态 : orderId($orderId), orderTime($orderTime)");
        global $weburl;
		
        $url = $weburl . "third_api/upmp_query_v2.php?orderTime=$orderTime&orderNumber=$orderId&merid=" . BankPayInfoV3 :: $merchantId . "&securitykey=" . BankPayInfoV3 :: $securityKey . "&queryurl=" . BankPayInfoV3 :: $queryUrl . "&tradeurl=" . BankPayInfoV3 :: $tradeUrl;
$logger->info("正在查询银联订单状态 : url($url)");

        $ch = curl_init();
		
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $returnValue = curl_exec($ch);
$logger->info("正在查询银联订单状态 : 返回的数据($returnValue)");
        curl_close($ch);
		
		return $returnValue;
	}
}