<?
$thismenucode = "1c612";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_usefultype_b extends browse 
{
	 var $prgnoware    = array("��վ��̨","��ҳ���ർ��");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_usefultype_id";
	 
	 var $browse_queryselect = "select * from web_usefultype 
	                            ";
	// var $browse_delsql = "delete from web_usefultype where fd_usefultype_id = '%s'" ;
	// var $browse_new    = "showpro.php" ;
	 var $browse_edit   = "indexnav.php?listid=" ;
	 
	 //var $browse_outtoexcel ="excelwriter_usefultype.php";
	 //var $browse_inputfile = "input_usefultype.php";

	 var $browse_field = array("fd_usefultype_id","fd_usefultype_no","fd_usefultype_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("������" , "fd_usefultype_name","TXT"),

				);
	 

}

class fd_usefultype_id extends browsefield {
        var $bwfd_fdname = "fd_usefultype_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_usefultype_no extends browsefield {
        var $bwfd_fdname = "fd_usefultype_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
		var $bwfd_align = "center";
		
}
class fd_usefultype_name extends browsefield {
        var $bwfd_fdname = "fd_usefultype_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
		var $bwfd_align = "center";
		
}

class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_usefultype_id") 
   			    );
   var $bwlk_prgname = "toptra.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�Ƽ�Ʒ��</span>";  
   
  
}
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_usefultype_id") 
   			    );
   var $bwlk_prgname = "toptralogo.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�Ƽ�Ʒ��logo</span>";  
   
  
}


if(empty($order)){
	$order = "fd_usefultype_title";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_usefultype_b_bu = new tb_usefultype_b ;
$tb_usefultype_b_bu->browse_skin = $loginskin ;
$tb_usefultype_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_usefultype_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_usefultype_b_bu->browse_editqx = 1;  // �༭Ȩ��
$tb_usefultype_b_bu->browse_link  = array("lk_view0","lk_view1");

$tb_usefultype_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
