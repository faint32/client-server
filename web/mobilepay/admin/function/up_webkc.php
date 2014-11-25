<?
function up_webkc($sdcrid,$commid){
	   $db     = new DB_erp ;
	   $dberp  = new DB_test;      
	   $dberp2 = new DB_test;      
	   
	   $query = "select * from tb_websaleprice 
	             left join tb_produre on fd_produre_id = fd_nbsepe_produreid
	             where fd_nbsepe_sdcrid = '$sdcrid' and fd_nbsepe_produreid = '$commid'
	            ";
	   $dberp->query($query);
	   if($dberp->nf()){
	     $dberp->next_record();
	     $webkcquantity = $dberp->f(fd_nbsepe_kcquantity);
	     $trademarkid = $dberp->f(fd_produre_trademarkid);
	     $catalogid   = $dberp->f(fd_produre_catalog);
	     
	     //查询订单
	     //普通订单
	     $query = "select sum(fd_orderdetail_quantity) as orderquantity  from web_orderdetail
                 left join web_order on  fd_order_id = fd_orderdetail_orderid 
                 where fd_order_state != '6' and fd_order_state != '7'  and  fd_order_zf = 0
                 and fd_orderdetail_productid = '$commid' and fd_order_isfq = 0 and fd_order_sdcrid = '$sdcrid'
                 group by fd_orderdetail_productid
                ";
	     $dberp->query($query);
	     if($dberp->nf()){
	       $dberp->next_record();
	       $orderquantity  = $dberp->f(orderquantity);
	     }
	 
	     //分切订单
	     $query = "select fd_orderdetail_quantity,fd_orderdetail_fqlong,fd_orderdetail_fqwidth,fd_produre_relation1,fd_kgweight_name  
	               from web_orderdetail
                 left join web_order on  fd_order_id = fd_orderdetail_orderid 
                 left join tb_produre on fd_produre_id = fd_orderdetail_productid       
                 left join tb_kgweight  on fd_kgweight_id = fd_produre_kgweight        
                 where fd_order_state != '6' and fd_order_state != '7'  and  fd_order_zf = 0
                 and fd_orderdetail_productid = '$commid' and fd_order_isfq = 1 and fd_order_sdcrid = '$sdcrid'
                 group by fd_orderdetail_productid
                ";
	     $dberp->query($query);
	     if($dberp->nf()){
	       while($dberp->next_record()){
	            $quantity       = $dberp->f(fd_orderdetail_quantity);
	            $fqlong         = $dberp->f(fd_orderdetail_fqlong);
	            $fqwidth        = $dberp->f(fd_orderdetail_fqwidth);
	            $relation1      = $dberp->f(fd_produre_relation1);
	            $kgweight       = $dberp->f(fd_kgweight_name);
	            
	            $orderquantity  += changekg_fq($kgweight , $fqlong , $fqwidth  , '令' , $quantity,$relation1);
	       }
	     }
	     
	     
	     $query = "select sum(fd_skqy_quantity) as kcquantity from tb_stockquantity 
	               where fd_skqy_sdcrid = '$sdcrid' and fd_skqy_commid = '$commid'
	               group by fd_skqy_commid
	              ";
	     $db->query($query);     
	     if($db->nf()){
	       $db->next_record();
	       $kcquantity  = $db->f(kcquantity);
	     }
	     
	     $kcquantity = ($kcquantity-$orderquantity)+0;	     
	     if($kcquantity > 0){
	     	 $query = "update tb_websaleprice set 
	     	           fd_nbsepe_kcquantity = '$kcquantity',fd_nbsepe_iskc = '1' ,fd_nbsepe_isshow='1'
	     	           where fd_nbsepe_sdcrid = '$sdcrid' and fd_nbsepe_produreid = '$commid'
	     	          ";
	     	 $dberp->query($query);
	     }else{
	       if($webkcquantity != 0){	        	        
	        $query = "update tb_websaleprice set 
	     	            fd_nbsepe_kcquantity = '$kcquantity',fd_nbsepe_iskc = '0' ,fd_nbsepe_isshow='0'
	     	            where fd_nbsepe_sdcrid = '$sdcrid' and fd_nbsepe_produreid = '$commid'
	     	           ";
	     	  $dberp->query($query);
	     	  
	     	  //更新组合表
	     	  $query = "delete from tb_zhmcpricelist WHERE fd_zhmpl_catalogid = '$catalogid' AND fd_zhmpl_trademarkid = '$trademarkid'";
	     	  $dberp->query($query);
	     	  
	     	  $query  = "select fd_trademark_id,fd_produre_catalog,fd_kgweight_name,
                     fd_kgweight_id,fd_nbsepe_sdcrid
                     from tb_kgweight
                     left join tb_produre on fd_produre_kgweight = fd_kgweight_id
                     left join tb_trademark on fd_trademark_id = fd_produre_trademarkid
                     left join tb_websaleprice on fd_produre_id = fd_nbsepe_produreid
                     where fd_nbsepe_iskc = 1 and  fd_nbsepe_isshow = 1 
                     and fd_produre_catalog = '$catalogid' AND fd_produre_trademarkid = '$trademarkid'
                     group by fd_nbsepe_sdcrid,fd_kgweight_id,fd_trademark_id,fd_produre_catalog
                    ";
           $dberp->query($query);
           if($dberp->nf()){
           	while($dberp->next_record()){
                  $brandid = $dberp->f(fd_trademark_id);
                  $procaid = $dberp->f(fd_produre_catalog);
                  $sdcrid  = $dberp->f(fd_nbsepe_sdcrid);
                  
                  if($arr_kw[$sdcrid][$brandid][$procaid]==""){
           	       $arr_kw[$sdcrid][$brandid][$procaid]  = $dberp->f(fd_kgweight_name);
           	       $arr_kwid[$sdcrid][$brandid][$procaid]= $dberp->f(fd_kgweight_id);
                  }else{
           	       $arr_kw[$sdcrid][$brandid][$procaid] .= "/".$dberp->f(fd_kgweight_name);
           	       $arr_kwid[$sdcrid][$brandid][$procaid] .= "/".$dberp->f(fd_kgweight_id);
                  }
           	}
           }
           
           $query  = "select fd_trademark_id,fd_produre_catalog,fd_guige_name,fd_guige_id,fd_nbsepe_sdcrid 
                      from tb_guige 
                      left join tb_produre on fd_produre_spec = fd_guige_id
                      left join tb_trademark on fd_trademark_id = fd_produre_trademarkid
                      left join tb_websaleprice on fd_produre_id = fd_nbsepe_produreid
                      where  fd_nbsepe_iskc = 1  and fd_nbsepe_isshow = 1  
                      and fd_produre_catalog = '$catalogid' AND fd_produre_trademarkid = '$trademarkid'  
                      group by fd_nbsepe_sdcrid,fd_guige_id,fd_trademark_id,fd_produre_catalog
                     ";
           $dberp->query($query);
           if($dberp->nf()){
           	while($dberp->next_record()){
                  $brandid = $dberp->f(fd_trademark_id);
                  $procaid = $dberp->f(fd_produre_catalog);
                  $sdcrid  = $dberp->f(fd_nbsepe_sdcrid);
                  
                  if($arr_gg[$sdcrid][$brandid][$procaid]==""){
           	       $arr_gg[$sdcrid][$brandid][$procaid]= $dberp->f(fd_guige_name);
           	       $arr_ggid[$sdcrid][$brandid][$procaid]= $dberp->f(fd_guige_id);
                  }else{
           	       $arr_gg[$sdcrid][$brandid][$procaid].= "/".$dberp->f(fd_guige_name);
           	       $arr_ggid[$sdcrid][$brandid][$procaid].= "/".$dberp->f(fd_guige_id);
                  }
           	}
           }
           
           $query = "select fd_nbsepe_sdcrid as sdcrid ,
                            fd_trademark_id,
                            fd_trademark_name,
                            fd_proca_id,
                            fd_proca_catname,
                            fd_nbsepe_bjunit,
                            fd_productlevel_name,
                            fd_productlevel_id,
                            min(fd_nbsepe_dunprice) as minprice,max(fd_nbsepe_dunprice) as maxprice,
                            min(fd_nbsepe_lingprice) as minprice2,max(fd_nbsepe_lingprice) as maxprice2
                            from tb_websaleprice 
                     left join tb_produre on fd_produre_id = fd_nbsepe_produreid and fd_nbsepe_iskc = 1
                     left join tb_procatalog on fd_produre_catalog = fd_proca_id
                     left join tb_trademark on fd_trademark_id = fd_produre_trademarkid
                     left join tb_productlevel  on fd_productlevel_id = fd_produre_level                     
                     where fd_nbsepe_lingprice <> 0  and fd_nbsepe_iskc =1 and fd_nbsepe_isshow = 1 and fd_produre_level = 1
                     and fd_produre_catalog = '$catalogid' AND fd_produre_trademarkid = '$trademarkid'
                     group by fd_nbsepe_sdcrid,fd_proca_id,fd_trademark_id
                     order by fd_proca_catname";
           $dberp->query($query);
           if($dberp->nf()){
              while($dberp->next_record()){
                   $sdcrid  = $dberp->f(sdcrid);	
           
                   $trademarkid   = $dberp->f(fd_trademark_id);
                   $catalogid     = $dberp->f(fd_proca_id);
            
                   $trademark = $dberp->f(fd_trademark_name);
                   $catalog   = $dberp->f(fd_proca_catname);
                   $level     = $dberp->f(fd_productlevel_name);
                   $levelid   = $dberp->f(fd_productlevel_id);
                   $bjunit    = $dberp->f(fd_nbsepe_bjunit);
                   
                   if($bjunit == "吨"){
                   	$minprice = round($dberp->f(minprice)*1.06,0);
                     $maxprice = round($dberp->f(maxprice)*1.06,0);
                   }else{
                     $minprice = round($dberp->f(minprice2)*1.06,3);
                     $maxprice = round($dberp->f(maxprice2)*1.06,3);
                   }
                   
                   $kgweight   = $arr_kw[$sdcrid][$trademarkid][$catalogid];
                   $kgweightid = $arr_kwid[$sdcrid][$trademarkid][$catalogid];
                   
                   $guige   = $arr_gg[$sdcrid][$trademarkid][$catalogid];
                   $guigeid = $arr_ggid[$sdcrid][$trademarkid][$catalogid];
                   
                   if($catalogid > 0){
                     $query ="insert into tb_zhmcpricelist(
           	     	  		    fd_zhmpl_catalogid   , fd_zhmpl_trademarkid   , fd_zhmpl_trademark    ,
           	     	  		    fd_zhmpl_catalog     , fd_zhmpl_level         ,
           	     	  		    fd_zhmpl_levelid     , fd_zhmpl_minprice      , fd_zhmpl_maxprice     ,
           	     	  		    fd_zhmpl_bjunit      , fd_zhmpl_kgweightid    , fd_zhmpl_kgweight     ,
           				  		    fd_zhmpl_guigeid     , fd_zhmpl_guige         , fd_zhmpl_sdcrid 
           	     	  		    )values(     
           	     	  	      '$catalogid'         , '$trademarkid'         , '$trademark'          ,
           	     	  		    '$catalog'           , '$level'               , 
           	     	  		    '$levelid'           , '$minprice'            , '$maxprice'           ,
           	     	  		    '$bjunit'            , '$kgweightid'          , '$kgweight'           ,
           				  		    '$guigeid'           , '$guige'               , '$sdcrid' 
           	     	  	      )";
           	     	  $dberp2->query($query);
           	      }
                   
              }	
           }
          }
          
          
	     }
	     
	          
	   }
}

//计算吨数
function changekg_fq($kgweight , $long , $width  , $unit , $quantity,$relation1){
	switch($unit){
		case "令":
		     //$kg   = $quantity * $relation3;  //一令有多小千克
		     //$dunquantity = $kg/1000;
		     $kg = $long*$width/2000000;
		     //$kg = round($kg,3);
		     $dunquantity = (($quantity*$kg*$kgweight/1000)/500)*$relation1;
		     break;
		case "千克":
		     $dunquantity = $quantity/1000;
		     break;
		case "吨":
		     $dunquantity = ($quantity/500)*$relation1;
		     break;
	}
  return $dunquantity ;
}

?>