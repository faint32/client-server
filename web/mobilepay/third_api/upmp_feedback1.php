<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type:text/html;charset=utf-8');
require ("../include/common.inc.php");
require_once ("./lib/upmp_service.php");
require ("./ofpay.class.php");

   spl_autoload_register(array (
       'Loader',
       'loadClass'
   ));

    $db = new DB_test;
    $TfbPayFeedback = new TfbPayFeedback();
    $raw_post_data = file_get_contents('php://input', 'r');
    $HTTP_POST = ($raw_post_data == $_REQUEST) ? $raw_post_data : $_REQUEST;

$HTTP_POST = 'a:17:{s:9:"orderTime";s:14:"20140708135035";s:10:"settleDate";s:4:"0708";s:11:"orderNumber";s:23:"tfb20140708135035357734";s:12:"exchangeRate";s:1:"0";s:9:"signature";s:32:"0a3704a77a103db3930139796ff7bff9";s:14:"settleCurrency";s:3:"156";s:10:"signMethod";s:3:"MD5";s:9:"transType";s:2:"01";s:8:"respCode";s:2:"00";s:7:"charset";s:5:"UTF-8";s:11:"sysReserved";s:58:"{traceTime=0708135035&acqCode=00215800&traceNumber=046583}";s:7:"version";s:5:"1.0.0";s:12:"settleAmount";s:4:"1200";s:11:"transStatus";s:2:"00";s:11:"reqReserved";s:12:"透传信息";s:5:"merId";s:15:"880000000000497";s:2:"qn";s:21:"201407081350350465837";}';
$HTTP_POST = unserialize($HTTP_POST);


	$bkordernumber = $HTTP_POST['orderNumber'];
	$transStatus = $HTTP_POST['transStatus']; // 交易状态



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
        $file="./".date('Y-m-d')."-反馈-log".".txt";
        $filehandle=fopen($file, "a");
        fwrite($filehandle,"\r\n======连接内容：\r\n".$arr_security['paytype']."--".$bkordernumber."\r\n\r\n".$transStatus."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n");
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
