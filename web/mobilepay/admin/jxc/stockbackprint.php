<?
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;
$count = 0;
$t = new Template('.', "keep");
$t->set_file("stockbackprint","stockbackprint.html");

$query = "select * from tb_paycardstockbackdetail 
		 left join tb_paycardstockback on fd_stock_id=fd_skdetail_stockid
		 where fd_skdetail_stockid = '$listid' ";
$db->query($query);

if($db->nf()){
	while($db->next_record()){
	 $no           = $db->f(fd_stock_no);             //单据编号
	 $suppid       = $db->f(fd_stock_suppid);         //客户id
	 $suppno       = $db->f(fd_stock_suppno);         //客户编号 
	 $suppname     = $db->f(fd_stock_suppname);       //客户名称
	 $now          = $db->f(fd_stock_date);           //录单日期
	 $dealwithman  = $db->f(fd_stock_dealwithman);    //经手人
	 $ldr  = $db->f(fd_stock_ldr);    //录单人
	 $paycardid[]=$db->f(fd_skdetail_paycardid);//商品id
	}
}
$paycardid=implode(',',$paycardid);
if(!empty($paycardid)){
	$where="fd_paycard_id in ($paycardid)";
}else{
	$where = 0;
	}
$t->set_block ( "stockbackprint", "prolist", "prolists" );
/*$query = "select fd_product_no               as lprono,
                 fd_product_name 			 as lproname,
				 fd_skdetail_price           as lprice,
                 fd_skdetail_id              as lstdetailid,
                 fd_skdetail_quantity        as lquantity,
                 fd_skdetail_memo            as lpromemo
				 from tb_paycardstockbackdetail  
                 left join tb_product on fd_product_id = fd_skdetail_productid
          where fd_skdetail_stockid = '$listid'"; */
$query="select * from tb_paycard 
		left join tb_paycardstockbackdetail on fd_skdetail_productid=fd_paycard_product
		left join tb_product on fd_product_id = fd_skdetail_productid
		where $where group by fd_paycard_id";
$db->query($query);
$rows = $db->num_rows();
$count=0;//商品记录数
$prosumnum=0;//商品总数量
$prosumprice=0;//商品总价

if($db->nf()){
	while($db->next_record()){		
		   $lprono       = $db->f(fd_product_no);    //商品编号 
		   $lproname     = $db->f(fd_product_name);  //商品名称
		   $lprice       = $db->f(fd_paycard_stockprice);      //商品单价
		   $lstdetailid  = $db->f(fd_skdetail_id);        //商品资料ID号
		   $lquantity    = 1;  //商品数量
		   $lpromemo     = $db->f(fd_skdetail_memo);      //商品备注
		   $paycardkey     = $db->f(fd_paycard_key);      //商品设备号
		   $batches      = $db->f(fd_paycard_batches);  //商品批次
      	   
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
						 "paycardkey"      => $paycardkey           ,
                         "batches"      => $batches           ,
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
						"paycardkey"      => ""           ,
                         "batches"      => ""           ,  
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
$printtime = date("Y年m月d日 H时i分",mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")));//派单时间
$t->set_var ("lprosum"           , $lprosum       ); 
$t->set_var ("lprosumprice"           , $lprosumprice       );  
$t->set_var ("count"            , $count        );
$t->set_var ("lprosumnum"            , $lprosumnum        );

$t->set_var ("no"           , $no       ); 
$t->set_var ("suppname"           , $suppname       );  
$t->set_var ("now"            , $now        );
$t->set_var ("dealwithman"            , $dealwithman        );
$t->set_var ("ldr"            , $ldr        );
$t->set_var ("rmbname"            , $rmbname        );
$t->set_var("printtime",$printtime);
$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse("out", "stockbackprint"); //   # 最后输出页面

?> 