<?
$thismenucode = "2n201";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("���������","���������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_repmglist_id";
	 

	 var $browse_queryselect = "select * ,fd_repmglist_id as tdcount
	                            from tb_repaymoneyglist
	                            left join tb_paycard on fd_repmglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_repmglist_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_repmglist_sdcrid
	                           ";
	 var $browse_edit = "repaymoney_sp.php?listid=" ;
	 
   var $browse_editname = "����";
    
   var $browse_state = array("fd_repmglist_payrq");
   var $browse_defaultorder = " fd_repmglist_paydate desc
                             ";	  
   
   

   
	 function makeedit($key) {	// ���ɱ༭����
	   $returnval = "" ;
	   switch($this->arr_spilthfield[0]){  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
	  	
	 	case "00":
	 	  if(!empty($this->browse_edit)){
	 		$returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
	 	  }
	 	  break;
	 	default:
	 	  $returnval = "<a href=javascript:linkurl(\"repaymoney_sp.php?type=error&gotype=sp&listid=".$key."\")>�鿴</a>" ;;	
	 	  break;
	   }
	   return $returnval;
	 }
	 
	 
	 var $browse_field = array("tdcount","fd_repmglist_payrq","fd_author_truename","fd_repmglist_paydate","fd_repmglist_paymoney","fd_repmglist_payfee","fd_repmglist_money","fd_repmglist_sdcrpayfeemoney","fd_repmglist_payfeedirct","fd_repmglist_sdcragentfeemoney","fd_repmglist_arrivetime","fd_sdcr_name","fd_paycard_no","fd_repmglist_fucardno","fd_repmglist_fucardbank","fd_repmglist_shoucardno","fd_repmglist_shoucardbank","fd_repmglist_memo","fd_repmglist_bkordernumber","fd_repmglist_bkntno","fd_repmglist_isagentpay","fd_repmglist_agentdate");
 	 
 	 var $browse_hj = array("fd_repmglist_paymoney","fd_repmglist_payfee","fd_repmglist_money","fd_repmglist_sdcrpayfeemoney","fd_repmglist_sdcragentfeemoney");
 	 
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ˢ�������", "fd_paycard_no","TXT"),
				"1" => array("�û���", "fd_author_truename","TXT"),
				"2" => array("��������", "fd_repmglist_paydate","TXT"),
				"3" => array("��������������", "fd_repmglist_bkntno","TXT"),
				"4" => array("Ԥ�ƴ�������", "fd_repmglist_arrivetime","TXT"),
				"5" => array("����������ˮ��", "fd_repmglist_bkordernumber","TXT"),
				"6" => array("�Ѵ�������", "fd_repmglist_agentdate","TXT"),	
				"7" => array("�����", "fd_repmglist_fucardno","TXT"),	
				"8" => array("�����", "fd_repmglist_shoucardno","TXT"),		
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




class fd_repmglist_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_repmglist_bkordernumber";	// ���ݿ����ֶ�����
        var $bwfd_title = "����������ˮ��";	// �ֶα���
}

class fd_repmglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_bkntno";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������������";	// �ֶα���
}


class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ�����豸��";	// �ֶα���
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}

class fd_repmglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_repmglist_paydate";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_repmglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_shoucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����";	// �ֶα���
}

class fd_repmglist_shoucardbank extends browsefield {
        var $bwfd_fdname = "fd_repmglist_shoucardbank";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����������";	// �ֶα���
} 

class fd_repmglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_fucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����";	// �ֶα���
}   

class fd_repmglist_fucardbank extends browsefield {
        var $bwfd_fdname = "fd_repmglist_fucardbank";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����������";	// �ֶα���
}   

class fd_repmglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_paymoney";	// ���ݿ����ֶ�����
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


class fd_repmglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ȡ�ն�<br>�̻�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_money extends browsefield {
        var $bwfd_fdname = "fd_repmglist_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܶ�";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_sdcrpayfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȡ<br>������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_sdcragentfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʽ�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_arrivetime extends browsefield {
        var $bwfd_fdname = "fd_repmglist_arrivetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "Ԥ�ƴ�������";	// �ֶα���
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;        	         	  
        	  $arrivetime = substr($arrivetime,0,10);
        	     	  
        	  $this->bwfd_show = $arrivetime;        	     	               	       
		        return $this->bwfd_show ;
		    }  
}


 
class fd_repmglist_payrq extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payrq";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "01") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>������</span>";    		
        	 	}else if($transStatus == "00"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>�������</span>";
        		 }
        		 else if($transStatus == "03"){
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>����ȡ��</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}

class fd_repmglist_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payfeedirct";	// ���ݿ����ֶ�����
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

class fd_repmglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_repmglist_isagentpay";	// ���ݿ����ֶ�����
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

class fd_repmglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_repmglist_agentdate";	// ���ݿ����ֶ�����
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



class fd_repmglist_memo extends browsefield {
        var $bwfd_fdname = "fd_repmglist_memo";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ժҪ";	// �ֶα���
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = 100 ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_feedback_b_bu->browse_querywhere = "fd_repmglist_state= '0'";

if(!empty($seldofile)){
  $tb_feedback_b_bu->browse_querywhere .= " and fd_repmglist_payrq = '$seldofile' ";
  $tb_feedback_b_bu->browse_haveselectvalue = $seldofile ;   //ѡ�е�ֵ
}else{
  $tb_feedback_b_bu->browse_querywhere .= " and fd_repmglist_payrq = '00'";
  $tb_feedback_b_bu->browse_haveselectvalue = "00";
}

$tb_feedback_b_bu->browse_firstval = "��ʾȫ��";

$tb_feedback_b_bu->browse_seldofile = array("�������","������","����ȡ��");
$tb_feedback_b_bu->browse_seldofileval = array("00","01","03");


$db = new DB_test ;
global $fd_repmglist_paymoney,$fd_repmglist_payfee,$fd_repmglist_money,$fd_repmglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_repmglist_paymoney,fd_repmglist_payfee,fd_repmglist_money,fd_repmglist_sdcrpayfeemoney,fd_repmglist_sdcragentfeemoney
          from tb_repaymoneyglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_repmglist_paymoney += $db->f(fd_repmglist_paymoney);   
     $fd_repmglist_payfee += $db->f(fd_repmglist_payfee);   
     $fd_repmglist_money += $db->f(fd_repmglist_money);   
     $fd_repmglist_sdcrpayfeemoney += $db->f(fd_repmglist_sdcrpayfeemoney);   
     $fd_repmglist_sdcragentfeemoney += $db->f(fd_repmglist_sdcragentfeemoney);  
}


$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

