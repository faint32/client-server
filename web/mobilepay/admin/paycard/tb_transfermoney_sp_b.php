<?
$thismenucode = "2n401";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("ת�˹���","ת�˻��֧������");
	 var $prgnowareurl =  array("","");
	 var $browse_key = "fd_tfmglist_id";			
	 var $browse_queryselect = "select * 
	                            from tb_transfermoneyglist
	                            left join tb_paycard on fd_tfmglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_tfmglist_authorid
	                            ";
	 var $browse_edit = "transfermoney_sp.php?listid=" ;
    var $browse_editname = "���";
	 

	 
	var $browse_defaultorder = " fd_tfmglist_paydate desc,fd_tfmglist_payrq desc
                             ";	  	  
  
   var $browse_state = array("fd_tfmglist_payrq");
   	   function makeedit($key) {	// ���ɱ༭����
			  $returnval = "" ;
			  switch($this->arr_spilthfield[0]){  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
				case "0":
				  if(!empty($this->browse_edit)){
					$returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
				  }
				  break;
				default:
				  $returnval = "<a href=javascript:linkurl(\"transfermoney_sp.php?type=error&gotype=sp&listid=".$key."\")>�鿴</a>" ;;	
				  break;
			  }
			  return $returnval;
			}
   var $browse_field = array("fd_tfmglist_bkordernumber","fd_tfmglist_paytype","fd_tfmglist_bkntno","fd_paycard_no","fd_author_truename","fd_tfmglist_paymoney","fd_tfmglist_payfee","fd_tfmglist_money","fd_tfmglist_sdcrpayfeemoney","fd_tfmglist_sdcragentfeemoney","fd_tfmglist_paydate","fd_tfmglist_payrq","fd_tfmglist_shoucardno","fd_tfmglist_fucardno","fd_tfmglist_current","fd_tfmglist_memo","fd_tfmglist_isagentpay","fd_tfmglist_agentdate");
   var $browse_hj = array("fd_tfmglist_paymoney","fd_tfmglist_payfee","fd_tfmglist_money","fd_tfmglist_sdcragentfeemoney","fd_tfmglist_sdcrpayfeemoney");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ˢ�������", "fd_paycard_no","TXT"),
				"1" => array("�û���", "fd_author_truename","TXT"),
				"2" => array("��������", "fd_tfmglist_paydate","TXT"),
				"3" => array("���н�����ˮ��", "fd_tfmglist_bkntno","TXT"),
				"4" => array("�����", "fd_tfmglist_fucardno","TXT"),	
				"5" => array("�����", "fd_tfmglist_shoucardno","TXT")				
				);

	 
	 
}

class fd_tfmglist_bkordernumber  extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_bkordernumber";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ˮ��
";	// �ֶα���
}
class fd_tfmglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_bkntno";	// ���ݿ����ֶ�����
        var $bwfd_title = "���н�����ˮ��
";	// �ֶα���
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

class fd_tfmglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paymoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ױ���";	// �ֶα���
		function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    } 
}
class fd_tfmglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_payfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ȡ�ն�<br>�̻�������";	// �ֶα���
		function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }   
}

class fd_tfmglist_money extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܶ�";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_sdcrpayfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȡ<br>������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_sdcragentfeemoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʽ�������";	// �ֶα���
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paydate";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������
";	// �ֶα���
}

class fd_tfmglist_paytype extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paytype";	// ���ݿ����ֶ�����
        var $bwfd_title = "ת������";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
        
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "tfmg") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>��ͨת��</span>";    		
        	 	}else if($transStatus == "suptfmg"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>����ת��</span>";
        		 }
        		 else{
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>��ͨת��</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}
class fd_tfmglist_payrq extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_payrq";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���
             
		 function makeshow() {	// ��ֵתΪ��ʾֵ
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "1") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>������</span>";    		
        	 	}else if($transStatus == "0"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>�������</span>";
        		 }
        		 else if($transStatus == "3"){
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>����ȡ��</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}
class fd_tfmglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_shoucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����

";	// �ֶα���
}
class fd_tfmglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_fucardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����
";	// �ֶα���
}
class fd_tfmglist_current extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_current";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_tfmglist_memo extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_memo";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ժҪ";	// �ֶα���
}

class fd_tfmglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_isagentpay";	// ���ݿ����ֶ�����
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

class fd_tfmglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_agentdate";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ѵ�������";	// �ֶα���
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;  
			  if($arrivetime==""){
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
if(empty($order)){
	$order = "fd_tfmglist_paydate";
	$upordown = "desc";
}
$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_feedback_b_bu->browse_querywhere = "fd_tfmglist_state = '0'";
if(!empty($seldofile)){
  $tb_feedback_b_bu->browse_querywhere .= " and fd_tfmglist_payrq = '$seldofile' ";
  $tb_feedback_b_bu->browse_haveselectvalue = $seldofile ;   //ѡ�е�ֵ
}else{
  $tb_feedback_b_bu->browse_querywhere .= " and fd_tfmglist_payrq = '00' ";
  $tb_feedback_b_bu->browse_haveselectvalue = "00";
}

$tb_feedback_b_bu->browse_firstval = "��ʾȫ��";

$tb_feedback_b_bu->browse_seldofile = array("�������","������","����ȡ��");
$tb_feedback_b_bu->browse_seldofileval = array("00","01","03");

$db = new DB_test ;
global $fd_tfmglist_paymoney,$fd_tfmglist_payfee,$fd_tfmglist_money,$fd_tfmglist_sdcragentfeemoney,$fd_tfmglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_tfmglist_paymoney,fd_tfmglist_payfee,fd_tfmglist_sdcragentfeemoney,fd_tfmglist_sdcrpayfeemoney,fd_tfmglist_money
          from tb_transfermoneyglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_tfmglist_paymoney += $db->f(fd_tfmglist_paymoney);   
     $fd_tfmglist_payfee += $db->f(fd_tfmglist_payfee);
	 $fd_tfmglist_money += $db->f(fd_tfmglist_money);   
     $fd_tfmglist_sdcragentfeemoney += $db->f(fd_tfmglist_sdcragentfeemoney);   
	 $fd_tfmglist_sdcrpayfeemoney += $db->f(fd_tfmglist_sdcrpayfeemoney);      
}

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

