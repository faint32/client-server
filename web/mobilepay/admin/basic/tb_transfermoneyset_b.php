<?
$thismenucode = "2n104";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_transfermoneyset_b extends browse 
{
	 var $prgnoware    = array("ˢ��������","ת�˻������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_transfermoneyset_id";
	 
	 var $browse_queryselect = "select * from tb_transfermoneyset
								left join tb_bank on fd_transfermoneyset_bankid=fd_bank_id
								left join tb_arrive on fd_transfermoneyset_arrivemode=fd_arrive_id
								";
	 var $browse_edit   = "transfermoneyset.php?listid=" ;
	  var $browse_new   = "transfermoneyset.php" ;
	

	 var $browse_field = array("fd_transfermoneyset_id","fd_bank_name","fd_transfermoneyset_fee","fd_arrive_name","fd_transfermoneyset_arrivetime",);
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_transfermoneyset_id","TXT"),
				"1" => array("��������" , "fd_bankset_name","TXT"),
				); 
}

class  fd_transfermoneyset_id  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_transfermoneyset_fee  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_fee";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
		
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        
			$this->bwfd_show=$this->bwfd_value."%";
		      return $this->bwfd_show ;
  	    }
}
class fd_arrive_name  extends browsefield {
        var $bwfd_fdname = "fd_arrive_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ģʽ";	// �ֶα���
}

class fd_transfermoneyset_arrivetime  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_arrivetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʱ��";	// �ֶα���
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_transfermoneyset_id") 
   			    );
   var $bwlk_prgname = "transfermoneyset.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�鿴</span>";  
} */
if(empty($order)){
	$order = "fd_bankset_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_transfermoneyset_b_bu = new tb_transfermoneyset_b ;
$tb_transfermoneyset_b_bu->browse_skin = $loginskin ;
$tb_transfermoneyset_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_transfermoneyset_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_transfermoneyset_b_bu->browse_editqx = 1;  // �༭Ȩ��
//$tb_transfermoneyset_b_bu->browse_link  = array("lk_view0");
$tb_transfermoneyset_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
