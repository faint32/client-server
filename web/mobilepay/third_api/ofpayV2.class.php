<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

class OfpayV2
{
	public $userid;
	public $userpws;
	public $sporder_time;
	private $log = false;
	
	function __construct()
	{
		$this->userid = "A942987";
		$this->userpws = md5("tfbao20140603");
		$this->sporder_time = date("YmdHis");
	}
	
	// 游戏充值
	public function gameRecharge($orderNumber, $cardid, $cardnum, $game_userid, $game_area, $game_srv, &$errMsg)
	{
		$this->log = true;
		
		$md5_str = strtoupper(md5($this->userid . $this->userpws . $cardid . $cardnum . $orderNumber . $this->sporder_time . $game_userid . $game_area . $game_srv . "OFCARD"));
		$url = "http://api2.ofpay.com/onlineorder.do?userid=" . $this->userid . "&userpws=" . $this->userpws . "&cardid=$cardid&cardnum=$cardnum&sporder_id=$orderNumber&sporder_time=" . $this->sporder_time . "&game_userid=$game_userid&game_area=$game_area&game_srv=$game_srv&md5_str=$md5_str&version=6.0";
		
		$file_raw_contents = $this->getRawContent($url);
		if($file_raw_contents != "")
		{
			$state = $this->analyseContent($file_raw_contents, 'game_state', &$errMsg, &$retCode);
			$state = (int)$state;
			if($retCode == 1)
			{
				if($state == 1)
				{
					$errMsg = "充值完成";
					return true;
				}
				else
				{
					$errMsg = "正在为您充值中!";
				}
			}
		}
		return false;
	}
	
	public function getRawContent($url)
	{
$logger = Logger::getLogger('Ofpay');
if($this->log) $logger->info("开始调用欧飞接口（" . $url . "）");
		$hasReturn = false;															// 从欧飞获取到了响应数据
		$tryCount = 0;																// 已经重试的次数
		while(!$hasReturn && $tryCount < 3)
		{
			$tryCount++;
			$ch = curl_init ();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$file_contents = curl_exec($ch);
			curl_close ( $ch );
			
			if($file_contents != "")
			{
				$hasReturn = true;
if($this->log) $logger->info("从欧飞获取到了响应数据（" . $file_contents . "）");
			}
			else
			{
if($this->log) $logger->info("尝试调用了" . $tryCount . "次，没有获取到响应");
			}
		}
		
		if($file_contents == "")
		{
$logger->ERR("调用欧飞接口（" . $url . "）不成功，尝试了" . $tryCount . "次，未获取响应数据");			
		}
		return $file_contents;
	}
	
	public function analyseContent($file_contents, $key = '', &$errMsg, &$retCode)
	{
$logger = Logger::getLogger('Ofpay');
$logger->debug("开始解析欧飞返回的数据（" . $file_contents . "）");
		$file_contents = auto_charset($file_contents, 'gbk', 'utf-8');
		$file_contents = str_replace("gb2312", "utf-8", $file_contents);
		$file_contents = str_replace("GB2312", "utf-8", $file_contents);
		$file_contents = simplexml_load_string($file_contents);
$logger->debug("开始把数据解析成xml格式：" . $key);
		foreach ($file_contents->children() as $item)
		{
			switch($item->getName())
			{
				case "err_msg":
					$errMsg = (string)$item;
					break;
				case "retcode":
					$retCode = (int)$item;
					break;
				case $key:
					return $item;
				default:
					$msgBody = $item;
			}
		}
		return $msgBody;
	}
}