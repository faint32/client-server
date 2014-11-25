<?
$thismenucode = "2n305";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("����ȯ����","����ȯ�쳣");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_couponsale_id";
	 

	 var $browse_queryselect = "select *,fd_couponsale_id as tdcount 
	                            from tb_couponsale
	                            left join tb_paycard on fd_couponsale_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_couponsale_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_couponsale_sdcrid
	                           ";
	 var $browse_edit = "couponsale_error.php?listid=" ;
   var $browse_editname = "�鿴";
   
   var $browse_defaultorder = " fd_couponsale_datetime desc";	 
   
	 var $browse_field = array("tdcount","fd_author_truename","fd_author_username","fd_author_idcard","fd_couponsale_paymoney","fd_couponsale_payfee","fd_couponsale_money","fd_couponsale_sdcrpayfeemoney","fd_couponsale_payfeedirct","fd_sdcr_name","fd_couponsale_datetime","fd_paycard_no","fd_couponsale_creditcardno","fd_couponsale_creditcardbank","fd_couponsale_bkordernumber","fd_couponsale_bkntno","fd_couponsale_isagentpay","fd_couponsale_agentdate");
	 
	 var $browse_hj = array("fd_couponsale_paymoney","fd_couponsale_payfee","fd_couponsale_money","fd_couponsale_sdcrpayfeemoney");
	 							
 	 var $browse_find = array(		// ��ѯ����
				"1" => array("���Ä����", "fd_couponsale_money","TXT"),
				"2" => array("ˢ������", "fd_paycard_key","TXT"),
				"3" => array("�û��˺�", "fd_author_username","TXT"),
				"4" => array("�û�����", "fd_author_truename","TXT"),
				"5" => array("����֤��", "fd_author_idcard","TXT"),
				"6" => array("��������", "fd_couponsale_datetime","TXT"),
				"7" => array("������", "fd_couponsale_czman","TXT"),
				"8" => array("��������", "fd_couponsale_czdatetime","TXT"),
				"9" => array("���н�����ˮ��", "fd_couponsale_bkntno","TXT")
				
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



class fd_couponsale_bkntno extends browsefield {
        var $bwfd_fdname = "fd_couponsale_bkntno";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������������";	// �ֶα���
}

class fd_couponsale_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_couponsale_bkordernumber";	// ���ݿ����ֶ�����
        var $bwfd_title = "����������ˮ��";	// �ֶα���
}

class fd_couponsale_paymoney extends browsefield {
        var $bwfd_fdname = "fd_couponsale_paymoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ױ���";	// �ֶα���
        
        function makeshow(){	    	               	       
        	$this->bwfd_align  = "right";    
		      return $this->bwfd_value ;
		    }     
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̻�";	// �ֶα���
}

class fd_couponsale_payfee extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ȡ�ն�<br>�̻�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_couponsale_money extends browsefield {
        var $bwfd_fdname = "fd_couponsale_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܶ�";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_couponsale_sdcrpayfeemoney  extends browsefield {
     var $bwfd_fdname = "fd_couponsale_sdcrpayfeemoney";	// ���ݿ����ֶ�����
     var $bwfd_title = "������ȡ<br>������";	// �ֶα���
             
		 function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		 }  
}

class fd_couponsale_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payfeedirct";	// ���ݿ����ֶ�����
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


class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ������";	// �ֶα���
}

class fd_couponsale_creditcardno extends browsefield {
        var $bwfd_fdname = "fd_couponsale_creditcardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "���п���";	// �ֶα���
}

class fd_couponsale_creditcardbank extends browsefield {
        var $bwfd_fdname = "fd_couponsale_creditcardbank";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ������";	// �ֶα���
}



class fd_author_username extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û��˺�";	// �ֶα���
}
class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û�����";	// �ֶα���
}
class fd_author_idcard extends browsefield {
        var $bwfd_fdname = "fd_author_idcard";	// ���ݿ����ֶ�����
        var $bwfd_title = "����֤��";	// �ֶα���
        var $bwfd_format = "idcard";
}


class fd_couponsale_payrq extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payrq";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        	  global $loginorganid; 
			     //$this->var = explode(",",$this->bwfd_value);
			     $transStatus = $this->bwfd_value;
			
			    if($transStatus == "01") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>������</span>";    		
        	 	}else if($transStatus == "00"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>�������</span>";
        		 }
        		 else if($transStatus == "03"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>����ȡ��</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}




class fd_couponsale_datetime extends browsefield {
        var $bwfd_fdname = "fd_couponsale_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_couponsale_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_couponsale_isagentpay";	// ���ݿ����ֶ�����
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

class fd_couponsale_agentdate extends browsefield {
        var $bwfd_fdname = "fd_couponsale_agentdate";	// ���ݿ����ֶ�����
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
$tb_feedback_b_bu->browse_querywhere = "fd_couponsale_payrq='0'";

$db = new DB_test ;
global $fd_couponsale_paymoney,$fd_couponsale_payfee,$fd_couponsale_money,$fd_couponsale_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_couponsale_paymoney,fd_couponsale_payfee,fd_couponsale_money,fd_couponsale_sdcrpayfeemoney
          from tb_couponsale ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_couponsale_paymoney += $db->f(fd_couponsale_paymoney);   
     $fd_couponsale_payfee += $db->f(fd_couponsale_payfee);   
     $fd_couponsale_money += $db->f(fd_couponsale_money);   
     $fd_couponsale_sdcrpayfeemoney += $db->f(fd_couponsale_sdcrpayfeemoney);   
}

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
