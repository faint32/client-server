<?
$thismenucode = "10n002";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("tb_payment_dateview","tb_payment_dateview.html");
$t->set_block("tb_payment_dateview", "BXBK", "bxbks");

$weekarray = array("日","一","二","三","四","五","六");

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

if($searchdate)
{
  
  $arr_tmpdate=explode("-",$searchdate);
  $startday = date( "Y-m-d" ,mktime(0,0,0,$arr_tmpdate[1],1,$arr_tmpdate[0]));
  $endday = date( "Y-m-d" ,mktime(0,0,0,$arr_tmpdate[1]+1,0,$arr_tmpdate[0]));
  

}

if($sdcrname)
{
	$querysdcr="and fd_agpm_sdcrid='$sdcrname'";
}

if($type)
{
	$isdisplay="";
}else{
	  $isdisplay="none";
}
switch($changeday){
  case '1':
    //当天
    $startday = date("Y-m-d");
	$endday = date("Y-m-d");
    break;
  case '2':
    //一周前
    $startday = date("Y-m-d",strtotime("-2 week"));
	$endday = date("Y-m-d");
    break;
  case '3':
    //一月前
    $startday = date("Y-m-d",strtotime("-1 month"));
	$endday = date("Y-m-d");
    break;		
}

if((strtotime($endday)-strtotime($startday))/86400 >90){
  $error = "查询区间不能大于90天";
  $startday = "";
  $endday = "";
}

if(empty($startday)&&empty($endday)){

 $nowdate = date( "Y-m-d" ,time());
 $arr_tmpdate=explode("-",$nowdate);
 
 $startday = date( "Y-m-d" ,mktime(0,0,0,$arr_tmpdate[1],1,$arr_tmpdate[0]));
  $endday = date( "Y-m-d" ,mktime(0,0,0,$arr_tmpdate[1]+1,0,$arr_tmpdate[0]));
}


if(empty($startday)||empty($endday)){
  //$isnodata = " and 1=2 ";
  $isnodata2 = "Y";
}else{
  //$isnodata = " and 1=1 ";
  $isnodata2 = "N";
}



require ("../report/getpaycardmenu.php");//获取交易类型菜单

$query = "select
		fd_agpm_sdcrpayfeemoney as sdcrpayfeemoney,
		fd_agpm_sdcragentfeemoney as sdcragentfeemoney,
		fd_agpm_paytype as paytype,
		date_format(fd_agpm_paydate,'%Y-%m-%d') as paydate ,
	    fd_agpm_paymoney as paymoney," .
	   "fd_agpm_agentmoney as agentmoney,
		fd_agpm_payfee as payfee,
		date_format(fd_agpm_agentdate,'%Y-%m-%d') as agentdate ,
		fd_agpm_payfeedirct as payfeedirct
		from tb_agentpaymoneylist 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
		where (fd_agpm_paydate>='$startday' and fd_agpm_paydate<='$endday') $querysdcr
		and fd_agpm_payrq='00' and  fd_appmnu_istabno = 1  order by fd_agpm_paydate desc ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		$paytype=$db->f(paytype);
		$paydate=$db->f(paydate);
		$agentdate=$db->f(agentdate);
		$payfeedirct=$db->f(payfeedirct);
		
		$arr_result['money'][$paytype][$paydate]['paymoney']    +=$db->f(paymoney);
		$arr_result['cbmoney'][$paytype][$paydate]['sdcrpayfeemoney'] +=$db->f(sdcrpayfeemoney);
		$arr_result['cbmoney'][$paytype][$agentdate]['sdcragentfeemoney'] +=$db->f(sdcragentfeemoney);
		$arr_result['agentmoney'][$paytype][$agentdate]['agentmoney']    +=$db->f(agentmoney);
		
		$arr_result['fee'][$paytype][$paydate]['payfee']    +=$db->f(payfee);
		
		$arr_result['money'][$paydate]['allpaymoney']    +=$db->f(paymoney);
		$arr_result['agentmoney'][$agentdate]['allagentmoney']    +=$db->f(agentmoney);
		$arr_result['fee'][$paydate]['allpayfee']    +=$db->f(payfee);
		$arr_result['cbmoney'][$paydate]['allsdcrpayfeemoney'] +=$db->f(sdcrpayfeemoney);
		$arr_result['cbmoney'][$agentdate]['allsdcragentfeemoney'] +=$db->f(sdcragentfeemoney);
	
		
	}
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

	  $fcolor = "";
	  if($curweek == '星期六'||$curweek == '星期日'){
	    $fcolor = "color:#FF0000;";
	  }
	  
	  
	  foreach($arr_paytype as $key=> $value)
	{
		$paytype=$key;
		$cbmoney=$arr_result['cbmoney'][$paytype][$curData]['sdcrpayfeemoney'] +$arr_result['cbmoney'][$paytype][$curData]['sdcragentfeemoney'] ;
		$payfee=$arr_result['fee'][$paytype][$curData]['payfee'];
		
		$paymoney=changenum('2',$arr_result['money'][$paytype][$curData]['paymoney']);
		$agentmoney=changenum('2',$arr_result['agentmoney'][$paytype][$curData]['agentmoney']);
		$cbmoney=changenum('2',$cbmoney);
		$payfee=changenum('2',$payfee);
		$showpaytype .='<TD class="right">
		<a href="payment_detail.php?type=day&paytype='.$paytype.'&datetime='.$curData.'">'.$paymoney.'</a>
		</TD>
        <TD class="right">
		<a href="payment_detail.php?type=day&paytype='.$paytype.'&datetime='.$curData.'">'.$agentmoney.'</a>
		</TD>
		<TD class="right">
		<a href="payment_detail.php?type=day&paytype='.$paytype.'&datetime='.$curData.'">'.$cbmoney.'</a>
		</TD>
		<TD style="border-right:1px #222 solid"  class="right">
		<a href="payment_detail.php?type=day&paytype='.$paytype.'&datetime='.$curData.'">'.$payfee.'</a>
		</TD>';
			
		
	
		$allmoney       +=$arr_result['money'][$paytype][$curData]['paymoney'];
		$allagentmoney  +=$arr_result['agentmoney'][$paytype][$curData]['agentmoney'];
		$allcbmoney     +=$cbmoney;
		$allpayfee      +=$payfee;
		
		
		
		
		$arr_allmoney[$paytype]['paymoney'] += $arr_result['money'][$paytype][$curData]['paymoney'];
		$arr_allmoney[$paytype]['agentmoney'] += $arr_result['agentmoney'][$paytype][$curData]['agentmoney'];
		$arr_allmoney[$paytype]['cbmoney'] +=$cbmoney;
		$arr_allmoney[$paytype]['payfee'] +=$arr_result['fee'][$paytype][$curData]['payfee'];		

	}
		
		
		$arr_result['money'][$curData]['allpaymoney']=changenum('2',$arr_result['money'][$curData]['allpaymoney']);
		
		$arr_result['agentmoney'][$curData]['allagentmoney']=changenum('2',$arr_result['agentmoney'][$curData]['allagentmoney']);
		$arr_result['fee'][$curData]['allpayfee']=changenum('2',$arr_result['fee'][$curData]['allpayfee']);
		
		$alldaypaymoney='<a href="payment_detail.php?type=day&datetime='.$curData.'">'.$arr_result['money'][$curData]['allpaymoney'].'</a>';   
		$alldayagentmoney='<a href="payment_detail.php?type=day&datetime='.$curData.'">'.$arr_result['agentmoney'][$curData]['allagentmoney'].'</a>';   
	
		$daycbmoney=$arr_result['cbmoney'][$curData]['allsdcrpayfeemoney']+$arr_result['cbmoney'][$curData]['allsdcragentfeemoney'];
		$daycbmoney=changenum('2',$daycbmoney);
		$alldaycbmoney='<a href="payment_detail.php?type=day&datetime='.$curData.'">'.$daycbmoney.'</a>';
	
		$alldaypayfee='<a href="payment_detail.php?type=day&datetime='.$curData.'">'.$arr_result['fee'][$curData]['allpayfee'].'</a>';   
	
	$t->set_var($arrTemData);					  
	  $curweek = "星期".$weekarray[date("w",strtotime($curData))];

	  $t->set_var("alldaypaymoney",$alldaypaymoney);
	  $t->set_var("alldayagentmoney",$alldayagentmoney);
	  $t->set_var("alldaycbmoney",$alldaycbmoney);
	  $t->set_var("alldaypayfee",$alldaypayfee);
	  $t->set_var("curDataWeek",$curweek);
	  $t->set_var("curData",$curData);
	  $t->set_var("tdcount",$count);
	  $t->set_var("fcolor",$fcolor);
	  $t->set_var("showpaytype",$showpaytype);
	  $t->parse("bxbks", "BXBK", true); 
	  $showpaytype="";
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


$allmoney=changenum('2',$allmoney);
$allcbmoney=changenum('2',$allcbmoney);
$allpayfee=changenum('2',$allpayfee);
$allagentmoney=changenum('2',$allagentmoney);


 $arr_startday=explode("-",$startday);
 $arr_endday=explode("-",$endday);
 
$str_startday=$arr_startday[0]."-".$arr_startday[1];
$str_endday=$arr_endday[0]."-".$arr_endday[1];


if($str_endday==$str_startday)
{
	$curData=$str_endday;
}else{
	$curData=$str_startday."@@".$str_endday;
}
$allmoney  = '<a href="payment_detail.php?type=day&datetime='.$curData.'&collect=day">'.$allmoney.'</a>';
$allcbmoney  = '<a href="payment_detail.php?type=day&datetime='.$curData.'&collect=day">'.$allcbmoney.'</a>';
$allpayfee  = '<a href="payment_detail.php?type=day&datetime='.$curData.'&collect=day">'.$allpayfee.'</a>';
$allagentmoney  = '<a href="payment_detail.php?type=day&datetime='.$curData.'&collect=day">'.$allagentmoney.'</a>';


foreach($arr_paytype as $key=> $value)
{
	$paymoney=changenum('2', $arr_allmoney[$key]['paymoney']);
	$agentmoney=changenum('2', $arr_allmoney[$key]['agentmoney']);
	$cbmoney=changenum('2', $arr_allmoney[$key]['cbmoney']);
	$payfee=changenum('2', $arr_allmoney[$key]['payfee']);
	
	$showall.=' <td class="right"><a href="payment_detail.php?type=day&datetime='.$curData.'&paytype='.$key.'&collect=day">'.$paymoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=day&datetime='.$curData.'&paytype='.$key.'&collect=day">'.$agentmoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=day&datetime='.$curData.'&paytype='.$key.'&collect=day">'.$cbmoney.'</a></td>
              <td class="right" style="border-right:1px #222 solid"><a href="payment_detail.php?type=day&datetime='.$curData.'&paytype='.$key.'&collect=day">'.$payfee.'</a></td>';
}


$query="select fd_sdcr_id as sdcrid ,fd_sdcr_name as sdcrname   from tb_sendcenter";
$db->query($query);
$arr_data=$db->getFiledData();
foreach($arr_data as $value)
{
	$arr_sdcrid[]=$value['sdcrid'];
	$arr_sdcrname[]=$value['sdcrname'];
}

$sdcrname=makeselect($arr_sdcrname,$sdcrname,$arr_sdcrid);

$t->set_var("sdcrname",$sdcrname);
$t->set_var("type",$type);
$t->set_var("showall",$showall);
$t->set_var("typeyear",$typeyear);
$t->set_var("isdisplay",$isdisplay);
$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("startday",$startday);
$t->set_var("endday",$endday);
$t->set_var("authorname",$authorname);
$t->set_var("paycarnum",count($arrPaycardData));



$t->set_var("allmoney",$allmoney);
$t->set_var("allcbmoney",$allcbmoney);
$t->set_var("allpayfee",$allpayfee);
$t->set_var("allagentmoney",$allagentmoney);



$t->set_var("bgcolor",$bgcolor);
$t->set_var("searchval",$searchval);



$t->set_var("skin",$loginskin);
$t->pparse("out", "tb_payment_dateview");    # 最后输出页面

?>