<?php
$thismenucode = "sys";
require ("../include/common.inc.php");
include ("../include/pageft.php");

$db = new DB_test();
$db1 = new DB_test();

if($brows_rows != "") {
	 $loginreportline = $brows_rows;
}

switch ($action) {
	case "selmore" : //新增数据 
        $query = "update tb_agentpaymoneylist set fd_agpm_state = 1 ,fd_agpm_spman = '$loginstaname',fd_agpm_spdate=now() where fd_agpm_id = '$selid'";
		$db->query($query);
		$action = "";
		break;

	default :
		break;
}


if (!empty ($sel_condit)) {
	$querywhere .= " and " . $sel_condit . " like '%" . $txtCondit . "%'";
}
if (!empty ($selectpaytype)) {
	$querywhere .= " and fd_agpm_paytype ='$selectpaytype'";
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("paymoneylist_dzview", "paymoneylist_dzview.html");

//显示列表
$t->set_block("paymoneylist_dzview", "prolist", "prolists");
$query = "select 
				case 
				when fd_agpm_payrq ='01' then '请求交易'
				when fd_agpm_payrq ='00' then '交易完成'
				else '取消交易' END  payrq,
				case 
				when fd_agpm_payfeedirct ='s' then ' 收款方'
				when fd_agpm_payfeedirct ='f' then '付款方'
				END  payfeedirct,
					fd_appmnu_name  as	 paytype,
				 fd_agpm_id               as agpm_id,
                 fd_agpm_bkordernumber    as bkordernumber,
                 fd_agpm_bkntno           as bkntno,
                 fd_paycard_key           as paycardkey,
                 fd_author_truename       as author,
                 fd_agpm_paydate          as paydate,
                 fd_agpm_fucardno         as fucardno,
                 fd_agpm_current          as current,
                 
                 fd_agpm_paymoney         as paymoney ,
                 fd_agpm_payfee           as payfee,
                 case 
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney - fd_agpm_payfee)
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 else fd_agpm_paymoney END money,
                 fd_agpm_arrivemode       as arrivemode,
                 fd_agpm_arrivedate       as arrivedate,
                 fd_sdcr_name             as sdcrname,
                 fd_agpm_memo             as memo
          from tb_agentpaymoneylist   
          left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid
          left join tb_sendcenter  on fd_sdcr_id  = fd_agpm_sdcrid
		   left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
          where fd_agpm_payrq = '00' and fd_agpm_state = 0 and  fd_appmnu_istabno = 1  $querywhere
          order by fd_agpm_paydate desc";
$db->query($query);
//and fd_agpm_paytype <>'recharge'
$total = $db->num_rows($result);
pageft($total, 20, $url);
if ($firstcount < 0) {
	$firstcount = 0;
}

$count =$firstcount;

$query = "$query limit $firstcount,$displaypg";	  
$db->query($query);
$rows = $db->num_rows();
$arr_result = $db->getFiledData('');
$count = 0;
foreach ($arr_result as $key => $value) {
	     $count++;
	     $value['count']= $count;	
	     $value['arrivemode'] = "T+".$value['arrivemode'];
	     if($value['payfeedirct']=="付款方"){$value['paymoney']=$value['paymoney']+$value['payfee'];}
		 $all_paymoney += $value['paymoney'];
	     $all_payfee += $value['payfee'];
	     $all_money += $value['money'];
	     $value['action']='<a href="#" onclick="submit_sel(\''.$value['agpm_id'].'\',\''.$value['bkordernumber'].'\')">到账审批</a>';     
	     $t->set_var($value);
	     $t->parse("prolists", "prolist", true);
}

if($count == 0){

  $t->parse("prolists", "", true);
}

$querywhere = urlencode($querywhere);
$t->set_var("pagenav", $pagenav);          //分页变量
$t->set_var("brows_rows", $brows_rows);

$arr_temp = array (
	"-请选择-",
	"银联订单号",
	"银行交易流水号",
	"客户名称",
	"明盛公户"
);
$arr_temp2 = array (
	"",
	"fd_agpm_bkordernumber",
	"fd_agpm_bkntno",
	"fd_author_truename",
	"fd_sdcr_name"
);
$condition = makeselect($arr_temp, $sel_condit, $arr_temp2);

$arr_paytypeid=array("","coupon","creditcard","recharge","repay","order","tfmg","suptfmg");
$arr_paytypename=array("所有类型","购买抵用券","信用卡还款","充值","还贷款","订单付款","转账汇款","超级转账");
$selectpaytype=makeselect($arr_paytypename,$paytype,$arr_paytypeid);

$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var("pagenav", $pagenav); //分页变量
$t->set_var("selectpaytype", $selectpaytype);
$t->set_var("conditions", $condition);
$t->set_var("txtCondit", $txtCondit);

$t->set_var("listid", $listid); //单据id 
$t->set_var("payorin", $payorin); //付款还是收款

$t->set_var("count", $count);
$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   );

$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址
$t->set_var("error", $error);

$t->set_var("checkid", $checkid); //批量删除商品ID   

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "paymoneylist_dzview"); # 最后输出页面
?>