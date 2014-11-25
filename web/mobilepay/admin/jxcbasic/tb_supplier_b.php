<?
$thismenucode = "2k102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_supplier_b extends browse 
{
	 var $prgnoware    = array("��������","��Ӧ������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_supp_id";
	 
	 var $browse_queryselect = "select * from tb_supplier ";
	 var $browse_delsql = "delete from tb_supplier where fd_supp_id = '%s'" ;
	 var $browse_edit = "supplier.php?&id=" ;
     var $browse_new  = "supplier.php";

	 var $browse_field = array("fd_supp_no","fd_supp_name","fd_supp_allname","fd_supp_supptypeid","fd_supp_xingfen",
	 							"fd_supp_linkman","fd_supp_manphone","fd_supp_workstatus","fd_supp_memo");
 	 var $browse_find = array(		// ��ѯ����
 	      "0" => array("���"      , "fd_supp_no"        ,"TXT") ,
				"1" => array("��Ӧ�̼��", "fd_supp_name"      ,"TXT") ,
				"2" => array("��Ӧ��ȫ��", "fd_supp_allname"   ,"TXT") ,
				"3" => array("��ϵ��"    , "fd_supp_linkman"   ,"TXT") ,
				"4" => array("ְλ"      , "fd_supp_position"  ,"TXT") ,
				"5" => array("�ֻ�����"  , "fd_supp_manphone"  ,"TXT") ,
				"6" => array("����"      , "fd_srte_name"      ,"TXT") ,
				"7" => array("����ʡ��"  , "fd_supp_xingfen"   ,"TXT")
				);
}

class fd_supp_no extends browsefield {
        var $bwfd_fdname = "fd_supp_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_supp_name extends browsefield {
        var $bwfd_fdname = "fd_supp_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_supp_allname extends browsefield {
        var $bwfd_fdname = "fd_supp_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "ȫ��";	// �ֶα���
}

class fd_supp_supptypeid extends browsefield {
        var $bwfd_fdname = "fd_supp_supptypeid";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_supp_xingfen extends browsefield {
        var $bwfd_fdname = "fd_supp_xingfen";	// ���ݿ����ֶ�����
        var $bwfd_title = "ʡ��";	// �ֶα���
}

class fd_supp_linkman extends browsefield {
        var $bwfd_fdname = "fd_supp_linkman";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ��";	// �ֶα���
}

class fd_supp_manphone extends browsefield {
        var $bwfd_fdname = "fd_supp_manphone";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ֻ�";	// �ֶα���
}

class fd_supp_workstatus extends browsefield {
        var $bwfd_fdname = "fd_supp_workstatus";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӫ״��";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "����";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��ͣ��";
        		     break;
        		case "2":
        		    $this->bwfd_show = "ͣ��";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}

class fd_supp_memo extends browsefield {
        var $bwfd_fdname = "fd_supp_memo";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ע";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
          $showvalue = $this->bwfd_value ;
          $showvalue = FSubstr($showvalue,0,15);
          return $showvalue ;
        }
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_supp_no";
}

$tb_supplier_b_bu = new tb_supplier_b ;
$tb_supplier_b_bu->browse_skin = $loginskin ;
$tb_supplier_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_supplier_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_supplier_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_supplier_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
