<?
$thismenucode = "2k202";
require ("../include/common.inc.php");
require ("../function/changestorage.php"); //�����޸Ŀ���ļ�
require ("../function/changemoney.php"); //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php"); //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php"); //�����޸��ʻ�����ļ�
require ("../function/cashglide.php"); //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php"); //�����������ʵ��ļ�
require ("../function/checkstorage.php"); //���ü���Ƿ�Ҫ�����֧�� 

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
		echo "<script>alert('�õ����Ѿ����ʣ��������޸ģ����֤')</script>";
		$action = "";
		$end_action = "";
	}
}

//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if ($end_action == "endsave") {
	$listdate = $date;

	$todaydate = date("Y-m-d", mktime("0", "0", "0", date("m", mktime()), date("d", mktime()), date("Y", mktime())));
	if ($todaydate < $listdate) {
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$end_action = "";
	}
}
 switch ($end_action) {
	case "endsave" : //����ύ����

		if ($loginopendate > $date) {
			$error = "���󣺵������ڲ���С�������½���¿�ʼ����";
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
					$price = $db->f(fd_stdetail_price); //����	        	
					$productid = $db->f(fd_stdetail_productid);
					$productname = $db->f(fd_product_name);
					$quantity = $db->f(fd_stdetail_quantity);
					$arr_data[$num]['paycardid']=$paycardid;
					$arr_data[$num]['productid']=$productid;
					$arr_data[$num]['price']=$price;
					$arr_data[$num]['quantity']=$quantity;
					$num++;
					if($strpaycardid){$strpaycardid .=",".$paycardid;}else{$strpaycardid=$paycardid;}
					
					//���ҿ���Ƿ�������
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
					//�����Ƿ��п��ɱ���
					$query = "select * from tb_storagecost where fd_sect_commid = '$productid' ";
					$db1->query($query);
					if ($db1->nf()) {
						$db1->next_record();
						$storagecost = $db1->f(fd_sect_cost);

						if ($storagecost == 0 and $flagquantity == 0) { //�����浥��Ϊ0ʱ�����޸Ŀ�浥��
							$query = "update tb_storagecost set fd_sect_cost = '$tmpcost'
							                            where fd_sect_commid = '$productid'";
							$db1->query($query);
						}
					} else { //���û�п��ɱ���¼
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

					//�޸Ĳֿ�������ͳɱ���
			      updatestorage($productid, $quantity, $storagecost, $storageid, 1); //0��������1����					
					//��ȡˢ�����豸��
					$paycardkey=getpaycardkey($paycardid);
					//��Ʒ��ˮ��
					$cogememo = "������Ʒ����Ϊ" . $productname . "��ˢ�����豸:" . $paycardkey;
					$cogelisttype = "3";
					$cogetype = 1; //0Ϊ���� �� 1Ϊ����
					commglide($storageid, $productid, $quantity, $cogememo, $cogelisttype, $loginstaname, $listid, $listno, $cogetype, $date);

					$allmoney += $price * $quantity; //�����ܶ�
				}
				//���ɷ����������ʵ�
				$ctatmemo = "Ӧ��" . $cusname . "�ͻ�" . $allmoney . "Ԫ";
				$cactlisttype = "3";
				$lessenmoney = 0;
				currentaccount(1, $cusid, $allmoney, $lessenmoney, $ctatmemo, $cactlisttype, $loginstaname, $listid, $listno, $date);

				if ($allmoney <> 0) {
					changemoney(1, $cusid, $allmoney, 0); //����λ0��������1������
				}

				//�����ʻ���ˮ��
				$chgememo = "���۵���ȡ". $cusname . "�ͻ�"  . $allmoney . "Ԫ";
				$chgelisttype = "3";
				$cogetype = 0; //0Ϊ�տ� �� 1Ϊ����
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
					
			 $db->query($query);   //���뵥������
			   $cus_listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
				
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
					
				$db->query($query);   //����ϸ�ڱ� ����
				}
				$arr_paycarid=explode(",",$strpaycardid);
				foreach($arr_paycarid as $value1)
				{
					$query="select * from tb_paycard where fd_paycard_id = '$value1'";
					$db->query($query);
					if($db->nf())
					{
						$db->next_record();
						$stockprice = $db->f(fd_paycard_stockprice); //����
					}
					$allstockprice += $stockprice; //�ɱ��ܼ۸�
				}
			}
			$query = "update tb_salelist set 
				            fd_selt_state  = 9   , fd_selt_datetime  = now() ,
							fd_selt_allmoney = '$allmoney' ,fd_selt_allcost='$allstockprice'    
				            where fd_selt_id  = '$listid' ";
		$db->query($query); //�޸ĵ�������
			require ("../include/alledit.2.php");
			echo "<script>alert('�����ɹ�!');location.href='$gotourl';</script>";
			
		}
		break;
	case "dellist" : //������ͨ��
		$query = "update tb_salelist set fd_selt_state ='0',fd_selt_memo='$memo_z'  where fd_selt_id = '$listid' ";
		$db->query($query); //�޸ĵ�������
		require ("../include/alledit.2.php");
		Header("Location: $gotourl");
		break;
	default :
		break;
} 

$t = new Template(".", "keep"); //����һ��ģ��
$t->set_file("stock_sp", "jxcsale_sp.html");

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
	$ldr = $db->f(fd_selt_ldr); //¼����
	$dealwithman = $db->f(fd_selt_dealwithman); //������
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
          where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc";
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
} else {

	$t->parse("prolists", "", true);
}

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
$t->set_var("ldr", $ldr); //¼����
$t->set_var("dealwithman", $dealwithman); //������

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

