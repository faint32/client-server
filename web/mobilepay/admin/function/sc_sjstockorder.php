<?
 

function sc_sjstockorder($orderid){
	  $dbshop = new DB_shop;
	  $db = new DB_test;
	  $dberp = new DB_erp;
    
    $query = "select * from web_order where fd_order_id = '$orderid'";
    $dbshop->query($query);
    if($dbshop->nf()){
	    $dbshop->next_record();
	    $memeberid       = $dbshop->f(fd_order_memeberid);	
	    $ishaveinvoice   = $dbshop->f(fd_order_isfp); 
	    $wlyf            = $dbshop->f(fd_order_wlfy);          
      $wlzftype        = $dbshop->f(fd_order_wlzftype); 
      $shopid          = $dbshop->f(fd_order_shopid);
      $sjallmoney      = $dbshop->f(fd_order_sjallmoney);
      $allmoney        = $dbshop->f(fd_order_allmoney);
      $mscompanyid     = $dbshop->f(fd_order_mscid);  
      
      $srmoney = $allmoney-$sjallmoney;
	  }
	  
	  if($ishaveinvoice == 0){
	  	$ishaveinvoice = 1;
	  }else{
	    $ishaveinvoice = 0;
	  }
	  
	  if(empty($mscompanyid)){
	  	$mscompanyid = 2;
	  }
	  
	  //查找对应供应商
    $query = "select * from tb_mscompany where fd_msc_id = '$mscompanyid'";
    $dberp->query($query);
    if($dberp->nf()){
    	$dberp->next_record();
    	$msc_sdcrid = $dberp->f(fd_msc_sdcrid);
    }
	  
	  
	  $query = "select * from tb_organmem where fd_organmem_id  = '$memeberid' and fd_organmem_isms = 1";	 	  
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$cusid  = $db->f(fd_organmem_cusid);
    	
    	$query = "select * from tb_customer  where fd_cus_id = '$cusid'";
      $dberp->query($query);
    	if($dberp->nf()){
    	  $dberp->next_record();
    	  $sdcrid  = $dberp->f(fd_cus_wgsdcrid); 
    	  $owlid   = $dberp->f(fd_cus_id); 
    	  $owlname = $dberp->f(fd_cus_allname); 
    	  $owlno   = $dberp->f(fd_cus_no); 
    	  $wgmscid = $dberp->f(fd_cus_wgmscid);       
    	  
    	  $otype   = 1;   	  
    	}
        	
    	
    	//查找对应供应商
      $query = "select * from tb_mscompany where fd_msc_id = '$mscompanyid'";
      $dberp->query($query);
      if($dberp->nf()){
      	$dberp->next_record();
      	$msc_sdcrid = $dberp->f(fd_msc_sdcrid);
      }
    	
    	//查找对应供应商
      $query = "select * from tb_supplier  where fd_supp_mscid = '$mscompanyid'";
      $dberp->query($query);
      if($dberp->nf()){
      	$dberp->next_record();
      	$suppid = $dberp->f(fd_supp_id);
      	$suppno = $dberp->f(fd_supp_no);
      	$suppname = $dberp->f(fd_supp_allname);
      }
    	
    	//插入采购订单主表
      $stockorderno = listnumber_update(65);   
      $memo_z = "网购采购(商家)";  
      $syear  = date( "Y", mktime());
      $smonth = date("n", mktime());
      
      $query = "insert into tb_stockorder(
	              fd_skor_listno      ,   fd_skor_suppid     , fd_skor_suppno        ,
	              fd_skor_suppname    ,   fd_skor_organid    , fd_skor_lddate        , 
	              fd_skor_memo        ,   fd_skor_cgmscid    , fd_skor_sdcrid        ,
	              fd_skor_fkqx        ,   fd_skor_year       , fd_skor_month         ,
	              fd_skor_yjfhdate    ,   fd_skor_yjshdate   , fd_skor_stocktype     ,
	              fd_skor_paymodel    ,   fd_skor_gdr        , fd_skor_ishaveinvoice ,
	              fd_skor_ldr         ,   fd_skor_suplinkid  , fd_skor_suplinkname   ,
	              fd_skor_cgfanli     ,   fd_skor_wftpzx     , fd_skor_transporttype ,
	              fd_skor_state      
	              )values(
	              '$stockorderno'     ,   '$suppid'          , '$suppno'             ,
	              '$suppname'         ,   '1'                , now()                 ,
	              '$memo_z'           ,   '$wgmscid'         , '$sdcrid'             ,
	              '0'                 ,   '$syear'           , '$smonth'             ,
	              now()               ,   now()              , '1'                   ,
	              '0'                 ,   ''                 , '$ishaveinvoice'      ,
	              '$loginstaname'     ,   ''                 , ''                    ,
	              ''                  ,   ''                 , ''                    ,
	              '6'
	              )";
	    $dberp->query($query);   //插入单据资料
	    $stockorderid = $dberp->insert_id(); 
	    
	    //插入明细表
	    $query = "select * from web_orderdetail   
                where fd_orderdetail_orderid  = '$orderid'";
      $dbshop->query($query);
      if($dbshop->nf()){
      	while($dbshop->next_record()){
      	     $commid     = $dbshop->f(fd_orderdetail_productid);
      	     $commbar    = $dbshop->f(fd_orderdetail_barcode);
      	     $commname   = $dbshop->f(fd_orderdetail_productname);
      	     $unitname   = $dbshop->f(fd_orderdetail_unit);   
      	     $price      = $dbshop->f(fd_orderdetail_price);  
      	     $quantity   = $dbshop->f(fd_orderdetail_quantity);   	        
  	  	          	     
      	     unset($comm_str);
             $comm_str     = explode("@@@",readproduct($commid));      
             $tmpralation  = $comm_str[5];    //对应关系3
      	     
      	     $dunquantity = changekg3($tmpralation , $unitname , $quantity);  //吨数量
      	     
      	     if($unitname == "令"){
      	       $unit = 20;
      	     }else{
      	       $unit = 21;
      	     }
      	     
      	     $alldunquantity += $dunquantity;
      	     
      	     //$allmoney += $price*$quantity;
      	     
 	      
      	     $query  = "insert into tb_stockorderdetail(
	                    fd_skdrdetail_skorid    ,  fd_skdrdetail_commid       ,  fd_skdrdetail_isorgan       ,
	                    fd_skdrdetail_commname  ,  fd_skdrdetail_commbar      ,  fd_skdrdetail_unit          ,
	                    fd_skdrdetail_quantity  ,  fd_skdrdetail_price        ,  fd_skdrdetail_memo          ,
	                    fd_skdrdetail_organid   ,  fd_skdrdetail_dyj          
	                    )values(
	                    '$stockorderid'         ,  '$commid'                  ,  '0'                         ,
	                    '$commname'             ,  '$commbar'                 ,  '$unit'                     ,
	                    '$quantity'             ,  '$price'                   ,  '$memo'                     ,
	                    '$sdcrid'               ,  '$dyj'                    
	                    )";
	           $dberp->query($query); 
        }
      }
      
      if($wlzftype == 2){
      	$dyj = $wlyf/$alldunquantity;
      	//$allmoney = $allmoney+$wlyf;
      	$query = "update tb_stockorderdetail set fd_skdrdetail_dyj = '$dyj' where fd_skdrdetail_skorid = '$stockorderid'";
        $dberp->query($query);
      }
      
      $query = "update tb_stockorder set fd_skor_allmoney = '$allmoney',fd_skor_alldunquantity = '$alldunquantity'
                where fd_skor_id = '$stockorderid'
               ";  
      $dberp->query($query);   
    	
    }else{
      $query = "select * from tb_organmem where fd_organmem_id  = '$memeberid'";	 	  
      $db->query($query);
      if($db->nf()){
    	  $db->next_record();
    	  $cusid  = $db->f(fd_organmem_cusid);
    	  
    	  $query = "select * from tb_mscompany where fd_msc_id = '$mscompanyid'";
        $dberp->query($query);
        if($dberp->nf()){
        	$dberp->next_record();
        	$sdcrid = $dberp->f(fd_msc_sdcrid);  
        }	
    	  
    	  $query = "select * from tb_customer where fd_cus_id = '$cusid'";
        $dberp->query($query);
    	  if($dberp->nf()){
    	    $dberp->next_record();
    	    //$sdcrid  = 1; 
    	    $owlid   = $dberp->f(fd_cus_id); 
    	    $owlname = $dberp->f(fd_cus_allname); 
    	    $owlno   = $dberp->f(fd_cus_no); 
    	    $otype   = 1;   	  
    	  }
      }
    }
    
    //生成应收应付转移
    //搜索对应供应商
    $query = "select * from tb_supplier where fd_supp_shopid = '$shopid'";
    $dberp->query($query);
    if($dberp->nf()){
      $dberp->next_record();
      $iwlid   = $dberp->f(fd_supp_id); 
      $iwlno   = $dberp->f(fd_supp_no); 
      $iwlname = $dberp->f(fd_supp_allname); 
      $itype   = 2;
      
      $ysyfzyno = listnumber_update(101);
      
      $memo_z = "网站客户应收款转移到商家应收款";
      
      $money = -$sjallmoney;
      
      $query = "insert into tb_ysyfwlzy(
                fd_ysyfwlzy_listno    ,  fd_ysyfwlzy_owlid   , fd_ysyfwlzy_otype   ,
                fd_ysyfwlzy_owlname   ,  fd_ysyfwlzy_owlno   , fd_ysyfwlzy_iwlid   , 
                fd_ysyfwlzy_iwlno     ,  fd_ysyfwlzy_iwlname , fd_ysyfwlzy_itype   , 
                fd_ysyfwlzy_listdate  ,  fd_ysyfwlzy_memo    , fd_ysyfwlzy_organid ,      
                fd_ysyfwlzy_sdcrid    ,  fd_ysyfwlzy_mscid   , fd_ysyfwlzy_sqman   ,
                fd_ysyfwlzy_money
                )values(
                '$ysyfzyno'           , '$owlid'             , '$otype'            ,
                '$owlname'            , '$owlno'             , '$iwlid'            ,
                '$iwlno'              , '$iwlname'           , '$itype'            ,
                now()                 , '$memo_z'            , '1'                 ,
                '$msc_sdcrid'         , '$mscompanyid'       , '$loginstaname'     ,
                '$money'
                )";
      $dberp->query($query);
      $listid = $dberp->insert_id(); 
      
      $query = "update tb_ysyfwlzy set fd_ysyfwlzy_state = '3' ,fd_ysyfwlzy_gzdate = now() where fd_ysyfwlzy_id = '$listid'";
      $dberp->query($query);    //修改
      
      $query = "select * from tb_ysyfwlzy where fd_ysyfwlzy_id = '$listid' ";
      $dberp->query($query);
      if($dberp->nf()){
      	 $dberp->next_record();
      	 $listno       = $dberp->f(fd_ysyfwlzy_listno);
      	 $owlid        = $dberp->f(fd_ysyfwlzy_owlid);
      	 $owlno        = $dberp->f(fd_ysyfwlzy_owlno);
      	 $owlname      = $dberp->f(fd_ysyfwlzy_owlname);
      	 $otype        = $dberp->f(fd_ysyfwlzy_otype);
      	 $iwlid        = $dberp->f(fd_ysyfwlzy_iwlid);
      	 $iwlno        = $dberp->f(fd_ysyfwlzy_iwlno);
      	 $iwlname      = $dberp->f(fd_ysyfwlzy_iwlname);
      	 $itype        = $dberp->f(fd_ysyfwlzy_itype);
      	 $listdate     = $dberp->f(fd_ysyfwlzy_listdate);
      	 $money        = $dberp->f(fd_ysyfwlzy_money);
      	 $sdcrid       = $dberp->f(fd_ysyfwlzy_sdcrid);        //配送中心id号
      	 $mscompanyid  = $dberp->f(fd_ysyfwlzy_mscid);
      	 
      	 $addmoney = -$money;
      	 $lessenmoney = 0;
     	   //生成客户往来对帐单 
	       $ctatmemo     = "应收应付款往来转移应付减少".-$money."元";
	       $cactlisttype = "101";
	       zbcurrentaccount($otype , $owlid , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$listdate , $sdcrid , $mscompanyid );
         
     	   changemoney($otype , $owlid ,$money , 1 , 1, $sdcrid , $mscompanyid , 1);  //修改应付应付款函数，0代表正，1代表负数
         //--------------------转到往来单位--------------------------------
         $addmoney    = 0;
      	 $lessenmoney = -$money;
      	 
     	   //生成客户往来对帐单 
	       $ctatmemo     = "应收应付款往来转移应付增加".-$money."元";
	       $cactlisttype = "101";
	       zbcurrentaccount($itype , $iwlid , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$listdate , $sdcrid , $mscompanyid );
     	   changemoney($itype , $iwlid ,$money , 0 , 1, $sdcrid , $mscompanyid , 1);  //修改应付应付款函数，0代表正，1代表负数
      }
      
    }
    
    //生成收入
    $memo_z = "网站会员向商家采购抵扣运费，提成，返利收入";
    $fkdno = listnumber_update(9);
    $query = "insert into tb_paymoneylist(
              fd_pymylt_no          ,  fd_pymylt_clientid    , fd_pymylt_type         ,
              fd_pymylt_clientname  ,  fd_pymylt_staid       , fd_pymylt_money        ,  
              fd_pymylt_date        ,  fd_pymylt_memo        , fd_pymylt_organid      , 
              fd_pymylt_clientno    ,  fd_pymylt_sdcrid      , fd_pymylt_mscompanyid  ,
              fd_pymylt_state       ,  fd_pymylt_paymscid
              )values(
              '$fkdno'              ,  '$owlid'              , '1'                    ,
              '$owlname'            ,  '$loginstaid'         , '$srmoney'             ,
              now()                 ,  '$memo_z'             , '1'                    ,  
              '$cusno'              ,  '1'                   , '2'                    ,
              '1'                   ,  '$mscompanyid'
              )";
    $dberp->query($query);    //插入付款单
    $fkdid = $dberp->insert_id(); 
        
    //生成客户往来对帐单
    $addmoney = $srmoney;
    $lessenmoney = 0; 
	  $ctatmemo     = "抵扣运费，提成，返利收入,应付减少".$srmoney."元";
	  $cactlisttype = "9";
	  zbcurrentaccount(1 , $owlid , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $fkdid , $fkdno ,$listdate , 1 , 2 );
	  changemoney($otype , $owlid ,$srmoney , 0 , 1, 1 , 2 , 1);  //修改应付应付款函数，0代表正，1代表负数
	  
	  //其他收入
	  $query = "select * from tb_othercompany where fd_orcy_izwz = 1";
	  if($dberp->nf()){
      $dberp->next_record();
      $companyid  = $dberp->f(fd_orcy_id);
      $company    = $dberp->f(fd_orcy_allname);     
	  }
	  $srdno = listnumber_update(13);
	  
	  $memo_z = "网站会员向商家采购运费，提成，返利收入"; 
	  $query = "insert into tb_incomelist(
	            fd_incomelist_no          ,   fd_incomelist_date         ,   fd_incomelist_company      ,
	            fd_incomelist_staid       ,   fd_incomelist_memo         ,   fd_incomelist_organid      , 
	            fd_incomelist_mscompanyid ,   fd_incomelist_sdcrid       ,   fd_incomelist_companyid    ,
	            fd_incomelist_state       ,   fd_incomelist_allmoney     ,   fd_incomelist_datetime
	            )values(
	            '$srdno'                  ,   now()                      ,  '$company'                  ,
	            '$loginstaid'             ,   '$memo_z'                  ,   '1'                        , 
	            '2'                       ,   '1'                        ,   '$companyid'               ,
	            '2'                       ,   '$srmoney'                 ,   now() 
	            )";
	   $dberp->query($query);   //插入单据资料
	   $listid = $dberp->insert_id();    //取出刚插入的记录的主关键值的id
	   
	   $query  = "insert into tb_incomelistdetail(
	       	       fd_ieltdl_incomelistid  ,  fd_ieltdl_ptteid  ,  fd_ieltdl_cost  , 
	       	       fd_ieltdl_memo
	       	       )values(
	       	       '$listid'               ,  '30'              ,  '$srmoney'         ,
	       	       '$memo' 
	       	       )";
	   $dberp->query($query);
     
     $listtype = "13";    //单据类型
     $oppositecode = "30301-a001";
	   updatesubject($listtype , $listid , $oppositecode , $cost,0,1);  //0代表正，1代表负数  
}


function updatesubject($listtype , $listid , $flstno,$money,$type,$organid){
	$db  = new DB_erp;
	
	if($money!=0){
	   if($type==0){
	   	 $endmoney = $money;
	   }else{
	     $endmoney = -$money;
     }
     
     $query = "select * from tb_fiscalsubject where fd_flst_no = '$flstno' and fd_flst_organid = '$organid'";
     $db->query($query);
     if($db->nf()){
     	$db->next_record();
     	$flstname = $db->f(fd_flst_name);
     	$oldnowmoney = $db->f(fd_flst_nowmoney);
     	$oldmonthmoney = $db->f(fd_flst_monthmoney);
     	
     	$newnowmoney = $oldnowmoney + $endmoney;
     	$newmonthmoney = $oldmonthmoney + $endmoney;
     	
     	$query = "update tb_fiscalsubject set 
                fd_flst_nowmoney   = '$newnowmoney'    ,
                fd_flst_monthmoney = '$newmonthmoney'  
                where fd_flst_no = '$flstno' and fd_flst_organid = '$organid'";
      $db->query($query);  //修改科目金额
     }  //查出科目名称
     
     $query = "insert into tb_subjectdetail(
               fd_stdl_listtype , fd_stdl_listid , fd_stdl_flstno ,
               fd_stdl_flstname , fd_stdl_money  , fd_stdl_organid
               )values(
               '$listtype'      , '$listid'      , '$flstno'      ,
               '$flstname'      , '$endmoney'    , '$organid'
               )";
	   $db->query($query);  //插入科目详情
  }
}

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

//计算商品数量的吨数
function changekg3($relation3 , $unit , $quantity){
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