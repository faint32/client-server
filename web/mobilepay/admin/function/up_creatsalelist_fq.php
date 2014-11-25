<?

//自动生成单据单据编号
function listnumber_update(){
	$db  = new DB_erp;	
	$listtype = 3;
	$organid = 1;
	$organno = 'a001';
	
	$ltctcount=1;
	$query = "select * from tb_organlistnocount where fd_olnc_listtype = '$listtype' 
	          and fd_olnc_organid = '$organid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$ltctcount = $db->f(fd_olnc_count)+1;
		$olncid    = $db->f(fd_olnc_id);
		
		$query = "update tb_organlistnocount set fd_olnc_count = '$ltctcount'
	            where fd_olnc_id = '$olncid' ";
	  $db->query($query);
	}else{
	  $query = "insert into tb_organlistnocount(
	            fd_olnc_count    , fd_olnc_organid , fd_olnc_sdcrid ,
	            fd_olnc_listtype
	            )values(
	            1                 , '$organid'     , '$sdcrid'      ,
	            '$listtype'
	            )";
	  $db->query($query);
	}
	
	if($ltctcount<100000 and $ltctcount>=10000){
	  $endltctcount = "0".$ltctcount;
	}elseif($ltctcount<10000 and $ltctcount>=1000){
	  $endltctcount = "00".$ltctcount;
	}elseif($ltctcount<1000 and $ltctcount>=100){
	  $endltctcount = "000".$ltctcount;
	}elseif($ltctcount<100 and $ltctcount>=10){
	  $endltctcount = "0000".$ltctcount;
	}elseif($ltctcount<10 and $ltctcount>=1){
	  $endltctcount = "00000".$ltctcount;
	}else{
	  $endltctcount = $ltctcount;
	}
  $listno = $organno."-".$endltctcount;

	return $listno;
}


//自动生成收款单号
function listnumber_update_fqsq(){
	$db  = new DB_erp;	
	$listtype = 64;
	$organid = 1;
	$organno = 'a001';
	
	$query = "select * from tb_listnumber where fd_ltnr_typeid = '$listtype'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$qianxu      = $db->f(fd_ltnr_qianxu);
	    
	  $ltctcount=1;
	  $query = "select * from tb_listcount where fd_ltct_ltnrtypeid = '$listtype' and fd_ltct_organid = '$organid' ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$ltctcount = $db->f(fd_ltct_count)+1;
	  	$ltctid    = $db->f(fd_ltct_id);

	  	$query = "update tb_listcount set
	              fd_ltct_count = '$ltctcount'
	              where fd_ltct_id = '$ltctid' ";
	    $db->query($query);
	  }else{
	    $query = "insert into tb_listcount(
	              fd_ltct_count , fd_ltct_organid , fd_ltct_ltnrtypeid
	              )values(
	              1             , '$organid'      , '$listtype'
	              )";
	    $db->query($query);
	  }
	  
	  if($ltctcount<100000 and $ltctcount>=10000){
	    $endltctcount = "0".$ltctcount;
	  }elseif($ltctcount<10000 and $ltctcount>=1000){
	    $endltctcount = "00".$ltctcount;
	  }elseif($ltctcount<1000 and $ltctcount>=100){
	    $endltctcount = "000".$ltctcount;
	  }elseif($ltctcount<100 and $ltctcount>=10){
	    $endltctcount = "0000".$ltctcount;
	  }elseif($ltctcount<10 and $ltctcount>=1){
	    $endltctcount = "00000".$ltctcount;
	  }else{
	    $endltctcount = $ltctcount;
	  }
    $listno= $qianxu."_".$organno."_".$endltctcount;
	}
	
	return $listno;
}

function order_creat_salelist_fq($orderid){
	  $db     = new DB_test;
	  $db2    = new DB_test;
	  $dberp  = new DB_erp;	
	  
	  //读取订单
	  $query = "select fd_order_id,fd_order_sdcrid,fd_order_isfp,fd_order_memo,fd_order_paymoney,fd_order_zspaymoney,
	                   fd_organmem_cusid,fd_order_receiveadderss,fd_provinces_name,fd_city_name,fd_county_name,fd_order_shman,
	                   fd_order_ystype,fd_order_no,fd_organmem_mcardid,fd_order_isfq,fd_order_wlfy,fd_order_wlzftype,fd_order_mobilephone,
	                   fd_order_wlzftype,fd_order_ysgsid,fd_order_allmoney,fd_order_mscid
	            from web_order 
	            left join tb_organmem on fd_organmem_id = fd_order_memeberid
	            left join tb_provinces on fd_order_province = fd_provinces_code
	            left join tb_city on fd_order_city = fd_city_code
	            left join tb_county on fd_order_county = fd_county_code
	            where fd_order_id = '$orderid'";
	  $db->query($query); 
	  if($db->nf()){
	    $db->next_record();
	    $sdcrid         = $db->f(fd_order_sdcrid);
	    $ishp           = $db->f(fd_order_isfp);
	    $memo           = $db->f(fd_order_memo);
	    $paymoney       = $db->f(fd_order_paymoney);
	    $zspaymoney     = $db->f(fd_order_zspaymoney);
	    $cusid          = $db->f(fd_organmem_cusid);
	    $receiveadderss = $db->f(fd_order_receiveadderss);
	    $province       = $db->f(fd_provinces_name);
	    $city           = $db->f(fd_city_name);
	    $county         = $db->f(fd_county_name);
	    $consignee      = $db->f(fd_order_receiver);
	    $csstate        = $db->f(fd_order_ystype);
	    $orderno        = $db->f(fd_order_no);
	    $cardid         = $db->f(fd_organmem_mcardid);
	    $isfq           = $db->f(fd_order_isfq);
	    $wlyf           = $db->f(fd_order_wlfy);
	    $wlzftype       = $db->f(fd_order_wlzftype);
	    $mobilephone    = $db->f(fd_order_mobilephone);
	    $mscid          = $db->f(fd_order_mscid);
	        	    
	    $webpaymoney    = $paymoney+$zspaymoney;
	    
	    if($ishp == 1){
	    	$ishavetax = 1;
	    }else{
	      $ishavetax = 2;
	    }
	     
	    //收货地址
	    $shplace = $province.$city.$county.$receiveadderss;
	    
	    if($mscid > 0){ 
	    	$mscompanyid = $mscid;
	    }else{
	      if($sdcrid == 1){
	      	$mscompanyid = 1;//广州市明盛物流有限公司
	      }else if($sdcrid == 2){
	      	$mscompanyid = 4;//上海粤琳珠物流有限公司
	      }else if($sdcrid == 3){
	      	$mscompanyid = 13;//廊坊市明盛纸业有限公司
	      }else if($sdcrid == 4){
	      	$mscompanyid = 9;
	      }else if($sdcrid == 5){
	      	$mscompanyid = 11;
	      }
	    }
	    
	    if($csstate == 1){//代办运输
	      $trafficmodel = 3;	
	    }else{
	      $trafficmodel = 2;	
	    }
	    
	    //插入销售单
	    $query  = "select * from tb_customer where fd_cus_id = '$cusid'";
	    $dberp->query($query); 
	    if($dberp->nf()){
	      $dberp->next_record();
	      $cusno = $dberp->f(fd_cus_no);
	      $cusname = $dberp->f(fd_cus_allname);
	    }
	    
	    $query  = "select * from tb_salelist_2010 where fd_selt_weborderid = '$orderid'";
	    $dberp->query($query); 
	    if(!$dberp->nf()){	    
	      $listno=listnumber_update();	    
	      $query = "insert into tb_salelist_2010(
	                fd_selt_no          ,   fd_selt_cusid        , fd_selt_cusno      ,
	                fd_selt_cusname     ,   fd_selt_organid      , fd_selt_date       , 
	                fd_selt_memo        ,   fd_selt_sdcrid       , fd_selt_iswebsale  ,
	                fd_selt_ishavetax   ,   fd_selt_trafficmodel , fd_selt_consignee  ,   
	                fd_selt_shplace     ,   fd_selt_mscompanyid  , fd_selt_skfs       ,
	                fd_selt_state       ,   fd_selt_webpaymoney  , fd_selt_weborderid ,
	                fd_selt_weborderno  ,   fd_selt_cardid       , fd_selt_wlyf       ,
	                fd_selt_wlzftype    ,   fd_selt_shphone      , fd_selt_ysgsid   	               
	                )values(
	                '$listno'           ,   '$cusid'             , '$cusno'           ,
	                '$cusname'          ,   '1'                  , now()              ,
	                '$memo'             ,   '$sdcrid'            , '1'                ,
	                '$ishavetax'        ,   '$trafficmodel'      , '$consignee'       ,
	                '$shplace'          ,   '$mscompanyid'       , '1'                ,
	                '92'                ,   '$webpaymoney'       , '$orderid'         ,
	                '$orderno'          ,   '$cardid'            , '$wlyf'            ,
	                '$wlzftype'         ,   '$mobilephone'       , '$ysgsid'
	                )";
	      $dberp->query($query); 
	      $listid = $dberp->insert_id();
	      
	      $query = "update web_order set fd_order_seltid = '$listid',fd_order_seltdate=now()  where fd_order_id = '$orderid'";
	      $db->query($query);
	      
	      $query  = "select sum(fd_orderdetail_quantity) as quantity,fd_orderdetail_icommid,fd_orderdetail_icommbar,fd_orderdetail_icommname,
	                 fd_orderdetail_fqmoney,fd_orderdetail_price,fd_produre_relation3,fd_orderdetail_storageid,fd_orderdetail_productid
	                 from web_orderdetail 
	                 left join tb_produre on fd_orderdetail_icommid = fd_produre_id
	                 where fd_orderdetail_orderid = '$orderid'
	                 group by fd_orderdetail_icommid
	                ";
	      $db->query($query); 
	      if($db->nf()){
	        while($db->next_record()){
	        	   $quantity = $db->f(quantity);	        	   
	        	   $commid    = $db->f(fd_orderdetail_icommid);
	        	   $commbar   = $db->f(fd_orderdetail_icommbar);
	        	   $commname  = $db->f(fd_orderdetail_icommname);
	        	   $fqmoney   = $db->f(fd_orderdetail_fqmoney);
	        	   $price     = $db->f(fd_orderdetail_price);
	        	   
	        	   $ocommid   = $db->f(fd_orderdetail_productid);
	        	   	        	  	        	   
          	   $relation3 = $db->f(fd_produre_relation3); 
	        	   
	        	   $storageid = $db->f(fd_orderdetail_storageid);
	        	    
               $dunshu = changekg2($relation3 , '令' , $quantity);                 
               $money = ($price+$fqmoney)*$dunshu;
               $price = round($money/$quantity,3);
	        	   
	        	   //判断成本价
	        	   $query = "select * from tb_storagecost 
	        	             where fd_sect_commid = '$commid' and fd_sect_organid = '1' and fd_sect_sdcrid = '$sdcrid'";
	        	   $dberp->query($query);
	        	   if(!$dberp->nf()){
	        	     $query = "select * from tb_storagecost 
	        	               where fd_sect_commid = '$ocommid' and fd_sect_organid = '1' and fd_sect_sdcrid = '$sdcrid'";
	        	     $dberp->query($query);  
	        	     if($dberp->nf()){
	        	     	 $dberp->next_record();
	        	     	 $ocost = $dberp->f(fd_sect_cost);
	        	     	 $costmoney = ($ocost+$fqmoney)*$dunshu;
	        	     	 $cost = round($costmoney/$quantity,3);
	        	       
	        	       $query="INSERT INTO tb_storagecost (
 	                         fd_sect_organid      , fd_sect_sdcrid  , fd_sect_commid   ,
 	                         fd_sect_cost   
                           )VALUES (
                           '1'                  , '$sdcrid'       , '$commid'        , 
                           '$cost'         
                           )";
	                 $dberp->query($query);	
	        	     }        
	        	   }
	        	   
	        	   	        	         	   	 		        	   	      	   	        	       	   
	        	   $query="INSERT INTO tb_salelistdetail_2010(
 	                     fd_stdetail_seltid     , fd_stdetail_commid  , fd_stdetail_commname   ,
 	                     fd_stdetail_commbar    , fd_stdetail_unit    , fd_stdetail_quantity   , 
 	                     fd_stdetail_price      , fd_stdetail_storageid      
                       )VALUES (
                       '$listid'              , '$commid'           , '$commname'            , 
                       '$commbar'             , '令'                , '$quantity'            , 
                       '$price'               , '$storageid'       
                       )";
	             $dberp->query($query);	      	   
	        }
	      }
	      
	      wgsalegz($listid);
	      //$gotourl = "http://www.papersystem.cn/ms2011/sale/up_wgsalegz.php?listid=".$listid;
	      //echo "<script>location='".$gotourl."'</script>";
	    
	    }
	  }
}

/*
function order_creat_fqsq($orderid){
	  $db     = new DB_test;
	  $db2    = new DB_test;
	  $dberp  = new DB_erp;	
	  
	  $query="select * from web_order  where fd_order_id = '$orderid'";
	  $db->query($query);
    if($db->nf()){
		  $db->next_record();
	    $sdcrid   = $db->f(fd_order_sdcrid); 
	    $ddclr    = $db->f(fd_order_ddclr);  
	    $orderno  = $db->f(fd_order_no);  
	  }
	  
	  $listno = listnumber_update_fqsq();	  
	  $query = "insert into tb_branchcut(
	            fd_bhct_no           ,   fd_bhct_cgorganid    , fd_bhct_date        , 
	            fd_bhct_memo         ,   fd_bhct_xsorganid    , fd_bhct_sdcrid      ,
	            fd_bhct_cgstaname    ,   fd_bhct_state        , fd_bhct_weborderid  ,
	            fd_bhct_weborderno   ,   fd_bhct_tjdate
	            )values(
	            '$listno'            ,   '1'                  , now()               ,
	            '$memo_z'            ,   '1'                  , '$sdcrid'           ,
	            '$ddclr'             ,   '1'                  , '$orderid'          ,
	            '$orderno'           ,   now()
	            )";
	  $dberp->query($query);   //插入单据资料
	  $fqsqid = $dberp->insert_id(); 
	  
	  $query="select * from web_orderdetail  
	          left join tb_produre on fd_produre_id = fd_orderdetail_productid
	          left join tb_kgweight  on fd_kgweight_id = fd_produre_kgweight
	          where fd_orderdetail_orderid = '$orderid'";
	  $db->query($query);
    if($db->nf()){
		  while($db->next_record()){
	         $commtypeid  = $db->f(fd_produre_catalog);
	         $trademarkid = $db->f(fd_produre_trademarkid);
	         $level       = $db->f(fd_produre_level);
	         $kgname      = $db->f(fd_kgweight_name);
	         $long        = $db->f(fd_orderdetail_fqlong);
	         $width       = $db->f(fd_orderdetail_fqwidth);
	         $unitid      = '令';
	         $fqshuliang  = $db->f(fd_orderdetail_quantity);
	         $spec        = $db->f(fd_produre_spec);
	         
	    
		  	   $lingshu = $fqshuliang;
	         
	         $dunshu = ($kgname*$long*$width*$lingshu)/(2000*1000*1000);
	         
	         $alldunshu += $dunshu;
	         
	         $query  = "insert into tb_branchcutdetail(
	     	              fd_bhctdl_bhctid      ,  fd_bhctdl_categoryid  ,  fd_bhctdl_trademarkid  ,fd_bhctdl_level,
	     	              fd_bhctdl_kgname      ,  fd_bhctdl_long        ,  fd_bhctdl_width        ,
	     	              fd_bhctdl_unit        ,  fd_bhctdl_quantity    ,  fd_bhctdl_memo    	   ,
					            fd_bhctdl_spec  
	     	              )values(
	     	              '$fqsqid'             ,  '$commtypeid'         ,  '$trademarkid'         ,'$level'        ,
	     	              '$kgname'             ,  '$long'               ,  '$width'               ,
	     	              '$unitid'             ,  '$fqshuliang'         ,  '$fqmemo'              ,
					            '$spec' 
	     	              )";
	     	   $dberp->query($query);   //插入细节表 数据
	    }     
	  }
	  
	  $query = "update tb_branchcut set fd_bhct_dunshu = '$alldunshu' where fd_bhct_id = '$fqsqid'";
	  $dberp->query($query);
		  
}
*/

//计算商品数量的吨数
function changekg2($relation3 , $unit , $quantity){
	switch($unit){
		case "令":
		     $kg = $quantity * $relation3;  //一令有多小千克
		     $dunquantity = $kg/1000;
		     break;
		case "千克":
		     $dunquantity = $quantity/1000;
		     break;
		case "吨":
		     $dunquantity = $quantity;
		     break;
	}
  return $dunquantity ;
}
?>