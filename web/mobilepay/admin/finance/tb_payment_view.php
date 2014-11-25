<?
$thismenucode = "10n002";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agency_monthtable_view","tb_payment_view.html");
$t->set_block("agency_monthtable_view", "BXBK", "bxbks");

$weekarray = array("��","һ","��","��","��","��","��");

if ($bgcolor=="#FFFFFF") {
	$bgcolor="#F1F4F9";
}else{
	$bgcolor="#FFFFFF";
}

if($endday<$startday){
  $temdayval = $startday;
  $startday = $endday;
  $endday = $temdayval;  
}

switch($changeday){
  case '1':
    //����
    $startday = date("Y-m-d");
	$endday = date("Y-m-d");
    break;
  case '2':
    //һ��ǰ
    $startday = date("Y-m-d",strtotime("-2 week"));
	$endday = date("Y-m-d");
    break;
  case '3':
    //һ��ǰ
    $startday = date("Y-m-d",strtotime("-1 month"));
	$endday = date("Y-m-d");
    break;	
  case '4':
    //3��ǰ
    $startday = date("Y-m-d",strtotime("-3 month"));
	$endday = date("Y-m-d");
    break;		
}

if((strtotime($endday)-strtotime($startday))/86400 >90){
  $error = "��ѯ���䲻�ܴ���90��";
  $startday = "";
  $endday = "";
}

if(empty($startday)&&empty($endday)){
  $startday = date("Y-m-d",strtotime("-1 week"));
  $endday = date("Y-m-d");
}


if(empty($startday)||empty($endday)){
  $isnodata = " and 1=2 ";
  $isnodata2 = "Y";
}else{
  $isnodata = " and 1=1 ";
  $isnodata2 = "N";
}


//��ȡ�Ŵ�����

$query = "select fd_repmglist_id,fd_repmglist_authorid,fd_repmglist_paycardid,sum(fd_repmglist_paymoney) as paymoney,DATE_FORMAT(fd_repmglist_paydate,'%Y-%m-%d') as  paydate from tb_repaymoneyglist where (fd_repmglist_paydate>='$startday' and fd_repmglist_paydate<='$endday') and fd_repmglist_payrq = '1' and fd_repmglist_state = '9' $querydate $isnodata  group by paydate";
$db->query($query);
while($db->next_record()){
  
  $arrRepaymoneygData[$db->f('paydate')] = array('id'=>$db->f('fd_repmglist_id'),'authorid'=>$db->f('fd_repmglist_authorid'),
                                'paycardid'=>$db->f('fd_repmglist_paycardid'),'paymoney'=>$db->f('paymoney'),
								'paydate'=>$db->f('paydate')
								); 
}

//��ȡת�˻������

$query = "select fd_tfmglist_id,fd_tfmglist_authorid,fd_tfmglist_paycardid,sum(fd_tfmglist_paymoney) as paymoney,DATE_FORMAT(fd_tfmglist_paydate,'%Y-%m-%d') as  paydate  from tb_transfermoneyglist where (fd_tfmglist_paydate>='$startday' and fd_tfmglist_paydate<='$endday') and fd_tfmglist_payrq = '1' and fd_tfmglist_state = '9' $querydate $isnodata group by paydate";
$db->query($query);
while($db->next_record()){
  $arrTransfermoneygData[$db->f('paydate')] = array('id'=>$db->f('fd_tfmglist_id'),'authorid'=>$db->f('fd_tfmglist_authorid'),
                                'paycardid'=>$db->f('fd_tfmglist_paycardid'),'paymoney'=>$db->f('paymoney'),
								'paydate'=>$db->f('paydate')
								); 
}


//��ȡ���ÿ���������
$query = "select fd_ccglist_id,fd_ccglist_authorid,fd_ccglist_paycardid,sum(fd_ccglist_paymoney) as paymoney,DATE_FORMAT(fd_ccglist_paydate,'%Y-%m-%d') as  paydate   from tb_creditcardglist where (fd_ccglist_paydate>='$startday' and fd_ccglist_paydate<='$endday') and fd_ccglist_payrq = '1' and fd_ccglist_state = '9' $querydate $isnodata group by paydate";
$db->query($query);
while($db->next_record()){
  $arrCreditcardgData[$db->f('paydate')] = array('id'=>$db->f('fd_ccglist_id'),'authorid'=>$db->f('fd_ccglist_authorid'),
                                'paycardid'=>$db->f('fd_ccglist_paycardid'),'paymoney'=>$db->f('paymoney'),
								'paydate'=>$db->f('paydate')
								); 
}


//��ȡ�Ż�ȯ����

$query = "select fd_couponglide_id,fd_couponglide_authorid,fd_couponglide_paycardid,DATE_FORMAT(fd_couponglide_datetime,'%Y-%m-%d') as  paydate  ,sum((fd_couponglide_addmoney) - (fd_couponglide_lessmoney)) as paymoney from tb_couponglide where (fd_couponglide_datetime>='$startday' and fd_couponglide_datetime<='$endday') $querydate $isnodata group by paydate";
$db->query($query);
while($db->next_record()){
  $arrCouponglideData[$db->f('paydate')] = array('id'=>$db->f('fd_couponglide_id'),'authorid'=>$db->f('fd_couponglide_authorid'),
                                'paycardid'=>$db->f('fd_couponglide_paycardid'),'paymoney'=>$db->f('paymoney'),
								'paydate'=>$db->f('paydate')
								); 
}

//ѭ��ʱ����������
$curData = $startday;
$count = 1;
$allmoney = 0;

if($isnodata2 == 'N'){
	while(true){
	  if(strtotime($curData) > strtotime($endday)){
		break;
	  }
	  

	
	$Repaymoneygpaymoney = $arrRepaymoneygData[$curData]['paymoney'];
	$Transfermoneygpaymoney = $arrTransfermoneygData[$curData]['paymoney'];
	$Creditcardgpaymoney = $arrCreditcardgData[$curData]['paymoney'];
	$Couponglidepaymoney = $arrCouponglideData[$curData]['paymoney'];
		
	$arrTemData = array('Repaymoneygpaymoney'=>$Repaymoneygpaymoney,'Transfermoneygpaymoney'=>$Transfermoneygpaymoney,
							  'Creditcardgpaymoney'=>$Creditcardgpaymoney,'Couponglidepaymoney'=>$Couponglidepaymoney
							  );  
	$allmoney += $Repaymoneygpaymoney + $Transfermoneygpaymoney + $Creditcardgpaymoney + $Couponglidepaymoney;			  
	$t->set_var($arrTemData);					  

	  $curweek = "����".$weekarray[date("w",strtotime($curData))];
	  $t->set_var("curDataWeek",$curweek);
	  $t->set_var("curData",$curData);
	  $t->set_var("tdcount",$count);
	  $fcolor = "";
	  if($curweek == '������'||$curweek == '������'){
	    $fcolor = "color:#FF0000;";
	  }
	  $t->set_var("fcolor",$fcolor);
	  $t->parse("bxbks", "BXBK", true); 
	  $curData = date("Y-m-d",strtotime("+1 day",strtotime($curData)));
	  $count++;
	};
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
$t->set_var("searchval",$searchval);



$t->set_var("skin",$loginskin);
$t->pparse("out", "agency_monthtable_view");    # ������ҳ��

?>