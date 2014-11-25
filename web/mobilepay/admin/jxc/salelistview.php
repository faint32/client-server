<?php
$thismenucode = "2k206";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //调用列出单据编号文件                                                 
require ("../function/changestorage.php");       //调用修改库存文件
require ("../function/changemoney.php");         //调用应收应付金额文件
require ("../function/commglide.php");           //调用商品流水帐文件
require ("../function/chanceaccount.php");       //调用修改帐户金额文件
require ("../function/cashglide.php");           //调用现金流水帐文件
require ("../function/currentaccount.php");      //调用往来对帐单文件
require ("../function/checkstorage.php");        //调用检测是否要补库存支出

$db  = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep");          //调用一个模版

$gourl = "tb_jxcsale_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");


switch ($action)
{
	case "draft"://转为草稿单
		$query = "select * from tb_salelist where fd_selt_id = '$listid'";
		$db->query($query);
		if($db->nf())
		{
			$db->next_record();                           //读取记录数据
			$cusid         = $db->f(fd_selt_cusid);       //客户id
			$cusno         = $db->f(fd_selt_cusno);       //客户编号
			$cusname       = $db->f(fd_selt_cusname);     //客户名称
			$now           = $db->f(fd_selt_date);        //录单日期
			$datetime      = $db->f(fd_selt_datetime);    //录单日期
			$memo          = $db->f(fd_selt_memo);        //备注
			$skfs          = $db->f(fd_selt_skfs);        //收款方式

			$listno = listnumber_update(3);  //保存单据
			$query="INSERT INTO tb_salelist( fd_selt_no , fd_selt_cusid , fd_selt_cusno , fd_selt_cusname , fd_selt_date , fd_selt_memo , fd_selt_allmoney , fd_selt_skfs ,fd_selt_datetime )VALUES ( '$listno' , '$cusid' ,   '$cusno' , '$cusname' , '$now' , '$memo' , '$paymoney' , '$skfs' ,'$datetime' )";
			$db->query($query);
			$oldid = $db->insert_id();
		}

		if(!empty($oldid))
		{
			$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid'";
			$db->query($query);
			if($db->nf()){
				while($db->next_record()){
					$proid       = $db->f(fd_stdetail_paycardid);    //商品ID
					$proprice    = $db->f(fd_stdetail_price);     //价格
					$query="INSERT INTO tb_salelistdetail ( fd_stdetail_seltid , fd_stdetail_paycardid , fd_stdetail_price )VALUES ( '$oldid' , '$proid' , '$proprice' )";
					$db2->query($query);
				}
			}
		}
		break;
}
$t = new Template(".", "keep"); //调用一个模版
$t->set_file("stock_sp", "salelistview.html");

$query = "select * from tb_salelist where fd_selt_id = '$listid'";
$db->query($query);
if ($db->nf()) {
	$db->next_record();
	$listid = $db->f(fd_selt_id); //id号  
	$listno = $db->f(fd_selt_no); //单据编号
	$cusid = $db->f(fd_selt_cusid); //客户id
	$cusno = $db->f(fd_selt_cusno); //客户编号 
	$cusname = $db->f(fd_selt_cusname); //客户名称
	$date = $db->f(fd_selt_date); //录单日期
	$memo_z = $db->f(fd_selt_memo); //备注
	$skfs = $db->f(fd_selt_skfs); //收款方式
	$shaddress = $db->f(fd_selt_shaddress);

	if ($date == "0000-00-00") {
		$date = date("Y-m-d", time());
	}

}

//商品名称
$query = "select * from tb_product";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {

		$arr_product[$db->f(fd_product_id)] = $db->f(fd_product_name);
	}
}

//显示列表
$t->set_block("stock_sp", "prolist", "prolists");
$query = "select * from tb_salelistdetail 
          where fd_stdetail_seltid = '$listid' ";
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
} else {

	$t->parse("prolists", "", true);
}

//收款方式
//收款方式
$arr_skfs = array("现金","支票","电汇","承兑","在线支付");
$arr_skfsval = array("1","2","3","4","5");
$skfs = makeselect($arr_skfs,$skfs,$arr_skfsval);

/* //查询客户的受信额度
$query = "select fd_cus_credit , fd_cus_custypeid from tb_customer where fd_cus_id = '$cusid'";
$db->query($query);
if ($db->nf()) {
	$db->next_record();
	$cus_credit = $db->f(fd_cus_credit);
	$cus_typeid = $db->f(fd_cus_custypeid);
	if (empty ($error) && $cus_typeid == 4) {
		$error = "这个客户已经被总部列入黑名单！请注意！";
	}
} */
$vallmoney = number_format($vallmoney, 2, ".", "");
$t->set_var("listid", $listid); //单据id 
$t->set_var("id", $id); //id 
$t->set_var("listno", $listno); //单据编号 
$t->set_var("cusid", $cusid); //客户id号
$t->set_var("cusno", $cusno); //客户编号
$t->set_var("cusname", $cusname); //客户名称
$t->set_var("memo_z", $memo_z); //备注
$t->set_var("skfs", $skfs); //收款方式 
$t->set_var("shaddress", $shaddress);
$t->set_var("vprice", $vprice);

$t->set_var("isshow", $isshow);

$t->set_var("paycardid", $paycardid); //商品id 

$t->set_var("count", $count);
$t->set_var("vallquantity", $vallquantity);
$t->set_var("vallmoney", $vallmoney); //总金额

$t->set_var("memo", $memo); //备注

$t->set_var("price", $price); //单价 
$t->set_var("money", $money); //金额 

$t->set_var("date", $date);

$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址
$t->set_var("error", $error);

$t->set_var("checkid", $checkid); //批量删除商品ID     

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "stock_sp"); # 最后输出页面
?>

