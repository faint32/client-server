<?php
    require ("../include/common.inc.php");
    $db = new db_test;
    $boolean = 0;
    $query = "select
                 case
                 when fd_agpm_payfeedirct ='s' then ' 收款方'
                 when fd_agpm_payfeedirct ='f' then '付款方'
                 END  payfeedirct,

                 case
                 when fd_agpm_paytype ='coupon' then '购买抵用券'
                 when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
                 "when fd_agpm_paytype ='recharge' then        '充值'" .
                 "when fd_agpm_paytype ='pay' then       '还贷款'" .
                 "when fd_agpm_paytype ='order' then '订单付款'" .
                 "when fd_agpm_paytype ='tfmg' then '转账汇款'
                 else '其他业务' END  paytype,

                 case
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney-fd_agpm_payfee)
                 else fd_agpm_paymoney END money,

                 fd_agpm_bkordernumber            as bkordernumber,
                 fd_pymyltdetail_id               as vid,
                 fd_agpm_bkntno                   as bkntno,
                 fd_paycard_key                   as paycardkey,
                 fd_author_truename               as author,
                 fd_agpm_paytype                  as paytypee,
                 fd_agpm_paydate                  as paydate,
                 fd_agpm_shoucardno               as shoucardno,
                 fd_agpm_shoucardbank             as shoucardbank,
                 fd_agpm_shoucardman              as shoucardman,
                 fd_agpm_shoucardmobile           as shoucardmobile,
                 fd_agpm_current                  as current,
                 fd_agpm_paymoney                 as paymoney ,
                 fd_agpm_payfee                   as payfee,
                 fd_agpm_arrivemode               as arrivemode,
                 fd_agpm_arrivedate               as arrivedate,
                 fd_paycardaccount_accountname    as accountname,
                 fd_sdcr_agentfee                 as sdcragentfee,
                 fd_agpm_isagentpay               as isagentpay,
                 fd_paycardaccount_accountnum     as accountnum
                 from tb_paymoneylistdetail  
                 left join tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id
                 left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
                 left join tb_author on fd_author_id = fd_agpm_authorid
                 left join tb_paycardaccount on fd_paycard_paycardaccount = fd_paycardaccount_id
                 left join tb_sendcenter on fd_sdcr_id = fd_agpm_sdcrid
                 where fd_pymyltdetail_paymoneylistid = '$listid'
                 order by fd_agpm_bkntno";
    $db->query($query);

    $arr_result = $db->getFiledData('');

    foreach ($arr_result as $key => $value)
    {
        switch ($value['paytype'])
        {
            case 'coupon':
                $table = 'couponsale';
                  $where = 'couponsale';
                break;
            case 'creditcard':
                $table = 'creditcardglist';
                $where = 'ccglist';
                break;
            case 'recharge':
                $table = 'rechargeglist';
                $where = 'rechargelist';
                break;
        case 'repay':
                $table = 'repaymoneyglist';
                $where = 'repmglist';
                break;
            case 'tfmg':
                $table = 'transfermoneyglist';
                $where = 'tfmglist';
                break;
             case 'suptfmg':
                $table = 'transfermoneyglist';
                $where = 'tfmglist';
                break;
            default:
                break;
        }

        if ($value['isagentpay'] == 2)
        {
            $sqlquery = "UPDATE tb_".$table." SET fd_".$where."_isagentpay = '0' , fd_".$where."_sdcragentfeemoney = '0.00' , fd_".$where."_agentdate = now() WHERE fd_".$where."_bkordernumber = '".$value['bkordernumber']."'";
            $db->query($sqlquery);

            $query1 = "UPDATE tb_agentpaymoneylist SET fd_agpm_isagentpay = '0' , fd_agpm_sdcragentfeemoney = '0.00' , fd_agpm_agentdate = now() WHERE fd_agpm_bkordernumber = '".$value['bkordernumber']."'";
            $db->query($query1);
        }
    }
    echo 'success';
?>