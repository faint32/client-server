<?
$thismenucode = "7001";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_squadno_b extends browse 
{
	 var $prgnoware    = array("�ƹ����","�������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_emailuser_id";
	 
	 var $browse_queryselect = "select * from tb_emailuser ";
     var $browse_querywhere = "";
	                            
	 var $browse_delsql = "delete from tb_emailuser where fd_emailuser_id = '%s'" ;
	 var $browse_new = "emailuser.php" ;
	 var $browse_edit = "emailuser.php?id=" ;
	 
	 var $browse_field = array("fd_emailuser_name","fd_emailuser_nick","fd_emailuser_host","fd_emailuser_port","fd_emailuser_status");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�����û���"      ,   "fd_emailuser_name"   ,"TXT") ,
				"1" => array("���ҹ�˾"      ,   "fd_emailuser_nick"   ,"TXT") 
				);
	 
}	

class fd_emailuser_name extends browsefield {
        var $bwfd_fdname = "fd_emailuser_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����û���";	// �ֶα���
}

class fd_emailuser_nick extends browsefield {
        var $bwfd_fdname = "fd_emailuser_nick";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����˳ƺ�";	// �ֶα���		
}

class fd_emailuser_host extends browsefield {
        var $bwfd_fdname = "fd_emailuser_host";	// ���ݿ����ֶ�����
        var $bwfd_title = "���������";	// �ֶα���		
}

class fd_emailuser_port extends browsefield {
        var $bwfd_fdname = "fd_emailuser_port";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˿�";	// �ֶα���		
}

class fd_emailuser_status extends browsefield {
        var $bwfd_fdname = "fd_emailuser_status";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���	
		function makeshow(){
		    if($this->bwfd_value == 1) {      		
		      return "����" ;
  	        }else{
			  return "�ر�" ;
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
