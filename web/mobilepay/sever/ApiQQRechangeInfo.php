<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-19
 * Time: 上午7:44
 */
/*
* 作者 Administrator
* 创建于 2014-04-19 上午7:44
* 描述：
*/


class ApiQQRechangeInfo extends TfbxmlResponse {
    //读取充值面额
    public function readRechaMoneyinfo() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];

        $query = "select fd_qqrecham_id as rechaMoneyid ,fd_qqrecham_money as rechamoney,
                  fd_qqrecham_paymoney as rechapaymoney ,fd_qqrecham_isdefault as rechaisdefault ,
                  fd_qqrecham_memo as rechamemo from tb_qqrechamoney where fd_qqrecham_active = '1'
			          order by fd_qqrecham_money";
        $db->query($query);
        $arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');


        $arr_message= array (
            "error_id" => "0",
            "result" => "success",
            "message" => "恭喜您,读取充值面额成功!"
        );
        $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $arr_msg['msgbody']['rechapersent'] = '100%';

        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;

    }

    //读取手机充值支付选项
    public function readRechaPayTypeinfo() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];

        $query = "select fd_rechapaytype_id as rechapaytypeid ,fd_rechapaytype_name as rechapayname,
                  fd_rechapaytype_memo as rechamemo ,fd_rechapaytype_active as rechaisactive
                   from tb_qqrechapaytype ";
        $db->query($query);
        $arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');


        $arr_message = array (
            "error_id" => "0",
            "result" => "success",
            "message" => "恭喜您,读取支付类型成功!"
        );
        $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );

        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }


    public function readRechacostmoney($money) {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];

        $query = "select   (fd_qqrecham_paymoney-fd_qqrecham_costmoney) as payfee from tb_qqrechamoney";
        if ($db->execute($query)) {
            $arr_merinfo = $db->get_one($query);
        }


        return $arr_merinfo['payfee'];
    }

    public function readQQhasRechamoney($qq) {  //计算QQ当前刷卡金额
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];
        $now = date('Y-m-d');
        $query = "select  sum(fd_mrclist_rechamoney) as remoney from tb_qqrechargelist
               where fd_mrclist_qq = '$qq' and fd_mrclist_payrq= '00' and fd_mrclist_paydate = '$now'";
        if ($db->execute($query)) {
            $arr_merinfo = $db->get_one($query);
        }
        return $arr_merinfo['remoney'];
    }


    //银行充值手机卡银联请求交易码
    function RechaMoneyRq() {
        global $weburl;
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']);
        $paytype = 'qqrecharge';
        $paycardid        = trim($arr_body['paycardid']);
        $paytypeid   = trim($arr_body['rechapaytypeid']); //支付类型id
        $rechamoney       = trim($arr_body['rechamoney']); //充值金额
		
        $rechapaymoney    = trim(u2g($arr_body['rechapaymoney']));  //实际支付金额
        $rechaqq      = trim(u2g($arr_body['rechaqq']));    //充值手机号码

        $qqhasrechamoney = $this->readQQhasRechamoney($rechaqq);
        $allqqremoney = $qqhasrechamoney+$rechamoney;
		if($allqqremoney > 5000)
		{
			$retcode = "200";
			$arr_msg['msgbody']['result'] = "failure";
			$arr_msg['msgbody']['message'] = "对不起，每个QQ号码每天累计充值Q币数不能大于5000!";
			$returnvalue = array (
				"msgbody" => $arr_msg['msgbody']
			);
			$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
			return $returnval;
		}


        $rechaqqprov  = trim($arr_body['rechaqqprov']);    //手机所属地区
        $rechabkcardno    = trim(u2g($arr_body['rechabkcardno'])); //银行卡号
        $rechabkcardid    = trim($arr_body['rechabkcardid']);      //银行卡关联id tb_banckcard.fd_bankcard_id
        $merReserved      = trim(u2g($arr_body['merReserved']));   //认证信息
       // $current = trim($arr_body['current']); //币种
       // $paycardid = trim(GetPayCalcuInfo::readpaycardid($paycardid)); //刷卡器设备号
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商
        $paycardkey       = trim($arr_paycard['paycardkey']); //刷卡器key
        //$feebankid = getbankid($shoucardbank); //获得银行id返回string
        $onepayfee  = $this->readRechacostmoney($rechamoney);
        $payfee  = $onepayfee*$rechamoney; //金额也就是数量
        $bkmoney = $rechapaymoney;

        $arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $rechabkcardno);
        $bkntno = trim($arr_bkinfo['bkntno']);
        $sdcrid = trim($arr_bkinfo['sdcrid']);

        $sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
        $sdcrpayfeemoney= (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);

        $paydate = date("Y-m-d H:i:s");
        $today   = date("Ymd");
        //$bkmoney  =
        $bkorderNumber = trim($arr_bkinfo['bkorderNumber']);

        $listno = makeorderno("qqrechargelist", "mrclist", "mrc");
        $datadetailArray['fd_mrclist_no']         = $listno;
        $datadetailArray['fd_mrclist_paycardid'] = $paycardid;
        $datadetailArray['fd_mrclist_authorid']  = $authorid;
        $datadetailArray['fd_mrclist_bkntno']     = $bkntno;
        $datadetailArray['fd_mrclist_paydate'] = $paydate;
        $datadetailArray['fd_mrclist_payrq'] = '01'; //刚请求的交易码状态为01
        $datadetailArray['fd_mrclist_paytypeid'] = $paytypeid; //手机充值支付类型 跟paytype 无任何关系
        $datadetailArray['fd_mrclist_paytype'] = $paytype;  //业务类型
        $datadetailArray['fd_mrclist_bkordernumber'] = $bkorderNumber;
        $datadetailArray['fd_mrclist_sdcrpayfeemoney'] = $sdcrpayfeemoney;

        $datadetailArray['fd_mrclist_sdcrid'] = $sdcrid;
        $datadetailArray['fd_mrclist_rechamoney'] = $rechamoney;
        $datadetailArray['fd_mrclist_bkmoney'] = $bkmoney;
        $datadetailArray['fd_mrclist_qq'] = $rechaqq;
        $datadetailArray['fd_mrclist_paymoney'] = $rechapaymoney;
        $datadetailArray['fd_mrclist_payfee'] = $payfee;
        $datadetailArray['fd_mrclist_qqprov'] = u2g($rechaqqprov);
        $datadetailArray['fd_mrclist_bankcardno'] = $rechabkcardno;
        $datadetailArray['fd_mrclist_bankcardid'] = $rechabkcardid ;
        $datadetailArray['fd_mrclist_bankcardbank'] = $rechabkcardname;
        $datadetailArray['fd_mrclist_date'] = $today;
        $datadetailArray['fd_mrclist_datetime'] = $paydate;
        $datadetailArray['fd_mrclist_cusid'] = $cusid;

        $db->insert("tb_qqrechargelist", $datadetailArray);


        $listid = $db->insert_id();
        //$listid = $db->insert_id();
       // $method = 'in';
       // $method = u2g($method);

        //$gettrue = AgentPayglist :: insertPayglist($this->reqxmlcontext, $bkntno, $listid, $ccgno, $paytype, $method, $arr_feeinfo);

        $arr_message = array (
            "result" => "success",
            "message" => "请求交易码成功!"
        );
        $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $arr_msg['msgbody']['bkntno']  = $bkntno;
        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }
    //银联交易成功
    public function checkRechaMoneyStatus() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $bkntno = trim($arr_body['bkntno']); //交易流水号
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $authorid = trim($arr_channelinfo['authorid']); //authorid
        $result = $arr_body['result'];
        $nowdate = date("Y-m-d H:i:s");

        //手机卡充值业务
        $query = "select fd_mrclist_bkordernumber as bkordernumber,fd_mrclist_authorid as authorid ,
                  fd_mrclist_date as orderdate ,DATE_FORMAT(fd_mrclist_date,'%Y%m%d') as orderTime,
                  fd_mrclist_qq as qq
                  from tb_qqrechargelist
                  where 1  and  fd_mrclist_bkntno = '$bkntno' and fd_mrclist_authorid = '$authorid'  limit 1";
        $arr_val= $db->get_one($query);

        $orderNumber =  $arr_val['bkordernumber'];
        $orderTime   =  $arr_val['orderTime'];

        $arr_returninfo = BankPayInfo :: bankorderquery($authorid,'',$orderNumber, $orderTime);
        $arr_returninfo =$this->Publiccls->xml_to_array($arr_returninfo);

      //  print_r($arr_returninfo);
       // exit;
       if(!is_array($arr_returninfo))
       {
           $arr_message = array (
               "result" => "failure",
               "message" => "请等待,正在为您充值中!"
           );
           $retcode = "200";  //反馈状态 0 成功 200 自定义错误
       }else
       {
           $of_retcode = $arr_returninfo['orderinfo']['retcode'];  //欧飞接口1为成功 跟我们自己的成功 0
           if($of_retcode==1)
           {
               $retcode = 0; //反馈状态 0 成功 200 自定义错误
               $arr_message = array (
                   "result" => "success",
                   "message" =>  $arr_returninfo['orderinfo']['err_msg']
               );
           }else
           {
               $retcode = $of_retcode; //反馈状态 0 成功 200 自定义错误
               $arr_message = array (
                   "result" => "failure",
                   "message" => $arr_returninfo['orderinfo']['err_msg']
               );
           }


       }
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];


        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );

        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }


    //转账汇款历史记录
    public function readQQRechangelist() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
        $authorid = trim($arr_channelinfo['authorid']); //操作者

        $msgstart = trim($arr_body['msgstart']) + 0;
        $msgdisplay = trim($arr_body['msgdisplay']) + 0;
        $paytype    = trim($arr_body['paytype']);
        $arr_state = array("请求交易","交易成功","交易取消","无效状态");
       // $arr_state = auto_charset($arr_state, 'utf-8', 'gbk');
        $arr_ofstate = array("充值成功","正在充值","充值失败","充值失败");
        $arr_state = auto_charset($arr_state, 'utf-8', 'gbk');
        $arr_ofstate = auto_charset($arr_ofstate, 'utf-8', 'gbk');

        if ($msgstart < 0)
            $msgstart = 0;

        $query = "select  1 from  tb_qqrechargelist where  fd_mrclist_authorid = '$authorid' and" .
            "  (fd_mrclist_payrq = '00' )";
        $db->query($query);
        $msgallcount = $db->nf();

        $query = "select fd_mrclist_bkordernumber as listno,
                fd_mrclist_rechamoney as rechamoney,
				  fd_mrclist_paymoney as rechapaymoney,
				   fd_mrclist_qqprov as rechaqqprov,
				   fd_mrclist_bankcardno as  rechabkcardno,
				   fd_mrclist_paydate as rechadatetime,
				   fd_mrclist_state as rechastate,
				  fd_mrclist_qq as rechaqq,case
        when fd_mrclist_payrq ='01' then '".$arr_state[0]."'
        when fd_mrclist_payrq ='00' then '".$arr_state[1]."'" .
            "when fd_mrclist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[4]."' END  state ,case
        when fd_mrclist_ofstate ='1' then '".$arr_ofstate[0]."'
        when fd_mrclist_ofstate ='-1' then '".$arr_ofstate[1]."'" .
            "when fd_mrclist_ofstate ='1007' then '".$arr_ofstate[2]."'
        else '".$arr_ofstate[3]."' END  ofstate from  tb_qqrechargelist where
									 fd_mrclist_authorid = '$authorid'
				and  (fd_mrclist_payrq = '00') order by fd_mrclist_id desc limit $msgstart,$msgdisplay ";
        $db->query($query);
        $msgdiscount = $db->nf();
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
        $arr_msg['msgbody']['msgallcount'] = $msgallcount;
        $arr_msg['msgbody']['msgdiscount'] = $msgdiscount + $msgstart;
        $returnvalue = array (

            "msgbody" => $arr_msg['msgbody']

        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }
}

function makeorderno($tablename, $fieldname, $preno = "pay") {
    $db = new DB_test();
    $db2 = new DB_test();
    $year = date("Y", mktime());
    $month = date("m", mktime());
    $day = date("d", mktime());

    $nowdate = $year . $month . $day;
    $query = "select fd_" . $fieldname . "_no as no from tb_" . $tablename . "   order by fd_" . $fieldname . "_no  desc";
    $db2->query($query);
    if ($db2->nf()) {
        $db2->next_record();
        $orderno = $db2->f(no);
        $orderdate = substr($orderno, 3, 8); //截取前8位判断是否当前日期
        if ($nowdate == $orderdate) {
            $orderno = substr($orderno, 11, 6) + 1; //是当前日期流水帐加1
            if ($orderno < 10) {
                $orderno = "00000" . $orderno;
            } else
                if ($orderno < 100) {
                    $orderno = "0000" . $orderno;
                } else
                    if ($orderno < 1000) {
                        $orderno = "000" . $orderno;
                    } else
                        if ($orderno < 10000) {
                            $orderno = "00" . $orderno;
                        } else {
                            $orderno = $orderno;
                        }
            $orderno = $preno . $nowdate . $orderno;
        } else {
            $orderno = $preno . $nowdate . "000001"; //不是当前日期,为5位流水帐,开始值为1。
        }
    } else {
        $orderno = $preno . $nowdate . "000001";
    }
    return $orderno;
}