<?
$thismenucode = "1c102";

require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_scategoty_b extends browse {
	//var $prgnoware = array ("�����������" );
	var $prgnoware = array ("��������","�ϴ��ļ�����");
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_scat_id";
	
	var $browse_queryselect = "select fd_scat_id , fd_scat_name , fd_fcat_foldername,fd_scat_foldername , fd_fcat_name , fd_scat_time from tb_upload_scategoty 
	                            left join tb_upload_fcategory on tb_upload_scategoty.fd_scat_fcatid=tb_upload_fcategory.fd_fcat_id ";
	
	var $browse_delsql = "delete from tb_upload_scategoty where fd_scat_id = '%s'";
	var $browse_new = "scategory.php";
	var $browse_edit = "scategory.php?id=";
	
	var $browse_field = array ("fd_scat_id", "fd_scat_name", "fd_fcat_foldername", "fd_scat_foldername", "fd_fcat_name", "fd_scat_time" );
	var $browse_find = array (// ��ѯ����
"0" => array ("�����", "fd_scat_name", "TXT" ), "1" => array ("�ļ�����", "fd_scat_foldername", "TXT" ), "2" => array ("������", "fd_fcat_name" ) );
	
	var $browse_link = array ("lk_view1" );
	
	function dodelete() { //  ɾ������.
		$dir = "../file/";
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			//��ȡ�����ļ���
			$query = "select * from tb_upload_scategoty where fd_scat_id='" . $this->browse_check [$i] . "'";
			$this->db->query ( $query );
			$this->db->next_record ();
			$sfolder = $this->db->f ( fd_scat_foldername );
			$fid = $this->db->f ( fd_scat_fcatid );
			
			//��ȡһ���ļ���
			$querys = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";
			$this->db->query ( $querys );
			$this->db->next_record ();
			$ffolder = $this->db->f ( fd_fcat_foldername );
			$dir .= $ffolder . '/';
			$dir .= $sfolder;
			$result = $this->file_exit ( $dir );
			if ($result) {
				die ( "<script>alert('�Ѿ���ͼƬ����ɾ��!'); window.history.back();</script>" );
			} else {
				
				$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
				$this->db->query ( $query ); //ɾ������ļ�¼
				@rmdir ( $dir );
			}
		}
	}
	//ɾ���ļ���
	function file_exit($path) {
		$handle = @opendir ( $path );
		$arr = array ();
		while ( false !== ($file = @readdir ( $handle )) ) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			$file_array [] = $file;
		}
		if ($file_array == NULL) { //û���ļ�
			@closedir ( $handle );
			return false;
		} else {
			//�ж��ļ��������Ƿ���ͼƬ
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
		//���ļ�
	}
}
class fd_scat_id extends browsefield {
	var $bwfd_fdname = "fd_scat_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "	ID"; // �ֶα���
}

class fd_scat_name extends browsefield {
	var $bwfd_fdname = "fd_scat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�����"; // �ֶα���
}

class fd_fcat_foldername extends browsefield {
	var $bwfd_fdname = "fd_fcat_foldername"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ϼ�Ŀ¼"; // �ֶα���
}
class fd_scat_foldername extends browsefield {
	var $bwfd_fdname = "fd_scat_foldername"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ӽ�Ŀ¼"; // �ֶα���
}
class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
class fd_scat_time extends browsefield {
	var $bwfd_fdname = "fd_scat_time"; // ���ݿ����ֶ�����
	var $bwfd_title = "ʱ��"; // �ֶα���
}

// ���Ӷ���
class lk_view1 extends browselink {
	var $bwlk_fdname = array (// �������ݿ����ֶ�����
"0" => array ("fd_scat_id", "" ) );
	var $bwlk_title = "�鿴"; // link����
	var $bwlk_prgname = "chakan.php?id="; // ���ӳ���
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}
if (empty ( $order )) {
	$order = "fd_scat_id";
	$upordown = "desc";
}

$tb_upload_scategoty_bu = new tb_upload_scategoty_b ( );
$tb_upload_scategoty_bu->browse_skin = $loginskin;
$tb_upload_scategoty_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_upload_scategoty_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_upload_scategoty_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_upload_scategoty_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
