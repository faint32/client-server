<?
$thismenucode = "7n006";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_mobile_b extends browse {
	//var $prgnoware = array ("ˢ��������", "������������" );
	var $prgnoware = array ("��������", "ˢ������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_mobile_id";
	
	var $browse_queryselect = "select *  from tb_mobile left join tb_mobilebrand on fd_mobilebrand_id = fd_mobile_brandid ";
	 var $browse_inputfile ="input_mobile.php";
	 
	 var $browse_fieldname =  array("������","Ʒ��","�ߴ�","�ֱ���","����ϵͳ");
	 var $browse_fieldval  =  array("fd_mobile_name","fd_mobile_brandid","fd_mobile_size","fd_mobile_hvga","fd_mobile_os");
     var $browse_ischeck  =  array("1","1","1","1"); 
     
	var $browse_edit = "mobile.php?listid=";
	var $browse_new = "mobile.php";
	var $browse_delsql = "delete from tb_mobile where fd_mobile_id = '%s'";
	
	var $browse_field = array ("fd_mobile_id","fd_mobilebrand_name", "fd_mobile_name","fd_mobile_size","fd_mobile_hvga","fd_mobile_os","fd_mobile_allow" );
	var $browse_find = array (// ��ѯ����
	 "0" => array ("���", "fd_mobile_id", "TXT" ),
	 "1" => array ("������", "fd_mobile_name", "TXT" ),
	 "2" => array ("�ߴ�", "fd_mobile_size", "TXT" ),
	 "3" => array ("�ֱ���", "fd_mobile_hvga", "TXT" ),
	 "4" => array ("Ʒ��", "fd_mobile_brand", "TXT" )
	 );
}

class fd_mobile_id extends browsefield {
	var $bwfd_fdname = "fd_mobile_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_mobile_name extends browsefield {
	var $bwfd_fdname = "fd_mobile_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
class fd_mobilebrand_name extends browsefield {
	var $bwfd_fdname = "fd_mobilebrand_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "Ʒ��"; // �ֶα���
}
class fd_mobile_size extends browsefield {
	var $bwfd_fdname = "fd_mobile_size"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ߴ�"; // �ֶα���
}
class fd_mobile_hvga extends browsefield {
	var $bwfd_fdname = "fd_mobile_hvga"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ֱ���"; // �ֶα���
}
class fd_mobile_os extends browsefield {
	var $bwfd_fdname = "fd_mobile_os"; // ���ݿ����ֶ�����
	var $bwfd_title = "����ϵͳ"; // �ֶα���
}
class fd_mobile_allow extends browsefield {
	var $bwfd_fdname = "fd_mobile_allow"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ�����ˢ����"; // �ֶα���
	function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "����";
        		     break;       		
        		case "0":
        		    $this->bwfd_show = "������";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
if (empty ( $order )) {
	$order = "fd_mobile_id";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_mobile_b_bu = new tb_mobile_b ( );
$tb_mobile_b_bu->browse_skin = $loginskin;
$tb_mobile_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_mobile_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_mobile_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


$tb_mobile_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
