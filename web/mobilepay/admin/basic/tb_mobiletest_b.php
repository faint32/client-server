<?
$thismenucode = "7n004";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");

class tb_mobiletest_b extends browse {
	//var $prgnoware = array ("刷卡器管理", "到帐周期设置" );
	var $prgnoware = array ("辅助功能", "机型测试报告" );
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
 	 
	var $browse_find = array (// 查询条件
	"0" => array ("编号", "fd_mt_id", "TXT" ), 
	"1" => array ("测试人员", "fd_mt_tester", "TXT" ), 
	"2" => array ("APP功能", "fd_appmnu_name", "TXT" ),
	"3" => array ("测试时间", "fd_mt_date", "TXT" ),
	"4" => array ("刷卡次数", "fd_mt_testcount", "TXT" ),
	"5" => array ("成功次数", "fd_mt_yescount", "TXT" ),
	"6" => array ("品牌", "fd_mobilebrand_name", "TXT" ),
	"7" => array ("机子名", "fd_mobile_name", "TXT" )
	);
}

class fd_appmnu_name extends browsefield {
	var $bwfd_fdname = "fd_appmnu_name"; // 数据库中字段名称
	var $bwfd_title = "APP功能"; // 字段标题
}
class fd_mt_tester extends browsefield {
	var $bwfd_fdname = "fd_mt_tester"; // 数据库中字段名称
	var $bwfd_title = "测试人员"; // 字段标题
}
class fd_mt_date extends browsefield {
	var $bwfd_fdname = "fd_mt_date"; // 数据库中字段名称
	var $bwfd_title = "测试日期"; // 字段标题
}
class fd_mobile_name extends browsefield {
	var $bwfd_fdname = "fd_mobile_name"; // 数据库中字段名称
	var $bwfd_title = "机子名"; // 字段标题
}
class fd_mobilebrand_name extends browsefield {
	var $bwfd_fdname = "fd_mobilebrand_name"; // 数据库中字段名称
	var $bwfd_title = "品牌"; // 字段标题
}
class fd_mt_testcount extends browsefield {
	var $bwfd_fdname = "fd_mt_testcount"; // 数据库中字段名称
	var $bwfd_title = "刷卡次数"; // 字段标题
}
class fd_mt_yescount extends browsefield {
	var $bwfd_fdname = "fd_mt_yescount"; // 数据库中字段名称
	var $bwfd_title = "成功次数"; // 字段标题
}
class parents extends browsefield {
	var $bwfd_fdname = "parents"; // 数据库中字段名称
	var $bwfd_title = "成功率"; // 字段标题
	 function makeshow() {	// 将值转为显示值
			$this->bwfd_show = ($this->bwfd_value+0)."%";
			
		      return $this->bwfd_show ;
  	    }
}

if (empty ( $order )) {
	$order = "fd_mt_id";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_mobiletest_b_bu = new tb_mobiletest_b ( );
$tb_mobiletest_b_bu->browse_skin = $loginskin;
$tb_mobiletest_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_mobiletest_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_mobiletest_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限

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
