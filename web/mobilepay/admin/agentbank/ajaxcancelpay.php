<?php
    require ("../include/common.inc.php");
    $db = new db_test;
    switch ($isagentpay) {
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
    $query = "UPDATE tb_".$table." SET fd_".$where."_isagentpay = '0' WHERE fd_".$where."_bkordernumber = '".$bkordernumber."'";
    $db->query($query);

    $query1 = "UPDATE tb_agentpaymoneylist SET fd_agpm_isagentpay = '0' WHERE fd_agpm_bkordernumber = '".$bkordernumber."'";
    $db->query($query1);
    echo 'success';
?>