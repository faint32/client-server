<?
$thismenucode = "7n006";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_mobile_b extends browse {
	//var $prgnoware = array ("刷卡器管理", "机型名称设置" );
	var $prgnoware = array ("辅助功能", "刷卡机型" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_mobile_id";
	
	var $browse_queryselect = "select *  from tb_mobile left join tb_mobilebrand on fd_mobilebrand_id = fd_mobile_brandid ";
	 var $browse_inputfile ="input_mobile.php";
	 
	 var $browse_fieldname =  array("机子名","品牌","尺寸","分辨率","操作系统");
	 var $browse_fieldval  =  array("fd_mobile_name","fd_mobile_brandid","fd_mobile_size","fd_mobile_hvga","fd_mobile_os");
     var $browse_ischeck  =  array("1","1","1","1"); 
     
	var $browse_edit = "mobile.php?listid=";
	var $browse_new = "mobile.php";
	var $browse_delsql = "delete from tb_mobile where fd_mobile_id = '%s'";
	
	var $browse_field = array ("fd_mobile_id","fd_mobilebrand_name", "fd_mobile_name","fd_mobile_size","fd_mobile_hvga","fd_mobile_os","fd_mobile_allow" );
	var $browse_find = array (// 查询条件
	 "0" => array ("编号", "fd_mobile_id", "TXT" ),
	 "1" => array ("机子名", "fd_mobile_name", "TXT" ),
	 "2" => array ("尺寸", "fd_mobile_size", "TXT" ),
	 "3" => array ("分辨率", "fd_mobile_hvga", "TXT" ),
	 "4" => array ("品牌", "fd_mobile_brand", "TXT" )
	 );
}

class fd_mobile_id extends browsefield {
	var $bwfd_fdname = "fd_mobile_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}
class fd_mobile_name extends browsefield {
	var $bwfd_fdname = "fd_mobile_name"; // 数据库中字段名称
	var $bwfd_title = "机子名"; // 字段标题
}
class fd_mobilebrand_name extends browsefield {
	var $bwfd_fdname = "fd_mobilebrand_name"; // 数据库中字段名称
	var $bwfd_title = "品牌"; // 字段标题
}
class fd_mobile_size extends browsefield {
	var $bwfd_fdname = "fd_mobile_size"; // 数据库中字段名称
	var $bwfd_title = "尺寸"; // 字段标题
}
class fd_mobile_hvga extends browsefield {
	var $bwfd_fdname = "fd_mobile_hvga"; // 数据库中字段名称
	var $bwfd_title = "分辨率"; // 字段标题
}
class fd_mobile_os extends browsefield {
	var $bwfd_fdname = "fd_mobile_os"; // 数据库中字段名称
	var $bwfd_title = "操作系统"; // 字段标题
}
class fd_mobile_allow extends browsefield {
	var $bwfd_fdname = "fd_mobile_allow"; // 数据库中字段名称
	var $bwfd_title = "是否适配刷卡器"; // 字段标题
	function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "适配";
        		     break;       		
        		case "0":
        		    $this->bwfd_show = "不适配";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
if (empty ( $order )) {
	$order = "fd_mobile_id";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_mobile_b_bu = new tb_mobile_b ( );
$tb_mobile_b_bu->browse_skin = $loginskin;
$tb_mobile_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_mobile_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_mobile_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_mobile_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
