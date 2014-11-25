<?php
$thismenucode = "2k201";
require ("../include/common.inc.php");

$db = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep"); //����һ��ģ��
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
	$db->next_record(); //��ȡ��¼����
	$listno = $db->f(fd_selt_no); //���ݱ��
	$cusid = $db->f(fd_selt_cusid); //�ͻ�id
	$cusno = $db->f(fd_selt_cusno); //�ͻ����
	$cusname = $db->f(fd_selt_cusname); //�ͻ�����
	$date = $db->f(fd_selt_date); //¼������
	$ldr = $db->f(fd_selt_ldr); //¼����
	$dealwithman = $db->f(fd_selt_dealwithman); //������
	$state = $db->f(fd_selt_state); //״̬
	$memo_z = $db->f(fd_selt_memo); //��ע
	$skfs = $db->f(fd_selt_skfs); //�տʽ
}

$t->set_file("sale_sq_view", "sale_sq_view.html");
//��Ʒ����
$query = "select * from tb_product";
$db->query($query);
if ($db->nf())
{
	while ($db->next_record())
	{
		$arr_product[$db->f(fd_product_id)] = $db->f(fd_product_name);
	}
}

//��ʾ�б�
$t->set_block("salelist", "prolist", "prolists");
$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc";
$db->query($query);
$ishavepaycard = $db->nf();
$count = 0; //��¼��
$vallquantity = 0; //�ܼ�

if ($db->nf()) {
	while ($db->next_record()) {
		$vid = $db->f(fd_stdetail_id);
		$vpaycardid = $db->f(fd_stdetail_paycardid); //��Ʒid��
		$vprice = $db->f(fd_stdetail_price) + 0; //����
		$vquantity = $db->f(fd_stdetail_quantity) + 0; //����
		$vmemo = $db->f(fd_stdetail_memo); //��ע
		$vproductid = $db->f(fd_stdetail_productid);
		$vmoney = $vprice * $vquantity; //���
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

//�տʽ
$arr_skfs = array (
	"",
	"�ֽ�",
	"֧Ʊ",
	"���",
	"�ж�"
);
$skfs = $arr_skfs[$skfs];

$t->set_var("cusid", $cusid); //�ͻ�ID
$t->set_var("cusno", $cusno); //�ͻ����
$t->set_var("cusname", $cusname); //�ͻ�����
$t->set_var("listid", $listid); //����ID
$t->set_var("listno", $listno); //���ݱ��
$t->set_var("memo_z", $memo_z); //���ݱ�ע
$t->set_var("now", $now); //¼��ʱ��
$t->set_var("ldr", $ldr); //¼����
$t->set_var("dealwithman", $dealwithman); //������
$t->set_var("skfs", $skfs); //�տʽ 
$t->set_var("vallquantity", $vallquantity);
$t->set_var("vallmoney", $vallmoney); //�ܽ��
$t->set_var("count", $count); //��¼��
$t->set_var("date", $date); //��
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); //ת�õĵ�ַ
$t->set_var("error", $error);

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "sale_sq_view"); # ������ҳ��
?>