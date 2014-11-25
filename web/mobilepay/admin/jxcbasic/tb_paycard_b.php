<?
$thismenucode = "2k401";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_paycard_b extends browse {
	var $prgnoware = array (
		"刷卡器管理"
	);
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_paycard_id";

	var $browse_queryselect = "select fd_paycard_id, fd_paycard_batches, fd_paycard_key, 
								fd_paycard_active,fd_paycard_activetime,fd_paycard_stockprice,
								fd_paycard_saleprice,fd_cus_name,fd_author_truename,
								concat_ws(',',fd_paycard_isnew,fd_paycard_posstate) as paycardstate,
								fd_product_suppname,
								fd_bank_name,fd_product_name,fd_paycard_scope,fd_paycard_state as paycardwhere
								from tb_paycard
								left join tb_bank on fd_bank_id= fd_paycard_bankid
								left join tb_author on fd_author_id=fd_paycard_authorid
								left join tb_product on fd_product_id=fd_paycard_product
								left join tb_customer on fd_cus_id=fd_paycard_cusid
								left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid";

	var $browse_edit = "paycard.php?listid=";
	var $browse_editname = "查看";
	
	var $browse_defaultorder = " fd_paycard_activetime desc,fd_paycard_key asc ";

	//var $browse_new = "paycard.php";
	//var $browse_delsql = "delete from tb_paycard where fd_paycard_id = '%s'";

	var $browse_field = array (
		"fd_paycard_id",
		"paycardstate",
		"fd_paycard_batches",
		"fd_paycard_key",
		"fd_author_truename",
		"fd_product_name",		
		"paycardwhere",
		"fd_product_suppname",
		"fd_paycard_activetime",		
		"fd_cus_name",
		"fd_paycard_stockprice",
		"fd_paycard_saleprice",
		"fd_paycard_scope"
	);
	var $browse_find = array (// 查询条件
	"0" => array (
			"编号",
			"fd_paycard_id",
			"TXT"
		),
//		"1" => array (
//			"是否激活",
//			"fd_paycard_active",
//			"TXT"
//		),
		"1" => array (
			"批次",
			"fd_paycard_batches",
			"TXT"
		),
		"2" => array (
			"设备号",
			"fd_paycard_key",
			"TXT"
		),
		"3" => array (
			"商品名",
			"fd_product_name",
			"TXT"
		),

		"4" => array (
			"供应商名",
			"fd_product_suppname",
			"TXT"
		),
		"5" => array (
			"代理商",
			"fd_cus_name",
			"TXT"
		),		
	);
}

class fd_paycard_id extends browsefield {
	var $bwfd_fdname = "fd_paycard_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}

class fd_paycard_batches extends browsefield {
	var $bwfd_fdname = "fd_paycard_batches"; // 数据库中字段名称
	var $bwfd_title = "批次"; // 字段标题
	var $bwfd_width = "15%"; // 字段标题
}
class fd_paycard_key extends browsefield {
	var $bwfd_fdname = "fd_paycard_key"; // 数据库中字段名称
	var $bwfd_title = "设备号"; // 字段标题
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // 数据库中字段名称
	var $bwfd_title = "终端商户"; // 字段标题
}

class paycardstate extends browsefield {
	var $bwfd_fdname = "paycardstate"; // 数据库中字段名称
	var $bwfd_title = "状态"; // 字段标题
	function makeshow() { // 将值转为显示值
			$arr_data=explode(",",$this->bwfd_value);
			$data1 = $arr_data[0];
			$data2 = $arr_data[1];
			if($data1=="1")
			{
				$this->bwfd_show = "新卡";
			}else{
			switch ($this->bwfd_value) {
				case "0":
					$this->bwfd_show = "停用";
					break;
				case "1" :
					$this->bwfd_show = "警告";
					break;
				case "2" :
					$this->bwfd_show = "启用";
					break;
				case "3" :
					$this->bwfd_show = "冻结";
					break;
			}
		}
		return $this->bwfd_show;
	}
}

class paycardwhere extends browsefield {
	var $bwfd_fdname = "paycardwhere"; // 数据库中字段名称
	var $bwfd_title = "刷卡器跟踪"; // 字段标题
	var $bwfd_width = "8%"; // 字段标题
		function makeshow() { // 将值转为显示值
			switch($this->bwfd_value)
			{
				case '1':
				$this->bwfd_show="在库存";
				break;
				case '2':
				$this->bwfd_show="已销售";
				break;
				case '-1':
				$this->bwfd_show="已退货";
				break;
				case '-2':
				$this->bwfd_show="销售退货";
				break;
			}
		return $this->bwfd_show;
	}
}

class fd_product_suppname extends browsefield {
	var $bwfd_fdname = "fd_product_suppname"; // 数据库中字段名称
	var $bwfd_title = "供应商"; // 字段标题
}
class fd_paycard_activetime extends browsefield {
	var $bwfd_fdname = "fd_paycard_activetime"; // 数据库中字段名称
	var $bwfd_title = "激活时间"; // 字段标题
	var $bwfd_width = "12%"; // 字段标题
}

class fd_product_name extends browsefield {
	var $bwfd_fdname = "fd_product_name"; // 数据库中字段名称
	var $bwfd_title = "商品名称"; // 字段标题
}
class fd_cus_name extends browsefield {
	var $bwfd_fdname = "fd_cus_name"; // 数据库中字段名称
	var $bwfd_title = "代理商"; // 字段标题
}
class fd_paycard_stockprice extends browsefield {
	var $bwfd_fdname = "fd_paycard_stockprice"; // 数据库中字段名称
	var $bwfd_title = "采购价格"; // 字段标题
}

class fd_paycard_saleprice extends browsefield {
	var $bwfd_fdname = "fd_paycard_saleprice"; // 数据库中字段名称
	var $bwfd_title = "销售价格"; // 字段标题
}
class fd_paycard_scope extends browsefield {
	var $bwfd_fdname = "fd_paycard_scope"; // 数据库中字段名称
	var $bwfd_title = "可刷卡类型"; // 字段标题
	function makeshow() { // 将值转为显示值
		switch ($this->bwfd_value) {
			case "creditcard" :
				$this->bwfd_show = "信用卡";
				break;
			case "bankcard" :
				$this->bwfd_show = "借记卡";
				break;
		}
		return $this->bwfd_show;
	}
}



if (empty ($order)) {
	$order = "fd_paycard_id";
}

if (isset ($pagerows)) { // 显示列数
	$pagerows = min($pagerows, 100); // 最大显示列数不超过100
} else {
	$pagerows = 100;
}

$tb_paycard_b_bu = new tb_paycard_b();
$tb_paycard_b_bu->browse_skin = $loginskin;
$tb_paycard_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_paycard_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_paycard_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
//$tb_paycard_b_bu->browse_link  = array("lk_view0");

$tb_paycard_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>
