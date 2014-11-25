<?php
$userid = "A942987";
$userpws = md5("tfbao18929559766");

$gourl = "http://api2.ofpay.com/queryuserinfo.do?userid=$userid&userpws=$userpws&version=6.0";

$ch = curl_init ();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $gourl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$result = curl_exec($ch);
curl_close($ch);

$xml = simplexml_load_string($result);
$arr = xml2array($xml);

$result = array_get_value_by_key($arr, "ret_leftcredit");

echo "公司在欧飞的账户余额为：$result 元";

function xml2array($xml)
{
	foreach($xml->children() as $parent => $child)
	{
		$return["$parent"] = xml2array($child) ? xml2array($child) : "$child";
	}
	return $return;
}

function array_get_value_by_key($arr, $name)
{
	foreach($arr as $key => $value)
	{
		if($key == $name)
		{
			return $value;
		}
		else if(is_array($value))
		{
			$result = array_get_value_by_key($value, $name);
			if($result != "")
			{
				return $result;
			}
		}
	}
	return "";
}