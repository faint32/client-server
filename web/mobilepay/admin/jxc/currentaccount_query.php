<?
$thismenucode = "2k225";
require("../include/common_noprivate.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("currentaccount_query","currentaccount_query.html");


$byear = date("Y", mktime()) ;
$bmonth = date("m", mktime()) - 1 ;
$bday = date("d", mktime()) ;

$eyear = date("Y", mktime()) ;
$emonth = date("m", mktime()) ;
$eday = date("d", mktime()) ;

$arr_clienttype = array("客户","供应商");
$arr_clientid   = array("1"   , "2" );
$clienttype = makeselect($arr_clienttype,$clienttype,$arr_clientid);

$t->set_var("byear",$byear);
$t->set_var("bmonth",$bmonth);
$t->set_var("bday",$bday);

$t->set_var("eyear",$eyear);
$t->set_var("emonth",$emonth);
$t->set_var("eday",$eday);

$t->set_var("clienttype"   , $clienttype   );      //客户类型
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);

include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->pparse("out", "currentaccount_query");       # 最后输出页面

//生成选择菜单的函数
function makeselect($arritem,$hadselected,$arry){ 
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