<?php
/*
* 作者 Administrator
* 创建于 2014-04-19 上午7:44
* 描述：
*/
class ApiAgentInfo extends TfbxmlResponse {
    //绑定代理商
    public function authorBindAgent() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $agentno = $arr_body['agentno'];
        $authorid = $arr_channelinfo['authorid'];
        $agentno = strtolower($agentno);
        $query = "select fd_cus_id as cusid ,fd_cus_no as cusno,fd_cus_name as cusname  from tb_customer where fd_cus_no = '$agentno'";
        $db->query($query);
        if($db->nf()>0)
        {
            $array_cus = $db->get_one($query);
            $array_cus = auto_charset($array_cus, 'gbk', 'utf-8');
            $dateArray['fd_author_bdagentid'] = $array_cus['cusid'];
            $dateArray['fd_author_bdagentno'] = $array_cus['cusno'];
            $dateArray['fd_author_bdagenttime'] = date("Y-m-d H:i:s");
            $wherequery = "fd_author_id  = '$authorid'";
            $db->update("tb_author", $dateArray, $wherequery);
            $arr_message = array (
                "result" => "success",
                "message" => "恭喜您，绑定代理商成功!"
            );
            $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        }
        else
        {
            $retmessage = "代理商代号不存在，请验证";
            $arr_message = array (
                "result" => "failure",
                "message" => $retmessage
            );
            $retcode = "200";  //反馈状态 0 成功 200 自定义错误
        }
        $arr_msg['msgbody']['agentno'] = $array_cus['cusno'];
        $arr_msg['msgbody']['agentname'] = $array_cus['cusname'];
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }
    //读取基本信息
    public function readagentinfo() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = $arr_channelinfo['authorid'];
        $agentid = $arr_channelinfo['agentid'];  //代理商id
        $arr_message = array (
            "result" => "success",
            "message" => "读取成功!"
        );
        $now =date("Y-m-d");
        $nowmonth = date("Y-m");
        /* 今天的分润 */
        $arr_fenrun = getcusfenrun::getcusmonthallfenrun($agentid,$now);
        $todayfenrun       = $arr_fenrun['cusfenrun'];  //分润

        /*总分润*/
        $arr_fenrun = getcusfenrun::getcusmonthallfenrun($agentid);
        $areafenrun       = $arr_fenrun['cusfenrun'];  //分润

        $areapaycardnum =getcusfenrun::getcauthoractivecardnum($agentid,$nowmonth);

        $salepaycardnum =getcusfenrun::getcusordernum($agentid,$nowmonth);

        $areaauthornum =getcusfenrun::getareaauthornum($agentid,$nowmonth);



        $retcode = "0";  //反馈状态 0 成功 200 自定义错误
        $arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
        $arr_msg['msgbody']['todayfenrun']     = $todayfenrun+0;        //今天总收益
        $arr_msg['msgbody']['areafenrun']      = $areafenrun+0;          //本区总收益
        $arr_msg['msgbody']['salepaycardnum'] = ($salepaycardnum+0); //本月销售数量 salenum|totalnum
        $arr_msg['msgbody']['areapaycardnum'] = $areapaycardnum+0; //本区刷卡器数
        $arr_msg['msgbody']['areaauthornum']  = $areaauthornum+0;  //本区用户数


        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );

        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }

    //代理商读取补货记录
    public function readagentorder() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']); //操作者
        $agentid  = trim($arr_channelinfo['agentid']); //操作者

        $msgstart = trim($arr_body['msgstart']) + 0;
        $msgdisplay = trim($arr_body['msgdisplay']) + 0;

        $arr_orderstate = array("订单处理","已收款","已发货","已收货","已取消");
        $arr_orderstate = auto_charset($arr_orderstate, 'utf-8', 'gbk');

        if ($msgstart < 0)
            $msgstart = 0;

        $query = "select
                  fd_selt_id as orderid ,fd_selt_date as orderdate ,fd_selt_state as orderstate, fd_selt_memo as ordermemo ,
                  case
                  when fd_selt_state ='0' then '".$arr_orderstate[0]."'
                  when fd_selt_state ='1' then '".$arr_orderstate[1]."'" .
            "when fd_selt_state ='2' then '".$arr_orderstate[2]."'
            when fd_selt_state ='9' then '".$arr_orderstate[3]."'
        else '".$arr_orderstate[4]."' END  orderstate1
        from tb_salelist where fd_selt_cusid = '$agentid'";
        $db->query($query);
        $msgallcount = $db->nf();

        $query = "select
                  fd_selt_id as orderid ,fd_selt_date as orderdate , fd_selt_state as orderstate,fd_selt_memo as ordermemo ,
                  case
                  when fd_selt_state ='0' then '".$arr_orderstate[0]."'
                  when fd_selt_state ='1' then '".$arr_orderstate[1]."'" .
            "when fd_selt_state ='2' then '".$arr_orderstate[2]."'
            when fd_selt_state ='9' then '".$arr_orderstate[3]."'
        else '".$arr_orderstate[4]."' END  orderstate1
        from tb_salelist where fd_selt_cusid = '$agentid' order by fd_selt_id desc limit $msgstart,$msgdisplay ";
        $db->query($query);
        $msgdiscount = $db->nf();
        $arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
        if (!$arr_msg) {
            $arr_message = array (
                "result" => "success",
                "message" => "没有数据!"
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
        $arr_msg['msgbody']['msgallcount'] = $msgallcount;
        $arr_msg['msgbody']['msgdiscount'] = $msgdiscount + $msgstart;


        $arr_proinfo = getcusfenrun::getagentprodure($agentid);
        $produrename = $arr_proinfo['agentprodure'];
        $nowprice    = $arr_proinfo['agentprice'];
        $limitminnum    = $arr_proinfo['limitminnum'];
        $limitmaxnum    = $arr_proinfo['limitmaxnum'];
        $produreid    = $arr_proinfo['agentprodureid'];
        $arr_msg['msgbody']['limitmaxnum'] = $limitmaxnum;
        $arr_msg['msgbody']['nowprice']    = $nowprice;
        $arr_msg['msgbody']['limitminnum'] =$limitminnum;
        $arr_msg['msgbody']['produreid']   = $produreid;
        $arr_msg['msgbody']['produrename'] = $produrename;

        $returnvalue = array (

            "msgbody" => $arr_msg['msgbody']

        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }
    //代理商读取补货记录
    public function agentorderstaterq() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']); //操作者
        $agentid  = trim($arr_channelinfo['agentid']); //操作者

        $orderid = trim($arr_body['orderid']);

        $query = "update tb_salelist set fd_selt_state ='9'  where fd_selt_id = '$orderid'";
        $db->query($query);


            $arr_message = array (
                "result" => "success",
                "message" => "已收货成功!"
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

    //购买刷卡器请求银联交易码
    function payagentOrderRq() {
        global $weburl;
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $authorid = trim($arr_channelinfo['authorid']);
        $agentid = trim($arr_channelinfo['agentid']);
        $paytype          = 'agentbuy';
        $orderprodureid     = trim($arr_body['orderprodureid']); //支付类型id
        $ordernum           = trim($arr_body['ordernum']); //充值金额
        $orderprice         = trim(u2g($arr_body['orderprice']));  //实际支付金额
        $ordermoney         = trim(u2g($arr_body['ordermoney']));    //订单金额
        $orderfucardno      = trim(u2g($arr_body['orderfucardno']));   //认证信息
        $orderfucardbank    = trim(u2g($arr_body['orderfucardbank']));   //认证信息
        $ordermemo          = trim(u2g($arr_body['ordermemo']));   //认证信息
        $agentno          = trim(u2g($arr_body['agentno']));   //认证信息
        $arr_paycard = GetPayCalcuInfo :: readpaycardid($arr_body['paycardid'],$authorid); //刷卡器设备号
        $paycardid    = $arr_paycard['paycardid'];   //刷卡器id
        $cusid       = trim($arr_paycard['cusid']); //代理商

        $ordermemo   = "自订刷卡器".$ordernum;

        $bkmoney       = $ordermoney;  //promoney+yunmoney
        $paymoney      = $ordermoney;
        $listno = makeorderno("salelist", "selt", "xs");


        $arr_bkinfo    = BankPayInfo :: bankpayorder($authorid,$paycardid,$bkmoney, $orderfucardno);
        $bkntno = trim($arr_bkinfo['bkntno']);
        $sdcrid = trim($arr_bkinfo['sdcrid']);

        $datetime = date("Y-m-d H:i:s");
        $date     = date("Y-m-d");

        $bkorderNumber = trim($arr_bkinfo['bkorderNumber']);

        $type = "app"; //自订
        $datadetailArray['fd_selt_paytype']     = $paytype;
        $datadetailArray['fd_selt_no']           = $listno;
        $datadetailArray['fd_selt_authorid']    = $authorid;
        $datadetailArray['fd_selt_bkntno']      = $bkntno;
        $datadetailArray['fd_selt_date']        = $date;
        $datadetailArray['fd_selt_payrq']       = '01'; //刚请求的交易码状态为01
        $datadetailArray['fd_selt_bkordernumber']= $bkorderNumber;
        $datadetailArray['fd_selt_sdcrid']        = $sdcrid;
        $datadetailArray['fd_selt_produreid']    = $orderprodureid;
        $datadetailArray['fd_selt_allquantity']           = $ordernum;
        $datadetailArray['fd_selt_saleprice']         = $orderprice;
        $datadetailArray['fd_selt_fucardno']     = $orderfucardno;
        $datadetailArray['fd_selt_fucardbank']   = $orderfucardbank;
        $datadetailArray['fd_selt_memo']          = u2g($ordermemo);
        $datadetailArray['fd_selt_datetime']     = $datetime;
        $datadetailArray['fd_selt_cusid']        = $agentid;  //代理商id
        $datadetailArray['fd_selt_paymoney']    = $paymoney;  //代理商id
        $datadetailArray['fd_selt_authorid']    = $authorid;  //代理商id
        $datadetailArray['fd_selt_type']    = $type;
        $datadetailArray['fd_selt_skfs']    = '5'; //在线支付


        $db->insert("tb_salelist", $datadetailArray);



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
    public function agentorderPayrqStatus() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        $bkntno = trim($arr_body['bkntno']); //交易流水号

        $authorid = trim($arr_channelinfo['authorid']); //authorid
        $result = $arr_body['result'];
        $nowdate = date("Y-m-d H:i:s");

        //代理商购买刷卡器
        $query = "select fd_selt_bkordernumber as bkordernumber,fd_selt_authorid as authorid ,
                  fd_selt_date as orderdate ,DATE_FORMAT(fd_selt_date,'%Y%m%d') as orderTime
                  from tb_salelist
                  where 1  and  fd_selt_bkntno = '$bkntno' and fd_selt_authorid = '$authorid'  limit 1";
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

    //代理商收益历史
    public function payagentfenrunlist() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
        $authorid             = trim($arr_channelinfo['authorid']);//操作者
        $agentid              = trim($arr_channelinfo['agentid']); //代理商id
        $querytype            = trim($arr_body['querytype']);     //收益条件
        $querywhere           = trim($arr_body['querywhere']);    //充值金额
        $appfunid             = trim($arr_body['appfunid']);      //充值金额

        switch($querytype)
        {
            case "year":
                break;
            case "month":
                break;
            case "date":
                break;
        }
        $querywhere1 = " and fd_frlist_paydate like '%$querywhere%'";
        $query = "select fd_frlist_cusid,sum(fd_frlist_cusfee) as totalfenrun
             from tb_cus_fenrunglist
               where fd_frlist_payrq = '00' and fd_frlist_sdcrid<100
              $querywhere1 and fd_frlist_cusid = '$agentid'   group by fd_frlist_cusid
     ";
        if($db->execute($query))
        {
            $arr_money = $db->get_one($query);
        }
        $query = "select fd_amtype_id as appfunid, fd_amtype_name as appfunname,
               sum(fd_frlist_paymoney) as paymoney,sum(fd_frlist_payfee) as payfee,sum(fd_frlist_cusfee) as allfenrun
               from tb_cus_fenrunglist left  join tb_appmenu on fd_appmnu_no = fd_frlist_paytype
               left join tb_appmenutype on fd_amtype_id = fd_appmnu_amtypeid
               where fd_frlist_payrq = '00' and fd_frlist_sdcrid<100
              $querywhere1 and fd_frlist_cusid = '$agentid'   group by fd_amtype_id order by
              fd_amtype_no     ";
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
        $arr_msg['msgbody']['totalfenrun'] = $arr_money['totalfenrun'];
        $returnvalue = array (
            "msgbody" => $arr_msg['msgbody']
        );
        $returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
    }

    //代理商收益历史
    public function payagentfenrunlistdetail() {
        $db = new DB_test();
        $arr_header = $this->arr_header;
        $arr_body = $this->arr_body;
        $arr_channelinfo = $this->arr_channelinfo;
        //$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //插卡器
        $authorid = trim($arr_channelinfo['authorid']); //操作者
        $agentid = trim($arr_channelinfo['agentid']); //代理商id
        $querytype            = trim($arr_body['querytype']); //支付类型id
        $querywhere           = trim($arr_body['querywhere']); //充值金额
        $appfunid             = trim($arr_body['appfunid']); //充值金额






        $querywhere1 = " and fd_frlist_paydate like '%$querywhere%'";
        $query = "select fd_amtype_id as appfunid, fd_appmnu_name as appfunname,
                 sum(fd_frlist_paymoney) as paymoney,sum(fd_frlist_payfee) as payfee,sum(fd_frlist_cusfee) as fenrun
             from tb_cus_fenrunglist left  join tb_appmenu on fd_appmnu_no = fd_frlist_paytype
             left join tb_appmenutype on fd_amtype_id = fd_appmnu_amtypeid
               where fd_frlist_payrq = '00' and fd_frlist_sdcrid<100
              $querywhere1 and fd_frlist_cusid = '$agentid' and fd_appmnu_amtypeid = '$appfunid'
              group by fd_appmnu_amtypeid order by
              fd_appmnu_id
     ";
        $db->query($query);

        $query = "select fd_amtype_id as appfunid, fd_appmnu_name as appfunname,
                 sum(fd_frlist_paymoney) as paymoney,sum(fd_frlist_payfee) as payfee,sum(fd_frlist_cusfee) as fenrun
             from tb_cus_fenrunglist left  join tb_appmenu on fd_appmnu_no = fd_frlist_paytype
             left join tb_appmenutype on fd_amtype_id = fd_appmnu_amtypeid
               where fd_frlist_payrq = '00' and fd_frlist_sdcrid<100
              $querywhere1 and fd_frlist_cusid = '$agentid' and fd_appmnu_amtypeid = '$appfunid'
              group by fd_appmnu_id order by
              fd_appmnu_id
     ";
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
        $arr_msg['msgbody']['totalfenrun'] = 0;
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