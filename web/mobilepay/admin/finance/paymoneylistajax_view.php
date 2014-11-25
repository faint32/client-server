<?php
$thismenucode = "2n603";
    require ("../include/common.inc.php");
    $db = new db_test;

    switch ($isagentpay)
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

    $query = "select * from tb_".$table." where fd_".$where."_bkordernumber = '".$bkordernumber."'";
    $db->query($query);
    $arr_result = $db->getFiledData('');
    $flie = "fd_".$where."_id";
    echo $arr_result[0][$flie];
?>