<?
$thismenucode = "2k202";
require ("../include/common.inc.php");
require ("../function/changestorage.php"); //调用修改库存文件
require ("../function/changemoney.php"); //调用应收应付金额文件
require ("../function/commglide.php"); //调用商品流水帐文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/cashglide.php"); //调用现金流水帐文件
require ("../function/currentaccount.php"); //调用往来对帐单文件
require ("../function/checkstorage.php"); //调用检测是否要补库存支出 

$db = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcsale_sp_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
if (!empty ($end_action)) {
	$query = "select * from tb_salelist where fd_selt_id = '$listid' 
		          and fd_selt_state = 9";
	$db->query($query);
	if ($db->nf()) {
		echo "<script>alert('该单据已经过帐，不能再修改，请查证')</script>";
		$action = "";
		$end_action = "";
	}
}

//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if ($end_action == "endsave") {
	$listdate = $date;

	$todaydate = date("Y-m-d", mktime("0", "0", "0", date("m", mktime()), date("d", mktime()), date("Y", mktime())));
	if ($todaydate < $listdate) {
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action = "";
	}
}
 switch ($end_action) {
	case "endsave" : //最后提交数据

		if ($loginopendate > $date) {
			$error = "错误：单据日期不能小于上月月结后本月开始日期";
		}else {
			$allmoney = 0;
			$alltrtrunmoney = 0;
			$alldunquantity = 0;
			$alldunmoney = 0;
			$allstoragecost = 0;
			$num=0;
			$query = "select * from tb_salelistdetail 
					left join tb_product on fd_product_id=fd_stdetail_productid	
				    where fd_stdetail_seltid = '$listid'";
					
			$db->query($query);
			if ($db->nf()) {
				while ($db->next_record()) {
					$paycardid = $db->f(fd_stdetail_paycardid);
					$tmpsbdetailid = $db->f(fd_stdetail_id);
					$price = $db->f(fd_stdetail_price); //单价	        	
					$productid = $db->f(fd_stdetail_productid);
					$productname = $db->f(fd_product_name);
					$quantity = $db->f(fd_stdetail_quantity);
					$arr_data[$num]['paycardid']=$paycardid;
					$arr_data[$num]['productid']=$productid;
					$arr_data[$num]['price']=$price;
					$arr_data[$num]['quantity']=$quantity;
					$num++;
					if($strpaycardid){$strpaycardid .=",".$paycardid;}else{$strpaycardid=$paycardid;}
					
					//查找库存是否有数量
					$flagquantity = 0;
					$query = "select * from tb_paycardstockquantity where fd_skqy_commid = '$productid' ";
					$db1->query($query);
					if ($db1->nf()) {
						while ($db1->next_record()) {
							if ($db1->f(fd_skqy_quantity) != 0) {
								$flagquantity = 1;
							}
						}
					}
					//查找是否有库存成本价
					$query = "select * from tb_storagecost where fd_sect_commid = '$productid' ";
					$db1->query($query);
					if ($db1->nf()) {
						$db1->next_record();
						$storagecost = $db1->f(fd_sect_cost);

						if ($storagecost == 0 and $flagquantity == 0) { //如果库存单价为0时，就修改库存单价
							$query = "update tb_storagecost set fd_sect_cost = '$tmpcost'
							                            where fd_sect_commid = '$productid'";
							$db1->query($query);
						}
					} else { //如果没有库存成本记录
						$storagecost = 0;
						if ($flagquantity == 0) {
							$query = "insert into tb_storagecost(
							                            fd_sect_cost    ,  fd_sect_commid 
							                            )values(
							                            '$tmpcost'      ,  '$productid'      
							                            )";
							$db1->query($query);
						}
					}

					//修改仓库的数量和成本价
			      updatestorage($productid, $quantity, $storagecost, $storageid, 1); //0代表正、1代表负					
					//获取刷卡器设备号
					$paycardkey=getpaycardkey($paycardid);
					//商品流水帐
					$cogememo = "销售商品类型为" . $productname . "的刷卡器设备:" . $paycardkey;
					$cogelisttype = "3";
					$cogetype = 1; //0为增加 ， 1为减少
					commglide($storageid, $productid, $quantity, $cogememo, $cogelisttype, $loginstaname, $listid, $listno, $cogetype, $date);

					$allmoney += $price * $quantity; //销售总额
				}
				//生成分行往来对帐单
				$ctatmemo = "应收" . $cusname . "客户" . $allmoney . "元";
				$cactlisttype = "3";
				$lessenmoney = 0;
				currentaccount(1, $cusid, $allmoney, $lessenmoney, $ctatmemo, $cactlisttype, $loginstaname, $listid, $listno, $date);

				if ($allmoney <> 0) {
					changemoney(1, $cusid, $allmoney, 0); //第四位0代表正，1代表负数
				}

				//生成帐户流水帐
				$chgememo = "销售单收取". $cusname . "客户"  . $allmoney . "元";
				$chgelisttype = "3";
				$cogetype = 0; //0为收款 ， 1为付款
				cashglide($accountid, $allmoney, $chgememo, $chgelisttype, $loginstaname, $listid, $listno, $cogetype, $date);
			

				 $query = "insert into tb_cus_stock(
	                fd_stock_no          ,   fd_stock_date  ,fd_stock_cusid   , fd_stock_cusno,
					fd_stock_cusname     ,	fd_stock_skfs   ,fd_stock_shaddress  ,fd_stock_allmoney,
					fd_stock_allcost    ,fd_stock_saleid     ,fd_stock_datetime  ,fd_stock_state
	                )values(
	                '$listno'           ,   '$date'      ,'$cusid'         , '$cusno',
					'$cusname'          ,   '$skfs'      ,'$shaddress'	   , '$allmoney',
  					'$allmoney'         ,   '$listid'    ,   now()         ,  '1'
	                )";
					
			 $db->query($query);   //插入单据资料
			   $cus_listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
				
			   for($i=0; $i<count($arr_data);$i++)
			   {
				
					$paycardid=$arr_data[$i]['paycardid'];
					$quantity=$arr_data[$i]['quantity'];
					$price=$arr_data[$i]['price'];
					$productid=$arr_data[$i]['productid'];
					updatepaycard($paycardid,$price,$date,$cusid);
					
				  $query  = "insert into tb_cus_stockdetail(
						fd_skdetail_stockid  ,  fd_skdetail_paycardid  ,  
						fd_skdetail_quantity ,  fd_skdetail_price   ,    
						fd_skdetail_productid    					
						)values(
						'$cus_listid'            ,  '$paycardid'    ,  
						'$quantity'          ,  '$price'            ,  
						'$productid'                     
						)";
					
				$db->query($query);   //插入细节表 数据
				}
				$arr_paycarid=explode(",",$strpaycardid);
				foreach($arr_paycarid as $value1)
				{
					$query="select * from tb_paycard where fd_paycard_id = '$value1'";
					$db->query($query);
					if($db->nf())
					{
						$db->next_record();
						$stockprice = $db->f(fd_paycard_stockprice); //单价
					}
					$allstockprice += $stockprice; //成本总价格
				}
			}
			$query = "update tb_salelist set 
				            fd_selt_state  = 9   , fd_selt_datetime  = now() ,
							fd_selt_allmoney = '$allmoney' ,fd_selt_allcost='$allstockprice'    
				            where fd_selt_id  = '$listid' ";
		$db->query($query); //修改单据资料
			require ("../include/alledit.2.php");
			echo "<script>alert('审批成功!');location.href='$gotourl';</script>";
			
		}
		break;
	case "dellist" : //审批不通过
		$query = "update tb_salelist set fd_selt_state ='0',fd_selt_memo='$memo_z'  where fd_selt_id = '$listid' ";
		$db->query($query); //修改单据资料
		require ("../include/alledit.2.php");
		Header("Location: $gotourl");
		break;
	default :
		break;
} 

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("stock_sp", "jxcsale_sp.html");

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
	$ldr = $db->f(fd_selt_ldr); //录单人
	$dealwithman = $db->f(fd_selt_dealwithman); //经手人
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
          where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc";
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
} else {

	$t->parse("prolists", "", true);
}

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
$t->set_var("ldr", $ldr); //录单人
$t->set_var("dealwithman", $dealwithman); //经手人

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

function noteprice($listid, $price, $commid) {
	$db = new DB_test;

	$query = "select * from tb_inpricenote where fd_inpene_commid = '$commid'";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$upprice = $db->f(fd_inpene_price);
		$inpeneid = $db->f(fd_inpene_id);

		$query = "update tb_inpricenote set 
				         fd_inpene_listid = '$listid' , fd_inpene_upprice = '$upprice' ,
				         fd_inpene_price  = '$price'
				         where fd_inpene_commid = '$commid' ";
		$db->query($query);

	} else {
		$query = "insert into tb_inpricenote(
				          fd_inpene_listid , fd_inpene_commid , 
				          fd_inpene_price  , fd_inpene_upprice
				          )values(
				          '$listid'        , '$commid'        , 
				          '$price'         , '0'
				          )";
		$db->query($query);
	}
}
?>

