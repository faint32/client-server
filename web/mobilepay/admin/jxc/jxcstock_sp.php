<?
$thismenucode = "2k202";
require ("../include/common.inc.php");
require ("../function/changestorage.php"); //�����޸Ŀ���ļ�
require ("../function/changemoney.php"); //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php"); //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php"); //�����޸��ʻ�����ļ�
require ("../function/currentaccount.php"); //�����������ʵ��ļ�
require ("../function/checkstorage.php"); //���ü���Ƿ�Ҫ�����֧�� 

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
		echo "<script>alert('�õ��ݲ��ڴ˲��裬�������޸ģ����֤')</script>";
		$action = "";
		$end_action = "";
	}
}

//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if($end_action == "endsave"){	
	$listdate = $date;	
	$todaydate = date ( "Y-m-d", mktime ( "0", "0", "0", date ( "m", mktime () ), date ( "d", mktime () ), date ( "Y", mktime () ) ) );
	if($todaydate < $listdate) {
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$end_action = "";
	}
}

switch ($end_action) {
	case "endsave" : //����ύ����
		if($loginopendate > $date){
			$error = "���󣺵������ڲ���С�������½���¿�ʼ����";
		}else{
			$query = "select * from tb_paycardstock where fd_stock_id = '$listid'";
			$db->query ( $query );
			if($db->nf ()){
				$db->next_record ();
				$listno    = $db->f ( fd_stock_no );          //���ݱ��
				$suppid    = $db->f ( fd_stock_suppid );      //��Ӧ��id��
				$suppno    = $db->f ( fd_stock_suppno );      //��Ӧ�̱�� 
				$suppname  = $db->f ( fd_stock_suppname );    //��Ӧ������
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
					   
					   //�޸Ĳֿ�
	           updatestorage($productid,$quantity,$price,$storageid,0);  //0��������1����
					     
					   //��Ʒ��ˮ��
					   $cogememo = $date."�ɹ�".$productname."����".$quantity."��ˢ����,ˢ�����豸��Ϊ".$paycardid;
					   $cogelisttype = "1";
					   $cogetype = 0; //0Ϊ���� �� 1Ϊ����
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
					     	             '$date ��ˢ�����ɹ����','$date','1'
					     	           )";
					     	  $db->query($query);
					     }  
				} 

				//���ɷ����������ʵ�
				$ctatmemo = "Ӧ��" . $suppname . "��Ӧ��" . $allmoney . "Ԫ";
				$cactlisttype = "1";
				currentaccount ( 2, $suppid, '', $allmoney, $ctatmemo, $cactlisttype, $loginstaname, $listid, $listno, $date );				
				changemoney ( 2, $suppid, $allmoney, 1 ); //0��������1������
							
			} 
			
			
			$query = "update tb_paycardstock set
	              fd_stock_state   = 9  ,  fd_stock_datetime     = now()                
	              where fd_stock_id = '$listid' ";
			$db->query ( $query ); //�޸ĵ�������
			require ("../include/alledit.2.php");
			echo "<script>alert('�����ɹ�!');location.href='$gotourl';</script>"; 
		}
		break;
	case "dellist" : //������ͨ��
		$query = "update tb_paycardstock set fd_stock_state ='0',fd_stock_memo='$memo_z'  where fd_stock_id = '$listid' ";
		$db->query ( $query ); //�޸ĵ�������
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "stock_sp", "jxcstock_sp.html" );

$query = "select * from tb_paycardstock 
          where fd_stock_id = '$listid'";
$db->query ( $query );
if ($db->nf ()) {
	$db->next_record ();
	$listid = $db->f(fd_stock_id); //id��  
	$listno = $db->f(fd_stock_no); //���ݱ��
	$suppno = $db->f(fd_stock_suppno); //��Ӧ�̱�� 
	$suppname = $db->f(fd_stock_suppname); //��Ӧ������
	$date = $db->f(fd_stock_date); //¼������
	$memo_z = $db->f(fd_stock_memo); //��ע
	$paymoney = $db->f(fd_stock_allmoney); //������    
	$ldr = $db->f (fd_stock_ldr); //������  
	$dealwithman = $db->f(fd_stock_dealwithman); //������  
	
	if ($paymoney == 0) {
		$paymoney = "";
	}

}

//��Ʒ����
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

//��ʾ�б�
$t->set_block ( "stock_sp", "prolist", "prolists" );
$query = "select * from tb_paycardstockdetail 
		
          where fd_skdetail_stockid = '$listid' order by fd_skdetail_id desc	";
$db->query ( $query );
$count = 0; //��¼��
$vallquantity = 0; //�ܼ�
if ($db->nf ()) {
	while ( $db->next_record () ) {
		$vid = $db->f ( fd_skdetail_id );
		$vpaycardid = $db->f ( fd_skdetail_paycardid ); //��Ʒid��
		$vprice = $db->f ( fd_skdetail_price ) + 0; //����
		$vquantity = $db->f ( fd_skdetail_quantity ) + 0; //����
		$vmemo = $db->f ( fd_skdetail_memo ); //��ע
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

$t->set_var ( "listid", $listid ); //����id 
$t->set_var ( "listno", $listno ); //���ݱ�� 
$t->set_var ( "suppno", $suppno ); //��Ӧ�̱��
$t->set_var ( "suppname", $suppname ); //��Ӧ������
$t->set_var ( "memo_z", $memo_z ); //��ע
$t->set_var ( "paymoney", $paymoney ); //������
$t->set_var ( "ldr", $ldr ); //������
$t->set_var ( "dealwithman", $dealwithman ); //������

$t->set_var ( "count", $count );
$t->set_var ( "vallquantity", $vallquantity );
$t->set_var ( "vallmoney", $vallmoney );

$t->set_var ( "date", $date ); //��


$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ
$t->set_var ( "error", $error );

$t->set_var ( "checkid", $checkid ); //����ɾ����ƷID   


// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "stock_sp" ); # ������ҳ��


function  isbuzero($alllenght,$chekvalue)//�Ƿ�Ҫ����
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

