<?
//$thismenucode = "2k227";
require ("../include/common.inc.php");

require ("../function/functionlistnumber.php");  //调用列出单据编号文件                                                 
require ("../function/changemoney.php");         //调用应收应付金额文件
require ("../function/commglide.php");           //调用商品流水帐文件
require ("../function/chanceaccount.php");       //调用修改帐户金额文件
require ("../function/cashglide.php");           //调用现金流水帐文件
require ("../function/currentaccount.php");      //调用往来对帐单文件
                                 

$db  = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep");          //调用一个模版
$gourl = "tb_jxcstock_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$query = "select * from tb_paycardstockdetail
			left join tb_paycard on fd_paycard_batches = fd_skdetail_batches
			left join tb_paycardstock on fd_stock_id = fd_skdetail_stockid
          	where fd_skdetail_batches = '$batches'";
$db->query($query);
if($db->nf()){
   $db->next_record();                              //读取记录数据  
   $listno       = $db->f(fd_stock_no);             //单据编号
   $suppid       = $db->f(fd_stock_suppid);         //客户id
   $suppno       = $db->f(fd_stock_suppno);         //客户编号 
   $suppname     = $db->f(fd_stock_suppname);       //客户名称
   $stockorderno = $db->f(fd_stock_stockorderno);   //订单编号
   $date          = $db->f(fd_stock_date);           //录单日期
   $iskickback   = $db->f(fd_stock_iskickback);     //是否反冲
   $staname      = $db->f(fd_sta_name);             //录单人
   $state        = $db->f(fd_stock_state);          //状态

   $memo_z       = $db->f(fd_stock_memo);           //备注
   $paymoney     = $db->f(fd_stock_allmoney);       //付款金额



}        

$t->set_file("paycardview","paycardview.html"); 

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
$t->set_block("paycardview", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail 
		 where fd_skdetail_batches = '$batches' 	"; 
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		
		$vid       = $db->f(fd_skdetail_id);
		$vpaycardid   = $db->f(fd_skdetail_paycardid);    //商品id号
       $vprice    = $db->f(fd_skdetail_price)+0;   //单价
       $vquantity = $db->f(fd_skdetail_quantity)+0;//数量
       $vmemo     = $db->f(fd_skdetail_memo);      //备注
	    $batches        = $db->f(fd_skdetail_batches);	 
		$vproductid    = $db->f(fd_skdetail_productid);   	
       $vproductid=$arr_product[$vproductid];	
        $vmoney = $vprice * $vquantity;
       $vallquantity +=$vquantity;
       $vallmoney +=$vmoney;

       
		   $count++;

		 
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
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         ,
						"vpaycardid" => $vpaycardid  		,
						  "batches"     =>$batches			,	
						  "vproductid"  =>$vproductid,
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
     
		  $t->parse("prolists", "", true);	
}           

$vallmoney = number_format($vallmoney, 2, ".", ",");



/* //显示帐户选择列表
$query = "select * from tb_account where fd_account_id = '$accountid'" ;
$db->query($query);
if($db->nf()){
  $db->next_record();	
	$accountname  = $db->f(fd_account_name);    
} */

$t->set_var("suppid"        , $suppid         );      //客户ID
$t->set_var("suppno"        , $suppno         );      //客户编号
$t->set_var("suppname"      , $suppname       );      //客户名称
$t->set_var("stockorderno"  , $stockorderno   );      //客户名称            
     
$t->set_var("listid"        , $listid         );      //单据ID
$t->set_var("listno"        , $listno         );      //单据编号          
         
$t->set_var("memo_z"        , $memo_z         );      //单据备注  
$t->set_var("now"           , $now            );      //录单时间   
$t->set_var("kickback_disabled" , $kickback_disabled  );   

$t->set_var("dyj"           , $dyj            );      //吨运价
$t->set_var("paymoney"      , $paymoney       );      //付款金额

$t->set_var("vallmoney"      , $vallmoney       );    //总金额

$t->set_var("vallquantity"  , $vallquantity   );      //总数量
$t->set_var("count"         , $count          );      //记录数
$t->set_var("date"           , $date            );      //日 


 
$t->set_var("action"        , $action         );        
$t->set_var("gotourl"       , $gotourl        );      // 转用的地址
$t->set_var("error"         , $error          );         

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "paycardview");    # 最后输出页面



?>

