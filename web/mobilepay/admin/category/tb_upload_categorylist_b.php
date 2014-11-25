<?
$thismenucode = "1c103";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_category_list_b extends browse {
	//var $prgnoware = array ("图片管理" );
	var $prgnoware = array ("基本设置","上传文件管理" );
	var $prgnowareurl = array ("", "" );
	var $browse_key = "fd_cat_id";
	
	var $browse_queryselect = "SELECT * FROM (
								tb_upload_category_list
								LEFT JOIN tb_upload_fcategory ON tb_upload_category_list.fd_cat_fcatid = tb_upload_fcategory.fd_fcat_id
								)
								LEFT JOIN tb_upload_scategoty ON tb_upload_category_list.fd_cat_scatid = tb_upload_scategoty.fd_scat_id
								";
	var $browse_delsql = "delete from tb_upload_category_list where fd_cat_id = '%s'";
	var $browse_new = "categorylist.php";
	var $browse_edit = "categorylist.php?id=";
	var $browse_state = array ("fd_cat_cancel" );
	
	var $browse_field = array ("fd_cat_dateid", "fd_cat_name", "fd_fcat_name", "fd_scat_name", "fd_cat_time", "fd_cat_thumurl", "fd_cat_cancel", "fd_cat_display" );
	var $browse_find = array (// 查询条件
"0" => array ("图片名称", "fd_cat_name", "TXT" ), "1" => array ("一级类名", "fd_fcat_name", "TXT" ), "2" => array ("二级类名", "fd_scat_name", "TXT" ), "3" => array ("存储位置", "fd_cat_url", "TXT" ), "4" => array ("是否删除", "fd_cat_cancel", "TXT" ) );

}

class fd_cat_dateid extends browsefield {
	var $bwfd_fdname = "fd_cat_dateid"; // 数据库中字段名称
	var $bwfd_title = "数据id"; // 字段标题
}
class fd_cat_name extends browsefield {
	var $bwfd_fdname = "fd_cat_name"; // 数据库中字段名称
	var $bwfd_title = "图片名称"; // 字段标题
}
class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // 数据库中字段名称
	var $bwfd_title = "一级类名"; // 字段标题
}
class fd_scat_name extends browsefield {
	var $bwfd_fdname = "fd_scat_name"; // 数据库中字段名称
	var $bwfd_title = "二级类名"; // 字段标题
}
class fd_cat_time extends browsefield {
	var $bwfd_fdname = "fd_cat_time"; // 数据库中字段名称
	var $bwfd_title = "时间"; // 字段标题


}
class fd_cat_thumurl extends browsefield {
	var $bwfd_fdname = "fd_cat_thumurl"; // 数据库中字段名称
	var $bwfd_title = "缩略图"; // 字段标题
	

	function makeshow() { // 将值转为显示值
		$this->bwfd_show = "<img src='" . $this->bwfd_value . "' widht=50 height='50' >";
		
		return $this->bwfd_show;
	}
}
class fd_cat_cancel extends browsefield {
	var $bwfd_fdname = "fd_cat_cancel"; // 数据库中字段名称
	var $bwfd_title = "是否删除"; // 字段标题
	function makeshow() { // 将值转为显示值
		switch ($this->bwfd_value) {
			case "0" :
				$this->bwfd_show = "否";
				break;
			case "1" :
				$this->bwfd_show = "是";
				break;
		}
		return $this->bwfd_show;
	}
}
class fd_cat_display extends browsefield {
	var $bwfd_fdname = "fd_cat_display"; // 数据库中字段名称
	var $bwfd_title = "是否默认显示"; // 字段标题
	function makeshow() { // 将值转为显示值
		switch ($this->bwfd_value) {
			case "0" :
				$this->bwfd_show = "否";
				break;
			case "1" :
				$this->bwfd_show = "是";
				break;
		}
		return $this->bwfd_show;
	}
}

class chakan extends browselink {
	var $bwlk_fdname = array (// 所需数据库中字段名称
"0" => array ("fd_cat_id" ), "1" => array ("fd_cat_display" ) );
	var $bwlk_prgname = "image.php?id=";
	var $bwlk_title = "<span >查看</span>";
	
	function makelink() {
		$delurld = $this->makeprg ();
		$cans = $this->bwlk_fdname [1] [1];
		if (! empty ( $this->bwlk_prgname )) {
			$dels = "<a href=$delurld>$this->bwlk_title</a>";
		}
		return $dels;
	}
}

class lk_view0 extends browselink {
	var $bwlk_fdname = array (// 所需数据库中字段名称
"0" => array ("fd_cat_id" ), "1" => array ("fd_cat_cancel" ) );
	var $bwlk_prgname = "categorylist.php?fd_cat_id=";
	var $bwlk_title = "<span style='color:#0000ff'>删除</span>";
	
	function makelink() {
		$delurl = $this->makeprg ();
		$action = "delete";
		$del = "<a href=$delurl$id&action=$action onclick='return submit_dels()'>$this->bwlk_title</a>";
		return $del;
	}
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}
if (empty ( $order )) {
	$order = "fd_cat_id";
	$upordown = "desc";
}
$tb_upload_category_list_bu = new tb_upload_category_list_b ( );
$tb_upload_category_list_bu->browse_link = array ("chakan", "lk_view0" );
$tb_upload_category_list_bu->browse_skin = $loginskin;
$tb_upload_category_list_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_upload_category_list_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_upload_category_list_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_upload_category_list_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
