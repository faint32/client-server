<?
$thismenucode = "10n004";     
require("../include/common.inc.php");
$db=new db_test;



$t = new Template(".","keep");
$t->set_file("template","payfeelist.html");

	$query = "select sum(fd_payfee_addmoney) as alladdmoney,sum(fd_payfee_lessmoney) as alllessmoney  from tb_payfeelist";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record()){
		   $alladdmoney         = $db->f(alladdmoney); 
		   $alllessmoney         = $db->f(alllessmoney); 
		   $allmoney=$alladdmoney+ $alllessmoney;
			
		}				
	}
$checkall= '<INPUT onclick=CheckAll() type=checkbox class=checkbox value=on name=chkall>';
$arr_text = array("流水编号","刷卡器设备号","手续费","交易方式","时间");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}
	$arr_titlename=array("tb_repaymoneyglist"=>"贷款还款支付手续费",
						 "tb_creditcardglist"=>"信用卡还款支付手续费",
						 "tb_transfermoneyglist"=>"转账汇款支付手续费",);
	$query = "select fd_payfee_tabname from tb_payfeelist group by fd_payfee_tabname";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record()){
		   $arr_tabname[]= $db->f(fd_payfee_tabname); 
		  
			
		}				
	}
	for($i=0;$i<count($arr_tabname);$i++)
	{
		$key=$arr_tabname[$i];
		if($i==0)
		{
			$classname="first";
		}else{
			$classname="";
		}
	$showcontent .='<div class="'.$classname.'">
		<div class="panel">
		<fieldset class="form">
		<legend>'.$arr_titlename[$key].' </legend>
		<table width="100%" border="0" cellspacing="1"  class="table" id="basictable'.$i.'">
		<thead><tr>'.$theadth.'</tr></thead><tbody></tbody></table>
		</fieldset>
		</div>
		</div>';
	}
$t->set_var("allmoney"     ,$allmoney      );      
$t->set_var("showcontent"           , $showcontent     ); 
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面

?>