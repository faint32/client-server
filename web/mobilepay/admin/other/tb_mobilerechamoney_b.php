<?
$thismenucode = "2n801";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_recham_b extends browse 
{
	 var $prgnoware    = array("���ѳ�ֵ","���ѡ��");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_recham_id";
	 
	 var $browse_queryselect = "select * from tb_mobilerechamoney ";
	
	 var $browse_edit   = "mobilerechamoney.php?listid=" ;
	  var $browse_new   = "mobilerechamoney.php" ;
	

	 var $browse_field = array("fd_recham_id","fd_recham_money","fd_recham_paymoney","fd_recham_costmoney","fd_recham_isdefault");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_recham_id","TXT"),
				"1" => array("��ֵ���" , "fd_recham_money","TXT"),
				); 
}

class  fd_recham_id  extends browsefield {
        var $bwfd_fdname = "fd_recham_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_recham_money  extends browsefield {
        var $bwfd_fdname = "fd_recham_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ֵ���";	// �ֶα���
}
class fd_recham_paymoney  extends browsefield {
        var $bwfd_fdname = "fd_recham_paymoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "ʵ��֧��";	// �ֶα���
}
class fd_recham_costmoney  extends browsefield {
    var $bwfd_fdname = "fd_recham_costmoney";	// ���ݿ����ֶ�����
    var $bwfd_title = "�ɱ����";	// �ֶα���
}

class fd_recham_isdefault  extends browsefield {
        var $bwfd_fdname = "fd_recham_isdefault";	// ���ݿ����ֶ�����
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
	$order = "fd_recham_id";
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
