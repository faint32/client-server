<?php      


function create_erpinmoney($memid,$paymoney){
      $db    = new mssale_test;
      $db2   = new mssale_test;
      $dberp = new erp_test;
      														
      $query = "select * from tb_organmem where fd_organmem_id = '$memid'";			  
      $db->query($query);
      if($db->nf()){
        $db->next_record();        
        $insert_cusid = $db->f(fd_organmem_cusid);
        $cusno        = $db->f(fd_organmem_cusno);
        $cusname      = $db->f(fd_organmem_cusname);
        
        $phone        = $db->f(fd_organmem_phone); 
        $mobile       = $db->f(fd_organmem_mobile); 
        $address      = $db->f(fd_organmem_address); 
        $comnpany     = $db->f(fd_organmem_comnpany);  
        $linkman      = $db->f(fd_organmem_linkman); 
        $email        = $db->f(fd_organmem_email);    
    
        $province     = $db->f(fd_organmem_province);    
        $city         = $db->f(fd_organmem_city);
        $county       = $db->f(fd_organmem_county);
        $memid        = $db->f(fd_order_memeberid);
        
        //绑定ERP客户
        if(empty($cusname)){			 		          	           
          $cusno = "ZB".strtoupper(c($comnpany)).randstr().""; 
          //生成客户编号
          $query = "select * from tb_webcustomer where fd_webcus_allname = '$comnpany' and fd_webcus_organid = '1'";		         
          $dberp->query($query);
          if($dberp->nf()){
            $dberp->next_record(); 
            $webmemid    = $dberp->f(fd_webcus_id);
          }else{		           		   
            $query="INSERT INTO tb_webcustomer (
                    fd_webcus_no          ,   fd_webcus_name      ,  fd_webcus_allname      ,
                    fd_webcus_address     ,   fd_webcus_phone1    ,  fd_webcus_quyuid       ,    
                    fd_webcus_newtime     ,   fd_webcus_provinces ,  fd_webcus_city         ,  
                    fd_webcus_county      ,   fd_webcus_organid   ,  fd_webcus_linkman      ,  
                    fd_webcus_manphone    ,   fd_webcus_email     
                    )VALUES(
                    '$cusno'              ,   '$comnpany'          ,  '$comnpany'           ,
                    '$address'            ,   '$phone'             ,  '$quyuid'             ,    
                    now()                 ,   '$province'          ,  '$city'               ,  
                    '$county'             ,   '1'                  ,  '$linkman'            ,  
                    '$mobile'             ,   '$email'
                    )";
            $dberp->query($query);	
            $webmemid = $dberp->insert_id(); 
          }
            
             
          $query = "select * from tb_customer where fd_cus_allname = '$comnpany' and fd_cus_organid = '1'";		         
          $dberp->query($query);
          if($dberp->nf()){
            $dberp->next_record(); 
            $insert_cusid = $dberp->f(fd_cus_id);
            $cusno = $dberp->f(fd_cus_no);
          }else{
            $query="INSERT INTO tb_customer(
                   fd_cus_no          ,   fd_cus_name      ,  fd_cus_allname      ,
                   fd_cus_address     ,   fd_cus_phone1    ,  fd_cus_quyuid       ,    
                   fd_cus_newtime     ,   fd_cus_organid   ,  fd_cus_iswebuse     ,  
                   fd_cus_provinces   ,   fd_cus_city      ,  fd_cus_county       ,   
                   fd_cus_linkman     ,   fd_cus_manphone  ,  fd_cus_email        ,  
                   fd_cus_credit 
                   )VALUES (
                   '$cusno'           ,   '$comnpany'      ,  '$comnpany'         ,
                   '$address'         ,   '$phone'         ,  '$quyuid'           ,    
                   now()              ,   '1'              ,  '1'                 ,  
                   '$province'        ,   '$city'          ,  '$county'          ,   
                   '$linkman'         ,   '$mobile'      ,  '$email'           ,  
                   '100'  
                   )";
            $dberp->query($query);	
            $insert_cusid = $dberp->insert_id();  //取出刚插入的记录的主关键值的id
          }
            
          $query = "update tb_webcustomer set fd_webcus_erpcusid = '$insert_cusid' where fd_webcus_id = '$webmemid'";
          $dberp->query($query);	   	
          
          $query = "update tb_organmem set fd_organmem_cusid = '$insert_cusid',fd_organmem_cusno = '$cusno', fd_organmem_cusname = '$comnpany'
                    where fd_organmem_id = '$memid'";
          $db->query($query); 		                  	          
        }
        	
        			     			     			     
        //--------------------增加收款单功能----------------------------
        $nowdate = date("Y-m-d");
        $havesave = 0;
        $paymemo  = "手机支付自动生成的收款单";
        //$paymemo = u2g($paymemo);
        
        if(empty($cusname)){  //如果客户是为空，就绑定网站注册公司为客户名称
        	$cusname = $comnpany;
        }
        
        $quyuid  = 5;
        $mscid   = 11;
        $skmscid = 11;
        $skaccountid = "208";
                                                            
        $pjlistno = listnumber_update2(10);  //票据编号
        
        $xt = '系统';

        $query = "insert into tb_bill(
                  fd_bill_no          ,  fd_bill_clientid , fd_bill_type         ,
                  fd_bill_clientname  ,  fd_bill_organid  , fd_bill_accountid    ,
                  fd_bill_money       ,  fd_bill_date     , fd_bill_memo         ,
                  fd_bill_dealwithman ,  fd_bill_billno   , fd_bill_firstorganid ,
                  fd_bill_billtype    ,  fd_bill_bank     , fd_bill_number       ,
                  fd_bill_remitter    ,  fd_bill_enddate  , fd_bill_sdcrid       ,
                  fd_bill_clientno    ,  fd_bill_iecrid   , fd_bill_isopeninvoice,
                  fd_bill_mscompanyid ,  fd_bill_state    , fd_bill_hzdate
                  )values(
                  '$pjlistno'         , '$insert_cusid'     , '1'              ,
                  '$cusname'          , '1'                 , '$skaccountid'   ,
                  '$paymoney'         , '$nowdate'          , '$paymemo'       ,
                  '$xt'               ,  ''                 , '1'              ,   
                  '4'                 ,  ''                 , ''               ,
                  ''                  , '$nowdate'          , '$quyuid'        ,
                  '$cusno'            , ''                  , '1'              ,
                  '$skmscid'          , '4'                 , now()
                  )";
        $dberp->query($query);    //插入付款单
        $pjlistid = $dberp->insert_id();    //取出刚插入的记录的主关键值的id
        
        $sklistno = listnumber_update2(8);  //票据编号
        
        $query = "insert into tb_inmoneylist (
                  fd_inmylt_no        , fd_inmylt_clientid    , fd_inmylt_type         , 
                  fd_inmylt_clientno  , fd_inmylt_clientname  , fd_inmylt_dealwithman  ,
                  fd_inmylt_accountid , fd_inmylt_money       , fd_inmylt_date         ,
                  fd_inmylt_state     , fd_inmylt_memo        , fd_inmylt_datetime     ,
                  fd_inmylt_organid   , fd_inmylt_sdcrid      , fd_inmylt_mscompanyid  ,
                  fd_inmylt_skmscid   , fd_inmylt_billid
                  )values(
                  '$sklistno'         , '$insert_cusid'       , '1'                    ,
                  '$cusno'            , '$cusname'            , '$xt'                  ,
                  '$skaccountid'      , '$paymoney'           , '$nowdate'             ,
                  '1'                 , '$paymemo'            , now()                  ,
                  '1'                 , '$quyuid'             , '$skmscid'             ,
                  '$skmscid'          , '$pjlistid'
                  )";
        $dberp->query($query);
        $sklistid = $dberp->insert_id();  //取出刚插入的记录的主关键值的id
        
        //生成客户往来对帐单 
        $ctatmemo     = "手机支付自动生成的收款单收取".$paymoney."元";

        $cactlisttype = "8";
        $addmoney = 0;
                
        zbcurrentaccount(1 , $insert_cusid , $addmoney ,$paymoney , $ctatmemo , $cactlisttype , $xt , $sklistid , $sklistno ,$nowdate  , $quyuid , $skmscid);
        	               
        changemoney(1 , $insert_cusid ,$paymoney , 1 , 1  , $quyuid , $skmscid , 1);  //修改应收应付款函数，0代表正，1代表负数
        
        changeaccount($skaccountid , $paymoney , 0);    //调用修改帐户金额的函数
        
        //生成帐户流水帐
        $chgememo     = "手机支付自动生成的收款单收取".$paymoney."元";
        
        $chgelisttype = "8";
        $cogetype = 0; //0为收款 ， 1为付款
        cashglide($skaccountid , $paymoney , $chgememo , $chgelisttype , $xt , $sklistid , $sklistno , $cogetype ,$nowdate );

     }	
}

function randstr($len=2) {
     $chars='0123456789';
     mt_srand((double)microtime()*1000000*getmypid());
     // seed the random number generater (must be done)
     $password='';
     while(strlen($password)<$len)
     $password.=substr($chars,(mt_rand()%strlen($chars)),1);
     return $password;
}  	

function listnumber_update2($listtype){
	$loginorganno = "a001";
  $loginorganid = '1';
	
	$db  = new erp_test;
	
	switch($listtype){
		case "5":  //内部购销
		  $organid = 1;
		  $query = "select fd_agency_no from tb_agency where fd_agency_id = '1'";
		  $db->query($query);
		  if($db->nf()){
		  	$db->next_record();
		  	$organno = $db->f(fd_agency_no);
		  }
		  break;
		case "6":  //内部退货
		  $organid = 1;
		  $query = "select fd_agency_no from tb_agency where fd_agency_id = '1'";
		  $db->query($query);
		  if($db->nf()){
		  	$db->next_record();
		  	$organno = $db->f(fd_agency_no);
		  }
		  break;
		case "7":  //内部付款
		  $organid = 1;
		  $query = "select fd_agency_no from tb_agency where fd_agency_id = '1'";
		  $db->query($query);
		  if($db->nf()){
		  	$db->next_record();
		  	$organno = $db->f(fd_agency_no);
		  }
		  break;
		case "22":  //内部应收款调整
		  $organid = 1;
		  $query = "select fd_agency_no from tb_agency where fd_agency_id = '1'";
		  $db->query($query);
		  if($db->nf()){
		  	$db->next_record();
		  	$organno = $db->f(fd_agency_no);
		  }
		  break;
		case "69":  //内部返利单
		  $organid = 1;
		  $query = "select fd_agency_no from tb_agency where fd_agency_id = '1'";
		  $db->query($query);
		  if($db->nf()){
		  	$db->next_record();
		  	$organno = $db->f(fd_agency_no);
		  }
		  break;
		default:
		  $organid = $loginorganid;
		  $organno = $loginorganno;
		  break;
	}

	
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

function zbcurrentaccount($ctatlinktype , $companyid  , $addmoney , $lessenmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno , $listdate , $sdcrid , $mscid ){    
	$db  = new erp_test;   
	$ishaverecord = 0;
	$query = "select * from tb_zbcurrentaccount where fd_ctat_linkid = '$companyid' and fd_ctat_linktype = '$ctatlinktype'
	          and fd_ctat_listid = '$listid' and fd_ctat_listtype = '$cactlisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($ishaverecord == 0){
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
}

function changemoney($unittype,$clientid,$money,$type , $organid , $sdcrid , $mscompanyid , $organtype){
	//$type =0表示应收 ， 1表示应付
	//$unittype = 2 表示供应商，1代表客户
	//$clientid 往来单位id号
	//$organid机构id号
	//$sdcrid配送中心id号
	//$mscompanyid明盛公司id
	//$organtype机构类型
	$db  = new erp_test;
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

function changeaccount($accountid , $money , $type){
	//$type =0表示增加金钱 ， 1表示减少金钱
	$db  = new erp_test;
		if($type==0){
			$endmoney = $money;
		}else{
		  $endmoney = -$money;
	  }
	  if($endmoney==""){
	  	$endmoney = 0;
	  }

	  $query = "update tb_account set fd_account_money = fd_account_money + '".$endmoney."' where fd_account_id = '$accountid' ";
	  $db->query($query);  //更新记录
}

//帐户流水帐
//$cogetype = 0; //0为增加 ， 1为减少
function cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype , $listdate ){   
	$db  = new erp_test;   
	$ishaverecord = 0;
	$query = "select * from tb_cashglide where fd_chge_accountid = '$accountid' 
	          and fd_chge_listid = '$listid' and fd_chge_listtype = '$chgelisttype' ";
	$db->query($query);
	if($db->nf()){
		$ishaverecord = 1;
	}
	if($allmoney<>0 && $ishaverecord == 0){
    $query = "SELECT MAX(fd_chge_id) as chge_id FROM tb_cashglide where 
              fd_chge_accountid = '$accountid' and fd_chge_iskickback <> 1"; 
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$maxchgeid = $db->f(chge_id);
    	
    	$query = "select fd_chge_balance from tb_cashglide 
                where fd_chge_id = '$maxchgeid' ";
      $db->query($query);
      if($db->nf()){
      	$db->next_record();
      	$chgebalance = $db->f(fd_chge_balance);
      }else{
        $chgebalance = 0 ;
      }
    }else{
      $chgebalance = 0 ;
    }
    if($allmoney!=0){  //金额不等于0 的才插入数据
      if($cogetype==0){
         $endchgebalance = $chgebalance+$allmoney;
         $query = "insert into tb_cashglide(
                   fd_chge_date     ,  fd_chge_accountid , fd_chge_listid   , 
                   fd_chge_listno   ,  fd_chge_listtype  , fd_chge_addmoney ,
                   fd_chge_lessen   ,  fd_chge_balance   , fd_chge_memo     ,
                   fd_chge_makename ,  fd_chge_datetime  , fd_chge_listdate
                   )values(
                   now()          ,  '$accountid'      , '$listid'        ,
                   '$listno'      ,  '$chgelisttype'   , '$allmoney'      ,
                   '0'            ,  '$endchgebalance' , '$chgememo'      ,
                   '$loginstaname',  now()             , '$listdate'
                   )";    
         $db->query($query);
      }else{
         $endchgebalance = $chgebalance-$allmoney;
         $query = "insert into tb_cashglide(
                   fd_chge_date     ,  fd_chge_accountid , fd_chge_listid   , 
                   fd_chge_listno   ,  fd_chge_listtype  , fd_chge_addmoney ,
                   fd_chge_lessen   ,  fd_chge_balance   , fd_chge_memo     ,
                   fd_chge_makename ,  fd_chge_datetime  , fd_chge_listdate
                   )values(
                   now()          ,  '$accountid'      , '$listid'        ,
                   '$listno'      ,  '$chgelisttype'   , '0'              ,
                   '$allmoney'    ,  '$endchgebalance' , '$chgememo'      ,
                   '$loginstaname',  now()             , '$listdate'
                   )";    
         $db->query($query);
      }
    }
  }
  //------------------------------------
}

/* 输入中文得到拼音 */
  $d=array(
  array("a",-20319),
  array("ai",-20317),
  array("an",-20304),
  array("ang",-20295),
  array("ao",-20292),
  array("ba",-20283),
  array("bai",-20265),
  array("ban",-20257),
  array("bang",-20242),
  array("bao",-20230),
  array("bei",-20051),
  array("ben",-20036),
  array("beng",-20032),
  array("bi",-20026),
  array("bian",-20002),
  array("biao",-19990),
  array("bie",-19986),
  array("bin",-19982),
  array("bing",-19976),
  array("bo",-19805),
  array("bu",-19784),
  array("ca",-19775),
  array("cai",-19774),
  array("can",-19763),
  array("cang",-19756),
  array("cao",-19751),
  array("ce",-19746),
  array("ceng",-19741),
  array("cha",-19739),
  array("chai",-19728),
  array("chan",-19725),
  array("chang",-19715),
  array("chao",-19540),
  array("che",-19531),
  array("chen",-19525),
  array("cheng",-19515),
  array("chi",-19500),
  array("chong",-19484),
  array("chou",-19479),
  array("chu",-19467),
  array("chuai",-19289),
  array("chuan",-19288),
  array("chuang",-19281),
  array("chui",-19275),
  array("chun",-19270),
  array("chuo",-19263),
  array("ci",-19261),
  array("cong",-19249),
  array("cou",-19243),
  array("cu",-19242),
  array("cuan",-19238),
  array("cui",-19235),
  array("cun",-19227),
  array("cuo",-19224),
  array("da",-19218),
  array("dai",-19212),
  array("dan",-19038),
  array("dang",-19023),
  array("dao",-19018),
  array("de",-19006),
  array("deng",-19003),
  array("di",-18996),
  array("dian",-18977),
  array("diao",-18961),
  array("die",-18952),
  array("ding",-18783),
  array("diu",-18774),
  array("dong",-18773),
  array("dou",-18763),
  array("du",-18756),
  array("duan",-18741),
  array("dui",-18735),
  array("dun",-18731),
  array("duo",-18722),
  array("e",-18710),
  array("en",-18697),
  array("er",-18696),
  array("fa",-18526),
  array("fan",-18518),
  array("fang",-18501),
  array("fei",-18490),
  array("fen",-18478),
  array("feng",-18463),
  array("fo",-18448),
  array("fou",-18447),
  array("fu",-18446),
  array("ga",-18239),
  array("gai",-18237),
  array("gan",-18231),
  array("gang",-18220),
  array("gao",-18211),
  array("ge",-18201),
  array("gei",-18184),
  array("gen",-18183),
  array("geng",-18181),
  array("gong",-18012),
  array("gou",-17997),
  array("gu",-17988),
  array("gua",-17970),
  array("guai",-17964),
  array("guan",-17961),
  array("guang",-17950),
  array("gui",-17947),
  array("gun",-17931),
  array("guo",-17928),
  array("ha",-17922),
  array("hai",-17759),
  array("han",-17752),
  array("hang",-17733),
  array("hao",-17730),
  array("he",-17721),
  array("hei",-17703),
  array("hen",-17701),
  array("heng",-17697),
  array("hong",-17692),
  array("hou",-17683),
  array("hu",-17676),
  array("hua",-17496),
  array("huai",-17487),
  array("huan",-17482),
  array("huang",-17468),
  array("hui",-17454),
  array("hun",-17433),
  array("huo",-17427),
  array("ji",-17417),
  array("jia",-17202),
  array("jian",-17185),
  array("jiang",-16983),
  array("jiao",-16970),
  array("jie",-16942),
  array("jin",-16915),
  array("jing",-16733),
  array("jiong",-16708),
  array("jiu",-16706),
  array("ju",-16689),
  array("juan",-16664),
  array("jue",-16657),
  array("jun",-16647),
  array("ka",-16474),
  array("kai",-16470),
  array("kan",-16465),
  array("kang",-16459),
  array("kao",-16452),
  array("ke",-16448),
  array("ken",-16433),
  array("keng",-16429),
  array("kong",-16427),
  array("kou",-16423),
  array("ku",-16419),
  array("kua",-16412),
  array("kuai",-16407),
  array("kuan",-16403),
  array("kuang",-16401),
  array("kui",-16393),
  array("kun",-16220),
  array("kuo",-16216),
  array("la",-16212),
  array("lai",-16205),
  array("lan",-16202),
  array("lang",-16187),
  array("lao",-16180),
  array("le",-16171),
  array("lei",-16169),
  array("leng",-16158),
  array("li",-16155),
  array("lia",-15959),
  array("lian",-15958),
  array("liang",-15944),
  array("liao",-15933),
  array("lie",-15920),
  array("lin",-15915),
  array("ling",-15903),
  array("liu",-15889),
  array("long",-15878),
  array("lou",-15707),
  array("lu",-15701),
  array("lv",-15681),
  array("luan",-15667),
  array("lue",-15661),
  array("lun",-15659),
  array("luo",-15652),
  array("ma",-15640),
  array("mai",-15631),
  array("man",-15625),
  array("mang",-15454),
  array("mao",-15448),
  array("me",-15436),
  array("mei",-15435),
  array("men",-15419),
  array("meng",-15416),
  array("mi",-15408),
  array("mian",-15394),
  array("miao",-15385),
  array("mie",-15377),
  array("min",-15375),
  array("ming",-15369),
  array("miu",-15363),
  array("mo",-15362),
  array("mou",-15183),
  array("mu",-15180),
  array("na",-15165),
  array("nai",-15158),
  array("nan",-15153),
  array("nang",-15150),
  array("nao",-15149),
  array("ne",-15144),
  array("nei",-15143),
  array("nen",-15141),
  array("neng",-15140),
  array("ni",-15139),
  array("nian",-15128),
  array("niang",-15121),
  array("niao",-15119),
  array("nie",-15117),
  array("nin",-15110),
  array("ning",-15109),
  array("niu",-14941),
  array("nong",-14937),
  array("nu",-14933),
  array("nv",-14930),
  array("nuan",-14929),
  array("nue",-14928),
  array("nuo",-14926),
  array("o",-14922),
  array("ou",-14921),
  array("pa",-14914),
  array("pai",-14908),
  array("pan",-14902),
  array("pang",-14894),
  array("pao",-14889),
  array("pei",-14882),
  array("pen",-14873),
  array("peng",-14871),
  array("pi",-14857),
  array("pian",-14678),
  array("piao",-14674),
  array("pie",-14670),
  array("pin",-14668),
  array("ping",-14663),
  array("po",-14654),
  array("pu",-14645),
  array("qi",-14630),
  array("qia",-14594),
  array("qian",-14429),
  array("qiang",-14407),
  array("qiao",-14399),
  array("qie",-14384),
  array("qin",-14379),
  array("qing",-14368),
  array("qiong",-14355),
  array("qiu",-14353),
  array("qu",-14345),
  array("quan",-14170),
  array("que",-14159),
  array("qun",-14151),
  array("ran",-14149),
  array("rang",-14145),
  array("rao",-14140),
  array("re",-14137),
  array("ren",-14135),
  array("reng",-14125),
  array("ri",-14123),
  array("rong",-14122),
  array("rou",-14112),
  array("ru",-14109),
  array("ruan",-14099),
  array("rui",-14097),
  array("run",-14094),
  array("ruo",-14092),
  array("sa",-14090),
  array("sai",-14087),
  array("san",-14083),
  array("sang",-13917),
  array("sao",-13914),
  array("se",-13910),
  array("sen",-13907),
  array("seng",-13906),
  array("sha",-13905),
  array("shai",-13896),
  array("shan",-13894),
  array("shang",-13878),
  array("shao",-13870),
  array("she",-13859),
  array("shen",-13847),
  array("sheng",-13831),
  array("shi",-13658),
  array("shou",-13611),
  array("shu",-13601),
  array("shua",-13406),
  array("shuai",-13404),
  array("shuan",-13400),
  array("shuang",-13398),
  array("shui",-13395),
  array("shun",-13391),
  array("shuo",-13387),
  array("si",-13383),
  array("song",-13367),
  array("sou",-13359),
  array("su",-13356),
  array("suan",-13343),
  array("sui",-13340),
  array("sun",-13329),
  array("suo",-13326),
  array("ta",-13318),
  array("tai",-13147),
  array("tan",-13138),
  array("tang",-13120),
  array("tao",-13107),
  array("te",-13096),
  array("teng",-13095),
  array("ti",-13091),
  array("tian",-13076),
  array("tiao",-13068),
  array("tie",-13063),
  array("ting",-13060),
  array("tong",-12888),
  array("tou",-12875),
  array("tu",-12871),
  array("tuan",-12860),
  array("tui",-12858),
  array("tun",-12852),
  array("tuo",-12849),
  array("wa",-12838),
  array("wai",-12831),
  array("wan",-12829),
  array("wang",-12812),
  array("wei",-12802),
  array("wen",-12607),
  array("weng",-12597),
  array("wo",-12594),
  array("wu",-12585),
  array("xi",-12556),
  array("xia",-12359),
  array("xian",-12346),
  array("xiang",-12320),
  array("xiao",-12300),
  array("xie",-12120),
  array("xin",-12099),
  array("xing",-12089),
  array("xiong",-12074),
  array("xiu",-12067),
  array("xu",-12058),
  array("xuan",-12039),
  array("xue",-11867),
  array("xun",-11861),
  array("ya",-11847),
  array("yan",-11831),
  array("yang",-11798),
  array("yao",-11781),
  array("ye",-11604),
  array("yi",-11589),
  array("yin",-11536),
  array("ying",-11358),
  array("yo",-11340),
  array("yong",-11339),
  array("you",-11324),
  array("yu",-11303),
  array("yuan",-11097),
  array("yue",-11077),
  array("yun",-11067),
  array("za",-11055),
  array("zai",-11052),
  array("zan",-11045),
  array("zang",-11041),
  array("zao",-11038),
  array("ze",-11024),
  array("zei",-11020),
  array("zen",-11019),
  array("zeng",-11018),
  array("zha",-11014),
  array("zhai",-10838),
  array("zhan",-10832),
  array("zhang",-10815),
  array("zhao",-10800),
  array("zhe",-10790),
  array("zhen",-10780),
  array("zheng",-10764),
  array("zhi",-10587),
  array("zhong",-10544),
  array("zhou",-10533),
  array("zhu",-10519),
  array("zhua",-10331),
  array("zhuai",-10329),
  array("zhuan",-10328),
  array("zhuang",-10322),
  array("zhui",-10315),
  array("zhun",-10309),
  array("zhuo",-10307),
  array("zi",-10296),
  array("zong",-10281),
  array("zou",-10274),
  array("zu",-10270),
  array("zuan",-10262),
  array("zui",-10260),
  array("zun",-10256),
  array("zuo",-10254)
 );
 function g($num){
  global $d;
  if($num>0&&$num<160){
   return chr($num);
  }
  elseif($num<-20319||$num>-10247){
   return "";
  }else{
   for($i=count($d)-1;$i>=0;$i--){if($d[$i][1]<=$num)break;}
   return $d[$i][0];
  }
 }
 
 function c($str){
  $ret="";
  for($i=0;$i<strlen($str);$i++){
   $p=ord(substr($str,$i,1));
  
   if($p>160){
   
    $q=ord(substr($str,++$i,1));
 
    $p=$p*256+$q-65536;
 
   }
   $ret.=substr(g($p),0,1);
   
  }
  return $ret;
 }

?>