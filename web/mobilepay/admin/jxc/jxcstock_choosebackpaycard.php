	<?
$thismenucode = "2k204";
require ("../include/common.inc.php");


$g_tmp_paycardid = "";
$gourl = "jxcstockback.php";
$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$db = new DB_test;
$t = new Template(".", "keep"); //����һ��ģ��

if(!empty($action) or !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_paycardstockback where fd_stock_id = '$listid' 
  	          and (fd_stock_state = 1 or fd_stock_state = 9)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('�õ����Ѿ����ʻ��ߵȴ������У��������޸ģ����֤')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}

switch ($action) {
	case "new" : //��������
		$query = "select * from tb_salelist_tmp  where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		if ($db->nf()) {
			while ($db->next_record()) {
				$tmp_paycardid = $db->f(fd_tmpsale_paycardid);
			}
		}
		$query = "insert into tb_paycardstockbackdetail(
						fd_skdetail_stockid   ,  fd_skdetail_paycardid , fd_skdetail_productid ,
						fd_skdetail_quantity 
						)values(
						'$listid'            ,  '$tmp_paycardid'       , '$productid'     ,
						'$quantity'          
						)";
		$db->query($query); //����ϸ�ڱ� ����
		$skdetail_id = $db->insert_id(); //ȡ���ղ���ļ�¼�����ؼ�ֵ��id

		$query = "update tb_salelist_tmp set fd_tmpsale_seltid='$skdetail_id' , fd_tmpsale_type='stockback' where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		countallbackpaycard($listid);
		
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);
		
		changepaycardstate($arr_tmp_paycardid ,'-1');
		
		echo "<script>location.href='jxcstockback.php?listid=$listid';</script>";
		break;
	case "edit" :
		$query = "select * from tb_salelist_tmp  where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		if ($db->nf()) {
			while ($db->next_record()) {
				$tmp_paycardid = $db->f(fd_tmpsale_paycardid);
			}
		}
		
		$query = "select * from tb_paycardstockbackdetail  where fd_skdetail_id='$vid'";
		$db->query($query);
		if ($db->nf()) {
			while ($db->next_record()) {
				$skdetail_paycardid = $db->f(fd_skdetail_paycardid);
			}
		}
		
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);
		
		$arr_skdetail_paycardid=explode(",",$skdetail_paycardid);
		
		

		
		changepaycardstate($arr_skdetail_paycardid,'1');
	

		changepaycardstate($arr_tmp_paycardid,'-1');
		
		$query = "update tb_paycardstockbackdetail set fd_skdetail_price = '$price' ,fd_skdetail_quantity='$quantity' ,  
									fd_skdetail_paycardid='$tmp_paycardid'
						where fd_skdetail_id = '$vid' ";
		$db->query($query); //����ϸ�ڱ� ����
		countallbackpaycard($listid);
		echo "<script>location.href='jxcstockback.php?listid=$listid';</script>";
		break;
}
$t->set_file("salelist", "jxcstock_choosebackpaycard.html");
$arr_text = array (
	"���κ�",
	"ˢ�����豸��",
	"���۸�",
	"��Ӧ��",
	"<INPUT onclick=CheckAll(this.form) type=checkbox 	 value=on name=chkall class='checkall'>"
);
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
$theadth .= "</thead>";
$query = "select * from tb_product where fd_product_suppid='$suppid'";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_productid[] = $db->f(fd_product_id);
		$arr_productname[] = $db->f(fd_product_name);
	}
}

if (empty ($vid)) {
	$action = "new";
} else {
	$query = "select * from tb_paycardstockbackdetail where fd_skdetail_id = '$vid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$paycardid = $db->f(fd_skdetail_paycardid); //��Ʒid��
		$price = $db->f(fd_skdetail_price) + 0; //����
		$quantity = $db->f(fd_skdetail_quantity) + 0; //����
		$id = $db->f(fd_skdetail_id);
		$productid = $db->f(fd_skdetail_productid);
		$listid = $db->f(fd_skdetail_stockid);
		$productname = $arr_product[$productid];

		if ($price == 0) {
			$price = "";
		}

		if ($quantity == 0) {
			$quantity = "";
		}

		$money = $price * $quantity;
		$action = "edit";
	}

	//����������ʱ����Ϣ,��ֹѡ��ˢ����û���淵��,�ٴν���,�������ݴ���
	$query = "update  tb_salelist_tmp set fd_tmpsale_paycardid='$paycardid'  where fd_tmpsale_seltid = '$vid' and fd_tmpsale_type='stockback'";
	$db->query($query);
	//��ȡ������ʱ����Ϣ
	$query = "select * from tb_salelist_tmp where fd_tmpsale_seltid='$vid' and fd_tmpsale_type='stockback'";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$tmpid = $db->f(fd_tmpsale_id); //id��     	   
	}

}

$productname = makeselect($arr_productname, $productid, $arr_productid);

$t->set_var("theadth", $theadth);
$t->set_var("listid", $listid);
$t->set_var("tmpid", $tmpid);
$t->set_var("price", $price); //���� 
$t->set_var("money", $money); //��� 
$t->set_var("quantity", $quantity);
$t->set_var("productname", $productname);
$t->set_var("vid", $vid);

$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ
$t->set_var("action", $action); // ת�õĵ�ַ

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "salelist"); # ������ҳ��

 
?>

