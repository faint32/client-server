<?
$thismenucode = "7n004";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");

class tb_mobiletest_b extends browse {
	//var $prgnoware = array ("ˢ��������", "������������" );
	var $prgnoware = array ("��������", "���Ͳ��Ա���" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_mt_id";
	
	var $browse_queryselect = "select *,(fd_mt_yescount/fd_mt_testcount)*100 as parents from 
			tb_mobiletest left join tb_mobile on  fd_mt_mobileid = fd_mobile_id  left join tb_appmenu on  fd_mt_appmnuid = fd_appmnu_id 
			 left join tb_mobilebrand on fd_mobilebrand_id = fd_mobile_brandid";
	
	var $browse_edit = "mobiletest.php?listid=";
 	var $browse_new = "mobiletest.php";
	var $browse_delsql = "delete from tb_mobiletest where fd_mt_id = '%s'";
	
	var $browse_field = array ( "fd_mt_tester","fd_mobilebrand_name", "fd_mobile_name","fd_appmnu_name","fd_mt_date","fd_mt_testcount","fd_mt_yescount","parents" );
	
	 var $browse_hj = array("fd_mt_testcount","fd_mt_yescount","parents");
 	 
	var $browse_find = array (// ��ѯ����
	"0" => array ("���", "fd_mt_id", "TXT" ), 
	"1" => array ("������Ա", "fd_mt_tester", "TXT" ), 
	"2" => array ("APP����", "fd_appmnu_name", "TXT" ),
	"3" => array ("����ʱ��", "fd_mt_date", "TXT" ),
	"4" => array ("ˢ������", "fd_mt_testcount", "TXT" ),
	"5" => array ("�ɹ�����", "fd_mt_yescount", "TXT" ),
	"6" => array ("Ʒ��", "fd_mobilebrand_name", "TXT" ),
	"7" => array ("������", "fd_mobile_name", "TXT" )
	);
}

class fd_appmnu_name extends browsefield {
	var $bwfd_fdname = "fd_appmnu_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "APP����"; // �ֶα���
}
class fd_mt_tester extends browsefield {
	var $bwfd_fdname = "fd_mt_tester"; // ���ݿ����ֶ�����
	var $bwfd_title = "������Ա"; // �ֶα���
}
class fd_mt_date extends browsefield {
	var $bwfd_fdname = "fd_mt_date"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_mobile_name extends browsefield {
	var $bwfd_fdname = "fd_mobile_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
class fd_mobilebrand_name extends browsefield {
	var $bwfd_fdname = "fd_mobilebrand_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "Ʒ��"; // �ֶα���
}
class fd_mt_testcount extends browsefield {
	var $bwfd_fdname = "fd_mt_testcount"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ������"; // �ֶα���
}
class fd_mt_yescount extends browsefield {
	var $bwfd_fdname = "fd_mt_yescount"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ɹ�����"; // �ֶα���
}
class parents extends browsefield {
	var $bwfd_fdname = "parents"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ɹ���"; // �ֶα���
	 function makeshow() {	// ��ֵתΪ��ʾֵ
			$this->bwfd_show = ($this->bwfd_value+0)."%";
			
		      return $this->bwfd_show ;
  	    }
}

if (empty ( $order )) {
	$order = "fd_mt_id";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_mobiletest_b_bu = new tb_mobiletest_b ( );
$tb_mobiletest_b_bu->browse_skin = $loginskin;
$tb_mobiletest_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_mobiletest_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_mobiletest_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��

$db = new DB_test ;
global $fd_mt_testcount,$fd_mt_yescount,$parents; 

if(!empty($tb_mobiletest_b_bu->browse_querywhere)){
  $query_str = "where ";
}
  $td_count = 0;               
$query = "select fd_mt_testcount,fd_mt_yescount,(fd_mt_yescount/fd_mt_testcount)*100 as parents
          from tb_mobiletest ".$query_str.$tb_mobiletest_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
	$td_count++;
     $fd_mt_testcount += $db->f(fd_mt_testcount);   
     $fd_mt_yescount += $db->f(fd_mt_yescount);   
     $parents += $db->f(parents);   
    
}
$parents = sprintf("%1\$.2f",($fd_mt_yescount/$fd_mt_testcount)*100+0)."%";
$tb_mobiletest_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
