<?
//为了避免重复包含文件而造成错误，加了判断函数是否存在的条件：
if(!function_exists(pageft)){ 
//定义函数pageft(),三个参数的含义为：
//$totle：信息总数；
//$displaypg：每页显示信息数，这里设置为默认是20；
//$url：分页导航中的链接，除了加入不同的查询信息“page”外的部分都与这个URL相同。
//　　　默认值本该设为本页URL（即$_SERVER["REQUEST_URI"]），但设置默认值的右边只能为常量，所以该默认值设为空字符串，在函数内部再设置为本页URL。
function pageft($totle,$displaypg=20,$url=''){

	//定义几个全局变量： 
	//$page：当前页码；
	//$firstcount：（数据库）查询的起始项；
	//$pagenav：页面导航条代码，函数内部并没有将它输出；
	//$_SERVER：读取本页URL“$_SERVER["REQUEST_URI"]”所必须。
	global $page,$firstcount,$pagenav,$pagenavtop ,$_SERVER;

	//为使函数外部可以访问这里的“$displaypg”，将它也设为全局变量。注意一个变量重新定义为全局变量后，原值被覆盖，所以这里给它重新赋值。
	$GLOBALS["displaypg"]=$displaypg;

	if(!$page) $page=1;

	//如果$url使用默认，即空值，则赋值为本页URL：
	if(!$url){
		$url=$_SERVER["REQUEST_URI"];
	}

	//URL分析：
	$parse_url=parse_url($url);
	$url_query=$parse_url["query"]; //单独取出URL的查询字串
	if($url_query){
		//因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
		//这里用到了正则表达式，请参考“PHP中的正规表达式”（http://www.pconline.com.cn/pcedu/empolder/wz/php/10111/15058.html）
		$url_query=ereg_replace("(^|&)page=$page","",$url_query);

		//将处理后的URL的查询字串替换原来的URL的查询字串：
		$url=str_replace($parse_url["query"],$url_query,$url);

		//在URL后加page查询信息，但待赋值： 
		if($url_query){ 
			$url.="&page"; 
		}else{ 
			$url.="page";
		}
	}else {
		$url.="?page";
	}

	//页码计算：
	$lastpg=ceil($totle/$displaypg); //最后页，也是总页数

	$page=min($lastpg,$page);
	$prepg=$page-1; //<
	$nextpg=($page==$lastpg ? 1 : $page+1); //>
	$firstcount=($page-1)*$displaypg;
	//
	//  <form id="form1" name="form1" method="post" action="">
	//        <span class="lpbmlnol">&lt; <</span> <span class="lpbmlnow"><a href="javascript:void(0)">1</a></span> <span class="lpbmlno"><a href="javascript:void(0)">2</a></span> <span class="lpbmlno"><a href="javascript:void(0)">3</a></span> <span class="lpbmlno"><a href="javascript:void(0)">4</a></span> <span class="lpbmlno"><a href="javascript:void(0)">5</a></span> <span class="lpbmlno"><a href="javascript:void(0)">6</a></span> &#8230; <span class="lpbmlno"><a href="javascript:void(0)">38</a></span> <span class="lpbmlno"><a href="javascript:void(0)">></a> &gt;</span> <span class="topage">到第</span> <span>
	//        <input type="text" name="textfield" id="textfield" value="10" class="tpipt01" />
	//        </span> <span>页</span> <span>
	//        <input type="submit" name="button" id="button" value="" class="tpipt02" />
	//        </span>
	//      </form>

	//新的导航
	if($lastpg<=1) return false;

	$pagenavtop ='';
	if($nextpg) 
	{
		$pagenavtop ="<span class='pxfpagernow'><a href='$url=$nextpg'  >rightnow</a></span>";
	}else 
	{
		$pagenavtop ="<span class='pxfpagerno'><a href='$url=$nextpg' >rightnow</a></span>";
	}
	if($nextpg>$lastpg)
	{
		$pagenavtop ="<span class='pxfpagerno'><a>rightnow</a></span>";
	}
	if($prepg<=0){$prepg=0;}

	if($prepg>0) 
	{
		$pagenavtop.="<span class='pxfpagelnow'><a href='$url=$prepg' >leftno</a></span>"; 
	}
	else
	{ 	$pagenavtop.="<span class='pxfpagelno'><a>leftno</a></span>";
	}
		
	//开始分页导航条代码：
	$pagenavtop .='<span class="pxcontent">'.$page.'/'.$lastpg.'</span><span>相关商品共'.$totle.'件</span>';

	$pagenav ='';
	//如果只有一页则跳出函数：


	if($prepg){
		$pagenav="<span class='lpbmlnol'><a href='$url=$prepg'><</a></span> ";
	}else{
		$pagenav='<span class="lpbmlnol">&lt; <</span>';
	}
	if($page==1){
		$pagenav .=" <span class='lpbmlnow'><a href='$url=1'>1</a></span>";
	}
	if($page>1){
		$pagenav .=" <span class='lpbmlnol'><a href='$url=1'>1</a></span> &#8230; ";
	}
	$nowpage=$page-4<0?"2":($page-2);
	if($nowpage+5>=$lastpg)
	{
		$countpage = $lastpg;
	}else
	{
		$countpage = $nowpage+5;
	}
	for($i=$nowpage;$i<=$countpage;$i++)
	{
		if($i==$page){
			$pagecls = "lpbmlnow";
		}else{
			$pagecls = "lpbmlnol";
		}
		if($i<=$lastpg){
			$pagenav.=" <span class='$pagecls'><a href='$url=$i'>".$i."</a></span>";
		}
	}
	if(($countpage+5)<$lastpg){
		$pagenav.=" &#8230; <span class='lpbmlnol'><a href='$url=$lastpg'>".$lastpg."</a></span> ";
	}
	if($nextpg){
		$pagenav.="<span class='lpbmlno'><a href='$url=$nextpg'>></a></span> ";
	}else{
		$pagenav.='<span class="lpbmlno">> &gt;</span>';
	}


	//下拉跳转列表，循环列出所有页码：
//	$pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value'>\n";
//	for($i=1;$i<=$lastpg;$i++){
//		if($i==$page){
//			$pagenav.="<option value='$i' selected>$i</option>\n";
//		}else{
//			$pagenav.="<option value='$i'>$i</option>\n";
//		}
//	}
//	$pagenav.="</select> 页";
	}
}
?>
