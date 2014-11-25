<?
$thismenucode = "2k114";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bankcard_b extends browse {
	var $prgnoware = array ("基本设置","可刷银行卡" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_bankcard_id";
	
	var $browse_queryselect = "select * from tb_bankcard 
								left join tb_bank on  fd_bank_id = fd_bankcard_bankid
								left join tb_author on fd_bankcard_authorid=fd_author_id ";
	
	var $browse_edit = "bankcard.php?listid=";
	var $browse_new = "bankcard.php";
	var $browse_delsql = "delete from tb_bankcard where fd_bankcard_id = '%s'" ;
	//var $browse_link  = array("lk_view0");
	
	var $browse_field = array ("fd_bankcard_id", "fd_bankcard_key", "fd_author_truename","fd_bank_name","fd_bankcard_active","fd_bankcard_isnew" );
	var $browse_find = array (// 查询条件
"0" => array ("编号", "fd_bankcard_id", "TXT" ), "1" => array ("银行名称", "fd_bankcard_name", "TXT" ) );
}

class fd_bankcard_id extends browsefield {
	var $bwfd_fdname = "fd_bankcard_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}
class fd_bankcard_key extends browsefield {
	var $bwfd_fdname = "fd_bankcard_key"; // 数据库中字段名称
	var $bwfd_title = "设备号"; // 字段标题
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // 数据库中字段名称
	var $bwfd_title = "用户名"; // 字段标题
}
class fd_bank_name extends browsefield {
	var $bwfd_fdname = "fd_bank_name"; // 数据库中字段名称
	var $bwfd_title = "所属银行"; // 字段标题
}
class fd_bankcard_active extends browsefield {
	var $bwfd_fdname = "fd_bankcard_active"; // 数据库中字段名称
	var $bwfd_title = "状态"; // 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "没激活";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "已激活";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }	
}
class fd_bankcard_isnew extends browsefield {
	var $bwfd_fdname = "fd_bankcard_isnew"; // 数据库中字段名称
	var $bwfd_title = "是否新卡"; // 字段标题
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
// 链接定义





class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_bankcard_id","")
   			    );  
   var $bwlk_title ="银行卡管理";	// link标题
   var $bwlk_prgname = "bankcardset.php?listid=";	// 链接程序
}


if (empty ( $order )) {
	$order = "fd_bankcard_id";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_bankcard_b_bu = new tb_bankcard_b ( );
$tb_bankcard_b_bu->browse_skin = $loginskin;
$tb_bankcard_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_bankcard_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_bankcard_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_bankcard_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
