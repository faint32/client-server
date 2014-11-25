<?
$thismenucode = "10n012";
require ("../include/common.inc.php");

$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("tb_payment_yearview","tb_payment_yearview.html");
$t->set_block("tb_payment_yearview", "BXBK", "bxbks");



if ($bgcolor=="#FFFFFF") {
	$bgcolor="#F1F4F9";
}else{
	$bgcolor="#FFFFFF";
}

require ("../report/getpaycardmenu.php");//获取交易类型菜单


$query = "select
		fd_agpm_paytype as paytype,
		date_format(fd_agpm_paydate,'%Y') as paydate ,
		sum(fd_agpm_paymoney) as paymoney,
		sum(fd_agpm_sdcrpayfeemoney+fd_agpm_sdcragentfeemoney) as cbmoney,
        sum(fd_agpm_payfee) as payfee,
		sum(fd_agpm_agentmoney) as agentmoney		 
		from tb_agentpaymoneylist 
		left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype	
		where  fd_agpm_payrq='00' and  fd_appmnu_istabno = 1
			group by  paydate,fd_agpm_paytype
			order by fd_agpm_paydate desc";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paytype=$db->f(paytype);
		$paydate=$db->f(paydate);
		$arr_paydate[]=$paydate;
		
		$arr_result[$paydate][$paytype]['paymoney']=$db->f(paymoney);
		$arr_result[$paydate][$paytype]['cbmoney']=$db->f(cbmoney);
		$arr_result[$paydate][$paytype]['payfee']=$db->f(payfee);
		$arr_result[$paydate][$paytype]['agentmoney']=$db->f(agentmoney);
		
		$arr_result[$paydate]['allyearpaymoney'] +=$db->f(paymoney);
		$arr_result[$paydate]['allyearagentmoney'] +=$db->f(agentmoney);
		$arr_result[$paydate]['allyearcbmoney'] +=$db->f(cbmoney);
		$arr_result[$paydate]['allyearpayfee'] +=$db->f(payfee);
	}
}

$arr_paydate=array_unique($arr_paydate);

//循环时间流出数据

$count = 1;
$allmoney = 0;

if($arr_paydate){

	  foreach($arr_paydate as $value){
			$curData= $value;
		  foreach($arr_paytype as $key=> $value1)
		{
			$paytype=$key;
			$paymoney=changenum('2',$arr_result[$curData][$paytype]['paymoney']);
			$agentmoney=changenum('2',$arr_result[$curData][$paytype]['agentmoney']);
			$cbmoney=changenum('2',$arr_result[$curData][$paytype]['cbmoney']);
			$payfee=changenum('2',$arr_result[$curData][$paytype]['payfee']);
			$showpaytype .='
			<TD  class="right">
			<a href="payment_detail.php?type=year&paytype='.$paytype.'&datetime='.$curData.'">'.$paymoney.'</a>
			</TD>
            <TD  class="right">
			<a href="payment_detail.php?type=year&paytype='.$paytype.'&datetime='.$curData.'">'.$agentmoney.'</a></TD>
			<TD  class="right">
			<a href="payment_detail.php?type=year&paytype='.$paytype.'&datetime='.$curData.'">'.$cbmoney.'</a></TD>
			<TD   style="border-right:1px #222 solid"  class="right">
			<a href="payment_detail.php?type=year&paytype='.$paytype.'&datetime='.$curData.'">'.$payfee.'</a></TD>';
			
			
			$allmoney +=$arr_result[$curData][$paytype]['paymoney'];
			$allagentmoney +=$arr_result[$curData][$paytype]['agentmoney'];
			$allcbmoney +=$arr_result[$curData][$paytype]['cbmoney'];
			$allpayfee +=$arr_result[$curData][$paytype]['payfee'];
			
			$arr_allmoney[$paytype]['paymoney'] += $arr_result[$curData][$paytype]['paymoney'];
			$arr_allmoney[$paytype]['agentmoney'] += $arr_result[$curData][$paytype]['agentmoney'];
			$arr_allmoney[$paytype]['cbmoney'] +=$arr_result[$curData][$paytype]['cbmoney'];
			$arr_allmoney[$paytype]['payfee'] +=$arr_result[$curData][$paytype]['payfee'];				
		}
		

		$arr_result[$curData]['allyearpaymoney']=changenum('2',$arr_result[$curData]['allyearpaymoney']);
		$arr_result[$curData]['allyearagentmoney']=changenum('2',$arr_result[$curData]['allyearagentmoney']);
		$aarr_result[$curData]['allyearcbmoney']=changenum('2',$arr_result[$curData]['allyearcbmoney']);
		$arr_result[$curData]['allyearpayfee']=changenum('2',$arr_result[$curData]['allyearpayfee']);
		
		$allyearpaymoney='<a href="payment_detail.php?type=year&datetime='.$curData.'">'.$arr_result[$curData]['allyearpaymoney'].'</a>';   
		$allyearagentmoney='<a href="payment_detail.php?type=year&datetime='.$curData.'">'.$arr_result[$curData]['allyearagentmoney'].'</a>';   
		$allyearcbmoney='<a href="payment_detail.php?type=year&datetime='.$curData.'">'.$arr_result[$curData]['allyearcbmoney'].'</a>';
		$allyearpayfee='<a href="payment_detail.php?type=year&datetime='.$curData.'">'.$arr_result[$curData]['allyearpayfee'].'</a>';   
		
		$t->set_var("allyearpaymoney",$allyearpaymoney);
		$t->set_var("allyearagentmoney",$allyearagentmoney);
		$t->set_var("allyearcbmoney",$allyearcbmoney);
		$t->set_var("allyearpayfee",$allyearpayfee);
	  
	  $t->set_var("curData",$curData);
	  $t->set_var("tdcount",$count);
	  $t->set_var("fcolor",$fcolor);
	  $t->set_var("showpaytype",$showpaytype);
	  $t->parse("bxbks", "BXBK", true); 
	  $showpaytype="";
	  $curData = date("Y-m",strtotime("+1 month",strtotime($curData)));
	  $count++;
	};
	
	 $t->set_var("nodateshow","none"); 
}else{
  
  $t->set_var("hasdateshow","none");
  $t->set_var("nodateshow",""); 
   $t->set_var("showpaytype","");
  $t->parse("bxbks", "BXBK", true); 
}


foreach($arr_paytype as $key=> $value)
{
	$paymoney=changenum('2', $arr_allmoney[$key]['paymoney']);
	$agentmoney=changenum('2', $arr_allmoney[$key]['agentmoney']);
	$cbmoney=changenum('2', $arr_allmoney[$key]['cbmoney']);
	$payfee=changenum('2', $arr_allmoney[$key]['payfee']);
	
	$showall.=' <td class="right"><a href="payment_detail.php?type=year&'.'&paytype='.$key.'">'.$paymoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=year&'.'&paytype='.$key.'">'.$agentmoney.'</a></td>
              <td class="right"><a href="payment_detail.php?type=year&'.'&paytype='.$key.'">'.$cbmoney.'</a></td>
              <td class="right" style="border-right:1px #222 solid"><a href="payment_detail.php?type=year&'.'&paytype='.$key.'">'.$payfee.'</a></td>';
}


$t->set_var("showall",$showall);

$allmoney = changenum('2', $allmoney);
$allagentmoney = changenum('2', $allagentmoney);
$allcbmoney = changenum('2', $allcbmoney);
$allpayfee = changenum('2', $allpayfee);

$allmoney  = '<a href="payment_detail.php?type=year">'.$allmoney.'</a>';
$allcbmoney  = '<a href="payment_detail.php?type=year">'.$allcbmoney.'</a>';
$allpayfee  = '<a href="payment_detail.php?type=year">'.$allpayfee.'</a>';
$allagentmoney  = '<a href="payment_detail.php?type=year">'.$allagentmoney.'</a>';
$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("startmonth",$startmonth);
$t->set_var("endmonth",$endmonth);
$t->set_var("authorname",$authorname);
$t->set_var("paycarnum",count($arrPaycardData));



$t->set_var("allmoney",$allmoney);
$t->set_var("allagentmoney",$allagentmoney);
$t->set_var("allcbmoney",$allcbmoney);
$t->set_var("allpayfee",$allpayfee);



$t->set_var("bgcolor",$bgcolor);
$t->set_var("searchval",$searchval);



$t->set_var("skin",$loginskin);
$t->pparse("out", "tb_payment_yearview");    # 最后输出页面

?>