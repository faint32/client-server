<?


//自动生成单据单据编号
function listnumber_update(){
	$db  = new DB_erp2;	
	$listtype = 87;
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


function order_creat_kslist($listid){
	  $db     = new DB_test;
	  $db2    = new DB_test;
	  $dberp  = new DB_erp2;	
	  
	  //读取客诉申请
	  $query = "select * from web_kesu 
	            left join tb_produre on fd_produre_id = fd_ks_commid 
	            left join tb_productlevel on fd_productlevel_id = fd_produre_level 
	            left join tb_kgweight on fd_kgweight_id = fd_produre_kgweight 
	            left join tb_guige  on fd_guige_id = fd_produre_spec 
	            left join web_orderdetail on fd_ks_orderdtid = fd_orderdetail_id 
	            left join web_order on fd_order_id = fd_ks_orderid
	            where fd_ks_id = '$listid'";
	  $db->query($query);
	  if($db->nf()){
	    $db->next_record();
	    $commid      = $db->f(fd_ks_commid);	
	    $thquantity  = $db->f(fd_ks_tuinum);	
	    $printsize   = $db->f(fd_ks_yincc);
	    $fqsl        = $db->f(fd_ks_fenqienum);
	    $shsl        = $db->f(fd_ks_sunhao);
	    $printsl     = $db->f(fd_ks_yinnum); 
	    $memo        = $db->f(fd_ks_canzhi);
	    $ksmoney     = $db->f(fd_ks_money);
	    $ksyy        = $db->f(fd_ks_cause);
	    $zlms        = $db->f(fd_ks_ksmemo);
	    $memo        = $db->f(fd_ks_memo);
	    $czmoney     = $db->f(fd_ks_canzhi); 
	    $commname    = $db->f(fd_produre_name); 
	    $level       = $db->f(fd_productlevel_name); 
	    $kz          = $db->f(fd_kgweight_name); 
	    $gg          = $db->f(fd_guige_name);
	    $orderid     = $db->f(fd_ks_orderid);
	    $price       = $db->f(fd_orderdetail_price);
	    $quantity    = $db->f(fd_orderdetail_quantity);
	    $sdcrid      = $db->f(fd_order_sdcrid);
	    
	    if($cause == 1){//印刷不良
	      $ksyy = "1@@@";
	    }else if($cause == 2){//纸张表面异常
	      $ksyy = "6@@@";
	    }else if($cause == 3){//纸张克数不够
	      $ksyy = "4@@@";
	    }else if($cause == 4){//打烂胶布
	      $ksyy = "5@@@";
	    }
	    	   
	    $money       = $quantity*$price;
	    
	    $kstype      = 2;
	    
	    if($sdcrid == 1){
	      $mscid = 1;//广州市明盛物流有限公司
	    }else if($sdcrid == 2){
	      $mscid = 4;//上海粤琳珠物流有限公司
	    }else if($sdcrid == 3){
	    	$mscid = 13;//廊坊市明盛纸业有限公司
	    }else if($sdcrid == 4){
	    	$mscid = 9;
	    }else if($sdcrid == 5){
	    	$mscid = 11;
	    }
	    
	    $query = "select * from tb_mscompany where fd_msc_id = '$mscid'";
	    $dberp->query($query);
	    if($dberp->nf()){
	      $dberp->next_record();
	      $msclinkman  = $dberp->f(fd_msc_phone);
	    }
	    
	    $query = "select * from tb_salelist where fd_selt_weborderid = '$orderid'";
	    $dberp->query($query);
	    if($dberp->nf()){
	      $dberp->next_record();
	      $xsno     = $dberp->f(fd_selt_no);
	      $xsdate   = $dberp->f(fd_selt_date);
	      $cusid    = $dberp->f(fd_selt_cusid);
	    }
	            
	    $listno = listnumber_update();
	    
	    $query="insert into tb_kssq(
	       	     fd_kssq_no        ,  fd_kssq_date           , fd_kssq_organid    ,
	       	     fd_kssq_mscid     ,  fd_kssq_msclinkman     , fd_kssq_cusid      ,
	       	     fd_kssq_commid    ,  fd_kssq_commname       , fd_kssq_level      ,
	       	     fd_kssq_kz        ,  fd_kssq_gg             , fd_kssq_xsno       ,
	       	     fd_kssq_xsdate    ,  fd_kssq_price          , fd_kssq_quantity   ,
	       	     fd_kssq_money     ,  fd_kssq_thquantity     , fd_kssq_ksmoney    ,
	       	     fd_kssq_czmoney   ,  fd_kssq_ksyy           , fd_kssq_zlms       ,
	       	     fd_kssq_memo      ,  fd_kssq_kstype         , fd_kssq_printsize  ,    
	       	     fd_kssq_fqsl      ,  fd_kssq_printsl        , fd_kssq_shsl       ,
	       	     fd_kssq_state     ,  fd_kssq_webksid   
	       	     )values(
	       	     '$listno'         ,  now()                  , '1'                ,
	       	     '$mscid'          , '$msclinkman'           , '$cusid'           ,
	       	     '$commid'         , '$commname'             , '$level'           , 
	       	     '$kz'             , '$gg'                   , '$xsno'            ,
	       	     '$xsdate'         , '$price'                ,'$quantity'         ,
	       	     '$money'          , '$thquantity'           , '$ksmoney'         ,
	       	     '$czmoney'        , '$ksyy'                 , '$zlms'            ,
	       	     '$memo'           , '$kstype'               , '$printsize'       , 
	       	     '$fqsl'           , '$printsl'              , '$shsl'            ,
	       	     '2'               , '$listid'
	       	     )"; 
	    $dberp->query($query); 
	    
	  }
	  
}

?>