<?
$thismenucode = "1n001";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_author_sp_b extends browse 
{
	 var $prgnoware    = array("��Ա","��ע����Ա");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_author_id";
	 
	 var $browse_queryselect = "select * from tb_author ";
	
	 //var $browse_edit   = "author_stop.php?listid=" ;
	 
	

	 var $browse_field = array("fd_author_id","fd_author_username","fd_author_truename","fd_author_mobile","fd_author_regtime");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_author_id","TXT"),
				); 
}

class  fd_author_id  extends browsefield {
        var $bwfd_fdname = "fd_author_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_author_username  extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}
class fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ʵ��";	// �ֶα���
}
class fd_author_mobile  extends browsefield {
        var $bwfd_fdname = "fd_author_mobile";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ֻ�����";	// �ֶα���
}
class fd_author_email  extends browsefield {
        var $bwfd_fdname = "fd_author_email";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_author_regtime extends browsefield {
        var $bwfd_fdname = "fd_author_regtime";	// ���ݿ����ֶ�����
        var $bwfd_title = "ע��ʱ��";	// �ֶα���
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "authorsp.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>���</span>";  
   
  
 
} */

if(empty($order)){
	$order = "fd_author_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_sp_b_bu = new tb_author_sp_b ;
$tb_author_sp_b_bu->browse_skin = $loginskin ;
$tb_author_sp_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = 1;  // �༭Ȩ��
$tb_author_sp_b_bu->browse_querywhere = "fd_author_state = -1 and fd_author_isstop = 0";
/* $tb_author_sp_b_bu->browse_link  = array("lk_view0"); */
$tb_author_sp_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
