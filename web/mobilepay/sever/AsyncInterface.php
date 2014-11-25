<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");
require_once ("../include/config.inc.php");

__BeginRecivePostData();
__EndRecivePostData();
switch($_POST['FUNC'])
{
	case 'MOBILE_RECHARGE':
		require_once "../third_api/ofpay/ofpayV3.class.php";
		OfpayV3 :: MobileRecharge($_POST);
		break;
	case 'GAME_RECHARGE':
		require_once ("../third_api/ofpay/ofpayV2.class.php");
		OfpayV2 :: GameRecharge($_POST);
		break;
	case 'SEND_SMS':
		require_once "../third_api/sendsms.php";
		SendMessage($_POST);
		break;
}

/*
 * 接口开始接收POST数据
 */
function __BeginRecivePostData()
{
	ob_end_clean();
	header("Connection: close\r\n");						// 这里的冒号前面不能有空格
	header("Content-Encoding: none\r\n");
	ignore_user_abort();
	ob_start();
}

/*
 * 接口与客户端断开
 */
function __EndRecivePostData()
{
	$size = ob_get_length();
	header("Content-Length: $size");
	ob_end_flush();
	flush();
	ob_end_clean();
}