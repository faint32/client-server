<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;
if(!empty($type)){
	switch ($type){
		case "all":
			$where = "and (fd_pymylt_state ='9' or fd_pymylt_state ='3')";
		break;
		case "ysp":
			$where = "and fd_pymylt_state ='9'";
		break;
		case "wsp":
			$where ="and fd_pymylt_state ='3'";
		break;
	}
}
if(!empty($datetype)){
	switch ($datetype){
		case "year":
			$datewhere = "and year(fd_agpm_agentdate) = '$listdate'";
		break;
		case "month":
			$datewhere = "and date(fd_agpm_agentdate) = '$listdate'";
		break;
	}
}


$aColumns = array("","fd_agpm_id","fd_agpm_bkntno","fd_agpm_bkordernumber","fd_agpm_shoucardbank","fd_agpm_agentdate","fd_agpm_shoucardno","fd_author_truename","fd_paycard_key","fd_agpm_shoucardman");

$sSearch=u2g($sSearch);
    $sWhere = "";
    if ($sSearch != "" )
    {
        $sWhere = "and  (";
        for ( $i=1 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= $aColumns[$i]." LIKE '%".trim($sSearch)."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
     
    /* Individual column filtering */
    for ( $i=1 ; $i<count($aColumns) ; $i++ )
    {
		$b_s="bSearchable_".$i;
		$s_s="sSearch_".$i;
        if ( $$b_s == "true" && $$s_s != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "AND ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".trim($$s_s)."%' ";
        }
    }

$query="select  case
                 when fd_agpm_paytype ='coupon' then '购买抵用券'
                 when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
                 "when fd_agpm_paytype ='recharge' then        '充值'" .
                 "when fd_agpm_paytype ='pay' then       '还贷款'" .
                 "when fd_agpm_paytype ='order' then '订单付款'" .
                 "when fd_agpm_paytype ='tfmg' then '转账汇款'
                 else '其他业务' END  paytype,
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
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = '1' $where $datewhere $sWhere group by fd_agpm_id";

	$db->query($query);
   $totoalcount=$db->nf()+0;
	
	$query = "  select  case
                 when fd_agpm_paytype ='coupon' then '购买抵用券'
                 when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
                 "when fd_agpm_paytype ='recharge' then        '充值'" .
                 "when fd_agpm_paytype ='pay' then       '还贷款'" .
                 "when fd_agpm_paytype ='order' then '订单付款'" .
                 "when fd_agpm_paytype ='tfmg' then '转账汇款'
                 else '其他业务' END  paytype,
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
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = '1' $where $datewhere $sWhere group by fd_agpm_id limit $iDisplayStart,$iDisplayLength";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
		$paytype = $db->f(paytype);
		$agpmid = $db->f(agpmid);
		$money = $db->f(money);
		$payfee = $db->f(payfee);
		$costmoney = $db->f(costmoney);
		$paydate = $db->f(paydate);
		$url = $db->f(url);
		$bkntno = $db->f(bkntno);
		$shoucardno = $db->f(shoucardno);
		$shoucardbank = $db->f(shoucardbank);
		$shoucardman = $db->f(shoucardman);
		$shoucardmobile = $db->f(shoucardmobile);
		$bkordernumber = $db->f(bkordernumber);
		$paycardkey = $db->f(paycardkey);
		$author = $db->f(author);
		
		if($url=='coupon'){$url='couponsale_view';}
		if($url=='creditcard'){$url='creditcard_sp';}
		if($url=='recharge'){$url='rechargeglist_sp';}
		if($url=='tfmg'){$url='transfermoney_sp';}
		if($url=='pay'){$url='repaymoney_sp';}
		
		if($typekind=='pay'){$money=$money;}
		if($typekind=='cost'){$money=$costmoney;}
		if($typekind=='fee'){$money=$payfee;}
		$allmoney +=$money;
		
		$allmoney  = number_format($allmoney, 2, ".", "");
		$count++;
		$arr_list[] = array(
				"DT_RowId" => $vid ,
				"DT_RowClass" => "",
				$count,
				"<span style='border-bottom:1px #999 solid;' onClick='javascript:setbkordernum(\"$url\",\"$bkordernumber\",this)'>".$bkordernumber."</span>",
				g2u($paytype),
				$paydate,
				$money,
				g2u($author),
				$shoucardno,
				$shoucardbank,
				g2u($shoucardman),
				$shoucardmobile,
				$paycardkey,);
		}
		$arr_list[count($arr_list)] = array(
		"DT_RowId" => "" ,
		"DT_RowClass" => "",
		g2u('合共'),
		"",
		"",
		"",
		"<span style='text-align:right;display:block;'>".$allmoney."</span>",
		"",
		"",
		"",
		"",
		"",
		"",
	);
	}else{
		 $arr_list[] = array(
			"DT_RowId" => "" ,
			"DT_RowClass" => "",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			""
		 );
		$vmember = "暂无数据";
	
		}

$returnarray['sEcho']=intval($sEcho);
$returnarray['iTotalRecords']=$totoalcount;
$returnarray['iTotalDisplayRecords']=$totoalcount;
$returnarray['aaData']=$arr_list;
$returnvalue = json_encode($returnarray);
echo json_encode($returnarray);

?>