<?php
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;
$count = 0;
$t = new Template('.', "keep");
$t->set_file("listprint","listprint.html");

$query = "select * from tb_paymoneylist  where fd_pymylt_id = '$listid' ";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$listno         = $db->f(fd_pymylt_no);
	$dealwithman    = $db->f(fd_pymylt_dealwithman);
	$money          = $db->f(fd_pymylt_money);
	$date           = $db->f(fd_pymylt_date);
	$fkdate         = $db->f(fd_pymylt_fkdate); 
}

$t->set_block ( "listprint", "prolist", "prolists" );
$query = "select fd_pymyltdetail_id               as vid,
                 fd_agpm_bkntno                   as bkntno,
                 fd_paycard_key                   as paycardkey,
                 fd_author_truename               as author,
                 fd_agpm_paytype                  as paytype,
                 fd_agpm_paydate                  as paydate,
                 fd_agpm_shoucardno               as shoucardno,
                 fd_agpm_shoucardbank             as shoucardbank,
                 fd_agpm_shoucardman              as shoucardman,
                 fd_agpm_shoucardmobile           as shoucardmobile,
                 fd_agpm_current                  as current,
                 fd_agpm_paymoney                 as paymoney ,
                 fd_agpm_payfee                   as payfee,
                 fd_agpm_money                    as money,
                 fd_agpm_arrivemode               as arrivemode,
                 fd_agpm_arrivedate               as arrivedate,
                 fd_paycardaccount_accountname    as accountname,
                 fd_paycardaccount_accountnum     as accountnum
                 from tb_paymoneylistdetail  
                 left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id 
                 left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
                 left join tb_author  on fd_author_id  = fd_agpm_authorid 
                 left join  tb_paycardaccount  on fd_paycard_paycardaccount = fd_paycardaccount_id   
                 where fd_pymyltdetail_paymoneylistid = '$listid'
                 order by fd_agpm_bkntno"; 
$db->query($query);
$arr_result = $db->getFiledData('');
$count = 0; 
$list_disabled = "disabled";
//print_r($arr_result);
foreach ($arr_result as $key => $value) {
	     $count++;
	     $value['count']= $count;	
	     $value['arrivemode']= "T+".$value['arrivemode'];
	     
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


$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var ("listno"           , $listno       ); 
$t->set_var ("listid"           , $listid       ); 
$t->set_var ("dealwithman"      , $dealwithman  ); 
$t->set_var ("money"            , $money        ); 
$t->set_var ("date"             , $date         ); 
$t->set_var ("fkdate"           , $fkdate       ); 
$t->set_var ("memo_z"           , $memo_z       ); 
$t->set_var ("count"            , $count        );
$t->set_var ("list_disabled"    , $list_disabled);
     
$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   ); 

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse("out", "listprint"); //   # 最后输出页面

?> 