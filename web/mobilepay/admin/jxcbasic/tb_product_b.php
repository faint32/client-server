<?
$thismenucode = "2k112";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_product_b extends browse 
{
	 var $prgnoware    = array("��������","��Ʒ����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_product_id";
	 
	 var $browse_queryselect = "select * from tb_product 								 
								 left join  tb_producttype on fd_producttype_id=fd_product_producttypeid
								";
	 var $browse_delsql = "delete from tb_product where fd_product_id = '%s'" ;
	 var $browse_edit = "product.php?listid=" ;
     var $browse_new  = "product.php";

	 var $browse_field = array("fd_product_no","fd_product_name","fd_producttype_name","fd_product_productscope",
	 							"fd_product_suppno","fd_product_suppname");
 	 var $browse_find = array(		// ��ѯ����
 	      "0" => array("���"      , "fd_product_no"        ,"TXT") ,
				"1" => array("��Ʒ����", "fd_producttype_name"      ,"TXT") ,
				"3" => array("��Ӧ�̱��"    , "fd_product_suppno"   ,"TXT") ,
				"4" => array("��Ӧ������"      , "fd_product_suppname"  ,"TXT") ,
				"5" => array("��Ʒ����"      , "fd_product_name"  ,"TXT") ,
				);
}

class fd_product_no extends browsefield {
        var $bwfd_fdname = "fd_product_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_product_name extends browsefield {
        var $bwfd_fdname = "fd_product_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ʒ����";	// �ֶα���
}

class fd_producttype_name extends browsefield {
        var $bwfd_fdname = "fd_producttype_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ʒ����";	// �ֶα���
}

class fd_product_productscope extends browsefield {
        var $bwfd_fdname = "fd_product_productscope";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ʒ���÷�Χ";	// �ֶα���
		
		        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "���ÿ�";
        		     break;       		
        		case "bankcard":
        		    $this->bwfd_show = "���";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}

class fd_product_suppno extends browsefield {
        var $bwfd_fdname = "fd_product_suppno";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ�̱��";	// �ֶα���
}

class fd_product_suppname extends browsefield {
        var $bwfd_fdname = "fd_product_suppname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ������";	// �ֶα���
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_product_no";
}

$tb_product_b_bu = new tb_product_b ;
$tb_product_b_bu->browse_skin = $loginskin ;
$tb_product_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_product_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_product_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_product_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
