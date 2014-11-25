<?php

$thismenucode = "sys";

require ("../include/common.inc.php");
include ("../include/pageft.php");

$db  = new DB_test();

if($brows_rows != "") {
	$loginreportline = $brows_rows;
}

$gourl = $linkstr.".php?listid=".$listid;
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
switch ($action) {
	case "selmore" : //新增数据 
		for($i = 0; $i < count ( $checkid ); $i ++){
			if(!empty($checkid[$i])){												
				$query = "update tb_agentpaymoneylist set fd_agpm_paystate = 1 where fd_agpm_id = '$checkid[$i]'";
				$db->query ( $query );
				
				$query = "insert tb_paymoneylistdetail set fd_pymyltdetail_agpmid = '$checkid[$i]',
				                                           fd_pymyltdetail_paymoneylistid = '$listid'";
				$db->query ( $query );
		   }		
		}
		
		upallmoney($listid);
		
		$action = "";
		
		Header ( "Location: $gotourl" );    
		break;
	
	default :
		break;
}

if (!empty($sel_condit)){
	$querywhere .= " and " . $sel_condit . " like '%" . $txtCondit . "%'";
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "selagentbkno", "selagentbkno.html" );

//显示列表
$t->set_block ( "selagentbkno", "prolist", "prolists" );
$query = "select 
                 case 
                 when fd_agpm_paytype ='coupon' then '购买抵用券'
                 when fd_agpm_paytype ='creditcard' then '信用卡还款'
                 when fd_agpm_paytype ='recharge' then        '充值'
                 when fd_agpm_paytype ='repay' then       '还贷款'
                 when fd_agpm_paytype ='order' then '订单付款'
                 when fd_agpm_paytype ='tfmg' then '转账汇款'
                 else '其他业务' END  paytype,

                 case
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney-fd_agpm_payfee)
                 else fd_agpm_paymoney END money,

                 fd_agpm_id               as agpm_id,
                 fd_agpm_bkordernumber     as bkordernumber,
                 fd_agpm_no               as agpm_no,
                 fd_agpm_bkntno           as bkntno,
                 fd_paycard_key           as paycardkey,
                 fd_author_truename       as author,
                 fd_agpm_paydate          as paydate,
                 fd_agpm_shoucardno       as shoucardno,
                 fd_agpm_shoucardbank     as shoucardbank,
                 fd_agpm_shoucardman      as shoucardman,
                 fd_agpm_shoucardmobile   as shoucardmobile,
                 fd_agpm_current          as current,
                 fd_agpm_paymoney         as paymoney ,
                 fd_agpm_payfee           as payfee,
                 fd_agpm_arrivemode       as arrivemode,
                 fd_agpm_arrivedate       as arrivedate,
                 fd_sdcr_name             as sdcrname,
                 fd_agpm_memo             as memo
                 from tb_agentpaymoneylist
                 left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
                 left join tb_author  on fd_author_id  = fd_agpm_authorid
                 left join tb_sendcenter on fd_sdcr_id = fd_agpm_sdcrid
                 where fd_agpm_payrq = '00' and fd_agpm_state = 1  and (fd_agpm_paystate = 0  or fd_agpm_paystate is null ) and fd_agpm_paytype='$paytype' $querywhere
                 order by fd_agpm_no";
$db->query($query);

$total= $db->num_rows($result);
pageft($total,20,$url);
if($firstcount < 0){
	$firstcount = 0 ;
}
$query = "$query limit $firstcount,$displaypg"; 
	  
$db->query($query);
$rows = $db->num_rows();
$arr_result = $db->getFiledData('');

$count = 0;

foreach($arr_result as $value){	
       $count++;
	     $value['count']= $count;	
	     
	     $value['arrivemode'] = "T+".$value['arrivemode'];
	     	     
	     $all_paymoney += $value['paymoney'];
	     $all_payfee += $value['payfee'];
	     $all_money += $value['money'];
	          
	     $t->set_var($value);
	     $t->parse("prolists", "prolist", true);
}


if(empty($arr_result)){
  $t->parse("prolists", "", true);	
}


$querywhere = urlencode($querywhere);



$arr_temp = array (
	"-请选择-",
	"单据编号",
	"银行交易流水号",
	"客户名称"
);
$arr_temp2 = array (
	"",
	"fd_agpm_no",
	"fd_agpm_bkntno",
	"fd_author_truename"
);
$condition = makeselect($arr_temp, $sel_condit, $arr_temp2);


$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var ("pagenav", $pagenav ); //分页变量
$t->set_var("brows_rows" ,$brows_rows);  

$t->set_var ("conditions", $condition );
$t->set_var ("txtCondit", $txtCondit );

$t->set_var ("listid", $listid ); //单据id 
$t->set_var ("payorin", $payorin ); //付款还是收款

$t->set_var ("count", $count );
$t->set_var ("all_paymoney", $all_paymoney );
$t->set_var ("all_payfee", $all_payfee );
$t->set_var ("all_money", $all_money );

$t->set_var ("action", $action );
$t->set_var ("gotourl", $gotourl ); // 转用的地址
$t->set_var ("error", $error );

$t->set_var ( "checkid", $checkid ); //批量删除商品ID   

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "selagentbkno" ); # 最后输出页面

function upallmoney($listid){
	  $db  = new DB_test();
	  
	  $query = "select  sum(fd_agpm_money) as allmoney
              from tb_paymoneylistdetail  
              left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id                  
              where fd_pymyltdetail_paymoneylistid = '$listid'";
    $db->query($query);  
    if($db->nf()){
		  $db->next_record ();
		  $allmoney  = $db->f(allmoney); 
		}     
		
		$query = "update tb_paymoneylist set fd_pymylt_money = '$allmoney' where fd_pymylt_id = '$listid'";   
		$db->query($query);
}

?>

