<?php
function SendMessage($data)
{
	$now = time();
$logger = Logger::getLogger('sendsms');
$logger->info("开始发送短信验证码($now) : " . print_r($data, true));
	$uid = "nicegan";
	$pwd = "chengan";
	$phone = $data['phone'];													// 发送的手机号码
	$url = "http://www.106jiekou.com/webservice/sms.asmx/Submit";
	$param = "account=" . $uid . "&password=" . $pwd . "&mobile=" . $phone . "&content=" . rawurlencode($data['message']);
$logger->info("正在发送短信验证码($now) : param($param)");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$returnValue = curl_exec($ch);
	if(curl_errno($ch))
$logger->error("正在发送短信验证码($now) : 发送失败 : " . curl_error($ch));
	curl_close($ch);
$logger->info("完成发送短信验证码($now) : " . print_r($returnValue, true));
}