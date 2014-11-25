<?
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_customer_b extends findbrowse{
	 var $prgname = "�ͻ�ѡ��" ;

	 var $brow_key = "fd_cus_id";
	 var $brow_queryselect = "select fd_cus_id , fd_cus_no , fd_cus_name , fd_cus_allname,fd_cus_custype , fd_cus_bank , fd_cus_account  from tb_customer";
	 var $brow_field = array("fd_cus_no","fd_cus_name","fd_cus_allname","fd_cus_custype");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_cus_id",
   			    "1" => "fd_cus_no",
   			    "2" => "fd_cus_allname",
   			    "3" => "fd_cus_name",
   			    "4" => "fd_cus_bank",
   			    "5" => "fd_cus_account"
   			   );  
  
    function makefindsql($whatdofind,$howdofind,$findwhat){
      $show = "";
      $findwhat =strval($findwhat);
      if((!empty($whatdofind))&&(!empty($howdofind))&&(!empty($findwhat) or $findwhat==0)) {
      	if($whatdofind=="fd_cus_custype"){
      	  switch($findwhat){
      	  	case "����":
      	  	   $findwhat = 0;
      	  	   break;
      	  	case "����":
      	  	   $findwhat = 1;
      	  	   break;
      	  	case "����":
      	  	   $findwhat = 2;
      	  	   break;
      	  }
      	}
      	if($howdofind=="like" or $howdofind=="not like"){
           $show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
        }else{
        	  $show = $whatdofind." ".$howdofind." '".$findwhat."'";
        }
      }
      
      return $show ;
   }
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("���", "fd_cus_no","TXT"),
			  "1" => array("����", "fd_cus_name","TXT"),
			  "2" => array("ȫ��", "fd_cus_allname","TXT"),
			   "3" => array("�ͻ�����", "fd_cus_custype","TXT"),
			 );
}

class fd_cus_no extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_cus_name extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_cus_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "ȫ��";	// �ֶα���
}

class fd_cus_custype extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_custype";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ͻ�����";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "����";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "����";
        		     break;
        		case "2":
        		    $this->bwfd_show = "ֽ��";
        	
        	}      		     
		      return $this->bwfd_show ;
  	    }
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_customer_bu = new tb_customer_b ;
$tb_customer_bu->brow_skin = $loginskin ;
$tb_customer_bu->brow_querywhere .= " fd_cus_state = 9 ";

$tb_customer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>