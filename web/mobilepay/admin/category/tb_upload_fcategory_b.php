<?php
$thismenucode = "1c101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_fcategory_b extends browse {
	//var $prgnoware = array ("һ���������" );
	var $prgnoware = array ("��������","�ϴ��ļ�����" );
	var $prgnowareurl = array ("", "" );

	var $browse_key = "fd_fcat_id";

	var $browse_queryselect = "select * from tb_upload_fcategory";
	var $browse_delsql = "delete from tb_upload_fcategory where fd_fcat_id = '%s'";
	var $browse_new = "fcategory.php";
	var $browse_edit = "fcategory.php?id=";

	var $browse_field = array ("fd_fcat_id", "fd_fcat_name", "fd_fcat_foldername", "fd_fcat_time" );
	var $browse_find = array (// ��ѯ����
		"0" => array ("�����", "fd_fcat_name", "TXT" ),
		"1" => array ("�ļ�����", "fd_fcat_foldername", "TXT" ),
		"2" => array ("ʱ��", "fd_fcat_time", "TXT" )
	);

	function dodelete() {//  ɾ������.
		$dir = "../file/";
		$ishvaeshow = 0;
		$valname = "";

		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql = "select * from tb_scategoty where fd_scat_fcatid='" . $this->browse_check [$i] . "'";
			$this->db->query ( $sql );
			if ( $this->db->nf () ) {
				die ( "<script>alert('��һ���������ӷ��࣬����ɾ����ض�������!'); window.history.back();</script>" );
			} else {
				//ɾ���ļ���
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
					$this->db->query ( $query ); //ɾ������ļ�¼
					rmdir ( $dir );
				} else {
					$valname .= $foldername . "�� ";
				}
				if ($ishvaeshow == 1) {
					die ( "<script>alert('�������ļ�����ɾ��!: " . $valname . "'); window.history.back();</script>" );
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
		if ($file_array == NULL) { //û���ļ�
			@closedir ( $handle );
			return false;
		}
		@closedir ( $handle );
		return true; //���ļ�
	}
}

class fd_fcat_id extends browsefield {
	var $bwfd_fdname = "fd_fcat_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "ID"; // �ֶα���
}

class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�����"; // �ֶα���
}

class fd_fcat_foldername extends browsefield {
	var $bwfd_fdname = "fd_fcat_foldername"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ļ�����"; // �ֶα���
}

class fd_fcat_time extends browsefield {
	var $bwfd_fdname = "fd_fcat_time"; // ���ݿ����ֶ�����
	var $bwfd_title = "ʱ��"; // �ֶα���
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_upload_fcategory_bu = new tb_upload_fcategory_b ( );
$tb_upload_fcategory_bu->browse_skin = $loginskin;
$tb_upload_fcategory_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_upload_fcategory_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_upload_fcategory_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_upload_fcategory_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
