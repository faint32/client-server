<?
$thismenucode = "6n004";     
require("../include/common.inc.php");
$db=new db_test;

$t = new Template(".","keep");
$t->set_file("template","salerpushmoney.html");

$arr_text = array("���/�·�","ˢ�����豸��","����","ʹ�ô���","���׽��","���","����");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';

}
$query="select fd_rewards_bmonth from tb_salerrewards";
$db->query($query);
if($db->nf())
{
	
	$db->next_record();
	$year=$db->f(fd_rewards_bmonth);
	$byear=date("Y",strtotime($year));
}else{
	$byear="2002";
}
$endyear=date("Y");

for($byear;$byear<=$endyear;$byear++)
{
	$arr_year[]=$byear;
}

$searchyear = mkselect($arr_year,$searchyear,$arr_year);
for($i=1;$i<13;$i++)
{
	$arr_month[]=$i;
}

$serachmonth = mkselect($arr_month,$serachmonth,$arr_month);

$t->set_var("searchyear"     , $searchyear      );      
$t->set_var("serachmonth"          , $serachmonth        );

$t->set_var("theadth"     , $theadth     ); 
  

$t->set_var("action",$action);
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������

//����ѡ��˵��ĺ���
function mkselect($arritem,$hadselected,$arry){ 
      $x .= "<option selected value=''>--��ѡ��--</option>" ;
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
  
   return $x ; 
}

?>