<?
$thismenucode = "2k303";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../third_api/readshopname.php");
class tb_author_sp_b extends browse 
{
	 var $prgnoware    = array("�̻�����","���δͨ���̻�");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_author_id";
	 
	 var $browse_queryselect = "select * from tb_author 
	 							left join tb_authorindustry on fd_auindustry_id = fd_author_auindustryid
								left join tb_sendcenter on fd_sdcr_id = fd_author_sdcrid ";
	
	 var $browse_edit   = "author_no.php?listid=" ;
	 
	

	 var $browse_field = array("fd_author_id","fd_author_username","fd_author_truename","fd_author_mobile","fd_author_email","fd_author_regtime","fd_author_state","fd_author_isstop","fd_author_shopid","fd_sdcr_name","fd_auindustry_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_author_listid","TXT"),
				"1" => array("�û���" , "fd_author_username","TXT"),
				"2" => array("��ʵ����" , "fd_author_truename","TXT"),
				"3" => array("�ֻ�����" , "fd_author_mobile","TXT"),
				"4" => array("����֤����" , "fd_author_idcard","TXT")
				); 
}

class  fd_author_id  extends browsefield {
        var $bwfd_fdname = "fd_author_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_author_username  extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}
class fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ʵ��";	// �ֶα���
}
class fd_author_mobile  extends browsefield {
        var $bwfd_fdname = "fd_author_mobile";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ֻ�����";	// �ֶα���
}
class fd_author_email  extends browsefield {
        var $bwfd_fdname = "fd_author_email";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_author_regtime extends browsefield {
        var $bwfd_fdname = "fd_author_regtime";	// ���ݿ����ֶ�����
        var $bwfd_title = "ע��ʱ��";	// �ֶα���
}
class fd_author_state extends browsefield {
        var $bwfd_fdname = "fd_author_state";	// ���ݿ����ֶ�����
        var $bwfd_title = "״̬";	// �ֶα���
		        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "�����";
        		     break;       		
        		case "9":
        		    $this->bwfd_show = "���ͨ��";
        		     break;
        		case "-1":
        		    $this->bwfd_show = "��ע��";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_isstop extends browsefield {
        var $bwfd_fdname = "fd_author_isstop";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ񶳽�";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_shopid extends browsefield {
        var $bwfd_fdname = "fd_author_shopid";	// ���ݿ����ֶ�����
        var $bwfd_title = "�̻���";	// �ֶα���
 function makeshow() { // ��ֵתΪ��ʾֵ
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getauthorshop ($this->bwfd_value);
		return $this->bwfd_show;
		}
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܲ�";	// �ֶα���
       
}
class fd_auindustry_name extends browsefield {
        var $bwfd_fdname = "fd_auindustry_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�̻�������ҵ";	// �ֶα���
       
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "authorsp.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>���</span>";  
   
  
 
} */

if(empty($order)){
	$order = "fd_author_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_sp_b_bu = new tb_author_sp_b ;
$tb_author_sp_b_bu->browse_skin = $loginskin ;
$tb_author_sp_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = 1;  // �༭Ȩ��
$tb_author_sp_b_bu->browse_querywhere = "fd_author_isstop = 1";
/* $tb_author_sp_b_bu->browse_link  = array("lk_view0"); */
$tb_author_sp_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>