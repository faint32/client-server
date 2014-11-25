<?php
    require ("../include/common.inc.php");
    $db = new db_test;
    $query = "select
             fd_agpm_bkordernumber            as bkordernumber,
             fd_agpm_bkntno                   as bkntno,
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
             fd_sdcr_agentfee                 as sdcragentfee,
             fd_agpm_isagentpay               as isagentpay," .
          
            "fd_agpm_authorid                 as authorid
             from tb_agentpaymoneylist
             left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
             left join tb_author on fd_author_id = fd_agpm_authorid
             left join tb_sendcenter on fd_sdcr_id = fd_agpm_sdcrid
             where fd_agpm_bkordernumber = '$bkordernumber'
             order by fd_agpm_bkntno";
    $db->query($query);
    if ( $db->nf() )
    {
        $db->next_record ();
        $agpmisagentpay = $db->f(fd_agpm_isagentpay);
        $paytype  = $db->f(paytypee);
        $paymoney = $db->f(paymoney);
        $authorid = $db->f(authorid);
        $bkordernumber = $db->f(bkordernumber);
    }

    switch ($paytype)
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

    if ($agpmisagentpay == 0) {
        // $query = "UPDATE tb_".$table." SET fd_".$where."_isagentpay = '2' WHERE fd_".$where."_bkordernumber = '".$bkordernumber."'";
        // $db->query($query);
        // $query1 = "UPDATE tb_agentpaymoneylist SET fd_agpm_isagentpay = '2' WHERE fd_agpm_bkordernumber = '".$bkordernumber."'";
        // $db->query($query1);
        // file_put_contents("text.txt", $query1);
        // echo 'success';
        $talbename = "tb_".$table;
        $dataArray = array("fd_" . $where . "_isagentpay"=>"2");
        $whereArray = "fd_" . $where . "_bkordernumber = '" . $bkordernumber . "'";
        $result1 = $db->update($talbename, $dataArray, $whereArray);
        unset($talbename);
        unset($dataArray);
        unset($whereArray);

        $talbename = "tb_agentpaymoneylist";
        $dataArray = array("fd_agpm_isagentpay"=>"2");
        $whereArray = "fd_agpm_bkordernumber = '" . $bkordernumber . "'";
        $result2 = $db->update($talbename, $dataArray, $whereArray);
        if ($result1 && $result2)
        {
            echo 'success';
        }
        else
        {
            echo 'failure';
        }
    }
    else
    {
        echo 'failure';
    }
?>