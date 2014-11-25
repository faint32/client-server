<?
$thismenucode = "2k109";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_staffer_b extends browse {
	 var $prgnoware    = array("基本设置","经手人管理");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_sta_id";
	 
	 var $browse_queryselect = "select * from tb_staffer
	                            left join tb_dept on fd_dept_id = fd_sta_deptid";
	 var $browse_delsql = "delete from tb_staffer where fd_sta_id = '%s'" ;
	 var $browse_new = "staffer.php?status=1" ;
	 var $browse_edit = "staffer.php?status=1&id=" ;	 
	 
   var $browse_querywhere = "fd_sta_dimission = 1 and fd_sta_type != 4 " ;
   

	 var $browse_fieldname =  array("编号","姓名","所属部门","身份证号","手机号码","入职时间","地址","备注",'ID');
     var $browse_fieldval =  array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_sta_idcard","fd_sta_mobile","fd_sta_jobtime","fd_sta_address","fd_sta_memo","fd_sta_id");
	 var $browse_ischeck  =  array("1","1","1","1","1","1","1","1","1","1");
     
	 var $browse_link  = array("lk_view0");
   
	 var $browse_field = array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_sta_mobile","fd_sta_jobtime");
 	 var $browse_find = array(		// 查询条件
				"0" => array("职员编号", "fd_sta_stano","TXT"),
				"1" => array("所属部门", "fd_dept_name","TXT"),  
				"2" => array("姓名", "fd_sta_name","TXT"), 
				"4" => array("出生地", "fd_sta_homeplace","TXT"),
				"5" => array("出生日期", "fd_sta_birthday","TXT"),
				"3" => array("手机号码", "fd_sta_mobile","TXT")
				);
}

class fd_sta_stano extends browsefield {
        var $bwfd_fdname = "fd_sta_stano";	// 数据库中字段名称
        var $bwfd_title = "职员编号";	// 字段标题
}

class fd_sta_name extends browsefield {
        var $bwfd_fdname = "fd_sta_name";	// 数据库中字段名称
        var $bwfd_title = "姓名";	// 字段标题
}



class fd_dept_name extends browsefield {
        var $bwfd_fdname = "fd_dept_name";	// 数据库中字段名称
        var $bwfd_title = "所属部门";	// 字段标题
}


class fd_sta_mobile extends browsefield {
        var $bwfd_fdname = "fd_sta_mobile";	// 数据库中字段名称
        var $bwfd_title = "手机号码";	// 字段标题
}

class fd_sta_jobtime extends browsefield {
        var $bwfd_fdname = "fd_sta_jobtime";	// 数据库中字段名称
        var $bwfd_title = "入职时间";	// 字段标题
}


// 链接定义





class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_sta_id","")
   			    );  
   var $bwlk_title ="离职设置";	// link标题
   var $bwlk_prgname = "dimission_set.php?staid=";	// 链接程序
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_staffer_bu = new tb_staffer_b ;
$tb_staffer_bu->browse_skin = $loginskin ;
$tb_staffer_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_staffer_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_staffer_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_staffer_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
