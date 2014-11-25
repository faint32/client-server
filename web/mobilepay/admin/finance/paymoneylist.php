<?php
$thismenucode = "2n601";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php"); //调用生成单据编号文件    
require ("../function/changemoney.php"); //调用应付应付金额文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/cashglide.php"); //调用现金流水帐文件
require ("../function/currentaccount.php"); //调用往来对帐单文件


$db  = new DB_test();
$db1 = new DB_test();
$gourl = "tb_agentpaymoney_sq_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

if(!empty($action) or !empty ($end_action) or !empty ($listid)) {
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' and fd_pymylt_state != 0 ";
	$db->query($query);
	if($db->nf()){
		echo "<script>alert('该单据已经不在本步骤，不能再修改，请查证')</script>";		
		$action = "";
		$end_action = "";
	}
}

switch ($action) {
	
	case "new" : //删除细节表数据
		if($ischangelistno != 1) {
			$listno = listnumber_update(9); //保存单据
		}
		
		$query = "select * from tb_paymoneylist where fd_pymylt_no = '$listno'";
		$db->query ( $query );
		if($db->nf()){
			$error = "该单据已经存在！请查证！";
		}else{
			$query = "insert into tb_paymoneylist(
                fd_pymylt_no      ,  fd_pymylt_dealwithman    , fd_pymylt_date   ,
                fd_pymylt_fkdate  ,  fd_pymylt_memo    ,fd_pymylt_paytype
                )values(
                '$listno'         , '$dealwithman'            , '$date'          ,
                '$fkdate'         , '$memo_z'       ,'$selectpaytype'
                )";
			//echo $query;exit;
			$db->query ( $query ); //插入付款单
			$listid = $db->insert_id(); //取出刚插入的记录的主关键值的id
		}
		
		$action = "";
		break;
		
	case "edit" : //新增数据
		$query = "select * from tb_paymoneylist where fd_pymylt_no = '$listno' and fd_pymylt_id  <> '$listid'";
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "该单据已经存在！请查证！";
		}else{
			if(!empty($selectpaytype)){
				$selectpaytype = $selectpaytype;
			}
			if(!empty($paytypee)){
				$selectpaytype = $paytypee;
			}
			$query = "update tb_paymoneylist set
                fd_pymylt_no     = '$listno'   ,  fd_pymylt_dealwithman  = '$dealwithman'  , fd_pymylt_date = '$date'   ,
                fd_pymylt_fkdate = '$fkdate'   ,  fd_pymylt_memo         = '$memo_z'     ,fd_pymylt_paytype='$selectpaytype' 
                where fd_pymylt_id = '$listid'";
				//echo $query;exit;
			$db->query ( $query ); //修改付款单
		}
		$action = "";
		break;
	
	case "del1" : //删除细节表数据-发票细节表
		for($i = 0; $i < count ( $checkid ); $i ++){
			if(!empty( $checkid [$i] )) {							
				$query = "select * from tb_paymoneylistdetail where fd_pymyltdetail_id = '$checkid[$i]'";
				$db->query($query);
				if($db->nf()){
		      $db->next_record ();
		      $agpmid  = $db->f(fd_pymyltdetail_agpmid);
		      
		      $query = "update tb_agentpaymoneylist set fd_agpm_paystate = 0 where fd_agpm_id = '$agpmid'";
				  $db->query ( $query );
				
				}
				
				$query = "delete from tb_paymoneylistdetail where fd_pymyltdetail_id = '$checkid[$i]'";
				$db->query($query);								
			}
		}
		
		upallmoney($listid);
		
		$action = "";
		break;
	
	default :
		break;
}

//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if($end_action == "endsave") {
	$todaydate = date ( "Y-m-d" );
	if ($todaydate < $date) {
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action = "";
	}
}

switch ($end_action) {
	case "endsave" :
	        upallmoney($listid);
			$query = "update tb_paymoneylist set fd_pymylt_state = 1, fd_pymylt_datetime = now()
                where fd_pymylt_id = '$listid'";
			$db->query ( $query ); //修改付款单
			
			require ("../include/alledit.2.php");
			Header ( "Location: $gotourl" );
		
		break;
	
	case "dellist" : //删除整条单据数据
		//--------------------start----------------------
		$query = "delete from tb_paymoneylistdetail where fd_pymyltdetail_paymoneylistid = '$listid'";
		$db->query ( $query );		
		$query = "delete from tb_paymoneylist  where fd_pymylt_id = '$listid'";
		$db->query ( $query ); //删除总表的数据		
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "paymoneylist", "paymoneylist.html" );

$count = 0;
if(empty($listid)){
	$date   = date ( "Y-m-d" );   
	$fkdate = date ( "Y-m-d" ); 
	
	$dealwithman = $loginstaname;
	$isdel="disabled";
	$action = "new";

}else{ //如果销售单id好不是为空
	$action = "edit";
	$isdel="";
	 upallmoney($listid);
	$query = "select * from tb_paymoneylist  where fd_pymylt_id = '$listid' ";
	$db->query ( $query );
	if($db->nf()){
		$db->next_record ();
		$listno         = $db->f(fd_pymylt_no);
		$dealwithman    = $db->f(fd_pymylt_dealwithman);
		$money          = $db->f(fd_pymylt_money);
		$date           = $db->f(fd_pymylt_date);
		$fkdate         = $db->f(fd_pymylt_fkdate);   
		$memo_z         = $db->f(fd_pymylt_memo);   
		$thmemo         = $db->f(fd_pymylt_thmemo);   
		$thman          = $db->f(fd_pymylt_thman); 
		$paytype          = $db->f(fd_pymylt_paytype);
		
	}
	
}

$arr_paytypeid=array("coupon","creditcard","recharge","repay","order","tfmg","suptfmg");
$arr_paytypename=array("购买抵用券","信用卡还款","充值","还贷款","订单付款","转账汇款","超级转账");
$selectpaytype=makeselect($arr_paytypename,$paytype,$arr_paytypeid);
if(!empty($thmemo)){
	$th_display = "";
}else{
  $th_display = "none";
}

 $query = "select fd_couponset_fee as fee ,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
		$arr_couponset = $db->get_one($query);
		$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
$t->set_block ( "paymoneylist", "prolist", "prolists" );
$query = "select
                 case
                 when fd_agpm_payfeedirct ='s' then ' 收款方'
                 when fd_agpm_payfeedirct ='f' then '付款方'
                 END  payfeedirct,

                 case 
                 when fd_agpm_paytype ='coupon' then '购买抵用券'
                 when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
                 "when fd_agpm_paytype ='recharge' then        '充值'" .
                 "when fd_agpm_paytype ='repay' then       '还贷款'" .
                 "when fd_agpm_paytype ='order' then '订单付款'" .
                 "when fd_agpm_paytype ='tfmg' then '转账汇款'" .
                 "when fd_agpm_paytype ='suptfmg' then '超级转账' 
                 else '其他业务' END  paytype,

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
	    // echo ($value['paytypee']);
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
	     $paytypeename = "<input name='paytypee' type='hidden' value='".$value['paytypee']."' />";  
	     $list_disabled = "";	     
       $t->set_var($value);
       $t->parse("prolists", "prolist", true);	
}

if(empty($arr_result)){
$ischoose='';
  $t->parse("prolists", "", true);	
}else{
	$ischoose='disabled';
}

if(empty ($listno)){//显示暂时的单据编号
	$listno = listnumber_view ("9");
}

if(empty($listid)) { //如果已经暂存，提交跟删除按钮可用，否则不可用
	$tijiao_dis = "disabled";
}else {
  $tijiao_dis = "";
}

$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var ("paytypeename"            , $paytypeename   ); 
$t->set_var ("isdel"            , $isdel   ); 
$t->set_var ("ischoose"         , $ischoose   ); 
$t->set_var ("tijiao_dis"       , $tijiao_dis   ); 
$t->set_var ("listno"           , $listno       ); 
$t->set_var ("listid"           , $listid       ); 
$t->set_var ("dealwithman"      , $dealwithman  ); 
$t->set_var ("money"            , $money        ); 
$t->set_var ("date"             , $date         ); 
$t->set_var ("fkdate"           , $fkdate       ); 
$t->set_var ("memo_z"           , $memo_z       ); 
$t->set_var ("count"            , $count        );
$t->set_var ("list_disabled"    , $list_disabled);
$t->set_var ("th_display"       , $th_display   );
$t->set_var ("thman"            , $thman        );
$t->set_var ("thmemo"           , $thmemo       );
$t->set_var ("paytype"          , $paytype       );
$t->set_var ("selectpaytype"    , $selectpaytype       );

$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   );      
      

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "paymoneylist" ); # 最后输出页面

function upallmoney($listid){
	  $db  = new DB_test();
	 $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
		$arr_couponset = $db->get_one($query);
		$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
	    $query = "select  (fd_agpm_paymoney) as allmoney,fd_agpm_paytype as paytype 
              from tb_paymoneylistdetail  
              left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id                  
              where fd_pymyltdetail_paymoneylistid = '$listid'  ";
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
    } 
		$query = "update tb_paymoneylist set fd_pymylt_money = '$vallmoney' where fd_pymylt_id = '$listid'";   
		$db->query($query);
		
}
?>

