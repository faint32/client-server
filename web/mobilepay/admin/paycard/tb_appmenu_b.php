<? 
$thismenucode = "2k513";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_author_sp_b extends browse {
	var $prgnoware = array ("��������", "APP��������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_appmnu_id";
	
	var $browse_queryselect = "select * from tb_appmenu
	                               left join tb_appmenutype on fd_amtype_id = fd_appmnu_amtypeid";
	var $browse_edit = "appmenu.php?listid=";
	var $browse_new = "appmenu.php";
	var $browse_link = array ("lk_view1" );
	var $browse_delsql = "delete from tb_appmenu where fd_appmnu_id = '%s'";
	 var $browse_relatingdelsql = array(
                                "0" => "delete from tb_appmenu where fd_appmnu_id = '%s'",
								"1"=>"update tb_appmenu set fd_appmnu_version=fd_appmnu_version+0.1"
                                 );
	var $browse_field = array ("fd_appmnu_id","fd_appmnu_order","fd_appmnu_isconst","fd_amtype_name","fd_appmnu_iscusfenrun","fd_appmnu_no","fd_appmnu_version", "fd_appmnu_name","fd_appmnu_url","fd_appmnu_scope", "fd_appmenu_active","fd_appmenu_authorstate","fd_appmnu_istabno");
	var $browse_find = array (// ��ѯ����
			"0" => array ("�汾��", "fd_appmnu_version", "TXT" ),
            "1" => array ("APP��������", "fd_appmnu_name", "TXT" ),
        "2" => array ("APP����ͼƬ", "fd_appmnu_pic", "TXT" ), "3" => array ("APP����Ŀ¼", "fd_appmnu_order", "TXT" ), "4" => array ("APP����url", "fd_appmnu_url", "TXT" )
    , "5" => array ("APP����Ĭ��", "fd_appmenu_default", "TXT" ),
        "6" => array ("��������", "fd_amtype_name", "TXT" ));

}
class fd_appmnu_id extends browsefield {
	var $bwfd_fdname = "fd_appmnu_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_appmnu_order extends browsefield {
    var $bwfd_fdname = "fd_appmnu_order"; // ���ݿ����ֶ�����
    var $bwfd_title = "����"; // �ֶα���
}
class fd_amtype_name extends browsefield {
    var $bwfd_fdname = "fd_amtype_name"; // ���ݿ����ֶ�����
    var $bwfd_title = "��������"; // �ֶα���
}
class fd_appmnu_no extends browsefield {
	var $bwfd_fdname = "fd_appmnu_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "���
"; // �ֶα���
}
class fd_appmnu_version extends browsefield {
	var $bwfd_fdname = "fd_appmnu_version"; // ���ݿ����ֶ�����
	var $bwfd_title = "�汾��
"; // �ֶα���
}
class fd_appmnu_name extends browsefield {
	var $bwfd_fdname = "fd_appmnu_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP��������
"; // �ֶα���
}
/*class fd_appmnu_pic extends browsefield {
	var $bwfd_fdname = "fd_appmnu_pic"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP����ͼƬ
"; // �ֶα���
}
class fd_appmnu_order extends browsefield {
	var $bwfd_fdname = "fd_appmnu_order"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP����Ŀ¼
"; // �ֶα���
}*/
class fd_appmnu_url extends browsefield {
	var $bwfd_fdname = "fd_appmnu_url"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP����url
"; // �ֶα���
}
/*class fd_appmenu_default extends browsefield {
	var $bwfd_fdname = "fd_appmenu_default"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP����Ĭ��
"; // �ֶα���
}*/

class fd_appmnu_isconst extends browsefield {
    var $bwfd_fdname = "fd_appmnu_isconst"; // ���ݿ����ֶ�����
    var $bwfd_title = "�Ƿ�̶�����"; // �ֶα���
    function makeshow() {	// ��ֵתΪ��ʾֵ
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "��";
                break;
            case "1":
                $this->bwfd_show = "<font color='#0000ff'>�̶�</font>";
                break;
            default:
                $this->bwfd_show = "��";
                break;

        }
        return $this->bwfd_show ;
    }
}
class fd_appmnu_iscusfenrun extends browsefield {
    var $bwfd_fdname = "fd_appmnu_iscusfenrun"; // ���ݿ����ֶ�����
    var $bwfd_title = "�����������̷���"; // �ֶα���
    function makeshow() {	// ��ֵתΪ��ʾֵ
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "������";
                break;
            case "1":
                $this->bwfd_show = "<font color='#0000ff'>����</font>";
                break;
            default:
                $this->bwfd_show = "";
                break;

        }
        return $this->bwfd_show ;
    }
}

class fd_appmnu_scope extends browsefield {
	var $bwfd_fdname = "fd_appmnu_scope"; // ���ݿ����ֶ�����
	var $bwfd_title = "ʹ�÷�Χ"; // �ֶα���
       function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "���ÿ�";
        		     break;       		
        		case "bankcard":
        		    $this->bwfd_show = "���";
        		     break;
				case "all":
        		    $this->bwfd_show = "���ֿ�������";
        		     break;	 
        		default:
        			  $this->bwfd_show = "";
        		    break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmenu_active extends browsefield {
	var $bwfd_fdname = "fd_appmenu_active"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ񼤻�"; // �ֶα���
       function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "δ����";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "�Ѽ���";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmenu_authorstate extends browsefield {
	var $bwfd_fdname = "fd_appmenu_authorstate"; // ���ݿ����ֶ�����
	var $bwfd_title = "ʹ���û�Ȩ��"; // �ֶα���
       function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "�����û�";
        		     break;       		
        		case "9":
        		    $this->bwfd_show = "<font color='#00CC00'>��֤�û�</font>";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmnu_istabno extends browsefield {
	var $bwfd_fdname = "fd_appmnu_istabno"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ���ʾ������"; // �ֶα���
       function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {     		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
				default:
					$this->bwfd_show = "��";
        		     break; 
        	}      		     
		      return $this->bwfd_show ;
			  }
}

// ���Ӷ���
class lk_view1 extends browselink {
	var $bwlk_fdname = array ("0" => array ("fd_appmnu_id") );
	var $bwlk_title = "<font color='#00CC00'>���ܽӿ�</font>"; // link����
	var $bwlk_prgname = "app_interface_b.php?listid="; // ���ӳ���

}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}
if(empty($order)){
	$order = "fd_appmnu_id";
	$upordown = "asc";
}

$tb_author_sp_b_bu = new tb_author_sp_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_author_sp_b_bu->browse_link = array ("lk_view1" );
//$tb_author_sp_b_bu->browse_querywhere = "fd_appmnu_istabno = 1";

$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
