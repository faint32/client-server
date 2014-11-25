<?php
$thismenucode = "2k206";
require ("../include/common.inc.php");
//require ("../include/browse.inc.php");
require ("../include/have_collect_browse.inc.php");

class tb_salelist_b extends browse {
  var $prgnoware    = array("刷卡器购销","销售历史");
  var $prgnowareurl =  array("","",);

  var $browse_key = "fd_selt_id";

  var $browse_queryselect = "select fd_selt_id ,fd_selt_state, fd_selt_no , fd_selt_cusno , fd_selt_cusname ,fd_selt_allquantity, fd_selt_date ,fd_selt_ldr,fd_selt_dealwithman, fd_selt_skfs , fd_selt_allmoney , fd_selt_allcost from tb_salelist";

  var $browse_defaultorder = "  fd_selt_no desc";

  function docollect(){  //汇总函数
  $str_querysql = $this->browse_collectquery.$this->browse_querywhere;
  $this->db->query($str_querysql);
  if($this->db->nf())
  {
    $this->db->next_record();
    $collectmoney = $this->db->f(collectmoney);
    $collectcost = $this->db->f(collectcost);
    $collectprofits =$collectmoney-$collectcost;
    $collectmoney = number_format($collectmoney, 2, ".", ",");
    $collectcost = number_format($collectcost, 2, ".", ",");
    $collectprofits = number_format($collectprofits, 2, ".", ",");
  }
  else
  {
    $collectmoney = 0;
    $collectcost=0;
  }
  $this->browse_collectdata = "销售总金额为：".$collectmoney."&nbsp;&nbsp;&nbsp;销售总成本为：".$collectcost."&nbsp;&nbsp;&nbsp;销售总利润为：".$collectprofits;
  }

  var $browse_find = array(		// 查询条件
    "0" => array("单据编号"      ,   "fd_selt_no"          ,"TXT"),
    "1" => array("客户编号"      ,   "fd_selt_cusno"       ,"TXT"),
    "2" => array("客户名称"      ,   "fd_selt_cusname"     ,"TXT"),
    "3" => array("单据日期"      ,   "fd_selt_date"        ,"TXT"),
  );
}

class fd_selt_no extends browsefield {
        var $bwfd_fdname = "fd_selt_no";	// 数据库中字段名称
        var $bwfd_title = "单据编号";	// 字段标题
}

class fd_selt_cusno extends browsefield {
        var $bwfd_fdname = "fd_selt_cusno";	// 数据库中字段名称
        var $bwfd_title = "客户编号";	// 字段标题
}

class fd_selt_cusname extends browsefield {
        var $bwfd_fdname = "fd_selt_cusname";	// 数据库中字段名称
        var $bwfd_title = "客户名称";	// 字段标题
}

class fd_selt_allquantity extends browsefield {
        var $bwfd_fdname = "fd_selt_allquantity";	// 数据库中字段名称
        var $bwfd_title = "销售数量";	// 字段标题
}

class fd_selt_allmoney extends browsefield {
        var $bwfd_fdname = "fd_selt_allmoney";	// 数据库中字段名称
        var $bwfd_title = "销售总额";	// 字段标题
}

class fd_selt_allcost extends browsefield {
        var $bwfd_fdname = "fd_selt_allcost";	// 数据库中字段名称
        var $bwfd_title = "销售总成本";	// 字段标题
}

class fd_selt_date extends browsefield {
        var $bwfd_fdname = "fd_selt_date";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}
class fd_selt_ldr extends browsefield {
        var $bwfd_fdname = "fd_selt_ldr";	// 数据库中字段名称
        var $bwfd_title = "录单人";	// 字段标题
}
class fd_selt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_selt_dealwithman";	// 数据库中字段名称
        var $bwfd_title = "经手人";	// 字段标题
}
class fd_selt_state extends browsefield {
    var $bwfd_fdname = "fd_selt_state";	// 数据库中字段名称
    var $bwfd_title = "状态";	// 字段标题

    function makeshow() {	// 将值转为显示值
        switch($this->bwfd_value){
            case "2":
                $this->bwfd_show = "已发货";
                break;
            case "9":
                $this->bwfd_show = "已收货";
                break;

            default:
                $this->bwfd_show = "";
                break;
        }
        return $this->bwfd_show;
    }
}

class fd_selt_trafficmodel extends browsefield {
        var $bwfd_fdname = "fd_selt_trafficmodel";	// 数据库中字段名称
        var $bwfd_title = "运输方式";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch($this->bwfd_value){
        		case "1":
        		  $this->bwfd_show = "送货";
        		  break;
        		case "2":
        		  $this->bwfd_show = "自提";
        		  break;
        		case "3":
        		  $this->bwfd_show = "代办物流";
        		  break;
        		default:
        		  $this->bwfd_show = "";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

class fd_selt_skfs extends browsefield {
  var $bwfd_fdname = "fd_selt_skfs";	// 数据库中字段名称
  var $bwfd_title = "付款方式";	// 字段标题

  function makeshow() {	// 将值转为显示值
    switch($this->bwfd_value){
      case "1":
        $this->bwfd_show = "现金";
        break;
      case "2":
        $this->bwfd_show = "支票";
        break;
      case "3":
        $this->bwfd_show = "电汇";
        break;
      case "4":
        $this->bwfd_show = "承兑";
        break;
        case "5":
            $this->bwfd_show = "在线支付";
            break;
      }
    return $this->bwfd_show;
  }
}



// 链接定义
class lk_view0 extends browselink {
  var $bwlk_fdname = array(			// 所需数据库中字段名称
    "0" => array("fd_selt_id") 
  );
  var $bwlk_prgname = "salelistview.php?listid=";
  var $bwlk_title ="明细帐";  
}

class lk_view1 extends browselink {
  var $bwlk_fdname = array(      // 所需数据库中字段名称
    "0" => array("fd_selt_id") 
  );
 var $bwlk_prgname = "salebackprint.php?listid=";
  var $bwlk_title ="打印";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}


$db = new DB_test;  
$query = "select * from web_teller where fd_tel_id = '$loginuser' " ;
$db->query($query);
if($db->nf()){
  $db->next_record();
  $logincostqx  = $db->f(fd_tel_costqx)  ;
}

$tb_salelist_bu = new tb_salelist_b ;


  $tb_salelist_bu->browse_field = array("fd_selt_no","fd_selt_cusno","fd_selt_cusname","fd_selt_date","fd_selt_ldr","fd_selt_dealwithman","fd_selt_skfs","fd_selt_allquantity","fd_selt_allmoney","fd_selt_state","fd_selt_allcost");


$tb_salelist_bu->browse_skin = $loginskin ;
$tb_salelist_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_salelist_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_salelist_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_salelist_bu->browse_querywhere .= " fd_selt_state = 9  ";

if($thismenuqx[2])
{
  $tb_salelist_bu->browse_link  = array("lk_view0","lk_view1");
}





$tb_salelist_bu->browse_collectquery = "select sum(fd_selt_allmoney) as collectmoney,sum(fd_selt_allcost) as collectcost  from tb_salelist";
$tb_salelist_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
