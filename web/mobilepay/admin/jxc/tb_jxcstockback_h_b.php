<?
$thismenucode = "2k214";
require ("../include/common.inc.php");
//require ("../include/browse.inc.php");
require ("../include/have_collect_browse.inc.php");


class tb_paycardstockback_b extends browse {
	 var $prgnoware    = array("刷卡器购销","入库退货历史");
	 var $prgnowareurl =  array("","",);
	 
	 var $browse_key = "fd_stock_id";
	 
	 var $browse_queryselect = "select fd_stock_id , fd_stock_no , fd_stock_suppno , fd_stock_suppname , fd_stock_date 
	                           ,fd_stock_allmoney,fd_stock_allquantity,
	                            fd_stock_ldr,fd_stock_dealwithman
	                            from tb_paycardstockback
	                            ";
                                 
	 function docollect(){  //汇总函数
	 	  $str_querysql = $this->browse_collectquery.$this->browse_querywhere;
      $this->db->query($str_querysql);
    	if($this->db->nf()){
    		$this->db->next_record();
    		$collectmoney = $this->db->f(collectmoney);
    		$collectquantity = $this->db->f(collectquantity);
    		
    		$collectmoney = number_format($collectmoney, 2, ".", ",");
    		$collectquantity = number_format($collectquantity, 4, ".", ",");
    	}else{
    	  $collectmoney = 0;
    	  $collectquantity=0;
      }
      $this->browse_collectdata = "退货总金额为：".$collectmoney."&nbsp;&nbsp;&nbsp;进货总数为：".$collectquantity;
   }
   
	 var $browse_field = array("fd_stock_no","fd_stock_suppno","fd_stock_suppname","fd_stock_date","fd_stock_allmoney","fd_stock_allquantity","fd_stock_ldr","fd_stock_dealwithman");
 	 var $browse_find = array(		// 查询条件
				"0" => array("单据编号"      ,   "fd_stock_no"         ,"TXT"),
				"1" => array("供应商编号"    ,   "fd_stock_suppno"     ,"TXT"),
				"2" => array("供应商名称"    ,   "fd_stock_suppname"   ,"TXT"), 
				"3" => array("单据日期"      ,   "fd_stock_date"       ,"TXT"), 

				);

}

class fd_stock_no extends browsefield {
        var $bwfd_fdname = "fd_stock_no";	// 数据库中字段名称
        var $bwfd_title = "单据编号";	// 字段标题
}

class fd_stock_suppno extends browsefield {
        var $bwfd_fdname = "fd_stock_suppno";	// 数据库中字段名称
        var $bwfd_title = "供应商编号";	// 字段标题
}

class fd_stock_suppname extends browsefield {
        var $bwfd_fdname = "fd_stock_suppname";	// 数据库中字段名称
        var $bwfd_title = "供应商名称";	// 字段标题
}


class fd_stock_date extends browsefield {
        var $bwfd_fdname = "fd_stock_date";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}



class fd_stock_allmoney extends browsefield {
	var $bwfd_fdname = "fd_stock_allmoney"; // 数据库中字段名称
	var $bwfd_title = "总金额"; // 字段标题
	var $bwfd_align = "right";
}


class fd_stock_allquantity extends browsefield {
	var $bwfd_fdname = "fd_stock_allquantity"; // 数据库中字段名称
	var $bwfd_title = "总数量"; // 字段标题
	var $bwfd_align = "right";
}
class fd_stock_ldr  extends browsefield {
	var $bwfd_fdname = "fd_stock_ldr"; // 数据库中字段名称
	var $bwfd_title = "录单人"; // 字段标题
}

class fd_stock_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_stock_dealwithman"; // 数据库中字段名称
	var $bwfd_title = "经手人"; // 字段标题
}





// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_stock_id") 
   			    );
   var $bwlk_prgname = "stockbackview.php?listid=";
   var $bwlk_title ="明细帐";  
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

//默认根据单据日期排序
if(empty($order)){
	$order = "fd_stock_date";
	$upordown = "desc";
}

$tb_paycardstockback_bu = new tb_paycardstockback_b ;
$tb_paycardstockback_bu->browse_skin = $loginskin ;
$tb_paycardstockback_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_paycardstockback_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_paycardstockback_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

if($thismenuqx[2])
{
	$tb_paycardstockback_bu->browse_link  = array("lk_view0");
}

$tb_paycardstockback_bu->browse_querywhere .= " fd_stock_state = 9 ";

$tb_paycardstockback_bu->browse_collectquery = "select sum(fd_stock_allmoney) as collectmoney , 
                                     sum(fd_stock_allquantity) as collectquantity from tb_paycardstockback
	                                   ";

$tb_paycardstockback_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
