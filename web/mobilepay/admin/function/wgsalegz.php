<?   

function wgsalegz($listid){
$db    = new DB_erp;
$db1   = new DB_erp;
$dberp = new DB_test;

$organtype = 1;
$organno = "a001";

$query = "select * from tb_salelist_2010 
          where fd_selt_id = '$listid' and fd_selt_state = '92'
         ";
$db->query($query);
if($db->nf()){         
  $query = "select fd_selt_historymemo,fd_selt_ldr,fd_selt_weborderid from tb_salelist_2010 where fd_selt_id = '$listid'";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
  	$historymemo = $db->f(fd_selt_historymemo);
  	$ldrname     = $db->f(fd_selt_ldr);
  	$weborderid  = $db->f(fd_selt_weborderid);
  }
  	    	 
  $liststate = 1;
      
  $query = "update tb_salelist_2010 set fd_selt_memo  = ''     , fd_selt_state   = '$liststate' ,  
                   fd_selt_cwspdate  = now() 
            where fd_selt_id = '$listid' ";
  $db->query($query);   //修改单据资料
  	  
  	  
  //更新订单状态
  $query = "update web_order set fd_order_state = '7',fd_order_gzdate = now()  where fd_order_id = '$weborderid'";
  $dberp->query($query);
  	  
  $query = "select * from web_order where fd_order_id = '$weborderid'";
  $dberp->query($query);
  if($dberp->nf()){
    $dberp->next_record();
    $memeberid = $dberp->f(fd_order_memeberid);
    $gdr = $dberp->f(fd_order_ddclr);
    
    //激活会员卡
    $query = "update tb_organmem set fd_organmem_active = '1'  where fd_organmem_id = '$memeberid'";
    $dberp->query($query);
    
    $query = "update web_salercard  set fd_salercard_opendate = now()  where fd_salercard_memberid  = '$memeberid' and fd_salercard_opendate = '0000-00-00'";
    $dberp->query($query);
    
  }
  	  
  $query = "select * from tb_salelist_2010 where fd_selt_id = '$listid'";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
  	$listid_2010  = $db->f(fd_selt_id); 
    $listno       = $db->f(fd_selt_no);            //单据编号
    $cusid        = $db->f(fd_selt_cusid);         //客户id号
    $cusno        = $db->f(fd_selt_cusno);         //客户编号 
    $cusname      = $db->f(fd_selt_cusname);       //客户名称
    $sendcenterid = $db->f(fd_selt_sdcrid);        //配送中心id号
    $mscompanyid  = $db->f(fd_selt_mscompanyid);   //所属公司id号 
    $memo         = $db->f(fd_selt_memo);
    $iswebsale    = $db->f(fd_selt_iswebsale);
    $ishavetax    = $db->f(fd_selt_ishavetax);
    $ishavetax    = $db->f(fd_selt_ishavetax);
    $skfs         = $db->f(fd_selt_skfs);
    $jhcldate     = $db->f(fd_selt_jhcldate);
    $cwspdate     = $db->f(fd_selt_cwspdate);
    $ckchdate     = $db->f(fd_selt_ckchdate);
    $ckqrdate     = $db->f(fd_selt_ckqrdate);
    $zjlspdate    = $db->f(fd_selt_zjlspdate);
    $ldr          = $db->f(fd_selt_ldr);
    $historymemo  = $db->f(fd_selt_historymemo);
    $shplace      = $db->f(fd_selt_shplace);
    $consignee    = $db->f(fd_selt_consignee);
    $webpaymoney  = $db->f(fd_selt_webpaymoney);
    $weborderid   = $db->f(fd_selt_weborderid);
    $weborderno   = $db->f(fd_selt_weborderno);  
    $date         = $db->f(fd_selt_date);
    $trafficmodel = $db->f(fd_selt_trafficmodel);
    $cardid       = $db->f(fd_selt_cardid);
    
    $wlyf         = $db->f(fd_selt_wlyf);          //物流运费 
    $wlzftype     = $db->f(fd_selt_wlzftype);      //物流运费支付类型  
    $ysgsid     = $db->f(fd_selt_ysgsid);
          
  }
       
  $query = "select fd_selt_id from tb_salelist where fd_selt_salelistid = '$listid' and fd_selt_no = '$listno'";
  $db->query($query);
  if($db->nf()){
    $db->next_record();
    $listid = $db->f(fd_selt_id);
  }else{
    $query = "insert into tb_salelist (
              fd_selt_no           , fd_selt_cusid              , fd_selt_cusno       ,
              fd_selt_cusname      , fd_selt_accountid          , 
              fd_selt_sdcrid       , fd_selt_mscompanyid        , fd_selt_date        ,
              fd_selt_organid      , fd_selt_datetime           , fd_selt_memo        , 
              fd_selt_iswebsale    , fd_selt_ishavetax          , fd_selt_trafficmodel, 
              fd_selt_skfs         , fd_selt_jhcldate           , fd_selt_zjlspdate   , 
              fd_selt_cwspdate     , fd_selt_ckchdate           , fd_selt_ckqrdate    ,
              fd_selt_ldr          , fd_selt_historymemo        , fd_selt_state       ,
              fd_selt_consignee    , fd_selt_shplace            , fd_selt_webpaymoney ,
              fd_selt_weborderid   , fd_selt_weborderno         , fd_selt_salelistid  ,
              fd_selt_cardid       , fd_selt_wlyf               , fd_selt_wlzftype    ,
              fd_selt_ysgsid 
              )values( 
              '$listno'            , '$cusid'                   , '$cusno'            ,
              '$cusname'           , '$accountid'               ,
              '$sendcenterid'      , '$mscompanyid'             , '$date'             ,
              '1'                  , now()                      , '$memo'             , 
              '$iswebsale'         , '$ishavetax'               , '$trafficmodel'     , 
              '$skfs'              ,'$jhcldate'                 , '$zjlspdate'        , 
              '$cwspdate'          , '$ckchdate'                , '$ckqrdate'         ,
              '$ldr'               , '$historymemo'             , '1'                 ,    
              '$consignee'         , '$shplace'                 , '$webpaymoney'      ,                     
              '$weborderid'        , '$weborderno'              , '$listid'           ,
              '$cardid'            , '$wlyf'                    , '$wlzftype'         ,
              '$ysgsid'
              )"; 
    $db->query($query);                              
    $listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
    
    //------------------------------插入采购单--------------------------------
    $cg_listid = 0;
    $query = "select fd_cus_linkorganid from tb_customer where fd_cus_id = '$cusid' ";
    $db1->query($query);
    if($db1->nf()){
    	$db1->next_record();
    	$linkorganid = $db1->f(fd_cus_linkorganid);
    	
    	$msc_name = "";
    	$query = "select fd_msc_name from tb_mscompany where fd_msc_id = '$mscompanyid' ";
    	$db1->query($query);
    	if($db1->nf()){
    		$db1->next_record();
    		$msc_name = $db1->f(fd_msc_name);
    	}
    	
    	$suppid = "";
    	$suppno = "";
    	$suppname = "";
    	$query = "select fd_supp_id , fd_supp_no  from tb_supplier 
    	          where fd_supp_allname = '$msc_name' and fd_supp_organid = '$linkorganid' ";
    	$db1->query($query);
    	if($db1->nf()){
    		$db1->next_record();
    		$suppid   = $db1->f(fd_supp_id);
    	  $suppno   = $db1->f(fd_supp_no);
    	  $suppname = $msc_name;
    	}else{
    	  $suppno   = changepinyin($msc_name)."-".$linkorganid;
    	  $suppname = $msc_name;
    	  $query = "insert into tb_supplier(
    	            fd_supp_no , fd_supp_allname  , fd_supp_organid
    	            )values(
    	            '$suppno'  , '$suppname'      , '$linkorganid'
    	            )";
    	  $db1->query($query);
    	  $suppid = $db1->insert_id();    //取出刚插入的记录的主关键值的id
      }
    	
    	$cg_memo_z = "网购销售单自动生成到分行的进货单";
      $cg_listno = listnumber_update(1);  //保存单据
      $query = "insert into tb_stock(
                fd_stock_no          ,   fd_stock_suppid        , fd_stock_suppno           ,
                fd_stock_suppname    ,   fd_stock_organid       , fd_stock_date             , 
                fd_stock_memo        ,   fd_stock_staid         , fd_stock_sdcrid           ,
                fd_stock_dealwithman ,   fd_stock_dyj           , fd_stock_paymentdate      
                $fieldsql
                )values(
                '$cg_listno'        ,   '$suppid'          , '$suppno'             ,
                '$suppname'         ,   '$linkorganid'     , now()                 ,
                '$cg_memo_z'        ,   '0'                , '0'                   ,
                ''                  ,   '0'                , now()                 
                )";
      $db->query($query);   //插入单据资料
      $cg_listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
    }
  }
  	  
  //更新客户的往来单据时间
  $query = "update tb_customer set fd_cus_newdatetime = now(),fd_cus_newlistid = '$listid',
            fd_cus_newlisttype = '1'
            where fd_cus_id = '$cusid'";
  $db->query($query);
  
  //插入销售细节表的商品数据
  $query = "select * from tb_salelistdetail_2010 where fd_stdetail_seltid = '$listid_2010' ";
  $db->query($query);
  if($db->nf()){
  	while($db->next_record()){
  		   $v_stdetailid   = $db->f(fd_stdetail_id);
  		   $v_storageid    = $db->f(fd_stdetail_storageid);
  		   $v_commid       = $db->f(fd_stdetail_commid);
  		   $v_commbar      = $db->f(fd_stdetail_commbar);
  		   $v_commname     = $db->f(fd_stdetail_commname);
  		   $v_unit         = $db->f(fd_stdetail_unit);
  		   $v_quantity     = $db->f(fd_stdetail_quantity);
  		   $v_price        = $db->f(fd_stdetail_price);
  		   $v_memo         = $db->f(fd_stdetail_memo);
  		   
  		   $query = "select * from tb_salelistdetail where fd_stdetail_stdetailid = '$v_stdetailid' and fd_stdetail_seltid = '$listid' ";
  		   $db1->query($query);
  		   if($db1->nf()){
  		   }else{
  		     $query = "insert into tb_salelistdetail (
  		               fd_stdetail_seltid        , fd_stdetail_storageid , fd_stdetail_commid  , 
  		               fd_stdetail_commbar       , fd_stdetail_commname  , fd_stdetail_unit    ,
  		               fd_stdetail_quantity      , fd_stdetail_price     , fd_stdetail_memo          
  		               )values(
  		               '$listid'                 , '$v_storageid'        , '$v_commid'         ,
  		               '$v_commbar'              , '$v_commname'         , '$v_unit'           ,
  		               '$v_quantity'             , '$v_price'            , '$v_memo'             
  		               )";
  		     $db1->query($query);
  		     
  		     if($cg_listid>0){
  		       $query  = "insert into tb_stockdetail(
  	                     fd_skdetail_stockid  ,  fd_skdetail_commid  ,  fd_skdetail_storageid ,
  	                     fd_skdetail_commname ,  fd_skdetail_commbar ,  fd_skdetail_unit      ,
  	                     fd_skdetail_quantity ,  fd_skdetail_price   ,  fd_skdetail_memo    
  	                     )values(
  	                     '$cg_listid'         ,  '$v_commid'         ,  '0'                   ,
  	                     '$v_commname'        ,  '$v_commbar'        ,  '$v_unit'             ,
  	                     '$v_quantity'        ,  '$v_price'          ,  '$v_memo'          
  	                     )";
  	         $db1->query($query);   //插入细节表 数据
  	       }
  	     }
  	}
  }
  	 
  	  	    
  $allmoney = 0;
  $alldunquantity =0;
  $alldunmoney = 0;
  $allstoragecost=0;
  $query = "select * from tb_salelistdetail 
            left join tb_produre on fd_produre_id = fd_stdetail_commid
            where fd_stdetail_seltid = '$listid'";
  $db->query($query);
  if($db->nf()){
  	while($db->next_record()){
  		   $commid         = $db->f(fd_stdetail_commid);
  		   $quantity       = $db->f(fd_stdetail_quantity);
  		   $tmpsbdetailid  = $db->f(fd_stdetail_id);
  		   $price          = $db->f(fd_stdetail_price);     //单价
  		   $tmpunit        = $db->f(fd_stdetail_unit);
  		   $tmpcost        = $db->f(fd_stdetail_tmpcost);
  		   $tmpralation    = $db->f(fd_produre_relation3);  //对应关系3
  		   $storageid      = $db->f(fd_stdetail_storageid);     //仓库ID
  		   
  		   $dunquantity = changekg($tmpralation , $tmpunit , $quantity);  //吨数量
  		   	  		  	  		   	  		   	  		  		
  		   $alldunquantity +=$dunquantity;
  		   
  		   if(empty($sendcenterid)){  
  		     $sendcenterid=0; //配送中心id
  		   }
  		   
  	     
  		   //查找库存是否有数量
         $flagquantity=0;
         $query = "select * from tb_stockquantity where fd_skqy_organid = '1' 
                   and fd_skqy_commid = '$commid' and fd_skqy_sdcrid = '$sendcenterid'";
         $db1->query($query);
         if($db1->nf()){
         	while($db1->next_record()){
         		if($db1->f(fd_skqy_quantity)!=0){
         			$flagquantity = 1;
         		}
           }
         }
         
         //查找是否有库存成本价
  		   $query = "select * from tb_storagecost where fd_sect_organid = '1'
  		             and fd_sect_commid = '$commid' and fd_sect_sdcrid = '$sendcenterid'";
  		   $db1->query($query);
  		   if($db1->nf()){
  		   	$db1->next_record();
  		   	$storagecost = $db1->f(fd_sect_cost);
         
  		   	if($storagecost==0 and $flagquantity==0){   //如果库存单价为0时，就修改库存单价
         	  $query = "update tb_storagecost set fd_sect_cost = '$tmpcost'
                       where fd_sect_organid = '1'  and fd_sect_sdcrid = '$sendcenterid'
                       and fd_sect_commid = '$commid'";
             $db1->query($query);   
           }
  		   }else{  //如果没有库存成本记录
           $storagecost = 0;
           if($flagquantity==0){
             $query = "insert into tb_storagecost(
                       fd_sect_cost    ,  fd_sect_commid , fd_sect_organid ,
                       fd_sect_sdcrid  
                       )values(
                       '$tmpcost'      ,  '$commid'      , '1'  ,
                       '$sendcenterid'
                       )";
             $db1->query($query);
           }
         }
         
         if($storagecost==0 and $flagquantity==0){
         	 $storagecost = $tmpcost;
         }
         //修改仓库的数量和成本价
         updatestorage($commid,$quantity,$storagecost,$storageid,1,$sendcenterid,1);  //0代表正、1代表负
         
         $query = "update tb_salelistdetail set fd_stdetail_cost = '$storagecost'
                   where fd_stdetail_id = '$tmpsbdetailid'  ";
         $db1->query($query);   //修改商品的库存成本价
         
         $allstoragecost += $storagecost*$quantity;    //销售成本价
         
  		   //商品流水帐
  		   $cogememo     = "商品销售减少";
  		   $cogelisttype = "3";
  		   $cogetype = 1; //0为增加 ， 1为减少
  		   commglide($storageid , $commid , $quantity , $cogememo , $cogelisttype , $gdr , $listid , $listno , $cogetype , $date);     		
  		   
  		   $allmoney += $quantity*$price;  //销售总额
  		   
  		   /*
  		   //记录该商品卖给该客户的最近销售价
  		   $query = "select * from tb_savesalepirce where fd_ssp_cusid = '$cusid' and fd_ssp_commid = '$commid'";
  		   $db1->query($query);
  		   if($db1->nf()){
  		   	$query = "update tb_savesalepirce set fd_ssp_price = '$price' 
  		   	          where fd_ssp_cusid = '$cusid' and fd_ssp_commid = '$commid'";
  		   	$db1->query($query);
  		   }else{
  		     $query = "insert into tb_savesalepirce(
  		               fd_ssp_cusid  , fd_ssp_commid  , fd_ssp_price
  		               )values(
  		               '$cusid'   ,  '$commid'  , '$price'
  		               )";
  		     $db1->query($query);
  	     }
  	     */
  	}
  }	
  
  /*
  //查询即时的应收应付款
  if($organtype==1){
  	$ysyf_sqlwhere = " and fd_ysyfm_mscompanyid = '$mscompanyid'";
  }else{
    $ysyf_sqlwhere = "";
  }
  
  
  $query = "select * from tb_ysyfmoney where fd_ysyfm_organid = '1'
            and fd_ysyfm_type = '1' and fd_ysyfm_companyid = '$cusid'
            $ysyf_sqlwhere ";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
    $nowysyfmoney = $db->f(fd_ysyfm_money);
  }
  */

  
  //修改应收金额
  if($wlzftype == 2){
  	$allmoney = $allmoney+$wlyf;
  }
    
  $allysmoney = $allmoney;
    
  //生成总部往来对帐单
  $ctatmemo     = "应收".$cusname."客户".$allysmoney."元";
  $cactlisttype = "3";
  $addmoney = $allmoney;
  $lessenmoney = $paymoney;
  zbcurrentaccount(1 , $cusid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $gdr , $listid , $listno ,$date , $sendcenterid , $mscompanyid);
  
  
  if($allysmoney<>0){
    changemoney(1 , $cusid ,$allysmoney , 0 , 1 , $sendcenterid , $mscompanyid , $organtype);  //第四位0代表正，1代表负数
  }
  
  /*
  //读取客户应收款
  $query = "select * from tb_ysyfmoney 
             where fd_ysyfm_type =1 and fd_ysyfm_companyid = '$cusid'";
  $db->query($query);
  if($db->nf()){
    while($db->next_record()){
         $yfk += $db->f(fd_ysyfm_money)+0;      
    }  
  }
  	     
  //-------------------------------------------------------
  if($allysmoney>=0){  //判断应收款是否增加或者减少。
  	 if($nowysyfmoney>=0){   //如果往来单位欠我们的款，就直接在应收款科目上增加应收的金额
  	 	  //------------------插入应收款类别科目--------------------------
        $listtype = "3";    //单据类型
        $flstno = "105-".$organno;
        updatesubject($listtype , $listid , $flstno,$allysmoney,0 , 1);  //0代表正，1代表负数 	
  	 }else{ //如果欠往来单位的
  	    $tmpnowysyfmoney = -$nowysyfmoney;
        $discrepantmoeny = $allysmoney - $tmpnowysyfmoney;
        if($discrepantmoeny>0){  //如果应收金额比现在的应付款还大，那就应收款科目增加超出部分金额
        	 $listtype = "3";    //单据类型
           $flstno = "105-".$organno;
        	 updatesubject($listtype , $listid , $flstno , $discrepantmoeny,0 , 1);  //0代表正，1代表负数
        	  
        	 $listtype = "3";    //单据类型
           $flstno = "201-".$organno;
           updatesubject($listtype , $listid , $flstno , $tmpnowysyfmoney,1 , 1);  //0代表正，1代表负数
        }else{  //如果应付款比单据的金额还大，那应付款科目直接减少。
          $listtype = "3";    //单据类型
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $allysmoney,1 , 1);  //0代表正，1代表负数
       }
  	 }
  }else{
     //多出来的款项目，做成应收款。
     $i_yfmoney = $paymoney - $allmoney ;
     $discrepantmoeny = $i_yfmoney - $nowysyfmoney;
     if($discrepantmoeny>0){  //如果收款金额比应收款的金额还大，那就把超出部分做应付款。
     	 if($nowysyfmoney>=0){  //如果之前应收该往来单位，那就应收款科目就平掉该往来单位的应收款，剩余的部分做到应付款科目里
     	 	  //------------------插入应付款科目--------------------------
          $listtype = "3";    //单据类型
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $discrepantmoeny,0 , 1);  //0代表正，1代表负数
          
          //------------------插入应收款科目--------------------------
          $listtype = "3";    //单据类型
          $flstno = "105-".$organno;
     	    updatesubject($listtype , $listid , $flstno , $nowysyfmoney,1 , 1);  //0代表正，1代表负数
     	 }else{  //如果之前是应付该往来单位的，应付款科目就增加收取的金额。
     	    //------------------插入应付款科目--------------------------
          $listtype = "3";    //单据类型
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $i_yfmoney,0 , 1);  //0代表正，1代表负数
     	 }
     	 
     }else{
        //------------------插入应收款科目--------------------------
       $listtype = "3";    //单据类型
       $flstno = "105-".$organno;
     	 updatesubject($listtype , $listid , $flstno , $i_yfmoney,1 , 1);  //0代表正，1代表负数
     }
  }//--------------------------------------------------
   	  
  	  
  //------------------插入库存商品类别科目--------------------------
  $listtype = "3";    //单据类型
  $flstno = "101-".$organno;
  updatesubject($listtype , $listid , $flstno,$allstoragecost,1 , 1);  //0代表正，1代表负数
  */
  
  //------------------插入销售收入类别科目--------------------------
  $listtype = "3";    //单据类型
  $flstno = "301-".$organno;
  updatesubject($listtype , $listid , $flstno,$allnoyfmoney,0, 1);  //0代表正，1代表负数
  
  //------------------插入销售成本类别科目--------------------------
  $listtype = "3";    //单据类型
  $flstno = "401-".$organno;
  updatesubject($listtype , $listid , $flstno,$allstoragecost,0, 1);  //0代表正，1代表负数
     	    
  $query = "update tb_salelist set fd_selt_allmoney = '$allmoney'  , fd_selt_allcost = '$allstoragecost'  ,
            fd_selt_alldunshu = '$alldunquantity'   , fd_selt_isch = 1 , fd_selt_chdate = now()                 
            where fd_selt_id = '$listid'";
  $db->query($query);   //修改单据金额
  	      	  
  $query = "update tb_customer set fd_cus_iswebwl = 1 where fd_cus_id = '$cusid'";
  $db->query($query);
  
  //更新订单状态
  $query = "update web_order set fd_order_alldunshu = '$alldunquantity',fd_order_allmoney= '$allmoney',  
            fd_order_allcost = '$allstoragecost' ,fd_order_state = '7'
            where fd_order_id = '$weborderid'";
  $dberp->query($query);
  
  $query = "select fd_order_memeberid from web_order where fd_order_id = '$weborderid'";
  $dberp->query($query);
  if($dberp->nf()){
  	$dberp->next_record();
  	$memid = $dberp->f(fd_order_memeberid);

  }
  
  
      
  $query = "select max(fd_order_date) as lastbuy,sum(fd_order_allmoney) as allmoney from web_order where (fd_order_state = 6 or fd_order_state = 7) and fd_order_zf = 0 and fd_order_memeberid = '$memid' group by fd_order_memeberid";
  $dberp->query($query);
  if($dberp->nf()){
  	$dberp->next_record();
  	$lastbuy = $dberp->f(lastbuy);
  	$allmoney = $dberp->f(allmoney);
  	
  	$query = "update tb_organmem set fd_organmem_lastsaletime = '$lastbuy' , fd_organmem_allmoney = '$allmoney' where fd_organmem_id = '$memid'";
  	$dberp->query($query);
  }
   //更新抢注会员会员卡
 $query = "select * from tb_organmem where fd_organmem_id = '$memid'";
$dberp->query($query);
$dberp->next_record();
$getstate = $dberp->f(fd_organmem_getstate);
$getsalerid = $dberp->f(fd_organmem_getsalerid);	

 if($getstate==1 and $getsalerid>0){
 //检测是否同一网导
 $query = "select * from tb_organmem
 left join web_salercard on fd_salercard_id = fd_organmem_mcardid
 where fd_organmem_id = '$memid' and fd_salercard_salerid = '$getsalerid'
 ";	
 $dberp->query($query);
 if($dberp->nf()==0){
 	//选取会员卡
 $query = "select* from web_salercard where fd_salercard_salerid = '$getsalerid' and fd_salercard_state =1 and fd_salercard_memberid =0 and fd_salercard_zf =0 limit 0,1";	
 $dberp->query($query);
 	if($dberp->nf()){
 		$dberp->next_record();
 		$mcardid = $dberp->f(fd_salercard_id);
 		
 //绑定会员卡
 $query = "update tb_organmem set fd_organmem_mcardid = '$mcardid',fd_organmem_getstate=0,fd_organmem_getsalerid=0,fd_organmem_getmemdate='' where fd_organmem_id = '$memid'";
 $dberp->query($query);
  $query = "update web_salercard set fd_salercard_memberid = '$memid',fd_salercard_opendate = now() where fd_salercard_id = '$mcardid'";
 $dberp->query($query);	
  $query = "update tb_salelist set fd_selt_cardid = '$mcardid'      
            where fd_selt_id = '$listid'";
  $db->query($query);   
  
  //清除抢注资料
 $query = "delete from  web_getmemcd where fd_getmemcd_salerid = '$getsalerid' and fd_getmemcd_memid = '$memid'"; 
  $dberp->query($query);	
 	}
 	
 	
}
}
  
  /*
  if($type == "wy"){
    $gotourl = "http://www.ms56.net/mssale/order_zszfcg.php?orderid=".$weborderid;
  }else{
    $gotourl = "http://www.ms56.net/mssale/admin/order/tb_ordercl.php";
  }  
  echo "<script>location='".$gotourl."'</script>";
  */
  //Header("Location: $gotourl"); 

}

}

?>

