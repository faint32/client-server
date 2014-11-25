<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_statomer_b extends findbrowse{
	 var $prgname = "选择员工" ;

	 var $brow_key = "fd_sta_id";
	 var $brow_queryselect = "select * from tb_staffer 
                            left join tb_dept on fd_dept_id = fd_sta_deptid
                            left join tb_jobs on fd_jobs_id = fd_sta_duty
	                         ";
	 var $brow_field = array("fd_sta_stano","fd_sta_name","fd_dept_name","fd_jobs_name");
	 
	 var $brow_querywhere = "fd_sta_dimission = 1 and fd_sta_type !=4  " ;
	
	 
   	 var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_sta_id",
   			    "1" => "fd_sta_stano",
   			    "2" => "fd_sta_name",
   			    "3" => "fd_dept_name",
   			    "4" => "fd_jobs_name",
			    	"5" => "fd_sta_sex" ,
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("编号", "fd_sta_stano","TXT"),
			  "1" => array("名称", "fd_sta_name","TXT"),
			 );
}

class fd_sta_stano extends findbrowsefield {
        var $bwfd_fdname = "fd_sta_stano";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_sta_name extends findbrowsefield {
        var $bwfd_fdname = "fd_sta_name";	// 数据库中字段名称
        var $bwfd_title = "名称";	// 字段标题
}

class fd_dept_name extends findbrowsefield {
        var $bwfd_fdname = "fd_dept_name";	// 数据库中字段名称
        var $bwfd_title = "部门";	// 字段标题
}

class fd_jobs_name extends findbrowsefield {
        var $bwfd_fdname = "fd_jobs_name";	// 数据库中字段名称
        var $bwfd_title = "职位";	// 字段标题
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_statomer_bu = new tb_statomer_b ;
$tb_statomer_bu->brow_skin = $loginskin ;
$tb_statomer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>