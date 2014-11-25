<?php
class ApiAuthorInfo extends TfbxmlResponse {
	//修改密码	
	
	
	
		    
	public function authorPwdModify() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid   = trim($arr_channelinfo['authorid']);
		$auoldpwd   = md5(trim($arr_body['auoldpwd']));
		$aunewpwd   = md5(trim($arr_body['aunewpwd']));
		$aurenewpwd = md5(trim($arr_body['aurenewpwd']));
		$aumoditype = (trim($arr_body['aumoditype']));

		$arr_message = array (
			"error_id" => "0",
			"result" => "failure",
			"message" => ""
		);
		if ($aumoditype == 1) {

			$query = "select 1 from tb_author  where fd_author_id = '$authorid' and fd_author_password = '$auoldpwd'";
			$db->query($query);
			if (!$db->nf()) {
				$arr_message = array (
					"error_id" => "2",
					"result" => "failure",
					"message" => "修改失败，当前密码输入错误!"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			}
			$query = "select 1 from tb_author  where fd_author_id = '$authorid'";
			$db->query($query);
			if (!$db->nf()) {
				$arr_message = array (
					"error_id" => "1",
					"result" => "failure",
					"message" => "用户信息已失效，请重新登录"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			}

			if ($arr_message['error_id'] == '0') {
				$query = "update tb_author set fd_author_password  = '$aunewpwd' where fd_author_id = '$authorid'";
				$db->query($query);

				$arr_message = array (
					"error_id" => "0",
					"result" => "success",
					"message" => "恭喜您,密码修改成功!"
				);
				$retcode = "0";  //反馈状态 0 成功 200 自定义错误
			}
		} else
			if ($aumoditype == 2) {
				
				$query = "select 1 from  tb_author where (fd_author_paypassword is null or fd_author_paypassword = '0') and fd_author_id = '$authorid'";
				if(!$db->execute($query))
				{
				$query = "select 1 from tb_author  where fd_author_id = '$authorid' and fd_author_paypassword = '$auoldpwd'";
				$db->query($query);
				if (!$db->nf()) {
				$arr_message = array (
					"error_id" => "2",
					"result" => "failure",
					"message" => "修改失败，当前支付密码输入错误!"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
				}
				}
			
				$query = "select 1 from tb_author  where fd_author_id = '$authorid'";
				$db->query($query);
				if (!$db->nf()) {
					$arr_message = array (
						"error_id" => "1",
						"result" => "failure",
						"message" => "用户信息已失效，请重新登录"
					);
					$retcode = "200";  //反馈状态 0 成功 200 自定义错误
				}

				if ($arr_message['error_id'] == '0') {
					$query = "update tb_author set fd_author_paypassword  = '$aunewpwd' where fd_author_id = '$authorid'";
					$db->query($query);

					$arr_message = array (
						"error_id" => "0",
						"result" => "success",
						"message" => "恭喜您,支付密码修改成功!"
					);
					$retcode = "0";  //反馈状态 0 成功 200 自定义错误
				}

			}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

	//readAuthorInfo  读取用户信息
	public function readAuthorInfo() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;

		$authorid = trim($arr_channelinfo['authorid']);

		$arr_message = array (
			"error_id" => "0",
			"result" => "success",
			"message" => "读取成功"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$query = "select * from tb_author  where fd_author_id = '$authorid'";
		$db->query($query);

		if ($db->nf()) {
			$db->next_record();

			$arr_msg['msgbody']['autruename'] = g2u($db->f(fd_author_truename));
			$arr_msg['msgbody']['autrueidcard'] = $db->f(fd_author_idcard);
			$arr_msg['msgbody']['auemail'] = $db->f(fd_author_email);
			$arr_msg['msgbody']['aumobile'] = $db->f(fd_author_mobile);
            $arr_msg['msgbody']['isagentid'] = $db->f(fd_author_cusid);
            $isagentid = $arr_msg['msgbody']['isagentid'];
            if($arr_msg['msgbody']['isagentid']>0)
            {
                $query = "select * from tb_customer where fd_cus_id = '$isagentid'";
                if ($db->execute($query)) {
                    $arr_merinfo = $db->get_one($query);
                }
                $arr_msg['msgbody']['agentcompany'] = $arr_merinfo['fd_cus_allname'];
                $arr_msg['msgbody']['agentarea']    =  $arr_merinfo['fd_cus_address'];
                $arr_msg['msgbody']['agentaddress'] =  $arr_merinfo['fd_cus_shaddress'];
                $arr_msg['msgbody']['agentmanphone'] =  $arr_merinfo['fd_cus_manphone'];
                $arr_msg['msgbody']['agentfax'] =  $arr_merinfo['fd_cus_fax'];
                $arr_msg['msgbody']['agenthttime'] =  $arr_merinfo['fd_cus_htbegintime']." - ". $arr_merinfo['fd_cus_htendtime'];
                $arr_msg['msgbody']['agentbzmoney'] =  $arr_merinfo['fd_cus_bzmoney'];

            }

		} else {
			$arr_message = array (
				"error_id" => "1",
				"result" => "failure",
				"message" => "用户信息已失效，请重新登录"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		if ($arr_message['error_id'] == '0') {
			$arr_picinfo1 = readAuthorupicinfo("1", $authorid);
			$arr_picinfo2 = readAuthorupicinfo("2", $authorid);
			$arr_picinfo[] = array_merge($arr_picinfo1, $arr_picinfo2);
			$arr_msg['msgbody'][] = $arr_picinfo1['msgchild'];
			$arr_msg['msgbody'][] = $arr_picinfo2['msgchild'];
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

	// uploadAuthorPic 用户身份证上传功能
	public function uploadAuthorPic() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$picid = trim($arr_body['picid']);
		$picpath = u2g(trim($arr_body['picpath']));
		$uploadmethod = u2g(trim($arr_body['uploadmethod']));
		$uploadpictype = u2g(trim($arr_body['uploadpictype']));
		$uploadmark = u2g(trim($arr_body['uploadmark']));
		$arr_upload = uploadaction($picid, $picpath, $uploadmethod, $uploadpictype, $uploadmark, $authorid);
		$arr_msg['msgbody'] = array (
			"picid" => $picid,
			"uploadmethod" => $uploadmethod,
			"message" => "恭喜您,上传成功!",
			"result" => "success"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	//modifyAuthorInfo  修改用户信息
	public function modifyAuthorInfo() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
        $agentid = trim($arr_channelinfo['agentid']);
		$autruename = u2g(trim($arr_body['autruename']));
		$auidcard = trim($arr_body['auidcard']);
		$auemail = trim($arr_body['auemail']);

		$query = "update tb_author set fd_author_truename = '$autruename',fd_author_idcard = '$auidcard',
			          fd_author_email = '$auemail' where fd_author_id  = '$authorid' ";

		$db->query($query);


        $arr_merinfo['fd_cus_allname'] = $arr_body['agentcompany'];
        $arr_merinfo['fd_cus_address'] = $arr_body['agentarea']    ;
        $arr_merinfo['fd_cus_shaddress'] = $arr_body['agentaddress'];
        $arr_merinfo['fd_cus_manphone'] = $arr_body['agentmanphone'];
        $arr_merinfo['fd_cus_fax']       = $arr_body['agentfax']  ;
        $wherequery = "fd_cus_id  = '$agentid'";
        $db->update("tb_customer", $arr_merinfo, $wherequery);


		$arr_msg['msgbody'] = array (
			"error_id" => "0",
			"result" => "success",
			"message" => "恭喜您,用户修改成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}

	//checkAuthorLogin  登录管理
	public function checkAuthorLogin() {
		global $au_token;
		$now = strtotime(date("Y-m-d H:i:s"));
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$aumobile = trim($arr_body['aumobile']);
		$aupwd = md5(trim($arr_body['aupwd']));
		$auloginmethod = (trim($arr_body['auloginmethod']));
		$query = "select fd_author_id,fd_author_paypassword from tb_author where fd_author_username  = '$aumobile'";
		$db->query($query);
		if (!$db->nf()) {
			
			$arr_message = array (
				
				"result" => "failure",
				"message" => "手机号码未注册，请先注册!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else
		{
		$au_token = Security :: desencrypt($now, 'E', 'mstongfubao'); //加密
		$query = "select fd_author_id,fd_author_paypassword from tb_author where fd_author_username  = '$aumobile' and fd_author_password = '$aupwd' ";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$authorid = $db->f(fd_author_id);
			$paypassword = $db->f(fd_author_paypassword) ? "1" : "0";
			$arr_message = array (
				"authorid" => $authorid,
				"result" => "success",
				"message" => "登录成功!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		} else {
			$paypassword = 0;
			$arr_message = array (
				"authorid" => "0",
				"result" => "failure",
				"message" => "帐号或者密码失败!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['authorid'] = $arr_message['authorid'];
		$arr_msg['msgbody']['ispaypwd'] = $paypassword;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
	   
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

	//忘记密码短信校验码获取		    
	public function getSmsCode() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$phone = trim($arr_body['smsmobile']);

		//$phone = trim ( $arr ['smsmobile'] );
		$query = "select 1 d from tb_author  where fd_author_username = '$phone'";
		$db->query($query);
		if (!$db->nf()) {
			$arr_message = array (
				"result" => "failure",
				"message" => "未注册手机号码"
			);
             $retcode = "200";  //反馈状态 0 成功 200 自定义错误
			$havemobile = 1;

		} else {
			$havemobile = 0;
		}

		if ($havemobile != 1) {
			$db = new DB_test();

			$id = "0";
			//$uid = "nicegan"; //用户账户
			//$pwd = "chengan"; //用户密码
			//$pwd = md5($pwd);
			//$mobno = $phone; //发送的手机号码,多个请以英文逗号隔开如"138000138000,138111139111"
			//$code = getCode(6, 60, 20);
			//$content = "通付宝找回密码验证码:" . $code . "【通付宝】"; //发送内容
		  $id = "0";
			$uid = "nicegan"; //用户账户
			$pwd = "chengan"; //用户密码
			$mobno = $phone; //发送的手机号码,多个请以英文逗号隔开如"138000138000,138111139111"
			$code = getCode(6, 60, 20);
			$target = "http://www.106jiekou.com/webservice/sms.asmx/Submit";
		    $post_data = "account=".$uid."&password=".$pwd."&mobile=".$mobno."&content=".rawurlencode("您的验证码是：".$code.",您的密码找回验证码【通付宝】。如需帮助请联系客服。");
		    $getvalue = $this->Post($post_data, $target);	
			//$arr_message =array();
			if(!@eregi('100',$getvalue)){
				$arr_message = array (
					"result" => "failure",
					"message" => "短信发送失败!!"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误

			} else {
				$arr_message = array (
					"result" => "success",
					"message" => "短信发送成功"
				);
				$retcode = "0";  //反馈状态 0 成功 200 自定义错误

			}
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

	public function forgetPwdModify() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$aumobile = trim($arr_body['aumobile']);
		$auoldpwd = md5(trim($arr_body['auoldpwd']));
		$aunewpwd = md5(trim($arr_body['aunewpwd']));
		$aurenewpwd = md5(trim($arr_body['aurenewpwd']));
		$aumoditype = (trim($arr_body['aumoditype'])); //1 或者 2
        if($aumoditype==2)
        {
        	$query = "update tb_author set fd_author_paypassword  = '$aunewpwd' where fd_author_username = '$aumobile'";
			$db->query($query);
        }else
        {
		$query = "update tb_author set fd_author_password  = '$aunewpwd' where fd_author_username = '$aumobile'";
		$db->query($query);
        }
		$arr_message = array (
			"error_id" => "0",
			"result" => "success",
			"message" => "恭喜您,密码修改成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误

		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

	//激活插卡器		    
	public function activePayCard() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$paycardkey = $arr_body['paycardkey'];
		$authorid = $arr_channelinfo['authorid'];
		$paycardkey = strtolower($paycardkey);
		$paycardkey = str_replace("fff","",$paycardkey);
		$query = "select fd_paycard_id,fd_paycard_active from tb_paycard where  fd_paycard_key = '$paycardkey' ";   //首页广告显示
		$db->query($query);
		if (!$db->nf()) {
			$arr_message = array (
				"result" => "failure",
				"message" => "激活失败,刷卡器设备号码不存在，请联系客服!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}else
		{ 
		$query = "select fd_paycard_id,fd_paycard_active from tb_paycard where " .
				" (fd_paycard_authorid ='0' or fd_paycard_authorid is NULL) 
			      and fd_paycard_key = '$paycardkey' ";   //首页广告显示
		$db->query($query);
		if ($db->nf()) {
			    $db->next_record();
				$paycardid = $db->f(fd_paycard_id);
				$query = "update tb_paycard set fd_paycard_active = '1' ,fd_paycard_activetime = now()," .
						 "fd_paycard_authorid = '$authorid',fd_paycard_posstate='2' where fd_paycard_id = '$paycardid'";
				$db->query($query);
				$arr_message = array (
					"result" => "success",
					"message" => "恭喜您，刷卡器已经激活成功!"
				);
				$retcode = "0";  //反馈状态 0 成功 200 自定义错误
			} else {
				$query = "select fd_paycard_id,fd_paycard_active from tb_paycard where " .
				" 1
			      and fd_paycard_key = '$paycardkey' and fd_paycard_authorid <>'$authorid'";   //首页广告显示
				if($db->execute($query))
				{
					$retmessage = "该刷卡器已被其他商户激活";
				}else
				{
					$retmessage = "你已激活刷卡器,可以直接使用了!";
				}
				$arr_message = array (
					"result" => "failure",
					"message" => $retmessage
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		}
       
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

	//插卡器检测是否可以使用		    
	public function payCardCheck() {
		global $arr_limitauthorid;
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$paycardkey = $arr_body['paycardkey'];
		$authorid = $arr_channelinfo['authorid'];
        /*2014-06-18 取消商户对刷卡器的限制 */
        $paycardkey = strtolower($paycardkey);
        $paycardkey = str_replace("fff","",$paycardkey);
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardkey'])); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardkey'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key

        AgentPayglist::savePayCardinfo($paycardid);  //保存最新刷卡时间
        $arr_message = array (
            "result" => "success",
            "message" => "刷卡器检验成功!"
        );
        $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $returnvalue = array (

            "msgbody" => $arr_msg['msgbody']

        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);


        /*---end ---   */


		$query = "select fd_paycard_authorid as pauthorid from tb_paycard where   fd_paycard_key = '$paycardkey'  ";
		$arr_authid = $db->get_one($query);
//		if(in_array($authorid, $arr_limitauthorid))
//			{
//		if($arr_authid['pauthorid']!=$authorid && $arr_authid['pauthorid']>0)
//		{
//			$Error = array (
//				'result' => 'failure',
//				'retcode' => '200',
//				'retmsg' => '该刷卡器已被其他商户使用，您不允许使用！'
//			);
//			$this->ErrorReponse->reponError($Error); //出错反馈
//			exit;
//
//		}
//			}
		$query = "select fd_paycard_id,fd_paycard_active,fd_paycard_posstate from tb_paycard where fd_paycard_key = '$paycardkey'  ";
		$db->query($query);
		//echo $query;
		if ($db->nf()) {
			$db->next_record();
			$paycardid = $db->f(fd_paycard_id);
			$posstate = $db->f(fd_paycard_posstate);  //0 停用 1 警告 2启用 3 冻结
			$paycardactive = $db->f(fd_paycard_active);  //0 停用 1 警告 2启用 3 冻结
			if ($paycardactive == 0) {
				$arr_message = array (
					"result" => "failure",
					"message" => "请先激活刷卡器!"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			} else {
				$arr_message = array (
					"result" => "success",
					"message" => "刷卡器检验成功!"
				);
				$retcode = "0";  //反馈状态 0 成功 200 自定义错误
			}
			if ($posstate == '3' || $posstate == '0' ) {
				$arr_message = array (
					"result" => "failure",
					"message" => "该刷卡器被冻结或者停用!"
				);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			}
		} else {
			$arr_message = array (
				"result" => "failure",
				"message" => "刷卡失败，请先激活刷卡器!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			if(!in_array($authorid, $arr_limitauthorid))
			{
			$arr_message = array (
				"result" => "success",
				"message" => "刷卡器验证通过!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
			}
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
function readAuthorupicinfo($uploadpictype, $authorid) {
	global $picurl,$g_propic;
	$dbfile = new DB_file();
	$Publiccls = new PublicClass(); //初始化类实例 

	$query = "select * from tb_upload_scategoty
			left join tb_upload_fcategory  on fd_scat_fcatid=fd_fcat_id 
			where fd_scat_id='$uploadpictype'";
	$dbfile->query($query);
	if ($dbfile->nf()) {
		$dbfile->next_record();
		$ffoldername = g2u($dbfile->f(fd_fcat_foldername));
		$sfoldername = g2u($dbfile->f(fd_scat_foldername));
	}
	$arr_uplaod = getupload($uploadpictype, $authorid);
	//echo var_dump($arr_uplaod);	
	$arr_message['picid'] = $arr_uplaod[0]['pic'];
	$arr_message['picpath'] = $arr_uplaod[1]['picpath'];
    $thumrul	= str_replace("../","",$arr_message['picpath'] );
      
	if(@eregi('http',$thumrul)){
		$arr_message['picpath']  = $thumrul;
	}else{
		$arr_message['picpath'] = $g_propic.$thumrul;		
	}
			
	$arr_message['uploadpictype'] = g2u($uploadpictype);
	$arr_message['uploadmethod'] = $arr_message['picid'] ? "modi" : "new";
	$arr_message['uploadurl'] = $picurl . 'mobilepaypic/';

	$pictype = '格式：jpg|bmp|gif';
	$pictype = $pictype;
	$uplaodmessage = "<msgchild>";
	$uplaodmessage .= "<picid>" . $arr_message['picid'] . "</picid>";
	$uplaodmessage .= "<pictype>" . $pictype . "</pictype>";
	$uplaodmessage .= "<picpath>" . $arr_message['picpath'] . "</picpath>";
	$uplaodmessage .= "<uploadpictype>" . $arr_message['uploadpictype'] . "</uploadpictype>";
	$uplaodmessage .= "<uploadurl>" . $arr_message['uploadurl'] . "</uploadurl>";
	$uplaodmessage .= "<uploadmethod>" . $arr_message['uploadmethod'] . "</uploadmethod>";
	$uplaodmessage .= "</msgchild>";

	$arr_xml = $Publiccls->xml_to_array($uplaodmessage);
	return $arr_xml;
}
?>