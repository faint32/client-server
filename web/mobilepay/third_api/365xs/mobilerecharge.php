<?php
class recharge365xs
{
	static $MODE = "FORMAL";																			// 当前模式：TEST、FORMAL

	static $privateKey_TEST = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDL2pg7PcmU+TKT4DTMJaf1KhIrbg0dBgCx+xwd9gxZMhAWLwoK
LZnYdoZQrXZVYOiT2vqgfu+xvdJHjBOd+nqhagPqhq69Qr57+qHasdXlHieDITRr
IuazY/pxP8MESwRS92oJabNJclmgvI+Leh1XixYZMrg67Bs7AhimKQgtBwIDAQAB
AoGBAKKOOX5kEu78mFxbGT8BeCGD3uFK7KIMO1mxyAYMQmSOC03lTLg5DMkUGp8S
8525nTzqDZkWH8U4fQoYpTwAlO/d5/XG92iywXXWkSypXEveh8dtxosAHUe91Pmo
mBCIAhma06sZGFwdezNH+NP1MNX/8N6w/YHF6qB+H34izG0hAkEA5W1DHBZGfOHq
GE/VgB83sydLPGxX9pb0KWWMIBRaGv/7OpbVNpvNkKXYRbzxzXBA/m9EXfZBK6Yz
SKXd4vy1NQJBAON3Eceao/xr1zpw4pkHopuOrd0SQVgLcRxOC109P0zTzLYUDT4H
LO1TN+u40IJZNW0hQ3Obp7aPX2zLnyxODMsCQEpPUHxJbr1GQxdqzEE6W0UoxgKl
KPySujSqUm+Vh/XU0Z+ReS+92SAvx1QXNc6PvE1s5pz0hPlJVLUEHFFH/r0CQQDO
rbX+A8jU5rfdZgy+t21Mosvff2LYOS1BZrh0s938VMZA+t89aQ+tZFv/VyI+Dgi5
a+v584jkHEm8dRfgDdsZAkEAu0d3G3iiCFWgaMw2viRKvhXm0Gr9kHjMnUCM30QZ
TU7RgHTiqJFbTxjDJLHgvzched4PYlRqcqoT8FUYNUODHA==
-----END RSA PRIVATE KEY-----';

	static $privateKey_FORMAL = '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQCeAEqu1LOzLp1i6YwfnjWhF9On7r5VIoYxr+AvYTiEO3lLlKrC
sf3Cfns5l+c1r2U4+VySFOHEN/R5f9vJNugPzZRr8PzsHzV8/SeI/4breJ1DFp16
CEtOEErENty5QS9LQmltKbvkcmfPCX0SNFdWvHCDGjl4LB+sO79sySQ/JQIDAQAB
An9A5GR2vgITMFJEZMIbVgz3m76Un42HXGdfb+I+Pg3fDPDgoAsjki1gWT3rjx7/
iVqxiq2rRWKmqPcINJ2clgR4If7ar7WTzVzjLIVgBQeXKmq9jM0azvOzSKbHRX+t
MHgTkcQ6X8Mw6SioNOjQ95H+p51HD05Os2/Prz43viQHAkEAxKtD6hfZGunVrz5f
fzouly+EtCpu2weYE6lVqmTeR5XKbYC16e+hulUI11NuzRnlVYvLk89fvBFtVDD0
waEomwJBAM2qtawPtLdtb3bL+NfkCujpFeg97Ya5jA9/Lb14zukIZtmfiOAuBsqk
H9aRrA36AJg2n4VOhXfo1q5KNIGDUz8CQQCfaPbOi3AFuZ3jwtnjJUTYdMLKyk46
qUgiP7JZQBNP0OFYquhI61yazQwyhMUd6CyUj5B+iatepH2KrXfmbvubAkEAiAil
6Yzp53mHBHidu46meK/TQa3UcgxAS+++/Vfu48we20reagmHjHlKZc4sk4IM6qEW
mkH5nfpwJRAH8rI+WQJAczxiC1NXjD69GzCaDVwkQlcsx/GOY2PSpyvRSGeAmtA2
1ixlGGcr8MqcO2AqZEuGFmDich6TSrMxh4qaDMWF4g==
-----END RSA PRIVATE KEY-----';

	static $publicKey_TEST = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDG78YogpvISlW/mvP0cIVBbrVu
1OuhRuyaMGgo00CZmn2556T0n0rmNMBFMdah//lfYvlRxZQk1x6luoP1w7p8P+V9
aIvVJ6eaBflzRTVkODB+TI9nt4fL5WsHS6gaLc73lIpvbCywYNfyltKyTSOBHzT3
WUoWPblFHTFciJE76wIDAQAB
-----END PUBLIC KEY-----';

	static $publicKey_FORMAL = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCeAEqu1LOzLp1i6YwfnjWhF9On
7r5VIoYxr+AvYTiEO3lLlKrCsf3Cfns5l+c1r2U4+VySFOHEN/R5f9vJNugPzZRr
8PzsHzV8/SeI/4breJ1DFp16CEtOEErENty5QS9LQmltKbvkcmfPCX0SNFdWvHCD
Gjl4LB+sO79sySQ/JQIDAQAB
-----END PUBLIC KEY-----';

	// 代理商编号，由系统配置
	static $macid_TEST = 'admin';
	static $macid_FORMAL = 'tongfubao';
	
	static $phone_TEST = '13958168215';

	static $url_TEST = "http://42.120.5.186:82/shop/buyunit/orderpay.do";
	static $url_FORMAL = "http://port.365xs.cn/shop/buyunit/orderpay.do";
	
	public function Recharge($data)
	{
		// 之所以这样写，是因为向上系统的时间跟我们的不一样，相差了八个小时
		$now = time() + (8 * 60 * 60);
$logger = Logger::getLogger('365xs');
$logger->info("开始向上充值($now) : " . print_r($data, true));
		$deno = $data['money'] + 0;															// 面额
		$macid = recharge365xs :: $MODE == "TEST" ? recharge365xs :: $macid_TEST : recharge365xs :: $macid_FORMAL;
		$orderid = $data['orderId'];														// 合作商订单号
		
		$orderid = substr($orderid, 6, 20);													// 由于向上的接口要求订单号不超过20位
		
		// 充值号码
		$phone = recharge365xs :: $MODE == "TEST" ? recharge365xs :: $phone_TEST : $data['phone'];
		
		// 这个私钥是向上提供的测试示例
		$privateKey = recharge365xs :: $MODE == "TEST" ? recharge365xs :: $privateKey_TEST : recharge365xs :: $privateKey_FORMAL;
		
		$sign = "deno" . $deno . "macid" . $macid . "orderid" . $orderid . "phone" . $phone . "time" . $now;
$logger->debug("正在向上充值($now) : 生成签名的明文($sign)");
		$piKey = openssl_pkey_get_private($privateKey);
		$encrypted = "";
		openssl_sign($sign, $encrypted, $piKey);
$logger->debug("正在向上充值($now) : 生成的密文($encrypted)");
		$sign = urlencode(base64_encode($encrypted));
$logger->debug("正在向上充值($now) : sign($sign)");
		$url = recharge365xs :: $MODE == "TEST" ? recharge365xs :: $url_TEST : recharge365xs :: $url_FORMAL;
		$url .= "?deno=$deno&macid=$macid&orderid=$orderid&phone=$phone&sign=$sign&time=$now";
$logger->info("正在向上充值($now) : url($url)");
		$maxTryCount = 3;
		$tryCount = 0;
		while($tryCount < $maxTryCount && !$xml)
		{
			$tryCount++;
			$xml = simplexml_load_file($url);
		}
		
		if($xml)
		{
$logger->info("正在向上充值($now) : 经历过$tryCount 次尝试后，返回的结果(" . print_r($xml, true) . ")");
			$xml = xml2array($xml);
			recharge365xs :: Feedback($xml, $orderId);
		}
		else
		{
$logger->error("完成向上充值($now) : 经历过$tryCount 次尝试后，($url) 没有返回数据");
		}
	}
	
	public function Feedback($result, $orderId)
	{
		$now = time() + (8 * 60 * 60);
$logger = Logger::getLogger('365xs');
$logger->info("开始向上充值返回结果分析($now) : " . print_r($result, true));
		if(!isset($result["sign"]))
		{
$logger->error("结束向上充值返回结果分析($now) : sign为空");
			return;
		}

		$sign = "deno" . $result["deno"] . "errcode" . $result["errcode"] . "errinfo" . $result["errinfo"] 
				. "id" . $result["id"] . "orderid" . $result["orderid"] . "successdeno" . $result["successdeno"];
		
		$publicKey = recharge365xs :: $MODE == "TEST" ? recharge365xs :: $publicKey_TEST : recharge365xs :: $publicKey_FORMAL;
		$puKey = openssl_pkey_get_public($publicKey);
		
		$match = (bool)openssl_verify($sign, urldecode(base64_decode($result["sign"])), $puKey);
		if(!$match)
		{
$logger->error("结束向上充值返回结果分析($now) : 签名验证错误：明文($sign), 密文(" . $result["sign"] . ")");
			//return;
		}
		
		$orderId = $orderId != "" ? $orderId : "tfbmrc" . $result["orderid"];
		if(isset($result["errcode"]) && $result["errcode"] == "OrderSuccess")
		{
$logger->info("正在向上充值返回结果分析($now) : 通付宝订单号($orderId) 充值成功");
			$db = new DB_test();
			$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_payrq = '00', fd_mrclist_ofstate = 1 
					WHERE fd_mrclist_bkordernumber = '". $orderId . "'";
$logger->debug("完成向上充值返回结果分析($now) : 回写状态进通付宝数据库($query)");
			$db->query($query);
		}
		else
		{
$logger->info("正在向上充值返回结果分析($now) : 通付宝订单号($orderId) 提交成功");
		}
	}
}

function xml2array($xml)
{
	foreach($xml->children() as $parent => $child)
	{
		$return["$parent"] = xml2array($child) ? xml2array($child) : "$child";
	}
	return $return;
}