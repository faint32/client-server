<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');

$db=new db_test;
$arr_text = array("流水编号","刷卡器设备号","手续费","交易方式","时间");
$str .= implode(",",$arr_text)."\n";

$arr_tabname=explode("?",$tabname);

$query = "SELECT * FROM `tb_payfeelist`
left join tb_paycard on fd_paycard_id=fd_payfee_paycardid 
where fd_payfee_tabname='$arr_tabname[0]'";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		

						 
		$vid             = $db->f(fd_payfee_id);            //id号  
	   $vpayfeeno        = $db->f(fd_payfee_no);            //id号  
       $vpaycardid       = $db->f(fd_paycard_no);       
       $vaddmoney        = $db->f(fd_payfee_addmoney); 
	   $vlessmoney       = $db->f(fd_payfee_lessmoney);
	   $vpaymode         = $db->f(fd_payfee_paymode);
       $vtime            = $db->f(fd_payfee_datetime);
	   $vpaymemo         = $db->f(fd_payfee_memo);
	   
	   
	   $arr_list = array(				
		                $vpayfeeno,
						$vpaycardid,
						$vaddmoney,
						$vpaymode,
						$vtime
						);				 
		$str .= implode(",",$arr_list)."\n";
     }
   }
$arr_titlename=array("tb_repaymoneyglist"=>"贷款还款支付手续费",
						 "tb_creditcardglist"=>"信用卡还款支付手续费",
						 "tb_transfermoneyglist"=>"转账汇款支付手续费",
						 "tb_couponrebuy"=>"优惠劵回购手续费");
      
	    $filename = $arr_titlename[$arr_tabname[0]].'手续费表'.date('Ymd').'.csv';
     export_csv($filename,$str);
    function export_csv($filename,$data) {
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data; 
}

?>