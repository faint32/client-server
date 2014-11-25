<?php
class ApiSafeGuard extends TfbxmlResponse
{
	// 获取所有预设密保问题
	public function getQueList()
	{
		$db = new DB_test();
		$query = "SELECT fd_question_id AS queid, fd_question_contect AS quecontent 
					FROM tb_safeguard_secque WHERE fd_question_state = 2 ORDER BY fd_question_orderid";
		$db->query($query);
		if($db->nf() > 0)
		{
			$retcode = "0";
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_msg['msgbody']['result'] = "success";
			$arr_msg['msgbody']['message'] = "成功获取密保问题";
			$arr_msg['msgbody']['msgallcount'] = $db->nf();
		}
		else
		{
			$retcode = "200";
			$arr_msg['msgbody']['result'] = "fail";
			$arr_msg['msgbody']['message'] = "暂时无法获取数据，请稍后再试！";
		}
		
		$returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }
	
	// 设置密保问题
	public function setAnswer()
	{
		$db = new DB_test();
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);
		$query = "DELETE FROM tb_safeguard_secque_useranswer WHERE fd_author_id = $authorid ;";
		$db->query($query);
		if(count($arr_body) > 0)
		{
			for($i = 0; $i < count($arr_body); $i++)
			{
				$itemKey = "msgchild" . ($i+1);
				$questionid = (trim($arr_body[$itemKey]['queid']));
				$answer = u2g(trim($arr_body[$itemKey]['answer']));
				if($questionid != "" && trim($arr_body[$itemKey]['answer']) != "")
				{
					$query = "REPLACE INTO tb_safeguard_secque_useranswer (`fd_question_id`, `fd_author_id`, `fd_answer`) 
							VALUES ( $questionid , $authorid , '" . $answer . "');";
					$db->query($query);
				}
			}
			$arr_message = array ("result" => "success", "message" => "设置成功");
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" => "设置失败");
		}
	
		$retcode = "0";
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 验证用户密保问题及答案
	public function validateUser()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
		
		$db = new DB_test();
		$phonenumber = trim($this->arr_body['phonenumber']);
		
		$query = "SELECT fd_author_id FROM tb_author WHERE fd_author_username = '" . $phonenumber . "'";
		$authorid = $db->get_all($query);
		
		if($authorid != 0 && count($authorid) > 0)
		{
			$authorid = $authorid[0]["fd_author_id"];
			$query = "SELECT Q.fd_question_contect  AS que, A.fd_answer AS answer 
					FROM tb_safeguard_secque_useranswer AS A 
					INNER JOIN tb_safeguard_secque AS Q ON A.fd_question_id = Q.fd_question_id 
					WHERE A.fd_author_id = " . $authorid;
					
			$db->query($query);
			
			$retcode = "0";
			if($db->nf() > 0)
			{
				$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
				$arr_msg['msgbody']['authorid'] = $authorid;
				$arr_message = array ("result" => "success", "message" => "成功获取密保问题");
			}
			else
			{
                $retcode = "200";
				$arr_message = array ("result" => "failure", "message" => "您还未设置过密保问题");
			}
		}
		else
		{
            $retcode = "200";
			$arr_message = array ("result" => "failure", "message" => "该手机号还未注册过通付宝账号");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}