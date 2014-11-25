<?php
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require_once ("./lib/upmp_service.php");

   $db = new DB_test;
   // $reqcontext = file_get_contents("php://input");
    $reqcontext = file_get_contents('php://input', 'r'); 
		//$reqcontext = ($raw_post_data == $HTTP_RAW_POST_DATA) ? 1 : 0; 
		echo $reqcontext; 

   
    echo var_dump($reqcontext);
    exit;
    $authorid=$authorid+0;
		$file="./".date('Y-m-d')."-log".".txt";
		$filehandle=fopen($file, "a"); 
		fwrite($filehandle,"\r\n======响应内容：\r\n".$reqcontext."\r\n\r\n".$returnval."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n"); 
		fclose($filehandle);
if (UpmpService :: verifySignature($reqcontext)) { // 服务器签名验证成功
	//请在这里加上商户的业务逻辑程序代码
	//获取通知返回参数，可参考接口文档中通知参数列表(以下仅供参考)
 
		
	$transStatus = $reqcontext['transStatus']; // 交易状态
	if ("" != $transStatus && "00" == $transStatus) {
		$bkordernumber = $reqcontext['orderNumber'];
		//BankPayInfo::bankpaystatus($bkordernumber,$bkordernumber,'coupon');
		$query = "update tb_couponsale set fd_couponsale_payrq = '$transtatus' where fd_couponsale_bkordernumber = '$bkordernumber' ";
		$db->query($query);

		$query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transtatus',fd_agpm_datetime = now()  where fd_agpm_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update tb_creditcardglist set fd_ccglist_payrq ='$transtatus',fd_ccglist_paydate = '$nowdate' where fd_ccglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='$transtatus' ,fd_tfmglist_paydate ='$nowdate' 
				          where fd_tfmglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_repaymoneyglist set fd_repmglist_payrq ='$transtatus' ,fd_repmglist_paydate ='$nowdate' 
				          where fd_repmglist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update  tb_rechargeglist set fd_rechargelist_payrq ='$transtatus' where fd_rechargelist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		$query = "update tb_orderpayglist set fd_oplist_payrq ='$transtatus',fd_oplist_paydate = '$nowdate' where fd_oplist_bkordernumber = '$bkordernumber'";
		$db->query($query);

		// 交易处理成功
	} else {
	}
	echo "success";
} else { // 服务器签名验证失败
	
	 // $authorid=$authorid+0;
		$file="./".date('Y-m-d')."-error-log".".txt";
		$filehandle=fopen($file, "a"); 
		fwrite($filehandle,"\r\n======响应内容：\r\n".var_dump($reqcontext)."\r\n\r\n".$returnval."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n"); 
		fclose($filehandle);
	echo "fail";
}
?>