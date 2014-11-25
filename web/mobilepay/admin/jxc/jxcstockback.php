<?
$thismenucode = "2k212";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");

$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcstockback_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

if(!empty($action) or !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_paycardstockback where fd_stock_id = '$listid' 
  	          and (fd_stock_state = 1 or fd_stock_state = 9)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('该单据已经过帐或者等待审批中，不能再修改，请查证')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}

switch($action){
	case "del":   //删除细节表数据
	  for($i=0;$i<count($checkid);$i++){
        if(!empty($checkid[$i])){		
		
			$query = "select * from tb_paycardstockbackdetail  where fd_skdetail_id='$checkid[$i]'";
			$db->query($query);
			if ($db->nf()) {
				while ($db->next_record()) {
					if($skdetail_paycardid){$skdetail_paycardid .=",".$db->f(fd_skdetail_paycardid);}else{$skdetail_paycardid=$db->f(fd_skdetail_paycardid);} 
				}
			}	
			
			
			$query="delete from tb_paycardstockbackdetail where fd_skdetail_id = '$checkid[$i]'";
			$db->query($query);
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$checkid[$i]' and fd_tmpsale_type='stockback'";			
      	  
			$db->query($query);
        }
    }
		if($skdetail_paycardid)
		{
			$arr_skdetail_paycardid=explode(",",$skdetail_paycardid);	
			changepaycardstate($arr_skdetail_paycardid,'1');
		}
	countallbackpaycard($listid);//统计刷卡器数量,金额

   echo "<script>alert('删除成功!');location.href='jxcstockback.php?listid=$listid';</script>";	 
     break;
	case "del_one":   //删除细节表数据
	$old_paycardid=getdatepaycard("tb_paycardstockbackdetail","skdetail",$delskdetailid);

	foreach($old_paycardid as $value)
	{
		if($value !=$delpaycard)
		{
			if($del_paycardid)
			{
				$del_paycardid .=",".$value;
			}else{
				$del_paycardid=$value;
			}
			
		}
	}
	$arr_num=explode(",",$del_paycardid);
	$paycardnum=count($arr_num);
	$query="update  tb_paycardstockbackdetail set fd_skdetail_paycardid='$del_paycardid', fd_skdetail_quantity='$paycardnum' where fd_skdetail_id = '$delskdetailid'";
	$db->query($query);
	
	
	$query="update  tb_salelist_tmp set fd_tmpsale_paycardid='$del_paycardid'  where fd_tmpsale_seltid = '$delskdetailid' and fd_tmpsale_type='stockback'";
	$db->query($query);

	$query = "update tb_paycard set fd_paycard_state='1' where fd_paycard_id='$delpaycard'";
	$db->query($query);
		
	countallbackpaycard($listid);//统计刷卡器数量,金额

echo "<script>alert('删除成功!');location.href='jxcstockback.php?listid=$listid';</script>";
	  break;
	case "new":  //新增数据
	  if(empty($listid)){  //如果单据id是不存在的
	  	
	  		listnumber_update(2);  //保存单据
	  	
	  	$query = "select * from tb_paycardstockback where fd_stock_no = '$listno' ";
		
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      $query = "insert into tb_paycardstockback(
	                fd_stock_no          ,   fd_stock_suppid     , fd_stock_suppno           ,
	                fd_stock_suppname    ,   fd_stock_date       , fd_stock_ldr         ,
	                fd_stock_dealwithman ,   fd_stock_memo        
	                )values(
	                '$listno'           ,   '$suppid'          , '$suppno'             ,
	                '$suppname'         ,   '$date'            , '$loginstaname'       ,
	                '$dealwithman'      ,   '$memo_z'           
	                )";
	       $db->query($query);   //插入单据资料
	       $listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
	    }
	  }else{   //如果单据id号已经存在
	    $query = "select * from tb_paycardstockback where fd_stock_no = '$listno' and fd_stock_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      
	      $query = "update tb_paycardstockback set 
	               fd_stock_no          = '$listno'      ,  fd_stock_suppid           = '$suppid'           ,
	               fd_stock_suppno      = '$suppno'      ,  fd_stock_suppname         = '$suppname'         ,
	               fd_stock_date        = '$date'        ,   fd_stock_ldr             = '$loginstaname'     ,
	                fd_stock_dealwithman = '$dealwithman' ,  fd_stock_memo            = '$memo_z'
	               where fd_stock_id = '$listid' ";
	      $db->query($query);   //修改单据资料
	    }
	  }
		
		echo "<script>alert('保存成功!');location.href='jxcstockback.php?listid=$listid';</script>";
	  break;
}

switch($end_action){
	case "endsave":    //最后提交数据
		$query="select * from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid'";
		$db->query($query);
		if($db->nf()){
			 $arr_tempdate = explode("/",$date);
			 $date = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
			 if($loginopendate>$date){
				  $error = "错误：单据日期不能小于上月月结后本月开始日期";
			 }else{	
				$query = "update tb_paycardstockback set fd_stock_state = 1 where fd_stock_id = '$listid'";
			   //echo $query;
			   $db->query($query);  //修改单据状态为审批状态
			
				require("../include/alledit.2.php");
			
				
			echo "<script>alert('保存成功!');location.href='$gotourl';</script>";
			}
		}else{
			$error = "还没添加刷卡器,请添加刷卡器!";
		}
	  break;
	case "dellist":    //删除整条单据数据
		$query = "select * from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid' ";
			$db->query($query);
			while($db->next_record())
			{
				$skdetail_id=$db->f(fd_skdetail_id);
				if($skdetail_paycardid){$skdetail_paycardid .=",".$db->f(fd_skdetail_paycardid);}else{$skdetail_paycardid=$db->f(fd_skdetail_paycardid);} 
				$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$skdetail_id' and fd_tmpsale_type='stockback'";
				$db->query($query);
			}
		$arr_skdetail_paycardid=explode(",",$skdetail_paycardid);	
		changepaycardstate($arr_skdetail_paycardid,'1');
	    
		$query = "delete from tb_paycardstockback where fd_stock_id = '$listid'";
	     $db->query($query);   //删除总表的数据
	     
	     $query = "delete from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid' ";
	     $db->query($query);   //删除细节表数据
	     require("../include/alledit.2.php");
		 echo "<script>alert('删除成功!');location.href='$gotourl';</script>";	
	  break;
	default:
	  break;
}
//商品名称
$query="select * from tb_product";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		
		$arr_productname[$db->f(fd_product_id)]=$db->f(fd_product_name);
		$arr_productno[$db->f(fd_product_id)]=$db->f(fd_product_no);
	}
} 

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("stock","jxcstockback.html"); 
if(empty($listid))
{		// 新增
  $tijiao_dis="disabled";
  $date  = date( "Y-m-d",time());
}else{
	$tijiao_dis="";
   $query = "select * from tb_paycardstockback where fd_stock_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid         = $db->f(fd_stock_id);            //id号  
       $listno         = $db->f(fd_stock_no);            //单据编号
       $suppid         = $db->f(fd_stock_suppid);        //供应商id
       $suppno         = $db->f(fd_stock_suppno);        //供应商编号 
       $suppname       = $db->f(fd_stock_suppname);      //供应商名称
       $date           = $db->f(fd_stock_date);          //录单日期
       $memo_z         = $db->f(fd_stock_memo);          //备注
   	   $dealwithman    = $db->f(fd_stock_dealwithman);   //经手人   
   	   
   }
}


//显示列表
$t->set_block("stock", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockbackdetail 
          where fd_skdetail_stockid = '$listid' order by fd_skdetail_id desc "; 
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
	   
       $vproductid=$arr_productname[$vproductid];
		   
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
						 "count"          => $count           ,
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
		  $save_dis="";
	}
}else{
		$save_dis="disabled";
		  $t->parse("prolists", "", true);	
}      


if(empty($listno)){ //显示暂时的单据编号
	$listno=listnumber_view("2");
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
$t->set_var("ldr"          , $loginstaname       );  
$t->set_var("save_dis", $save_dis     );      //商品ID

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("vallmoney"    , $vallmoney );                                                   
$t->set_var("date"         , $date         );      //日期

    
$t->set_var("gotourl"   , $gotourl      );      // 转用的地址
$t->set_var("error"     , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   );                                      
$t->set_var("checkid"   , $checkid    );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "stock");    # 最后输出页面



?>

