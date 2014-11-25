<?
$thismenucode = "10n011";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("tb_payment_monthview","tb_payment_monthview.html");
$t->set_block("tb_payment_monthview", "BXBK", "bxbks");

$weekarray = array("日","一","二","三","四","五","六");

if ($bgcolor=="#FFFFFF") {
	$bgcolor="#F1F4F9";
}else{
	$bgcolor="#FFFFFF";
}



if($typeyear)
{
	$isdisplay="";
}elseif(empty($typeyear) and $type)
{
	$isdisplay="none";
}else{
	 $isdisplay="none";
}




if(empty($startmonth)&&empty($endmonth)){

 $nowdate = date( "Y-m-d" ,time());
 $arr_tmpdate=explode("-",$nowdate);
  $startmonth = date( "Y-m" ,mktime(0,0,0,1,1,$arr_tmpdate[0]));
  $endmonth   = date( "Y-m" ,mktime(0,0,0,13,0,$arr_tmpdate[0]));
}


switch($changeday){
  case 'up':
    //上一年
 $arr_tmpdate=explode("-",$startmonth);
 $arr_tmpdate[0]=$arr_tmpdate[0]-1;
  $startmonth = date( "Y-m" ,mktime(0,0,0,1,1,$arr_tmpdate[0]));
  $endmonth   = date( "Y-m" ,mktime(0,0,0,13,0,$arr_tmpdate[0]));
 
  
    break;
  case 'down':
    //下一年
	
	$arr_tmpdate=explode("-",$startmonth);
	 $arr_tmpdate[0]=$arr_tmpdate[0]+1;
	
	  $startmonth = date( "Y-m" ,mktime(0,0,0,1,1,$arr_tmpdate[0]));
	  $endmonth   = date( "Y-m" ,mktime(0,0,0,13,0,$arr_tmpdate[0]));
    break;
		
}
$year=$arr_tmpdate[0];

if($searchyear)
{
	
  $startmonth = date( "Y-m" ,mktime(0,0,0,1,1,$searchyear));

  $endmonth   = date( "Y-m" ,mktime(0,0,0,13,0,$searchyear));
  $year=$searchyear;
   
}

if($searchdate)
{
	
	  $startmonth = date( "Y-m" ,mktime(0,0,0,1,1,$searchdate));
	  
	  $endmonth   = date( "Y-m" ,mktime(0,0,0,13,0,$searchdate));
	  $year=$searchdate;
	  
	 
}
 
if(empty($startmonth)||empty($endmonth)){
  //$isnodata = " and 1=2 ";
  $isnodata2 = "Y";
}else{
  //$isnodata = " and 1=1 ";
  $isnodata2 = "N";
}


require ("../report/getpaycardmenu.php");//获取交易类型菜单


$query = "select
		fd_agpm_paytype as paytype,
		date_format(fd_agpm_paydate,'%Y-%m') as paydate ,
		sum(fd_agpm_paymoney) as paymoney,
		sum(fd_agpm_sdcrpayfeemoney+fd_agpm_sdcragentfeemoney) as cbmoney,
        sum(fd_agpm_payfee) as payfee	,
		sum(fd_agpm_agentmoney) as agentmoney
		from tb_agentpaymoneylist 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype	
		where  year(fd_agpm_paydate)='$year' and  fd_appmnu_istabno = 1
			and fd_agpm_payrq='00'
			group by  paydate,fd_agpm_paytype
			order by fd_agpm_paydate desc ";

$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paytype=$db->f(paytype);
		$paydate=$db->f(paydate);
		
		$arr_result[$paydate][$paytype]['paymoney']=$db->f(paymoney);
		$arr_result[$paydate][$paytype]['cbmoney']=$db->f(cbmoney);
		$arr_result[$paydate][$paytype]['payfee']=$db->f(payfee);
		$arr_result[$paydate][$paytype]['agentmoney']=$db->f(agentmoney);
		
		$arr_result[$paydate]['allmonthpaymoney'] +=$db->f(paymoney);
		$arr_result[$paydate]['allmonthcbmoney'] +=$db->f(cbmoney);
		$arr_result[$paydate]['allmonthpayfee'] +=$db->f(payfee);
		$arr_result[$paydate]['allmonthagentmoney'] +=$db->f(agentmoney);
	}
}

//循环时间流出数据
$curData = $startmonth;
$count = 1;
$allmoney = 0;

if($isnodata2 == 'N'){
	while(true){
	  if(strtotime($curData) > strtotime($endmonth)){
		break;
	  }

	  
	 $t->set_var($arrTemData);	
		
	  $t->set_var("curData",$curData);
	  $t->set_var("tdcount",$count);

	  
	  
	  foreach($arr_paytype as $key=> $value)
	{
			$paytype=$key;
		$paymoney=changenum('2',$arr_result[$curData][$paytype]['paymoney']);
		$agentmoney=changenum('2',$arr_result[$curData][$paytype]['agentmoney']);
		$cbmoney=changenum('2',$arr_result[$curData][$paytype]['cbmoney']);
		$payfee=changenum('2',$arr_result[$curData][$paytype]['payfee']);
			$showpaytype .='<TD  class="right">
			<a href="payment_detail.php?type=month&paytype='.$paytype.'&datetime='.$curData.'">'.$paymoney.'</a>
			</TD>
              <TD  class="right">
			  <a href="payment_detail.php?type=month&paytype='.$paytype.'&datetime='.$curData.'">'.$agentmoney.'</a>
			  </TD>
			   <TD  class="right">
			  <a href="payment_detail.php?type=month&paytype='.$paytype.'&datetime='.$curData.'"> '.$cbmoney.'</a>
			   </TD>
			    <TD style="border-right:1px #222 solid"  class="right">
				<a href="payment_detail.php?type=month&paytype='.$paytype.'&datetime='.$curData.'">'.$payfee.'</a>
				</TD>';
			$allmoney +=$arr_result[$curData][$paytype]['paymoney'];
			$allagentmoney +=$arr_result[$curData][$paytype]['agentmoney'];
			$allcbmoney +=$arr_result[$curData][$paytype]['cbmoney'];
			$allpayfee +=$arr_result[$curData][$paytype]['payfee'];
			
			$arr_allmoney[$paytype]['paymoney'] += $arr_result[$curData][$paytype]['paymoney'];
			$arr_allmoney[$paytype]['agentmoney'] += $arr_result[$curData][$paytype]['agentmoney'];
			$arr_allmoney[$paytype]['cbmoney'] +=$arr_result[$curData][$paytype]['cbmoney'];
			$arr_allmoney[$paytype]['payfee'] +=$arr_result[$curData][$paytype]['payfee'];		
			
	}
	

	
		$arr_result[$curData]['allmonthpaymoney']=changenum('2',$arr_result[$curData]['allmonthpaymoney']);
		$arr_result[$curData]['allmonthagentmoney']=changenum('2',$arr_result[$curData]['allmonthagentmoney']);
		$aarr_result[$curData]['allmonthcbmoney']=changenum('2',$arr_result[$curData]['allmonthcbmoney']);
		$arr_result[$curData]['allmonthpayfee']=changenum('2',$arr_result[$curData]['allmonthpayfee']);
	
		$allmonthpaymoney=' <a href="payment_detail.php?type=month&datetime='.$curData.'">'. $arr_result[$curData]['allmonthpaymoney'].'</a>';   
		$allmonthagentmoney=' <a href="payment_detail.php?type=month&datetime='.$curData.'">'.$arr_result[$curData]['allmonthagentmoney'].'</a>';   
		$allmonthcbmoney= '<a href="payment_detail.php?type=month&datetime='.$curData.'">'.$arr_result[$curData]['allmonthcbmoney'].'</a>';
		$allmonthpayfee='<a href="payment_detail.php?type=month&datetime='.$curData.'">'.$arr_result[$curData]['allmonthpayfee'].'</a>';   
		
	  $t->set_var("allmonthpaymoney",$allmonthpaymoney);
	  $t->set_var("allmonthagentmoney",$allmonthagentmoney);
	  $t->set_var("allmonthcbmoney",$allmonthcbmoney);
	  $t->set_var("allmonthpayfee",$allmonthpayfee);
	  $t->set_var("fcolor",$fcolor);
	  $t->set_var("showpaytype",$showpaytype);
	  $t->parse("bxbks", "BXBK", true); 
	  $showpaytype="";
	  $curData = date("Y-m",strtotime("+1 month",strtotime($curData)));
	  $count++;
	};
	$t->set_var("hasdateshow","");
    $t->set_var("nodateshow","none");
}else{
  
  $t->set_var("hasdateshow","none");
  $t->set_var("nodateshow",""); 
  $t->parse("bxbks", "BXBK", true); 
}


 $arr_month=explode("-",$startmonth);

$curData=$arr_month[0];
foreach($arr_paytype as $key=> $value)
{
	$paymoney=changenum('2', $arr_allmoney[$key]['paymoney']);
	$agentmoney=changenum('2', $arr_allmoney[$key]['agentmoney']);
	$cbmoney=changenum('2', $arr_allmoney[$key]['cbmoney']);
	$payfee=changenum('2', $arr_allmoney[$key]['payfee']);
	
	$showall.=' <td class="right"><a href="payment_detail.php?type=month&datetime='.$curData.'&paytype='.$key.'&collect=month">'.$paymoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=month&datetime='.$curData.'&paytype='.$key.'&collect=month">'.$agentmoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=month&datetime='.$curData.'&paytype='.$key.'&collect=month">'.$cbmoney.'</a></td>
              <td class="right" style="border-right:1px #222 solid"><a href="payment_detail.php?type=month&datetime='.$curData.'&paytype='.$key.'&collect=month">'.$payfee.'</a></td>';
}


$allmoney = changenum('2', $allmoney);
$allagentmoney = changenum('2', $allagentmoney);
$allcbmoney = changenum('2', $allcbmoney);
$allpayfee = changenum('2', $allpayfee);

$allmoney  = '<a href="payment_detail.php?type=month&datetime='.$curData.'&collect=month">'.$allmoney.'</a>';
$allcbmoney  = '<a href="payment_detail.php?type=month&datetime='.$curData.'&collect=month">'.$allcbmoney.'</a>';
$allpayfee  = '<a href="payment_detail.php?type=month&datetime='.$curData.'&collect=month">'.$allpayfee.'</a>';
$allagentmoney  = '<a href="payment_detail.php?type=month&datetime='.$curData.'&collect=month">'.$allagentmoney.'</a>';

$t->set_var("count",$count);
$t->set_var("error",$error);
$t->set_var("showall",$showall);

$t->set_var("isdisplay",$isdisplay);
$t->set_var("type",$type);
$t->set_var("typeyear",$typeyear);

$t->set_var("startmonth",$startmonth);
$t->set_var("endmonth",$endmonth);
$t->set_var("authorname",$authorname);
$t->set_var("paycarnum",count($arrPaycardData));




$t->set_var("allmoney",$allmoney);
$t->set_var("allagentmoney",$allagentmoney);
$t->set_var("allcbmoney",$allcbmoney);
$t->set_var("allpayfee",$allpayfee);

$t->set_var("allcreditcardmoney",$allcreditcardmoney);
$t->set_var("allcreditcardagentmoney",$allcreditcardagentmoney);
$t->set_var("allcreditcardcbmoney",$allcreditcardcbmoney);
$t->set_var("allcreditcardpayfee",$allcreditcardpayfee);

$t->set_var("alltfmgmoney",$alltfmgmoney);
$t->set_var("alltfmgagentmoney",$alltfmgagentmoney);
$t->set_var("alltfmgcbmoney",$alltfmgcbmoney);
$t->set_var("alltfmgpayfee",$alltfmgpayfee);

$t->set_var("allrechargemoney",$allrechargemoney);
$t->set_var("allrechargeagentmoney",$allrechargeagentmoney);
$t->set_var("allrechargecbmoney",$allrechargecbmoney);
$t->set_var("allrechargepayfee",$allrechargepayfee);

$t->set_var("allrepaymoney",$allrepaymoney);
$t->set_var("allrepayagentmoney",$allrepayagentmoney);
$t->set_var("allrepaycbmoney",$allrepaycbmoney);
$t->set_var("allrepaypayfee",$allrepaypayfee);

$t->set_var("allcouponmoney",$allcouponmoney);
$t->set_var("allcouponagentmoney",$allcouponagentmoney);
$t->set_var("allcouponcbmoney",$allcouponcbmoney);
$t->set_var("allcouponpayfee",$allcouponpayfee);

$t->set_var("allordermoney",$allordermoney);
$t->set_var("allorderagentmoney",$allorderagentmoney);
$t->set_var("allordercbmoney",$allordercbmoney);
$t->set_var("allorderpayfee",$allorderpayfee);

$t->set_var("bgcolor",$bgcolor);
$t->set_var("searchval",$searchval);



$t->set_var("skin",$loginskin);
$t->pparse("out", "tb_payment_monthview");    # 最后输出页面

?>