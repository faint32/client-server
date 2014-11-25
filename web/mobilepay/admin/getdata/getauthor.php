<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_author_b extends findbrowse{
	 var $prgname = " ��Աѡ��" ;

	 var $brow_key = "fd_author_id";
	 var $brow_queryselect = "select fd_author_id ,fd_author_username , fd_author_truename , fd_author_mobile 
	                          from tb_author ";	 
	 
	 var $brow_field = array("fd_author_id","fd_author_username","fd_author_truename","fd_author_mobile");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_author_id"  ,
   			    "1" => "fd_author_truename",
   			    "2" => "fd_author_mobile",
   			    "3" => "fd_author_username",
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("��Ա���", "fd_author_id"  ,"TXT"),
			  "1" => array("��Ա���", "fd_author_username","TXT"),
			  "2" => array("��Աȫ��", "fd_author_truename","TXT")
			 );
}

class fd_author_id extends findbrowsefield {
        var $bwfd_fdname = "fd_author_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ա���";	// �ֶα���
}

class fd_author_username extends findbrowsefield {
        var $bwfd_fdname = "fd_author_username";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ա�˻�";	// �ֶα���
}

class fd_author_truename extends findbrowsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ա��ʵ����";	// �ֶα���
}
class fd_author_mobile extends findbrowsefield {
        var $bwfd_fdname = "fd_author_mobile";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ա�ֻ�";	// �ֶα���
}

if(empty($order)){
	$order = "fd_author_id";
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_author_bu = new tb_author_b ;

$tb_author_bu->brow_skin = $loginskin ;
$tb_author_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>