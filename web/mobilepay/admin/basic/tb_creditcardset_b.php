<?
$thismenucode = "2n103";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bankset_b extends browse 
{
	 var $prgnoware    = array("ˢ��������","���ÿ���������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_creditcset_id";
	 
	 var $browse_queryselect = "select * from tb_creditcardset
								left join tb_bank on fd_creditcset_bankid=fd_bank_id
								left join tb_arrive on fd_creditcset_arrivetime=fd_arrive_id
								";
	 var $browse_edit   = "creditcardset.php?listid=" ;
	  var $browse_new   = "creditcardset.php" ;
	

	 var $browse_field = array("fd_creditcset_id","fd_bank_name","fd_creditcset_fee","fd_arrive_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_creditcset_id","TXT"),
				"1" => array("��������" , "fd_bank_name","TXT"),
				); 
}

class  fd_creditcset_id  extends browsefield {
        var $bwfd_fdname = "fd_creditcset_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_creditcset_fee  extends browsefield {
        var $bwfd_fdname = "fd_creditcset_fee";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        
			$this->bwfd_show=$this->bwfd_value."%";
		      return $this->bwfd_show ;
  	    }
}
class fd_arrive_name  extends browsefield {
        var $bwfd_fdname = "fd_arrive_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ȼָ�ʱ��";	// �ֶα���
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_creditcset_id") 
   			    );
   var $bwlk_prgname = "creditcardset.php?listid=";
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

$tb_bankset_b_bu = new tb_bankset_b ;
$tb_bankset_b_bu->browse_skin = $loginskin ;
$tb_bankset_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_bankset_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_bankset_b_bu->browse_editqx = 1;  // �༭Ȩ��
//$tb_bankset_b_bu->browse_link  = array("lk_view0");
$tb_bankset_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
