<?
$thismenucode = "2k204";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //调用生成单据编号文件                                                
require ("../function/changestorage.php");       //调用修改库存文件
require ("../function/changemoney.php");         //调用应收应付金额文件
require ("../function/commglide.php");           //调用商品流水帐文件
require ("../function/chanceaccount.php");       //调用修改帐户金额文件
require ("../function/cashglide.php");           //调用现金流水帐文件
require ("../function/currentaccount.php");      //调用往来对帐单文件

$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcsale_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

if(!empty($action) && !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_salelist where fd_selt_id = '$listid' 
  	          and (fd_selt_state = 9 or fd_selt_state = 1)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('该单据已经过帐或者等待审核，不能再修改，请查证')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}

switch($action){
	case "del":   //删除细节表数据
	  for($i=0;$i<count($checkid);$i++){
        if(!empty($checkid[$i])){
			$query = "select * from tb_salelistdetail  where fd_stdetail_id='$checkid[$i]'";
			$db->query($query);
			if ($db->nf()) {
				while ($db->next_record()) {
					if($stdetail_paycardid){$stdetail_paycardid .=",".$db->f(fd_stdetail_paycardid);}else{$stdetail_paycardid=$db->f(fd_stdetail_paycardid);} 
			
				}
			}
			$query="delete from tb_salelistdetail where fd_stdetail_id = '$checkid[$i]'";
			$db->query($query);
			
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$checkid[$i]' and fd_tmpsale_type='sale'";
		    $db->query($query);
        }
		
    }
		if($stdetail_paycardid){
		$arr_stdetail_paycardid=explode(",",$stdetail_paycardid);	
		changepaycardstate($arr_stdetail_paycardid,'1');	
		}
		
		countallsalepaycard($listid,'tb_salelist','tb_salelistdetail');
		
	echo "<script>alert('删除成功!');location.href='jxcsale.php?listid=$listid';</script>";
	break;
		case "del_one":   //删除细节表数据
			
			
			
			$old_paycardid=getdatepaycard("tb_salelistdetail","stdetail",$delseltid);
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
			$query="update  tb_salelistdetail set fd_stdetail_paycardid='$del_paycardid', fd_stdetail_quantity='$paycardnum' where fd_stdetail_id = '$delseltid'";
			$db->query($query);
			
			
			$query="update  tb_salelist_tmp set fd_tmpsale_paycardid='$del_paycardid'  where fd_tmpsale_seltid = '$delseltid' and fd_tmpsale_type='sale'";
      	    $db->query($query);
		
			$query = "update tb_paycard set fd_paycard_state='1' where fd_paycard_id='$delpaycard'";
			$db->query($query);
    
			countallsalepaycard($listid,'tb_salelist','tb_salelistdetail');
			
	echo "<script>alert('删除成功!');location.href='jxcsale.php?listid=$listid';</script>";
	  break;
	  case "new":  //新增数据
	
		  if (empty($listid)){  //如果单据id是不存在的
	  	
	  		listnumber_update(3);  //保存单据
	  	
	  	$query = "select * from tb_salelist where fd_selt_no = '$listno' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      
	      $query = "insert into tb_salelist(
	                fd_selt_no          ,   fd_selt_date  ,fd_selt_cusid   , fd_selt_cusno,
					fd_selt_cusname       ,	fd_selt_skfs  ,fd_selt_shaddress  ,fd_selt_ldr,fd_selt_dealwithman,
					fd_selt_saleprice     , fd_selt_allquantity,fd_selt_type
	                )values(
	                '$listno'           ,   '$date'      ,'$cusid'         , '$cusno',
					'$cusname'            ,   '$skfs'      ,'$shaddress','$ldr','$dealwithma',
					'$saleprice'     	    ,  '$allquantity' , '$type'
	                )";
	       $db->query($query);   //插入单据资料
	       $listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
	    }
		
	  }else{   //如果单据id号已经存在
	    $query = "select * from tb_salelist where fd_selt_no = '$listno' and fd_selt_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "单据编号已经存在！请查证！";
	    }else{
	      
	      $query = "update tb_salelist set 
	               fd_selt_no          = '$listno'    , fd_selt_cusid         = '$cusid'           ,
	               fd_selt_cusno      = '$cusno'      , fd_selt_cusname         = '$cusname'         ,
	               fd_selt_date        = '$date'      , fd_selt_shaddress      = '$shaddress'       ,
	               fd_selt_skfs        = '$skfs'      , fd_selt_ldr           ='$ldr'             ,
	               fd_selt_allquantity = '$allquantity', fd_selt_saleprice     = '$saleprice'       ,
	               fd_selt_type        = '$type',
				   fd_selt_dealwithman = '$dealwithman'
	               where fd_selt_id = '$listid' ";
	      $db->query($query);   //修改单据资料
	    }
	  }	
	   echo "<script>alert('暂存成功!');location.href='jxcsale.php?listid=$listid';</script>";
	  break;
}



//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if($end_action=="endsave"){
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$date){
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$action="";
	}
}

switch($end_action){
	case "endsave":    //最后提交数据

        $query = "update tb_salelist set
	                fd_selt_no           = '$listno'      ,  fd_selt_cusid       = '$cusid'      ,
	                fd_selt_cusno        = '$cusno'       ,  fd_selt_cusname     = '$cusname'    ,
	                fd_selt_date         = '$date'        , 
	                fd_selt_memo         = '$memo_z'      , fd_selt_skfs        = '$skfs'       ,
	                fd_selt_shaddress    = '$shaddress'    ,    fd_selt_ldr           ='$ldr',
				      fd_selt_dealwithman  = '$dealwithman'  ,   fd_selt_allquantity = '$allquantity',
				      fd_selt_saleprice    = '$saleprice'    ,
	                fd_selt_type         = '$type'
	                where fd_selt_id = '$listid' ";		
	      $db->query($query);   //修改单据资料
			$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
			$db->query($query);
			while($db->next_record())
			{
				$stdetail_id=$db->f(fd_stdetail_id);
					$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$stdetail_id' and fd_tmpsale_type='sale'";
					$db->query($query);
			}
		    $query = "update tb_salelist set  fd_selt_state  = '2'   where fd_selt_id  = '$listid' ";
	        $db->query($query);   //修改单据资料
	        require("../include/alledit.2.php");
	      echo "<script>alert('提交成功!');location.href='$gotourl';</script>";	

	    
	  break;
		case "dellist":    //删除整条单据数据
		 $query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
		$db->query($query);
		while($db->next_record())
		{
			$stdetail_id=$db->f(fd_stdetail_id);
			if($stdetail_paycardid){$stdetail_paycardid .=",".$db->f(fd_stdetail_paycardid);}else{$stdetail_paycardid=$db->f(fd_stdetail_paycardid);} 
			
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$stdetail_id' and fd_tmpsale_type='sale'";
			$db->query($query);
		}
			
			$arr_stdetail_paycardid=explode(",",$stdetail_paycardid);
			changepaycardstate($arr_stdetail_paycardid,'1');
	     
		 $query = "delete from tb_salelist where fd_selt_id = '$listid'";
	     $db->query($query);   //删除总表的数据
	     
	     $query = "delete from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
	     $db->query($query);   //删除细节表数据
		
	     require("../include/alledit.2.php");
	    echo "<script>alert('删除成功!');location.href='$gotourl';</script>";	
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("salelist","jxcsale.html"); 
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


if (empty($listid))
{		// 新增
  
   $date  = date( "Y-m-d",time());
	$tijiao_dis="disabled";

}else{
  $tijiao_dis="";
   $query = "select * from tb_salelist where fd_selt_id = '$listid'";
   $db->query($query);
      if($db->nf()){
   
   	   $db->next_record();
   	   $listid         = $db->f(fd_selt_id);            //id号  
       $listno         = $db->f(fd_selt_no);            //单据编号
       $cusid         = $db->f(fd_selt_cusid);        //供应商id
       $cusno         = $db->f(fd_selt_cusno);        //供应商编号 
       $cusname       = $db->f(fd_selt_cusname);      //供应商名称
       $date           = $db->f(fd_selt_date);          //录单日期
	   $ldr           = $db->f(fd_selt_ldr);          //录单人
	   $dealwithman    = $db->f(fd_selt_dealwithman);          //经手人
       $memo_z         = $db->f(fd_selt_memo);          //备注
   	   $skfs           = $db->f(fd_selt_skfs);          //备注
   	   $shaddress      = $db->f(fd_selt_shaddress);          //备注
       $allquantity    = $db->f(fd_selt_allquantity);
       $saleprice      = $db->f(fd_selt_saleprice);
          $type        = $db->f(fd_selt_type);
	
   }

}





//显示列表
$t->set_block("salelist", "prolist"  , "prolists"); 
$query = "select * from tb_salelistdetail 
          where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc"; 
$db->query($query);
$ishavepaycard=$db->nf();
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	$save_dis="";
	while($db->next_record()){		
	   $vid       = $db->f(fd_stdetail_id);
	   $vpaycardid   = $db->f(fd_stdetail_paycardid);    //商品id号
       $vprice    = $db->f(fd_stdetail_price)+0;   //单价
       $vquantity = $db->f(fd_stdetail_quantity)+0;//数量
       $vmemo     = $db->f(fd_stdetail_memo);      //备注
	   $vproductid    = $db->f(fd_stdetail_productid);  
       $vmoney = $vprice * $vquantity;   //金额
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
						 "count"          => $count           ,
                         "vquantity"    => $vquantity     ,
                         "vpaycardid" => $vpaycardid  ,
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         , 
						  "vproductid"   =>$vproductid
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
		  $t->parse("prolists", "", true);	
		 $save_dis="disabled";
}      
//获取单据编号   
if(empty($listno))
{
	$listno=listnumber_view(3);
}

//收款方式
$arr_skfs = array("现金","支票","电汇","承兑","在线支付");
$arr_skfsval = array("1","2","3","4","5");
$skfs = makeselect($arr_skfs,$skfs,$arr_skfsval);

$arr_type = array("auto","app");
$arr_typeval = array("系统补货","自订");
$type = makeselect($arr_typeval,$type,$arr_type);




/* 
//查询客户的受信额度
$query = "select fd_cus_credit , fd_cus_custypeid from tb_customer where fd_cus_id = '$cusid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$cus_credit = $db->f(fd_cus_credit);
	$cus_typeid = $db->f(fd_cus_custypeid);
	if(empty($error) && $cus_typeid==4){
		$error = "这个客户已经被总部列入黑名单！请注意！";
	}
} */
$vallmoney = number_format($vallmoney, 2, ".", "");
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("tmpid"       , $tmpid         );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("cusid"        , $cusid        );      //客户id号
$t->set_var("cusno"        , $cusno        );      //客户编号
$t->set_var("cusname"      , $cusname      );      //客户名称
$t->set_var("memo_z"       , $memo_z       );      //备注
$t->set_var("ldr"      , $ldr      );      //录单人
$t->set_var("dealwithman"       , $dealwithman       );      //经手人
$t->set_var("skfs"         , $skfs         );      //收款方式 
$t->set_var("shaddress"    , $shaddress    );
$t->set_var("productid"    , $productid     );      //商品ID
$t->set_var("ishavepaycard", $ishavepaycard     );      //商品ID
$t->set_var("save_dis", $save_dis     );      //商品ID
$t->set_var("allquantity", $allquantity     );
$t->set_var("saleprice", $saleprice     );
$t->set_var("type", $type     );




$t->set_var("isxydisabled"  , $isxydisabled  );      //屏蔽保存按钮 

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("vallmoney"    , $vallmoney    );      //总金额





                                                    
$t->set_var("date"         , $date         );      


$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   ); 
                                            
$t->set_var("checkid"      , $checkid      );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salelist");    # 最后输出页面



?>

