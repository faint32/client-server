<?php

/**
 * 类名：接口处理核心类
 * 功能：组转报文请求，发送报文，解析应答报文
 * 版本：1.0
 * 日期：2012-10-11
 * 作者：中国银联UPMP团队
 * 版权：中国银联
 * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。该代码仅供参考。
 */
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require_once("./lib/upmp_service.php");

$mer_id       = $_GET['merid'];
$security_key = $_GET['securitykey'];
$tradeurl      = $_GET['tradeurl'];
$queryurl      = $_GET['queryurl'];

$orderTime = $_GET['orderTime'];
$orderNumber = $_GET['orderNumber'];
//$orderNumber  = 'tfb2014042809551313';
//$orderTime  = '20140428';
//tfb2014042809551313
$mer_id = $mer_id?$mer_id:(upmp_config::$mer_id);


//需要填入的部分
$req['version']     	= upmp_config::$version; // 版本号
$req['charset']     	= upmp_config::$charset; // 字符编码
$req['transType']   	= "01"; // 交易类型
$req['merId']       	= $mer_id; // 商户代码
$req['orderTime']   	= $orderTime; // 交易开始日期时间yyyyMMddHHmmss或yyyyMMdd
$req['orderNumber'] 	= $orderNumber; // 订单号

// 保留域填充方法
$merReserved['test']   	= "test";
$req['merReserved']   	= UpmpService::buildReserved($merReserved); // 商户保留域(可选)

$resp = array ();
$validResp = UpmpService::query($req, $resp);

// 商户的业务逻辑
if ($validResp){
	// 服务器应答签名验证成功

    include ("./httpclient.php");

    $resp = $resp;
    $client = new HttpClient('127.0.0.1');  // create a client
    $client->post('/'.CONST_APIDIR.'/third_api/upmp_feedback.php',$resp);   // get
    $fp = $client->getContent();
    echo $fp;
    exit;
    //exit;
	//print_r($resp);
}else {
	// 服务器应答签名验证失败
	echo ($resp);
    exit;
}

?>
