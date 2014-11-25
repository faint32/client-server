<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

require_once ("../class/BankPayInfoV2.php");

// 水电煤气接口
class ApiUtility extends TfbxmlResponse
{
	public function getProductList()
	{																		// This function don't need parameter from client request
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
$logger = Logger::getLogger('utility');
$logger->debug("start getProductList");
		$db = new DB_test();
		$query = "SELECT * FROM tb_utility_product WHERE fd_state = 1 ORDER BY fd_province_name, fd_city_name, fd_paytype_name, fd_company_id";
$logger->debug("process getProductList : sql : (" . $query . ")");
		$data = array();
		$data = auto_charset($db->get_all($query), 'gbk', 'utf-8');		// convert gbk in database into utf8 which will be used in response xml
$logger->debug("process getProductList : count from sql : (" . count($data) . ")");
		$startNewCityData = false;
		$allProvinceProduct = array();
		$provinceDetail = "";
		
		if($data != 0)														// Table 'tb_utility_product' has data in it
		{
			for($i = 0; $i < count($data); $i++)
			{
				if($i > 0 && ($data[$i]["fd_province_name"] != $data[$i-1]["fd_province_name"] || $data[$i]["fd_city_name"] != $data[$i-1]["fd_city_name"]))
				{
					$startNewCityData = true;
				}
				else
				{
					$startNewCityData = false;
				}
				
				if($startNewCityData && $provinceDetail != "")
				{
					$allProvinceProduct[] = array("provinceName" => $data[$i-1]["fd_province_name"], "city" => $provinceDetail);
					$provinceDetail = "";
				}
				
				if($startNewCityData || $i == 0)
				{
					$provinceDetail .= $data[$i]["fd_city_name"];
					$provinceDetail .= " #@#patypeName:" . $data[$i]["fd_paytype_name"];
					$provinceDetail .= " @@companyid:" . $data[$i]["fd_company_id"] . ",companyname:" . $data[$i]["fd_company_name"];
				}
				else
				{
					if($data[$i]["fd_paytype_name"] != $data[$i-1]["fd_paytype_name"])
					{
						$provinceDetail .= " #@#patypeName:" . $data[$i]["fd_paytype_name"];
					}
					$provinceDetail .= " @@companyid:" . $data[$i]["fd_company_id"] . ",companyname:" . $data[$i]["fd_company_name"];
				}
			}
		}
		if($provinceDetail != "")
		{
			$allProvinceProduct[] = array("provinceName" => $data[count($data) - 1]["fd_province_name"], "city" => $provinceDetail);
			$retcode = "0";
			$arr_message = array ("result" => "success", "message" => "获取成功！");
		}
$logger->debug("complete getProductList : allProvinceProduct : (" . print_r($allProvinceProduct, true) . ")");
		$arr_msg['msgbody'] = $allProvinceProduct;
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	// When user chose the company for which he wanted to pay, and input his account No., there will be a order created in the server.
	public function createOrder()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
$logger = Logger::getLogger('utility');
$logger->debug("start createOrder");
		$authorid = trim($this->arr_channelinfo['authorid']);
		
		$arr_body = $this->arr_body;
		$account = trim($arr_body['account']);
		$proId = trim($arr_body['proId']);
$logger->info("process createOrder : authorid : (" . $authorid . "), account : (" . $account . "), proId : (" . $proId . ")");
		
		if($account == "" || $proId == "" || $authorid == "")
		{
			$arr_message = array ("result" => "fail", "message" => "输入信息不完整");
		}
		else
		{
			// interface info
			$usernumber = "7000035";
			$sign = "ae4544eaa21f4575997c12332581e662";
			$timestamp = date("Y-m-d H:i:s");
			$timeout = 30;
			$url = "http://lifeapi.salerwise.com/IWEC/BillsQuery";
			$yearmonth = date("Ym");
			$recordkey = $usernumber . $proId . $account . $yearmonth . $timestamp . $sign;
			$recordkey = md5($recordkey);
			$recordkey = substr($recordkey , 0, 16);
			$recordkey = strtoupper($recordkey);
			
			$data = array(
				'usernumber' => $usernumber, 
				'proId' => $proId, 
				'account' => $account, 
				'timestamp' => $timestamp, 
				'recordkey' => $recordkey,
				'yearmonth' => $yearmonth, 
			);
$logger->debug("process createOrder : visit url(" . $url . ") using data (" . print_r($data, true) . ")");
			$ch = curl_init ();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$file_contents = curl_exec($ch);
			curl_close($ch);
$logger->debug("process createOrder : get data (" . $file_contents . ")");
			if($file_contents != "")
			{
				$username = "";
				$factBill = 0;
				$totalBill = 0;
				$result = "";
				
				$file_contents = str_replace("GB2312", "UTF-8", $file_contents);
				$xml = simplexml_load_string($file_contents);
				if($xml)
				{
$logger->debug("process createOrder : create xml successful");
					if(isset($xml->bills))
					{
						$factBill = (double)($xml->bills);
					}
					if(isset($xml->username))
					{
						$username = "" . $xml->username . "";
					}
					if(isset($xml->result))
					{
						$result = "" . $xml->result . "";
					}
				}
				else
				{
$logger->error("process createOrder : can not create xml from (" . $file_contents . ")");
				}
$logger->debug("process createOrder : get data from xml");
				if($factBill != 0)
				{
					$cost = $factBill * 0.01;
					$cost = $cost > 1? $cost : 1;
					$payfee = $factBill - $cost - $factBill * 0.008;
					
					$query = "INSERT INTO tb_utility_order (`fd_author_id`, `fd_account_id`, `fd_pro_id`, `fd_username`, `fd_fact_bill`, `fd_total_bill`, `fd_utility_payfee`) 
						VALUES ( $authorid , $account , $proId, '" . u2g($username) . "', $factBill, $totalBill, $payfee);";
					$db = new DB_test();
					$db->query($query);
					$orderid = $db->insert_id();
$logger->debug("process createOrder : executing sql (" . $query . ") to create new orderId (" . $orderid . ")");
					$arr_message = array ("result" => "success", "message" => "订单建立成功");
					$retcode = "0";
					$arr_msg['msgbody']['orderid'] = $orderid;
					$arr_msg['msgbody']['username'] = (string)$username;
					$arr_msg['msgbody']['factBills'] = (string)($factBill / 100);
					$arr_msg['msgbody']['totalBill'] = (string)($factBill / 100);
				}
				else
				{
					if($result != "hderr")
					{
						$message = $username;
					}
					else
					{
						if($xml && isset($xml->msg))
						{
							$message = "" . $xml->msg . "";
						}
					}
$logger->error("process createOrder : error info found (" . $message . ")");
					$arr_message = array ("result" => "success", "message" => "查询失败：" . $message);
				}
			}
			else
			{
$logger->error("process createOrder : can not get data from salerwise");
			}
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	// When user confirmed his account, and would like to jump to the entrance of unionpay, his order's status will be changed to "submited"
	public function submitOrder()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
$logger = Logger::getLogger('utility');
$logger->debug("start submitOrder");
		
		$authorid = trim($this->arr_channelinfo['authorid']);
		
		$arr_body = $this->arr_body;
		$orderid = trim($arr_body['orderid']);
		$paycardid = trim($arr_body['paycardid']);
		$rechabkcardno = trim($arr_body['rechabkcardno']);
$logger->info("process submitOrder : authorid : (" . $authorid . "), orderid : (" . $orderid . "), paycardid : (" . $paycardid . "), rechabkcardno : (" . $rechabkcardno . ")");
		if($orderid != "" && $rechabkcardno != "" && $authorid != "")
		{
			$query = "SELECT fd_product_price, fd_total_price FROM tb_utility_order 
					WHERE fd_author_id = $authorid AND fd_order_id = $orderid";
			$db = new DB_test();
			$result = $db->get_all($query);
			if($result != 0 && count($result) > 0)
			{
$logger->debug("process submitOrder : executing sql (" . $query . ") to get count (" . count($result) . ")");
$logger->info("process submitOrder : the bill is " . ($result[0]["fd_total_bill"] / 100));
				$arr_bkinfo = BankPayInfo :: bankpayorder($authorid, $paycardid, ($result[0]["fd_total_bill"] / 100), $rechabkcardno);
$logger->debug("process submitOrder : get data from bankpay" . print_r($arr_bkinfo, true));
				$bkntno = trim($arr_bkinfo['bkntno']);				
				$bkorderNumber = trim($arr_bkinfo['bkorderNumber']);
				
				$arr_paycard = GetPayCalcuInfo :: readpaycardid($paycardid, $authorid);
$logger->debug("process submitOrder : get data from paycalcul" . print_r($arr_paycard, true));
				$paycardid = $arr_paycard['paycardid'];
				$cusid = trim($arr_paycard['cusid']);
				$paycardkey = trim($arr_paycard['paycardkey']);
			
				$query = "UPDATE tb_utility_order SET fd_order_state = 2, fd_bkordernumber = '$bkorderNumber', fd_union_pay_number = $bkntno, fd_submit_time = NOW(), fd_complete_time = NOW(), fd_utility_cusid = $cusid WHERE fd_author_id = $authorid AND fd_order_id = $orderid";
$logger->debug("process submitOrder : executing sql (" . $query . ")");
				$db->query($query);
				$arr_msg['msgbody']['bkntno'] = $bkntno;
				$retcode = "0";
				$arr_message = array ("result" => "success", "message" => "提交成功");
			}
			else
			{
$logger->error("process submitOrder : can not get any date from executing sql (" . $query . ")");
				$arr_message = array ("result" => "fail", "message" => "订单号异常！");
			}
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" => "数据不完整");
		}
$logger->info("complete submitOrder : (" . print_r($arr_msg['msgbody'], true) . ")");
		$arr_msg['msgbody']['result'] = $arr_message["result"];
		$arr_msg['msgbody']['message'] = $arr_message["message"];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// After user paid the bill, the client will send the result to this server, and the order related will be set "paid"
	public function completeOrder()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
$logger = Logger::getLogger('utility');
$logger->debug("start submitOrder");
		$authorid = trim($this->arr_channelinfo['authorid']);
		
		$arr_body = $this->arr_body;
		$orderid = trim($arr_body['orderid']);
		$bkntno = trim($arr_body['bkntno']);
		
		$hasReceiveMoney = false;
		
		$db = new DB_test();
		
		$query = "SELECT *, DATE_FORMAT(fd_submit_time,'%Y%m%d') as orderTime FROM tb_utility_order WHERE fd_author_id = $authorid AND fd_order_id = $orderid";
		$orderInDB = $db->get_all($query);
		if($orderInDB != 0 && count($orderInDB) > 0)
		{
			$orderNumber = $orderInDB[0]["fd_bkordernumber"];
			$orderTime = $orderInDB[0]["orderTime"];
			
			$arr_returninfo = BankPayInfoV2 :: bankorderquery($authorid, '' , $orderNumber, $orderTime);
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
		
		if($hasReceiveMoney)
		{
			// interface info
			$usernumber = "7000035";
			$sign = "ae4544eaa21f4575997c12332581e662";
			$inordernumber = "IWEC" . $usernumber . date("YmdHis") . "6543";
			$outordernumber = $orderid;
			$proId = $orderInDB[0]["fd_pro_id"];
			$account = $orderInDB[0]["fd_account_id"];
			$paymoney = $orderInDB[0]["fd_total_bill"];
			$starttime = date("Y-m-d H:i:s");
			$timeout = 30;
			$url = "http://lifeapi.salerwise.com/IWEC/IRechargeList_WEC";
			$yearmonth = "";
			$recordkey = $usernumber . $inordernumber . $outordernumber . $proId . $account . $paymoney . $yearmonth . $starttime .  $timeout . $sign;
			$recordkey = md5($recordkey);
			$recordkey = substr($recordkey , 0, 16);
			$recordkey = strtoupper($recordkey);
				
			$data = array(
					'usernumber' => $usernumber, 
					'inordernumber' => $inordernumber, 
					'outordernumber' => $outordernumber, 
					'proId' => $proId, 
					'account' => $account, 
					'paymoney' => $paymoney, 
					'starttime' => $starttime, 
					'timeout' => $timeout, 
					'recordkey' => $recordkey,
					'yearmonth' => $yearmonth, 
			);
			$ch = curl_init ();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$file_contents = curl_exec($ch);
			curl_close ( $ch );
			$result = false;
			if($file_contents != "")
			{
				$file_contents = str_replace("GB2312", "UTF-8", $file_contents);
				$xml = simplexml_load_string($file_contents);
				foreach ($xml->children() as $child)
				{
					if($child->getName() == "result" && (string)$child == "success")
					{
						$result = true;
					}
				}
			}
			
			$this->customerProfit($orderInDB[0]);
			
			if($result)
			{
				$query = "UPDATE tb_utility_order SET fd_order_state = 3, fd_complete_time = NOW() WHERE fd_author_id = $authorid AND fd_order_id = $orderid";
				$db->query($query);
				$retcode = "0";
				$arr_message = array ("result" => "success", "message" => "提交成功");
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
		if(is_array($orderInDB) && count($orderInDB) == 1 && $orderInDB[0]['fd_utility_cusid'] != null)
		{
			$profitArray = array();
			$profitArray['fd_frlist_authorid'] = $orderInDB[0]['fd_author_id'];
			$profitArray['fd_frlist_cusid'] = $orderInDB[0]['fd_utility_cusid'];
			$profitArray['fd_frlist_paycardid'] = $orderInDB[0]['fd_utility_paycardid'];
			$profitArray['fd_frlist_paydate'] = date("Ymd");
			$profitArray['fd_frlist_paymoney'] = $orderInDB[0]['fd_total_price'];
			$profitArray['fd_frlist_payfee'] = $orderInDB[0]['fd_utility_payfee'];
			$profitArray['fd_frlist_cusfee'] = $orderInDB[0]['fd_utility_payfee'] * 0.125;
			$profitArray['fd_frlist_bkordernumber'] = $orderInDB[0]['fd_bkordernumber'];
			$profitArray['fd_frlist_payrq'] = '00';
			$profitArray['fd_frlist_paytype'] = 'family';
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
			
			$query = "SELECT A.fd_union_pay_number AS bkntno, A.fd_pro_id AS proId, 
					(A.fd_fact_bill / 100) AS factNumber, (A.fd_total_bill / 100) AS payNumber, IF(A.fd_order_state = 3, 0, 1) AS status, B.fd_company_name AS company, 
					A.fd_complete_time AS completeTime FROM tb_utility_order AS A JOIN tb_utility_product AS B ON A.fd_pro_id = B.fd_company_id 	
					WHERE A.fd_author_id = $authorid AND (A.fd_order_state = 2 || A.fd_order_state = 3) LIMIT $start, $count";
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
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}