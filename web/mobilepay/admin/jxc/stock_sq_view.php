<?
$thismenucode = "2k201";
require ("../include/common.inc.php");

                                                 
$db  = new DB_test;

if($backstate == 0){
  $gourl = "tb_jxcstock_b.php" ;
}else if($backstate == 1){
	$gourl = "tb_jxcstock_sp_b.php" ;
}
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("stock_sq_view","stock_sq_view.html"); 

$query = "select * from tb_paycardstock 
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
  $dealwithman  = $db->f(fd_stock_dealwithman);   //经手人
  $ldr          = $db->f(fd_stock_ldr);           //经手人
  
  if($paymoney==0){
  	 $paymoney ="";
  }	   
}



//显示列表
$t->set_block("stock_sq_view", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail  
          left join tb_product on fd_product_id = fd_skdetail_productid
          left join tb_producttype on fd_producttype_id = fd_product_producttypeid
          where fd_skdetail_stockid = '$listid' "; 
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		
	     $vid          = $db->f(fd_skdetail_id);
	     $vpaycardid   = $db->f(fd_skdetail_paycardid);   
       $vprice       = $db->f(fd_skdetail_price)+0;   
       $vquantity    = $db->f(fd_skdetail_quantity)+0;
       $vmemo        = $db->f(fd_skdetail_memo);                 
	     $vbatches     = $db->f(fd_skdetail_batches);
       $vcommno      = $db->f(fd_product_no);
       $vcommname    = $db->f(fd_product_name);
       $vcommtype    = $db->f(fd_producttype_name);
                       
       $vmoney       = $vprice * $vquantity;   
                        
       $vallquantity += $vquantity;
       $vallmoney    += $vmoney;
	     
	     $vprice       = number_format($vprice, 2, ".", ""); 
       $vmoney       = number_format($vmoney, 2, ".", ""); 
	     	     
		   $count++;
		   
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
                         "vmoney"       => $vmoney        , 
						             "vbatches"     => $vbatches      ,
						             "vcommname"    => $vcommname     ,
						             "vcommno"      => $vcommno       ,
						             "vcommtype"    => $vcommtype     , 
						             "vpaycardid"   => $vpaycardid    , 						             
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
  $trid  = "tr1";
	$imgid = "img1";
  $t->set_var(array("trid"         => $trid      ,
                    "imgid"        => $imgid     ,
                    "vid"          => ""         ,
                    "vquantity"    => ""         ,
                    "vmemo"        => ""         ,
                    "bgcolor"      => "#ffffff"  ,
				            "vproductid"   => ""         ,
                    "vprice"       => ""         ,
				            "vbatches"     => ""         ,
				            "rowcount"     => ""         ,
                    "vmoney"       => ""         ,
                    "vcommname"    => ""         ,
				            "vcommno"      => ""         ,
				            "vcommtype"    => ""         ,  
				            "vpaycardid"   => ""         ,
		          ));
	$t->parse("prolists", "prolist", true);	
}      



$vallmoney = number_format($vallmoney, 2, ".", "");


$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("suppno"       , $suppno       );      //供应商编号
$t->set_var("suppname"     , $suppname     );      //供应商名称
$t->set_var("memo_z"       , $memo_z       );      //备注

$t->set_var("dealwithman"  , $dealwithman  );
$t->set_var("ldr"          , $ldr          );


$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("vallmoney" , $vallmoney );
$t->set_var("valldunquantity" , $valldunquantity );
$t->set_var("date"     , $date         );      //年
       
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      
                                            
$t->set_var("checkid"      , $checkid    );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "stock_sq_view");    # 最后输出页面

?>

