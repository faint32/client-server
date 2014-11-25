<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");
$logger = Logger::getLogger('upmp');

/**
 * 根据upmp_query.php文件修改
 * 去掉了商户的业务逻辑部分，只负责判断收没收到钱
 */
header('Content-Type:text/html;charset=utf-8');
require_once ("./lib/upmp_service.php");

$mer_id 		= $_GET['merid'];
$security_key 	= $_GET['securitykey'];
$tradeurl 		= $_GET['tradeurl'];
$queryurl 		= $_GET['queryurl'];
$orderTime 		= $_GET['orderTime'];
$orderNumber 	= $_GET['orderNumber'];

$mer_id 		= $mer_id ? $mer_id : (upmp_config::$mer_id);

$req['version'] 		= upmp_config::$version;				// 版本号
$req['charset'] 		= upmp_config::$charset;				// 字符编码
$req['transType'] 		= "01";									// 交易类型
$req['merId'] 			= $mer_id;								// 商户代码
$req['orderTime'] 		= $orderTime;							// 交易开始日期时间yyyyMMddHHmmss或yyyyMMdd
$req['orderNumber'] 	= $orderNumber;							// 订单号

$merReserved['test'] 	= "test";
// 商户保留域(可选)
$req['merReserved'] 	= UpmpService::buildReserved($merReserved);

$resp = array();
$logger->info("process upmp_query_v2" . print_r($req, true));
$validResp = UpmpService::query($req, $resp);
$logger->info("process upmp_query_v2 (" . $validResp . ", " . print_r($resp, true) . ")");

if($validResp && $resp['respCode'] == '00' && $resp['transStatus'] == '00')
{
	echo "00";
}
else
{
	echo "03";
}