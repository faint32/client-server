<?php
$thismenucode = "2k206";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //�����г����ݱ���ļ�                                                 
require ("../function/changestorage.php");       //�����޸Ŀ���ļ�
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php");           //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�
require ("../function/checkstorage.php");        //���ü���Ƿ�Ҫ�����֧��

$db  = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep");          //����һ��ģ��

$gourl = "tb_jxcsale_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");


switch ($action)
{
	case "draft"://תΪ�ݸ嵥
		$query = "select * from tb_salelist where fd_selt_id = '$listid'";
		$db->query($query);
		if($db->nf())
		{
			$db->next_record();                           //��ȡ��¼����
			$cusid         = $db->f(fd_selt_cusid);       //�ͻ�id
			$cusno         = $db->f(fd_selt_cusno);       //�ͻ����
			$cusname       = $db->f(fd_selt_cusname);     //�ͻ�����
			$now           = $db->f(fd_selt_date);        //¼������
			$datetime      = $db->f(fd_selt_datetime);    //¼������
			$memo          = $db->f(fd_selt_memo);        //��ע
			$skfs          = $db->f(fd_selt_skfs);        //�տʽ

			$listno = listnumber_update(3);  //���浥��
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
					$proid       = $db->f(fd_stdetail_paycardid);    //��ƷID
					$proprice    = $db->f(fd_stdetail_price);     //�۸�
					$query="INSERT INTO tb_salelistdetail ( fd_stdetail_seltid , fd_stdetail_paycardid , fd_stdetail_price )VALUES ( '$oldid' , '$proid' , '$proprice' )";
					$db2->query($query);
				}
			}
		}
		break;
}
$t = new Template(".", "keep"); //����һ��ģ��
$t->set_file("stock_sp", "salelistview.html");

$query = "select * from tb_salelist where fd_selt_id = '$listid'";
$db->query($query);
if ($db->nf()) {
	$db->next_record();
	$listid = $db->f(fd_selt_id); //id��  
	$listno = $db->f(fd_selt_no); //���ݱ��
	$cusid = $db->f(fd_selt_cusid); //�ͻ�id
	$cusno = $db->f(fd_selt_cusno); //�ͻ���� 
	$cusname = $db->f(fd_selt_cusname); //�ͻ�����
	$date = $db->f(fd_selt_date); //¼������
	$memo_z = $db->f(fd_selt_memo); //��ע
	$skfs = $db->f(fd_selt_skfs); //�տʽ
	$shaddress = $db->f(fd_selt_shaddress);

	if ($date == "0000-00-00") {
		$date = date("Y-m-d", time());
	}

}

//��Ʒ����
$query = "select * from tb_product";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {

		$arr_product[$db->f(fd_product_id)] = $db->f(fd_product_name);
	}
}

//��ʾ�б�
$t->set_block("stock_sp", "prolist", "prolists");
$query = "select * from tb_salelistdetail 
          where fd_stdetail_seltid = '$listid' ";
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

//�տʽ
//�տʽ
$arr_skfs = array("�ֽ�","֧Ʊ","���","�ж�","����֧��");
$arr_skfsval = array("1","2","3","4","5");
$skfs = makeselect($arr_skfs,$skfs,$arr_skfsval);

/* //��ѯ�ͻ������Ŷ��
$query = "select fd_cus_credit , fd_cus_custypeid from tb_customer where fd_cus_id = '$cusid'";
$db->query($query);
if ($db->nf()) {
	$db->next_record();
	$cus_credit = $db->f(fd_cus_credit);
	$cus_typeid = $db->f(fd_cus_custypeid);
	if (empty ($error) && $cus_typeid == 4) {
		$error = "����ͻ��Ѿ����ܲ��������������ע�⣡";
	}
} */
$vallmoney = number_format($vallmoney, 2, ".", "");
$t->set_var("listid", $listid); //����id 
$t->set_var("id", $id); //id 
$t->set_var("listno", $listno); //���ݱ�� 
$t->set_var("cusid", $cusid); //�ͻ�id��
$t->set_var("cusno", $cusno); //�ͻ����
$t->set_var("cusname", $cusname); //�ͻ�����
$t->set_var("memo_z", $memo_z); //��ע
$t->set_var("skfs", $skfs); //�տʽ 
$t->set_var("shaddress", $shaddress);
$t->set_var("vprice", $vprice);

$t->set_var("isshow", $isshow);

$t->set_var("paycardid", $paycardid); //��Ʒid 

$t->set_var("count", $count);
$t->set_var("vallquantity", $vallquantity);
$t->set_var("vallmoney", $vallmoney); //�ܽ��

$t->set_var("memo", $memo); //��ע

$t->set_var("price", $price); //���� 
$t->set_var("money", $money); //��� 

$t->set_var("date", $date);

$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ
$t->set_var("error", $error);

$t->set_var("checkid", $checkid); //����ɾ����ƷID     

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "stock_sp"); # ������ҳ��
?>

