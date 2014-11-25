<?
$thismenucode = "2k201";
require ("../include/common.inc.php");

require ("../function/functionlistnumber.php");

$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcstock_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

if(!empty($action) or !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_paycardstock where fd_stock_id = '$listid' 
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
       	 $query="delete from tb_paycardstockdetail where fd_skdetail_id = '$checkid[$i]'";
         $db->query($query); 
       }
    }
    
    countall($listid);
    
    echo "<script>alert('删除成功!');location.href='jxcstock.php?listid=$listid';</script>";	 
    
	  break;
	case "new":  //新增数据
	  if(empty($listid)){  //如果单据id是不存在的
	  	
	    listnumber_update(1);  //保存单据
	  	
	  	$query = "select * from tb_paycardstock where fd_stock_no = '$listno' ";
		
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      $query = "insert into tb_paycardstock(
	                fd_stock_no          ,   fd_stock_suppid     , fd_stock_suppno      ,
	                fd_stock_suppname    ,   fd_stock_date       , fd_stock_ldr         ,
	                fd_stock_dealwithman ,   fd_stock_memo        
	                )values(
	                '$listno'            ,   '$suppid'            , '$suppno'             ,
	                '$suppname'          ,   '$date'              , '$loginstaname'       ,
	                '$dealwithman'       ,   '$memo_z'           
	                )";
	       $db->query($query);   //插入单据资料
	       $listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
	    }
		
	  }else{   //如果单据id号已经存在
	    $query = "select * from tb_paycardstock where fd_stock_no = '$listno' and fd_stock_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{	      
	      $query = "update tb_paycardstock set 
	                fd_stock_no          = '$listno'      ,  fd_stock_suppid       = '$suppid'           ,
	                fd_stock_suppno      = '$suppno'      ,  fd_stock_suppname     = '$suppname'         ,
	                fd_stock_date        = '$date'        ,  fd_stock_ldr          = '$loginstaname'     ,
	                fd_stock_dealwithman = '$dealwithman' ,  fd_stock_memo         = '$memo_z'  
	                where fd_stock_id = '$listid' ";
	      $db->query($query);   //修改单据资料
	    }
	  }
	  if(!empty($paycardid) && !empty($listid)){
		  $query="select * from tb_paycardstockdetail";
		  $db->query($query);
		  if($db->nf()){
		  	while($db->next_record()){
		  		   $strpaycardid=$db->f(fd_skdetail_paycardid);
		  		   
		  		   $arr_paycardid=explode("-",$strpaycardid);
		  		   $arr_oldstartint = preg_replace('/[^0-9]/',"",$arr_paycardid[0]);
		  		   $arr_oldendint = preg_replace('/[^0-9]/',"",$arr_paycardid[1]);
		  		   $arr_oldcart = preg_replace('/[0-9]/',"",$arr_paycardid[0]);
		  		   for($arr_oldstartint;$arr_oldstartint<=$arr_oldendint;$arr_oldstartint++){
		  			    $arr_oldstockpaycardid[]=$arr_oldcart.$arr_oldstartint;
		  		   }
		  	}
			
		}
		
		  $arr_startint = preg_replace('/[^0-9]/',"",$startpaycardid);
		  $arr_endint = preg_replace('/[^0-9]/',"",$endpaycardid);
		  $arr_cart = preg_replace('/[0-9]/',"",$startpaycardid);
	
			  for($arr_startint;$arr_startint<=$arr_endint;$arr_startint++){
				   $arr_stockpaycardid[]=$arr_cart.$arr_startint;
			  }
			  
			  $query = "select * from tb_paycard";
			$db->query($query);   //判断在同一表中是否存在同一条商品数据
			if($db->nf()){
				  while($db->next_record()){
					   $arr_paycardid[] = $db->f(fd_paycard_key);
				  }
			}
				
			  $oldissamepaycard=array_intersect($arr_stockpaycardid,$arr_oldstockpaycardid);
			  $issamepaycard=array_intersect($arr_stockpaycardid, $arr_paycardid);
			
			  if($oldissamepaycard){$newissamepaycard=$oldissamepaycard;}
				if($issamepaycard){$newissamepaycard=$issamepaycard;}
				  if($newissamepaycard){
					$str_issamepaycard=implode(",",$newissamepaycard);
					  $error = "刷卡器设备号:<br/>".$str_issamepaycard."<br/>已入库,请查证!";
				  }else{
					$query  = "insert into tb_paycardstockdetail(
							   fd_skdetail_stockid    ,  fd_skdetail_paycardid  ,  
							   fd_skdetail_quantity   ,  fd_skdetail_price      ,  fd_skdetail_memo ,  
								   fd_skdetail_productid  ,  fd_skdetail_batches   					
							   )values(
							   '$listid'              ,  '$paycardid'           ,  
								   '$quantity'            ,  '$price'               ,  '$memo'          ,  
								   '$productid'           ,  '$batches'             
							   )";		  	
					$db->query($query);   //插入细节表 数据
					
					countall($listid);
									
					echo "<script>alert('暂存成功!');location.href='jxcstock.php?listid=$listid';</script>";	 
				}		  
			
	    }
	  break;
	case "edit":
	    $query = "select * from tb_paycardstock where fd_stock_no = '$listno' and fd_stock_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      $query = "update tb_paycardstock set 
	               fd_stock_no          = '$listno'      ,  fd_stock_suppid        = '$suppid'           ,
	               fd_stock_suppno      = '$suppno'      ,  fd_stock_suppname      = '$suppname'         ,
	               fd_stock_date        = '$date'        ,
	               fd_stock_dealwithman = '$dealwithman' ,  fd_stock_memo          = '$memo_z'      
	               where fd_stock_id = '$listid' ";
	      $db->query($query);   //修改单据资料
	    }
	    
	    if(!empty($id) and empty($vid)){
		    $query="select * from tb_paycardstockdetail where fd_skdetail_id <>'$id'";
		    $db->query($query);
		    if($db->nf()){
		    	while($db->next_record()){
		    		   $strpaycardid=$db->f(fd_skdetail_paycardid);
		    		   
		    		   $arr_paycardid=explode("-",$strpaycardid);
		    		   $arr_oldstartint = preg_replace('/[^0-9]/',"",$arr_paycardid[0]);
		    		   $arr_oldendint = preg_replace('/[^0-9]/',"",$arr_paycardid[1]);
		    		   $arr_oldcart = preg_replace('/[0-9]/',"",$arr_paycardid[0]);
		    		   for($arr_oldstartint;$arr_oldstartint<=$arr_oldendint;$arr_oldstartint++) {
		    		   	  $arr_oldstockpaycardid[]=$arr_oldcart.$arr_oldstartint;
		    		   }
		    	}
		    	
		    }

		    $arr_startint = preg_replace('/[^0-9]/',"",$startpaycardid);
		    $arr_endint = preg_replace('/[^0-9]/',"",$endpaycardid);
		    $arr_cart = preg_replace('/[0-9]/',"",$startpaycardid);
		    
		    for($arr_startint;$arr_startint<=$arr_endint;$arr_startint++){
		    	 $arr_stockpaycardid[]=$arr_cart.$arr_startint;
		    }
		
		    $query = "select * from tb_paycard";
	  	  $db->query($query);   //判断在同一表中是否存在同一条商品数据
	  	  if($db->nf()){
	  	  	while($db->next_record()){
			  	     $arr_paycardid[]=$db->f(fd_paycard_key);
			    }
	  	  }
			
		    $oldissamepaycard=array_intersect($arr_stockpaycardid,$arr_oldstockpaycardid);
		    $issamepaycard=array_intersect($arr_stockpaycardid, $arr_paycardid);
		    
		    if($oldissamepaycard){
		    	$newissamepaycard=$oldissamepaycard;
		    }
		    
		    if($issamepaycard){$newissamepaycard=$issamepaycard;}
	      	if($newissamepaycard){
		    	  $str_issamepaycard=implode(",",$newissamepaycard);
	      		$error = "刷卡器设备号:<br/>".$str_issamepaycard."<br/>已入库,请查证!";
	      	}else{
	      	  $query  = "update tb_paycardstockdetail set
	      	             fd_skdetail_paycardid   = '$paycardid' ,  fd_skdetail_productid='$productid'     ,    
		    			 fd_skdetail_quantity  = '$quantity'    ,  fd_skdetail_price  = '$price'       
	      	             where fd_skdetail_id = '$id' ";
	      	  $db->query($query);   //插入细节表 数据
		    	  
		    	  countall($listid);
		    	  
		    	  echo "<script>alert('修改成功!');location.href='jxcstock.php?listid=$listid';</script>";	 	
	        }		    	
	       }	 
	  break;
}

switch($end_action){
	case "endsave":    //最后提交数据
		$query="select *, sum(fd_skdetail_quantity) as allquantity from tb_paycardstockdetail where fd_skdetail_stockid = '$listid'group by fd_skdetail_stockid";
		$db->query($query);
		$count=$db->nf();
		if($db->nf()){
		while($db->next_record())
		{
			$allquantity=$db->f(allquantity);
		}
		}
		if($count){
			 $arr_tempdate = explode("/",$date);
			 $date = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
			 if($loginopendate>$date){
				  $error = "错误：单据日期不能小于上月月结后本月开始日期";
			 }elseif($allquantity>100000){
				$error = "刷卡器一次入库总数不能超过10万个!";
			 }else{
				 $query = "update tb_paycardstock set fd_stock_state = 1
				           where fd_stock_id = '$listid'";
			   $db->query($query);  //修改单据状态为审批状态
				 require("../include/alledit.2.php");		 
				 echo "<script>alert('保存成功!');location.href='$gotourl';</script>";	 
				
			}
		}else{
			$error = "还没添加刷卡器,请添加刷卡器!";
		}
	  break;
	case "dellist":    //删除整条单据数据
	     $query = "delete from tb_paycardstock where fd_stock_id = '$listid'";
	     $db->query($query);   //删除总表的数据
	     
	     $query = "delete from tb_paycardstockdetail where fd_skdetail_stockid = '$listid' ";
	     $db->query($query);   //删除细节表数据
	     require("../include/alledit.2.php");	     
	     echo "<script>alert('删除成功!');location.href='$gotourl';</script>";	
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
$t->set_file("stock","jxcstock.html"); 
if(empty($listid))
{		// 新增
   $action = "new";
   $date  = date( "Y-m-d",time());
$tijiao_dis="disabled";

}else{
$tijiao_dis="";
  $query = "select * from tb_paycardstock where fd_stock_id = '$listid'";
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

  if(empty($vid)){
  	 $action = "new";
	if(!empty($suppno))
	{
		$batches=makebatches($suppno,$listid);
	}
  }else{
    $query = "select * from tb_paycardstockdetail where fd_skdetail_id = '$vid' ";
    $db->query($query);
    if($db->nf()){
      $db->next_record();
      $paycardid      = $db->f(fd_skdetail_paycardid);    //商品id号
      $price          = $db->f(fd_skdetail_price)+0;      //单价
      $quantity       = $db->f(fd_skdetail_quantity)+0;   //数量
      $memo           = $db->f(fd_skdetail_memo);         //备注
      $id             = $db->f(fd_skdetail_id);    	  
      $batches        = $db->f(fd_skdetail_batches);       	  
      $paycardtype    = $db->f(fd_skdetail_paycardtype);    
	   $productid      = $db->f(fd_skdetail_productid);    
		 
		 $productname    = $arr_productname[$productid];
		 $productno      = $arr_productno[$productid];
		 $arr_paycardid  = explode("-",$paycardid);
		 $startpaycardid = $arr_paycardid[0];
		 $endpaycardid   = $arr_paycardid[1];			
      	  
      if($price==0){
      	 $price ="";
      }
     
      if($quantity==0){
        $quantity ="";
      }
      	  
      $money = $price * $quantity;  	   
      $action = "edit";
    }
  }
}

//显示列表
$t->set_block("stock", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail  
          left join tb_product on fd_product_id = fd_skdetail_productid
          left join tb_producttype on fd_producttype_id=fd_product_producttypeid
          where fd_skdetail_stockid = '$listid' order by fd_skdetail_id desc "; 
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
						 "count"          => $count           ,
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
$isendsave='';
}else{
$isendsave='disabled';
     $trid  = "tr1";
		 $imgid = "img1";
     $t->set_var(array("trid"          => $trid    ,
                        "imgid"        => $imgid   ,
                        "vid"          => ""       ,
						"count"          => ""       ,
                        "vquantity"    => ""       ,
                        "vmemo"        => ""       ,
                        "bgcolor"      => "#ffffff",
						            "vproductid"   => ""       ,
                        "vprice"       => ""       ,
						            "vbatches"     => ""       ,
						            "rowcount"     => ""       ,
                        "vmoney"       => ""       ,
                        "vcommname"    => ""       ,
						            "vcommno"      => ""       ,
						            "vcommtype"    => ""       ,  
						            "vpaycardid"   => ""       ,
				          ));
		 $t->parse("prolists", "prolist", true);	
}      


if(empty($listno)){ //显示暂时的单据编号
	$listno=listnumber_view("1");
}


$vallmoney = number_format($vallmoney, 2, ".", "");

$t->set_var("isendsave"          , $isendsave             );  
$t->set_var("tijiao_dis"          , $tijiao_dis     );  

$t->set_var("listid"          , $listid             );     
$t->set_var("id"              , $id                 );     
$t->set_var("listno"          , $listno             );     
$t->set_var("suppid"          , $suppid             );     
$t->set_var("suppno"          , $suppno             );     
$t->set_var("suppname"        , $suppname           );     
$t->set_var("memo_z"          , $memo_z             );     
$t->set_var("dealwithman"     , $dealwithman        );  
$t->set_var("ldr"             , $loginstaname       );  

$t->set_var("startpaycardid"  , $startpaycardid     );     
$t->set_var("endpaycardid"    , $endpaycardid       );     
$t->set_var("count"           , $count              );
$t->set_var("vallquantity"    , $vallquantity       );
$t->set_var("vallmoney"       , $vallmoney          );
$t->set_var("quantity"        , $quantity           );  


$t->set_var("productid"       , $productid          );      
$t->set_var("productname"     , $productname        );      
$t->set_var("productno"       , $productno          );      
$t->set_var("batches"         , $batches            );      

$t->set_var("paycardid"       , $paycardid          );     
$t->set_var("price"           , $price              );     
$t->set_var("money"           , $money              );                                                     
$t->set_var("date"            , $date               );  
   
$t->set_var("action"          , $action             );        
$t->set_var("gotourl"         , $gotourl            );      
$t->set_var("error"           , $error              );      
                                         
$t->set_var("checkid"         , $checkid            ); 

//判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "stock");    # 最后输出页面

//统计刷卡器数量
function countall($listid){
	 $db  = new DB_test;  
	 
	 $query = "select sum(fd_skdetail_quantity) as allquantity,sum(fd_skdetail_quantity*fd_skdetail_price) as allmoney
	           from tb_paycardstockdetail 
	           where fd_skdetail_stockid = '$listid'
	           group by fd_skdetail_stockid
	          "; 
	 $db->query($query);
	 if($db->nf()){
	   $db->next_record();
	   $allquantity = $db->f(allquantity);
	   $allmoney = $db->f(allmoney);	  
	   
	   $query = "update tb_paycardstock set fd_stock_allmoney = '$allmoney',fd_stock_allquantity  = '$allquantity'
	             where fd_stock_id = '$listid'
	            ";  	   
	   $db->query($query);
	 }  	 
}


?>

