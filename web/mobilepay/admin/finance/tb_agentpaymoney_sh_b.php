<?
$thismenucode = "2n604";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_paymoneylist_b extends browse {
	var $prgnoware = array ("�ʽ��������", "������˶�" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_pymylt_id";
	
	var $browse_queryselect = "select * from tb_paymoneylist";
	
	var $browse_edit   = "paymoneylist_hd.php?listid=";

	
	var $browse_state = array("fd_pymylt_state"); 
	
	var $browse_defaultorder = " CASE WHEN fd_pymylt_state = '3'
                                THEN 1                                  
                                WHEN fd_pymylt_state = '0'
                                THEN 3 
                                WHEN fd_pymylt_state = '1'
                                THEN 2
                                WHEN fd_pymylt_state = '2'
                                THEN 4                                 
                                END,fd_pymylt_state desc
                              ";	  
	
	var $browse_editname = "�˶�";
	
	function makeedit($key) {	// ���ɱ༭����
  	  $returnval = "" ;
   	  switch($this->arr_spilthfield[0]){  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
   	  	case "3":
   	  	  if(!empty($this->browse_edit)){
  	        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")><font color='#0000ff'>".$this->browse_editname."</font></a>" ;
  	      }
   	  	  break;
   	  	default:
   	  	  $returnval = "<a href=javascript:linkurl(\"paymoneylist_view.php?state=4&listid=".$key."\")>�鿴</a>" ;;	
   	  	  break;
   	  }
  	  return $returnval;
   }
		
	var $browse_field = array ("fd_pymylt_no","fd_pymylt_state","fd_pymylt_paytype","fd_pymylt_dealwithman", "fd_pymylt_date", "fd_pymylt_fkdate", "fd_pymylt_money");
	var $browse_find = array (// ��ѯ����
                      "0" => array ("���ݱ��"    , "fd_pymylt_no"            , "TXT" ), 
                      "1" => array ("������"      , "fd_pymylt_dealwithman"   , "TXT" ), 
                      "2" => array ("��������"    , "fd_pymylt_date"          , "TXT" ), 
                      "3" => array ("��������"    , "fd_pymylt_fkdate"        , "TXT" ), 
                      "4" => array ("������"    , "fd_pymylt_money"         , "TXT" ), 
                      );
}

class fd_pymylt_no extends browsefield {
	var $bwfd_fdname = "fd_pymylt_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "���ݱ��"; // �ֶα���
}

class fd_pymylt_state extends browsefield {
	var $bwfd_fdname = "fd_pymylt_state"; // ���ݿ����ֶ�����
	var $bwfd_title = "״̬"; // �ֶα���
	
	function makeshow() {	// ��ֵתΪ��ʾֵ
  	switch($this->bwfd_value){
  		case "0":
  		  $this->bwfd_show = "����������";
  		  break;
  		case "1":
  		  $this->bwfd_show = "����������";
  		  break;
  		case "2":
  		  $this->bwfd_show = "���������";
  		  break;
  		case "3":
  		  $this->bwfd_show = "������˶�";
  		  break;
  	}
	  return $this->bwfd_show;
  }
	
}
class fd_pymylt_paytype extends browsefield {
	var $bwfd_fdname = "fd_pymylt_paytype"; // ���ݿ����ֶ�����
	var $bwfd_title = "����������"; // �ֶα���
	
	function makeshow() {	// ��ֵתΪ��ʾֵ
  	switch($this->bwfd_value){
  		case "coupon":
  		  $this->bwfd_show = "�������ȯ";
  		  break;
  		case "creditcard":
  		  $this->bwfd_show = "���ÿ�����";
  		  break;
  		case "recharge":
  		  $this->bwfd_show = "��ֵ";
  		  break;
  		case "repay":
  		  $this->bwfd_show = "������";
  		  break;
		case "order":
  		  $this->bwfd_show = "��������";
  		  break;
  		case "tfmg":
  		  $this->bwfd_show = "ת�˻��";
  		  break;
		default:
			$this->bwfd_show = "����ҵ��";
		break;
  	}
	  return $this->bwfd_show;
  }
	
}

class fd_pymylt_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_pymylt_dealwithman"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}

class fd_pymylt_date extends browsefield {
	var $bwfd_fdname = "fd_pymylt_date"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}

class fd_pymylt_fkdate extends browsefield {
	var $bwfd_fdname = "fd_pymylt_fkdate"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
	
}

class fd_pymylt_money extends browsefield {
	var $bwfd_fdname = "fd_pymylt_money"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
	var $bwfd_align = "right";
}


if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

if (empty ( $order )) {
	$order = "fd_pymylt_date";
	$upordown = "desc";
}

$tb_paymoneylist_b_bu = new tb_paymoneylist_b ( );
$tb_paymoneylist_b_bu->browse_skin = $loginskin;
$tb_paymoneylist_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_paymoneylist_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_paymoneylist_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_paymoneylist_b_bu->browse_querywhere = "  fd_pymylt_state != '9'";

$tb_paymoneylist_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
