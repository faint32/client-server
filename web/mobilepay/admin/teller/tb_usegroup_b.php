<?
$thismenucode = "9102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class web_usegroup_b extends browse {
	 var $prgnoware = array("ϵͳ����","�û������","�����û���");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_usegroup_id";
 	 var $browse_queryselect = "select * from web_usegroup";

	 //var $browse_delsql = "delete from web_usegroup where fd_usegroup_id = %s" ;
	 var $browse_new = "usegroup.php" ;
	 var $browse_edit = "usegroup.php?id=" ;

	 var $browse_field = array("fd_usegroup_name","fd_usegroup_memo");
	 var $browse_link  = array("lk_view0");

	 var $browse_find = array(		// ��ѯ����
				"1" => array("����", "fd_usegroup_name","TXT"),
				"2" => array("��ע", "fd_usegroup_memo","TXT") 										
			 );
}

class fd_usegroup_name extends browsefield {
        var $bwfd_fdname = "fd_usegroup_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}

class fd_usegroup_memo extends browsefield {
        var $bwfd_fdname = "fd_usegroup_memo";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ע";	// �ֶα���        
}


// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_usegroup_id","")
   			    );  
   var $bwlk_title ="�û��鹦��";	// link����
   var $bwlk_prgname = "selgroupqx.php?id=";	// ���ӳ���
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$web_usegroup_bu = new web_usegroup_b ;
$web_usegroup_bu->browse_skin = $loginskin ;
$web_usegroup_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$web_usegroup_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$web_usegroup_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$web_usegroup_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>