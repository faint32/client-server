<?
$thismenucode = "9101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class web_teller_b extends browse {
	 var $prgnoware = array("ϵͳ����","�û�����","�����û�");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_tel_id";
 	 var $browse_queryselect = "select fd_tel_id,fd_tel_name,fd_tel_recsts,fd_usegroup_name  from web_teller
 	                            left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid 
 	                           ";
       
	 var $browse_delsql = "delete from web_teller where fd_tel_id = %s" ;
	 var $browse_new = "teller.php" ;
	 var $browse_edit = "teller.php?id=" ;
   

   
	 var $browse_field = array("fd_tel_name","fd_usegroup_name");
	 var $browse_link  = array("lk_view0");

	 var $browse_find = array(		// ��ѯ����
				"0" => array("�û���", "fd_tel_name","TXT")	,
				"1" => array("�û���", "fd_usegroup_name","TXT")										
			 );
}

class fd_tel_name extends browsefield {
        var $bwfd_fdname = "fd_tel_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}

class fd_usegroup_name extends browsefield {
        var $bwfd_fdname = "fd_usegroup_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û���";	// �ֶα���
}



// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_tel_id","")
   			    );  
   var $bwlk_title ="�û�����";	// link����
   var $bwlk_prgname = "seltelqx.php?id=";	// ���ӳ���
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$web_teller_bu = new web_teller_b ;

if($loginuser != 1){
	$web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and fd_tel_id !='1'" ;
}

//if($loginuser =='24'){          
//	$web_teller_bu->browse_querywhere = " fd_tel_recsts=0 " ;
//}else if($loginuser =='20'){
//  $web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and fd_tel_id !='24'" ;
//}else{
//  $web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and (fd_tel_id !='24' and fd_tel_id !='20')" ;
//}


$web_teller_bu->browse_skin = $loginskin ;
$web_teller_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$web_teller_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$web_teller_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$web_teller_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>