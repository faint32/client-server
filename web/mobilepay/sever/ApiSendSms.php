<?php
class ApiSendSms extends TfbxmlResponse {
	//获取手机短信接口		    
	public function getSmsCode() {

		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$db = new DB_test;
		$phone = $arr_body['smsmobile'];
		$id = "0";
		$uid = "nicegan"; //用户账户
		$pwd = "chengan"; //用户密码
		$mobno = $phone; //发送的手机号码,多个请以英文逗号隔开如"138000138000,138111139111"
		$code = getCode(6, 60, 20);
		$target = "http://www.dxton.com/webservice/sms.asmx/Submit";
		$post_data = "account=".$uid."&password=".$pwd."&mobile=".$mobno."&content=".rawurlencode("您的验证码是：".$code.",【通付宝】。如需帮助请联系客服。");
		$getvalue = $this->Post($post_data, $target);	
		//$getvalue = Get($url);
		if (!is_numeric($getvalue)) {
			$arr_message = array (
				"result" => "failure",
				"message" => "短信发送失败!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误

		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "短信发送成功"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误

		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	 function Post($data, $target) {
  
	$url_info = parse_url($target);
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader .= "Host:" . $url_info['host'] . "\r\n";
    $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
    $httpheader .= "Connection:close\r\n\r\n";
    //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
    $httpheader .= $data;

    $fd = fsockopen($url_info['host'], 80);
    fwrite($fd, $httpheader);
    $gets = "";
    while(!feof($fd)) {
        $gets .= fread($fd, 128);
    }
    fclose($fd);
    return $gets;
	
}
	

}
?>