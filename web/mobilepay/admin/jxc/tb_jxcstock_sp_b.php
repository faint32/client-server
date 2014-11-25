<?
$thismenucode = "2k202";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_paycardstock_b extends browse {
	 var $prgnoware    = array("ˢ�������","�������");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_stock_id";
	 
	 var $browse_queryselect = "select fd_stock_id , fd_stock_no , fd_stock_suppno , fd_stock_suppname ,
	                            fd_stock_date , fd_stock_state,  fd_stock_allmoney,fd_stock_allquantity,
	                            fd_stock_ldr,fd_stock_dealwithman
	                            from tb_paycardstock
	                            ";
   var $browse_edit  = "jxcstock_sp.php?listid=" ;
   
   var $browse_editname = "����";
   
   var $browse_defaultorder = " CASE WHEN fd_stock_state = '1'
                                THEN 1                                  
                                WHEN fd_stock_state = '0'
                                THEN 2                           
                                END,fd_stock_date desc
                              ";	  
   
   
   var $browse_state = array("fd_stock_state");
   
   function makeedit($key) {	// ���ɱ༭����
  	  $returnval = "" ;
   	  switch($this->arr_spilthfield[0]){  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
   	  	case "1":
   	  	  if(!empty($this->browse_edit)){
  	        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
  	      }
   	  	  break;
   	  	case "0":
   	  	  $returnval = "<a href=javascript:linkurl(\"stock_sq_view.php?backstate=1&listid=".$key."\")>�鿴</a>" ;;	
   	  	  break;
   	  }
  	  return $returnval;
    }
   
	 var $browse_field = array("fd_stock_state","fd_stock_no","fd_stock_suppno","fd_stock_suppname","fd_stock_allmoney","fd_stock_allquantity","fd_stock_date","fd_stock_ldr","fd_stock_dealwithman");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���ݱ��"      ,   "fd_stock_no"         ,"TXT"),
				"1" => array("��Ӧ�̱��"    ,   "fd_stock_suppno"     ,"TXT"),
				"2" => array("��Ӧ������"    ,   "fd_stock_suppname"   ,"TXT"), 
				"3" => array("��������"      ,   "fd_stock_date"       ,"TXT"), 
				);

}

class fd_stock_no extends browsefield {
        var $bwfd_fdname = "fd_stock_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ݱ��";	// �ֶα���
}

class fd_stock_suppno extends browsefield {
        var $bwfd_fdname = "fd_stock_suppno";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ�̱��";	// �ֶα���
}

class fd_stock_suppname extends browsefield {
        var $bwfd_fdname = "fd_stock_suppname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ������";	// �ֶα���
}

class fd_stock_date extends browsefield {
        var $bwfd_fdname = "fd_stock_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_stock_state extends browsefield {
        var $bwfd_fdname = "fd_stock_state";	// ���ݿ����ֶ�����
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

class fd_stock_allquantity extends browsefield {
	var $bwfd_fdname = "fd_stock_allquantity"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
	var $bwfd_align = "right";
}

class fd_stock_allmoney extends browsefield {
	var $bwfd_fdname = "fd_stock_allmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ܽ��"; // �ֶα���
	var $bwfd_align = "right";
}

class fd_stock_ldr  extends browsefield {
	var $bwfd_fdname = "fd_stock_ldr"; // ���ݿ����ֶ�����
	var $bwfd_title = "¼����"; // �ֶα���
}

class fd_stock_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_stock_dealwithman"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_stock_id","")
   			    );  
   var $bwlk_title ="��ӡ";	// link����
   var $bwlk_prgname = "stockprint.php?listid=";	// ���ӳ���
   
   function makelink() {	// ��������
    $linkurl = $this->makeprg();
    $link  = "<a href=javascript:windowopen(\"".$linkurl."\")>".$this->bwlk_title."</a>";
    return $link;
  }
}
if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_paycardstock_bu = new tb_paycardstock_b ;
$tb_paycardstock_bu->browse_skin = $loginskin ;
$tb_paycardstock_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_paycardstock_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_paycardstock_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

 $tb_paycardstock_bu->browse_link = array("lk_view0");
$tb_paycardstock_bu->browse_querywhere .= "fd_stock_state = 1 ";
$tb_paycardstock_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
