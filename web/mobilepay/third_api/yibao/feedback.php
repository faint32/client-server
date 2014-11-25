<?php
// 20140728
require_once ("../../include/config.inc.php");

require_once ("../../class/Logger.php");
Logger::configure("../../class/Logger.ini");

$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("开始处理易宝异步返回的数据($now) : " . $_SERVER["QUERY_STRING"]);
echo "success";

require_once ("YiBaoPay.php");

$payResult = YiBaoPay :: Feedback($_GET, "", "", true);
$logger->info("正在处理易宝异步返回的数据($now) : " . print_r($payResult, true));
if($payResult["r6_Order"] != "")
{
	$orderId = $payResult["r6_Order"];
	$payType = substr($orderId, 3, 3);
	switch($payType)
	{
		case "mrc":																				// 手机充值业务
			require_once ("../../class/mobilerecharge.php");
$logger->info("正在处理易宝异步返回的数据($now) : 开始手机充值业务");
			MobileRecharge :: YiBaoPayFeedback($payResult, $orderId);
$logger->info("正在处理易宝异步返回的数据($now) : 完成手机充值业务");
			break;
	}
}