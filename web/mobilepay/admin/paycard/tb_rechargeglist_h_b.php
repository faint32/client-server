<?
$thismenucode = "2n502";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	  var $prgnoware    = array("��ֵ����","��ֵ֧����ʷ");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_rechargelist_id";
	 

	 var $browse_queryselect = "select * 
	                            from tb_rechargeglist
	                            left join tb_paycard on fd_rechargelist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_rechargelist_authorid
	                            ";
	 var $browse_edit = "rechargeglist_sp.php?type=check&listid=" ;
       var $browse_editname = "�鿴";
	 var $browse_field = array("fd_rechargelist_no","fd_author_truename","fd_paycard_key","fd_rechargelist_bankcardno","fd_rechargelist_bankname","fd_rechargelist_bkntno","fd_rechargelist_money","fd_rechargelist_banktype","fd_rechargeglist_isagentpay","fd_rechargeglist_agentdate","fd_rechargelist_datetime");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ˢ�������", "fd_paycard_key","TXT"),
				"1" => array("�û���", "fd_author_truename","TXT"),
				"2" => array("��ֵ����", "fd_rechargelist_datetime","TXT"),
				
				);
	 
}

class fd_rechargelist_no extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ˮ��
";	// �ֶα���
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}

class fd_paycard_key extends browsefield {
        var $bwfd_fdname = "fd_paycard_key";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ�����豸��";	// �ֶα���
}
class fd_rechargelist_bankcardno extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bankcardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "���п���";	// �ֶα���
}
class fd_rechargelist_bankname extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bankname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_rechargelist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bkntno";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ˮ��";	// �ֶα���
}
class fd_rechargelist_money extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ֵ���";	// �ֶα���
}
class fd_rechargelist_banktype extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_banktype";	// ���ݿ����ֶ�����
        var $bwfd_title = "���п�����";	// �ֶα���
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "���ÿ�";
        		     break;       		
        		case "depositcard":
        		    $this->bwfd_show = "���";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}

class fd_rechargelist_datetime extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ֵ����";	// �ֶα���
}

class fd_rechargeglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_rechargeglist_isagentpay";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ��Ѵ���";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
					    
			if($transStatus == "0") {
        		$this->bwfd_show = "��";    		
        	}else if($transStatus == "1"){
        	  $this->bwfd_show = "<span style='color:#0000ff'>��</span>";
        	}
        		   	     
		      return $this->bwfd_show ;
  	  }
}

class fd_rechargeglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_rechargeglist_agentdate";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ѵ�������";	// �ֶα���
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;  
			  if($arrivetim==""){
				  $arrivetime = "-";
			  }else{
        	  	$arrivetime = substr($arrivetime,0,10);
			  }
        	     	  
        	  $this->bwfd_show = $arrivetime;        	     	               	       
		        return $this->bwfd_show ;
		    }  
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
//$tb_feedback_b_bu->browse_querywhere = "fd_ccglist_state = '9' and fd_ccglist_payrq='1'";
$tb_feedback_b_bu->browse_querywhere = "fd_rechargelist_payrq='00' and fd_rechargelist_state='9'";
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

