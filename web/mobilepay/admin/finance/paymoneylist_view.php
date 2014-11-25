<?php
$thismenucode = "2n603";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php"); //调用生成单据编号文件    
require ("../function/changemoney.php"); //调用应付应付金额文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/cashglide.php"); //调用现金流水帐文件
require ("../function/currentaccount.php"); //调用往来对帐单文件


$db  = new DB_test();
$db1 = new DB_test();

if($state == 0){
	$gourl = "tb_agentpaymoney_sq_b.php";
}else if($state == 1){
	$gourl = "tb_agentpaymoney_sp_b.php";
}else if($state == 2){
	$gourl = "tb_agentpaymoney_out_b.php";
}else if($state == 4){
	$gourl = "tb_agentpaymoney_sh_b.php";
}else{
	$gourl = "tb_agentpaymoney_h_b.php";
}

$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "paymoneylist_view", "paymoneylist_view.html" );

$count = 0;

$action = "edit";
$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' ";
$db->query ( $query );
if($db->nf()){
	$db->next_record ();
	$listno         = $db->f(fd_pymylt_no);
	$dealwithman    = $db->f(fd_pymylt_dealwithman);
	$money          = $db->f(fd_pymylt_money);
	$date           = $db->f(fd_pymylt_date);
	$fkdate         = $db->f(fd_pymylt_fkdate);
	$memo_z         = $db->f(fd_pymylt_memo);
    $paytype        = $db->f(fd_pymylt_paytype);
    $state          = $db->f(fd_pymylt_state);
}
	upallmoney($listid);
$t->set_block ( "paymoneylist_view", "prolist", "prolists" );
 $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
		$arr_couponset = $db->get_one($query);
		$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
$query = "select 
                 case
                 when fd_agpm_payfeedirct ='s' then ' 收款方'
                 when fd_agpm_payfeedirct ='f' then '付款方'
                 END  payfeedirct,

                 fd_agpm_paytype  as  paytype,

                 case
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney - fd_agpm_payfee)
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 else fd_agpm_paymoney END money,

                 fd_pymyltdetail_id               as vid,
                 fd_agpm_paytype                  as paytypee,
                 fd_agpm_bkntno                   as bkntno,
                 fd_agpm_bkordernumber            as bkordernumber,
                 fd_paycard_key                   as paycardkey,
                 fd_author_truename               as author,
                 fd_agpm_paydate                  as paydate,
                 fd_agpm_shoucardno               as shoucardno,
                 fd_agpm_shoucardbank             as shoucardbank,
                 fd_agpm_shoucardman              as shoucardman,
                 fd_agpm_shoucardmobile           as shoucardmobile,
                 fd_agpm_current                  as current,
                 fd_agpm_paymoney                 as paymoney,
                 fd_agpm_payfee                   as payfee,
                 fd_agpm_arrivemode               as arrivemode,
                 fd_agpm_arrivedate               as arrivedate,
                 fd_paycardaccount_accountname    as accountname,
                 fd_sdcr_name                     as sdcrname,
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
$count = 0;
$list_disabled = "disabled";

foreach ($arr_result as $key => $value) {
        $count++;
        $value['count']= $count;
        $value['arrivemode']= "T+".$value['arrivemode'];
          if($value['paytypee']=='coupon')
		{
			
			$value['payfee'] = $value['paymoney']  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$value['money'] = $value['money'] -$value['payfee'];
			
			//echo $value['money'];
		}else
		{
			$value['money']  = $value['money'];
			$value['payfee'] = "0"; 
		}
        $all_paymoney += $value['paymoney'];
        $all_payfee += $value['payfee'];
        $all_money += $value['money'];
        
        $list_disabled = "";
        $t->set_var($value);
        $t->parse("prolists", "prolist", true);
}

if(empty($arr_result)){
        $t->parse("prolists", "", true);
}



if(empty($listid)) { //如果已经暂存，提交跟删除按钮可用，否则不可用
        $tijiao_dis = "disabled";
} else {
        $tijiao_dis = "";
}

$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");


switch ($paytype) {
    case 'coupon':
        $paytype = "购买抵用券";
        break;
    case 'creditcard':
        $paytype = "信用卡还款";
        break;
    case 'recharge':
        $paytype = "充值";
        break;
    case 'repay':
        $paytype = "还贷款";
        break;
    case 'order':
        $paytype = "订单付款";
        break;
    case 'tfmg':
        $paytype = "转账汇款";
        break;
     case 'suptfmg':
        $paytype = "转账汇款";
        break;
    default:
        $paytype = "其他业务";
        break;
}

$t->set_var ("tijiao_dis"       , $tijiao_dis   );
$t->set_var ("listno"           , $listno       );
$t->set_var ("listid"           , $listid       );
$t->set_var ("dealwithman"      , $dealwithman  );
$t->set_var ("money"            , $money        );
$t->set_var ("date"             , $date         );
$t->set_var ("fkdate"           , $fkdate       );
$t->set_var ("memo_z"           , $memo_z       );
$t->set_var ("paytype"          , $paytype      );
$t->set_var ("count"            , $count        );
$t->set_var ("list_disabled"    , $list_disabled);
$t->set_var ("state"    , $state);

$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse ( "out", "paymoneylist_view" ); # 最后输出页面
function upallmoney($listid){
	  $db  = new DB_test();
	 $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
		$arr_couponset = $db->get_one($query);
		$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
	    $query = "select  (fd_agpm_paymoney) as allmoney,fd_agpm_paytype as paytype 
              from tb_paymoneylistdetail  
              left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id                  
              where fd_pymyltdetail_paymoneylistid = '$listid'   ";
    $db->query($query);  
    if($db->nf()){
		  while($db->next_record())
		  {
		  $allmoney  = $db->f(allmoney);
		  $paytype  = $db->f(paytype); 
		    
		 if($paytype=='coupon')
		{
		    $value['payfee'] = $allmoney  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$vallmoney +=$allmoney -$value['payfee'];
			//$allmoney = $allmoney  * (1-$couponfee*0.01);
			//$allmoney = $arr_couponset['maxfee']<$allmoney?$arr_couponset['maxfee']:$allmoney;
		}else
		{
			$vallmoney += $allmoney;
		}
		}
		$query = "update tb_paymoneylist set fd_pymylt_money = '$vallmoney' where fd_pymylt_id = '$listid'";   
		$db->query($query);
		
    } 
		
}
?>