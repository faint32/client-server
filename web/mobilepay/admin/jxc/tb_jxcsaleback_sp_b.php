<?php
$thismenucode = "2k216";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_salelistback_b extends browse {
	 var $prgnoware    = array("ˢ��������","�����˻�����");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_selt_id";
	 
	 var $browse_queryselect = "select fd_selt_id , fd_selt_no , fd_selt_cusno , fd_selt_cusname ,fd_selt_ldr ,fd_selt_dealwithman ,
	                            fd_selt_date ,fd_selt_state,fd_selt_skfs ,fd_selt_allquantity
								from tb_salelistback
	                            ";
	                            
   var $browse_edit  = "jxcsaleback_sp.php?listid=" ;
   
   var $browse_editname = "����";
   
   var $browse_state = array("fd_selt_state");			
	
	var $browse_defaultorder = " CASE WHEN fd_selt_state = '1'
                                THEN 1                                  
                                WHEN fd_selt_state = '0'
                                THEN 2                           
                                END,fd_selt_date desc
                              ";	  
	
	
  function makeedit($key)
  {// ���ɱ༭����
    $returnval = "" ;
    switch($this->arr_spilthfield[0])
    {  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
      case "1":
        if(!empty($this->browse_edit)){
        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
        }
        break;
      case "0":
        $returnval = "<a href=javascript:linkurl(\"saleback_sq_view.php?backstate=1&listid=".$key."\")>�鿴</a>&nbsp;|&nbsp;&nbsp;&nbsp;" ;;
        break;
    }
    return $returnval;
  }
   
	 var $browse_field = array("fd_selt_state","fd_selt_no","fd_selt_cusno","fd_selt_cusname","fd_selt_allquantity","fd_selt_date","fd_selt_skfs","fd_selt_ldr","fd_selt_dealwithman");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���ݱ��"      ,   "fd_selt_no"          ,"TXT"),
				"1" => array("�ͻ����"      ,   "fd_selt_cusno"       ,"TXT"),
				"2" => array("�ͻ�����"      ,   "fd_selt_cusname"     ,"TXT"), 
				"3" => array("��������"      ,   "fd_selt_date"        ,"TXT"), 
				);

}
class fd_selt_no extends browsefield {
        var $bwfd_fdname = "fd_selt_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ݱ��";	// �ֶα���
}

class fd_selt_cusno extends browsefield {
        var $bwfd_fdname = "fd_selt_cusno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ͻ����";	// �ֶα���
}

class fd_selt_cusname extends browsefield {
        var $bwfd_fdname = "fd_selt_cusname";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ͻ�����";	// �ֶα���
}

class fd_selt_allquantity extends browsefield {
        var $bwfd_fdname = "fd_selt_allquantity";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˻�����";	// �ֶα���
}

class fd_selt_date extends browsefield {
        var $bwfd_fdname = "fd_selt_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_selt_ldr extends browsefield {
        var $bwfd_fdname = "fd_selt_ldr";	// ���ݿ����ֶ�����
        var $bwfd_title = "¼����";	// �ֶα���
}

class fd_selt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_selt_dealwithman";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
}



class fd_selt_skfs extends browsefield {
        var $bwfd_fdname = "fd_selt_skfs";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ʽ";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch($this->bwfd_value){
        		case "1":
        		  $this->bwfd_show = "�ֽ�";
        		  break;
        		case "2":
        		  $this->bwfd_show = "֧Ʊ";
        		  break;
        		case "3":
        		  $this->bwfd_show = "���";
        		  break;
        		case "4":
        		  $this->bwfd_show = "�ж�";
        		  break;
        		default:
        		  $this->bwfd_show = "";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}
class fd_selt_state extends browsefield {
        var $bwfd_fdname = "fd_selt_state";	// ���ݿ����ֶ�����
        var $bwfd_title = "״̬";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch($this->bwfd_value){
        		case "0":
        		  $this->bwfd_show = "�ݴ�";
        		  break;
        		case "1":
        		  $this->bwfd_show = "�ȴ�����";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

class lk_view0 extends browselink {
  var $bwlk_fdname = array(      // �������ݿ����ֶ�����
    "0" => array("fd_selt_id") 
  );
  var $bwlk_prgname = "salebackprint.php?listid=";
  var $bwlk_title ="��ӡ";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_salelistback_bu = new tb_salelistback_b ;
$tb_salelistback_bu->browse_skin = $loginskin ;
$tb_salelistback_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_salelistback_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_salelistback_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

if($thismenuqx[2])
{
  $tb_salelist_bu->browse_link  = array("lk_view0");
}

$tb_salelistback_bu->browse_querywhere .= "(fd_selt_state = 0 or fd_selt_state = 1) ";

$tb_salelistback_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
