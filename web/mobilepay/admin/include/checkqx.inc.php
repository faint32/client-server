<?
include("../include/menutitle.php"); 
// 判断权限 程序在$t->pparse("out", "");    # 最后输出页面前调用


$addqx = $thismenuqx[1];  // 增加
$editqx = $thismenuqx[2];  // 修改
$delqx = $thismenuqx[3];  // 删除
$otherqx = $thismenuqx[4];  // 其他功能

if(($addqx != 1) and ($action == "new")){
	$dissave = "disabled" ;
}
if(($editqx != 1) and ($action == "edit")) {
	$dissave = "disabled" ;
}else{
  $dissave = "" ;
}
if(($delqx != 1)){
	$disdel = "disabled" ;
}

if(($otherqx != 1)){
	$disotherqx = "disabled" ;
}else{
  $disotherqx = "" ;
}
//echo $error;
if(empty($error))
{
$show_error="none";
}else
{
$show_error="";
}

if($logincostqx == 1){
	$cost_display = "";
}else{
  $cost_display = "none";
}

$showtips='<div id="sending" STYLE=" WIDTH: 30%; POSITION: absolute; TOP: 50%; left:40%; z-index:101; display:none;font-size:13px; "> 
       <table width=400 height=80 border=1 cellspacing=2 cellpadding=1 >
          <tr> 
               <td bgcolor=#F1F4F9  id="showcontent"></td>
          </tr>
       </table>
</div>
<div id="sendingbg" STYLE="LEFT: 0px; WIDTH: 100%; POSITION: absolute; TOP: 0px; HEIGHT: 100%;  z-index:100; filter: Alpha(Opacity=50);  background-color: #ffffff; display:none">';

$t->set_var("showtips" , $showtips      ); 
$t->set_var("logintabshow" , $logintabshow      ); 
$t->set_var("display_detail" , $display_detail      ); 
$t->set_var("cost_display" , $cost_display );
$t->set_var("show_error",$show_error); 
$t->set_var("disdel",$disdel); 
$t->set_var("dissave",$dissave); 
$t->set_var("disnew",$disnew); 
$t->set_var("disotherqx",$disotherqx); 
$t->set_var("showeditdiv",$showeditdiv); 
if(!$error)
{
	$show_error="none";
}


$t->set_var("show_error"   , $show_error );
$t->set_var("g_uppic"      , $g_uppic );
$t->set_var("g_upbackurl"      , $g_upbackurl );
$t->set_var("g_weburl"      , $g_weburl );

?>