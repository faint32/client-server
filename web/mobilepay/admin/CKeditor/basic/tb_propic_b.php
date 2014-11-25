<?
$thismenucode = "1c607";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("��������","��Ʒ����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_procatrad_id";
	 
	 var $browse_queryselect = "select fd_trademark_name,fd_trademark_id,fd_proca_catname,fd_procatrad_id,fd_procatrad_datetime,
	 fd_procatrad_commjs from web_conf_procatrademark
	                            left join tb_trademark on fd_trademark_id = fd_procatrad_trademarkid
	                            left join tb_procatalog on fd_proca_id = fd_procatrad_procaid                        
	                             ";

     var $browse_defaultorder = " fd_procatrad_datetime desc
                              ";	    
     var $browse_new    = "propic.php" ;
	 var $browse_edit   = "propic.php?listid=" ;
    // var $browse_link  = array("lk_view0");

	 var $browse_field = array("fd_procatrad_id","fd_proca_catname","fd_trademark_name","fd_procatrad_datetime","fd_procatrad_commjs");
  	 var $browse_find = array(		// ��ѯ����
				"0" => array("Ʒ��"    , "fd_trademark_name"   ,"TXT"    ),
				"1" => array("����"    , "fd_proca_catname"   ,"TXT"    ),
				);
}
class fd_procatrad_id extends browsefield {
        var $bwfd_fdname = "fd_procatrad_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_procatrad_datetime extends browsefield {
        var $bwfd_fdname = "fd_procatrad_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ʱ��";	// �ֶα���
		var $bwfd_align ="center";
}
class fd_trademark_name extends browsefield {
        var $bwfd_fdname = "fd_trademark_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "Ʒ��";	// �ֶα���
}
class fd_proca_catname extends browsefield {
        var $bwfd_fdname = "fd_proca_catname";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}
class fd_procatrad_commjs extends browsefield {
        var $bwfd_fdname = "fd_procatrad_commjs";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ����";	// �ֶα���
		
		  function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {      		
        		case "":
        		    $this->bwfd_show = "δ����";
        		     break;
        		default:
        		    $this->bwfd_show = "�Ѳ���";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;

// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_procatrad_id","")
   			    );  
   var $bwlk_title ="Ʒ��logo";	// link����
   var $bwlk_prgname = "tradlogo.php?listid=";	// ���ӳ���
}

$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_procatalog_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_procatalog_b_bu->browse_editqx = 1;  // �༭Ȩ��
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
