<?
$thismenucode = "2n901";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_recham_b extends browse 
{
	 var $prgnoware    = array("qq��ֵ","���ѡ��");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_qqrecham_id";
	 
	 var $browse_queryselect = "select * from tb_qqrechamoney ";
	
	 var $browse_edit   = "qqrechamoney.php?listid=" ;
	  var $browse_new   = "qqrechamoney.php" ;
	

	 var $browse_field = array("fd_qqrecham_id","fd_qqrecham_money","fd_qqrecham_paymoney","fd_qqrecham_costmoney","fd_qqrecham_isdefault");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_qqrecham_id","TXT"),
				"1" => array("��ֵ���" , "fd_qqrecham_money","TXT"),
				); 
}

class  fd_qqrecham_id  extends browsefield {
        var $bwfd_fdname = "fd_qqrecham_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_qqrecham_money  extends browsefield {
        var $bwfd_fdname = "fd_qqrecham_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ֵ���";	// �ֶα���
}
class fd_qqrecham_paymoney  extends browsefield {
        var $bwfd_fdname = "fd_qqrecham_paymoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "ʵ��֧��";	// �ֶα���
}
class fd_qqrecham_costmoney  extends browsefield {
    var $bwfd_fdname = "fd_qqrecham_costmoney";	// ���ݿ����ֶ�����
    var $bwfd_title = "�ɱ����";	// �ֶα���
}

class fd_qqrecham_isdefault  extends browsefield {
        var $bwfd_fdname = "fd_qqrecham_isdefault";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ�Ĭ����ʾ";	// �ֶα���
        function makeshow() {	// ��ֵתΪ��ʾֵ
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "��";
                break;
            case "1":
                $this->bwfd_show = "��";
                break;
            default:
                $this->bwfd_show = "��";
                break;
        }
        return $this->bwfd_show ;
    }

}

if(empty($order)){
	$order = " fd_qqrecham_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_recham_b_bu = new tb_recham_b ;
$tb_recham_b_bu->browse_skin = $loginskin ;
$tb_recham_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_recham_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_recham_b_bu->browse_editqx = 1;  // �༭Ȩ��

$tb_recham_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
