<?php
class ApiAuthorfeedbck  extends TfbxmlResponse {
	//反馈意见		    
	public function authorFeedbck() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim ( $arr_channelinfo ['authorid'] );
		$fdcontent = (u2g ( trim ( $arr_body ['fdcontent'] ) ));
		$fdlinkmethod = u2g ( trim ( $arr_body ['fdlinkmethod'] ) );
		$fdcontent = str_replace("'","‘",$fdcontent);
		$fdcontent = str_replace("¥","￥",$fdcontent);
		//addslashes
		$query = "insert into tb_feedback (fd_feedback_authorid ,fd_feedback_content ,fd_feedback_linkman,
	          fd_feedback_datetime)values( '$authorid' ,'$fdcontent','$fdlinkmethod',now())";
		$db->query ( $query );
		
		$arr_message = array ("error_id" => "0", "result" => "success", "message" => "感谢您的宝贵意见!" );
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg ['msgbody'] ['result'] = $arr_message ['result'];
		$arr_msg ['msgbody'] ['message'] = $arr_message ['message'];
		$returnvalue = array (
		"msgbody" => $arr_msg ['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 
	}

}

?>