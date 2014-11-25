<?php
class ApiAppInfo  extends TfbxmlResponse {
	//版本号管理		    
	public function checkAppVersion() {
		$db = new DB_test;
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$apptype = g2u(trim($arr_body['apptype']));
		$appversion = g2u(trim($arr_body['appversion']));

		$query = "select * from tb_version where fd_version_apptype= '$apptype' order by fd_version_updatetime desc";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$apptype = $apptype;
			$appdownurl = $db->f(fd_version_downurl);
			$appisnew = $db->f(fd_version_isnew);
			switch ($apptype){ 
	    case "2":
	     $returnversion = TfbxmlResponse :: returnCurl("http://itunes.apple.com/lookup?id=740276926");
	     $appnewversion = $returnversion["results"][0]["version"];
	    break;
	    default:
	    	$appnewversion = $db->f(fd_version_no);
	     break;
	     }

			$clearoldinfo = $db->f(fd_version_clearoldinfo);
			$appnewcontent = $db->f(fd_version_newcontent);
			$appstrupdate = $db->f(fd_version_strupdate);

			$arr_msg['msgbody']['apptype'] = g2u(trim($apptype));
			$arr_msg['msgbody']['appdownurl'] = g2u(trim($appdownurl));
			$arr_msg['msgbody']['appisnew'] = trim($appisnew);
			$arr_msg['msgbody']['appnewversion'] = g2u(trim($appnewversion));
			$arr_msg['msgbody']['clearoldinfo'] = trim($clearoldinfo);
			$arr_msg['msgbody']['appnewcontent'] = g2u(trim($appnewcontent));
			$arr_msg['msgbody']['appstrupdate'] = trim($appstrupdate);
			$str_appnewversion = str_replace(".","",$appnewversion);
			$str_appversion = str_replace(".","",$appversion);
			if ($str_appnewversion <= $str_appversion) {
				$arr_message = array (
					"result" => "failure",
					"message" => "当前版本【".$appnewversion."】是最新版本!"
				);
				//$arr_msg['msgbody']['appisnew'] = trim($appisnew);
				$retcode = "200";  //反馈状态 0 成功 200 自定义错误
			} else  {
				$arr_message = array (
					"result" => "success",
					"message" => "您有新的版本[".$appversion."]更新!"
				);
				$retcode = "0";  //反馈状态 0 成功 200 自定义错误
			}
		} else {
			$arr_message = array (
				"result" => "failure",
				"message" => "接口错误，请查证!"
			);
			$retcode = "200";
		}

		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['chargeWithoutLogin'] = 1;

		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 
	}
	//读取银行列表		    
	public function readBankList() {
		$db = new DB_test;
		
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$activemobilesms  = trim($arr_body['activemobilesms']);
		$querysql = trim(u2g(trim($arr_body ['querywhere'])));
		//$appversion = trim($arr_body['appversion']);
		$msgstart = $arr_body ['msgstart'] + 0;
		$msgdisplay = $arr_body ['msgdisplay'] + 0;
		
		if ($msgstart < 0)
			$msgstart = 0;
		if($msgdisplay==0) $msgdisplay=40;
			
    if($activemobilesms)
    {
    	$querywhere = " and fd_bank_activemobilesms = '1'";
    }
    if($querysql!="")
    {
    	$querywhere .= " and fd_bank_name like '%$querysql%'";
    	
    }
    	$query = "select  1 from tb_bank where fd_bank_active = '1' $querywhere  ";
		$db->query ( $query );
		$msgallcount = $db->nf ();
		if($msgstart>=$msgallcount)
		{
			$msgstart = $msgallcount-$msgdisplay;
		    if ($msgstart < 0)
			$msgstart = 0;
		}
		$query = "select fd_bank_id as bankid,fd_bank_no as bankno,fd_bank_name as bankname from 
			       tb_bank where fd_bank_active = '1' $querywhere order by fd_bank_no asc limit $msgstart, $msgdisplay "; //只显示激活的银行列表 
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
        $msgdiscount = $db->nf();
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "查询银行列表数据为空!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "读取成功!"
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg ['msgbody'] ['msgallcount'] = $msgallcount;
		$arr_msg ['msgbody'] ['msgdiscount'] = $msgdiscount + $msgstart;
		$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']		
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval; 
	}
	//读取首页广告		    
	public function readIndexAdList() {
		$db = new DB_test;
		
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;

		$query = "select fd_adpic_title as adtitle,fd_adpic_linkurl as adlinkurl,fd_adpic_picurl as adpicurl, 
			          fd_adpic_no as adno  from 
			          tb_adpic where fd_adpic_active = '1'  and fd_adpic_type = '1' order by  fd_adpic_no asc"; //首页广告显示
		$db->query($query);
		$alladcount = $db->nf();
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
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
		$arr_msg['msgbody']['adallcount'] = $alladcount;
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
		
		);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);return $returnval; 
	}

	public function readAppruleList() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$appruleid = $arr_body['appruleid'];
		$query = "select fd_apprules_id as appruleid,fd_apprules_title as appruletitle, fd_apprules_content as apprulecontent,fd_apprules_updatetime as updatetime from tb_apprules where fd_apprules_id = '$appruleid'";
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		if (!$arr_msg) {
			$arr_message = array (
				"result" => "failure",
				"message" => "没有数据!"
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
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);return $returnval; 
	}
	public function readMenuModule() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$paycardkey = $arr_body['paycardkey'];
        $authorid   = $arr_channelinfo['authorid'];
		$appversion = $arr_body['appversion']+0;
		$query = "select fd_appmnu_id as mnuid,fd_appmnu_no as mnuno, fd_appmnu_name as mnuname,
				fd_appmnu_pic as mnupic,fd_appmnu_order as mnuorder, fd_appmnu_url as mnuurl ,
				fd_appmnu_version as mnuversion,fd_amtype_id as mnutypeid,
				fd_amtype_name as mnutypename,
				case
              when fd_appmnuc_count is null then '0'
              else fd_appmnuc_count  END  pointnum,
				fd_appmnu_isconst as mnuisconst
				from tb_appmenu  join tb_appmenutype on  fd_amtype_id = fd_appmnu_amtypeid
				left join tb_appmenucout on fd_appmnu_id = fd_appmnuc_appmnuid
				and fd_appmnuc_authorid = '$authorid'  where fd_appmenu_active = '1' order by fd_appmnu_order
				";
		$db->query($query);
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		$arr_one = $db->get_one($query);
		$version = $arr_one['mnuversion'] ;
		if ($version>$appversion) {
			$arr_message = array (
				"result" => "success",
				"message" => "有新的功能更新哦！",
				"version" => $version,
				"isnew" => '1'
			);
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "目前功能最新",
				"version" => $version,
				"isnew" => '0'
			);
			$arr_msg = "";
			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['version'] = $arr_message['version'];
		$arr_msg['msgbody']['isnew'] = $arr_message['isnew'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);return $returnval; 
	}
	
		public function readQueryCardMoney() {
		$db = new DB_test();
		$arr_header      = $this->arr_header;
		$arr_body        = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bankcardno = $arr_body['bankcardno'];
		$bankid = $arr_body['bankid'];
		$bankname = $arr_body['bankname'];
		
		$lastfour = substr ($bankcardno, -4);
		$query = "select fd_bank_sendmesage as smsmsg ,fd_bank_smsphone as smsphone from 
			       tb_bank where fd_bank_id = '$bankid'"; //只显示激活的银行列表 
		$db->query($query);
		if($db->nf())
		{
			$db->next_record();
			$smsmsg = $db->f('smsmsg');
			$smsphone = $db->f('smsphone');
		}
		
		if (!$db->nf()) {
			$arr_message = array (
				"result" => "failure",
				"message" => "暂不支持该银行手机查询余额业务",
				"retcode" => "200"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		} else {
			$arr_message = array (
				"result" => "success",
				"message" => "成功获取余额查询短信码!",
				"retcode" => "0"
 			);
 			$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}
		$smsmsg = str_replace("%f",$lastfour,$smsmsg);
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['smsmsg'] = g2u($smsmsg);
		$arr_msg['msgbody']['smsphone'] = $smsphone;
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);

		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval; 
	}

    public function authorMenuCount() {
        $db = new DB_test();
        $arr_header      = $this->arr_header;
        $arr_body        = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $appmnuid = $arr_body['appmnuid'];
        $authorid = $arr_channelinfo['authorid'];


        $query = "select fd_appmnuc_count as count from tb_appmenucout where fd_appmnuc_authorid = '$authorid'
                and fd_appmnuc_appmnuid = '$appmnuid' ";
        $db->query($query);
        if($db->nf())
        {
            $arr_val = $db->get_one($query);
            $count = $arr_val['count'];

            $querywhere = "  fd_appmnuc_authorid = '$authorid'
                and fd_appmnuc_appmnuid = '$appmnuid'";


            $dateArray['fd_appmnuc_count'] = $count+1;
            $db->update("tb_appmenucout", $dateArray,$querywhere);

        }else
        {
            $dateArray['fd_appmnuc_authorid'] = $authorid;
            $dateArray['fd_appmnuc_appmnuid'] = $appmnuid;
            $dateArray['fd_appmnuc_count'] = $memo;
            $db->insert("tb_appmenucout", $dateArray);
        }

        $listid = $db->insert_id(); //取出刚插入的记录的主关键值的id


            $arr_message = array (
                "result" => "success",
                "message" => "插入数据成功!",
                "retcode" => "0"
            );
            $retcode = "0";  //反馈状态 0 成功 200 自定义错误


        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];

        $returnvalue = array ("msgbody" => $arr_msg['msgbody']);

        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }




}
?>