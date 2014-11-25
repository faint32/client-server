<?php
/*
* 作者 Administrator
* 创建于 2014-04-19 上午7:44
* 描述：
*/

class ApiBuyOderInfo extends TfbxmlResponse {
    //读取订单商品
    public function readOrderProinfo() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];

        $query = "select fd_produre_id as produreid ,fd_produre_no as produreno,fd_produre_name as produrename,
                  fd_produre_price as produreprice ,fd_produre_zheprice as produrezheprice ,fd_produre_pic as produrepic,
                  fd_produre_memo as produrememo,fd_produre_limitnum as produrelimitnum from tb_orderprodure where fd_produre_active = '1'
			          order by fd_produre_sortno";
        $db->query($query);
        $arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
        $arr_message= array (
            "error_id" => "0",
            "result" => "success",
            "message" => "恭喜您,读取商品成功!"
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

    //读取收货地址
    public function readShaddressinfo() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];

        $query = "select fd_shaddress_id as shaddressid ,fd_shaddress_address as shaddress,
                  fd_shaddress_shman as shman ,fd_shaddress_phone as shphone,
                  fd_shaddress_yunfei as shyunfei,fd_shaddress_shyunfeitype as shyunfeitype,
                  fd_shaddress_isuse as shdefault,fd_shaddress_faxno as faxno
                   from tb_authorshaddress where fd_shaddress_authorid = '$authorid'";
        $db->query($query);
        $arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');

        $arr_message = array (
            "error_id" => "0",
            "result" => "success",
            "message" => "恭喜您,读取收货地址成功!"
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


    //收货地址新增
    function shaddressAdd() {
        global $weburl;
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']);

        $shprovincecode    = trim($arr_body['shprovincecode']);
        $shcitycode        = trim($arr_body['shcitycode']);
        $shcountycode      = trim($arr_body['shcountycode']);
        $shaddress         = trim(u2g($arr_body['shaddress']));     //详细收货地址
        $shman             = trim(u2g($arr_body['shman'])); //收货人
        $shphone           = trim(u2g($arr_body['shphone']));  //收货电话
        $shdefault         = trim(u2g($arr_body['shdefault']));    //是否默认收货地址

        $datetime = date("Y-m-d H:i:s");
        $today   = date("Ymd");

        $listno = makeorderno("qqrechargelist", "mrclist", "mrc");
        $datadetailArray['fd_shaddress_province']         = $shprovincecode;
        $datadetailArray['fd_shaddress_address']          = $shaddress;
        $datadetailArray['fd_shaddress_shman']  = $shman;
        $datadetailArray['fd_shaddress_phone']     = $shphone;
        $datadetailArray['fd_shaddress_isuse'] = $shdefault;

        $datadetailArray['fd_shaddress_city']   = $shcitycode;
        $datadetailArray['fd_shaddress_county'] = $shcountycode ;
        $datadetailArray['fd_shaddress_faxno']  = $faxno;
        $datadetailArray['fd_shaddress_authorid'] = $authorid;
        $datadetailArray['fd_shaddress_datetime'] = $datetime;

        $db->insert("tb_authorshaddress", $datadetailArray);

        $listid = $db->insert_id();

        $arr_message = array (
            "result" => "success",
            "message" => "插入收货地址成功!"
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
    //删除收货地址
    public function shaddressDelete() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $bkntno = trim($arr_body['bkntno']); //交易流水号
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器设备号
        $authorid = trim($arr_channelinfo['authorid']); //authorid
        $shaddressid = $arr_body['shaddressid'];
        $nowdate = date("Y-m-d H:i:s");

        $wherequery = "fd_shaddress_id  = '$shaddressid'";
        $db->delete("tb_authorshaddress", $wherequery);

        $arr_message = array (
            "result" => "success",
            "message" => "删除收货地址成功!"
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


    //购买刷卡器请求银联交易码
    function payOrderRq() {
        global $weburl;
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']);
        $paytype          = 'orderbuy';
        $orderpaytypeid   = trim($arr_body['orderpaytypeid']);
        $orderprodureid   = trim($arr_body['orderprodureid']); //支付类型id
        $ordernum         = trim($arr_body['ordernum']); //充值金额
        $orderprice         = trim(u2g($arr_body['orderprice']));  //实际支付金额
        $ordermoney         = trim(u2g($arr_body['ordermoney']));    //订单金额
        $ordershaddressid   = trim($arr_body['ordershaddressid']);    //手机所属地区
        $oredershaddress    = trim(u2g($arr_body['oredershaddress'])); //银行卡号
        $ordershman         = trim(u2g($arr_body['ordershman']));      //银行卡关联id tb_banckcard.fd_bankcard_id
        $ordershphone       = trim(u2g($arr_body['ordershphone']));   //认证信息
        $orderfucardno      = trim(u2g($arr_body['orderfucardno']));   //认证信息
        $orderfucardbank    = trim(u2g($arr_body['orderfucardbank']));   //认证信息
        $ordermemo          = trim(u2g($arr_body['ordermemo']));   //认证信息
        $agentno            = trim(u2g($arr_body['agentno']));   //认证信息

        $orderpaytype      = trim($arr_body['paytype']);
        $promoney          = trim(u2g($arr_body['promoney']));   //认证信息
        $yunmoney          = trim(u2g($arr_body['yunmoney']));   //认证信息
        $produrename       = trim(u2g($arr_body['produrename']));

        $bkmoney    = $ordermoney;  //promoney+yunmoney
        $rechabkcardno = makeorderno("orderglist", "orderlist", "orl");
        $arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $orderfucardno);
        $bkntno = trim($arr_bkinfo['bkntno']);
        $sdcrid = trim($arr_bkinfo['sdcrid']);

        if($agentno!="")  //判断代理商代号
        {
          // $cusid =$this->checkcusagentno($agentno);

            $query = "select fd_cus_id as cusid from tb_customer where fd_cus_no = '$agentno'";

            if ($db->execute($query)) {
                $arr_cusinfo = $db->get_one($query);
                $cusid= $arr_cusinfo['cusid'];

            }else
            {
                $arr_message = array (
                    "result" => "failure",
                    "message" => "对不起，您填写的代理商代号错误，请重新填写!"
                );
                $retcode = "200";  //反馈状态 0 成功 200 自定义错误
                $arr_msg['msgbody']['result'] = $arr_message['result'];
                $arr_msg['msgbody']['message'] = $arr_message['message'];
                $arr_msg['msgbody']['bkntno']  = $bkntno;
                $returnvalue = array (
                    "msgbody" => $arr_msg['msgbody']
                );
                $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
                return $returnval;
                exit;
            }


        }


        $datetime = date("Y-m-d H:i:s");
        $date     = date("Y-m-d");
        //$bkmoney  =
        $bkorderNumber = trim($arr_bkinfo['bkorderNumber']);



        $datadetailArray['fd_orderlist_paytype']         = $paytype;
        $datadetailArray['fd_orderlist_no']         = $listno;
        $datadetailArray['fd_orderlist_authorid']  = $authorid;
        $datadetailArray['fd_orderlist_bkntno']     = $bkntno;
        $datadetailArray['fd_orderlist_date']       = $date;
        $datadetailArray['fd_orderlist_payrq']       = '01'; //刚请求的交易码状态为01
        $datadetailArray['fd_orderlist_bkordernumber'] = $bkorderNumber;
        $datadetailArray['fd_orderlist_sdcrid']      = $sdcrid;
        $datadetailArray['fd_orderlist_produreid'] = $orderprodureid;
        $datadetailArray['fd_orderlist_produrename'] = $produrename;
        $datadetailArray['fd_orderlist_cusid'] = $cusid; //分润代理商



        $datadetailArray['fd_orderlist_num']     = $ordernum;
        $datadetailArray['fd_orderlist_price']           = $orderprice;
        $datadetailArray['fd_orderlist_promoney']    = $promoney;
        $datadetailArray['fd_orderlist_yunmoney']    = $yunmoney;
        $datadetailArray['fd_orderlist_ordermoney']    = $ordermoney;
        $datadetailArray['fd_orderlist_shaddressid']       = $ordershaddressid;
        $datadetailArray['fd_orderlist_shaddress']   = $oredershaddress;
        $datadetailArray['fd_orderlist_shman']   = $ordershman ;
        $datadetailArray['fd_orderlist_shphone'] = $ordershphone;
        $datadetailArray['fd_orderlist_fucardno'] = $orderfucardno;
        $datadetailArray['fd_orderlist_fucardbank'] = $orderfucardbank;
        $datadetailArray['fd_orderlist_memo']          = $ordermemo;
        $datadetailArray['fd_orderlist_datetime']      = $datetime;
        $datadetailArray['fd_orderlist_agentno']      = $agentno;

        $db->insert("tb_orderglist", $datadetailArray);


        $listid = $db->insert_id();

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
    public function orderPayrqStatus() {
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
        $query = "select fd_orderlist_bkordernumber as bkordernumber,fd_orderlist_authorid as authorid ,
                  fd_orderlist_date as orderdate ,DATE_FORMAT(fd_orderlist_date,'%Y%m%d') as orderTime
                  from tb_orderglist
                  where 1  and  fd_orderlist_bkntno = '$bkntno' and fd_orderlist_authorid = '$authorid'  limit 1";
        $arr_val= $db->get_one($query);


        $orderNumber =  $arr_val['bkordernumber'];
        $orderTime   =  $arr_val['orderTime'];

        $arr_returninfo = BankPayInfo :: bankorderquery($authorid,'',$orderNumber, $orderTime);


        $arr_returninfo =$this->Publiccls->xml_to_array($arr_returninfo);

       if($result=='success')
       {
           $retcode = 0; //反馈状态 0 成功 200 自定义错误
           $arr_message = array (
               "result" => "success",
               "message" =>  "订单已支付完成"
           );
       }else
       {
           $retcode = 0; //反馈状态 0 成功 200 自定义错误
           $arr_message = array (
               "result" => "success",
               "message" =>  "交易已取消"
           );

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
    public function readOrderlist() {
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
        $arr_orderstate = array("订单处理","确认收款","已发货","已完成");
        $arr_state = auto_charset($arr_state, 'utf-8', 'gbk');
        $arr_orderstate = auto_charset($arr_orderstate, 'utf-8', 'gbk');

        if ($msgstart < 0)
            $msgstart = 0;

        $query = "select  1 from  tb_orderglist where  fd_orderlist_authorid = '$authorid' and" .
            "  (fd_orderlist_payrq = '00' )";
        $db->query($query);
        $msgallcount = $db->nf();

        $query = "select fd_orderlist_bkordernumber as orderno,
                fd_orderlist_produrename as orderprodurename,
				  fd_orderlist_num as ordernum,
				   fd_orderlist_price as orderprice,
				   fd_orderlist_ordermoney as  ordermoney,
				   fd_orderlist_promoney as  promoney,
				   fd_orderlist_yunmoney as  yunmoney,
				   fd_orderlist_shaddress as ordershaddress,
				   fd_orderlist_shman as ordershman,
				   fd_orderlist_wlno as wlno,
				   fd_orderlist_wlcompanyid as kdcomanyid,
				  fd_orderlist_shphone as ordershphone,case
                when fd_orderlist_payrq ='01' then '".$arr_state[0]."'
        when fd_orderlist_payrq ='00' then '".$arr_state[1]."'" .
            "when fd_orderlist_payrq ='03' then '".$arr_state[2]."'
        else '".$arr_state[4]."' END  orderpaystatus ,case
        when fd_orderlist_state ='0' then '".$arr_ofstate[0]."'
        when fd_orderlist_state ='1' then '".$arr_ofstate[1]."'" .
            "when fd_orderlist_state ='2' then '".$arr_ofstate[2]."'
        else '".$arr_ofstate[3]."' END  orderstate from  tb_orderglist where
									 fd_orderlist_authorid = '$authorid'
				and  (fd_orderlist_payrq = '00') order by fd_orderlist_id desc limit $msgstart,$msgdisplay ";
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