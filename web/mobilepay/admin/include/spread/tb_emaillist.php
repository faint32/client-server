<?
$thismenucode = "7002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_squadno_b extends browse 
{
	 var $prgnoware    = array("�ƹ����","�ʼ��ƹ�");
	 var $prgnowareurl =  array("","");

	 var $browse_key = "fd_emaillist_id";
	 
	 var $browse_queryselect = "select * from tb_emaillist ";
     var $browse_querywhere = "";
	                            
	 var $browse_delsql = "delete from tb_emaillist where fd_emaillist_id = '%s'" ;
	 var $browse_new = "emaillist.php" ;
	 var $browse_edit = "emaillist.php?id=" ;
	 
	 var $browse_field = array("fd_emaillist_title","fd_emaillist_file","fd_emaillist_date","fd_emaillist_fsdate");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�ʼ�����"      ,   "fd_emaillist_title"   ,"TXT") ,
				"1" => array("�ʼ�ģ��"      ,   "fd_emaillist_file"   ,"TXT") 
				);
	 
}	

class fd_emaillist_title extends browsefield {
        var $bwfd_fdname = "fd_emaillist_title";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʼ�����";	// �ֶα���
}

class fd_emaillist_file extends browsefield {
        var $bwfd_fdname = "fd_emaillist_file";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʼ�ģ��";	// �ֶα���		
}

class fd_emaillist_date extends browsefield {
        var $bwfd_fdname = "fd_emaillist_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���	
		function makeshow(){
		  if(!empty($this->bwfd_value)){
		    return date("Y-m-d H:i:s",$this->bwfd_value);
		  }else{
		    return '';
		  }
		}			
}

class fd_emaillist_fsdate extends browsefield {
        var $bwfd_fdname = "fd_emaillist_fsdate";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���	
		function makeshow(){
		  if(!empty($this->bwfd_value)){
		    return date("Y-m-d H:i:s",$this->bwfd_value);
		  }else{
		    return '';
		  }
		}	
}



if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_squadno_b_bu = new tb_squadno_b ;
$tb_squadno_b_bu->browse_skin = $loginskin ;
$tb_squadno_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_squadno_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_squadno_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_squadno_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
