<?php
class ApiAuCardInfo extends TfbxmlResponse {

	//readAuthorInfo  读取用户信息
	public function readAuBkCardInfo() {
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

			$arr_msg['msgbody']['aushoucardman'] = g2u($db->f(fd_author_shoucardman));
			$arr_msg['msgbody']['aushoucardphone'] = $db->f(fd_author_shoucardphone);
			$arr_msg['msgbody']['aushoucardno'] = $db->f(fd_author_shoucardno);
			$arr_msg['msgbody']['aushoucardbank'] = g2u($db->f(fd_author_shoucardbank));
			$arr_msg['msgbody']['aushoucardstate'] = $db->f(fd_author_shoucardstate)+0;

		} else {
			$arr_message = array (
				"error_id" => "1",
				"result" => "failure",
				"message" => "用户信息已失效，请重新登录"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	//modifyAuBkCardInfo  修改用户信息
	public function modifyAuBkCardInfo() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$shoucardman = u2g(trim($arr_body['aushoucardman']));
		$shoucardphone = trim($arr_body['aushoucardphone']);
		$shoucardno = trim($arr_body['aushoucardno']);
		$shoucardbank = u2g(trim($arr_body['aushoucardbank']));
        
        $query = "select * from tb_author  where fd_author_id = '$authorid'  and fd_author_couponstate='1'";
		$db->query($query);
			if ($db->nf()) 
			{
				$arr_message = array (
				"result" => "failure",
				"message" => "银行卡资料已经审批，如需修改请联系客服！"
				
			);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			    $Error = array (
				'result' => 'failure',
				'retcode' => '200',
				'retmsg' => $arr_message['message']
			);
			$arr_msg['msgbody'] = array (
			"result" => "failure",
			"message" => "银行卡资料已经审批，如需修改请联系客服！"
		);
		$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
			//$ErrorReponse->reponError($Error); //出错反馈 
			//return false;
		}
			
		$query = "update tb_author set   fd_author_shoucardman = '$shoucardman',
			   fd_author_shoucardphone = '$shoucardphone',
			   fd_author_shoucardno='$shoucardno',
			   fd_author_shoucardbank='$shoucardbank',fd_author_couponstate = '0' where fd_author_id  = '$authorid' ";

		$db->query($query);

		$arr_msg['msgbody'] = array (
			"error_id" => "0",
			"result" => "success",
			"message" => "恭喜您,我的银行卡修改成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
}
?>