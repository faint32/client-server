<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require_once ("./lib/upmp_service.php");
require ("./ofpay.class.php");
require_once ("../class/mobilerecharge.php");

   spl_autoload_register(array (
    'Loader',
    'loadClass'
   ));

   $db = new DB_test;
    $TfbPayFeedback = new TfbPayFeedback();
    $raw_post_data = file_get_contents('php://input', 'r'); 
    $HTTP_POST = ($raw_post_data == $_REQUEST) ? $raw_post_data : $_REQUEST;



	$bkordernumber = $HTTP_POST['orderNumber']; 
	$transStatus = $HTTP_POST['transStatus']; // 交易状态

    $file="./".date('Y-m-d')."-feedback-log".".txt";
    $filehandle=fopen($file, "a");
    fwrite($filehandle,"\r\n======响应内容：\r\n".$bkordernumber."\r\n\r\n".serialize($HTTP_POST)."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n");
    fclose($filehandle);

//
//  echo $bkordernumber;
//exit;

	$arr_security =  $TfbPayFeedback->bkordernumbertokey($bkordernumber,$transStatus);
    $security_key = $arr_security['securitykey'];
    $cusfee   = $arr_security['cusfee']; //代理商分润


if (UpmpService :: verifySignature($HTTP_POST)) { // 服务器签名验证成功
	//请在这里加上商户的业务逻辑程序代码
	//获取通知返回参数，可参考接口文档中通知参数列表(以下仅供参考)


	if ($transStatus != ""   && $transStatus == "00" ) {
        $file="./".date('Y-m-d')."-feedback-log".".txt";
        $filehandle=fopen($file, "a");
        fwrite($filehandle,"\r\n======响应内容：\r\n".$arr_security['paytype']."--".$bkordernumber."\r\n\r\n".$transStatus."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n");
        fclose($filehandle);

        $return = $TfbPayFeedback->changePayTranstatus($bkordernumber,$transStatus,$arr_security['paytype'],$cusfee);
		// 交易处理成功
	} else {
        $return = $TfbPayFeedback->changePayTranstatus($bkordernumber,$transStatus,$arr_security['paytype'],$cusfee);
	}
	echo $return;
} else { // 服务器签名验证失败
   	$file="./".date('Y-m-d')."-log".".txt";
	$filehandle=fopen($file, "a");
	fwrite($filehandle,"\r\n======响应内容：fail.\r\n".var_dump($HTTP_POST).$bkordernumber."\r\n\r\n".$transStatus."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n");
	fclose($filehandle);
	echo "fail";
}
//自动加载类文件
class Loader {
    public static function loadClass($class) {
        $file = '../class/'.$class . '.class.php';
        if (is_file($file)) {
            require_once ($file);
        }
    }
}
?>