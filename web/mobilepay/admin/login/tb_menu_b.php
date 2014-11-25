<?
$thismenucode = "9101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_menu_b extends browse {
	 var $prgnoware = array("ϵͳ����","�˵�����","�˵�����");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_menu_id";
 	 var $browse_queryselect = "select fd_menu_id,fd_menu_code,fd_menu_upcode,fd_menu_jpg,fd_menu_name,fd_menu_url,fd_menu_hz,fd_menu_sno,fd_menu_active,CONCAT_WS(',',fd_menu_code,fd_menu_name,fd_menu_upcode) as fd_menu_node from tb_menu ";
       
	 var $browse_delsql = "delete from tb_menu where fd_menu_id = %s" ;
	 var $browse_new = "menu.php" ;
	 var $browse_edit = "menu.php?id=" ;
   
	 var $browse_field = array("fd_menu_code","fd_menu_upcode","fd_menu_jpg","fd_menu_name","fd_menu_url","fd_menu_node","fd_menu_hz","fd_menu_sno","fd_menu_active");
	// var $browse_link  = array("lk_view0");

	 /*var $browse_find = array(		// ��ѯ����
				"0" => array("�û���", "fd_menu_code","TXT")	,
				"1" => array("�û���", "fd_menu_upcode","TXT")										
			 );*/
}

class fd_menu_code extends browsefield {
        var $bwfd_fdname = "fd_menu_code";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�����";	// �ֶα���
}

class fd_menu_upcode extends browsefield {
        var $bwfd_fdname = "fd_menu_upcode";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ϼ��˵�����";	// �ֶα���
}
class fd_menu_name extends browsefield {
        var $bwfd_fdname = "fd_menu_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�����";	// �ֶα���
}
class fd_menu_jpg extends browsefield {
        var $bwfd_fdname = "fd_menu_jpg";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�ͼƬ";	// �ֶα���
		
}
class fd_menu_url extends browsefield {
        var $bwfd_fdname = "fd_menu_url";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�url";	// �ֶα���
}
class fd_menu_node extends browsefield {
			var $bwfd_fdname = "fd_menu_node";	// ���ݿ����ֶ�����
			var $bwfd_title = "�˵��ڵ�";	// �ֶα���
			function makeshow() {	// ��ֵתΪ��ʾֵ
			$this->var = explode(",",$this->bwfd_value);
			$this->bwfd_show = getupmeuncode($this->var[0]);
			return $this->bwfd_show;
		}
		
}
class fd_menu_hz extends browsefield {
        var $bwfd_fdname = "fd_menu_hz";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�hz";	// �ֶα���
}
class fd_menu_sno extends browsefield {
        var $bwfd_fdname = "fd_menu_sno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˵�sno";	// �ֶα���
}
class fd_menu_active extends browsefield {
        var $bwfd_fdname = "fd_menu_active";	// ���ݿ����ֶ�����
        var $bwfd_title = "����״̬";	// �ֶα���
}



// ���Ӷ���
/*class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_menu_id") 
   			    );
   var $bwlk_prgname = "menu.php?id=";
   var $bwlk_title ="�޸�";  
}*/


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_menu_bu = new tb_menu_b ;


//$tb_menu_bu->browse_querywhere = " fd_tel_recsts=0 and fd_menu_id !='1'" ;




$tb_menu_bu->browse_skin = $loginskin ;
$tb_menu_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_menu_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_menu_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_menu_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;

function getupmeuncode($i){
		while($i)
		  {
			$return_array= getupmenuname($i);
			if(is_array($return_array))
			{
				$i	  = $return_array['mnuupcode'];
				$name = $return_array['mnuname']."_".$name; 
			}else
			{
				$i = false;
			}
		  }
		return $name;

}
function getupmenuname($code)
{
	$db  = new DB_test;	
	$query= "select fd_menu_code as mnucode,fd_menu_upcode as mnuupcode,fd_menu_name as mnuname  from tb_menu where fd_menu_code = '$code'";
	$return_array = $db->get_one($query);
	return $return_array;
}
function g2u($str)
{
	$value=iconv("gb2312", "UTF-8", $str);
	return $value;
}
?>