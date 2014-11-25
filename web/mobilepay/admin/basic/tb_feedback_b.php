<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("辅助功能","意见反馈");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_feedback_id";
	 

	 var $browse_queryselect = "select * from tb_feedback left join tb_author on fd_author_id = fd_feedback_authorid ";
	 
	 var $browse_delsql = "delete from tb_feedback where fd_feedback_id = '%s'" ;
	// var $browse_new = "feedback.php" ;
	 var $browse_edit = "feedback.php?listid=" ;
	 var $browse_new ="feedback.php";
   
	 var $browse_field = array("fd_author_truename","fd_feedback_content","fd_feedback_linkman","fd_feedback_datetime","fd_feedback_isread","fd_feedback_isreadtime","fd_feedback_isreply","fd_feedback_isreplytime","fd_feedback_isreplycontent");
 	 var $browse_find = array(		// 查询条件
				"0" => array("用户名", "fd_author_truename","TXT"),
				"1" => array("反馈时间", "fd_feedback_datetime","TXT"),
				"2" => array("联系人信息", "fd_feedback_linkman","TXT"),
				"3" => array("最近阅读时间", "fd_feedback_isreadtime","TXT"),
				"4" => array("回复时间", "fd_feedback_isreplytime","TXT")
				);
	 
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}

class fd_feedback_content extends browsefield {
        var $bwfd_fdname = "fd_feedback_content";	// 数据库中字段名称
        var $bwfd_title = "反馈意见";	// 字段标题
}
class fd_feedback_linkman extends browsefield {
        var $bwfd_fdname = "fd_feedback_linkman";	// 数据库中字段名称
        var $bwfd_title = "联系人信息";	// 字段标题
}
class fd_feedback_datetime extends browsefield {
        var $bwfd_fdname = "fd_feedback_datetime";	// 数据库中字段名称
        var $bwfd_title = "反馈时间";	// 字段标题
}
class fd_feedback_isread extends browsefield {
        var $bwfd_fdname = "fd_feedback_isread";	// 数据库中字段名称
        var $bwfd_title = "是否读过";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
				default:
					   $this->bwfd_show = "否";
        		     break;     		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_feedback_isreadtime extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreadtime";	// 数据库中字段名称
        var $bwfd_title = "最近阅读时间";	// 字段标题
}
class fd_feedback_isreply extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreply";	// 数据库中字段名称
        var $bwfd_title = "否是回复";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
				default:
					   $this->bwfd_show = "否";
        		     break;         		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_feedback_isreplytime extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreplytime";	// 数据库中字段名称
        var $bwfd_title = "回复时间";	// 字段标题
}
class fd_feedback_isreplycontent extends browsefield {
        var $bwfd_fdname = "fd_feedback_isreplycontent";	// 数据库中字段名称
        var $bwfd_title = "回复内容";	// 字段标题
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

