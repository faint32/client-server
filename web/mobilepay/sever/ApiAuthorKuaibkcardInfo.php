<?php
//按店多接口设计做的
class ApiAuthorKuaibkcardInfo extends TfbxmlResponse
{
	public function readKuaibkcardLists()
	{
		$authorId = trim($this->arr_channelinfo['authorid']);
		$query = "SELECT fd_bkcardid AS bkcardid, fd_bkcardno AS bkcardno, fd_bkcardbankid AS bkcardbankid, fd_bkcardbankcode AS bkcardbankcode, fd_bkcardbank AS bkcardbank, CONCAT('bank', CAST(fd_bkcardbankid AS CHAR), '.png') AS bkcardbanklogo, fd_bkcardbankman AS bkcardbankman, fd_bkcardbankphone AS bkcardbankphone, fd_bkcardyxmonth AS bkcardyxmonth, fd_bkcardyxyear AS bkcardyxyear, fd_bkcardcvv AS bkcardcvv, fd_bkcardidcard AS bkcardidcard, fd_bkcardisdefault AS bkcardisdefault, fd_bkcardcardtype AS bkcardcardtype FROM tb_author_quickpaycard WHERE fd_bkauthorid = " . $authorId;
		$db = new DB_test();
		$db->query($query);
		
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "您还没绑定过快捷银行卡!"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	public function AddKuaibkcard()
	{
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);

		$bkcardbankcode = trim($arr_body['bkcardbankid']);
		$bkcardbank = trim($arr_body['bkcardbank']);
		$bkcardno = trim($arr_body['bkcardno']);
		$bkcardbankman = trim($arr_body['bkcardbankman']);
		$bkcardbankphone = trim($arr_body['bkcardbankphone']);
		$bkcardyxmonth = trim($arr_body['bkcardyxmonth']);
		$bkcardyxyear = trim($arr_body['bkcardyxyear']);
		$bkcardcvv = trim($arr_body['bkcardcvv']);
		$bkcardidcard = trim($arr_body['bkcardidcard']);
		$bkcardcardtype = trim($arr_body['bkcardcardtype']);
		$bkcardisdefault = trim($arr_body['bkcardisdefault']);
		$bkcardbankid = getbankid(u2g($bkcardbank));
		
		$query = "select 1 from tb_author_quickpaycard where fd_bkauthorid = $authorid AND fd_bkcardno = '$bkcardno'";
		if ($db->execute($query)) ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '该卡已经绑定过'));
		
		if($bkcardisdefault == 1)
		{
			$query = "UPDATE tb_author_quickpaycard SET fd_bkcardisdefault = 0 WHERE fd_bkauthorid = " . $authorid;
			$db->query($query);
		}
		
		$query = "insert into tb_author_quickpaycard(
							fd_bkauthorid			,fd_bkcardno		,fd_bkcardbankid	,
				            fd_bkcardbankcode		,fd_bkcardbank		,fd_bkcardbankman	,
				            fd_bkcardbankphone		,fd_bkcardyxmonth          ,fd_bkcardyxyear     ,
				            fd_bkcardcvv		,fd_bkcardidcard		,fd_bkcardisdefault		 ,
				            fd_bkcardcardtype)values
						   ('$authorid'		,'$bkcardno'	,'$bkcardbankid'		,
						   '$bkcardbankcode'		,'$bkcardbank'	,'$bkcardbankman'		,
						   '$bkcardbankphone'		,'$bkcardyxmonth'	,'$bkcardyxyear'			,
						   '$bkcardcvv'			,'$bkcardidcard','$bkcardisdefault'		,
						   '$bkcardcardtype')";
		$db->query(auto_charset($query, 'utf-8', 'gbk'));
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "添加成功");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	public function EditKuaibkcard()
	{
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);

		$bkcardid = trim($arr_body['bkcardid']);
		$bkcardbankcode = trim($arr_body['bkcardbankid']);
		$bkcardbank = trim($arr_body['bkcardbank']);
		$bkcardno = trim($arr_body['bkcardno']);
		$bkcardbankman = trim($arr_body['bkcardbankman']);
		$bkcardbankphone = trim($arr_body['bkcardbankphone']);
		$bkcardyxmonth = trim($arr_body['bkcardyxmonth']);
		$bkcardyxyear = trim($arr_body['bkcardyxyear']);
		$bkcardcvv = trim($arr_body['bkcardcvv']);
		$bkcardidcard = trim($arr_body['bkcardidcard']);
		$bkcardcardtype = trim($arr_body['bkcardcardtype']);
		$bkcardisdefault = trim($arr_body['bkcardisdefault']);
		$bkcardbankid = getbankid(u2g($bkcardbank));
		
		if($bkcardisdefault == 1)
		{
			$query = "UPDATE tb_author_quickpaycard SET fd_bkcardisdefault = 0 WHERE fd_bkauthorid = " . $authorid;
			$db->query($query);
		}
		
		$query = "UPDATE tb_author_quickpaycard SET fd_bkcardno = '$bkcardno',fd_bkcardbankid = '$bkcardbankid',fd_bkcardbankcode = '$bkcardbankcode',fd_bkcardbank = '$bkcardbank',fd_bkcardbankman = '$bkcardbankman',fd_bkcardbankphone = '$bkcardbankphone',fd_bkcardyxmonth = '$bkcardyxmonth',fd_bkcardyxyear = '$bkcardyxyear',fd_bkcardcvv = '$bkcardcvv',fd_bkcardidcard = '$bkcardidcard',fd_bkcardisdefault = '$bkcardisdefault',fd_bkcardcardtype = '$bkcardcardtype' WHERE fd_bkcardid = " . $bkcardid;
		$db->query(auto_charset($query, 'utf-8', 'gbk'));
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "修改成功");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	public function DeleteKuaibkcard()
	{
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);

		$bkcardid = trim($arr_body['bkcardid']);
		
		$query = "DELETE FROM tb_author_quickpaycard WHERE fd_bkcardid = " . $bkcardid;
		$db->query(auto_charset($query, 'utf-8', 'gbk'));
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "解除成功");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	public function DefaultKuaibkcard()
	{
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim($arr_channelinfo['authorid']);

		$bkcardid = trim($arr_body['bkcardid']);
		
		$bkcardisdefault = trim($arr_body['bkcardisdefault']);
		
		if($bkcardisdefault == 1)
		{
			$query = "UPDATE tb_author_quickpaycard SET fd_bkcardisdefault = 0 WHERE fd_bkauthorid = " . $authorid;
			$db->query($query);
		}
		
		$query = "UPDATE tb_author_quickpaycard SET fd_bkcardisdefault = '$bkcardisdefault' WHERE fd_bkcardid = " . $bkcardid;
		$db->query(auto_charset($query, 'utf-8', 'gbk'));
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "设定成功");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}