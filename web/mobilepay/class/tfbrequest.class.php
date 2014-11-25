<?php
require_once ("autoken.class.php");

$req_token = ""; // 授权码
$au_token = ""; // 动态码 
$req_version = ""; // 版本号
$req_bkenv = ""; // 银联环境
$req_time = "";
$authorid = "";
$api_name = "";
$api_name_func = "";
$authorid ="";
$authortruename = "";
$g_sdcrid = "";
//授权码-动态码验证处理 调用接口处理前期方法
class TfbAuthRequest {
	public $keepcontext = true; // 是否维持会话  
	public $Security; // 加密解密   
	public $Bytes;    // Btypes数组处理  
	public $AuToken;  // 授权问题 
	public $ErrorReponse; // 出错处理 

	public $debug = false; // 是否在Debug模式，为true的时候会打印出请求内容和相同的头部  

	function __construct() {
		$this->Security = new Security();
		$this->Bytes = new Bytes();
		$this->AuToken = new AuToken();
		$this->ErrorReponse = new ErrorReponse();
	}
	//检查数据源并返回原文
	function getReqContext($reqcontext) {

		$key0 = substr($reqcontext, 0, 1);
		if (is_numeric($key0)) {
			$returnvalue = $this->DesDecryptStr($reqcontext);
		} else {
			$returnvalue = $reqcontext;
		}
		return $returnvalue;
	}
	//检查数据源类型以密文传送过来还是原文传送过来的
	public static function getReqDatatype($reqcontext) {

		$key0 = substr($reqcontext, 0, 1);
		if (is_numeric($key0)) {
			$ReqDataType = 'E'; //密文

		} else {
			$ReqDataType = 'O'; //原文

		}
		return $ReqDataType;
	}
	//AES解密方法
	function DesDecryptStr($reqcontext) {

		$key = substr($reqcontext, 0, 1);
		$length = strlen($reqcontext); //strlen()：  
		$data = substr($reqcontext, 1, ($length -1));
		$keyarr = unserialize(base64_decode(Security :: $ARR_PASSKEY));
		$keyvalue = $this->Bytes->toStr($keyarr[$key]);
		//echo $data;
		return str_replace("", "", $this->Security->decrypt(trim($data), $keyvalue));
		//return $returnd;

	}
	//AES加密方法
	public function desEncryptStr($reqcontext) {
		$keyarr = unserialize(base64_decode(Security :: $ARR_PASSKEY));
		$key = (int)array_rand($keyarr, 1);
		$keyvalue = $this->Bytes->toStr($keyarr[$key]);
		return trim($key).$this->Security->encrypt($reqcontext, $keyvalue);
	}
	/*
	 * 授权信息验证分为以下内容
	 * 1.注册用户直接返回 true
	 * 2.认证用户校验
	 * 3.授权码验证（授权码为唯一标识符，并且用于用户信息是否变更，一旦被第三方登录或者变更则提示失效重新登录）
	 * 4.动态码验证（时效性校验）
	 * 5.刷卡器是否激活使用验证
	 * 
	 */
	//授权信息验证
	function apiAutoken($xml) {   
		global $req_token; // 授权码
		global $au_token; // 动态码 
		global $req_version; // 版本号
		global $req_bkenv; // 银联环境
		global $req_time;
		global $api_name;
		global $api_name_func;
		global $authorid ;
		global $arr_limitauthorid;

		$Publiccls = new PublicClass(); //初始化类实例 
		$arr_xml = $Publiccls->xml_to_array($xml);
		$api_name = $arr_xml['operation_request']['msgheader']['channelinfo']['api_name'];
		$api_name_func = $arr_xml['operation_request']['msgheader']['channelinfo']['api_name_func'];
		$req_token  = $arr_xml['operation_request']['msgheader']['req_token'];
		$req_time   = $arr_xml['operation_request']['msgheader']['req_time'];
		$au_token   = $arr_xml['operation_request']['msgheader']['au_token'];
		$req_version= $arr_xml['operation_request']['msgheader']['req_version'];
		$req_bkenv  = $arr_xml['operation_request']['msgheader']['req_bkenv'];
		$req_appenv = $arr_xml['operation_request']['msgheader']['req_appenv']; //1:安卓_phone 2:安卓_pad 3:iphone  4:ipad 
		$req_appevn = $arr_xml['operation_request']['msgheader']['req_appevn']; //1:安卓_phone 2:安卓_pad 3:iphone  4:ipad 
		$authorid   = $arr_xml['operation_request']['msgheader']['channelinfo']['authorid'];
		$req_time   = $arr_xml['operation_request']['msgheader']['req_time'];
		//$authorid = $arr_xml['operation_request']['msgheader']['channelinfo']['authorid'];
		
		// 需要开通-cai 
		if(!in_array($authorid, $arr_limitauthorid))
		{
        	//return true;
		}
		$desreqtoken = $this->DesDecryptStr($req_token);   //授权码解密
		$reqtokenss    = $this->checkauthorexists($desreqtoken,$authorid);     //授权码验证 授权码登录验证- 功能权限验证
		if($api_name!='ApiAuthorInfo' && $api_name!='ApiAuthorReg' && $api_name!='ApiAppInfo' && $api_name!='ApiAuthorInfoV2' && $api_name!='ApiSafeGuard')
		{
		$app_env = $this->getappnav($req_version,$req_appenv,$req_appevn);
		}
		if($api_name!='ApiAuthorInfo' && $api_name!='ApiAuthorReg' && $api_name!='ApiAppInfo' && $api_name!='ApiAuthorInfoV2' && $api_name!='ApiSafeGuard')
		{
			//$app_env = $this->getappnav($req_version,$req_appenv,$req_appevn);
			$checktokel=$this->AuToken->checkAuToken($au_token,$api_name_func);
			
		}
		$arr_checkloginMod = AuToken :: checkLoginMod($api_name, $api_name_func);   //检验登录接口还是非登录接口  

		switch($arr_checkloginMod['result'])
		{
			case 'success':   //需要登录验证
			
			if(!$arr_checkloginMod['ischeck']) return true;  //注册用户直接返回true的功能
			if ($arr_checkloginMod['ischeck'] == '1') //调用该方法需要验证授权码和动态码 
				{
				$desreqtoken = $this->DesDecryptStr($req_token);   //授权码解密		
				
				$reqtoken = $this->AuToken->checkReqToken($desreqtoken, $au_token,$api_name, $api_name_func, $authorid); //授权码验证 授权码登录验证- 功能权限验证
				
                return true;
			} 
			break;
			default:  //没找到相应的模块直接出错反馈 
			$Error = array (
				'result'  => 'failure',
				'retcode' => '404',
				'retmsg'  => '客户端调用错误'
			);
			$returnvalue = $this->ErrorReponse->reponError($Error);
			break;
		}
	}
	public function checkauthorexists($reqtoken,$authorid) //验证授权码
	{
		$db = new DB_test();
		if ($reqtoken) {
			$arr_auval = explode("@@", $reqtoken);
		} 
		//授权码转为数组{mac地址@@登录时手机时间@@登录名@@密码}  
		if (is_array($arr_auval)) {
			$aumobile = trim($arr_auval[2]);
			$aupwd = md5(trim($arr_auval[3]));
		} 
		if($authorid>0)
		{
		$query = "select fd_author_id as authorid,fd_author_state as state,fd_author_truename as truename,fd_author_username as username " .
				 "from tb_author where  fd_author_id= '$authorid'";
				 //echo $query;
		if ($db->execute($query)) {
			return true;
			//$arr_author = $db->get_one($query);
			//$authortruename = g2u($arr_author['username']);
		} else {
			$Error = array (
				'result' => 'failure',
				'retcode' => '300',
				'retmsg' => '用户信息异常，请重新登录！'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
		}
		}else
		{
			return true;
		}
		
	}
 function getappnav($req_version,$req_appenv,$req_appevn)
 {
 	$db = new DB_test();
 	if($req_appevn<>'')
 	{
 		$req_appenv = $req_appevn;
 	}
 	if($req_version=='')
 	{
 		return true;
 	}
 	if($req_appenv<3)
 	{
 	$query = "select * from tb_version where fd_version_apptype= '1' and fd_version_no <= '$req_version'";
	if($db->execute($query))
	{
		return true;
 	}else
 	{
 		   $Error = array (
				'result' => 'failure',
				'retcode'=> '200',
				'retmsg' => '您使用的是旧版本程序，请下载【通过菜单-》更多-》版本更新】最新本！'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
 		
 	}
 	}
 }
 	
	
}
//Bytyes数组处理
class Bytes {

	/**
	  * 转换一个String字符串为byte数组
	  * @param $str 需要转换的字符串
	  * @param $bytes 目标byte数组
	  * @author Zikie
	  */

	public static function  getBytes($str) {

		$len = strlen($str);
		$bytes = array ();
		for ($i = 0; $i < $len; $i++) {
			if (ord($str[$i]) >= 128) {
				$byte = ord($str[$i]) - 256;
			} else {
				$byte = ord($str[$i]);
			}
			$bytes[] = $byte;
		}
		return $bytes;
	}

	/**
	  * 将字节数组转化为String类型的数据
	  * @param $bytes 字节数组
	  * @param $str 目标字符串
	  * @return 一个String类型的数据
	  */

	function toStr($bytes) {
		$str = '';
		foreach ($bytes as $ch) {
			$str .= chr($ch);
		}

		return $str;
	}

	/**
	  * 转换一个int为byte数组
	  * @param $byt 目标byte数组
	  * @param $val 需要转换的字符串
	  * @author Zikie
	  */

	public static function integerToBytes($val) {
		$byt = array ();
		$byt[0] = ($val & 0xff);
		$byt[1] = ($val >> 8 & 0xff);
		$byt[2] = ($val >> 16 & 0xff);
		$byt[3] = ($val >> 24 & 0xff);
		return $byt;
	}

	/**
	  * 从字节数组中指定的位置读取一个Integer类型的数据
	  * @param $bytes 字节数组
	  * @param $position 指定的开始位置
	  * @return 一个Integer类型的数据
	  */

	public function bytesToInteger($bytes, $position) {
		$val = 0;
		$val = $bytes[$position +3] & 0xff;
		$val <<= 8;
		$val |= $bytes[$position +2] & 0xff;
		$val <<= 8;
		$val |= $bytes[$position +1] & 0xff;
		$val <<= 8;
		$val |= $bytes[$position] & 0xff;
		return $val;
	}

	/**
	 * 转换一个shor字符串为byte数组
	 * @param $byt 目标byte数组
	 * @param $val 需要转换的字符串
	 * @author Zikie
	 */

	public function shortToBytes($val) {
		$byt = array ();
		$byt[0] = ($val & 0xff);
		$byt[1] = ($val >> 8 & 0xff);
		return $byt;
	}

	/**
	  * 从字节数组中指定的位置读取一个Short类型的数据。
	  * @param $bytes 字节数组
	  * @param $position 指定的开始位置
	  * @return 一个Short类型的数据
	  */

	public static function bytesToShort($bytes, $position) {
		$val = 0;
		$val = $bytes[$position +1] & 0xFF;
		$val = $val << 8;
		$val |= $bytes[$position] & 0xFF;
		return $val;
	}

}
//加密处理
class Security {
	static $ARR_PASSKEY = 'YToxMDp7aTowO2E6MTY6e2k6MDtpOjExNDtpOjE7aToxMTk7aToyO2k6MTAzO2k6MztpOjY4O2k6NDtpOjEwNTtpOjU7aToxMTM7aTo2O2k6Nzc7aTo3O2k6NTE7aTo4O2k6NTA7aTo5O2k6MTE4O2k6MTA7aTo1MztpOjExO2k6NjQ7aToxMjtpOjU3O2k6MTM7aTo2NztpOjE0O2k6ODI7aToxNTtpOjMzO31pOjE7YToxNjp7aTowO2k6ODQ7aToxO2k6OTg7aToyO2k6Njc7aTozO2k6ODk7aTo0O2k6MzY7aTo1O2k6ODE7aTo2O2k6NzE7aTo3O2k6MTA5O2k6ODtpOjExNTtpOjk7aTozNztpOjEwO2k6MTEyO2k6MTE7aTo5MDtpOjEyO2k6NTQ7aToxMztpOjM4O2k6MTQ7aTo5OTtpOjE1O2k6MTE0O31pOjI7YToxNjp7aTowO2k6MTAxO2k6MTtpOjExOTtpOjI7aTo1NjtpOjM7aTo3ODtpOjQ7aTo1NTtpOjU7aTo4MDtpOjY7aToxMDY7aTo3O2k6ODc7aTo4O2k6MTE3O2k6OTtpOjEwNztpOjEwO2k6NjQ7aToxMTtpOjQ5O2k6MTI7aTozMztpOjEzO2k6Njg7aToxNDtpOjUxO2k6MTU7aTo4Njt9aTozO2E6MTY6e2k6MDtpOjU3O2k6MTtpOjEwOTtpOjI7aTo1NTtpOjM7aTo3NDtpOjQ7aTo4NztpOjU7aTo5NztpOjY7aTo4MDtpOjc7aTo3MjtpOjg7aTo0MjtpOjk7aToxMTI7aToxMDtpOjExMDtpOjExO2k6NzM7aToxMjtpOjU2O2k6MTM7aTo2NjtpOjE0O2k6MTA2O2k6MTU7aTozNjt9aTo0O2E6MTY6e2k6MDtpOjM3O2k6MTtpOjgyO2k6MjtpOjY3O2k6MztpOjcwO2k6NDtpOjExNTtpOjU7aTo4NztpOjY7aTo5OTtpOjc7aTo2NjtpOjg7aTo1MDtpOjk7aToxMDA7aToxMDtpOjY1O2k6MTE7aToxMjI7aToxMjtpOjcxO2k6MTM7aTo4NTtpOjE0O2k6NzI7aToxNTtpOjEyMTt9aTo1O2E6MTY6e2k6MDtpOjcyO2k6MTtpOjExNTtpOjI7aToxMDA7aTozO2k6NTY7aTo0O2k6NTI7aTo1O2k6MTE5O2k6NjtpOjgwO2k6NztpOjEyMjtpOjg7aTo1MztpOjk7aToxMDE7aToxMDtpOjExMztpOjExO2k6MTEyO2k6MTI7aToxMDM7aToxMztpOjY0O2k6MTQ7aTo4ODtpOjE1O2k6MTA2O31pOjY7YToxNjp7aTowO2k6Njg7aToxO2k6OTg7aToyO2k6MzM7aTozO2k6MzY7aTo0O2k6NTM7aTo1O2k6MTA0O2k6NjtpOjY1O2k6NztpOjgzO2k6ODtpOjQ4O2k6OTtpOjc4O2k6MTA7aTo1MTtpOjExO2k6NTc7aToxMjtpOjkwO2k6MTM7aTo4NTtpOjE0O2k6NzM7aToxNTtpOjgwO31pOjc7YToxNjp7aTowO2k6MTE5O2k6MTtpOjUxO2k6MjtpOjU2O2k6MztpOjY1O2k6NDtpOjY5O2k6NTtpOjUzO2k6NjtpOjg5O2k6NztpOjQyO2k6ODtpOjEwMDtpOjk7aTo3ODtpOjEwO2k6MTA3O2k6MTE7aTo4NTtpOjEyO2k6Nzk7aToxMztpOjEwNTtpOjE0O2k6ODc7aToxNTtpOjMzO31pOjg7YToxNjp7aTowO2k6NzQ7aToxO2k6OTk7aToyO2k6MTEyO2k6MztpOjg3O2k6NDtpOjcwO2k6NTtpOjY5O2k6NjtpOjEyMTtpOjc7aTo0OTtpOjg7aTo3NjtpOjk7aTo1MztpOjEwO2k6MTAxO2k6MTE7aTo3MztpOjEyO2k6NTI7aToxMztpOjc5O2k6MTQ7aTozODtpOjE1O2k6MTAzO31pOjk7YToxNjp7aTowO2k6Njc7aToxO2k6NTc7aToyO2k6MTA1O2k6MztpOjEwNjtpOjQ7aToxMTA7aTo1O2k6NzI7aTo2O2k6MTE3O2k6NztpOjc2O2k6ODtpOjY4O2k6OTtpOjEwMTtpOjEwO2k6MTAwO2k6MTE7aTozMztpOjEyO2k6NTE7aToxMztpOjU1O2k6MTQ7aTozODtpOjE1O2k6ODY7fX0=';
	public static function encrypt($input, $key) {

		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);

		$input = Security :: pkcs5_pad($input, $size);

		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');

		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

		mcrypt_generic_init($td, $key, $iv);

		$data = mcrypt_generic($td, $input);

		mcrypt_generic_deinit($td);

		mcrypt_module_close($td);

		$data = base64_encode($data);

		return $data;

	}

	private static function pkcs5_pad($text, $blocksize) {

		$pad = $blocksize - (strlen($text) % $blocksize);

		return $text . str_repeat(chr($pad), $pad);

	}

	public static function decrypt($sStr, $sKey) {

		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');

		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

		$decrypted = @ mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, base64_decode($sStr), MCRYPT_MODE_ECB, $iv);

		$dec_s = strlen($decrypted);

		$padding = ord($decrypted[$dec_s -1]);

		$decrypted = substr($decrypted, 0, - $padding);

		return $decrypted;

	}
	public static function desencrypt($string, $operation, $key = '') //des加密解密方法，用于动态码
	{
		$key = md5($key);
		$key_length = strlen($key);
		$string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
		$string_length = strlen($string);
		$rndkey = $box = array ();
		$result = '';
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}
		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a +1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if ($operation == 'D') {
			if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
				return substr($result, 8);
			} else {
				return '';
			}
		} else {
			return str_replace('=', '', base64_encode($result));
		}
	}


}

?>