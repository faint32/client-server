<?
$thismenucode = "2n503";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("���ÿ��������","���ÿ�����֧���쳣");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_ccglist_id";
	 

	 var $browse_queryselect = "select * ,fd_ccglist_id as tdcount 
	                            from tb_creditcardglist
	                            left join tb_paycard on fd_ccglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_ccglist_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_ccglist_sdcrid
	                            ";
	 var $browse_edit = "creditcard_sp.php?type=error&listid=" ;
   var $browse_editname = "�鿴";
	 var $browse_field = array("tdcount","fd_author_truename","fd_ccglist_shoucardno","fd_ccglist_fucardno","fd_ccglist_paymoney","fd_ccglist_payfee","fd_ccglist_money","fd_ccglist_sdcrpayfeemoney","fd_ccglist_payfeedirct","fd_ccglist_sdcragentfeemoney","fd_ccglist_arrivedate","fd_sdcr_name","fd_ccglist_paydate","fd_paycard_no","fd_ccglist_memo","fd_ccglist_bkordernumber","fd_ccglist_bkntno","fd_ccglist_isagentpay","fd_ccglist_agentdate");
 	 
 	 var $browse_hj = array("fd_ccglist_paymoney","fd_ccglist_payfee","fd_ccglist_money","fd_ccglist_sdcrpayfeemoney","fd_ccglist_sdcragentfeemoney");
 	 
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ˢ�������", "fd_paycard_no","TXT"),
				"1" => array("�û���", "fd_author_truename","TXT"),
				"2" => array("��������", "fd_ccglist_paydate","TXT"),
				"3" => array("���н�����ˮ��", "fd_ccglist_bkntno","TXT"),	
				"4" => array("�����", "fd_ccglist_fucardno","TXT"),	
				"5" => array("�����", "fd_ccglist_shoucardno","TXT"),
				
				);
	 
}

$tdcount  = 0;

class tdcount  extends browsefield {
        var $bwfd_fdname = "tdcount";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
        
        function makeshow(){	
        	global $tdcount;   
        	$tdcount++;        	
        	       	                  
          $showvalue = $tdcount;       	       
          	
        	$this->bwfd_show = $showvalue;       	        	

		      return $this->bwfd_show ;
		    }
}

class fd_ccglist_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_ccglist_bkordernumber";	// ���ݿ����ֶ�����
        var $bwfd_title = "����������ˮ��";	// �ֶα���
}

class fd_ccglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_bkntno";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������������";	// �ֶα���
}

class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ�����豸��";	// �ֶα���
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���
";	// �ֶα���
}

class fd_ccglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_paydate";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������
";	// �ֶα���
}
class fd_ccglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_shoucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����

";	// �ֶα���
}
class fd_ccglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_fucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����
";	// �ֶα���
}

class fd_ccglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_paymoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ױ���";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̻�";	// �ֶα���
}   


class fd_ccglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_ccglist_payfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ȡ�ն�<br>�̻�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_money extends browsefield {
        var $bwfd_fdname = "fd_ccglist_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܶ�";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_sdcrpayfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȡ<br>������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_sdcragentfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʽ�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_arrivedate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_arrivedate";	// ���ݿ����ֶ�����
        var $bwfd_title = "Ԥ�ƴ�������";	// �ֶα���
        
        function makeshow(){	
        	  $arrivedate = $this->bwfd_value;        	         	  
        	  $arrivedate = substr($arrivedate,0,10);
        	     	  
        	  $this->bwfd_show = $arrivedate;        	     	               	       
		        return $this->bwfd_show ;
		    }  
}

class fd_ccglist_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_ccglist_payfeedirct";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ն��̻���<br>�����Ƿ���";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "s") {
        		$this->bwfd_show = "��";    		
        	}else if($transStatus == "f"){
        	  $this->bwfd_show = "<span style='color:#0000ff'>��</span>";
        	}
        		   	     
		      return $this->bwfd_show ;
  	  }
}




class fd_ccglist_memo extends browsefield {
        var $bwfd_fdname = "fd_ccglist_memo";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ժҪ";	// �ֶα���
}

class fd_ccglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_ccglist_isagentpay";	// ���ݿ����ֶ�����
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

class fd_ccglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_agentdate";	// ���ݿ����ֶ�����
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
$tb_feedback_b_bu->browse_querywhere = "fd_ccglist_payrq='03'";


$db = new DB_test ;
global $fd_ccglist_paymoney,$fd_ccglist_payfee,$fd_ccglist_money,$fd_ccglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_ccglist_paymoney,fd_ccglist_payfee,fd_ccglist_money,fd_ccglist_sdcrpayfeemoney,fd_ccglist_sdcragentfeemoney
          from tb_creditcardglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_ccglist_paymoney += $db->f(fd_ccglist_paymoney);   
     $fd_ccglist_payfee += $db->f(fd_ccglist_payfee);   
     $fd_ccglist_money += $db->f(fd_ccglist_money);   
     $fd_ccglist_sdcrpayfeemoney += $db->f(fd_ccglist_sdcrpayfeemoney);   
     $fd_ccglist_sdcragentfeemoney += $db->f(fd_ccglist_sdcragentfeemoney);  
}

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

