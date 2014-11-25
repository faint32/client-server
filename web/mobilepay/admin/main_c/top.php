<?

require ("../include/common.inc.php");
$db=new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("top","top.html"); 
$t->set_block("top", "MAILBK", "mailbks");  //使用一个块

$year  = date( "Y", mktime()) ;
$month = date( "m", mktime()) ;
$day   = date( "d", mktime()) ;

 $isshowchageorgan = "";


$t->set_var("isshowchageorgan"  , $isshowchageorgan);  //是否显示改变机构
$t->set_var("loginstaname"      , $loginstaname);
$t->set_var("organname"         , $loginorganname);
$t->set_var("loginflstmonth"         , $showmonth);

$t->set_var("loginname"         , $loginname);
$t->set_var("organname"         , $loginorganname);
$t->set_var("staffername"       , $loginstaname);
$t->set_var("agencyname"       , $loginorganname);
$t->set_var("loginflstmonth"         , $showmonth);
$t->set_var("loginshowmonth"         , $showopenmonth);

$t->set_var("year"         , $year         );
$t->set_var("month"        , $month        );
$t->set_var("day"          , $day          );
$t->set_var("skin",$loginskin);  // 调用皮肤
$t->pparse("out", "top");    # 最后输出页面
//生成选择菜单的函数
function mkselect($arritem,$hadselected,$arry){
	//$x="<option value='all'>总部</option>";
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