<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("��������","�������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_feedback_id";
	 

	 var $browse_queryselect = "select * from tb_feedback left join tb_author on fd_author_id = fd_feedback_authorid ";
	 
	 var $browse_delsql = "delete from tb_feedback where fd_feedback_id = '%s'" ;
	// var $browse_new = "feedback.php" ;
	 var $browse_edit = "feedback.php?listid=" ;
	 var $browse_new ="feedback.php";
   
	 var $browse_field = array("fd_author_truename","fd_feedback_content","fd_feedback_linkman","fd_feedback_datetime","fd_feedback_isread","fd_feedback_isreadtime","fd_feedback_isreply","fd_feedback_isreplytime","fd_feedback_isreplycontent");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�û���", "fd_author_truename","TXT"),
				"1" => array("����ʱ��", "fd_feedback_datetime","TXT"),
				"2" => array("��ϵ����Ϣ", "fd_feedback_linkman","TXT"),
				"3" => array("����Ķ�ʱ��", "fd_feedback_isreadtime","TXT"),
				"4" => array("�ظ�ʱ��", "fd_feedback_isreplytime","TXT")
				);
	 
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}

class fd_feedback_content extends browsefield {
        var $bwfd_fdname = "fd_feedback_content";	// ���ݿ����ֶ�����
        var $bwfd_title = "�������";	// �ֶα���
}
class fd_feedback_linkman extends browsefield {
        var $bwfd_fdname = "fd_feedback_linkman";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ����Ϣ";	// �ֶα���
}
class fd_feedback_datetime extends browsefield {
        var $bwfd_fdname = "fd_feedback_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���
}
class fd_feedback_isread extends browsefield {
        var $bwfd_fdname = "fd_feedback_isread";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ����";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
				default:
					   $this->bwfd_show = "��";
        		     break;     		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_feedback_isreadtime extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreadtime";	// ���ݿ����ֶ�����
        var $bwfd_title = "����Ķ�ʱ��";	// �ֶα���
}
class fd_feedback_isreply extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreply";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ǻظ�";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
				default:
					   $this->bwfd_show = "��";
        		     break;         		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_feedback_isreplytime extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreplytime";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ظ�ʱ��";	// �ֶα���
}
class fd_feedback_isreplycontent extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreplycontent";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ظ�����";	// �ֶα���
}



if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

