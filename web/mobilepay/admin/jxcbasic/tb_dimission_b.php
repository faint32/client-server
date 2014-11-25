<?
$thismenucode = "2k110";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_staffer_b extends browse {
	 //var $prgnoware    = array("人事管理","离职员工");
	 var $prgnoware    = array("基本设置","经手人离职管理");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_sta_id";
	 
	 var $browse_queryselect = "select * from tb_staffer
	                           left join tb_dept on fd_dept_id = fd_sta_deptid  ";
	 //var $browse_delsql = "delete from tb_staffer where fd_sta_id = '%s'" ;
	 var $browse_edit = "staffer.php?status=2&id=" ;
	 //var $browse_outtoexcel ="excelwriter_staffer.php";
	 
	 var $browse_querywhere = "fd_sta_dimission = 2" ;
   
   //var $browse_inputfile = "input_staffer.php";
   
	 var $browse_field = array("fd_sta_stano","fd_sta_name","fd_sta_phone","fd_dept_name","fd_sta_mobile","fd_sta_jobtime","fd_sta_dimissiondate");
 	 var $browse_find = array(		// 查询条件
				"0" => array("职员编号", "fd_sta_stano","TXT"),
				"1" => array("所属部门", "fd_dept_name","TXT"),  
				"2" => array("姓名", "fd_sta_name","TXT"), 
				"3" => array("电话", "fd_sta_phone","TXT")
				);
	 

}

class fd_sta_stano extends browsefield {
        var $bwfd_fdname = "fd_sta_stano";	// 数据库中字段名称
        var $bwfd_title = "职员编号";	// 字段标题
}

class fd_dept_name extends browsefield {
        var $bwfd_fdname = "fd_dept_name";	// 数据库中字段名称
        var $bwfd_title = "所属部门";	// 字段标题
}

class fd_sta_name extends browsefield {
        var $bwfd_fdname = "fd_sta_name";	// 数据库中字段名称
        var $bwfd_title = "姓名";	// 字段标题
}

class fd_sta_mobile extends browsefield {
        var $bwfd_fdname = "fd_sta_mobile";	// 数据库中字段名称
        var $bwfd_title = "手机号码";	// 字段标题
}

class fd_sta_jobtime extends browsefield {
        var $bwfd_fdname = "fd_sta_jobtime";	// 数据库中字段名称
        var $bwfd_title = "入职时间";	// 字段标题
}

class fd_sta_dimissiondate extends browsefield {
        var $bwfd_fdname = "fd_sta_dimissiondate";	// 数据库中字段名称
        var $bwfd_title = "离职时间";	// 字段标题
}



class fd_sta_phone extends browsefield {
        var $bwfd_fdname = "fd_sta_phone";	// 数据库中字段名称
        var $bwfd_title = "电话";	// 字段标题
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
