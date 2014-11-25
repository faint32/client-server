<?
$thismenucode = "2k114";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bankcard_b extends browse {
	var $prgnoware = array ("��������","��ˢ���п�" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_bankcard_id";
	
	var $browse_queryselect = "select * from tb_bankcard 
								left join tb_bank on  fd_bank_id = fd_bankcard_bankid
								left join tb_author on fd_bankcard_authorid=fd_author_id ";
	
	var $browse_edit = "bankcard.php?listid=";
	var $browse_new = "bankcard.php";
	var $browse_delsql = "delete from tb_bankcard where fd_bankcard_id = '%s'" ;
	//var $browse_link  = array("lk_view0");
	
	var $browse_field = array ("fd_bankcard_id", "fd_bankcard_key", "fd_author_truename","fd_bank_name","fd_bankcard_active","fd_bankcard_isnew" );
	var $browse_find = array (// ��ѯ����
"0" => array ("���", "fd_bankcard_id", "TXT" ), "1" => array ("��������", "fd_bankcard_name", "TXT" ) );
}

class fd_bankcard_id extends browsefield {
	var $bwfd_fdname = "fd_bankcard_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_bankcard_key extends browsefield {
	var $bwfd_fdname = "fd_bankcard_key"; // ���ݿ����ֶ�����
	var $bwfd_title = "�豸��"; // �ֶα���
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // ���ݿ����ֶ�����
	var $bwfd_title = "�û���"; // �ֶα���
}
class fd_bank_name extends browsefield {
	var $bwfd_fdname = "fd_bank_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_bankcard_active extends browsefield {
	var $bwfd_fdname = "fd_bankcard_active"; // ���ݿ����ֶ�����
	var $bwfd_title = "״̬"; // �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "û����";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "�Ѽ���";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }	
}
class fd_bankcard_isnew extends browsefield {
	var $bwfd_fdname = "fd_bankcard_isnew"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ��¿�"; // �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }	
}
// ���Ӷ���





class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_bankcard_id","")
   			    );  
   var $bwlk_title ="���п�����";	// link����
   var $bwlk_prgname = "bankcardset.php?listid=";	// ���ӳ���
}


if (empty ( $order )) {
	$order = "fd_bankcard_id";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_bankcard_b_bu = new tb_bankcard_b ( );
$tb_bankcard_b_bu->browse_skin = $loginskin;
$tb_bankcard_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_bankcard_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_bankcard_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


$tb_bankcard_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
