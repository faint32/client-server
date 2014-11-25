<?php
class ApiAppHelpinfo  extends TfbxmlResponse {
	//反馈意见		    
	public function readHelpList() {
		$db = new DB_test();
		
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$msgstart = $arr_body ['msgstart'] + 0;
		$msgdisplay = $arr_body ['msgdisplay'] + 0;
		
		if ($msgstart < 0)
			$msgstart = 0;
		
		$query = "select  1 from web_help order by fd_help_no ";
		$db->query ( $query );
		$msgallcount = $db->nf ();
		$query = "select fd_help_id as helpid ,fd_help_name as helpname,fd_help_contect as helpcontent,
	          fd_help_date as helpdate from web_help order by fd_help_no limit $msgstart, $msgdisplay  ";
		$db->query ( $query );
		$msgdiscount = $db->nf ();
		$arr_msg = auto_charset ( $db->getData ( '', 'msgbody' ), 'gbk', 'utf-8' );
		
		$arr_message = array (
					"result" => "success",
					"message" => "读取成功！"
				);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
				
		$arr_msg ['msgbody'] ['msgallcount'] = $msgallcount;
		$arr_msg ['msgbody'] ['msgdiscount'] = $msgdiscount + $msgstart;
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg ['msgbody']  );
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 
	}
}

?>