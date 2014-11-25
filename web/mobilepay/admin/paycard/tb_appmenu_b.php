<? 
$thismenucode = "2k513";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_author_sp_b extends browse {
	var $prgnoware = array ("基础设置", "APP功能设置" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_appmnu_id";
	
	var $browse_queryselect = "select * from tb_appmenu
	                               left join tb_appmenutype on fd_amtype_id = fd_appmnu_amtypeid";
	var $browse_edit = "appmenu.php?listid=";
	var $browse_new = "appmenu.php";
	var $browse_link = array ("lk_view1" );
	var $browse_delsql = "delete from tb_appmenu where fd_appmnu_id = '%s'";
	 var $browse_relatingdelsql = array(
                                "0" => "delete from tb_appmenu where fd_appmnu_id = '%s'",
								"1"=>"update tb_appmenu set fd_appmnu_version=fd_appmnu_version+0.1"
                                 );
	var $browse_field = array ("fd_appmnu_id","fd_appmnu_order","fd_appmnu_isconst","fd_amtype_name","fd_appmnu_iscusfenrun","fd_appmnu_no","fd_appmnu_version", "fd_appmnu_name","fd_appmnu_url","fd_appmnu_scope", "fd_appmenu_active","fd_appmenu_authorstate","fd_appmnu_istabno");
	var $browse_find = array (// 查询条件
			"0" => array ("版本号", "fd_appmnu_version", "TXT" ),
            "1" => array ("APP设置名称", "fd_appmnu_name", "TXT" ),
        "2" => array ("APP设置图片", "fd_appmnu_pic", "TXT" ), "3" => array ("APP设置目录", "fd_appmnu_order", "TXT" ), "4" => array ("APP设置url", "fd_appmnu_url", "TXT" )
    , "5" => array ("APP设置默认", "fd_appmenu_default", "TXT" ),
        "6" => array ("所属分类", "fd_amtype_name", "TXT" ));

}
class fd_appmnu_id extends browsefield {
	var $bwfd_fdname = "fd_appmnu_id"; // 数据库中字段名称
	var $bwfd_title = "序号"; // 字段标题
}
class fd_appmnu_order extends browsefield {
    var $bwfd_fdname = "fd_appmnu_order"; // 数据库中字段名称
    var $bwfd_title = "排序"; // 字段标题
}
class fd_amtype_name extends browsefield {
    var $bwfd_fdname = "fd_amtype_name"; // 数据库中字段名称
    var $bwfd_title = "所属分类"; // 字段标题
}
class fd_appmnu_no extends browsefield {
	var $bwfd_fdname = "fd_appmnu_no"; // 数据库中字段名称
	var $bwfd_title = "编号
"; // 字段标题
}
class fd_appmnu_version extends browsefield {
	var $bwfd_fdname = "fd_appmnu_version"; // 数据库中字段名称
	var $bwfd_title = "版本号
"; // 字段标题
}
class fd_appmnu_name extends browsefield {
	var $bwfd_fdname = "fd_appmnu_name"; // 数据库中字段名称
	var $bwfd_title = "APP设置名称
"; // 字段标题
}
/*class fd_appmnu_pic extends browsefield {
	var $bwfd_fdname = "fd_appmnu_pic"; // 数据库中字段名称
	var $bwfd_title = "APP设置图片
"; // 字段标题
}
class fd_appmnu_order extends browsefield {
	var $bwfd_fdname = "fd_appmnu_order"; // 数据库中字段名称
	var $bwfd_title = "APP设置目录
"; // 字段标题
}*/
class fd_appmnu_url extends browsefield {
	var $bwfd_fdname = "fd_appmnu_url"; // 数据库中字段名称
	var $bwfd_title = "APP设置url
"; // 字段标题
}
/*class fd_appmenu_default extends browsefield {
	var $bwfd_fdname = "fd_appmenu_default"; // 数据库中字段名称
	var $bwfd_title = "APP设置默认
"; // 字段标题
}*/

class fd_appmnu_isconst extends browsefield {
    var $bwfd_fdname = "fd_appmnu_isconst"; // 数据库中字段名称
    var $bwfd_title = "是否固定功能"; // 字段标题
    function makeshow() {	// 将值转为显示值
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "非";
                break;
            case "1":
                $this->bwfd_show = "<font color='#0000ff'>固定</font>";
                break;
            default:
                $this->bwfd_show = "非";
                break;

        }
        return $this->bwfd_show ;
    }
}
class fd_appmnu_iscusfenrun extends browsefield {
    var $bwfd_fdname = "fd_appmnu_iscusfenrun"; // 数据库中字段名称
    var $bwfd_title = "收益计入代理商分润"; // 字段标题
    function makeshow() {	// 将值转为显示值
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "不计入";
                break;
            case "1":
                $this->bwfd_show = "<font color='#0000ff'>计入</font>";
                break;
            default:
                $this->bwfd_show = "";
                break;

        }
        return $this->bwfd_show ;
    }
}

class fd_appmnu_scope extends browsefield {
	var $bwfd_fdname = "fd_appmnu_scope"; // 数据库中字段名称
	var $bwfd_title = "使用范围"; // 字段标题
       function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "信用卡";
        		     break;       		
        		case "bankcard":
        		    $this->bwfd_show = "储蓄卡";
        		     break;
				case "all":
        		    $this->bwfd_show = "两种卡都可以";
        		     break;	 
        		default:
        			  $this->bwfd_show = "";
        		    break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmenu_active extends browsefield {
	var $bwfd_fdname = "fd_appmenu_active"; // 数据库中字段名称
	var $bwfd_title = "是否激活"; // 字段标题
       function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "未激活";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "已激活";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmenu_authorstate extends browsefield {
	var $bwfd_fdname = "fd_appmenu_authorstate"; // 数据库中字段名称
	var $bwfd_title = "使用用户权限"; // 字段标题
       function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "所有用户";
        		     break;       		
        		case "9":
        		    $this->bwfd_show = "<font color='#00CC00'>认证用户</font>";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}
class fd_appmnu_istabno extends browsefield {
	var $bwfd_fdname = "fd_appmnu_istabno"; // 数据库中字段名称
	var $bwfd_title = "是否显示报表编号"; // 字段标题
       function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {     		
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

// 链接定义
class lk_view1 extends browselink {
	var $bwlk_fdname = array ("0" => array ("fd_appmnu_id") );
	var $bwlk_title = "<font color='#00CC00'>功能接口</font>"; // link标题
	var $bwlk_prgname = "app_interface_b.php?listid="; // 链接程序

}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}
if(empty($order)){
	$order = "fd_appmnu_id";
	$upordown = "asc";
}

$tb_author_sp_b_bu = new tb_author_sp_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_author_sp_b_bu->browse_link = array ("lk_view1" );
//$tb_author_sp_b_bu->browse_querywhere = "fd_appmnu_istabno = 1";

$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
