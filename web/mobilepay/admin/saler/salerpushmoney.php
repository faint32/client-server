<?
$thismenucode = "6n004";     
require("../include/common.inc.php");
$db=new db_test;

$t = new Template(".","keep");
$t->set_file("template","salerpushmoney.html");

$arr_text = array("年份/月份","刷卡器设备号","网导","使用次数","交易金额","提成","操作");

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
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面

//生成选择菜单的函数
function mkselect($arritem,$hadselected,$arry){ 
      $x .= "<option selected value=''>--请选择--</option>" ;
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