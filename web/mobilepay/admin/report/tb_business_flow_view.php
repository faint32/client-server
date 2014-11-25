<?
$thismenucode = "10n001";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agency_monthtable_view","tb_business_flow_view.html");
$t->set_block("agency_monthtable_view", "BXBK", "bxbks");

$t->set_block("BXBK", "BXBK2", "bxbk2s");
$weekarray = array("日","一","二","三","四","五","六");

if($bgcolor=="#FFFFFF") {
	$bgcolor="#F1F4F9";
}else{
	$bgcolor="#FFFFFF";
}

if($endday<$startday){
  $temdayval = $startday;
  $startday  = $endday;
  $endday    = $temdayval;  
}

switch($changeday){
  case '1':
    //当天
    $startday = date("Y-m-d");
	  $endday = date("Y-m-d");
  break;
  case '2':
    //一周前
    $startday = date("Y-m-d",strtotime("-1 week"));
	  $endday = date("Y-m-d");
  break;
  case '3':
    //一月前
    $startday = date("Y-m-d",strtotime("-1 month"));
	  $endday = date("Y-m-d");
  break;	
  case '4':
    //3月前
    $startday = date("Y-m-d",strtotime("-3 month"));
	  $endday = date("Y-m-d");
  break;		
}

if((strtotime($endday)-strtotime($startday))/86400 >90){
  $error = "查询区间不能大于90天";
  $startday = "";
  $endday = "";
}

if(empty($startday)&&empty($endday)){
  $startday = date("Y-m-d",strtotime("-1 week"));
  $endday = date("Y-m-d");
}

$querywhere = "";
if($searchval!='搜商户'){
  $querywhere = " and (fd_author_username = '$searchval' or fd_author_mobile = '$searchval') "; 
}


if(empty($startday)||empty($endday)||$searchval!='搜商户'){
  //$isnodata = " and 1=2 ";
  $isnodata2 = "Y";
}else{
  //$isnodata = " and 1=1 ";
  $isnodata2 = "N";
}


//获取刷卡器数据
$query = "select * from tb_author 
          left join tb_paycard on (fd_paycard_authorid = fd_author_id  and fd_paycard_active = '1') 
          where fd_author_state = '9' and fd_author_isstop = '0' $querywhere  $isnodata";
$db->query($query);
while($db->next_record()){
     $arrPaycardData[] = array( 'id' => $db->f('fd_paycard_id')   ,
                                'key'=> $db->f('fd_paycard_key')
                              ); 
     $searchvalid = $db->f('fd_author_id');
     $authorname  = $db->f('fd_author_username');
}

if(!empty($searchvalid)){
	$querydate = " and fd_repmglist_authorid = '".$searchvalid."'";
}

//获取信贷数据
$query = "select fd_repmglist_id,fd_repmglist_authorid,fd_repmglist_paycardid,
                 sum(fd_repmglist_paymoney) as paymoney,DATE_FORMAT(fd_repmglist_paydate,'%Y-%m-%d') as  paydate 
                 from tb_repaymoneyglist 
                 where  (fd_repmglist_paydate>='$startday' 
                 and fd_repmglist_paydate<='$endday') and fd_repmglist_payrq = '00' and fd_repmglist_state = '9' 
                 $querydate $isnodata  
                 group by fd_repmglist_paycardid,paydate";
$db->query($query);
while($db->next_record()){ 
     $arrRepaymoneygData[$db->f('paydate').'-'.$db->f('fd_repmglist_paycardid')] = array('id'        => $db->f('fd_repmglist_id'),
                                                                                         'authorid'  => $db->f('fd_repmglist_authorid'),
                                                                                         'paycardid' => $db->f('fd_repmglist_paycardid'),
                                                                                         'paymoney'  => $db->f('paymoney'),
								                                                                         'paydate'   => $db->f('paydate')
								                                                                         ); 					                                                                         
}

if(!empty($searchvalid)){
	$querydate = " and fd_tfmglist_authorid = '".$searchvalid."'";
}

//获取转账汇款数据
$query = "select fd_tfmglist_id,fd_tfmglist_authorid,fd_tfmglist_paycardid,sum(fd_tfmglist_paymoney) as paymoney,
                 DATE_FORMAT(fd_tfmglist_paydate,'%Y-%m-%d') as  paydate  
                 from tb_transfermoneyglist 
                 where   (fd_tfmglist_paydate>='$startday' 
                 and fd_tfmglist_paydate<='$endday') and fd_tfmglist_payrq = '00' and fd_tfmglist_state = '9' 
                 $querydate $isnodata 
                 group by fd_tfmglist_paycardid,paydate";
$db->query($query);
while($db->next_record()){
  $arrTransfermoneygData[$db->f('paydate').'-'.$db->f('fd_tfmglist_paycardid')] = array('id'       => $db->f('fd_tfmglist_id'),
                                                                                        'authorid' => $db->f('fd_tfmglist_authorid'),
                                                                                        'paycardid'=> $db->f('fd_tfmglist_paycardid'),
                                                                                        'paymoney' => $db->f('paymoney'),
								                                                                        'paydate' => $db->f('paydate')
								                                                                       ); 
}


if(!empty($searchvalid)){
	$querydate = " and fd_ccglist_authorid = '".$searchvalid."'";
}

//获取信用卡还款数据
$query = "select fd_ccglist_id,fd_ccglist_authorid,fd_ccglist_paycardid,sum(fd_ccglist_paymoney) as paymoney,
          DATE_FORMAT(fd_ccglist_paydate,'%Y-%m-%d') as  paydate   
          from tb_creditcardglist 
          where  (fd_ccglist_paydate>='$startday' and fd_ccglist_paydate<='$endday') 
          and fd_ccglist_payrq = '00' and fd_ccglist_state = '9' $querydate $isnodata 
          group by fd_ccglist_paycardid,paydate";
$db->query($query);
while($db->next_record()){
     $arrCreditcardgData[$db->f('paydate').'-'.$db->f('fd_ccglist_paycardid')] = array('id'       => $db->f('fd_ccglist_id'),
                                                                                       'authorid' => $db->f('fd_ccglist_authorid'),
                                                                                       'paycardid'=> $db->f('fd_ccglist_paycardid'),
                                                                                       'paymoney' => $db->f('paymoney'),
								                                                                       'paydate'  => $db->f('paydate')
								                                                                       ); 
}


if(!empty($searchvalid)){
	$querydate = " and fd_couponsale_authorid = '".$searchvalid."'";
}

//获取优惠券数据
$query = "select fd_couponsale_id,fd_couponsale_authorid,fd_couponsale_paycardid,
                 DATE_FORMAT(fd_couponsale_datetime,'%Y-%m-%d') as  paydate  ,
                 sum(fd_couponsale_money) as paymoney 
                 from tb_couponsale 
                 where  (fd_couponsale_datetime>='$startday' 
                 and fd_couponsale_datetime<='$endday') $querydate $isnodata 
                 group by fd_couponsale_paycardid,paydate";
$db->query($query);
while($db->next_record()){
     $arrCouponglideData[$db->f('paydate').'-'.$db->f('fd_couponsale_paycardid')] = array( 'id'       => $db->f('fd_couponsale_id'),
                                                                                           'authorid' => $db->f('fd_couponsale_authorid'),
                                                                                           'paycardid'=> $db->f('fd_couponsale_paycardid'),
                                                                                           'paymoney' => $db->f('paymoney'),
								                                                                           'paydate'  => $db->f('paydate')
								                                                                           ); 
}

//循环时间流出数据
$curData = $startday;
$count = 1;
$allmoney = 0;

if($isnodata2 == 'N'){
	while(true){
	     if(strtotime($curData) > strtotime($endday)){
		     break;
	     }
	     @reset($arrPaycardData);
	     while(list($key,$val) = each($arrPaycardData)){
		        $Repaymoneygpaymoney    = $arrRepaymoneygData[$curData.'-'.$val['id']]['paymoney'];
		        $Transfermoneygpaymoney = $arrTransfermoneygData[$curData.'-'.$val['id']]['paymoney'];
		        $Creditcardgpaymoney    = $arrCreditcardgData[$curData.'-'.$val['id']]['paymoney'];
		        $Couponglidepaymoney    = $arrCouponglideData[$curData.'-'.$val['id']]['paymoney'];
		   
		        $arrTemData = array('paycardid'             => $val['key'],
		                           'Repaymoneygpaymoney'    => $Repaymoneygpaymoney,
		                           'Transfermoneygpaymoney' => $Transfermoneygpaymoney,
		   					               'Creditcardgpaymoney'    => $Creditcardgpaymoney,
		   					               'Couponglidepaymoney'    => $Couponglidepaymoney
		   					               );  
		       $allmoney += $Repaymoneygpaymoney + $Transfermoneygpaymoney + $Creditcardgpaymoney + $Couponglidepaymoney;		  
		       $t->set_var($arrTemData);					  
		       $t->parse("bxbk2s", "BXBK2", true); 					  
	     }
	     
	     $curweek = "星期".$weekarray[date("w",strtotime($curData))];
	     $t->set_var("curDataWeek",$curweek);
	     $t->set_var("curData",$curData);
	     $t->set_var("tdcount",$count);
	     $fcolor = "";
	     if($curweek == '星期六'||$curweek == '星期日'){
	       $fcolor = "color:#FF0000;";
	     }
	     $t->set_var("fcolor",$fcolor);
	     $t->parse("bxbks", "BXBK", true); 
	     $t->set_var('bxbk2s', ''); 
	     $curData = date("Y-m-d",strtotime("+1 day",strtotime($curData)));
	     $count++;
	}
	$t->set_var("hasdateshow","");
  $t->set_var("nodateshow","none");
}else{  
  $t->set_var("hasdateshow","none");
  $t->set_var("nodateshow","");    
  $t->parse("bxbks", "BXBK", true);  
  
}

$allmoney  = number_format($allmoney, 2, ".", "");

$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("startday",$startday);
$t->set_var("endday",$endday);
$t->set_var("authorname",$authorname);
$t->set_var("paycarnum",count($arrPaycardData));
$t->set_var("allmoney",$allmoney);
$t->set_var("bgcolor",$bgcolor);
$t->set_var("searchval",$searchval?$searchval : '搜商户');



$t->set_var("skin",$loginskin);
$t->pparse("out", "agency_monthtable_view");    # 最后输出页面



?>