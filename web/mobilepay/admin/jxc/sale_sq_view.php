<?php
$thismenucode = "2k201";
require ("../include/common.inc.php");

$db = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep"); //调用一个模版
if($backstate == 0){
  $gourl = "tb_jxcsale_b.php" ;
}else if($backstate == 1){
	$gourl = "tb_jxcsale_sp_b.php" ;
}

$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$query = "select * from tb_salelist where fd_selt_id = '$listid'";
$db->query($query);
if ($db->nf())
{
	$db->next_record(); //读取记录数据
	$listno = $db->f(fd_selt_no); //单据编号
	$cusid = $db->f(fd_selt_cusid); //客户id
	$cusno = $db->f(fd_selt_cusno); //客户编号
	$cusname = $db->f(fd_selt_cusname); //客户名称
	$date = $db->f(fd_selt_date); //录单日期
	$ldr = $db->f(fd_selt_ldr); //录单人
	$dealwithman = $db->f(fd_selt_dealwithman); //经手人
	$state = $db->f(fd_selt_state); //状态
	$memo_z = $db->f(fd_selt_memo); //备注
	$skfs = $db->f(fd_selt_skfs); //收款方式
}

$t->set_file("sale_sq_view", "sale_sq_view.html");
//商品名称
$query = "select * from tb_product";
$db->query($query);
if ($db->nf())
{
	while ($db->next_record())
	{
		$arr_product[$db->f(fd_product_id)] = $db->f(fd_product_name);
	}
}

//显示列表
$t->set_block("salelist", "prolist", "prolists");
$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc";
$db->query($query);
$ishavepaycard = $db->nf();
$count = 0; //记录数
$vallquantity = 0; //总价

if ($db->nf()) {
	while ($db->next_record()) {
		$vid = $db->f(fd_stdetail_id);
		$vpaycardid = $db->f(fd_stdetail_paycardid); //商品id号
		$vprice = $db->f(fd_stdetail_price) + 0; //单价
		$vquantity = $db->f(fd_stdetail_quantity) + 0; //数量
		$vmemo = $db->f(fd_stdetail_memo); //备注
		$vproductid = $db->f(fd_stdetail_productid);
		$vmoney = $vprice * $vquantity; //金额
		$vallquantity += $vquantity;
		$vallmoney += $vmoney;

		$vproductid = $arr_product[$vproductid];
		$count++;

		$vdunprice = number_format($vdunprice, 4, ".", "");
		$vmoney = number_format($vmoney, 2, ".", "");

		$trid = "tr" . $count;
		$imgid = "img" . $count;

		if ($s == 1) {
			$bgcolor = "#F1F4F9";
			$s = 0;
		} else {
			$bgcolor = "#ffffff";
			$s = 1;
		}
		$t->set_var(array (
			"trid" => $trid,
			"imgid" => $imgid,
			"vid" => $vid,
			"count" => $count,
			"vquantity" => $vquantity,
			"vpaycardid" => $vpaycardid,
			"vmemo" => $vmemo,
			"bgcolor" => $bgcolor,
			"vprice" => $vprice,
			"rowcount" => $count,
			"vmoney" => $vmoney,
			"vproductid" => $vproductid
		));
		$t->parse("prolists", "prolist", true);
	}
}
else
{
	$trid = "tr1";
	$imgid = "img1";
	$t->set_var(array (
		"trid" => $trid,
		"imgid" => $imgid,
		"vid" => "",
		"count" => "",
		"vquantity" => "",
		"vpaycardid" => "",
		"vmemo" => "",
		"bgcolor" => "#ffffff",
		"vproductid" => "",
		"vprice" => "",
		"vbatches" => "",
		"rowcount" => "",
		"vmoney" => ""
	));
	$t->parse("prolists", "", true);
}

$vallmoney = round($vallmoney, 2);

//收款方式
$arr_skfs = array (
	"",
	"现金",
	"支票",
	"电汇",
	"承兑"
);
$skfs = $arr_skfs[$skfs];

$t->set_var("cusid", $cusid); //客户ID
$t->set_var("cusno", $cusno); //客户编号
$t->set_var("cusname", $cusname); //客户名称
$t->set_var("listid", $listid); //单据ID
$t->set_var("listno", $listno); //单据编号
$t->set_var("memo_z", $memo_z); //单据备注
$t->set_var("now", $now); //录单时间
$t->set_var("ldr", $ldr); //录单人
$t->set_var("dealwithman", $dealwithman); //经手人
$t->set_var("skfs", $skfs); //收款方式 
$t->set_var("vallquantity", $vallquantity);
$t->set_var("vallmoney", $vallmoney); //总金额
$t->set_var("count", $count); //记录数
$t->set_var("date", $date); //年
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); //转用的地址
$t->set_var("error", $error);

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "sale_sq_view"); # 最后输出页面
?>