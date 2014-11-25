<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_statomer_b extends findbrowse{
	 var $prgname = "ѡ��Ա��" ;

	 var $brow_key = "fd_sta_id";
	 var $brow_queryselect = "select * from tb_staffer 
                            left join tb_dept on fd_dept_id = fd_sta_deptid
                            left join tb_jobs on fd_jobs_id = fd_sta_duty
	                         ";
	 var $brow_field = array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_jobs_name");
	 
	 var $brow_querywhere = "fd_sta_dimission = 1 and fd_sta_type !=4  " ;
	
	 
   	 var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_sta_id",
   			    "1" => "fd_sta_stano",
   			    "2" => "fd_sta_name",
   			    "3" => "fd_dept_name",
   			    "4" => "fd_jobs_name",
			    	"5" => "fd_sta_sex" ,
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("���", "fd_sta_stano","TXT"),
			  "1" => array("����", "fd_sta_name","TXT"),
			 );
}

class fd_sta_stano extends findbrowsefield {
        var $bwfd_fdname = "fd_sta_stano";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_sta_name extends findbrowsefield {
        var $bwfd_fdname = "fd_sta_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_dept_name extends findbrowsefield {
        var $bwfd_fdname = "fd_dept_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_jobs_name extends findbrowsefield {
        var $bwfd_fdname = "fd_jobs_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "ְλ";	// �ֶα���
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_statomer_bu = new tb_statomer_b ;
$tb_statomer_bu->brow_skin = $loginskin ;
$tb_statomer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>