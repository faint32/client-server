<?php
// POST响应  
class TfbxmlResponse {
	public $Publiccls;
	public $Arr_reqcontext;
	public $arr_header;
	public $arr_body;
	public $arr_channelinfo;
	public $reqxmlcontext;
	public $ErrorReponse; // 出错处理
	public function __construct() {
		$Publiccls = new PublicClass(); //初始化类实例 
		global $reqxmlcontext;
		$this->Arr_reqcontext  = $Publiccls->xml_to_array($reqxmlcontext); // xml 转为 array 
		$this->arr_header      = $this->Arr_reqcontext['operation_request']['msgheader'];
		$this->arr_body        = $this->Arr_reqcontext['operation_request']['msgbody'];
		$this->arr_channelinfo = $this->Arr_reqcontext['operation_request']['msgheader']['channelinfo'];
		$this->reqxmlcontext   = $reqxmlcontext;
        $this->Publiccls = new PublicClass(); //初始化类实例
		$this->ErrorReponse = new ErrorReponse();
	}
	public static function returnCurl($toUrl){
 	  $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $toUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		if ($output === FALSE) {
			$Error = array (
				'rettype' => '800',
				'retcode' => '800',
				'retmsg' => curl_error($ch)
			);
			$ErrorReponse->reponError($Error);
			exit ();
		}
		$result = json_decode($output,true);
		return $result;
		curl_close($ch);
}	
	public static function ResponsetoApp($retcode, $msg_body) {
		global $req_token; // 授权码
		global $au_token; // 动态码 
		global $req_version; // 版本号
		global $req_bkenv; // 银联环境
		global $req_time;
		global $api_name;
		global $api_name_func;
		global $reqcontext;
		global $authorid;
		global $authortruename;
		global $g_sdcrid;
	$arr_retmsg = array (
			'0' => '成功',
			'500' => '执行代码错误',
			'600' => '客户端调用错误',
			'700' => '数据库出错',
			'900' => '引用延迟',
			'800' => '银行卡交易代码请求错误，请填写正确的银行卡信息',
			'400' => '商户权限不足',
			'300' => '停留时间太长，请重新登录！',
			'404' => '请求功能不存在',
			'505' => '用户信息已被异地篡改，请重新登录',
			'200' => '自定义错误',
			'100' => '商户权限不足'
		);
		if($retcode!='200' && $retcode!='0' && $retcode!='300')
		{
		   $retmsg = $arr_retmsg[$retcode];
		}else if($retcode!='700' or $retcode!='800')
		{
			$retmsg = $msg_body['msgbody']['message'];
		}
		if($g_sdcrid>='100')
		{
		  $req_bkenv  = '01';  //测试环境
		}else
		{
		   $req_bkenv = '00';  //正式环境
		}
		$msg_header = array ('msgheader' => array (
					'au_token'  => $au_token,
					'req_token' => $req_token ,
					'req_bkenv' => $req_bkenv,
					'retinfo' => array (
						'rettype' => $retcode,
						'retcode' => $retcode,
						'retmsg' => $retmsg
					)));
		$Responsecontext['operation_response'] = array_merge($msg_header, $msg_body);
		
		$rqvalue = xml_encode($Responsecontext, 'utf-8');
		//$returnval ="";
        $reqcry= TfbAuthRequest::getReqDatatype($reqcontext); //请求的数据流 
        $TfbAuthRequest = new TfbAuthRequest();
		if ($reqcry== 'E') {
			$returnval = $TfbAuthRequest->desEncryptStr($rqvalue);
		} else {
			$returnval = $rqvalue;
		}
		$authorid=$authorid+0;
		$file = "../../".CONST_LOGDIR."/" . date('md')."-" .$authortruename. "log" . ".txt";
		$filehandle=fopen($file, "a"); 
		fwrite($filehandle,"\r\n======响应内容：\r\n".$rqvalue."\r\n\r\n".$returnval."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n"); 
		fclose($filehandle);
	    return $returnval;
	}	
}
/*
 * 日志文件管理
 */
class tfblog {
	public static function getlogid($func, $logmem, $authorid,$returnmem) {

		$db = new DB_test();
		$query = "insert into tb_log (fd_log_time ,fd_log_func,fd_log_memo,fd_log_authorid,fd_log_returnmemo)" .
				" values(now() ,'$func' ,'$logmem','$authorid','$returnmem')";
		$db->query($query);
		$G_logid = $db->insert_id();
		return true;
	}
	public static function getreturnlog($logval) {
		global $G_logid;
		$db = new DB_test();
		$query = " update tb_log set  where fd_log_id = '$G_logid'";
		$db->query($query);
		return true;

	}

}
/*
 * retcode   0 = 成功  500 = 执行代码错误  600 = 客户端调用错误  700 = 数据库出错 900 = 引用延迟  800 = 请求支付接口错误  400 = 商户权限不足  300 = 离开时间太长，请重新登录
 * 
 * 错误自动处理
 */
class ErrorReponse  {
	public $debug = false; // 是否在Debug模式，为true的时候会打印出请求内容和相同的头部  
	function __construct() {
	}
 
	function reponError($Error) {
		$arr = array (
					'msgbody' => array (
					'result' => 'failure',
					'message' => $Error['retmsg']));
     
		$returnval = TfbxmlResponse :: ResponsetoApp($Error['retcode'], $arr);
		echo $returnval;
		exit;
		
	}
	


}
//自动加载类文件
class Loader {
	public static function loadClass($class) {
		$file = $class . '.php';
		if (is_file($file)) {
			require_once ($file);
		}
	}
}
?>