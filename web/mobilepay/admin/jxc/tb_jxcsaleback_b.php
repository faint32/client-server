<?php
$thismenucode = "2k215";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_salelist_b extends browse {
  var $prgnoware    = array("刷卡器购销","销售退货");
  var $prgnowareurl =  array("","",);

  var $browse_key = "fd_selt_id";

  var $browse_queryselect = "select fd_selt_id , fd_selt_no , fd_selt_cusno , fd_selt_cusname , fd_selt_date ,fd_selt_state, fd_selt_ldr ,fd_selt_dealwithman,fd_selt_skfs,fd_selt_allquantity from tb_salelistback";
  var $browse_delsql = "delete from tb_salelistback where fd_selt_id = '%s'" ;
  var $browse_new   = "jxcsaleback.php" ;
  var $browse_edit  = "jxcsaleback.php?listid=" ;
  var $browse_relatingdelsql = array(
    "0" => "delete from tb_salelistbackdetail where fd_stdetail_seltid = '%s'",
    "1" => "delete from tb_salelist_tmp where fd_tmpsale_seltid = '%s' and fd_tmpsale_type='saleback' "
  );

  var $browse_field = array("fd_selt_state","fd_selt_no","fd_selt_cusno","fd_selt_cusname","fd_selt_allquantity","fd_selt_date","fd_selt_skfs","fd_selt_ldr","fd_selt_dealwithman");
  var $browse_find = array(		// 查询条件
    "0" => array("单据编号"      ,   "fd_selt_no"         ,"TXT"),
    "1" => array("客户编号"      ,   "fd_selt_cusno"      ,"TXT"),
    "2" => array("客户名称"      ,   "fd_selt_cusname"    ,"TXT"), 
    "3" => array("单据日期"      ,   "fd_selt_date"       ,"TXT"), 
  );
  var $browse_state = array("fd_selt_state");

  var $browse_defaultorder = " CASE WHEN fd_selt_state = '0'
  THEN 1
  WHEN fd_selt_state = '1'
  THEN 2
  END,fd_selt_date desc
  ";

  function makeedit($key) {	// 生成编辑连接
    $returnval = "" ;
    switch($this->arr_spilthfield[0])
    {//判断记录是在那一个状态下，在那一个状态下就使用那一个连接
      case "0":
        if(!empty($this->browse_edit)){
          $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
        }
        break;
      case "1":
        $returnval = "<a href=javascript:linkurl(\"saleback_sq_view.php?backstate=0&listid=".$key."\")>查看</a>";
        break;
    }
    return $returnval;
  }
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
        var $bwfd_title = "退货数量";	// 字段标题
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
      default:
        $this->bwfd_show = "";
        break;
    }
    return $this->bwfd_show;
  }
}
class fd_selt_state extends browsefield {
  var $bwfd_fdname = "fd_selt_state";// 数据库中字段名称
  var $bwfd_title = "状态";// 字段标题

  function makeshow() {// 将值转为显示值
    switch($this->bwfd_value){
      case "0":
        $this->bwfd_show = "暂存";
        break;
      case "1":
        $this->bwfd_show = "等待审批";
        break;
    }
    return $this->bwfd_show;
  }
}

class lk_view0 extends browselink {
  var $bwlk_fdname = array(// 所需数据库中字段名称
    "0" => array("fd_selt_id")
  );
  var $bwlk_prgname = "salebackprint.php?listid=";
  var $bwlk_title ="打印";
}

if(isset($pagerows))
{// 显示列数
  $pagerows = min($pagerows,100) ;// 最大显示列数不超过100
}
else
{
  $pagerows = $loginbrowline ;
}

$tb_salelist_bu = new tb_salelist_b ;
$tb_salelist_bu->browse_skin = $loginskin ;
$tb_salelist_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_salelist_bu->browse_addqx = $thismenuqx[2];  // 新增权限
$tb_salelist_bu->browse_editqx = $thismenuqx[1];  // 编辑权限

if($thismenuqx[2])
{
  $tb_salelist_bu->browse_link  = array("lk_view0");
}

//显示有权限查看的机构资料

$tb_salelist_bu->browse_querywhere .= "(fd_selt_state = 0 or fd_selt_state = 1) ";

//-------------------------------------

$tb_salelist_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
