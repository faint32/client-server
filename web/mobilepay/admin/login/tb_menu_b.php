<?
$thismenucode = "9101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_menu_b extends browse {
	 var $prgnoware = array("系统管理","菜单管理","菜单设置");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_menu_id";
 	 var $browse_queryselect = "select fd_menu_id,fd_menu_code,fd_menu_upcode,fd_menu_jpg,fd_menu_name,fd_menu_url,fd_menu_hz,fd_menu_sno,fd_menu_active,CONCAT_WS(',',fd_menu_code,fd_menu_name,fd_menu_upcode) as fd_menu_node from tb_menu ";
       
	 var $browse_delsql = "delete from tb_menu where fd_menu_id = %s" ;
	 var $browse_new = "menu.php" ;
	 var $browse_edit = "menu.php?id=" ;
   
	 var $browse_field = array("fd_menu_code","fd_menu_upcode","fd_menu_jpg","fd_menu_name","fd_menu_url","fd_menu_node","fd_menu_hz","fd_menu_sno","fd_menu_active");
	// var $browse_link  = array("lk_view0");

	 /*var $browse_find = array(		// 查询条件
				"0" => array("用户名", "fd_menu_code","TXT")	,
				"1" => array("用户组", "fd_menu_upcode","TXT")										
			 );*/
}

class fd_menu_code extends browsefield {
        var $bwfd_fdname = "fd_menu_code";	// 数据库中字段名称
        var $bwfd_title = "菜单代码";	// 字段标题
}

class fd_menu_upcode extends browsefield {
        var $bwfd_fdname = "fd_menu_upcode";	// 数据库中字段名称
        var $bwfd_title = "上级菜单代码";	// 字段标题
}
class fd_menu_name extends browsefield {
        var $bwfd_fdname = "fd_menu_name";	// 数据库中字段名称
        var $bwfd_title = "菜单名称";	// 字段标题
}
class fd_menu_jpg extends browsefield {
        var $bwfd_fdname = "fd_menu_jpg";	// 数据库中字段名称
        var $bwfd_title = "菜单图片";	// 字段标题
		
}
class fd_menu_url extends browsefield {
        var $bwfd_fdname = "fd_menu_url";	// 数据库中字段名称
        var $bwfd_title = "菜单url";	// 字段标题
}
class fd_menu_node extends browsefield {
			var $bwfd_fdname = "fd_menu_node";	// 数据库中字段名称
			var $bwfd_title = "菜单节点";	// 字段标题
			function makeshow() {	// 将值转为显示值
			$this->var = explode(",",$this->bwfd_value);
			$this->bwfd_show = getupmeuncode($this->var[0]);
			return $this->bwfd_show;
		}
		
}
class fd_menu_hz extends browsefield {
        var $bwfd_fdname = "fd_menu_hz";	// 数据库中字段名称
        var $bwfd_title = "菜单hz";	// 字段标题
}
class fd_menu_sno extends browsefield {
        var $bwfd_fdname = "fd_menu_sno";	// 数据库中字段名称
        var $bwfd_title = "菜单sno";	// 字段标题
}
class fd_menu_active extends browsefield {
        var $bwfd_fdname = "fd_menu_active";	// 数据库中字段名称
        var $bwfd_title = "激活状态";	// 字段标题
}



// 链接定义
/*class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_menu_id") 
   			    );
   var $bwlk_prgname = "menu.php?id=";
   var $bwlk_title ="修改";  
}*/


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_menu_bu = new tb_menu_b ;


//$tb_menu_bu->browse_querywhere = " fd_tel_recsts=0 and fd_menu_id !='1'" ;




$tb_menu_bu->browse_skin = $loginskin ;
$tb_menu_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_menu_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_menu_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_menu_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;

function getupmeuncode($i){
		while($i)
		  {
			$return_array= getupmenuname($i);
			if(is_array($return_array))
			{
				$i	  = $return_array['mnuupcode'];
				$name = $return_array['mnuname']."_".$name; 
			}else
			{
				$i = false;
			}
		  }
		return $name;

}
function getupmenuname($code)
{
	$db  = new DB_test;	
	$query= "select fd_menu_code as mnucode,fd_menu_upcode as mnuupcode,fd_menu_name as mnuname  from tb_menu where fd_menu_code = '$code'";
	$return_array = $db->get_one($query);
	return $return_array;
}
function g2u($str)
{
	$value=iconv("gb2312", "UTF-8", $str);
	return $value;
}
?>