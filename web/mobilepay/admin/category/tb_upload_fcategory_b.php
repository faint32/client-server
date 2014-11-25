<?php
$thismenucode = "1c101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_fcategory_b extends browse {
	//var $prgnoware = array ("一级类别资料" );
	var $prgnoware = array ("基本设置","上传文件大类" );
	var $prgnowareurl = array ("", "" );

	var $browse_key = "fd_fcat_id";

	var $browse_queryselect = "select * from tb_upload_fcategory";
	var $browse_delsql = "delete from tb_upload_fcategory where fd_fcat_id = '%s'";
	var $browse_new = "fcategory.php";
	var $browse_edit = "fcategory.php?id=";

	var $browse_field = array ("fd_fcat_id", "fd_fcat_name", "fd_fcat_foldername", "fd_fcat_time" );
	var $browse_find = array (// 查询条件
		"0" => array ("类别名", "fd_fcat_name", "TXT" ),
		"1" => array ("文件夹名", "fd_fcat_foldername", "TXT" ),
		"2" => array ("时间", "fd_fcat_time", "TXT" )
	);

	function dodelete() {//  删除过程.
		$dir = "../file/";
		$ishvaeshow = 0;
		$valname = "";

		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql = "select * from tb_scategoty where fd_scat_fcatid='" . $this->browse_check [$i] . "'";
			$this->db->query ( $sql );
			if ( $this->db->nf () ) {
				die ( "<script>alert('该一级分类有子分类，请先删除相关二级分类!'); window.history.back();</script>" );
			} else {
				//删除文件夹
				$query = "select * from tb_upload_fcategory where fd_fcat_id='" . $this->browse_check [$i] . "'";
				$this->db->query ( $query );
				$this->db->next_record ();
				$foldername = $this->db->f ( fd_fcat_foldername );
				$dir .= $foldername;
				$result = $this->file_exit ( $dir );
				if ($result) {
					$ishvaeflage = 1;
					$ishvaeshow = 1;
				}
				if ($ishvaeflage == 0) {
					$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
					$this->db->query ( $query ); //删除点击的记录
					rmdir ( $dir );
				} else {
					$valname .= $foldername . "、 ";
				}
				if ($ishvaeshow == 1) {
					die ( "<script>alert('里面有文件不能删除!: " . $valname . "'); window.history.back();</script>" );
				}
			}
		}
	}

	function file_exit($path) {
		$handle = @opendir ( $path );
		while ( false !== ($file = @readdir ( $handle )) ) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			$file_array [] = $file;
		}
		if ($file_array == NULL) { //没有文件
			@closedir ( $handle );
			return false;
		}
		@closedir ( $handle );
		return true; //有文件
	}
}

class fd_fcat_id extends browsefield {
	var $bwfd_fdname = "fd_fcat_id"; // 数据库中字段名称
	var $bwfd_title = "ID"; // 字段标题
}

class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // 数据库中字段名称
	var $bwfd_title = "类别名"; // 字段标题
}

class fd_fcat_foldername extends browsefield {
	var $bwfd_fdname = "fd_fcat_foldername"; // 数据库中字段名称
	var $bwfd_title = "文件夹名"; // 字段标题
}

class fd_fcat_time extends browsefield {
	var $bwfd_fdname = "fd_fcat_time"; // 数据库中字段名称
	var $bwfd_title = "时间"; // 字段标题
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_upload_fcategory_bu = new tb_upload_fcategory_b ( );
$tb_upload_fcategory_bu->browse_skin = $loginskin;
$tb_upload_fcategory_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_upload_fcategory_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_upload_fcategory_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_upload_fcategory_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
