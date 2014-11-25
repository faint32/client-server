<?
$thismenucode = "2k512";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("基础设置", "交易公户设置");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_sdcr_id";
	 
	 //var $browse_delsql = "delete from tb_coupon where fd_sdcr_id = '%s'" ;
	 var $browse_queryselect = "select * from tb_sendcenter
							left join tb_provinces on fd_provinces_code=fd_sdcr_provcode	
							left join tb_city on fd_city_code=fd_sdcr_citycode	
								";
	 var $browse_edit = "sendcenter.php?listid=" ;
	 var $browse_new = "sendcenter.php";
   var $browse_editname = "修改";
    
   var $browse_defaultorder = " fd_sdcr_id asc
                              "; 
    
	 var $browse_field = array("fd_sdcr_id","fd_provinces_name","fd_city_name","fd_sdcr_name","fd_sdcr_active","fd_sdcr_merid","fd_sdcr_payfee","fd_sdcr_agentfee");
 	 var $browse_find = array(		// 查询条件
				"0" => array("所属省份", "fd_provinces_name","TXT"),
				"1" => array("所属城市", "fd_city_name","TXT"),
				"2" => array("银联商户号", "fd_sdcr_merid","TXT"),
				"3" => array("银联商户密钥", "fd_sdcr_securitykey","TXT"),
				);
	 
}

class fd_sdcr_id extends browsefield {
        var $bwfd_fdname = "fd_sdcr_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_provinces_name extends browsefield {
        var $bwfd_fdname = "fd_provinces_name";	// 数据库中字段名称
        var $bwfd_title = "所属省份";	// 字段标题
}

class fd_city_name extends browsefield {
        var $bwfd_fdname = "fd_city_name";	// 数据库中字段名称
        var $bwfd_title = "所属城市";	// 字段标题
}
class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// 数据库中字段名称
        var $bwfd_title = "公司名";	// 字段标题
}

class fd_sdcr_active extends browsefield {
        var $bwfd_fdname = "fd_sdcr_active";	// 数据库中字段名称
        var $bwfd_title = "是否激活";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "未激活";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "激活";
        		     break; 						
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_sdcr_merid extends browsefield {
        var $bwfd_fdname = "fd_sdcr_merid";	// 数据库中字段名称
        var $bwfd_title = "银联商户号";	// 字段标题
}

class fd_sdcr_payfee extends browsefield {
        var $bwfd_fdname = "fd_sdcr_payfee";	// 数据库中字段名称
        var $bwfd_title = "银联支付收明盛手续费";	// 字段标题
}
class fd_sdcr_agentfee extends browsefield {
        var $bwfd_fdname = "fd_sdcr_agentfee";	// 数据库中字段名称
        var $bwfd_title = "资金代付收取手续费";	// 字段标题
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = 1;  // 删除权限
$tb_feedback_b_bu->browse_addqx =1;  // 新增权限
$tb_feedback_b_bu->browse_editqx =1;  // 编辑权限
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

