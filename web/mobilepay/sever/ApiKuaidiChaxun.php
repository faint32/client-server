<?php
class ApiKuaidiChaxun  extends TfbxmlResponse{
	
	//快递查询		    
	public function kuaiState() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$authorid = trim ( $arr_header ['authorid'] );
		$kdtype = trim ( $arr_body ['kdtype'] );
		$kdcode = trim ( $arr_body ['kdcode'] );
		$a = new Express();
		$arr_msg = $a->gethtmlorder($kdtype,$kdcode);
		//print_r($arr_msg);exit;
		$returnvalue = array ( $arr_msg );
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 

				  
		
	}
}
?>