<?
$thismenucode = "2k204";
require ("../include/common.inc.php");

$g_tmp_paycardid = "";
$gourl = "jxcsaleback.php";
$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$db = new DB_test;
$t = new Template(".", "keep"); //调用一个模版

if(!empty($action) && !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_salelistback where fd_selt_id = '$listid' 
  	          and (fd_selt_state = 9 or fd_selt_state = 1)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('该单据已经过帐或者等待审核，不能再修改，请查证')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}


switch ($action) {
	case "new" : //新增数据
		$query = "select * from tb_salelist_tmp  where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		if ($db->nf()) {
			while ($db->next_record()) {
				$tmp_paycardid = $db->f(fd_tmpsale_paycardid);
			}
		}
		$query = "insert into tb_salelistbackdetail(
						fd_stdetail_seltid   ,  fd_stdetail_paycardid , fd_stdetail_productid ,
						fd_stdetail_quantity ,  fd_stdetail_price
						)values(
						'$listid'            ,  '$tmp_paycardid'       , '$productid'     ,
						'$quantity'          ,  '$price'
						)";
		$db->query($query); //插入细节表 数据
		$stdetail_id = $db->insert_id(); //取出刚插入的记录的主关键值的id

		$query = "update tb_salelist_tmp set fd_tmpsale_seltid='$stdetail_id',fd_tmpsale_type='saleback' where fd_tmpsale_id='$tmpid' ";
		$db->query($query);
		
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);
		 changepaycardstate($arr_tmp_paycardid,'-2');//修改刷卡器状态
		 
		 countallsalepaycard($listid,'tb_salelistback','tb_salelistbackdetail');
		 
		echo "<script>location.href='jxcsaleback.php?listid=$listid';</script>";
		break;
	case "edit" :
		$query = "select * from tb_salelist_tmp  where fd_tmpsale_id='$tmpid'";
		$db->query($query);
		if ($db->nf()) {
			while ($db->next_record()) {
				$tmp_paycardid = $db->f(fd_tmpsale_paycardid);
			}
		}
		
		$query="select fd_stdetail_paycardid as paycardid from tb_salelistbackdetail where fd_stdetail_id = '$vid' ";
		$arr_data=$db->get_one($query);
		
		$arr_paycardid=explode(",",$arr_data['paycardid']);
		$arr_tmp_paycardid=explode(",",$tmp_paycardid);

		changepaycardstate($arr_paycardid,'1');//修改刷卡器状态
		
		changepaycardstate($arr_tmp_paycardid,'-2');//修改刷卡器状态
		
		$query = "update tb_salelistbackdetail set fd_stdetail_price = '$price' ,fd_stdetail_quantity='$quantity' ,  
									fd_stdetail_paycardid='$tmp_paycardid'
						where fd_stdetail_id = '$vid' ";
		$db->query($query); //插入细节表 数据
		
		 countallsalepaycard($listid,'tb_salelistback','tb_salelistbackdetail');	
		echo "<script>location.href='jxcsaleback.php?listid=$listid';</script>";
		break;
}
$t->set_file("salelist", "jxcsale_choosebackpaycard.html");
$arr_text = array (
	"批次号",
	"刷卡器设备号",
	"销售价格",
	"供应商",
	"<INPUT onclick=CheckAll(this.form) type=checkbox 	 value=on name=chkall class='checkall'>"
);
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
$theadth .= "</thead>";

$query="select fd_paycard_product  from tb_paycard where fd_paycard_cusid='$cusid' group by fd_paycard_product";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$saleproduct=$db->f(fd_paycard_product);
		if($saleproductid)
		{
			$saleproductid .=","."'$saleproduct'";
		}else{
			$saleproductid="'$saleproduct'";
		}
	}
	
	$querywhere= "where fd_product_id in ($saleproductid)";
}


$query = "select * from tb_product $querywhere";

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
	$query = "select * from tb_salelistbackdetail where fd_stdetail_id = '$vid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$paycardid = $db->f(fd_stdetail_paycardid); //商品id号
		$price = $db->f(fd_stdetail_price) + 0; //单价
		$quantity = $db->f(fd_stdetail_quantity) + 0; //数量
		$productid = $db->f(fd_stdetail_productid);
		$listid = $db->f(fd_stdetail_seltid);
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

	//更新销售临时表信息,防止选择刷卡器没保存返回,再次进入,出现数据错误
	$query = "update  tb_salelist_tmp set fd_tmpsale_paycardid='$paycardid'  where fd_tmpsale_seltid = '$vid' and fd_tmpsale_type='saleback'";
	$db->query($query);
	//获取销售临时表信息
	$query = "select * from tb_salelist_tmp where fd_tmpsale_seltid='$vid' and fd_tmpsale_type='saleback'";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$tmpid = $db->f(fd_tmpsale_id); //id号     	   
	}

}

$productname = makeselect($arr_productname, $productid, $arr_productid);

$t->set_var("theadth", $theadth);
$t->set_var("listid", $listid);
$t->set_var("tmpid", $tmpid);
$t->set_var("price", $price); //单价 
$t->set_var("money", $money); //金额 
$t->set_var("quantity", $quantity);
$t->set_var("productname", $productname);
$t->set_var("vid", $vid);
$t->set_var("cusid", $cusid);

$t->set_var("gotourl", $gotourl); // 转用的地址
$t->set_var("action", $action); // 转用的地址

// 判断权限 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "salelist"); # 最后输出页面

function getnewpaycardid($paycardid)
{
	$arr_paycardid=explode(",",$paycardid);
		foreach($arr_paycardid as $va0)
		{
		if($strpaycardid)
		{
			$strpaycardid .=","."'$va0'";
		}else{
			$strpaycardid="'$va0'";
		}
		}
		return $strpaycardid;
}


?>

