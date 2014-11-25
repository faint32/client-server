<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require ("../include/common.q.inc.php");
require ("../include/json.php");
error_reporting(0);
$db  = new DB_test;	
/**
 * @author blog.anchen8.net
 * @copyright 2013
 */
  			
  $query     = "select fd_order_state as orderstate,fd_order_id as orderid,fd_orderno as orderno ,
	                 fd_order_date as ordertime  ,fd_order_allmoney as ordermoney,fd_order_alldunshu as orderpronum,
	                 fd_order_type as orderpaytype,fd_order_shman as shman,fd_order_comnpany as shcmpyname,
	                 fd_order_receiveadderss as shaddress , '配送仓' as fhstorage,'配送方式' as fhwltype,
	                 fd_order_demo as ordermemo,'商品金额' as allpromoney,'物流费用' as fhwlmoney,
	                 from web_order where  1 $querywhere limit $start,$display";
	$dbmsale->query($query);
	$arr_value = $dbmsale->getfielData(''); 
	foreach($arr_value as $key=>$value )
	{
	$query = "select fd_orderdetail_quantity as pronum,fd_orderdetail_productname as proname,fd_orderdetail_price as proprice 
	          (fd_orderdetail_price*fd_orderdetail_quantity) as promoney from web_orderdetail where fd_orderdetail_orderid ='$orderid'";
	$dbmsale->query($query);
	$arr_msg['\''.$key.'\''] = auto_charset($dbmsale->getData('','msgbody'),'gbk','utf-8');
	}
    if(!$arr_msg)
	{
	    	$arr_message = array("result"=>"failure","message"=>"银行列表为空!");
	}else
	{
			$arr_message = array("result"=>"success","message"=>"读取成功!");
	}
    
?>// TODO: 