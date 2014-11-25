<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_version_b extends browse 
{
	 var $prgnoware    = array("��������","�汾����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_version_id";
	 

	 var $browse_queryselect = "select * from tb_version ";
	 
	 var $browse_delsql = "delete from tb_version where fd_version_id = '%s'" ;
	 var $browse_new = "version.php" ;
	 var $browse_edit = "version.php?id=" ;
   
	 var $browse_field = array("fd_version_no","fd_version_apptype","fd_version_updatetime",
	 														"fd_version_datetime","fd_version_isnew","fd_version_downurl",
	 														"fd_version_clearoldinfo","fd_version_newcontent","fd_version_strupdate");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�汾��", "fd_version_no","TXT"),
				"1" => array("APP����", "fd_version_apptype","TXT")
				);
	 
}

class fd_version_no extends browsefield {
        var $bwfd_fdname = "fd_version_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "�汾��";	// �ֶα���
}

class fd_version_apptype extends browsefield {
        var $bwfd_fdname = "fd_version_apptype";	// ���ݿ����ֶ�����
        var $bwfd_title = "APP����";	// �ֶα���
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "android_phone";
        		     break;
        		case "2":
        		    $this->bwfd_show = "ios_phone";
        		     break; 
        		case "3":
        		    $this->bwfd_show = "android_pad";
        		     break;     
				case "4":
        		    $this->bwfd_show = "ios_pad";
        		     break;  		 								
          }
		      return $this->bwfd_show ;
  	    }
}



class fd_version_updatetime extends browsefield {
        var $bwfd_fdname = "fd_version_updatetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "�汾����ʱ��";	// �ֶα���
}
class fd_version_datetime extends browsefield {
        var $bwfd_fdname = "fd_version_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���
}
class fd_version_isnew extends browsefield {
        var $bwfd_fdname = "fd_version_isnew";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ����°汾";	// �ֶα���
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
class fd_version_downurl extends browsefield {
        var $bwfd_fdname = "fd_version_downurl";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ص�ַ";	// �ֶα���
}
class fd_version_clearoldinfo extends browsefield {
        var $bwfd_fdname = "fd_version_clearoldinfo";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ɵ�����";	// �ֶα���
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
class fd_version_newcontent extends browsefield {
        var $bwfd_fdname = "fd_version_newcontent";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_version_strupdate extends browsefield {
        var $bwfd_fdname = "fd_version_strupdate";	// ���ݿ����ֶ�����
        var $bwfd_title = "ǿ�Ƹ���";	// �ֶα���
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




if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_version_b_bu = new tb_version_b ;
$tb_version_b_bu->browse_skin = $loginskin ;
$tb_version_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_version_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_version_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_version_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

