<?php
require ("../include/common.inc.php");
$db = new DB_test ;
$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agentpaymoneyyear_detail","agentpaymoneyyear_detail.html");
$t->set_block ( "agentpaymoneyyear_detail", "prolist", "prolists" );
if(!empty($type)){
	switch ($type){
		case "all":
			$where = "and (fd_pymylt_state ='9' or fd_pymylt_state ='3')";
			$tabtype="�ܴ�����";
		break;
		case "ysp":
			$where = "and fd_pymylt_state ='9'";
			$tabtype="�Ѻ˶Գ���";
		break;
		case "wsp":
			$where ="and fd_pymylt_state ='3'";
			$tabtype="δ�˶Գ���";
		break;
	}
}
if(!empty($typekind)){
	switch ($typekind){
		case "pay":
			$thname="֧����";
		break;
		case "cost":
			$thname="�ɱ�";
		break;
		case "fee":
			$thname="������";
		break;
	}
}

$query="select  case
                 when fd_agpm_paytype ='coupon' then '�������ȯ'
                 when fd_agpm_paytype ='creditcard' then '���ÿ�����'" .
                 "when fd_agpm_paytype ='recharge' then        '��ֵ'" .
                 "when fd_agpm_paytype ='pay' then       '������'" .
                 "when fd_agpm_paytype ='order' then '��������'" .
                 "when fd_agpm_paytype ='tfmg' then 'ת�˻��'
                 else '����ҵ��' END  paytype,
				fd_agpm_id               as agpmid,
                fd_agpm_agentmoney  as money,
				fd_agpm_payfee  as payfee,
				fd_agpm_sdcragentfeemoney  as costmoney,
				fd_agpm_agentdate        as paydate,
				fd_agpm_paytype as url,
				
				fd_agpm_bkntno as bkntno,	
				fd_agpm_shoucardno as shoucardno,
                fd_agpm_shoucardbank as shoucardbank,
				fd_agpm_shoucardman as shoucardman,
                fd_agpm_shoucardmobile as shoucardmobile,
				fd_agpm_bkordernumber            as bkordernumber,
                 fd_paycard_key                   as paycardkey,
                 fd_author_truename               as author
				
		  from tb_agentpaymoneylist 
		  left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  left join tb_paymoneylistdetail on fd_pymyltdetail_agpmid = fd_agpm_id 
		  left join tb_paymoneylist on fd_pymyltdetail_paymoneylistid = fd_pymylt_id
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = '1' $where and year(fd_agpm_agentdate) = '$year'";

	$db->query($query);
	$arr_result = $db->getFiledData('');
	foreach($arr_result as $val)
	{
	if($val['url']=='coupon'){$val['url']='couponsale_view';}
	if($val['url']=='creditcard'){$val['url']='creditcard_sp';}
	if($val['url']=='recharge'){$val['url']='rechargeglist_sp';}
	if($val['url']=='tfmg'){$val['url']='transfermoney_sp';}
	if($val['url']=='pay'){$val['url']='repaymoney_sp';}
	
	if($typekind=='pay'){$val['money']=$val['money'];}
	if($typekind=='cost'){$val['money']=$val['costmoney'];}
	if($typekind=='fee'){$val['money']=$val['payfee'];}
	$allmoney +=$val['money'];
	$count++;
	$t->set_var($val);
	$t->parse("prolists", "prolist", true);	
	}
$allmoney  = number_format($allmoney, 2, ".", "");
$allpayfee  = number_format($allpayfee, 2, ".", "");
$allcostmoney  = number_format($allcostmoney, 2, ".", "");
$t->set_var("gotourl",$gotourl);
$t->set_var("error",$error);
$t->set_var("allmoney",$allmoney);
$t->set_var("allpayfee",$allpayfee);
$t->set_var("allcostmoney",$allcostmoney);
$t->set_var("skin",$loginskin);
$t->set_var("month",$month);
$t->set_var("year",$year);
$t->set_var("thname",$thname);
$t->set_var("tabtype",$tabtype);
$t->pparse("out", "agentpaymoneyyear_detail");    # ������ҳ��
?>