<?

//自动生成单据单据编号
function listnumber_update(){
	$db  = new DB_erp;	
	$listtype = 115;
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

function changemoney($unittype,$clientid,$money,$type , $organid , $sdcrid , $mscompanyid , $organtype){
	//$type =0表示应收 ， 1表示应付
	//$unittype = 2 表示供应商，1代表客户
	//$clientid 往来单位id号
	//$organid机构id号
	//$sdcrid配送中心id号
	//$mscompanyid明盛公司id
	//$organtype机构类型
	$db  = new DB_erp;
	if($type==0){
		$endmoney = $money;
	}else{
		$endmoney = -$money;
	}
	if($organtype==1){
		$sqlwhere = " and fd_ysyfm_mscompanyid = '$mscompanyid'";
	}else{
	  $sqlwhere = "";
  }
	$query = "select * from tb_ysyfmoney where fd_ysyfm_type = '$unittype'
	          and fd_ysyfm_companyid = '$clientid' $sqlwhere";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$oldmoney = $db->f(fd_ysyfm_money);
		$listid = $db->f(fd_ysyfm_id);

		$newmoney = $oldmoney + $endmoney;

		$query ="update tb_ysyfmoney set fd_ysyfm_money = '$newmoney'
		         where fd_ysyfm_id = '$listid' ";
		$db->query($query);
	}else{
	  $query = "insert into tb_ysyfmoney(
	            fd_ysyfm_type     , fd_ysyfm_companyid , fd_ysyfm_money       ,
	            fd_ysyfm_organid  , fd_ysyfm_sdcrid    , fd_ysyfm_mscompanyid
	            )values(
	            '$unittype'   , '$clientid'        , '$endmoney'     ,
	            '$organid'    , '$sdcrid'          , '$mscompanyid'
	            )";
	  $db->query($query);
  }
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

function zbcurrentaccount($ctatlinktype , $companyid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid ){    
	$db  = new DB_erp;   
	$ishaverecord = 0;
	$query = "select * from tb_zbcurrentaccount where fd_ctat_linkid = '$companyid' and fd_ctat_linktype = '$ctatlinktype'
	          and fd_ctat_listid = '$listid' and fd_ctat_listtype = '$cactlisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
     /*$query = "SELECT MAX(fd_ctat_id) as ctat_id FROM tb_zbcurrentaccount where 
               fd_ctat_linktype = '$ctatlinktype' and fd_ctat_linkid = '$companyid' 
               and fd_ctat_iskickback <> 1 and fd_ctat_mscid = '$mscid'"; 
     $db->query($query);
     if($db->nf()){
     	$db->next_record();
     	$maxctatid = $db->f(ctat_id);
     	
     	$query = "select fd_ctat_balance from tb_zbcurrentaccount 
                 where fd_ctat_id = '$maxctatid' ";
       $db->query($query);
       if($db->nf()){
       	$db->next_record();
       	$ctatbalance = $db->f(fd_ctat_balance);
       }else{
         $ctatbalance = 0 ;
       }
     }else{
       $ctatbalance = 0 ;
     }*/
     $endctatbalance = $ctatbalance+$addmoney-$lessenmoney;
     $query = "insert into tb_zbcurrentaccount(
               fd_ctat_date     ,  fd_ctat_linktype  , fd_ctat_linkid   , 
               fd_ctat_listno   ,  fd_ctat_listtype  , fd_ctat_addmoney ,
               fd_ctat_lessen   ,  fd_ctat_balance   , fd_ctat_memo     ,
               fd_ctat_listid   ,  fd_ctat_makename  , fd_ctat_datetime ,
               fd_ctat_listdate ,  fd_ctat_sdcrid    , fd_ctat_mscid
               )values(
               now()          ,  '$ctatlinktype'   , '$companyid'     ,
               '$listno'      ,  '$cactlisttype'   , '$addmoney'      ,
               '$lessenmoney' ,  '$endctatbalance' , '$ctatmemo'      ,
               '$listid'      ,  '$loginstaname'   , now()            ,
               '$listdate'    ,  '$sdcrid'         , '$mscid'
               )";    
     $db->query($query);
  }
  //------------------------------------
}

function update_erpcusfk($cusid,$fkmoney,$year,$month){
	  //取得最新订单所属的配送中心
	  $db  = new DB_test;
	  $query = "select fd_order_sdcrid,fd_organmem_cusname from tb_order 
	            left join tb_organmem on fd_organmem_id = fd_order_memeberid
	            where fd_organmem_cusid = '$cusid' order by fd_order_date desc";
	  $db->query($query); 
	  if($db->nf()){
	    $db->next_record();
	    $sdcrid = $db->f(fd_order_sdcrid);
	    $cusname = $db->f(fd_organmem_cusname);
	    
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
	    
	    $db_erp = new DB_erp ;
	    
	    //自动生成单据单据编号
	    $listno = listnumber_update();
	    $query = "insert into tb_wzfkfy  (
                fd_wzfkfy_no         , fd_wzfkfy_cusid            , fd_wzfkfy_money       ,
                fd_wzfkfy_date       , fd_wzfkfy_year             , fd_wzfkfy_month       ,
                fd_wzfkfy_sdcrid     , fd_wzfkfy_organid   
                )values( 
                '$listno'            , '$cusid'                   , '$fkmoney'            ,
                now()                , '$year'                    , '$month'              ,
                '$sdcrid'            , '1'                 
                )"; 
      $db_erp->query($query);
      $listid = $db_erp->insert_id();
      
      //更新应收款
      if($fkmoney<>0){
	      changemoney(1 , $cusid ,$fkmoney , 1 , 1 , $sdcrid , $mscompanyid , 1);  //第四位0代表正，1代表负数
	      
	      //生成总部往来对帐单
	      $ctatmemo     = "应付".$cusname."客户".$fkmoney."元";
	      $cactlisttype = "115";
	      $addmoney = 0;
	      $lessenmoney = $fkmoney;
	      $date = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()),date( "Y", mktime())));
	      zbcurrentaccount(1 , $cusid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , '网站自动生成' , $listid , $listno ,$date , $sdcrid , $mscompanyid);
	      
        //------------------插入网站返款费用科目--------------------------
	      $listtype = "115";    //单据类型
	      $flstno = "4030313-a001";
	      updatesubject($listtype , $listid , $flstno,$fkmoney,0, 1);  //0代表正，1代表负数	      
	    }
    			    
	  }  
}

?>