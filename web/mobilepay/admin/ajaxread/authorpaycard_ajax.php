<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


$aColumns = array("fd_paycard_key","fd_author_truename","fd_paycard_batches");

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
	
     $query = "select
				 " .
				"  case 
				when fd_agpm_state ='coupon' then '成功'
				else '失败' END  state," .
				"" .
				"  case 
				when fd_agpm_paytype ='coupon' then '购买抵用券'
				when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
			   "when fd_agpm_paytype ='recharge' then        '充值'" .
			   "when fd_agpm_paytype ='repay' then       '还贷款'" .
			   "when fd_agpm_paytype ='order' then '订单付款'" .
			   "when fd_agpm_paytype ='tfmg' then '转账汇款'
				else '其他业务' END  paytype," .
				"
				 fd_agpm_id,count(fd_agpm_paycardid) as paynum,	
				 fd_agpm_sdcrpayfeemoney,
				 fd_agpm_sdcragentfeemoney,
				 fd_agpm_payfee,
				 fd_author_truename,
				 fd_paycard_key,
				 fd_paycardtype_name,
                 fd_agpm_paymoney,
				 fd_paycard_batches,
				 fd_agpm_money,
				 fd_agpm_paymoney from tb_agentpaymoneylist   
				 left join tb_author on fd_author_id = fd_agpm_authorid
				 left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
				 left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid
                 where 1 $sWhere group by fd_agpm_paycardid";
   $db->query($query);
   $totoalcount=$db->nf()+0;
   
   				  
$query = "select
				 " .
				"  case 
				when fd_agpm_state ='1' then '成功'
				else '失败' END  state," .
				"" .
				"  case 
				when fd_agpm_paytype ='coupon' then '购买抵用券'
				when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
			   "when fd_agpm_paytype ='recharge' then        '充值'" .
			   "when fd_agpm_paytype ='repay' then       '还贷款'" .
			   "when fd_agpm_paytype ='order' then '订单付款'" .
			   "when fd_agpm_paytype ='tfmg' then '转账汇款'
				else '其他业务' END  paytype," .
				" fd_agpm_id,count(fd_agpm_paycardid) as paynum,		
				 sum(fd_agpm_payfee) as payfee,
				 sum(fd_agpm_sdcrpayfeemoney) as sdcrpayfeemoney,
				 sum(fd_agpm_sdcragentfeemoney) as sdcragentfeemoney,
				 fd_author_truename,
				 fd_paycard_key,
				 fd_paycardtype_name,
				 fd_paycard_scope,
                 fd_agpm_paymoney,
				 fd_agpm_state,
				 fd_paycard_batches,
				 fd_agpm_money,
				 fd_agpm_paymoney from tb_agentpaymoneylist   
				 left join tb_author on fd_author_id = fd_agpm_authorid
				 left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
				 left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid
                 where 1 $sWhere group by fd_agpm_paycardid  limit $iDisplayStart,$iDisplayLength";
$db->query($query);
//echo $query;
if($db->nf())
{
	while($db->next_record())
	{
		$vid     = $db->f(fd_agpm_id);
		$paynum     = $db->f(paynum);
		$truename         = $db->f(fd_author_truename) ;
		$paycardkey     = $db->f(fd_paycard_key) ;
		$paycardtype	 = $db->f(fd_paycardtype_name) ;
		$scope 	 = $db->f(paytype);
		$paydate      = $db->f(fd_agpm_paydate);
		$state     = $db->f(state);
		
		$batches      = $db->f(fd_paycard_batches);
		$money     = $db->f(fd_agpm_money);
		$paymoney     = $db->f(fd_agpm_paymoney);
		
		$sdcrpayfeemoney      = $db->f(sdcrpayfeemoney);
		$sdcragentfeemoney     = $db->f(sdcragentfeemoney);
		$payfee     = $db->f(payfee);
		$cssy =$payfee-$sdcrpayfeemoney-$sdcragentfeemoney;
		
		
/*$caozuo = "<span color='#FF0000' style='border-bottom:1px #999 solid;' onClick=javascript:showstorage('".$vid."',this)>".g2u(查看详细)."</span>";*/
	   	$count++;   
	   $arr_list[] = array("DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$count,
						g2u($truename),
						$paycardkey,
						g2u($paycardtype),
						g2u($scope),
						$batches,
						
						g2u($state),
						$paynum,
						$paymoney,
						$cssy,
						//$caozuo
						);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$count,
						g2u($truename),
						$paycardkey,
						g2u($paycardtype),
						g2u($scope),
						$batches,
						
						g2u($state),
						$paynum,
						$paymoney,
						$cssy,
						//$caozuo
						);
   }
      
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>