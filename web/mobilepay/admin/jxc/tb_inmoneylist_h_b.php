<?
$thismenucode = "2k220";
require ("../include/common.inc.php");
//require ("../include/browse.inc.php");
require ("../include/have_collect_browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_inmoneylist_b extends browse 
{
	 var $prgnoware    = array("刷卡器购销","收款历史");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_inmylt_id";
	 
	 var $browse_queryselect = "select * from tb_inmoneylist
	                            left join tb_account  on fd_account_id   = fd_inmylt_accountid 
	                            ";
	 
	 var $browse_collectquery = "select sum(fd_inmylt_money) as collectmoney from tb_inmoneylist  "; 
	 
	 function docollect(){  //汇总函数
      $str_querysql = $this->browse_collectquery.$this->browse_querywhere." and fd_inmylt_kickback =0";
      $this->db->query($str_querysql);
    	if($this->db->nf()){
    		$this->db->next_record();
    		$collectmoney = $this->db->f(collectmoney);
    		$collectmoney = number_format($collectmoney, 2, ".", ",");
    	}else{
    	  $collectmoney = 0;
      }
      $this->browse_collectdata = "收款汇总金额为：".$collectmoney;
   }                   
	                           

	 var $browse_field = array("fd_inmylt_no","fd_inmylt_date","fd_inmylt_clientno","fd_inmylt_clientname","fd_inmylt_dealwithman","fd_inmylt_money","fd_inmylt_kickback");
 	 var $browse_find = array(		// 查询条件
				"0" => array("单据编号"    , "fd_inmylt_no"          ,"TXT"),				
				"1" => array("单据日期"    , "fd_inmylt_date"        ,"TXT"), 
				"2" => array("收款人"      , "fd_inmylt_dealwithman" ,"TXT"),
				//"3" => array("帐户名称"    , "fd_account_name"       ,"TXT"),
				"3" => array("往来单位"    , "fd_inmylt_clientname"  ,"TXT"),
				"4" => array("收款金额"    , "fd_inmylt_money"       ,"TXT")
				);
}

class fd_inmylt_no extends browsefield {
        var $bwfd_fdname = "fd_inmylt_no";	// 数据库中字段名称
        var $bwfd_title = "单据编号";	// 字段标题
}

class fd_inmylt_clientno extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientno";	// 数据库中字段名称
        var $bwfd_title = "往来单位编号";	// 字段标题
}

class fd_inmylt_clientname extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientname";	// 数据库中字段名称
        var $bwfd_title = "往来单位名称";	// 字段标题
}

class fd_inmylt_date extends browsefield {
        var $bwfd_fdname = "fd_inmylt_date";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}

class fd_inmylt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_inmylt_dealwithman";	// 数据库中字段名称
        var $bwfd_title = "收款人";	// 字段标题
}

/*class fd_account_name extends browsefield {
        var $bwfd_fdname = "fd_account_name";	// 数据库中字段名称
        var $bwfd_title = "帐户名称";	// 字段标题
}*/

class fd_inmylt_money extends browsefield {
        var $bwfd_fdname = "fd_inmylt_money";	// 数据库中字段名称
        var $bwfd_title = "收款金额";	// 字段标题
        var $bwfd_format = "num";
}

class fd_inmylt_kickback extends browsefield {
        var $bwfd_fdname = "fd_inmylt_kickback";	// 数据库中字段名称
        var $bwfd_title = "状态";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
          $showvalue = $this->bwfd_value ;
          switch($showvalue){
          	case "0":
          	   $showvalue = "";
          	   break;
          	case "1":
          	   $showvalue = "<font color='#FF0000'>被冲单据</font>";
          	   $this->bwfd_align  = "center";
          	   break;
          	case "2":
          	   $showvalue = "<font color='#FF0000'>红字冲销</font>";
          	   $this->bwfd_align  = "center";
          	   break;
          }
          
          return $showvalue ;
        }
}

// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_inmylt_id","")
   			    );  
   var $bwlk_title ="明细帐";	// link标题
   var $bwlk_prgname = "inmoneylist_view.php?listid=";	// 链接程序
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_inmylt_date";
	$upordown = "desc";
}

$tb_inmoneylist_b_bu = new tb_inmoneylist_b ;


$tb_inmoneylist_b_bu->browse_skin = $loginskin ;
$tb_inmoneylist_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_inmoneylist_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_inmoneylist_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_inmoneylist_b_bu->browse_querywhere = "  fd_inmylt_state = '9' ";

if($thismenuqx[2])
{
	$tb_inmoneylist_b_bu->browse_link  = array("lk_view0");
}
$tb_inmoneylist_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
