<?
$thismenucode = "2n108";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_paycardactivelist_b extends browse 
{
	 var $prgnoware    = array("ˢ��������","�Ѽ���ˢ����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_pcdactive_id";
	 
	 var $browse_queryselect = "select * from  tb_paycardactivelist 
								left join tb_bank on fd_pcdactive_bankid=fd_bank_id
								left join tb_saler on fd_pcdactive_salerid=fd_saler_id
								left join tb_paycard on fd_pcdactive_paycardid=fd_paycard_id
								left join tb_author on fd_pcdactive_authorid=fd_author_id
								";
	var $browse_edit   = "pcdactive.php?listid=" ;
    var $browse_editname   = "�鿴" ;
	//var $browse_new   = "pcdactive.php" ;
	

	 var $browse_field = array("fd_pcdactive_id","fd_author_truename","fd_paycard_no","fd_paycard_type","fd_pcdactive_activedate","fd_saler_truename","fd_bank_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ˢ����key" , "fd_paycard_no","TXT"),
				"1" => array("����ʱ��" , "fd_pcdactive_activedate","TXT"),
				"2" => array("����" , "fd_saler_truename","TXT"),
				"3" => array("������" , "fd_author_truename","TXT"),
				); 
}

class  fd_pcdactive_id  extends browsefield {
        var $bwfd_fdname = "fd_pcdactive_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class  fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "���������";	// �ֶα���
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_paycard_no  extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ����key";	// �ֶα���
			
}
class fd_paycard_type  extends browsefield {
        var $bwfd_fdname = "fd_paycard_type";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ��������";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
			switch($this->bwfd_value)
			{
				case "creditcard";
				$this->bwfd_show="���ÿ�";
				break;
				case "bankcard";
				$this->bwfd_show="���";
				break;
			}
			
		      return $this->bwfd_show ;
  	    }
			
}
class fd_saler_truename  extends browsefield {
        var $bwfd_fdname = "fd_saler_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class  fd_pcdactive_activedate  extends browsefield {
        var $bwfd_fdname = "fd_pcdactive_activedate";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_pcdactive_id") 
   			    );
   var $bwlk_prgname = "../saler/bangding.php?bangdingtype=saler&url=activepaycard&thismenucode=2n001&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�����ж�����</span>";  
   
  
 
} */
if(empty($order)){
	$order = "fd_pcdactive_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_paycardactivelist_b_bu = new tb_paycardactivelist_b ;
$tb_paycardactivelist_b_bu->browse_skin = $loginskin ;
$tb_paycardactivelist_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_paycardactivelist_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_paycardactivelist_b_bu->browse_editqx = 1;  // �༭Ȩ��
//$tb_paycardactivelist_b_bu->browse_link  = array("lk_view0");
$tb_paycardactivelist_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
