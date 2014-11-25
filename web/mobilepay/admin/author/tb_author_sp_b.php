<?
$thismenucode = "2k301";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../third_api/readshopname.php");
class tb_author_sp_b extends browse {
	//var $prgnoware = array ("��Ա", "δ��˻�Ա" );
	var $prgnoware = array ("�̻�����", "������̻�" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_author_id";
	
	var $browse_queryselect = "select * from tb_author
	 							left join tb_authorindustry on fd_auindustry_id = fd_author_auindustryid
	 						   left join tb_authortype on fd_authortype_id = fd_author_authortypeid 
								left join tb_sendcenter on fd_sdcr_id = fd_author_sdcrid ";
	
	var $browse_edit = "author_sp.php?listid=";
	var $browse_editname = "���";
		 var $browse_delsql = "delete from tb_author where fd_author_id = '%s'" ;
	var $browse_field = array ("fd_author_id","fd_author_username", "fd_author_truename", "fd_author_regtime","fd_author_shopid","fd_author_shoucardman","fd_author_shoucardphone","fd_author_shoucardbank","fd_author_shoucardno","fd_author_datetime",  "fd_author_state", "fd_author_isstop","fd_author_slotpayfsetid","fd_author_slotscdmsetid","fd_author_bkcardpayfsetid","fd_author_bkcardscdmsetid", "fd_authortype_name",  "fd_author_mobile","fd_sdcr_name","fd_auindustry_name" );
	var $browse_find = array (// ��ѯ����
				"0" => array("���" , "fd_author_id","TXT"),
				"1" => array("�û���" , "fd_author_username","TXT"),
				"2" => array("��ʵ����" , "fd_author_truename","TXT"),
				"3" => array("�ֻ�����" , "fd_author_mobile","TXT"),
				"4" => array("���֤����" , "fd_author_idcard","TXT"),
				"5" => array("ʱ��" , "fd_author_datetime","TXT")
				
				);
	function dodelete(){ //  ɾ������
		$db = new DB_test();
		for($i=0; $i<count($this->browse_check); $i++)
		{
			$query = "update  tb_paycard set fd_paycard_authorid='' where fd_paycard_authorid=".$this->browse_check[$i]."";
			$db->query($query);
					
			$query = sprintf($this->browse_delsql,$this->browse_check[$i]);
			$this->db->query($query); //ɾ������ļ�¼
		}
	}		
}

class fd_author_shoucardphone extends browsefield {
	var $bwfd_fdname = "fd_author_shoucardphone"; // ���ݿ����ֶ�����
	var $bwfd_title = "�տ��˵绰"; // �ֶα���
}

class fd_author_shoucardbank extends browsefield {
	var $bwfd_fdname = "fd_author_shoucardbank"; // ���ݿ����ֶ�����
	var $bwfd_title = "�տ�����"; // �ֶα���
}

class fd_author_id extends browsefield {
	var $bwfd_fdname = "fd_author_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class  fd_author_slotpayfsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_slotpayfsetid";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ÿ������ײ�";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	
			$this->bwfd_show=getauthorset("tb_payfeeset","payfset",$this->bwfd_value);	
        	      		     
		      return $this->bwfd_show ;
  	    } 
}
class  fd_author_slotscdmsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_slotscdmsetid";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ÿ�����ײ�";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	$this->bwfd_show=getauthorset("tb_slotcardmoneyset","scdmset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    } 
}
class  fd_author_bkcardpayfsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_bkcardpayfsetid";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ǿ������ײ�";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	$this->bwfd_show=getauthorset("tb_payfeeset","payfset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    }
}
class  fd_author_bkcardscdmsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_bkcardscdmsetid";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ǿ�����ײ�";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	$this->bwfd_show=getauthorset("tb_slotcardmoneyset","scdmset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    } 
}
class fd_author_username extends browsefield {
	var $bwfd_fdname = "fd_author_username"; // ���ݿ����ֶ�����
	var $bwfd_title = "�û���"; // �ֶα���
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // ���ݿ����ֶ�����
	var $bwfd_title = "��ʵ��"; // �ֶα���
}
class fd_author_mobile extends browsefield {
	var $bwfd_fdname = "fd_author_mobile"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ֻ�����"; // �ֶα���
}
class fd_author_email extends browsefield {
	var $bwfd_fdname = "fd_author_email"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_authortype_name extends browsefield {
        var $bwfd_fdname = "fd_authortype_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�̻�����";	// �ֶα���
}

class fd_author_datetime extends browsefield {
	var $bwfd_fdname = "fd_author_datetime"; // ���ݿ����ֶ�����
	var $bwfd_title = "�޸�ʱ��"; // �ֶα���
}

class fd_author_regtime extends browsefield {
	var $bwfd_fdname = "fd_author_regtime"; // ���ݿ����ֶ�����
	var $bwfd_title = "ע��ʱ��"; // �ֶα���
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

class fd_author_shoucardman extends browsefield {
        var $bwfd_fdname = "fd_author_shoucardman";	// ���ݿ����ֶ�����
        var $bwfd_title = "�տ���";	// �ֶα���
       
}
class fd_author_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_author_shoucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�տ��";	// �ֶα���
       
}
class lk_view0 extends browselink {
	var $bwlk_fdname = array (// �������ݿ����ֶ�����
"0" => array ("fd_author_id" ) );
	var $bwlk_prgname = "author_sp.php?listid=";
	var $bwlk_title = "<span style='color:#0000ff'>���</span>";

}
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorpaycard.php?type=author_sp&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�鿴ˢ����</span>";  
   
} 

class lk_view2 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorbank.php?type=author_sp&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�鿴���ÿ�</span>";  
   
} 
if(empty($order)){
	//$order = "fd_author_id";
	$order = "fd_author_regtime";
	$upordown = "desc";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = 100;
}

$tb_author_sp_b_bu = new tb_author_sp_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_author_sp_b_bu->browse_querywhere = "(fd_author_state = 0  or  fd_author_state is Null )  and fd_author_isstop = 0";
$tb_author_sp_b_bu->browse_link = array ("lk_view1","lk_view2" );
$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );

 function getauthorset($tabname,$filename,$id)//��ȡ�̼��ײ�
{
	$db = new DB_test;
	$query = "select fd_".$filename."_name as name from  $tabname
	where fd_".$filename."_id='$id'";
	
	$db->query($query);
	$arr_data=$db->getFiledData();
	return $arr_data['0']['name'];
}
?>
