<?php
class ApiKuaiDiinfo  extends TfbxmlResponse{
	//查询订单号		    
	public function chaxunKuaiDiNo() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$com = $arr_body ['com'];
		$nu = $arr_body ['nu'];
		
		$query = "select fd_kdcompany_id as comid,fd_kdcompany_no as com,
		         fd_kdcompany_api as api,fd_kdcompany_name as comname from 
	       tb_kdcompany where fd_kdcompany_no = '$com'"; //只显示激活的列表 
		$db->query ( $query );
		$arr_v = auto_charset ( $db->getFiledData ( '' ), 'gbk', 'utf-8' );
		$a = new Express ( );
		switch ($arr_v ['api']) {
			case 'gethtmlorder' :
				//echo $arr_v ['api'];
				$result = $a->gethtmlorder ( $com, $nu );
				$arr_msg ['msgbody']['apiurl'] = $result;
				$arr_msg ['msgbody'] ['apitype'] = $arr_v ['api'];
				break;
			default :
				$result = $a->getorder ( $com, $nu );
				$arr_msg ['msgbody'] = $result;
				$arr_msg ['msgbody'] ['apitype'] = $arr_v ['api'];
				break;
		}
		$arr_message = array ("result" => "success", "message" => "读取成功!" );
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		
		$returnvalue = array ( "msgbody" => $arr_msg ['msgbody']  );
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);return $returnval; 
	}
	
	//读取快递公司		    
	public function readKuaiDicmpList() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$apptype = trim ( $arr_body ['apptype'] );
		$appversion = trim ( $arr_body ['appversion'] );
		
		$query = "select fd_kdcompany_id as comid,fd_kdcompany_no as com,fd_kdcompany_name as comname
		         ,fd_kdcompany_api as apitype,fd_kdcompany_pic as comlogo ,fd_kdcompany_phone as comphone from 
	             tb_kdcompany where fd_kdcompany_active = '1' order by fd_kdcompany_api desc"; //只显示激活的列表 
		$db->query ( $query );
		$arr_msg = auto_charset ( $db->getData ( '', 'msgbody' ), 'gbk', 'utf-8' );
		
		if (! $arr_msg) {
			$arr_message = array ("result" => "failure", "message" => "列表为空!" );
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array ("result" => "success", "message" => "读取成功!" );
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg ['msgbody'] ['result'] = $arr_message ['result'];
		$arr_msg ['msgbody'] ['message'] = $arr_message ['message'];
		$returnvalue = array ( "msgbody" => $arr_msg ['msgbody'] );
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 
	}
}



?>