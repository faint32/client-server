<?
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;
$count = 0;
$t = new Template('.', "keep");
$t->set_file("stockprint","stockprint.html");

$query = "select * from tb_paycardstock where fd_stock_id = '$listid' ";
$db->query($query);
if($db->nf()){
	$db->next_record();
	 $no           = $db->f(fd_stock_no);             //单据编号
	 $suppid       = $db->f(fd_stock_suppid);         //客户id
	 $suppno       = $db->f(fd_stock_suppno);         //客户编号 
	 $suppname     = $db->f(fd_stock_suppname);       //客户名称
	 $now          = $db->f(fd_stock_date);           //录单日期
	 $dealwithman  = $db->f(fd_stock_dealwithman);    //经手人
	 $ldr 		   = $db->f(fd_stock_ldr);    //录单人
}

//显示商品列表
$t->set_block("stockprint", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail  
                 left join tb_product on fd_product_id = fd_skdetail_productid
          where fd_skdetail_stockid = '$listid'"; 
$db->query($query);
$rows = $db->num_rows();
$count=0;//商品记录数
$prosumnum=0;//商品总数量
$prosumprice=0;//商品总价

if($db->nf()){
	while($db->next_record()){		
		   $lprono       = $db->f(fd_product_no);    //商品编号 
		   $lproname     = $db->f(fd_product_name);  //商品名称
		   $batches      = $db->f(fd_skdetail_batches);  //商品条形码
		   $lprice       = $db->f(fd_skdetail_price);      //商品单价
		   $lstdetailid  = $db->f(fd_skdetail_id);        //商品资料ID号
		   $lquantity    = $db->f(fd_skdetail_quantity);  //商品数量
		   $lpromemo     = $db->f(fd_skdetail_memo);      //商品备注
		 	$paycardkey     = $db->f(fd_skdetail_paycardid);
		    
      		 $lprosum      = $lquantity*$lprice;              //商品总价
		   $lprosumnum   = $lquantity+$lprosumnum;          //统计商品总数量
		   $lprosumprice = $lprosum+$lprosumprice;        //统计商品总价
		    $lprosum  = number_format($lprosum, 2, ".", "");
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
		   
		   
		   $t->set_var(array("lproid"       => $lproid            ,
                         "lstdetailid"  => $lstdetailid       ,
                         "lprono"       => $lprono            ,
                         "lproname"     => $lproname          ,
                         "batches"      => $batches           ,
						 "paycardkey"      => $paycardkey           ,
                         "lprice"       => $lprice            ,    
		                     "lquantity"    => $lquantity         ,
				                 "lpromemo"     => $lpromemo          ,
				                 "lprosum"      => $lprosum           ,
				                 "trid"         => $trid             ,
				                 "imgid"        => $imgid            ,
				                 "count"        => $count            ,
				                 "lprosumnum"    => $lprosumnum        ,
				                 "lprosumprice"  => $lprosumprice      ,
				                 "bgcolor"      => $bgcolor           ,
				                 "datashow"     => ""                ,
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
      $t->set_var(array("lproid"       => ""           ,
                        "lstdetailid"  => ""           ,
                        "lprono"       => ""           , 
                        "lproname"     => ""           ,
                        "batches"      => ""           ,  
						"paycardkey"      => ""           ,
                        "lprice"       => ""           ,      
		                    "lquantity"    => ""           ,
				                "lpromemo"     => ""           ,    
				                "lprosum"      => ""           , 
				                "trid"         => "0"          ,
				                "imgid"        => ""           ,
				                "count"        => ""           ,
				                "lprosumnum"   => ""           ,
				                "lprosumprice" => ""           ,
				               
				                "datashow"     => "none"       ,
		              ));
		  $t->parse("prolists", "prolist", true);
}

$lprosumprice     = number_format($lprosumprice, 2, ".", "");

$t->set_var ("lprosum"           , $lprosum       ); 
$t->set_var ("lprosumprice"           , $lprosumprice       );  
$t->set_var ("count"            , $count        );
$t->set_var ("lprosumnum"            , $lprosumnum        );

$t->set_var ("no"           , $no       ); 
$t->set_var ("suppname"           , $suppname       );  
$t->set_var ("now"            , $now        );
$t->set_var ("dealwithman"            , $dealwithman        );
$t->set_var ("rmbname"            , $rmbname        );
$t->set_var ("ldr"            , $ldr        );
$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse("out", "stockprint"); //   # 最后输出页面

?> 