<?
$thismenucode = "2k202";
require ("../include/common.inc.php");
require ("../function/changestorage.php"); //调用修改库存文件
require ("../function/changemoney.php"); //调用应收应付金额文件
require ("../function/commglide.php"); //调用商品流水帐文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/currentaccount.php"); //调用往来对帐单文件
require ("../function/checkstorage.php"); //调用检测是否要补库存支出 

$db = new DB_test ( );
$db1 = new DB_test ( );

$gourl = "tb_jxcstock_sp_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
if(!empty( $end_action )) {
	$query = "select * from tb_paycardstock where fd_stock_id = '$listid' 
	          and fd_stock_state != 1";
	$db->query ( $query );
	if($db->nf ()){
		echo "<script>alert('该单据不在此步骤，不能再修改，请查证')</script>";
		$action = "";
		$end_action = "";
	}
}

//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if($end_action == "endsave"){	
	$listdate = $date;	
	$todaydate = date ( "Y-m-d", mktime ( "0", "0", "0", date ( "m", mktime () ), date ( "d", mktime () ), date ( "Y", mktime () ) ) );
	if($todaydate < $listdate) {
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action = "";
	}
}

switch ($end_action) {
	case "endsave" : //最后提交数据
		if($loginopendate > $date){
			$error = "错误：单据日期不能小于上月月结后本月开始日期";
		}else{
			$query = "select * from tb_paycardstock where fd_stock_id = '$listid'";
			$db->query ( $query );
			if($db->nf ()){
				$db->next_record ();
				$listno    = $db->f ( fd_stock_no );          //单据编号
				$suppid    = $db->f ( fd_stock_suppid );      //供应商id号
				$suppno    = $db->f ( fd_stock_suppno );      //供应商编号 
				$suppname  = $db->f ( fd_stock_suppname );    //供应商名称
			}
			
		
			$allmoney = 0;
			$alltrtrunmoney = 0;
			$alldunquantity = 0;
			$alldunmoney = 0;
			
			$query = "select * from tb_paycardstockdetail 
					      left join tb_product on fd_product_id=fd_skdetail_productid
	              where fd_skdetail_stockid = '$listid'";
			$db->query ( $query );
			if($db->nf()){
				while($db->next_record()){
					   $paycardid           =   $db->f ( fd_skdetail_paycardid );
					   $quantity            =   $db->f ( fd_skdetail_quantity );
					   $price               =   $db->f ( fd_skdetail_price );
					   $productid           =   $db->f (fd_skdetail_productid);
					   $productname         =   $db->f (fd_product_name );
					   $batches             =   $db->f (fd_skdetail_batches);
					   $productscope        =   $db->f (fd_product_productscope);
					   $producttypeid       =   $db->f (fd_product_producttypeid);
					   $arr_savepaycardid[]	=   $paycardid;
					   
					   $arr_data[$paycardid]['price']         =  $price;
					   $arr_data[$paycardid]['productid']     =  $productid;
					   $arr_data[$paycardid]['batches']       =  $batches;
					   $arr_data[$paycardid]['productscope']  =  $productscope;
					   $arr_data[$paycardid]['producttypeid'] =  $producttypeid;
					   
					   $allmoney += $quantity*$price;    
					   
					   //修改仓库
	           updatestorage($productid,$quantity,$price,$storageid,0);  //0代表正、1代表负
					     
					   //商品流水帐
					   $cogememo = $date."采购".$productname."类型".$quantity."个刷卡器,刷卡器设备号为".$paycardid;
					   $cogelisttype = "1";
					   $cogetype = 0; //0为增加 ， 1为减少
					   commglide ( $storageid, $productid, $quantity, $cogememo, $cogelisttype, $loginstaname, $listid, $listno, $cogetype, $date ); 
	        			
				}
				
				foreach($arr_savepaycardid as $valeu){			
					     $arr_paycardid   = explode ( "-", $valeu );
					     $startpaycardid  = $arr_paycardid [0];
					     $endpaycardid    = $arr_paycardid [1];
					     $arr_startint    = preg_replace ( '/[^0-9]/', "", $startpaycardid );
					     $arr_endint      = preg_replace ( '/[^0-9]/', "", $endpaycardid );
					     $arr_cart        = preg_replace ( '/[0-9]/', "", $startpaycardid );
					     
					     $price         = $arr_data[$valeu]['price'];
					     $productid     = $arr_data[$valeu]['productid'];
					     $batches       = $arr_data[$valeu]['batches'];
					     $productscope  = $arr_data[$valeu]['productscope'];
					     $producttypeid = $arr_data[$valeu]['producttypeid'];
					      $intlenght=strlen($arr_endint);
					     for($arr_startint; $arr_startint <= $arr_endint; $arr_startint ++) {
					     	   $arr_startint=$arr_startint+0;
							 $newdata=isbuzero($intlenght,$arr_startint);
							 
							$paycardid =trim($arr_cart.$newdata);
							  
					     	  $query = "insert into tb_paycard(
										fd_paycard_key,fd_paycard_no,fd_paycard_product,fd_paycard_batches,fd_paycard_stockprice,
					     	            fd_paycard_paycardtypeid ,fd_paycard_scope,fd_paycard_memo,fd_paycard_datetime,fd_paycard_state
					     	           )values('$paycardid','$paycardid','$productid','$batches','$price','$producttypeid','$productscope',
					     	             '$date 该刷卡器采购入库','$date','1'
					     	           )";
					     	  $db->query($query);
					     }  
				} 

				//生成分行往来对帐单
				$ctatmemo = "应付" . $suppname . "供应商" . $allmoney . "元";
				$cactlisttype = "1";
				currentaccount ( 2, $suppid, '', $allmoney, $ctatmemo, $cactlisttype, $loginstaname, $listid, $listno, $date );				
				changemoney ( 2, $suppid, $allmoney, 1 ); //0代表正，1代表负数
							
			} 
			
			
			$query = "update tb_paycardstock set
	              fd_stock_state   = 9  ,  fd_stock_datetime     = now()                
	              where fd_stock_id = '$listid' ";
			$db->query ( $query ); //修改单据资料
			require ("../include/alledit.2.php");
			echo "<script>alert('审批成功!');location.href='$gotourl';</script>"; 
		}
		break;
	case "dellist" : //审批不通过
		$query = "update tb_paycardstock set fd_stock_state ='0',fd_stock_memo='$memo_z'  where fd_stock_id = '$listid' ";
		$db->query ( $query ); //修改单据资料
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "stock_sp", "jxcstock_sp.html" );

$query = "select * from tb_paycardstock 
          where fd_stock_id = '$listid'";
$db->query ( $query );
if ($db->nf ()) {
	$db->next_record ();
	$listid = $db->f(fd_stock_id); //id号  
	$listno = $db->f(fd_stock_no); //单据编号
	$suppno = $db->f(fd_stock_suppno); //供应商编号 
	$suppname = $db->f(fd_stock_suppname); //供应商名称
	$date = $db->f(fd_stock_date); //录单日期
	$memo_z = $db->f(fd_stock_memo); //备注
	$paymoney = $db->f(fd_stock_allmoney); //付款金额    
	$ldr = $db->f (fd_stock_ldr); //付款金额  
	$dealwithman = $db->f(fd_stock_dealwithman); //付款金额  
	
	if ($paymoney == 0) {
		$paymoney = "";
	}

}

//商品名称
$query = "select * from tb_product
		left join  tb_producttype    on fd_producttype_id= fd_product_producttypeid
";
$db->query ( $query );
if ($db->nf ()) {
	while ( $db->next_record () ) {
		
		$arr_productname [$db->f ( fd_product_id )] = $db->f ( fd_product_name );
		$arr_productno [$db->f ( fd_product_id )] = $db->f ( fd_product_no );
		$arr_producttypename [$db->f ( fd_product_id )] = $db->f ( fd_producttype_name );
	}
}

//显示列表
$t->set_block ( "stock_sp", "prolist", "prolists" );
$query = "select * from tb_paycardstockdetail 
		
          where fd_skdetail_stockid = '$listid' order by fd_skdetail_id desc	";
$db->query ( $query );
$count = 0; //记录数
$vallquantity = 0; //总价
if ($db->nf ()) {
	while ( $db->next_record () ) {
		$vid = $db->f ( fd_skdetail_id );
		$vpaycardid = $db->f ( fd_skdetail_paycardid ); //商品id号
		$vprice = $db->f ( fd_skdetail_price ) + 0; //单价
		$vquantity = $db->f ( fd_skdetail_quantity ) + 0; //数量
		$vmemo = $db->f ( fd_skdetail_memo ); //备注
		$batches = $db->f ( fd_skdetail_batches );
		$vproductid = $db->f ( fd_skdetail_productid );
		
		$vproductname = $arr_productname [$vproductid];
		$vproductno = $arr_productno [$vproductid];
		$vproducttypename = $arr_producttypename [$vproductid];
		
		$vmoney = $vprice * $vquantity;
		$vallquantity += $vquantity;
		$vallmoney += $vmoney;
		
		$count ++;
		
		$vmoney = number_format ( $vmoney, 2, ".", "" );
		
		$trid = "tr" . $count;
		$imgid = "img" . $count;
		
		if ($s == 1) {
			$bgcolor = "#F1F4F9";
			$s = 0;
		} else {
			$bgcolor = "#ffffff";
			$s = 1;
		}
		
		$t->set_var ( array ("trid" => $trid, 
							"imgid" => $imgid,
							"count" => $count,
							"vid" => $vid,
							"vquantity" => $vquantity,
							"vmemo" => $vmemo,
							"bgcolor" => $bgcolor,
							"vprice" => $vprice, 
							"rowcount" => $count,
							"vmoney" => $vmoney,
							"vpaycardid" => $vpaycardid,
							"batches" => $batches, 
							"vproductname" => $vproductname, 
							"vproductno" => $vproductno,
							"vproducttypename" => $vproducttypename
							) );
		$t->parse ( "prolists", "prolist", true );
	}
} else {
	
	$t->parse ( "prolists", "", true );
}

$vallmoney = number_format ( $vallmoney, 2, ".", "" );

$t->set_var ( "listid", $listid ); //单据id 
$t->set_var ( "listno", $listno ); //单据编号 
$t->set_var ( "suppno", $suppno ); //供应商编号
$t->set_var ( "suppname", $suppname ); //供应商名称
$t->set_var ( "memo_z", $memo_z ); //备注
$t->set_var ( "paymoney", $paymoney ); //付款金额
$t->set_var ( "ldr", $ldr ); //付款金额
$t->set_var ( "dealwithman", $dealwithman ); //付款金额

$t->set_var ( "count", $count );
$t->set_var ( "vallquantity", $vallquantity );
$t->set_var ( "vallmoney", $vallmoney );

$t->set_var ( "date", $date ); //年


$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

$t->set_var ( "checkid", $checkid ); //批量删除商品ID   


// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "stock_sp" ); # 最后输出页面


function  isbuzero($alllenght,$chekvalue)//是否要补零
{
	  $cheklenght=strlen($chekvalue);
	if($alllenght!=$cheklenght)
	{
		
		 for($cheklenght;$cheklenght<$alllenght;$cheklenght++)
		{
			if($newvalue)
			{	
				$newvalue ="0".$newvalue;
			}else{
				$newvalue="0".$chekvalue;
			}
		} 
	}else{
		$newvalue=$chekvalue;
	} 
	return $newvalue;;
}

?>

