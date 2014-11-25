<?
$thismenucode = "2k212";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
class tb_paycardstockback_b extends browse {
	 var $prgnoware    = array("ˢ��������","����˻�");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_stock_id";
	 
	 var $browse_queryselect = "select fd_stock_id , fd_stock_no , fd_stock_suppno , fd_stock_suppname ,
	                            fd_stock_date ,  fd_stock_state  ,fd_stock_allmoney,fd_stock_allquantity,
	                            fd_stock_ldr,fd_stock_dealwithman
	                            from tb_paycardstockback
	                            ";
	 var $browse_delsql = "delete from tb_paycardstockback where fd_stock_id = '%s'" ;
    var $browse_relatingdelsql = array(
                                "0" => "delete from tb_paycardstockbackdetail where fd_skdetail_stockid = '%s'",
								"1" => "delete from tb_salelist_tmp where fd_tmpsale_seltid = '%s' and fd_tmpsale_type='stockback'"
                                 );
	 var $browse_new   = "jxcstockback.php" ;
   var $browse_edit  = "jxcstockback.php?listid=" ;
   
   
   var $browse_state = array("fd_stock_state");
   
    var $browse_defaultorder = " CASE WHEN fd_stock_state = '0'
                               THEN 1                                  
                               WHEN fd_stock_state = '1'
                                THEN 2                           
                               END,fd_stock_date desc
                             ";	  
   
   
   function makeedit($key) {	// ���ɱ༭����
  	  $returnval = "" ;
   	  switch($this->arr_spilthfield[0]){  //�жϼ�¼������һ��״̬�£�����һ��״̬�¾�ʹ����һ������
   	  	case "0":
   	  	  if(!empty($this->browse_edit)){
  	        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")>".$this->browse_editname."</a>" ;
  	      }
   	  	  break;
   	  	case "1":
   	  	  $returnval = "<a href=javascript:linkurl(\"stock_back_view.php?backstate=0&listid=".$key."\")>�鿴</a>" ;;	
   	  	  break;
   	  }
  	  return $returnval;
    }
       
	 var $browse_field = array("fd_stock_state","fd_stock_no","fd_stock_suppno","fd_stock_suppname","fd_stock_date","fd_stock_allquantity","fd_stock_allmoney","fd_stock_ldr","fd_stock_dealwithman");
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

/*class lk_view3 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_stock_id") 
   			    );
   var $bwlk_prgname = "stock_cw_print.php?listid=";
   var $bwlk_title ="���񵥴�ӡ";  
}*/


// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_stock_id","")
   			    );  
   var $bwlk_title ="��ӡ";	// link����
   var $bwlk_prgname = "stockbackprint.php?listid=";	// ���ӳ���
   
   function makelink() {	// ��������
    $linkurl = $this->makeprg();
    $link  = "<a href=javascript:windowopen(\"".$linkurl."\")>".$this->bwlk_title."</a>";
    return $link;
  }
}

/* // ���Ӷ���
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_stock_id","")
   			    );  
   var $bwlk_title ="�ֲִ�ӡ";	// link����
   var $bwlk_prgname = "print_stock_selstorage.php?listid=";	// ���ӳ���
} */

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_paycardstockback_bu = new tb_paycardstockback_b ;
$tb_paycardstockback_bu->browse_skin = $loginskin ;
$tb_paycardstockback_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_paycardstockback_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_paycardstockback_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

//�д�ӡȨ�޲��ܴ�ӡ
//if($thismenuqx[5]==1){
 // $tb_paycardstockback_bu->browse_link = array("lk_view0","lk_view3");
//}
 $tb_paycardstockback_bu->browse_link = array("lk_view0");
//��ʾ��Ȩ�޲鿴�Ļ�������
$tb_paycardstockback_bu->browse_querywhere .= " (fd_stock_state = 0 or fd_stock_state = 1) ";

$tb_paycardstockback_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
