<?php
    require ("../include/common.inc.php");
    $db = new db_test;
    $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
	$arr_couponset = $db->get_one($query);
	$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
    $boolean = 0;
    $query = "select
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
                 fd_paycardaccount_accountnum     as accountnum," .                 
            
            	"fd_agpm_authorid                 as authorid
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
        switch ($value['paytypee'])
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
                $paymoney = $value['paymoney'];
                $authorid = $value['authorid'];
                $paytype  = $value['paytypee'];
                $bkordernumber = $value['bkordernumber'];
                   $query = "update tb_authoraccount set fd_acc_money = fd_acc_money-$paymoney where fd_acc_authorid = '$authorid'";
            $db->query($query);
            $query = "insert into tb_authoraccountglist (fd_accglist_authorid,fd_accglist_acctype,fd_accglist_money,fd_accglist_datetime,fd_accglist_paytype,fd_accglist_bkordernumber)" .
            		"values('$authorid','','-$paymoney',now(),'$paytype','$bkordernumber')"; 
            $db->query($query);
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
        
        if($value['paytypee']=='coupon')
		{
			$value['payfee'] = $value['paymoney']  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$agentmoney = $value['paymoney'] -$value['payfee'];
			//$agentmoney = $value['paymoney']  * (1-$couponfee*0.01);
		}else
		{
			$agentmoney = $value['paymoney'];
		}
        
        if ($value['isagentpay'] == 2)
        {
            $sqlquery = "UPDATE tb_".$table." SET fd_".$where."_isagentpay = '1' , fd_".$where."_sdcragentfeemoney = '".$value['sdcragentfee']."' , fd_".$where."_agentdate = now() WHERE fd_".$where."_bkordernumber = '".$value['bkordernumber']."'";
            $db->query($sqlquery);
            // $talbename = "tb_".$table;
            // $dataArray = array(
            //     "fd_" . $where . "_isagentpay"=>"1" , 
            //     "fd_".$where."_sdcragentfeemoney"=>$value['sdcragentfee'] , 
            //     "fd_".$where."_agentdate"=>now()
            // );
            // $whereArray = "fd_" . $where . "_bkordernumber = '" . $value['bkordernumber'] . "'";
            // $result1 = $db->update($talbename, $dataArray, $whereArray);
            // unset($talbename);
            // unset($dataArray);
            // unset($whereArray);

            $query1 = "UPDATE tb_agentpaymoneylist SET fd_agpm_isagentpay = '1' , fd_agpm_sdcragentfeemoney = '".$value['sdcragentfee']."' , fd_agpm_agentdate = now() , fd_agpm_agentmoney = ".$agentmoney." WHERE fd_agpm_bkordernumber = '".$value['bkordernumber']."'";
            $db->query($query1);
            // $talbename = "tb_agentpaymoneylist";
            // $dataArray = array(
            //     "fd_agpm_isagentpay"=>"1" , 
            //     "fd_agpm_sdcragentfeemoney"=>$value['sdcragentfee'] , 
            //     "fd_agpm_agentdate"=>now() , 
            //     "fd_agpm_agentmoney"=>$value['money']
            // );
            // $whereArray = "fd_agpm_bkordernumber = '" . $value['bkordernumber'] . "'";
            // $result2 = $db->update($talbename, $dataArray, $whereArray);
        }
    }
    // $query2 = "UPDATE tb_paymoneylist SET fd_pymylt_isagentpay = '1' WHERE fd_pymylt_id = ".$listid;
    // $db->query($query2);
    $talbename = "tb_paymoneylist";
    $dataArray = array("fd_pymylt_isagentpay"=>"1");
    $whereArray = "fd_pymylt_id = '" . $listid . "'";
    $result = $db->update($talbename , $dataArray , $whereArray);
    if ($result)
    {
        echo 'success';
    }
?>