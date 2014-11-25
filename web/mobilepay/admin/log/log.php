<?


$arr_text = array("操作人","操作方式","操作内容","操作时间");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}

$t->set_var("log_shopid"  , $shopid     ); 
$t->set_var("theadth"     , $theadth     ); 




?>