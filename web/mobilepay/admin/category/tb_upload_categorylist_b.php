<?
$thismenucode = "1c103";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_upload_category_list_b extends browse {
	//var $prgnoware = array ("ͼƬ����" );
	var $prgnoware = array ("��������","�ϴ��ļ�����" );
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
	var $browse_find = array (// ��ѯ����
"0" => array ("ͼƬ����", "fd_cat_name", "TXT" ), "1" => array ("һ������", "fd_fcat_name", "TXT" ), "2" => array ("��������", "fd_scat_name", "TXT" ), "3" => array ("�洢λ��", "fd_cat_url", "TXT" ), "4" => array ("�Ƿ�ɾ��", "fd_cat_cancel", "TXT" ) );

}

class fd_cat_dateid extends browsefield {
	var $bwfd_fdname = "fd_cat_dateid"; // ���ݿ����ֶ�����
	var $bwfd_title = "����id"; // �ֶα���
}
class fd_cat_name extends browsefield {
	var $bwfd_fdname = "fd_cat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "ͼƬ����"; // �ֶα���
}
class fd_fcat_name extends browsefield {
	var $bwfd_fdname = "fd_fcat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "һ������"; // �ֶα���
}
class fd_scat_name extends browsefield {
	var $bwfd_fdname = "fd_scat_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_cat_time extends browsefield {
	var $bwfd_fdname = "fd_cat_time"; // ���ݿ����ֶ�����
	var $bwfd_title = "ʱ��"; // �ֶα���


}
class fd_cat_thumurl extends browsefield {
	var $bwfd_fdname = "fd_cat_thumurl"; // ���ݿ����ֶ�����
	var $bwfd_title = "����ͼ"; // �ֶα���
	

	function makeshow() { // ��ֵתΪ��ʾֵ
		$this->bwfd_show = "<img src='" . $this->bwfd_value . "' widht=50 height='50' >";
		
		return $this->bwfd_show;
	}
}
class fd_cat_cancel extends browsefield {
	var $bwfd_fdname = "fd_cat_cancel"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ�ɾ��"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
		switch ($this->bwfd_value) {
			case "0" :
				$this->bwfd_show = "��";
				break;
			case "1" :
				$this->bwfd_show = "��";
				break;
		}
		return $this->bwfd_show;
	}
}
class fd_cat_display extends browsefield {
	var $bwfd_fdname = "fd_cat_display"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ�Ĭ����ʾ"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
		switch ($this->bwfd_value) {
			case "0" :
				$this->bwfd_show = "��";
				break;
			case "1" :
				$this->bwfd_show = "��";
				break;
		}
		return $this->bwfd_show;
	}
}

class chakan extends browselink {
	var $bwlk_fdname = array (// �������ݿ����ֶ�����
"0" => array ("fd_cat_id" ), "1" => array ("fd_cat_display" ) );
	var $bwlk_prgname = "image.php?id=";
	var $bwlk_title = "<span >�鿴</span>";
	
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
	var $bwlk_fdname = array (// �������ݿ����ֶ�����
"0" => array ("fd_cat_id" ), "1" => array ("fd_cat_cancel" ) );
	var $bwlk_prgname = "categorylist.php?fd_cat_id=";
	var $bwlk_title = "<span style='color:#0000ff'>ɾ��</span>";
	
	function makelink() {
		$delurl = $this->makeprg ();
		$action = "delete";
		$del = "<a href=$delurl$id&action=$action onclick='return submit_dels()'>$this->bwlk_title</a>";
		return $del;
	}
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
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
$tb_upload_category_list_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_upload_category_list_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_upload_category_list_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_upload_category_list_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
