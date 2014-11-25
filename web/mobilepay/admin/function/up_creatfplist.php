<?


//自动生成单据单据编号
function listnumber_update(){
	$db  = new DB_erp2;	
	$listtype = 31;
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


function order_creat_fplist($listid){
	  $db     = new DB_test;
	  $db2    = new DB_test;
	  $dberp  = new DB_erp2;	
	  
	  //读取发票申请
	  $query = "select * from web_invoicesq 
	            left join tb_organmem on fd_organmem_id = fd_ivcsq_memberid
	            where fd_ivcsq_id = '$listid'";
	  $db->query($query);
	  if($db->nf()){
	    $db->next_record();
	    $iecrid    = $db->f(fd_ivcsq_fpcusid);	
	    $iecrno    = $db->f(fd_ivcsq_fpcusno);	
	    $iecrname  = $db->f(fd_ivcsq_fpcusname);
	    $orderid   = $db->f(fd_ivcsq_orderid);
	    $fptype    = $db->f(fd_ivcsq_fptype);
	    $money     = $db->f(fd_ivcsq_money); 
	    $memo      = $db->f(fd_ivcsq_memo); 
	    $fpusename = $db->f(fd_organmem_comnpany);
	    $fpuseid   = $db->f(fd_ivcsq_memberid);
	    
	    if($invoicetype == "普通发票"){
	    	$invoicetype = 1;
	    }else{
	      $invoicetype = 0;
	    }
	    
	    $s_orderid = implode(",",explode("@@@",$orderid)); 	
	    $query="select * from web_order 
              where fd_order_id in ($s_orderid) ";	           
      $db->query($query);
      if($db->nf()){
	      $db->next_record();    
	      $sdcrid = $db->f(fd_order_sdcrid);           
	      
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
	    
	    $issue = date( "Y", mktime()).date( "m", mktime());	    
	    $listno = listnumber_update();
	    
	    $query = "insert into tb_invoiceapply(
	              fd_ieay_listno      ,   fd_ieay_iecrid     , fd_ieay_iecrno        ,
	              fd_ieay_iecrname    ,   fd_ieay_sporganid  , fd_ieay_date          , 
	              fd_ieay_mscompanyid ,   fd_ieay_issue      , fd_ieay_staname       ,
	              fd_ieay_invoicetype ,   fd_ieay_sdcrid     , fd_ieay_cporganid     ,   
	              fd_ieay_xjmoney     ,   fd_ieay_yhmoney    , fd_ieay_spsdcrid      ,
	              fd_ieay_memo        ,   fd_ieay_dziecrid   , fd_ieay_sqinvoicetype ,
	              fd_ieay_sqmscid     ,   fd_ieay_sqyhmoney  , fd_ieay_sqxjmoney     ,
	              fd_ieay_websqid     ,   fd_ieay_fpuseid    , fd_ieay_fpusename
	              )values(
	              '$listno'           ,   '$iecrid'          , '$iecrno'             ,
	              '$iecrname'         ,   '1'                , now()                 ,
	              '$mscompanyid'      ,   '$issue'           , '网站'                ,
	              '$invoicetype'      ,   '$sdcrid'          , '1'                   ,   
	              '0'                 ,   '$money'            , '$sdcrid'            ,
	              '$memo'             ,   '$iecrid'          , '$invoicetype'        ,
	              '$mscompanyid'      ,   '0'                , '$money'              ,
	              '$listid'           ,   '$fpuseid'         , '$fpusename'
	              )";
	    $dberp->query($query); 
	    
	  }
	  
}

?>