<?php
//require_once ("create_erpinmoney.class.php");
class TfbPayFeedback
{
    public $DB_test;
    public $TfbAccount; // 出错处理
    public $Tfb_ThirdApi;

    public function __construct()
    {
        $this->DB_test = new DB_test; //初始化类实例
        $this->TfbAccount = new TfbAccount();
        $this->Tfb_ThirdApi = new Tfb_ThirdApi();
    }
    public function changePayTranstatus($bkordernumber, $transStatus, $paytype,$cusfee)
    {
        $dbmsale = new DB_mssale();

        $nowdate = date("Y-m-d H:i:s");
        switch (trim($paytype)) {
            case "coupon" : //购买抵用券
                $query = "update tb_couponsale set fd_couponsale_payrq = '$transStatus' where fd_couponsale_bkordernumber = '$bkordernumber' ";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now(),fd_agpm_cusfenrun= '$cusfee'  where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                break;
            case "creditcard" : //信用卡还款
                $query = "update tb_creditcardglist set fd_ccglist_payrq ='$transStatus',fd_ccglist_paydate = '$nowdate' where fd_ccglist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now(),fd_agpm_cusfenrun = '$cusfee'  where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                break;
            case "recharge" : //充值
                $query = "update  tb_rechargeglist set fd_rechargelist_payrq ='$transStatus' where fd_rechargelist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now(),fd_agpm_cusfenrun = '$cusfee'  where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);

                $rechange = 'account';
                break;
            case "repay" : //还贷款
                $query = "update  tb_repaymoneyglist set fd_repmglist_payrq ='$transStatus' ,fd_repmglist_paydate ='$nowdate'
																				          where fd_repmglist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now() ,fd_agpm_cusfenrun = '$cusfee' where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                break;
            case "order" : //明盛商城订单付款
                $query = "update tb_orderpayglist set fd_oplist_payrq ='$transStatus',fd_oplist_paydate = '$nowdate' where fd_oplist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now() ,fd_agpm_cusfenrun = '$cusfee' where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $rechange = 'order';
                break;
            case "tfmg" : //转账汇款
                $query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='$transStatus' ,fd_tfmglist_paydate ='$nowdate'
																				          where fd_tfmglist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now() ,fd_agpm_cusfenrun = '$cusfee' where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                break;

            case "suptfmg" : //转账汇款
                $query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='$transStatus' ,fd_tfmglist_paydate ='$nowdate'
						  where fd_tfmglist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                $query = "update  tb_agentpaymoneylist set fd_agpm_payrq ='$transStatus',fd_agpm_datetime = now() ,fd_agpm_cusfenrun = '$cusfee' where fd_agpm_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
                break;
            case "orderbuy" : //购买刷卡器

                $query = "update  tb_orderglist set fd_orderlist_payrq ='$transStatus' ,
                      fd_orderlist_paydate ='$nowdate',fd_orderlist_cusfee = '$cusfee'
						  where fd_orderlist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);


                break;
            case "mobilerecharge" : //手机充值
                $query = "update tb_mobilerechargelist set fd_mrclist_payrq ='$transStatus',fd_mrclist_paydate = '$nowdate',fd_mrclist_cusfee = '$cusfee' where  fd_mrclist_bkordernumber = '$bkordernumber'";

                $this->DB_test->query($query);
                if ($transStatus == '00') {
                    // echo "<br>这是调用欧飞接口：".$query;
                    $return = $this->Tfb_ThirdApi->mobilerecharge($bkordernumber, $transStatus);
                    return $return;
                }
                break;
            case "qqrecharge" : //手机充值

                $query = "update tb_qqrechargelist set fd_mrclist_payrq ='$transStatus',fd_mrclist_paydate ='$nowdate',fd_mrclist_cusfenrun  = '$cusfee' where  fd_mrclist_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);

                if ($transStatus == '00') {
                    // echo "<br>这是调用欧飞接口：".$query;
                    $return = $this->Tfb_ThirdApi->qqrecharge($bkordernumber, $transStatus);
                    return $return;
                }
                break;
            case "agentbuy" : //代理商补货
                if($transStatus=='00')
                {
                    $cwstate = 1;
                }else
                {
                    $cwstate = 0;
                }
                $query = "update tb_salelist set fd_selt_payrq ='$transStatus',fd_selt_cwstate = '$cwstate',
                      fd_selt_paydate = now() where  fd_selt_bkordernumber = '$bkordernumber'";
                $this->DB_test->query($query);
//                if ($transStatus == '00') {
//                    // echo "<br>这是调用欧飞接口：".$query;
//                    $return = $this->Tfb_ThirdApi->qqrecharge($bkordernumber, $transStatus);
//                    return $return;
//
//                }
                break;
            default :
                break;
        }
        switch ($rechange) {
            case "account" :
                $query = "select fd_agpm_paycardid as paycardid,fd_agpm_authorid as authorid , fd_agpm_bkmoney as bkmoney," .
                    "fd_agpm_paytype as paytype , fd_agpm_bkordernumber as bkordernumber
										  from tb_agentpaymoneylist where fd_agpm_bkordernumber = '$bkordernumber' and fd_agpm_payrq = '00' 
										  limit 1"; //交易成功的才执行 
                if ($this->DB_test->execute($query)) {
                    $arr_payinfo = $this->DB_test->get_one($query);

                    if ($this->TfbAccount->changeAccountglist($arr_payinfo)) //如果账户流水改变，则修改账户金额
                    {
                        $getacc = $this->TfbAccount->changeAccount($arr_payinfo);
                    }
                }
                break;
            case "order" :
                $query = "select fd_oplist_orderid as orderid from tb_orderpayglist where fd_oplist_payrq ='00' " .
                    "and  fd_oplist_bkordernumber = '$bkordernumber'";
                $arr_oporderinfo = $this->DB_test->get_one($query);
                $orderid = $arr_oporderinfo['orderid'];
                $query = "update web_order set fd_order_state = '7' where fd_order_id = '$orderid'";
                $dbmsale->query($query);

                break;
        }

        return true;
    }

    public function bkordernumbertokey($bkordernumber,$transStatus)
    {
        $arr_merinfo = array();
        $arr_merinfo2 = array();
        //通付宝银行卡对银行卡业务
        $query = "select fd_agpm_sdcrid as sdcrid,fd_agpm_authorid as authorid ,fd_agpm_paytype as paytype,
fd_agpm_paydate as paydate,fd_agpm_cusid as cusid ,fd_agpm_sdcrpayfeemoney as sdcrpayfeemoney,
        fd_agpm_paycardid as paycardid,fd_agpm_payfee as payfee ,fd_agpm_paymoney as paymoney
          from tb_agentpaymoneylist where fd_agpm_bkordernumber = '$bkordernumber' limit 1";
        if ($this->DB_test->execute($query)) {
            $arr_merinfo = $this->DB_test->get_one($query);
        }

        //订单付款业务
        $query = "select fd_oplist_sdcrid as sdcrid,fd_oplist_authorid as authorid ,fd_oplist_paytype as paytype,
                fd_oplist_paydate as paydate ,
        fd_oplist_paycardid as paycardid,fd_oplist_payfee as payfee ,fd_oplist_paymoney as paymoney    from tb_orderpayglist where 1 and  fd_oplist_bkordernumber = '$bkordernumber' limit 1";
        $this->DB_test->query($query);
        if ($this->DB_test->execute($query)) {
            $arr_merinfo = $this->DB_test->get_one($query);
        }

        //购买刷卡器业务
        $query = "select fd_orderlist_sdcrid as sdcrid,fd_orderlist_authorid as authorid ,
                 fd_orderlist_paytype as paytype, fd_orderlist_paydate as paydate ,
                 fd_orderlist_price as orderprice,fd_orderlist_num as ordernum,fd_orderlist_cusid as cusid,
                fd_orderlist_paycardid as paycardid,fd_orderlist_payfee as payfee ,fd_orderlist_ordermoney as paymoney  from tb_orderglist where 1 and  fd_orderlist_bkordernumber = '$bkordernumber' limit 1";
        if ($this->DB_test->execute($query)) {
            $arr_merinfo =  $this->DB_test->get_one($query);
        }


        //手机卡充值业务
        $query = "select fd_mrclist_sdcrid as sdcrid ,fd_mrclist_authorid as authorid ,
               fd_mrclist_paytype as paytype,fd_mrclist_paydate as paydate ,fd_mrclist_cusid as cusid,
               fd_mrclist_sdcrpayfeemoney as sdcrpayfeemoney,
               fd_mrclist_paycardid as paycardid,fd_mrclist_payfee as payfee ,fd_mrclist_paymoney as paymoney
               from tb_mobilerechargelist where 1  and  fd_mrclist_bkordernumber = '$bkordernumber' limit 1";

        if ($this->DB_test->execute($query)) {
            $arr_merinfo = $this->DB_test->get_one($query);
        }

        //qq充值业务
        $query = "select fd_mrclist_sdcrid as sdcrid ,fd_mrclist_authorid as authorid ,
                 fd_mrclist_paytype as paytype,fd_mrclist_paydate as paydate ,

               fd_mrclist_sdcrpayfeemoney as sdcrpayfeemoney,
                 fd_mrclist_paycardid as paycardid ,fd_mrclist_payfee as payfee ,fd_mrclist_cusid as cusid,
                 fd_mrclist_paymoney as paymoney from tb_qqrechargelist where 1 and
                 fd_mrclist_bkordernumber = '$bkordernumber' limit 1";
        if ($this->DB_test->execute($query)) {
            $arr_merinfo = $this->DB_test->get_one($query);
        }

        //代理商购买刷卡器
        $query = "select fd_selt_sdcrid as sdcrid ,fd_selt_authorid as authorid ,
               fd_selt_paytype as paytype,fd_selt_paydate as paydate ,fd_selt_cusid,
        fd_selt_paycardid as paycardid ,fd_selt_payfee as payfee ,fd_selt_paymoney as paymoney from tb_salelist
         where 1 and  fd_selt_bkordernumber = '$bkordernumber' limit 1";
        if ($this->DB_test->execute($query)) {
            $arr_merinfo = $this->DB_test->get_one($query);
        }

        //获取第一次刷卡器交易成功时间
        if($transStatus=='00')
        {
            $paycardid = $arr_merinfo['paycardid'];
            $paydate   = $arr_merinfo['paydate'];
            if($arr_merinfo['paydate']=='0000-00-00' or $arr_merinfo['paydate']=='' )
            {
                $paydate= date("Y-m-d");
                $arr_merinfo['paydate']= date("Y-m-d");
            }

            $this->paycardfirstactive($paycardid,$paydate);
        }
        $arr_merinfo['bkordernumber'] = $bkordernumber;
        $arr_merinfo['payrq'] = $transStatus;

        //获取分润金额
         $arr_cusfee = getcusfenrun::get_cusfenrun($arr_merinfo['cusid'],$arr_merinfo['paytype'],$arr_merinfo['paymoney'],
 $arr_merinfo['sdcrpayfeemoney'], $arr_merinfo['payfee'],$arr_merinfo['feelirun'],$paydate,$arr_merinfo['orderprice'],$arr_merinfo['ordernum']);
        $arr_merinfo['cusfee'] = $arr_cusfee['cusfee'];
        $arr_merinfo['tfbfenrun'] = $arr_cusfee['tfbfenrun'];

        $dle = $this->insertintocusfee($arr_merinfo); //插入分润金额；

       //获取交易时银联的合约公司KEY
        $query = "select
		         fd_sdcr_merid as merid,fd_sdcr_securitykey as securitykey,
		         fd_sdcr_id as sdcrid,fd_sdcr_payfee as sdcrpayfee,fd_sdcr_tradeurl as tradeurl,
				 fd_sdcr_queryurl as queryurl,fd_sdcr_minpayfee as minsdcrpayfee,fd_sdcr_agentfee as sdcragentfee from tb_sendcenter
 where fd_sdcr_id= '" . $arr_merinfo['sdcrid'] . "'";

        if ($this->DB_test->execute($query)) {
            $arr_merinfo2 = $this->DB_test->get_one($query);
        }
        $arr_retrunmerinfo = array_merge($arr_merinfo, $arr_merinfo2);

        return $arr_retrunmerinfo;

    }
/*首次刷卡时间*/
        public function  paycardfirstactive($paycardid,$paydate)
        {
            unset($dateArray);
            $dateArray['fd_paycard_firstusedate'] = $paydate;
            $wherequery= "fd_paycard_id   = '$paycardid'";
            $this->DB_test->update("tb_paycard", $dateArray, $wherequery);
            return true;
        }
    /* 生成代理商收益 */
    public function  insertintocusfee($arr_info)
    {
        //
        $query = "select 1 from tb_cus_fenrunglist where 1 and  fd_frlist_bkordernumber = '".$arr_info['bkordernumber']."' limit 1";
        if ($this->DB_test->execute($query))
        {
            $arr_merinfo = $this->DB_test->get_one($query);
        }else
        {
            unset($dateArray);
            $dateArray['fd_frlist_authorid']       = $arr_info['authorid'];
            $dateArray['fd_frlist_cusid']          = $arr_info['cusid'];
            $dateArray['fd_frlist_paycardid']      = $arr_info['paycardid'];
            $dateArray['fd_frlist_paydate']        = $arr_info['paydate'];
            $dateArray['fd_frlist_paymoney']       = $arr_info['paymoney'];
            $dateArray['fd_frlist_payfee']         = $arr_info['payfee'];
            $dateArray['fd_frlist_cusfee']         = $arr_info['cusfee'];
            $dateArray['fd_frlist_bkordernumber'] = $arr_info['bkordernumber'];
            $dateArray['fd_frlist_payrq']          = $arr_info['payrq'];
            $dateArray['fd_frlist_paytype']        = $arr_info['paytype'];
            $dateArray['fd_frlist_sdcrid']         = $arr_info['sdcrid'];
            $dateArray['fd_frlist_sdcrpayfeemoney']         = $arr_info['sdcrpayfeemoney'];
            $dateArray['fd_frlist_tfbfenrun']         = $arr_info['tfbfenrun'];

            $dateArray['fd_frlist_datetime']       = date("Y-m-d H:i:s");
            $this->DB_test->insert("tb_cus_fenrunglist", $dateArray);
        }

        return 1;
    }

}

class TfbAccount
{
    public $DB_test;

    public function __construct()
    {
        $this->DB_test = new DB_test(); //初始化类实例
    }

    function changeAccountglist($arr_payinfo)
    {
        $authorid = $arr_payinfo['authorid'];
        $money = $arr_payinfo['bkmoney'];
        $paycardid = $arr_payinfo['paycardid'];
        $paytype = $arr_payinfo['paytype'];
        $bkordernumber = $arr_payinfo['bkordernumber'];
        $query = "insert into tb_authoraccountglist(fd_accglist_authorid,fd_accglist_money,fd_accglist_datetime,
												    fd_accglist_paycardid,fd_accglist_paytype,fd_accglist_bkordernumber)values
				 ('$authorid','$money',now(),'$paycardid','$paytype','$bkordernumber')";
        $this->DB_test->query($query);
        return true;
    }

    function changeAccount($arr_payinfo)
    {
        $authorid = $arr_payinfo['authorid'];
        $money = $arr_payinfo['bkmoney'];
        $paycardid = $arr_payinfo['paycardid'];
        $paytype = $arr_payinfo['paytype'];
        $bkordernumber = $arr_payinfo['bkordernumber'];
        $query = "select 1 from tb_authoraccount where fd_acc_authorid = '$authorid'";
        $this->DB_test->query($query);
        if ($this->DB_test->nf()) {
            $query = "update  tb_authoraccount set fd_acc_money = fd_acc_money + '$money',fd_acc_datetime = now() where fd_acc_authorid= '$authorid'";
            $this->DB_test->query($query);

        } else {
            $query = "insert into tb_authoraccount(fd_acc_money,fd_acc_authorid,fd_acc_datetime)values
																			 ('$money','$authorid',now())";
            $this->DB_test->query($query);
        }
        return true;
    }
}

class Tfb_ThirdApi
{
    public $DB_test;
    public $Ofpay;
    public $Publiccls; //初始化类实例

    public function __construct()
    {
        $this->DB_test = new DB_test(); //初始化类实例
        $this->Ofpay = new Ofpay();
        $this->Publiccls = new PublicClass();

    }

    function mobilerecharge($bkordernumber, $transStatus)
    {

        //手机卡充值业务
        $query = "select fd_mrclist_sdcrid as sdcrid,fd_mrclist_ofstate as ofstate,fd_mrclist_authorid as authorid ,fd_mrclist_rechamoney as rechamoney ,fd_mrclist_rechaphone as rechaphone
                  from tb_mobilerechargelist
                  where 1  and  fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' limit 1";
        $this->DB_test->query($query);


        $file="../../".CONST_LOGDIR."/".date('Y-m-d')."手机充值-log".".txt";
        $filehandle=fopen($file, "a");
        fwrite($filehandle,"\r\n======手机充值SQL内容：\r\n".$query."\r\n\r\n\r\n");
        fclose($filehandle);


        if ($this->DB_test->nf()) {
            $this->DB_test->next_record();
            $authorid = $this->DB_test->f('authorid');
            $sdcrid = $this->DB_test->f('sdcrid');
            $rechamoney = $this->DB_test->f('rechamoney'); //充值金额
            $rechaphone = $this->DB_test->f('rechaphone'); //充值手机
            $ofstate = $this->DB_test->f('ofstate') + 0; //充值手机
            switch ($ofstate) {
                case "0":
                    if ($sdcrid < 100 ) {
                        $query = "update tb_mobilerechargelist set
               fd_mrclist_ofstate = '-1' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
               fd_mrclist_payrq = '$transStatus' "; //代表正在充值
                        $this->DB_test->query($query);

                        $returnxml = $this->Ofpay->mobilerecharge($rechaphone, $rechamoney);

                        $ofreqcontent = $returnxml['ofreqcontent'];
                        $ofanscontent = $returnxml['ofanscontent'];
                        $ofstate = $returnxml['orderinfo']['retcode']; ////如果成功将为1，澈消(充值失败)为9，充值中为0,只能当状态为9时，商户才可以退款给用户。

                        $query = "update tb_mobilerechargelist set fd_mrclist_ofreqcontent = '$ofreqcontent',fd_mrclist_ofanscontent='$ofanscontent'
                      ,fd_mrclist_ofstate = '$ofstate' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' ";
                        $this->DB_test->query($query);

                        echo g2u($ofanscontent);
                        exit;
                    } else {
                        $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，模拟环境下充值成功。</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                        echo $xml;
                        exit;
                    }
                    break;
                case "1":
                    $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，您已充值成功，请勿重新提交！</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                    echo $xml;
                    exit;
                    break;
                case "-1":
                    $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>正在为您充值，请等待！</err_msg>
                            <retcode>1000</retcode>
                            </orderinfo>';
                    echo $xml;
                    exit;
                    break;
                default:
                    if ($sdcrid < 100 ) {
                        $query = "update tb_mobilerechargelist set
               fd_mrclist_ofstate = '-1' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
               fd_mrclist_payrq = '$transStatus' "; //代表正在充值
                        $this->DB_test->query($query);

                        $returnxml = $this->Ofpay->mobilerecharge($rechaphone, $rechamoney);

                        $ofreqcontent = $returnxml['ofreqcontent'];
                        $ofanscontent = $returnxml['ofanscontent'];
                        $ofstate = $returnxml['orderinfo']['retcode']; ////如果成功将为1，澈消(充值失败)为9，充值中为0,只能当状态为9时，商户才可以退款给用户。

                        $query = "update tb_mobilerechargelist set fd_mrclist_ofreqcontent = '$ofreqcontent',fd_mrclist_ofanscontent='$ofanscontent'
                      ,fd_mrclist_ofstate = '$ofstate' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' ";
                        $this->DB_test->query($query);

                        echo g2u($ofanscontent);
                        exit;
                    } else {
                        $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，模拟环境下充值成功。</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                        echo $xml;
                        exit;
                    }
                    break;
            }

        } else {
            $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>支付失败，系统无法为您充值！</err_msg>
                            <retcode>200</retcode>
                            </orderinfo>';
            echo $xml;
            exit;
            // break;

        }

    }

    function qqrecharge($bkordernumber, $transStatus)
    {

        //手机卡充值业务
        $query = "select fd_mrclist_sdcrid as sdcrid,fd_mrclist_ofstate as ofstate,fd_mrclist_authorid as authorid ,fd_mrclist_rechamoney as rechamoney ,fd_mrclist_qq as rechaphone
                  from tb_qqrechargelist
                  where 1  and  fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' limit 1";
        $this->DB_test->query($query);

        if ($this->DB_test->nf()) {
            $this->DB_test->next_record();
            $authorid = $this->DB_test->f('authorid');
            $sdcrid = $this->DB_test->f('sdcrid');
            $rechamoney = $this->DB_test->f('rechamoney'); //充值金额
            $rechaphone = $this->DB_test->f('rechaphone'); //充值qq
            $ofstate = $this->DB_test->f('ofstate') + 0; //充值手机
            switch ($ofstate) {
                case "0":
                    if ($sdcrid < 100 ) {
                        $query = "update tb_qqrechargelist set
               fd_mrclist_ofstate = '-1' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
               fd_mrclist_payrq = '$transStatus' "; //代表正在充值
                        $this->DB_test->query($query);

                        $returnxml = $this->Ofpay->qqrecharge($rechaphone, $rechamoney);

                        $ofreqcontent = $returnxml['ofreqcontent'];
                        $ofanscontent = $returnxml['ofanscontent'];
                        $ofstate = $returnxml['orderinfo']['retcode']; ////如果成功将为1，澈消(充值失败)为9，充值中为0,只能当状态为9时，商户才可以退款给用户。

                        $query = "update tb_qqrechargelist set fd_mrclist_ofreqcontent = '$ofreqcontent',fd_mrclist_ofanscontent='$ofanscontent'
                      ,fd_mrclist_ofstate = '$ofstate' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' ";
                        $this->DB_test->query($query);

                        echo g2u($ofanscontent);
                        exit;
                    } else {
                        $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，模拟环境下充值成功。</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                        echo $xml;
                        exit;
                    }
                    break;
                case "1":
                    $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，您已充值成功，请勿重新提交！</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                    echo $xml;
                    exit;
                    break;
                case "-1":
                    $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>正在为您充值，请等待！</err_msg>
                            <retcode>1000</retcode>
                            </orderinfo>';
                    echo $xml;
                    exit;
                    break;
                default:
                    if ($sdcrid < 100 ) {
                        $query = "update tb_qqrechargelist set
               fd_mrclist_ofstate = '-1' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
               fd_mrclist_payrq = '$transStatus' "; //代表正在充值
                        $this->DB_test->query($query);

                        $returnxml = $this->Ofpay->qqrecharge($rechaphone, $rechamoney);

                        $ofreqcontent = $returnxml['ofreqcontent'];
                        $ofanscontent = $returnxml['ofanscontent'];
                        $ofstate = $returnxml['orderinfo']['retcode']; ////如果成功将为1，澈消(充值失败)为9，充值中为0,只能当状态为9时，商户才可以退款给用户。

                        $query = "update tb_qqrechargelist set fd_mrclist_ofreqcontent = '$ofreqcontent',fd_mrclist_ofanscontent='$ofanscontent'
                      ,fd_mrclist_ofstate = '$ofstate' where fd_mrclist_bkordernumber = '$bkordernumber' and fd_mrclist_payrq='00' and
                  fd_mrclist_payrq = '$transStatus' ";
                        $this->DB_test->query($query);

                        echo g2u($ofanscontent);
                        exit;
                    } else {
                        $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>恭喜您，模拟环境下充值成功。</err_msg>
                            <retcode>1</retcode>
                            </orderinfo>';
                        echo $xml;
                        exit;
                    }
                    break;
            }


        } else {
            $xml = '<?xml version="1.0" encoding="GB2312" ?>
                            <orderinfo>
                            <err_msg>支付失败，系统无法为您充值！</err_msg>
                            <retcode>200</retcode>
                            </orderinfo>';
            echo $xml;
            exit;
            // break;

        }

    }

}

?>