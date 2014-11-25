<?
$thismenucode = "1c102";

require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_scategoty_b extends browse {
	//var $prgnoware = array ("二级类别设置" );
	var $prgnoware = array ("基本设置","上传文件子类");
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_scat_id";
	
	var $browse_queryselect = "select fd_scat_id , fd_scat_name , fd_fcat_foldername,fd_scat_foldername , fd_fcat_name , fd_scat_time from tb_upload_scategoty 
	                            left join tb_upload_fcategory on tb_upload_scategoty.fd_scat_fcatid=tb_upload_fcategory.fd_fcat_id ";
	
	var $browse_delsql = "delete from tb_upload_scategoty where fd_scat_id = '%s'";
	var $browse_new = "scategory.php";
	var $browse_edit = "scategory.php?id=";
	
	var $browse_field = array ("fd_scat_id", "fd_scat_name", "fd_fcat_foldername", "fd_scat_foldername", "fd_fcat_name", "fd_scat_time" );
	var $browse_find = array (// 查询条件
"0" => array ("类别名", "fd_scat_name", "TXT" ), "1" => array ("文件夹名", "fd_scat_foldername", "TXT" ), "2" => array ("父类名", "fd_fcat_name" ) );
	
	var $browse_link = array ("lk_view1" );
	
	function dodelete() { //  删除过程.
		$dir = "../file/";
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			//获取二级文件名
			$query = "select * from tb_upload_scategoty where fd_scat_id='" . $this->browse_check [$i] . "'";
			$this->db->query ( $query );
			$this->db->next_record ();
			$sfolder = $this->db->f ( fd_scat_foldername );
			$fid = $this->db->f ( fd_scat_fcatid );
			
			//获取一级文件名
			$querys = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";
			$this->db->query ( $querys );
			$this->db->next_record ();
			$ffolder = $this->db->f ( fd_fcat_foldername );
			$dir .= $ffolder . '/';
			$dir .= $sfolder;
			$result = $this->file_exit ( $dir );
			if ($result) {
				die ( "<script>alert('已经有图片不能删除!'); window.history.back();</script>" );
			} else {
				
				$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
				$this->db->query ( $query ); //删除点击的记录
				@rmdir ( $dir );
			}
		}
	}
	//删除文件夹
	function file_exit($path) {
		$handle = @opendir ( $path );
		$arr = array ();
		while ( false !== ($file = @readdir ( $handle )) ) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			$file_array [] = $file;
		}
		if ($file_array == NULL) { //没有文件
			@closedir ( $handle );
			return false;
		} else {
			//判读文件夹下面是否有图片
			foreach ( $file_array as $value ) {
				$arr = explode ( '.', $value );
				switch ($arr [1]) {
					case 'jpg' :
						return true;
						break;
					case 'jpeg' :
						return true;
						break;
					case 'gif' :
						return true;
						break;
					case 'png' :
						return true;
						break;
					case 'pjpeg' :
						return true;
						break;
				}
			}
			return false;
		}
		@closedir ( $handle );
		//有文件
	}
}
class fd_scat_id extends browsefield {
	var $bwfd_fdname = "fd_scat_id"; // 数据库中字段名称
	var $bwfd_title = "	ID"; // 字段标题
}

class fd_scat_name extends browsefield {
	var $bwfd_fdname = "fd_scat_name"; // 数据库中字段名称
	var $bwfd_title = "类别名"; // 字段标题
}

class fd_fcat_foldername extends browsefield {
	var $bwfd_fdname = "fd_fcat_foldername"; // 数据库中字段名称
	var $bwfd_title = "上级目录"; // 字段标题
}
class fd_scat_foldername extends browsefield {
	var $bwfd_fdname = "fd_scat_foldername"; // 数据库中字段名称
	var $bwfd_title = "子级目录"; // 字段标题
}
class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // 数据库中字段名称
	var $bwfd_title = "父类名"; // 字段标题
}
class fd_scat_time extends browsefield {
	var $bwfd_fdname = "fd_scat_time"; // 数据库中字段名称
	var $bwfd_title = "时间"; // 字段标题
}

// 链接定义
class lk_view1 extends browselink {
	var $bwlk_fdname = array (// 所需数据库中字段名称
"0" => array ("fd_scat_id", "" ) );
	var $bwlk_title = "查看"; // link标题
	var $bwlk_prgname = "chakan.php?id="; // 链接程序
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}
if (empty ( $order )) {
	$order = "fd_scat_id";
	$upordown = "desc";
}

$tb_upload_scategoty_bu = new tb_upload_scategoty_b ( );
$tb_upload_scategoty_bu->browse_skin = $loginskin;
$tb_upload_scategoty_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_upload_scategoty_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_upload_scategoty_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_upload_scategoty_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
