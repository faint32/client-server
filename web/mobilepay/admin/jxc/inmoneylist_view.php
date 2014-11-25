<?
$thismenucode = "2k220";
require ("../include/common.inc.php");
require ("../function/chanceaccount.php");
require ("../function/functionlistnumber.php");

$db  = new DB_test;
$db1 = new DB_test;


$gourl = "tb_inmoneylist_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$t = new Template(".", "keep");          //调用一个模版

if($actiontype=="draft"){
	$query = "select * from tb_inmoneylist where fd_inmylt_id = '$listid'";
  $db->query($query);   //提取帐户id号
  if($db->nf()){
  	$db->next_record();
  	$accountid   = $db->f(fd_inmylt_accountid);
  	$paymoney    = $db->f(fd_inmylt_money);
  	$dealwithman = $db->f(fd_inmylt_dealwithman);
  	$cusid       = $db->f(fd_inmylt_clientid);
  	$cusno       = $db->f(fd_inmylt_clientno);
  	$clienttype  = $db->f(fd_inmylt_type);
  	$cusname     = $db->f(fd_inmylt_clientname);
  	$staid       = $db->f(fd_inmylt_staid);
  	$date        = $db->f(fd_inmylt_date);
  	$memo_z      = $db->f(fd_inmylt_memo);
  	
  	$listno = listnumber_update(8);  //保存单据
  	
  	$query = "insert into tb_inmoneylist(
              fd_inmylt_no          ,  fd_inmylt_clientid    , fd_inmylt_type      ,
              fd_inmylt_clientname  ,  fd_inmylt_staid       , fd_inmylt_accountid ,
              fd_inmylt_money       ,  fd_inmylt_date        , fd_inmylt_memo      ,
              fd_inmylt_dealwithman ,  fd_inmylt_clientno  
              )values(
              '$listno'             , '$cusid'            , '$clienttype'    ,
              '$cusname'            , '$staid'            , '$accountid'     ,
              '$paymoney'           , '$date'             , '$memo_z'        ,
              '$dealwithman'        , '$cusno'         
              )";
    $db1->query($query);    //插入付款单
  }
}

$count = 0;
$allbalance=0;  //总共余额
if(empty($listid)){
	 $t->set_file("inmoneylist_view","inmoneylist_view.html");
	 $t->set_block("inmoneylist_view", "prolist"  , "prolists");  
   $error = "错误：该单据不存在！";
}else{   //如果收款单id号不是为空
   $action ="edit";
   $query = "select * from tb_inmoneylist 
             left join tb_account on fd_account_id = fd_inmylt_accountid
             left join tb_staffer on fd_sta_id = fd_inmylt_staid
             where fd_inmylt_id = '$listid' ";
   $db->query($query);
   if($db->nf()){
   	  $db->next_record();
   	  $listno     = $db->f(fd_inmylt_no);
   	  $accountid  = $db->f(fd_account_name);
   	  $cusid      = $db->f(fd_inmylt_clientid);
   	  $cusno      = $db->f(fd_inmylt_clientno);
   	  $cusname    = $db->f(fd_inmylt_clientname);
   	  $clienttype = $db->f(fd_inmylt_type);
   	  $date       = $db->f(fd_inmylt_date);
   	  $payallmoney = $db->f(fd_inmylt_money);
   	  $staname    = $db->f(fd_sta_name);
   	  $iskickback = $db->f(fd_inmylt_kickback);
   	  $dealwithman = $db->f(fd_inmylt_dealwithman);
   	  $memo_z      = $db->f(fd_inmylt_memo);
   	  $billid      = $db->f(fd_inmylt_billid);
   	  
   	  $arr_temp = explode("-",$date);
   	  $year = $arr_temp[0];
   	  $month = $arr_temp[1];
   	  $day = $arr_temp[2];
   }
   if($iskickback==0){
      $t->set_file("inmoneylist_view","inmoneylist_view.html"); 
   }else{
      $t->set_file("inmoneylist_view","backinmoneylist_view.html"); 
   }
}

//$thismenuqx[4]!=1 //是否有反向冲销权限
if($iskickback=="1" or $loginopendate > $date or $thismenuqx[4]!=1 or $billid>0 ){
  $iskickback = "disabled"; 
}else{
  $iskickback = ""; 
}
$t->set_var("iskickback"         , $iskickback         ); 	


switch($clienttype){
	case "1":
	  $clienttype = "客户";
	  break;
	case "2":
	  $clienttype = "供应商";
	  break;
}

$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("cusname"      , $cusname      );      //客户名称
$t->set_var("cusid"        , $cusid        );      //客户id号
$t->set_var("cusno"        , $cusno        );      //客户编号
$t->set_var("clienttype"   , $clienttype   );      //客户类型
$t->set_var("staname"      , $staname      );      //经手人
$t->set_var("memo_z"       , $memo_z       );      //备注
$t->set_var("accountid"    , $accountid    );      //帐户
$t->set_var("payallmoney"  , $payallmoney  );      //付款金额
$t->set_var("dealwithman"  , $dealwithman  );      //付款金额瞟
                                                    
$t->set_var("year"         , $year         );      //年
$t->set_var("month"        , $month        );      //月
$t->set_var("day"          , $day          );      //日  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      
                                            
$t->set_var("checkid"        , $checkid    );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "inmoneylist_view");    # 最后输出页面
?>

