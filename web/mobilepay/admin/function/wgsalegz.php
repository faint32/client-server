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
  $db->query($query);   //�޸ĵ�������
  	  
  	  
  //���¶���״̬
  $query = "update web_order set fd_order_state = '7',fd_order_gzdate = now()  where fd_order_id = '$weborderid'";
  $dberp->query($query);
  	  
  $query = "select * from web_order where fd_order_id = '$weborderid'";
  $dberp->query($query);
  if($dberp->nf()){
    $dberp->next_record();
    $memeberid = $dberp->f(fd_order_memeberid);
    $gdr = $dberp->f(fd_order_ddclr);
    
    //�����Ա��
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
    $listno       = $db->f(fd_selt_no);            //���ݱ��
    $cusid        = $db->f(fd_selt_cusid);         //�ͻ�id��
    $cusno        = $db->f(fd_selt_cusno);         //�ͻ���� 
    $cusname      = $db->f(fd_selt_cusname);       //�ͻ�����
    $sendcenterid = $db->f(fd_selt_sdcrid);        //��������id��
    $mscompanyid  = $db->f(fd_selt_mscompanyid);   //������˾id�� 
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
    
    $wlyf         = $db->f(fd_selt_wlyf);          //�����˷� 
    $wlzftype     = $db->f(fd_selt_wlzftype);      //�����˷�֧������  
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
    $listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
    
    //------------------------------����ɹ���--------------------------------
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
    	  $suppid = $db1->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
      }
    	
    	$cg_memo_z = "�������۵��Զ����ɵ����еĽ�����";
      $cg_listno = listnumber_update(1);  //���浥��
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
      $db->query($query);   //���뵥������
      $cg_listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
    }
  }
  	  
  //���¿ͻ�����������ʱ��
  $query = "update tb_customer set fd_cus_newdatetime = now(),fd_cus_newlistid = '$listid',
            fd_cus_newlisttype = '1'
            where fd_cus_id = '$cusid'";
  $db->query($query);
  
  //��������ϸ�ڱ����Ʒ����
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
  	         $db1->query($query);   //����ϸ�ڱ� ����
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
  		   $price          = $db->f(fd_stdetail_price);     //����
  		   $tmpunit        = $db->f(fd_stdetail_unit);
  		   $tmpcost        = $db->f(fd_stdetail_tmpcost);
  		   $tmpralation    = $db->f(fd_produre_relation3);  //��Ӧ��ϵ3
  		   $storageid      = $db->f(fd_stdetail_storageid);     //�ֿ�ID
  		   
  		   $dunquantity = changekg($tmpralation , $tmpunit , $quantity);  //������
  		   	  		  	  		   	  		   	  		  		
  		   $alldunquantity +=$dunquantity;
  		   
  		   if(empty($sendcenterid)){  
  		     $sendcenterid=0; //��������id
  		   }
  		   
  	     
  		   //���ҿ���Ƿ�������
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
         
         //�����Ƿ��п��ɱ���
  		   $query = "select * from tb_storagecost where fd_sect_organid = '1'
  		             and fd_sect_commid = '$commid' and fd_sect_sdcrid = '$sendcenterid'";
  		   $db1->query($query);
  		   if($db1->nf()){
  		   	$db1->next_record();
  		   	$storagecost = $db1->f(fd_sect_cost);
         
  		   	if($storagecost==0 and $flagquantity==0){   //�����浥��Ϊ0ʱ�����޸Ŀ�浥��
         	  $query = "update tb_storagecost set fd_sect_cost = '$tmpcost'
                       where fd_sect_organid = '1'  and fd_sect_sdcrid = '$sendcenterid'
                       and fd_sect_commid = '$commid'";
             $db1->query($query);   
           }
  		   }else{  //���û�п��ɱ���¼
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
         //�޸Ĳֿ�������ͳɱ���
         updatestorage($commid,$quantity,$storagecost,$storageid,1,$sendcenterid,1);  //0��������1����
         
         $query = "update tb_salelistdetail set fd_stdetail_cost = '$storagecost'
                   where fd_stdetail_id = '$tmpsbdetailid'  ";
         $db1->query($query);   //�޸���Ʒ�Ŀ��ɱ���
         
         $allstoragecost += $storagecost*$quantity;    //���۳ɱ���
         
  		   //��Ʒ��ˮ��
  		   $cogememo     = "��Ʒ���ۼ���";
  		   $cogelisttype = "3";
  		   $cogetype = 1; //0Ϊ���� �� 1Ϊ����
  		   commglide($storageid , $commid , $quantity , $cogememo , $cogelisttype , $gdr , $listid , $listno , $cogetype , $date);     		
  		   
  		   $allmoney += $quantity*$price;  //�����ܶ�
  		   
  		   /*
  		   //��¼����Ʒ�����ÿͻ���������ۼ�
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
  //��ѯ��ʱ��Ӧ��Ӧ����
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

  
  //�޸�Ӧ�ս��
  if($wlzftype == 2){
  	$allmoney = $allmoney+$wlyf;
  }
    
  $allysmoney = $allmoney;
    
  //�����ܲ��������ʵ�
  $ctatmemo     = "Ӧ��".$cusname."�ͻ�".$allysmoney."Ԫ";
  $cactlisttype = "3";
  $addmoney = $allmoney;
  $lessenmoney = $paymoney;
  zbcurrentaccount(1 , $cusid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $gdr , $listid , $listno ,$date , $sendcenterid , $mscompanyid);
  
  
  if($allysmoney<>0){
    changemoney(1 , $cusid ,$allysmoney , 0 , 1 , $sendcenterid , $mscompanyid , $organtype);  //����λ0��������1������
  }
  
  /*
  //��ȡ�ͻ�Ӧ�տ�
  $query = "select * from tb_ysyfmoney 
             where fd_ysyfm_type =1 and fd_ysyfm_companyid = '$cusid'";
  $db->query($query);
  if($db->nf()){
    while($db->next_record()){
         $yfk += $db->f(fd_ysyfm_money)+0;      
    }  
  }
  	     
  //-------------------------------------------------------
  if($allysmoney>=0){  //�ж�Ӧ�տ��Ƿ����ӻ��߼��١�
  	 if($nowysyfmoney>=0){   //���������λǷ���ǵĿ��ֱ����Ӧ�տ��Ŀ������Ӧ�յĽ��
  	 	  //------------------����Ӧ�տ�����Ŀ--------------------------
        $listtype = "3";    //��������
        $flstno = "105-".$organno;
        updatesubject($listtype , $listid , $flstno,$allysmoney,0 , 1);  //0��������1������ 	
  	 }else{ //���Ƿ������λ��
  	    $tmpnowysyfmoney = -$nowysyfmoney;
        $discrepantmoeny = $allysmoney - $tmpnowysyfmoney;
        if($discrepantmoeny>0){  //���Ӧ�ս������ڵ�Ӧ������Ǿ�Ӧ�տ��Ŀ���ӳ������ֽ��
        	 $listtype = "3";    //��������
           $flstno = "105-".$organno;
        	 updatesubject($listtype , $listid , $flstno , $discrepantmoeny,0 , 1);  //0��������1������
        	  
        	 $listtype = "3";    //��������
           $flstno = "201-".$organno;
           updatesubject($listtype , $listid , $flstno , $tmpnowysyfmoney,1 , 1);  //0��������1������
        }else{  //���Ӧ����ȵ��ݵĽ�����Ӧ�����Ŀֱ�Ӽ��١�
          $listtype = "3";    //��������
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $allysmoney,1 , 1);  //0��������1������
       }
  	 }
  }else{
     //������Ŀ���Ŀ������Ӧ�տ
     $i_yfmoney = $paymoney - $allmoney ;
     $discrepantmoeny = $i_yfmoney - $nowysyfmoney;
     if($discrepantmoeny>0){  //����տ����Ӧ�տ�Ľ����ǾͰѳ���������Ӧ���
     	 if($nowysyfmoney>=0){  //���֮ǰӦ�ո�������λ���Ǿ�Ӧ�տ��Ŀ��ƽ����������λ��Ӧ�տʣ��Ĳ�������Ӧ�����Ŀ��
     	 	  //------------------����Ӧ�����Ŀ--------------------------
          $listtype = "3";    //��������
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $discrepantmoeny,0 , 1);  //0��������1������
          
          //------------------����Ӧ�տ��Ŀ--------------------------
          $listtype = "3";    //��������
          $flstno = "105-".$organno;
     	    updatesubject($listtype , $listid , $flstno , $nowysyfmoney,1 , 1);  //0��������1������
     	 }else{  //���֮ǰ��Ӧ����������λ�ģ�Ӧ�����Ŀ��������ȡ�Ľ�
     	    //------------------����Ӧ�����Ŀ--------------------------
          $listtype = "3";    //��������
          $flstno = "201-".$organno;
          updatesubject($listtype , $listid , $flstno , $i_yfmoney,0 , 1);  //0��������1������
     	 }
     	 
     }else{
        //------------------����Ӧ�տ��Ŀ--------------------------
       $listtype = "3";    //��������
       $flstno = "105-".$organno;
     	 updatesubject($listtype , $listid , $flstno , $i_yfmoney,1 , 1);  //0��������1������
     }
  }//--------------------------------------------------
   	  
  	  
  //------------------��������Ʒ����Ŀ--------------------------
  $listtype = "3";    //��������
  $flstno = "101-".$organno;
  updatesubject($listtype , $listid , $flstno,$allstoragecost,1 , 1);  //0��������1������
  */
  
  //------------------����������������Ŀ--------------------------
  $listtype = "3";    //��������
  $flstno = "301-".$organno;
  updatesubject($listtype , $listid , $flstno,$allnoyfmoney,0, 1);  //0��������1������
  
  //------------------�������۳ɱ�����Ŀ--------------------------
  $listtype = "3";    //��������
  $flstno = "401-".$organno;
  updatesubject($listtype , $listid , $flstno,$allstoragecost,0, 1);  //0��������1������
     	    
  $query = "update tb_salelist set fd_selt_allmoney = '$allmoney'  , fd_selt_allcost = '$allstoragecost'  ,
            fd_selt_alldunshu = '$alldunquantity'   , fd_selt_isch = 1 , fd_selt_chdate = now()                 
            where fd_selt_id = '$listid'";
  $db->query($query);   //�޸ĵ��ݽ��
  	      	  
  $query = "update tb_customer set fd_cus_iswebwl = 1 where fd_cus_id = '$cusid'";
  $db->query($query);
  
  //���¶���״̬
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
   //������ע��Ա��Ա��
 $query = "select * from tb_organmem where fd_organmem_id = '$memid'";
$dberp->query($query);
$dberp->next_record();
$getstate = $dberp->f(fd_organmem_getstate);
$getsalerid = $dberp->f(fd_organmem_getsalerid);	

 if($getstate==1 and $getsalerid>0){
 //����Ƿ�ͬһ����
 $query = "select * from tb_organmem
 left join web_salercard on fd_salercard_id = fd_organmem_mcardid
 where fd_organmem_id = '$memid' and fd_salercard_salerid = '$getsalerid'
 ";	
 $dberp->query($query);
 if($dberp->nf()==0){
 	//ѡȡ��Ա��
 $query = "select* from web_salercard where fd_salercard_salerid = '$getsalerid' and fd_salercard_state =1 and fd_salercard_memberid =0 and fd_salercard_zf =0 limit 0,1";	
 $dberp->query($query);
 	if($dberp->nf()){
 		$dberp->next_record();
 		$mcardid = $dberp->f(fd_salercard_id);
 		
 //�󶨻�Ա��
 $query = "update tb_organmem set fd_organmem_mcardid = '$mcardid',fd_organmem_getstate=0,fd_organmem_getsalerid=0,fd_organmem_getmemdate='' where fd_organmem_id = '$memid'";
 $dberp->query($query);
  $query = "update web_salercard set fd_salercard_memberid = '$memid',fd_salercard_opendate = now() where fd_salercard_id = '$mcardid'";
 $dberp->query($query);	
  $query = "update tb_salelist set fd_selt_cardid = '$mcardid'      
            where fd_selt_id = '$listid'";
  $db->query($query);   
  
  //�����ע����
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

