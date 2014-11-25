<?
$thismenucode = "2k102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_manufacturer_b extends browse 
{
	 var $prgnoware    = array("��������","����������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_manu_id";
	 
	 var $browse_queryselect = "select * from tb_manufacturer ";
	 var $browse_delsql = "delete from tb_manufacturer where fd_manu_id = '%s'" ;
	 var $browse_edit = "manufacturer.php?listid=" ;
     var $browse_new  = "manufacturer.php";

	 var $browse_field = array("fd_manu_no","fd_manu_name","fd_manu_allname","fd_manu_xingfen",
	 							"fd_manu_linkman","fd_manu_manphone","fd_manu_workstatus");
 	 var $browse_find = array(		// ��ѯ����
 	      "0" => array("���"      , "fd_manu_no"        ,"TXT") ,
				"1" => array("�����̼��", "fd_manu_name"      ,"TXT") ,
				"2" => array("������ȫ��", "fd_manu_allname"   ,"TXT") ,
				"3" => array("��ϵ��"    , "fd_manu_linkman"   ,"TXT") ,
				"5" => array("�ֻ�����"  , "fd_manu_manphone"  ,"TXT") ,
				"7" => array("����ʡ��"  , "fd_manu_xingfen"   ,"TXT")
				);
}

class fd_manu_no extends browsefield {
        var $bwfd_fdname = "fd_manu_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̱��";	// �ֶα���
}

class fd_manu_name extends browsefield {
        var $bwfd_fdname = "fd_manu_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̼��";	// �ֶα���
}

class fd_manu_allname extends browsefield {
        var $bwfd_fdname = "fd_manu_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȫ��";	// �ֶα���
}

class fd_manu_xingfen extends browsefield {
        var $bwfd_fdname = "fd_manu_xingfen";	// ���ݿ����ֶ�����
        var $bwfd_title = "ʡ��";	// �ֶα���
}

class fd_manu_linkman extends browsefield {
        var $bwfd_fdname = "fd_manu_linkman";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ��";	// �ֶα���
}

class fd_manu_manphone extends browsefield {
        var $bwfd_fdname = "fd_manu_manphone";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ���ֻ�";	// �ֶα���
}

class fd_manu_workstatus extends browsefield {
        var $bwfd_fdname = "fd_manu_workstatus";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӫ״��";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "����";
        		     break;       		
        		case "2":
        		    $this->bwfd_show = "��ͣ��";
        		     break;
        		case "3":
        		    $this->bwfd_show = "ͣ��";
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

if(empty($order)){
	$order = "fd_manu_no";
}

$tb_manufacturer_b_bu = new tb_manufacturer_b ;
$tb_manufacturer_b_bu->browse_skin = $loginskin ;
$tb_manufacturer_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_manufacturer_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_manufacturer_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_manufacturer_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
