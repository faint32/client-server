<?
$thismenucode = "10n010";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../third_api/readshopname.php");

class tb_author_b extends browse 
{
	 var $prgnoware    = array("商户","已绑定改套餐商户");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_author_id";
	 
	 var $browse_queryselect = "select * from tb_author ";

	 var $browse_field = array("fd_author_id","fd_author_username","fd_author_idcard","fd_author_truename","fd_author_mobile","fd_author_email","fd_author_regtime","fd_author_state","fd_author_isstop","fd_author_shopid");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_author_listid","TXT"),
				"1" => array("用户名" , "fd_author_username","TXT"),
				"2" => array("真实姓名" , "fd_author_truename","TXT"),
				"3" => array("手机号码" , "fd_author_mobile","TXT"),
				"4" => array("身份证号码" , "fd_author_idcard","TXT")
				); 
}

class  fd_author_id  extends browsefield {
        var $bwfd_fdname = "fd_author_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_author_username  extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}
class fd_author_idcard  extends browsefield {
        var $bwfd_fdname = "fd_author_idcard";	// 数据库中字段名称
        var $bwfd_title = "身份证";	// 字段标题
}
class fd_author_password  extends browsefield {
        var $bwfd_fdname = "fd_author_password";	// 数据库中字段名称
        var $bwfd_title = "密码";	// 字段标题
}
class fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "真实名";	// 字段标题
}
class fd_author_mobile  extends browsefield {
        var $bwfd_fdname = "fd_author_mobile";	// 数据库中字段名称
        var $bwfd_title = "手机号码";	// 字段标题
}
class fd_author_email  extends browsefield {
        var $bwfd_fdname = "fd_author_email";	// 数据库中字段名称
        var $bwfd_title = "电子邮箱";	// 字段标题
}

class fd_author_regtime extends browsefield {
        var $bwfd_fdname = "fd_author_regtime";	// 数据库中字段名称
        var $bwfd_title = "注册时间";	// 字段标题
}
class fd_author_state extends browsefield {
        var $bwfd_fdname = "fd_author_state";	// 数据库中字段名称
        var $bwfd_title = "状态";	// 字段标题
		        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "待审核";
        		     break;       		
        		case "9":
        		    $this->bwfd_show = "审核通过";
        		     break;
        		case "-1":
        		    $this->bwfd_show = "已注销";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_isstop extends browsefield {
        var $bwfd_fdname = "fd_author_isstop";	// 数据库中字段名称
        var $bwfd_title = "是否停用";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_shopid extends browsefield {
        var $bwfd_fdname = "fd_author_shopid";	// 数据库中字段名称
        var $bwfd_title = "商户名";	// 字段标题
        function makeshow() { // 将值转为显示值
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getauthorshop ($this->bwfd_value);
		return $this->bwfd_show;
		}
       
}



if(empty($order)){
	$order = "fd_author_id";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_b_bu = new tb_author_b ;
$tb_author_b_bu->browse_skin = $loginskin ;
$tb_author_b_bu->browse_delqx = 1;  // 删除权限
$tb_author_b_bu->browse_addqx = 1;  // 新增权限
$tb_author_b_bu->browse_editqx = 1;  // 编辑权限
if($type=="scdmsetid"){$tb_author_b_bu->browse_querywhere = "fd_author_scdmsetid = '$listid' and fd_author_state = '9'";}
if($type=="payfsetid"){$tb_author_b_bu->browse_querywhere = "fd_author_payfsetid = '$listid' and fd_author_state = '9'";}
$tb_author_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
