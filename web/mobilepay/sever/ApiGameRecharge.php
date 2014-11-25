<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

require_once ("../third_api/ofpayV2.class.php");
require_once ("../class/BankPayInfoV2.php");

// 游戏充值接口
class ApiGameRecharge extends TfbxmlResponse
{
	public function getGameList()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");

$logger = Logger::getLogger('gamerecharge');
$logger->debug("start getGameList, now the memory_get_usage is " . memory_get_usage());
		
		$objOfpayV2 = new OfpayV2();
		$url = "http://api2.ofpay.com/querylist.do?userid=" . $objOfpayV2->userid . "&userpws=" . $objOfpayV2->userpws . "&cardid=22&version=6.0";
$logger->debug("process getGameList : url : (" . $url . "), now the memory_get_usage is " . memory_get_usage());
		
		$xmlInfo = $this->getXmlInfo($url);
		if(isset($xmlInfo))
		{
			$gameArray = array();
			foreach ($xmlInfo->children() as $game)
			{
				if(isset($game->cardid) && isset($game->cardname))
				{
					$cardname = "" . $game->cardname . "";
					$gameArray[] = array("gameId" => (string)($game->cardid), "gameName" => $cardname);
				}
			}
			if(count($gameArray) > 0)
			{
				$retcode = "0";
				$arr_message = array ("result" => "success", "message" => "获取游戏信息成功");
				$arr_msg['msgbody'] = $gameArray;
$logger->debug("process getChildGame : game list : (" . print_r($gameArray, true) . "), now the memory_get_usage is " . memory_get_usage());
			}
		}
$logger->debug("complete getGameList");
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);

		return $returnval;
	}
	
	public function getplatformList()
	{
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "获取成功");
		
		$platformList = array(
			array("platformId" => 2206, "platformName" => "Q币按元随意直充"),
			array("platformId" => 2208, "platformName" => "网易官方接口直充"),
			array("platformId" => 2241, "platformName" => "盛大一卡通/盛大点卷 直充"),
		);
		
		$arr_msg['msgbody'] = $platformList;
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function getChildGame()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");

$logger = Logger::getLogger('gamerecharge');
$logger->debug("start getChildGame");
		
		$parentGameId = trim($this->arr_body['gameId']);		
		if($parentGameId != "")
		{
$logger->debug("process getChildGame : parentGameId : (" . $parentGameId . ")");
			$objOfpayV2 = new OfpayV2();
			$url = "http://api2.ofpay.com/querycardinfo.do?userid=" . $objOfpayV2->userid . "&userpws=" . $objOfpayV2->userpws . "&cardid=$parentGameId&version=6.0";
			
			$xmlInfo = $this->getXmlInfo($url);
			if(isset($xmlInfo))
			{
				$gameArray = array();
				foreach ($xmlInfo->children() as $game)
				{
					if(isset($game->cardid) && isset($game->cardname) && isset($game->pervalue) && isset($game->inprice))
					{
						$cardname = "" . $game->cardname . "";
						$gameArray[] = array("gameId" => (string)($game->cardid), "gameName" => $cardname, "price" => (float)($game->pervalue), "cost" => (float)($game->inprice));
					}
				}
				if(count($gameArray) > 0)
				{
					$retcode = "0";
					$arr_message = array ("result" => "success", "message" => "获取游戏信息成功");
					$arr_msg['msgbody'] = $gameArray;
$logger->debug("process getChildGame : game list : (" . print_r($gameArray, true) . "), now the memory_get_usage is " . memory_get_usage());
				}
			}
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" => "传入参数错误");
		}
$logger->debug("complete getChildGame");
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function getGameDetail()
	{
		$retcode = "200";
		$arr_message = array ("result" => "failure", "message" => "操作出现异常，请稍后再试！");
		
		$gameId = trim($this->arr_body['gameId']);
		
		$gameName = "";
		if($gameId != "")
		{
			$areaList = array();

			$url = "http://api2.ofpay.com/getareaserver.do?gameid=" . $gameId;
$logger = Logger::getLogger('gamerecharge');
$logger->debug("start getGameDetail from (" . $url . ")");
			$xml = simplexml_load_file($url);
			if($xml)
			{
$logger->debug("process getGameDetail : data from ofpay : (" . $xml->asXML() . ")");
				foreach ($xml->children() as $row)
				{
					if(isset($row->GAMENAME))
					{
						$gameName = "" . $row->GAMENAME . "";
					}
					$area = "";
					$server = "";
					if(isset($row->SERVER))
					{
						$server = "" . $row->SERVER . "";
					}
					if(isset($row->AREA))
					{
						$area = "" . $row->AREA . "";
						if($areaList[$area] != "")
						{
							$areaList[$area] = $areaList[$area] . "#" . $server;
						}
						else
						{
							$areaList[$area] = $server;
						}
					}
				}
			}
			else
			{
$logger->error("process getGameDetail : can not get data from ofpay url (" . $url . ")");
			}
			
			$resultList = array();
			foreach($areaList as $key => $value)
			{
				$resultList[] = array("area" => $key, "server" => $value);
			}
			$arr_msg['msgbody'] = $resultList;

			if(count($areaList) > 0)
			{
				$retcode = "0";
				$arr_message = array ("result" => "success", "message" => "读取数据成功！");
			}
			else
			{
				$retcode = "0";
				$arr_message = array ("result" => "success", "message" => "该游戏不分区服");
			}
		}
		else
		{
			$arr_message = array ("result" => "failure", "message" => "参数错误");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['gameName'] = $gameName;
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	private function getXmlInfo($url)
	{
$logger = Logger::getLogger('gamerecharge');
$logger->debug("start getXmlInfo from (" . $url . ")");
		$xml = simplexml_load_file($url);
		if($xml)
		{
$logger->debug("process getXmlInfo : data from ofpay : (" . $xml->asXML() . ")");
			if(isset($xml->retcode))
			{
				$retCode = (int)($xml->retcode);
			}
			
			if($retCode == 1)
			{
				if(isset($xml->ret_cardinfos))
				{
					return $xml->ret_cardinfos;
				}
			}
			else
			{
				if(isset($xml->err_msg))
				{
					$errMsg = (string)($xml->err_msg);
				}
$logger->error("process getXmlInfo : fail to get data from (" . $url . "), the error message is (" . $errMsg . ")");
			}
		}
		else
		{
$logger->error("process getXmlInfo : can not get data from ofpay url (" . $url . ")");
		}
		return null;
	}

	public function createOrder()
	{
		$retcode = "200";
		$arr_message = array("result" => "fail", "message" => "操作出现异常，请稍后再试！");
		
		$authorid = trim($this->arr_channelinfo['authorid']);
		
		$arr_body = $this->arr_body;
		$gameId = trim($arr_body['gameId']);
		$gameName = trim($arr_body['gameName']);
		$gameName = auto_charset($gameName, 'utf-8', 'gbk');
		$area = trim($arr_body['area']);
		$area = auto_charset($area, 'utf-8', 'gbk');
		$server = trim($arr_body['server']);
		$server = auto_charset($server, 'utf-8', 'gbk');
		$price = trim($arr_body['price']);
		$cost = trim($arr_body['cost']);
		$quantity = trim($arr_body['quantity']);
		$userCount = trim($arr_body['userCount']);
		$paycardid = trim($arr_body['paycardid']);
		$rechabkcardno = trim($arr_body['rechabkcardno']);
		
		if($authorid == "" || $gameId == "" || $gameName == "" || $price == "" || $cost == "" || $quantity == "" || $userCount == "" || $rechabkcardno == "")
		{
			$arr_message = array ("result" => "fail", "message" => "输入信息不完整");
		}
		else
		{
			$arr_bkinfo = BankPayInfo :: bankpayorder($authorid, $paycardid, $price, $rechabkcardno);
			$bkntno = trim($arr_bkinfo['bkntno']);
			$bkorderNumber = trim($arr_bkinfo['bkorderNumber']);
			$sdcrid = trim($arr_bkinfo['sdcrid']);
			
			$arr_paycard = GetPayCalcuInfo :: readpaycardid($paycardid, $authorid);
			
			$paycardid = $arr_paycard['paycardid'];
			$cusid = trim($arr_paycard['cusid']);
			$paycardkey = trim($arr_paycard['paycardkey']);
			$payfee = $price - $cost - $price * 0.008;
			
			$datadetailArray['fd_grclist_payfee'] = $payfee;
			$datadetailArray['fd_grclist_cusid'] = $cusid;
			$datadetailArray['fd_grclist_paycardid'] = $paycardid;
			$datadetailArray['fd_grclist_authorid'] = $authorid;
			$datadetailArray['fd_grclist_paydate'] = date("Ymd");;
			$datadetailArray['fd_grclist_bkntno'] = $bkntno;
			$datadetailArray['fd_grclist_bkordernumber'] = $bkorderNumber;
			$datadetailArray['fd_grclist_sdcrid'] = $sdcrid;
			$datadetailArray['fd_grclist_rechamoney'] = $price;
			$datadetailArray['fd_grclist_bkmoney'] = $price;
			$datadetailArray['fd_grclist_paymoney'] = $price;
			$datadetailArray['fd_grclist_gamecardid'] = $gameId;
			$datadetailArray['fd_grclist_gamename'] = $gameName;
			$datadetailArray['fd_grclist_gamecardnum'] = $quantity;
			$datadetailArray['fd_grclist_gameuserid'] = $userCount;
			$datadetailArray['fd_grclist_gamearea'] = $area;
			$datadetailArray['fd_grclist_gamesrv'] = $server;
			$datadetailArray['fd_grclist_bankcardno'] = $rechabkcardno;
			$datadetailArray['fd_grclist_state'] = 0;
			$datadetailArray['fd_grclist_datetime'] = date("Y-m-d H:i:s");
			
			$db = new DB_test();
			$db->insert("tb_gamerechargelist", $datadetailArray);
			
			$arr_message = array ("result" => "success", "message" => "订单建立成功");
			$retcode = "0";
			$arr_msg['msgbody']['bkntno'] = $bkntno;
			$arr_msg['msgbody']['totalPrice'] = $price;
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}

	public function completeOrder()
	{
$logger = Logger::getLogger('gamerecharge');
$logger->debug("start completeOrder");
		$authorid = trim($this->arr_channelinfo['authorid']);
		$bkntno = trim($this->arr_body['bkntno']);
		
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
		
		$hasReceiveMoney = false;
		
		if($authorid != "" && $bkntno != "")
		{
$logger->debug("process completeOrder : authorid and bkntno : (" . $authorid . ", " . $bkntno . ")");
			$query = "SELECT *, DATE_FORMAT(fd_grclist_paydate,'%Y%m%d') as fd_grclist_paydate FROM tb_gamerechargelist WHERE fd_grclist_authorid = $authorid AND fd_grclist_bkntno = '$bkntno'";
			$db = new DB_test();
			$orderInDB = $db->get_all($query);
			
			if($orderInDB != 0 && count($orderInDB) > 0)
			{
				$orderNumber = $orderInDB[0]["fd_grclist_bkordernumber"];
				$orderTime = $orderInDB[0]["fd_grclist_paydate"];
$logger->debug("process completeOrder : orderNumber : (" . $orderNumber . ")");
				$arr_returninfo = BankPayInfoV2 :: bankorderquery($authorid, '' , $orderNumber, $orderTime);
$logger->debug("process completeOrder : value from BankPayInfoV2 : (" . $arr_returninfo . ")");
				if(md5($arr_returninfo) == "cace2a1f74fa974808c185f17ef557de")
				{
					$retcode = 0;
					$arr_message = array ("result" => "success", "message" =>  "付款已收到，正在为您充值中!");
					$hasReceiveMoney = true;
				}
				else
				{
					$arr_message = array ("result" => "fail", "message" => $arr_returninfo);
				}
			}
			else
			{
				$arr_message = array ("result" => "fail", "message" => "输入信息有误");
			}
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" => "输入信息不完整");
		}
		
		if($hasReceiveMoney)
		{
$logger->debug("process completeOrder : start ofpay");
			// 开始调用欧飞接口进行充值
			if($orderInDB != 0 && count($orderInDB) > 0)
			{
				$cardid = $orderInDB[0]["fd_grclist_gamecardid"];
				$cardnum = $orderInDB[0]["fd_grclist_gamecardnum"];
				$game_userid = $orderInDB[0]["fd_grclist_gameuserid"];
				$game_area = $orderInDB[0]["fd_grclist_gamearea"];
				$game_srv = $orderInDB[0]["fd_grclist_gamesrv"];
			
				$objOfpayV2 = new OfpayV2();
				$returnvalue =  $objOfpayV2->gameRecharge($orderNumber, $cardid, $cardnum, $game_userid, $game_area, $game_srv, &$errMsg);
$logger->debug("process completeOrder : value from OfpayV2 : (" . $returnvalue . ")");
				if($errMsg != "")
				{
					$arr_message['message'] = $errMsg;
				}
				
				$query = "UPDATE tb_gamerechargelist SET fd_grclist_state = 1, fd_grclist_datetime = NOW() WHERE fd_grclist_authorid = $authorid AND fd_grclist_bkntno = '$bkntno'";
				$db->query($query);
				
				$this->customerProfit($orderInDB);
			}
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];

        $returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 给代理商分润
	private function customerProfit($orderInDB)
	{
$logger = Logger::getLogger('gamerecharge');
$logger->debug("start customerProfit");
		if(is_array($orderInDB) && count($orderInDB) == 1 && $orderInDB[0]['fd_grclist_cusid'] != null)
		{
			$profitArray = array();
			$profitArray['fd_frlist_authorid'] = $orderInDB[0]['fd_grclist_authorid'];
			$profitArray['fd_frlist_cusid'] = $orderInDB[0]['fd_grclist_cusid'];
			$profitArray['fd_frlist_paycardid'] = $orderInDB[0]['fd_grclist_paycardid'];
			$profitArray['fd_frlist_paydate'] = date("Ymd");
			$profitArray['fd_frlist_paymoney'] = $orderInDB[0]['fd_grclist_paymoney'];
			$profitArray['fd_frlist_payfee'] = $orderInDB[0]['fd_grclist_payfee'];
			$profitArray['fd_frlist_cusfee'] = $orderInDB[0]['fd_grclist_payfee'] * 0.27;
			$profitArray['fd_frlist_bkordernumber'] = $orderInDB[0]['fd_grclist_bkordernumber'];
			$profitArray['fd_frlist_payrq'] = '00';
			$profitArray['fd_frlist_paytype'] = 'game';
			$profitArray['fd_frlist_datetime'] = date("Y-m-d H:i:s");
			$profitArray['fd_frlist_ifjsfenrun'] = 0;
			$profitArray['fd_frlist_sdcrid'] = $orderInDB[0]['fd_grclist_sdcrid'];
$logger->debug("process customerProfit : " . print_r($profitArray, true));
			$db = new DB_test();
			$db->insert("tb_cus_fenrunglist", $profitArray);
$logger->info("complete customerProfit : " . print_r($profitArray, true));
		}
	}
	
	public function getOrderHistory()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
		
		$authorid = trim($this->arr_channelinfo['authorid']);
		
		if($authorid > 0)
		{
			$arr_body = $this->arr_body;
		
			$start = intval(trim($arr_body['msgstart']));
			$start = $start > 0 ? $start : 0;
			$count = intval(trim($arr_body['msgdisplay']));
			$count = $count > 0 ? $count : 8;
			
			$db = new DB_test();
			
			$query = "SELECT fd_grclist_bkntno AS bkntno, fd_grclist_gameuserid AS account, fd_grclist_gamecardnum AS quantity, fd_grclist_gamename AS gamename, fd_grclist_state, fd_grclist_paymoney AS totalPrice, fd_grclist_datetime AS completeTime FROM tb_gamerechargelist WHERE fd_grclist_authorid = $authorid AND fd_grclist_state = 1 LIMIT $start, $count";
			$db->query($query);
			
			$retcode = "0";
		
			if($db->nf() > 0)
			{
				$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
				$arr_message = array ("result" => "success", "message" => "获取成功");
			}
			else
			{
				$arr_message = array ("result" => "success", "message" => "没有数据");
			}
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" => "输入信息不完整");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}