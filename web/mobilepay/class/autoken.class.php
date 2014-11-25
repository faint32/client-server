<?php
class AuToken {

	public $activetimeout; // 默认的超时为3000s
	public $ErrorReponse; // 出错处理 
	public $debug = false; // 是否在Debug模式，为true的时候会打印出请求内容和相同的头部  

	function __construct() {

		$this->ErrorReponse = new ErrorReponse();
		$this->activetimeout = 3000; // 默认的超时为3000s

	}

	public static function getAuthorinfo($req_token) {
		$myname_extend = new DB_Autoken();
		$authorinfo = $myname_extend->DesDecryptStr($req_token);
		$arr_author[] = explode("@@", $authorinfo);
		return $arr_author;
	}
	/*
	 * 检测检验的接口是否需要登录返回数组$arr_return
	 * 
	 * 
	 */
	public static function checkLoginMod($api_name, $api_name_func) //检查调用的功能是否需要登录验证 
	{
		$db = new DB_test();

		$query = "select fd_interface_ischeck as ischeck from web_test_interface where fd_interface_apiname = '$api_name' " .
		"and fd_interface_apinamefunc = '$api_name_func' ";

		if ($db->execute($query)) {
			$arr_val = $db->get_one($query);
			$arr_return = array (
				'result' => 'success',
				'ischeck' => $arr_val['ischeck']
			);
		} else {

			$arr_return = array (
				'result' => 'success',
				'ischeck' => '0'
			);
		}
		return $arr_return;
	}
	public function checkauthorModqx($api_name, $api_name_func) //检查该功能需要的用户权限 
	{
		$db = new DB_test();
		$query = "select fd_appmenu_authorstate as authorstate from web_test_interface" .
		" left join tb_appmenu on fd_interface_appmenuid  = fd_appmnu_id  where fd_interface_apiname = '$api_name' " .
		" and fd_interface_apinamefunc = '$api_name_func' ";
		if ($db->execute($query)) {
			$arr_val = $db->get_one($query);
			$arr_return = array (
				'result' => 'success',
				'authorstate' => $arr_val['authorstate'] + 0
			);
		} else {
			$arr_return = array (
				'result' => 'failure',
				'authorstate' => ''
			);
		}
		return $arr_return;
	}
	
	function checkReqToken($reqtoken, $autoken, $apiname, $apinamefunc,$authorid) //验证授权码
	{
		global $authortruename;
		//global $autorid;
		$db = new DB_test();

		$autoken = $this->checkAuToken($autoken,$apinamefunc); //验证动态码
		if ($reqtoken) {
			$arr_auval = explode("@@", $reqtoken);
		} //授权码转为数组{mac地址@@登录时手机时间@@登录名@@密码}  
		if (is_array($arr_auval)) {
			$aumobile = trim($arr_auval[2]);
			$aupwd = md5(trim($arr_auval[3]));
		} else {
			$Error = array (
				'result' => 'failure',
				'retcode' => '300',
				'retmsg' => '授权码调用错误，请重新登录授权'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
		}

		$arr_authorqx = $this->checkauthorModqx($apiname, $apinamefunc); //该接口需要认证用户还是注册用户使用

		$query = "select fd_author_id as authorid,fd_author_state as state,fd_author_truename as truename,fd_author_username as username from tb_author where fd_author_username  = '$aumobile' " .
		"  and fd_author_isstop = '0' and fd_author_password = '$aupwd' ";
		if ($db->execute($query)) {
			$arr_author = $db->get_one($query);
			$authortruename = g2u($arr_author['username']);
		} else {
			$Error = array (
				'result' => 'failure',
				'retcode' => '300',
				'retmsg' => '账户或者密码有误，为保证安全请重新登录'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
		}

		if ($arr_author['state'] >= $arr_authorqx['authorstate']) { //如果商户的状态大于要求状态则通过
			return true;
		} else {
			$Error = array (
				'result' => 'failure',
				'retcode' => '400',
				'retmsg' => '该功能你无权操作，认证用户方可使用。'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
		}

	}
	function checkAuToken($autoken,$func='') //验证动态码
	{
		global $au_token;
		$db = new DB_test();
		$now = strtotime(date("Y-m-d H:i:s"));
		$datatime = $this->activetimeout;
		if (!$autoken && $func == 'checkAuthorLogin') {
			$au_token = Security :: desencrypt($now, 'E', 'mstongfubao'); //加密
			return true;
		}
        //$autoken= 'ynITU8XBqKPJkwfh97J+0mID';
		$oldautoken = Security :: desencrypt($autoken, 'D', 'mstongfubao'); //解密
		$au_token = Security :: desencrypt($now, 'E', 'mstongfubao'); //加密
        if($autoken!="")
        {
		$outtime = ($now - $oldautoken);
        }else
        {
        	$Error = array (
				'result'  => 'failure',
				'retcode' => '200',
				'retmsg'  => '动态码为空，请检查客户端接口'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
        }
       // echo $outtime."-".$datatime;
		if ($outtime > $datatime) {
			$Error = array (
				'result'  => 'failure',
				'retcode' => '300',
				'retmsg'  => $datatime.'秒停留超时，请重新登录'
			);
			$this->ErrorReponse->reponError($Error); //出错反馈 
			exit;
		} else {
			return true;

		}
	}
	public static function getauthorusername($authorid) //验证授权码
	{

		$db = new DB_test();

		$query = "select fd_author_state as state,fd_author_truename as truename,fd_author_username as username," .
				" fd_author_memid as memid,fd_author_shoucardno as shoucardno,fd_author_shoucardman as shoucardman," .
				" fd_author_shoucardphone as shoucardmobile,fd_author_shoucardbank as shoucardbank from" .
		" tb_author where fd_author_id  = '$authorid' ";

		if ($db->execute($query)) {
			$arr_author = $db->get_one($query);
			$authortruename = g2u($arr_author['username']);

		}
		return $arr_author;
	}
}
?>