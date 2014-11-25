<?php
//require ("../include/global.session.php");
require_once("./lib/upmp_service.php");
$money    = $_GET['money'];
$cardinfo = $_GET['cardinfo'];
$orderNumber = $_GET['orderNumber'];
$mer_id       = $_GET['merid'];
$security_key = $_GET['securitykey'];
$tradeurl      = $_GET['tradeurl'];
$queryurl      = $_GET['queryurl'];

		
$mer_id = $mer_id?$mer_id:(upmp_config::$mer_id);


	
//需要填入的部分
$req['version']     		= upmp_config::$version; // 版本号
$req['charset']     		= upmp_config::$charset; // 字符编码
$req['transType']   		= "01"; // 交易类型
$req['merId']       		= $mer_id; // 商户代码
$req['backEndUrl']      	= upmp_config::$mer_back_end_url; // 通知URL
$req['frontEndUrl']     	= upmp_config::$mer_front_end_url; // 前台通知URL(可选)
$req['orderDescription']	= "订单描述";// 订单描述(可选)
$req['orderTime']   		= date("YmdHis"); // 交易开始日期时间yyyyMMddHHmmss
$req['orderTimeout']   		= ""; // 订单超时时间yyyyMMddHHmmss(可选)
$req['orderNumber'] 		= $orderNumber; //订单号(商户根据自己需要生成订单号)
$req['orderAmount'] 		= $money; // 订单金额
$req['orderCurrency'] 		= "156"; // 交易币种(可选)
$req['reqReserved'] 		= "透传信息"; // 请求方保留域(可选，用于透传商户信息)

// 保留域填充方法
$merReserved['customerInfo']   		= base64_encode($cardinfo);
//$merReserved['test']   		= 'test';
$req['merReserved']   		= UpmpService::buildReserved($merReserved); // 商户保留域(可选)

$resp = array ();
$validResp = UpmpService::trade($req, $resp);

// 商户的业务逻辑
if ($validResp){
	// 服务器应答签名验证成功
	print_r($resp);
}else {
	// 服务器应答签名验证失败
	print_r($resp);
}

?>
