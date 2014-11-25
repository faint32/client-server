<?


$arr_paytype=getauthorpaycardmenu();

$arr_text=array("入款","支出","成本","手续费");


foreach($arr_paytype as $value)
{ 
	
	$theadth .=' <th width="100" colspan="4" >'.$value.'</th>';
	
		
		foreach($arr_text as $v1)
		{
			$theadth1 .=' <th>'.$v1.'</th>';
		}
}
//"recharge",


$t->set_var("theadth",$theadth);
$t->set_var("theadth1",$theadth1);
?>