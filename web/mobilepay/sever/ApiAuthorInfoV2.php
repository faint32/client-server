<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

class ApiAuthorInfoV2 extends TfbxmlResponse
{
	/*
	* 注册，需要传入参数：phonenumber（用户手机号，作为用户账号）、paypasswd（登录密码）
	*/
	public function register()
	{
$logger = Logger::getLogger('AuthorInfo');
		$retcode = "200";
		$arr_message = array ("result" => "failure", "message" => "操作出现异常，请稍后再试！");
		
		$arr_body = $this->arr_body;
		$phonenumber = trim($arr_body['phonenumber']);
		$paypasswd = trim(md5($arr_body['paypasswd']));
$logger->info("开始注册 : phonenumber : (" . $phonenumber . ")");

		if($phonenumber != "" && $arr_body['paypasswd'] != "")
		{
			$legalPasswd = true;
			// 目前并没有对密码的合法性进行判断
			if($legalPasswd)
			{
				$db = new DB_test();
				$query = "SELECT 1 FROM tb_author WHERE fd_author_username = '$phonenumber'";
				$db->query($query);
				if($db->nf())
				{
					$arr_message = array ("result" => "failure", "message" => "该手机号已注册过通付宝账户");
				}
				else
				{
					$query = "INSERT INTO tb_author (fd_author_username, fd_author_paypassword, fd_author_mobile, fd_author_regtime, fd_author_datetime, 
							fd_author_isstop, fd_author_state, fd_author_sdcrid, fd_author_auindustryid, fd_author_slotpayfsetid, 
							fd_author_slotscdmsetid, fd_author_bkcardpayfsetid, fd_author_bkcardscdmsetid, 
							fd_author_couponstate, fd_author_memid, fd_author_shopid, fd_author_authortypeid) 
							VALUES( '$phonenumber' ,'$paypasswd', '$phonenumber', now(), now(), 0, 9, 3, 4, 8, 14, 25, 9, 0, 3554, 102, 5)";
					$db->query($query);
					$authorid = $db->insert_id();
					$arr_message = array ("result" => "success", "message" => "恭喜您,注册成功!");
					$retcode = "0";
					$arr_msg['msgbody']['authorid'] = $authorid;
					global $au_token;
					
					$au_token = Security :: desencrypt(strtotime(date("Y-m-d H:i:s")), 'E', 'mstongfubao');
				}
			}
			else
			{
				$arr_message = array ("result" => "failure", "message" => "密码不符合规则！");
			}
		}
$logger->info("complete register");
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];

		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	// ABANDONED 20140616
	public function authorExists()
	{
		$arr_body = $this->arr_body;
		$phonenumber = trim($arr_body['phonenumber']);
		$paypasswd = trim(md5($arr_body['paypasswd']));
		
		$arr_message = array ("result" => "failure", "message" => "操作出现异常，请稍后再试！");
		$retcode = "200";
		if($phonenumber != "" && $arr_body['paypasswd'] != "")
		{
			$db = new DB_test();
			$query = "SELECT fd_author_id, fd_author_password FROM tb_author WHERE fd_author_username = '$phonenumber' AND fd_author_paypassword = '$paypasswd'";
			$author = $db->get_all($query);
			if($author != 0 && count($author) == 1)
			{
				$arr_message = array ("result" => "success", "message" => "该用户已经存在");
				$arr_msg['msgbody']['authorid'] = $author[0]["fd_author_id"];
				$arr_msg['msgbody']['gesturepasswd'] = $author[0]["fd_author_password"] == "" ? 0 : 1;
				$retcode = "0";
			}
			else
			{
				$arr_message = array ("result" => "failure", "message" => "用户不存在或密码错误");
			}
		}
		else
		{
			$arr_message = array ("result" => "failure", "message" => "信息不完整");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];

		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	// 对应文档《登录注册相关流程》中 9修改用户信息
	public function modifyAuthorInfo()
	{
		$arr_body = $this->arr_body;
		$accountnumber = trim($arr_body['accountnumber']);
		$accountname = trim($arr_body['accountname']);
		$bankname = trim($arr_body['bankname']);
		$phonenumber = trim($arr_body['phonenumber']);
		
		$authorid = $this->arr_channelinfo['authorid'];
		
		$retcode = 200;
		$arr_message = array("result" => "failure", "message" => "操作出现异常，请稍后再试！");
		
		if($authorid != "")
		{
			if($phonenumber != "" || ($accountnumber != "" && $accountname != "" && $bankname != ""))
			{
				$db = new DB_test();
				if($phonenumber != "")
				{
					$query = "UPDATE tb_author SET fd_author_mobile = '$phonenumber' WHERE fd_author_id = $authorid;";
					$db->query($query);
				}
				if($accountnumber != "" && $accountname != "" && $bankname != "")
				{
					$query = "UPDATE tb_author SET fd_author_shoucardman = '$accountname', fd_author_shoucardphone = '$phonenumber', fd_author_shoucardno = '$accountnumber', fd_author_shoucardbank = '$bankname' WHERE fd_author_id = $authorid;";
					$db->query($query);
				}
				$retcode = "0";
				$arr_message = array("result" => "success", "message" => "信息修改成功");
			
			}
			else
			{
				$arr_message = array("result" => "failure", "message" => "传入参数异常");
			}
		}
		else
		{
			$arr_message = array("result" => "failure", "message" => "用户账号异常!");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	// 对应文档《登录注册相关流程》中 8登录
	public function login()
	{
		$retcode = "200";
		$arr_message = array ("result" => "fail", "message" => "操作出现异常，请稍后再试！");
		
		$now = time();
$logger = Logger::getLogger('AuthorInfo');
		$authorid = $this->arr_channelinfo['authorid'];

		$arr_body = $this->arr_body;
		$mobile = trim($arr_body['mobile']);
		
		$gesturepasswdRaw = trim($arr_body['gesturepasswd']);
		$gesturepasswd = md5($gesturepasswdRaw);
		
		$paypasswdRaw = trim($arr_body['paypasswd']);
		$paypasswd = md5($paypasswdRaw);
$logger->info("start login($now) : authorid($authorid), mobile($mobile), gesturepasswdRaw($gesturepasswdRaw), paypasswdRaw($paypasswdRaw)");

		if(($authorid != "" || $mobile != "") && ($gesturepasswdRaw != "" || $paypasswdRaw != ""))
		{
			global $au_token;
$logger->debug("process login($now) : au_token($au_token)");
			$now = strtotime(date("Y-m-d H:i:s"));
			
			$passwd = $gesturepasswdRaw != "" ? $gesturepasswd : $paypasswd;
			
			$author = $this->getAuthorInfo($authorid, $mobile);
			if($author != null)
			{
				if($author['fd_author_paypassword'] == "" && $paypasswdRaw != "")
				{
$logger->info("process login($now) : authorid(" . $author['fd_author_id'] . ") fd_author_paypassword is null, so reset his fd_author_paypassword");
					$this->resetPayPassword($author['fd_author_id'], $paypasswd);
					$author['fd_author_paypassword'] = $passwd;
				}
				if($author['fd_author_password'] == $passwd || $author['fd_author_paypassword'] == $passwd)
				{
					if($author['fd_author_isstop'] == 1 || $author['fd_author_state'] == -1)
					{
						$arr_message = array("result" => "failure", "message" => "您的账户异常，已经冻结使用!");
					}
					else
					{
						$au_token = Security :: desencrypt($now, 'E', 'mstongfubao');
$logger->debug("process login($now) : au_token($au_token)");
						$arr_message = array ("result" => "success", "message" => "登录成功!");
						$retcode = "0";
					}
				}
				else
				{
					$arr_message = array("result" => "failure", "message" => "密码错误!");
				}
				$arr_msg['msgbody']['authorid'] = $author['fd_author_id'];
				$arr_msg['msgbody']['relateAgent'] = $author['fd_author_bdagentid'] > 0 ? 1 : 0;
				$arr_msg['msgbody']['agentid'] = $author['fd_author_cusid'] + 0;
				$arr_msg['msgbody']['gesturepasswd'] = $author['fd_author_password'] != "" ? 1 : 0;
			}
			else
			{
				$arr_message = array("result" => "failure", "message" => "帐号不存在!");
			}
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	private function getAuthorInfo($authorid, $mobile)
	{
		$now = time();
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("start getAuthorInfo($now) : authorid($authorid), mobile($mobile)");
		if($authorid != "")
		{
			$query = "SELECT fd_author_isstop, fd_author_state, fd_author_id, fd_author_password, fd_author_paypassword, fd_author_cusid, fd_author_bdagentid 
					FROM tb_author WHERE fd_author_id = '$authorid'";
		}
		else
		{
			$query = "SELECT fd_author_isstop, fd_author_state, fd_author_id, fd_author_password, fd_author_paypassword, fd_author_cusid, fd_author_bdagentid 
					FROM tb_author WHERE fd_author_username = '$mobile'";
		}
		
		if($query != "")
		{
$logger->debug("process getAuthorInfo($now) : query($query)");
			$db = new DB_test();
			$result = $db->get_all($query);
			if(!is_array($result) || count($result) != 1)
			{
$logger->error("process getAuthorInfo($now) : get data from query($query), dataInDB(" . print_r($result, true) . ")");
				return null;
			}
			else
			{
				$author = array(
					"fd_author_isstop" => $result[0]["fd_author_isstop"], 
					"fd_author_state" => $result[0]["fd_author_state"],
					"fd_author_id" => $result[0]["fd_author_id"],
					"fd_author_password" => $result[0]["fd_author_password"],
					"fd_author_paypassword" => $result[0]["fd_author_paypassword"],
					"fd_author_bdagentid" => $result[0]["fd_author_bdagentid"],
					"fd_author_cusid" => $result[0]["fd_author_cusid"] + 0);
				return $author;
			}
		}
		return null;
	}

	// 修改密码		    
	public function authorPwdModify()
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("开始修改密码");
		$authorid = $this->arr_channelinfo['authorid'];
$logger->debug("传入的authorid为（" . $authorid . "）");
		$arr_body = $this->arr_body;
		$auoldpwd = trim($arr_body['auoldpwd']);
$logger->debug("传入的auoldpwd为（" . $auoldpwd . "）");
		$auoldpwd = md5($auoldpwd);
		$aunewpwdRaw = trim($arr_body['aunewpwd']);
$logger->debug("传入的aunewpwd为（" . $auoldpwd . "）");
		$aunewpwd = md5($aunewpwdRaw);
		$aumoditype = (trim($arr_body['aumoditype']));
$logger->debug("传入的aumoditype为（" . $aumoditype . "）");
		$reset = (trim($arr_body['reset']));
$logger->debug("传入的reset为（" . $reset . "）");

		$retcode = 200;
		$arr_message = array("result" => "failure", "message" => "操作出现异常，请稍后再试！");
		
		if($authorid != "" && $aunewpwdRaw != "")
		{
			if ($aumoditype == 1)
			{																							// 修改手势密码
				if($reset == 1)
				{
					if($this->resetGuestPassword($authorid, $aunewpwd))
					{
						$retcode = "0";
						$arr_message = array("result" => "success", "message" => "手势密码修改成功");
					}
				}
				else
				{
					if($aunewpwd == $auoldpwd)
					{
						$arr_message = array("result" => "failure", "message" => "新密码不能等于旧密码!");
					}
					else
					{
						if($this->changeGuestPawdUsingOld($authorid, $aunewpwd, $auoldpwd))
						{
							$retcode = "0";
							$arr_message = array("result" => "success", "message" => "手势密码修改成功");
						}
						else
						{
							$arr_message = array("result" => "failure", "message" => "帐号或者密码错误!");
						}
					}
				}
			}
			else if($aumoditype == 2)
			{																							// 修改登录密码
				if($reset == 1)
				{
					if($this->resetPayPassword($authorid, $aunewpwd))
					{
						$retcode = "0";
						$arr_message = array("result" => "success", "message" => "登录密码修改成功");
					}
				}
				else
				{
					if($aunewpwd == $auoldpwd)
					{
						$arr_message = array("result" => "failure", "message" => "新密码不能等于旧密码!");
					}
					else
					{
						if($this->changePayPawdUsingOld($authorid, $aunewpwd, $auoldpwd))
						{
							$retcode = "0";
							$arr_message = array("result" => "success", "message" => "登录密码修改成功");
						}
						else
						{
							$arr_message = array("result" => "failure", "message" => "帐号或者密码错误!");
						}
					}
				}
			}
		}
		else
		{
			$arr_message = array("result" => "failure", "message" => "用户输入信息异常，请稍后再试！");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	// 重置手势密码
	private function resetGuestPassword($authorid, $aunewpwd)
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("开始重置手势密码");
		$db = new DB_test();
		$query = "UPDATE tb_author SET fd_author_password = '$aunewpwd' WHERE fd_author_id = '$authorid'";
$logger->debug("sql语句（" . $query . "）");
		$db->query($query);
		return true;
	}
	
	// 通过旧密码修改手势密码
	private function changeGuestPawdUsingOld($authorid, $aunewpwd, $auoldpwd)
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("开始通过旧密码修改手势密码");
		$db = new DB_test();
		$query = "UPDATE tb_author SET fd_author_password = '$aunewpwd' WHERE fd_author_id = '$authorid' AND fd_author_password = '$auoldpwd'";
$logger->debug("sql语句（" . $query . "）");
		$db->query($query);
		$result = $db->affected_rows();
		if($result == 1)
		{
			return true;
		}
		else if($result > 1)
		{
$logger->ERR("sql语句（" . $query . "）的执行影响了（" . $result . "）条数据");
		}
		return false;
	}
	
	// 重置登录密码
	private function resetPayPassword($authorid, $aunewpwd)
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("开始重置登录密码");
		$db = new DB_test();
		$query = "UPDATE tb_author SET fd_author_paypassword = '$aunewpwd' WHERE fd_author_id = '$authorid'";
$logger->debug("sql语句（" . $query . "）");
		$db->query($query);
		return true;
	}
	
	// 通过旧密码修改登录密码
	private function changePayPawdUsingOld($authorid, $aunewpwd, $auoldpwd)
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->debug("开始通过旧密码修改登录密码");
		$db = new DB_test();
		$query = "UPDATE tb_author SET fd_author_paypassword = '$aunewpwd' WHERE fd_author_id = '$authorid' AND fd_author_paypassword = '$auoldpwd'";
$logger->debug("sql语句（" . $query . "）");
		$db->query($query);
		$result = $db->affected_rows();
$logger->debug("sql语句的执行影响了（" . $result . "）条数据");
		if($result == 1)
		{
			return true;
		}
		else if($result > 1)
		{
$logger->ERR("sql语句（" . $query . "）的执行影响了（" . $result . "）条数据");
		}
		return false;
	}
}