<?php

class Ofpay {
	//private $expressname = array (); //封装了
    public $Publiccls; //初始化类实例

	function __construct() {
		//$this->expressname = $this->expressname ();
        $this->Publiccls = new PublicClass();
    }
	
	/*
	 * 采集网页内容的方法
	 */
	private function getcontent($url) {
		if (function_exists ( "file_get_contents" )) {
			$file_contents = file_get_contents ( $url );
		} else {
			$ch = curl_init ();
			$timeout = 5;
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
			$file_contents = curl_exec ( $ch );
			curl_close ( $ch );
		}
		return $file_contents;
	}

	
	/*
	 * 解析object成数组的方法
	 * @param $json 输入的object数组
	 * return $data 数组
	 */
	private function json_array($json) {
		if ($json) {
			foreach ( ( array ) $json as $k => $v ) {
				$data [$k] = ! is_string ( $v ) ? $this->json_array ( $v ) : $v;
			}
			return $data;
		}
	}

	public function mobilerecharge($phone,$cardnum) {
        $userid = "A942987";                    // 商户编号
        $userpws=md5("tfbao20140603");     //密码md5(******);
        $userpws1=("tfbao20140603");     //密码md5(******);
        $cardid ="140101";                     //快充值编号
        $cardnum=$cardnum+0;                          //充值面额 10，20，30，50，100，300
        $mctype= "";                            //慢充使用参数 此处不用
        $sporder_id = "tfb".time();             //商家订单号 （自定义设置）
        $sporder_time = date("YmdHis");
        $game_userid  = $phone;         //手机号码
        $md5_str  = strtoupper(md5($userid.$userpws.$cardid.$cardnum.$sporder_id.$sporder_time.$game_userid."OFCARD"));
        $ret_url  = "";                         //可以返回空
        $version  = '6.0';                      //默认等于6.0即可

        $gourl = "http://api2.ofpay.com/onlineorder.do?userid=$userid&userpws=$userpws&cardid=$cardid&cardnum=$cardnum&sporder_id=$sporder_id&sporder_time=$sporder_time&game_userid=$game_userid&md5_str=$md5_str&ret_url=&version=6.0";
        $result = $this->getcontent ( $gourl );
        $arr_xml =$this->Publiccls->xml_to_array($result);
        $arr_xml['ofreqcontent'] = $gourl;
        $arr_xml['ofanscontent'] = $result;


       // echo $arr_xml['orderinfo']['retcode'];
        $file="../../".CONST_LOGDIR."/".date('Y-m-d')."手机充值-log".".txt";
        $filehandle=fopen($file, "a");
        fwrite($filehandle,"\r\n======手机充值响应内容：\r\n".$gourl."\r\n\r\n返回值:".serialize($arr_xml)."\r\n\r\n".g2u($result)."<!----结束------------>\r\n\r\n\r\n");
        fclose($filehandle);

		return $arr_xml;
	}

    public function qqrecharge($qq,$cardnum) {
        $userid = "A942987";                    // 商户编号
        $userpws=md5("tfbao20140603");     //密码md5(******);
        $userpws1=("tfbao20140603");     //密码md5(******);
        $cardid ="220612";                     //快充值编号
        $cardnum=$cardnum+0;                          //充值面额 1,2,4,6,10，20，30，50，100，300
        $mctype= "";                            //慢充使用参数 此处不用
        $sporder_id = "tfb".time();             //商家订单号 （自定义设置）
        $sporder_time = date("YmdHis");
        $game_userid  = $qq;                     //qq号码
        $game_srv     = "";                     //游戏使用到
        $game_area     = "";                    //游戏使用到
        $md5_str  = strtoupper(md5($userid.$userpws.$cardid.$cardnum.$sporder_id.$sporder_time.$game_userid."OFCARD"));
        $ret_url  = "";                         //可以返回空
        $version  = '6.0';                      //默认等于6.0即可

        $gourl = "http://api2.ofpay.com/onlineorder.do?userid=$userid&userpws=$userpws&cardid=$cardid&cardnum=$cardnum&sporder_id=$sporder_id&sporder_time=$sporder_time&game_userid=$game_userid&game_area=$game_area&game_srv=$game_srv&md5_str=$md5_str&ret_url=&version=6.0";
        $result = $this->getcontent ( $gourl );
        $arr_xml =$this->Publiccls->xml_to_array($result);
        $arr_xml['ofreqcontent'] = $gourl;
        $arr_xml['ofanscontent'] = $result;

        // echo $arr_xml['orderinfo']['retcode'];
        $file="../../".CONST_LOGDIR."/".date('Y-m-d')."qq充值-log".".txt";
        $filehandle=fopen($file, "a");
        fwrite($filehandle,"\r\n======qq充值响应内容：\r\n".$gourl."\r\n\r\n返回值:".serialize($arr_xml)."\r\n\r\n".g2u($result)."<!----结束------------>\r\n\r\n\r\n");
        fclose($filehandle);

        return $arr_xml;
    }

}
?>
