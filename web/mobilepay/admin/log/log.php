<?


$arr_text = array("������","������ʽ","��������","����ʱ��");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}

$t->set_var("log_shopid"  , $shopid     ); 
$t->set_var("theadth"     , $theadth     ); 




?>