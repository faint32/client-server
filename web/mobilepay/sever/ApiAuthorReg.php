<?php
class ApiAuthorReg extends TfbxmlResponse {
	//获取手机短信接口		    
	public function getSmsCode() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$phone = trim($arr_body['smsmobile']);
		$query = "select 1 d from tb_author  where fd_author_username = '$phone'";
		$db->query($query);
		if ($db->nf()) {
			$arr_message = array (
				"result" => "failure",
				"message" => "该手机号码已注册,直接登录即可。"
			);
            $retcode = "200";  //反馈状态 0 成功 200 自定义错误
			$havemobile = 1;

		} else {
			$havemobile = 0;
		}

		if ($havemobile != 1) {
		$code = getCode(6, 60, 20);
		
				$data = array(
					'FUNC' => 'SEND_SMS', 
					'phone' => $phone, 
					'message' => "您的验证码是：" . $code . "，欢迎注册通付宝用户。如需帮助请联系客服。"
				);
				
				// 调用接口把短信发给用户
				global $weburl;
				$url = $weburl . "sever/AsyncInterface.php";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				
				curl_exec($ch);
				curl_close($ch);
			
			$arr_message = array (
					"result" => "success",
					"message" => "短信发送成功"
				);
				$retcode = "0";
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['smsmobile'] = $phone;
		$arr_msg['msgbody']['smscode'] = $code;
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
	// 注册接口 

	function authorReg() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;

		$aumobile = trim($arr_body['aumobile']);
		$aupassword = trim(md5($arr_body['aupassword']));
		$autruename = u2g(trim($arr_body['autruename']));
		$auidcard = trim($arr_body['auidcard']);
		$auemail = trim($arr_body['auemail']);

		$query = "select 1 from tb_author  where fd_author_username = '$aumobile'";
		$db->query($query);
		if ($db->nf()) {
			$arr_message = array (
				"result" => "failure",
				"message" => "该手机号码已注册"
			);
            $retcode = "200";  //反馈状态 0 成功 200 自定义错误
			$havemobile = 1;

		} else {
			$havemobile = 0;
		}
		if ($havemobile != 1) {
			$query = "insert into tb_author (fd_author_password ,fd_author_mobile ,fd_author_truename,
							          fd_author_idcard,fd_author_email ,fd_author_username,fd_author_regtime,fd_author_datetime 
							          )values( '$aupassword' ,'$aumobile','$autruename','$auidcard','$auemail','$aumobile',now(),now())";
			$db->query($query);
            $listid = $db->insert_id(); 
            $sdcrid=3;
            $auindustryid =4;
            $slotpayfsetid = 8;
            $slotscdmsetid = 14;
            $bkcardpayfsetid = 25;
            $bkcardscdmsetid = 9;
            $memid           = 3554;
            $shopid          = 102;
            $authortypeid    = 5;
            		$query = "update tb_author set 
					   fd_author_isstop='0'," .
					   	"fd_author_state = '9'," .
					   	"fd_author_sdcrid = '$sdcrid'," .
					   "fd_author_auindustryid = '$auindustryid',
		               fd_author_slotpayfsetid = '$slotpayfsetid',
		               fd_author_slotscdmsetid = '$slotscdmsetid',
					   fd_author_bkcardpayfsetid='$bkcardpayfsetid',
					   fd_author_bkcardscdmsetid='$bkcardscdmsetid' ,
					   fd_author_couponstate      = 0   ," .
					  "fd_author_memid          = '$memid'," .
					  "fd_author_shopid         = '$shopid'," .
					  "fd_author_authortypeid   = '$authortypeid' 
					   						
					   where fd_author_id='$listid'";
			$db->query($query);
			$arr_message = array (
				"result" => "success",
				"message" => "恭喜您,注册成功!"
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

}
?>