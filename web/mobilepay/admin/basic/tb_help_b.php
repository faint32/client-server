<?
$thismenucode = "7n001";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("��������","��������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_help_id";
	 
	 var $browse_queryselect = "select web_help.*,web_helpset.fd_helpset_name from web_help 
	                              left join web_helpset on web_helpset.fd_helpset_id = web_help.fd_help_type
	                            ";
	 var $browse_delsql = "delete from web_help where fd_help_id = '%s'" ;
	 var $browse_new    = "help.php" ;
	 var $browse_edit   = "help.php?id=" ;
	 //var $browse_outtoexcel ="excelwriter_procatalog.php";
	 //var $browse_inputfile = "input_procatalog.php";

	 var $browse_field = array("fd_help_no","fd_help_name","fd_helpset_name","fd_help_state","fd_help_date");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�������" , "fd_help_no","TXT"),
				"1" => array("��������" , "fd_helpset_name","TXT"),
				"2" => array("��������" , "fd_helpset_name","TXT",)
				); 
}
class fd_help_no extends browsefield {
        var $bwfd_fdname = "fd_help_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "�������";	// �ֶα���
}

class fd_help_name extends browsefield {
        var $bwfd_fdname = "fd_help_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_helpset_name extends browsefield {
        var $bwfd_fdname = "fd_helpset_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_help_state extends browsefield {
        var $bwfd_fdname = "fd_help_state";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch($this->bwfd_value){
        		case "2":
        		  $this->bwfd_show = "ͣ��";
        		  break;
        		case "1":
        		  $this->bwfd_show = "ʹ����";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}
class fd_help_date extends browsefield {
        var $bwfd_fdname = "fd_help_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���
}



if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

//echo $teller_userid."-id<br>";
//echo $thismenuqx[1]."=".$thismenuqx[2]."=".$thismenuqx[3];
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
