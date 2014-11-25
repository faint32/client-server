<?

//取得全局变量 
if ($brows_rows=="")
{
	$brows_rows = 0;
}

if($brows_rows=='0')
{
	$brows_rows=$loginbrowline;
}
elseif($brows_rows>=500)
{
	$brows_rows=500;
}

//增加排序字段
if($sorttype == "" or $sorttype == "asc"){
	$sorttype = "desc";
}else
if($sorttype == "desc"){
	$sorttype = "asc";
}
if(!empty($sorts)){
	$order = " order by $sorts $sorttype";
}

//$querywhere .=" and fd_mat_ssgs = '$loginczqx'";

if(!function_exists(pageft)){ 
function pageft($totle,$displaypg=20,$url=''){
error_reporting(0);
//定义几个全局变量： 
//$page：当上一页码；
//$firstcount：（数据库）查询的起始项；
//$pagenav：页面导航条代码，函数内部并没有将它输出；
//$_SERVER：读取本页URL“$_SERVER["REQUEST_URI"]”所必须。
global $page,$firstcount,$pagenav,$_SERVER;

//为使函数外部可以访问这里的“$displaypg”，将它也设为全局变量。注意一个变量重新定义为全局变量后，原值被覆盖，所以这里给它重新赋值。
$GLOBALS["displaypg"]=$displaypg;

if(!$page) $page=1;

//如果$url使用默认，即空值，则赋值为本页URL：
if(!$url){ $url=$_SERVER["REQUEST_URI"];}

//URL分析：
$parse_url=parse_url($url);
$url_query=$parse_url["query"]; //单独取出URL的查询字串
if($url_query){

$url_query=ereg_replace("(^|&)page=$page","",$url_query);

//将处理后的URL的查询字串替换原来的URL的查询字串：
$url=str_replace($parse_url["query"],$url_query,$url);

//在URL后加page查询信息，但待赋值： 
if($url_query) $url.="&page"; else $url.="page";
}else {
$url.="?page";
}
//页码计算：
$lastpg=ceil($totle/$displaypg); //最下一页，也是总页数
$page=min($lastpg,$page);
$prepg=$page-1; //上一页
$nextpg=($page==$lastpg ? 0 : $page+1); //下一页
$firstcount=($page-1)*$displaypg;

////开始分页导航条代码：
//$pagenav="第 <B>".($totle?($firstcount+1):0)."</B>-<B>".min($firstcount+$displaypg,$totle)."</B> 条记录，共 $totle 条,每页<input type='text' class='input' size=3 name='brows_rows' value='{brows_rows}' onblur='pressbrowse(this.value);' onFocus='this.select()'   title='输入关键字后,按ENTER或点击“查询”进行查询!'> 行  ";


$pagenav="共 $totle 条/共 $lastpg 页,每页<input type='text' class='page_other' size=3 name='brows_rows' value='{brows_rows}' onblur='pressbrowse(this.value);' onFocus='this.select()'   title='输入关键字后,按ENTER或点击“查询”进行查询!'> 行  ";

//如果只有一页则跳出函数：
if($lastpg<=1) return false;

$pagenav.="<a href='$url=1' id='btfirst'>首页</a> ";
if($prepg) $pagenav.=" <a href='$url=$prepg' id='btper' >上页</a> "; else $pagenav.=" 上页 ";
if($nextpg) $pagenav.=" <a href='$url=$nextpg' id='btnext'>下页</a> "; else $pagenav.=" 下页 ";
$pagenav.=" <a href='$url=$lastpg' id='btlast'>尾页</a> ";

//下拉跳转列表，循环列出所有页码：
$pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value' >\n";
for($i=1;$i<=$lastpg;$i++){
if($i==$page) $pagenav.="<option value='$i' selected class='input'>$i</option>\n";
else $pagenav.="<option value='$i'>$i</option>\n";
}
$pagenav.="</select> 页";
}
}
?>
