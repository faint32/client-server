<?php
class APImssale {
	public function getorderinfo($querywhere, $start, $display) {
		$dbmsale = new DB_mssale();
		if ($start < 0) $start = 0;
		$start   = $start +0;
		$display = $display +0;

		$query   = "select 1  from web_order where 1=1    $querywhere ";
		$dbmsale->query($query);
		$msgallcount = $dbmsale->nf();

		$wfstate = '未付款';
		$wfstate = u2g($wfstate);
		$yfstate = '已付款';
		$yfstate = u2g($yfstate);
		$qfstate = '其他';
		$qfstate = u2g($qfstate);
		$query = "select case 
	        when fd_order_state ='6' then '" . $wfstate . "'
	        when fd_order_state ='7' then '" . $yfstate . "'
	        else '" . $qfstate . "' END  orderstate,fd_order_id as orderid,fd_order_no as orderno ,
		                 fd_order_date as ordertime  ,fd_order_allmoney as ordermoney,fd_order_alldunshu as orderpronum,
		                 fd_order_type as orderpaytype,fd_order_shman as shman,fd_order_comnpany as shcmpyname,
		                 fd_order_receiveadderss as shaddress , '' as fhstorage,'' as fhwltype,
		                 fd_order_memo as ordermemo,'' as allpromoney,'' as fhwlmoney
		                 from web_order " .
		                "where 1=1  $querywhere limit $start ,$display";
		$dbmsale->query($query);
       //echo $query;
		$msgdiscount = $dbmsale->nf ();
		$arr_value = $dbmsale->getData('','msorder');
		//$arr_orderinfo = $dbmsale->get_all($query);
		foreach ($arr_value as $key => $value) {
			foreach ($value as $k => $v) {
				$orderid= $v['orderid'];
				$query = "select fd_orderdetail_quantity as pronum,fd_orderdetail_productname as proname,
					          fd_orderdetail_price as proprice 
					          ,(fd_orderdetail_price*fd_orderdetail_quantity) as promoney from web_orderdetail 
					          where fd_orderdetail_orderid ='$orderid'";
				$dbmsale->query($query);
				//echo $query;
				if ($dbmsale->nf()) {
					$arr_value[$key][$k]['msproinfo'] = $dbmsale->getFiledData('msgchild');
				}
			}
		}
		if (!$arr_value) {
			$arr_message = array (
				"result" => "failure",
				"message" => "很抱歉，没有找到相关的订单信息!"
			);
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功"
			);
		}
		//echo var_dump($arr_value);
		$arr_message = auto_charset($arr_message, 'utf-8', 'gbk');
		$arr_msg['msgbody'] = $arr_value;
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['msgallcount'] = $msgallcount;
		$arr_msg['msgbody']['msgdiscount'] = $msgdiscount + $start;
		return $arr_msg['msgbody'];
	}
}
?>