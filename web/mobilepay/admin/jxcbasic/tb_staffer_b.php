<?
$thismenucode = "2k109";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_staffer_b extends browse {
	 var $prgnoware    = array("��������","�����˹���");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_sta_id";
	 
	 var $browse_queryselect = "select * from tb_staffer
	                            left join tb_dept on fd_dept_id = fd_sta_deptid";
	 var $browse_delsql = "delete from tb_staffer where fd_sta_id = '%s'" ;
	 var $browse_new = "staffer.php?status=1" ;
	 var $browse_edit = "staffer.php?status=1&id=" ;	 
	 
   var $browse_querywhere = "fd_sta_dimission = 1 and fd_sta_type != 4 " ;
   

	 var $browse_fieldname =  array("���","����","��������","���֤��","�ֻ�����","��ְʱ��","��ַ","��ע",'ID');
     var $browse_fieldval =  array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_sta_idcard","fd_sta_mobile","fd_sta_jobtime","fd_sta_address","fd_sta_memo","fd_sta_id");
	 var $browse_ischeck  =  array("1","1","1","1","1","1","1","1","1","1");
     
	 var $browse_link  = array("lk_view0");
   
	 var $browse_field = array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_sta_mobile","fd_sta_jobtime");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("ְԱ���", "fd_sta_stano","TXT"),
				"1" => array("��������", "fd_dept_name","TXT"),  
				"2" => array("����", "fd_sta_name","TXT"), 
				"4" => array("������", "fd_sta_homeplace","TXT"),
				"5" => array("��������", "fd_sta_birthday","TXT"),
				"3" => array("�ֻ�����", "fd_sta_mobile","TXT")
				);
}

class fd_sta_stano extends browsefield {
        var $bwfd_fdname = "fd_sta_stano";	// ���ݿ����ֶ�����
        var $bwfd_title = "ְԱ���";	// �ֶα���
}

class fd_sta_name extends browsefield {
        var $bwfd_fdname = "fd_sta_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}



class fd_dept_name extends browsefield {
        var $bwfd_fdname = "fd_dept_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}


class fd_sta_mobile extends browsefield {
        var $bwfd_fdname = "fd_sta_mobile";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ֻ�����";	// �ֶα���
}

class fd_sta_jobtime extends browsefield {
        var $bwfd_fdname = "fd_sta_jobtime";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ְʱ��";	// �ֶα���
}


// ���Ӷ���





class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_sta_id","")
   			    );  
   var $bwlk_title ="��ְ����";	// link����
   var $bwlk_prgname = "dimission_set.php?staid=";	// ���ӳ���
}



if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_staffer_bu = new tb_staffer_b ;
$tb_staffer_bu->browse_skin = $loginskin ;
$tb_staffer_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_staffer_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_staffer_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_staffer_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
