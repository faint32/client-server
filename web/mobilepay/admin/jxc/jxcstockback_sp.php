<?
$thismenucode = "2k213";
require ("../include/common.inc.php");                                            
require ("../function/changestorage.php"); //调用修改库存文件
require ("../function/changemoney.php"); //调用应收应付金额文件
require ("../function/commglide.php"); //调用商品流水帐文件
require ("../function/chanceaccount.php"); //调用修改帐户金额文件
require ("../function/cashglide.php"); //调用现金流水帐文件
require ("../function/currentaccount.php"); //调用往来对帐单文件
//require ("../function/makeavgprice.php");        //调用生成平均单价
require ("../function/checkstorage.php"); //调用检测是否要补库存支出 
$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcstockback_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
if(!empty($end_action)){
	$query = "select * from tb_paycardstockback where fd_stock_id = '$listid' 
	          and fd_stock_state = 9";
	$db->query($query);
	if($db->nf()){
		echo "<script>alert('该单据已经过帐，不能再修改，请查证')</script>"; 
		$action ="";
		$end_action="";
	}
}


//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if($end_action=="endsave"){
	$arr_tempdate = explode("/",$date);
	$listdate = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$listdate){
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action="";
	}
}

switch($end_action){
	case "endsave":    //最后提交数据
		
	      if($loginopendate>$date){
	     	  $error = "错误：单据日期不能小于上月月结后本月开始日期";
	     }else{	
	       $query = "select * from tb_paycardstockback where fd_stock_id = '$listid'";
          $db->query($query);
          if($db->nf()){
          	   $db->next_record();
               $listno       = $db->f(fd_stock_no);            //单据编号
               $suppid       = $db->f(fd_stock_suppid);        //供应商id号
               $suppno       = $db->f(fd_stock_suppno);        //供应商编号 
               $suppname     = $db->f(fd_stock_suppname);      //供应商名称
               $allmoney     = $db->f(fd_stock_allmoney)+0;    //付款金额
			   $allquantity     = $db->f(fd_stock_allquantity)+0;    //付款金额
               $accountid    = $db->f(fd_stock_accountid);     //付款帐户
          }
	        $query = "select * from tb_paycardstockbackdetail 
					left join tb_product on fd_product_id=fd_skdetail_productid
	                  where fd_skdetail_stockid = '$listid'";
	        $db->query($query);
	        if($db->nf()){
			
	        	while($db->next_record()){
	        		$paycardid         = $db->f(fd_skdetail_paycardid);
	        		$quantity          = $db->f(fd_skdetail_quantity);
	        		$price             = $db->f(fd_skdetail_price);
					$productid         = $db->f(fd_skdetail_productid);
					$batches           = $db->f(fd_skdetail_batches);
					$productname       = $db->f (fd_product_name );	
					if($strpaycardid){$strpaycardid .=",".$paycardid;}else{$strpaycardid=$paycardid;}	

					
					//noteprice($listid,$price,$paycardid);
					

	        		//修改仓库
	        		updatestorage($productid,$quantity,$price,$storageid,1);  //0代表正、1代表负
	        		
					//商品流水帐
					//获取刷卡器设备号
					$paycardkey=getpaycardkey($paycardid);
	        		$cogememo = $date."退".$productname."类型,".$quantity."个刷卡器,刷卡器设备号为:".$paycardkey;
	        		$cogelisttype = "2";
	        		$cogetype = 1; //0为增加 ， 1为减少
	        		commglide($storageid , $productid , $quantity , $cogememo , $cogelisttype , $loginstaname , $listid , $listno , $cogetype ,$date);     		
	        		

	        	}

	           		$arr_backpaycarid=explode(",",$paycardid);
					foreach($arr_backpaycarid as $value) 
 	        		{
						
						$query="select fd_paycard_memo,fd_paycard_stockprice from tb_paycard where fd_paycard_id='$value'";
						$db->query($query);
						if($db->nf())
						{
							$db->next_record();
							$paycard_memo=$db->f(fd_paycard_memo);
							$stockprice=$db->f(fd_paycard_stockprice);
							
						}
						$query="update tb_paycard set fd_paycard_memo='$paycard_memo,$date 该刷卡器退货' 
						where fd_paycard_id='$value'";	
						$db->query($query);
					}  
					
	          //生成分行往来对帐单
	          $ctatmemo     = "退回给供应商".$suppname.$productname."类型".$allquantity."个刷卡器,退款为".$allmoney."元";
	          $cactlisttype = "2";
	          currentaccount(2 , $suppid , $allmoney ,0 , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$date );

	        	changemoney(2 , $suppid ,$allmoney , 0 );  //0代表正，1代表负数
	        		        		        	
	        	if($allmoney<>0){
	        	   //生成帐户流水帐
	        	 $chgememo     = "退".$productname."类型".$allquantity."个刷卡器收款".$allmoney."元";
	             $chgelisttype = "2";
	             $cogetype = 0; //0为增加 ， 1为减少
	        	  cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype ,$date);
	        	   
	        	   //修改帐户金额
	        	  // changeaccount($accountid , $allmoney , 1); //0代表进帐户，1代表出帐户
	          }
	        }
	         $query = "update tb_paycardstockback set
	                 fd_stock_state   = 9  ,  fd_stock_datetime = now()             
	                 where fd_stock_id = '$listid' ";
		
	       $db->query($query);   //修改单据资料
	        require("../include/alledit.2.php");
	      echo "<script>alert('审批成功!');location.href='$gotourl';</script>";
	     } 
	  break;
		case "dellist":    //审批不通过
       $query = "update tb_paycardstockback set fd_stock_state ='0'  where fd_stock_id = '$listid' ";
	     $db->query($query);   //修改单据资料
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("stock_sp","jxcstockback_sp.html"); 

$query = "select * from tb_paycardstockback 
          where fd_stock_id = '$listid'";
$db->query($query);
if($db->nf()){
	   $db->next_record();
	   $listid       = $db->f(fd_stock_id);            //id号  
     $listno       = $db->f(fd_stock_no);            //单据编号
     $suppno       = $db->f(fd_stock_suppno);        //供应商编号 
     $suppname     = $db->f(fd_stock_suppname);      //供应商名称
     $date         = $db->f(fd_stock_date);          //录单日期
     $memo_z       = $db->f(fd_stock_memo);          //备注
     $allmoney     = $db->f(fd_stock_allmoney)+0;    //付款金额 
	 $dealwithman  = $db->f(fd_stock_dealwithman);   //经手人
	  $ldr         = $db->f(fd_stock_ldr);   //经手人
     if($paymoney==0){
     	 $paymoney ="";
     }


	   
}


//商品名称
$query="select * from tb_product";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	
		$arr_product[$db->f(fd_product_id)]=$db->f(fd_product_name);
	}
} 


//显示列表
$t->set_block("stock_sp", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockbackdetail 
          where fd_skdetail_stockid = '$listid' "; 
$db->query($query);
$ishavepaycard=$db->nf();
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		
	   $vid       = $db->f(fd_skdetail_id);
	   $vpaycardid   = $db->f(fd_skdetail_paycardid);    //商品id号
       $vprice    = $db->f(fd_skdetail_price)+0;   //单价
       $vquantity = $db->f(fd_skdetail_quantity)+0;//数量
       $vmemo     = $db->f(fd_skdetail_memo);      //备注
	   $vproductid    = $db->f(fd_skdetail_productid);  
       $vmoney      = $vprice * $vquantity;   //金额
	   $vbatches        = $db->f(fd_skdetail_batches);
     
	  $vallquantity +=$vquantity;
       $vallmoney +=$vmoney;
	   
       $vproductid=$arr_product[$vproductid];
		   
		   $count++;
		   $vdunprice = number_format($vdunprice, 4, ".", "");
		   $vmoney = number_format($vmoney, 2, ".", "");

		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
		   
		   
		   
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $vid           ,
                         "vquantity"    => $vquantity     ,
                         "vpaycardid" => $vpaycardid  ,
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         , 
						  "vbatches"     =>$vbatches      ,
						  "vproductid"   =>$vproductid
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
   
		  $t->parse("prolists", "", true);	
}      



$vallmoney = number_format($vallmoney, 2, ".", "");


$t->set_var("ishavepaycard", $ishavepaycard     );      //商品ID
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("suppid"       , $suppid       );      //供应商id号
$t->set_var("suppno"       , $suppno       );      //供应商编号
$t->set_var("suppname"     , $suppname     );      //供应商名称
$t->set_var("memo_z"       , $memo_z       );      //备注
$t->set_var("dealwithman"  , $dealwithman        );  
$t->set_var("ldr"          , $ldr       );  

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("allmoney"    , $allmoney );                                                   
$t->set_var("date"         , $date         );      //日期

    
$t->set_var("gotourl"   , $gotourl      );      // 转用的地址
$t->set_var("error"     , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   );                                      
$t->set_var("checkid"   , $checkid    );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "stock_sp");    # 最后输出页面






?>

