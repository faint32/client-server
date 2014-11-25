<?
$thismenucode = "2k115";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_holiday_b extends browse 
{
	 var $prgnoware    = array("基本设置","公众假期管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_holiday_id";
	 
	 var $browse_queryselect = "select * from tb_holiday 
	 					left join tb_staffer on fd_sta_id = fd_holiday_staid";
	var $browse_new    = "holiday.php" ;		
	var $browse_edit    = "holiday.php?listid=" ;
	  var $browse_delsql = "delete from tb_holiday where fd_holiday_id = '%s'" ;
   
    //var $browse_outtoexcel ="excelwriter_holiday.php";
	
	 var $browse_inputfile ="input_holiday.php";
	 
	 var $browse_fieldname =  array("假期名称","公众假期年份","公众假期日期");
	 var $browse_fieldval  =  array("fd_holiday_name","fd_holiday_year","fd_holiday_date");
     var $browse_ischeck  =  array("1","1","1","1"); 
	 
   var $browse_field = array("fd_holiday_id","fd_holiday_name","fd_holiday_year","fd_holiday_date","fd_holiday_active","fd_holiday_datetime","fd_sta_name" );
   
 	 var $browse_find = array(		// 查询条件
				"0" => array("假期名称", "fd_holiday_name","TXT"   ),
				"1" => array("公众假期年份", "fd_holiday_year","TXT"   ),
				"2" => array("公众假期日期", "fd_holiday_date","TXT"   )
				);
}

class fd_holiday_id extends browsefield {
        var $bwfd_fdname = "fd_holiday_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_holiday_name extends browsefield {
        var $bwfd_fdname = "fd_holiday_name";	// 数据库中字段名称
        var $bwfd_title = "假期名称";	// 字段标题
}

class fd_holiday_year extends browsefield {
        var $bwfd_fdname = "fd_holiday_year";	// 数据库中字段名称
        var $bwfd_title = "公众假期年份";	// 字段标题
}

class fd_holiday_date extends browsefield {
        var $bwfd_fdname = "fd_holiday_date";	// 数据库中字段名称
        var $bwfd_title = "公众假期日期";	// 字段标题
       
}


class fd_holiday_active extends browsefield {
        var $bwfd_fdname = "fd_holiday_active";	// 数据库中字段名称
        var $bwfd_title = "是否停用";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {      		
        		case "1":
        		    $this->bwfd_show = "正常";
        		     break;
        		case "0":
        		    $this->bwfd_show = "<font color='#ff0000'>已取消</font>";
        		     break; 
        		default:
        		     $this->bwfd_show = "";
        		     break;     		 								
          }
		      return $this->bwfd_show ;
  	    }
}

class fd_holiday_datetime extends browsefield {
        var $bwfd_fdname = "fd_holiday_datetime";	// 数据库中字段名称
        var $bwfd_title = "最新修改时间";	// 字段标题
       
}
class fd_sta_name extends browsefield {
        var $bwfd_fdname = "fd_sta_name";	// 数据库中字段名称
        var $bwfd_title = "导入人";	// 字段标题
       
}

class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_holiday_id") 
   			    );
   var $bwlk_prgname = "monthtable_view.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看每年所有假期</span>";  
   
} 


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_holiday_b_bu = new tb_holiday_b ;
$tb_holiday_b_bu->browse_skin = $loginskin ;
$tb_holiday_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_holiday_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_holiday_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
// $tb_holiday_b_bu->browse_querywhere = " fd_holiday_organid='$loginorganid'"; 
$tb_holiday_b_bu->browse_link  = array("lk_view0"); 
$tb_holiday_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
