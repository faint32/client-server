<?
$thismenucode = "2k115";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_holiday_b extends browse 
{
	 var $prgnoware    = array("��������","���ڼ��ڹ���");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_holiday_id";
	 
	 var $browse_queryselect = "select * from tb_holiday 
	 					left join tb_staffer on fd_sta_id = fd_holiday_staid";
	var $browse_new    = "holiday.php" ;		
	var $browse_edit    = "holiday.php?listid=" ;
	  var $browse_delsql = "delete from tb_holiday where fd_holiday_id = '%s'" ;
   
    //var $browse_outtoexcel ="excelwriter_holiday.php";
	
	 var $browse_inputfile ="input_holiday.php";
	 
	 var $browse_fieldname =  array("��������","���ڼ������","���ڼ�������");
	 var $browse_fieldval  =  array("fd_holiday_name","fd_holiday_year","fd_holiday_date");
     var $browse_ischeck  =  array("1","1","1","1"); 
	 
   var $browse_field = array("fd_holiday_id","fd_holiday_name","fd_holiday_year","fd_holiday_date","fd_holiday_active","fd_holiday_datetime","fd_sta_name" );
   
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("��������", "fd_holiday_name","TXT"   ),
				"1" => array("���ڼ������", "fd_holiday_year","TXT"   ),
				"2" => array("���ڼ�������", "fd_holiday_date","TXT"   )
				);
}

class fd_holiday_id extends browsefield {
        var $bwfd_fdname = "fd_holiday_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_holiday_name extends browsefield {
        var $bwfd_fdname = "fd_holiday_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_holiday_year extends browsefield {
        var $bwfd_fdname = "fd_holiday_year";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ڼ������";	// �ֶα���
}

class fd_holiday_date extends browsefield {
        var $bwfd_fdname = "fd_holiday_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ڼ�������";	// �ֶα���
       
}


class fd_holiday_active extends browsefield {
        var $bwfd_fdname = "fd_holiday_active";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ�ͣ��";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {      		
        		case "1":
        		    $this->bwfd_show = "����";
        		     break;
        		case "0":
        		    $this->bwfd_show = "<font color='#ff0000'>��ȡ��</font>";
        		     break; 
        		default:
        		     $this->bwfd_show = "";
        		     break;     		 								
          }
		      return $this->bwfd_show ;
  	    }
}

class fd_holiday_datetime extends browsefield {
        var $bwfd_fdname = "fd_holiday_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����޸�ʱ��";	// �ֶα���
       
}
class fd_sta_name extends browsefield {
        var $bwfd_fdname = "fd_sta_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
       
}

class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_holiday_id") 
   			    );
   var $bwlk_prgname = "monthtable_view.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>�鿴ÿ�����м���</span>";  
   
} 


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_holiday_b_bu = new tb_holiday_b ;
$tb_holiday_b_bu->browse_skin = $loginskin ;
$tb_holiday_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_holiday_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_holiday_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
// $tb_holiday_b_bu->browse_querywhere = " fd_holiday_organid='$loginorganid'"; 
$tb_holiday_b_bu->browse_link  = array("lk_view0"); 
$tb_holiday_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
