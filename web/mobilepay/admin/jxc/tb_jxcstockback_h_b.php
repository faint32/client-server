<?
$thismenucode = "2k214";
require ("../include/common.inc.php");
//require ("../include/browse.inc.php");
require ("../include/have_collect_browse.inc.php");


class tb_paycardstockback_b extends browse {
	 var $prgnoware    = array("ˢ��������","����˻���ʷ");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_stock_id";
	 
	 var $browse_queryselect = "select fd_stock_id , fd_stock_no , fd_stock_suppno , fd_stock_suppname , fd_stock_date 
	                           ,fd_stock_allmoney,fd_stock_allquantity,
	                            fd_stock_ldr,fd_stock_dealwithman
	                            from tb_paycardstockback
	                            ";
                                 
	 function docollect(){  //���ܺ���
	 	  $str_querysql = $this->browse_collectquery.$this->browse_querywhere;
      $this->db->query($str_querysql);
    	if($this->db->nf()){
    		$this->db->next_record();
    		$collectmoney = $this->db->f(collectmoney);
    		$collectquantity = $this->db->f(collectquantity);
    		
    		$collectmoney = number_format($collectmoney, 2, ".", ",");
    		$collectquantity = number_format($collectquantity, 4, ".", ",");
    	}else{
    	  $collectmoney = 0;
    	  $collectquantity=0;
      }
      $this->browse_collectdata = "�˻��ܽ��Ϊ��".$collectmoney."&nbsp;&nbsp;&nbsp;��������Ϊ��".$collectquantity;
   }
   
	 var $browse_field = array("fd_stock_no","fd_stock_suppno","fd_stock_suppname","fd_stock_date","fd_stock_allmoney","fd_stock_allquantity","fd_stock_ldr","fd_stock_dealwithman");
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



class fd_stock_allmoney extends browsefield {
	var $bwfd_fdname = "fd_stock_allmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ܽ��"; // �ֶα���
	var $bwfd_align = "right";
}


class fd_stock_allquantity extends browsefield {
	var $bwfd_fdname = "fd_stock_allquantity"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
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
   			    "0" => array("fd_stock_id") 
   			    );
   var $bwlk_prgname = "stockbackview.php?listid=";
   var $bwlk_title ="��ϸ��";  
}



if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

//Ĭ�ϸ��ݵ�����������
if(empty($order)){
	$order = "fd_stock_date";
	$upordown = "desc";
}

$tb_paycardstockback_bu = new tb_paycardstockback_b ;
$tb_paycardstockback_bu->browse_skin = $loginskin ;
$tb_paycardstockback_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_paycardstockback_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_paycardstockback_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

if($thismenuqx[2])
{
	$tb_paycardstockback_bu->browse_link  = array("lk_view0");
}

$tb_paycardstockback_bu->browse_querywhere .= " fd_stock_state = 9 ";

$tb_paycardstockback_bu->browse_collectquery = "select sum(fd_stock_allmoney) as collectmoney , 
                                     sum(fd_stock_allquantity) as collectquantity from tb_paycardstockback
	                                   ";

$tb_paycardstockback_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
