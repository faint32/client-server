<?

require ("../include/common.inc.php");
$db=new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("top","top.html"); 
$t->set_block("top", "MAILBK", "mailbks");  //ʹ��һ����

$year  = date( "Y", mktime()) ;
$month = date( "m", mktime()) ;
$day   = date( "d", mktime()) ;

 $isshowchageorgan = "";


$t->set_var("isshowchageorgan"  , $isshowchageorgan);  //�Ƿ���ʾ�ı����
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
$t->set_var("skin",$loginskin);  // ����Ƥ��
$t->pparse("out", "top");    # ������ҳ��
//����ѡ��˵��ĺ���
function mkselect($arritem,$hadselected,$arry){
	//$x="<option value='all'>�ܲ�</option>";
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