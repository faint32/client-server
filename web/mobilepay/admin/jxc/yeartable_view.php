<?php
$thismenucode = "2k223";
require ("../include/common.inc.php");

$db = new DB_test ;
$dbtj = new DB_test ;
$gourl = "yeartable_query.php";
$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$t = new Template('.', "keep");
$t->set_file("yeartable_view","yeartable_view.html");
$t->set_block("yeartable_view", "BXBK", "bxbks");

$begindate = date( "Y/m/d" , mktime(0,0,0,$month,1,$year));
$enddate = date( "Y/m/d" , mktime(0,0,0,$month+1,0,$year));

//统计工资单
/*$query = "select fd_leim_type,fd_wagelistdetail_wage , month(fd_wagelist_date) as datemonth from  tb_wagelistdetail 
          left join tb_wagelist on fd_wagelist_id = fd_wagelistdetail_wlid    
          left join tb_laborageitem on fd_wagelistdetail_leimid = fd_leim_id
          where year(fd_wagelist_date) = '$year' 
         " ;      
$db->query($query);
if($db->nf()){
  while($db->next_record()){
  	   $listdate  = $db->f(datemonth); 
       $wage = $db->f(fd_wagelistdetail_wage);
       
       $leim_type = $db->f(fd_leim_type);
       
       if($leim_type == 0){
         $arr_gz[$listdate] += $wage; 
       }else{
         $arr_gz[$listdate] -= $wage; 
       } 
  }
}*/


//销售单
$query = "select sum(fd_selt_allmoney) as allmoney , sum(fd_selt_allcost) as allcost  , month(fd_selt_date) as datemonth
          from tb_salelist 
          where year(fd_selt_date) = '$year' and fd_selt_state =9 
           group by fd_selt_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$xsmoney    = $db->f(allmoney);
		$listdate   = $db->f(datemonth);        
    $xscost     = $db->f(allcost);
		
		$arr_xsmoney[$listdate] += $xsmoney;
		$arr_xscost[$listdate] += $xscost;
  }
}

//查看月往来数
/*$query = "select COUNT(DISTINCT fd_selt_cusid) as cusnum , month(fd_selt_date) as datemonth from tb_salelist
          where year(fd_selt_date) = '$year' and fd_selt_state =1 
          and fd_selt_iskickback = 0  group by fd_selt_date " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$wlcusnum  = $db->f(cusnum);
		$listdate   = $db->f(datemonth);        //单据时间

		$arr_wlcusnum[$listdate] += $wlcusnum;
  }
}*/

//销售退货单
$query = "select sum(fd_selt_allmoney) as allmoney , sum(fd_selt_allcost) as allcost, 
         month(fd_selt_date) as datemonth from tb_salelistback  
         where year(fd_selt_date) = '$year' and fd_selt_state =1 
         group by fd_selt_date" ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate    = $db->f(datemonth);             

		$arr_xsmoney[$listdate] -= $db->f(allmoney);
		$arr_xscost[$listdate] -= $db->f(allcost);
  }
}

//其他收入单
/*$query = "select fd_incomelist_allmoney , month(fd_incomelist_date) as datemonth
         from tb_incomelist 
         where year(fd_incomelist_date) = '$year' and fd_incomelist_state =2 
         and fd_incomelist_kickback = 0  " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$qtsrmoney   = $db->f(fd_incomelist_allmoney);
		$listdate    = $db->f(datemonth);    
		
		$arr_qtsrmoney[$listdate] += $qtsrmoney;
		
  }
}

//费用单
$query = "select fd_payoutlist_allmoney , month(fd_payoutlist_date) as datemonth 
         from tb_payoutlist 
         where year(fd_payoutlist_date) = '$year' and fd_payoutlist_state =2 
         and fd_payoutlist_kickback = 0  ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$fymoney   = $db->f(fd_payoutlist_allmoney);
		$listdate  = $db->f(datemonth);        
		$arr_fymoney[$listdate] += $fymoney;
  }
}

//盘点利润
$query = "select fd_checknote_profit ,  month(fd_checknote_date) as datemonth  from tb_checknote  
         left join tb_storage on fd_storage_id = fd_checknote_storageid
         where year(fd_checknote_date) = '$year'";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$pdlirun   = $db->f(fd_checknote_profit);
		$listdate  = $db->f(datemonth);        
		$arr_pdlirun[$listdate] += $pdlirun;
  }
}


//外部应收款调帐
$query = "select (fd_ysyfat_nowmoney-fd_ysyfat_oldmoney) as lirun , month(fd_ysyfat_date) as datemonth
	        from tb_ysyfadjust
          where year(fd_ysyfat_date) = '$year'";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$wbysktz   = $db->f(lirun);
		$listdate  = $db->f(datemonth);        
		$arr_wbysktz[$listdate] += $wbysktz;
  }
}

//帐户调帐收入
$query = "select fd_atat_money , month(fd_atat_date) as datemonth from tb_accountadjust
          left join tb_account on fd_account_id = fd_atat_accountid
          where year(fd_atat_date) = '$year' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$zhtz     = $db->f(fd_atat_money);
		$listdate  = $db->f(datemonth);        
		if($zhtz>0){
		  $arr_zhtzsr[$listdate] += $zhtz;
	  }else{
	    $arr_zhtzgs[$listdate] += -$zhtz;
	  }
  }
}

//组装拆卸利润
$query = "select sum(fd_loaddown_lirun) as allmoney  , month(fd_loaddown_date) as datemonth 
          from tb_loaddown  
          where year(fd_loaddown_date) = '$year' and fd_loaddown_staus =1 
          and fd_loaddown_backstaus = 0 group by fd_loaddown_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$zzcx     = $db->f(allmoney);
		$listdate = $db->f(datemonth);        
		$arr_zzcx[$listdate] += $zzcx;
  }
}

//外部进货退货利润
$query = "select (fd_stockback_allmoney - fd_stockback_allcost) as lirun , month(fd_stockback_date) as datemonth  
         from tb_stockback  
         where year(fd_stockback_date) = '$year' and fd_stockback_state =1 
         and fd_stockback_iskickback = 0 "  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$jhthlirun = $db->f(lirun);
		$listdate  = $db->f(datemonth);        
		
		$arr_jhthlirun[$listdate] += $jhthlirun;
  }
}

//商品调价利润
$query = "select fd_ccp_lirun , month(fd_ccp_date) as datemonth   
         from tb_commoditychangeprice     
         where year(fd_ccp_date) = '$year'
         and fd_ccp_staus = 1" ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$sptjlirun = $db->f(fd_ccp_lirun);
		$listdate  = $db->f(datemonth);        
		$arr_sptjlirun[$listdate] += $sptjlirun;
  }
}


//固定资产额外收入
$query = "select fd_bfes_money , month(fd_bfes_date) as datemonth   
          from tb_buyfixedassets
          where year(fd_bfes_date) = '$year' and fd_bfes_state =2 
          and fd_bfes_accountid = '0' "  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(datemonth);        
	  $arr_gdzcewsr[$listdate] += $db->f(fd_bfes_money);
  }
}

//固定资产变卖差价收入
$query = "select (fd_clear_skmoney-fd_clear_zcmoney) as lirun , month(fd_clear_qlrq) as datemonth     
          from tb_clear
          where year(fd_clear_qlrq) = '$year' and fd_clear_status =2 
          and fd_clear_qlfs = '出售' " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(datemonth);        
	  $arr_gdzcbmcjsr[$listdate] += $db->f(lirun);
  }
}

//固定资产报废和盘赢
$query = "select fd_clear_zcmoney  , month(fd_clear_qlrq) as datemonth  , fd_clear_qlfs
          from tb_clear
          where year(fd_clear_qlrq) = '$year' and fd_clear_status =2 
          and (fd_clear_qlfs = '盘亏' or fd_clear_qlfs = '报废' )"  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate  = $db->f(datemonth);        
		$qlfs      = $db->f(fd_clear_qlfs);        
		if($qlfs=="盘亏"){
			$arr_gdzcpgzc[$listdate] += $db->f(fd_clear_zcmoney);
		}else{
	    $arr_gdzcbfzc[$listdate] += $db->f(fd_clear_zcmoney);
	  }
  }
}

//入库补库存支出
$query = "select (fd_iocy_aftermoney + fd_iocy_formermoney) as allgain , month(fd_iocy_date) as datemonth 
	        from tb_intocompensatory
          where year(fd_iocy_date) = '$year' 
          $str_sqlwhere " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(datemonth);        
	  $arr_rkbkczc[$listdate] += $db->f(allgain);
  }
}

//固定资产折旧
$query = "select fd_onezj_zjprice , month(fd_onezj_zjdate) as datemonth   
          from tb_onezj
          where year(fd_onezj_zjdate) = '$year' 
          and fd_onezj_state =2 ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(datemonth);        
	  $arr_gdzczj[$listdate] += $db->f(fd_onezj_zjprice);
  }
}


//进货单
$query = "select sum(fd_stock_allmoney) as allmoney  , month(fd_stock_date) as datemonth    
          from tb_stock  
          where year(fd_stock_date) = '$year'  and fd_stock_state =2 
          and fd_stock_iskickback = 0 group by fd_stock_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney    = $db->f(allmoney);
		$listdate   = $db->f(datemonth);        
		
		$arr_jhmoney[$listdate] += $cgmoney;
  }
}

//退货单
$query = "select sum(fd_stockback_allmoney) as allmoney  , month(fd_stockback_date) as datemonth     
          from tb_stockback  
          where year(fd_stockback_date) = '$year' and fd_stockback_state =2 
          and fd_stockback_iskickback = 0  group by fd_stockback_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney   = $db->f(allmoney);
		$listdate  = $db->f(datemonth);        
		
		$arr_jhmoney[$listdate] -= $cgmoney;
  }
}*/
$query = "select sum(fd_stock_allmoney) as allmoney  , month(fd_stock_date) as datemonth    
          from tb_paycardstock  
          where year(fd_stock_date) = '$year'  and fd_stock_state =9 
          group by fd_stock_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney    = $db->f(allmoney);
		$listdate   = $db->f(datemonth);        
		
		$arr_jhmoney[$listdate] += $cgmoney;
  }
}

$query = "select sum(fd_stock_allmoney) as allmoney  , month(fd_stock_date) as datemonth    
          from tb_paycardstockback  
          where year(fd_stock_date) = '$year'  and fd_stock_state =9 
          group by fd_stock_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney    = $db->f(allmoney);
		$listdate   = $db->f(datemonth);        
		
		$arr_jhmoney[$listdate] -= $cgmoney;
  }
}

$arr_tmpbegindate = explode("/",$begindate);
$begindatecount = mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2],$arr_tmpbegindate[0]);

$arr_tmpenddate = explode("/",$enddate);
$enddatecount = mktime("0","0","0",$arr_tmpenddate[1],$arr_tmpenddate[2],$arr_tmpenddate[0]);

$daycount = date("z",$enddatecount-$begindatecount);  //两个日期相隔的天数。

for ($i = 0; $i <= $daycount; $i++)
{
	$listdate = date("Y-m-d" , mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2]+$i,$arr_tmpbegindate[0]));
	$arr_flagdate[$listdate] = 1;
}

$count=0;
for($i=1;$i<=12;$i++){		
  $listdate = $year."年".$i."月";
  $tmpdate = $i;
  $begindatetime = date( "Y/m/d" , mktime(0,0,0,$i,1,$year));
  $enddatetime = date( "Y/m/d" , mktime(0,0,0,$i+1,0,$year));
  
    $xsmoney    = $arr_xsmoney[$tmpdate];     //销售收入
    $xscost     = $arr_xscost[$tmpdate];      //销售成本
   // $qtsrmoney  = $arr_qtsrmoney[$tmpdate];   //其他收入单
   // $fymoney    = $arr_fymoney[$tmpdate];     //费用单
		
	//	$cusnum       = $arr_wlcusnum[$tmpdate];

    $jhmoney    = $arr_jhmoney[$tmpdate];     //进货金额
    
    //$pdlirun    = $arr_pdlirun[$tmpdate];        //盘点利润
    //$ysktz      = $arr_wbysktz[$tmpdate];        //外部应收款调帐
    //$zhtzsr     = $arr_zhtzsr[$tmpdate];         //帐户调帐收入
    //$zhtzgs     = $arr_zhtzgs[$tmpdate];         //帐户调帐亏损
    //$zzcx       = $arr_zzcx[$tmpdate];           //组装拆卸利润
    //$jhthlirun  = $arr_jhthlirun[$tmpdate];      //进货退货利润
    //$sptjlirun  = $arr_sptjlirun[$tmpdate];      //商品调价利润
    //$gdzcewsr   = $arr_gdzcewsr[$tmpdate];       //固定资产额外收入
    //$gdzcbmcjsr = $arr_gdzcbmcjsr[$tmpdate];     //固定资产变卖差价收入
    //$gdzcpgzc   = $arr_gdzcpgzc[$tmpdate];       //固定资产盘亏
    //$gdzcbfzc   = $arr_gdzcbfzc[$tmpdate];       //固定资产报废
    //$rkbkczc    = $arr_rkbkczc[$tmpdate];        //入库补库存支出
    //$gdzczj     = $arr_gdzczj[$tmpdate];         //固定资产折旧
    
   // $gz     = $arr_gz[$tmpdate]; 
      
    $count++ ;
    
    $lirun = $xsmoney - $xscost;
    
    $shengli = $lirun;
    $shengli = $shengli;
    
    //$allgz +=$gz;
    $allxscost +=$xscost;
    $allxsmoney +=$xsmoney;
    $alllirun   +=$lirun;
    
    //$allqtsrmoney +=$qtsrmoney;
    //$allfymoney   +=$fymoney;
    $allshengli   +=$shengli;
   
		//$allcusnum       += $cusnum;
    
    //$allpdlirun      += $pdlirun;        //盘点利润
    //$allysktz        += $ysktz;          //应收款调帐收入
    //$allzhtzsr       += $zhtzsr;         //帐户调帐收入
    //$allzhtzgs       += $zhtzgs;         //帐户调帐亏损
    //$allzzcx         += $zzcx;           //组装拆卸利润
    //$alljhthlirun    += $jhthlirun;      //进货退货利润
    //$allsptjlirun    += $sptjlirun;      //商品调价利润
    //$allgdzcewsr     += $gdzcewsr;       //固定资产额外收入
    //$allgdzcbmcjsr   += $gdzcbmcjsr;     //固定资产变卖差价收入
    //$allgdzcpgzc     += $gdzcpgzc;       //固定资产盘亏
   // $allgdzcbfzc     += $gdzcbfzc;       //固定资产报废
    //$allrkbkczc      += $rkbkczc;        //入库补库存支出
    //$allgdzczj       += $gdzczj;         //固定资产折旧
    
    $alljhmoney      += $jhmoney;
    
    if($xsmoney!=0){
    	$lrpercent = round($lirun/$xsmoney*100,2);
    	$xspercent = 100;
    }else{
      $lrpercent = 0;
    	$xspercent = 0;
    }
    
    $lrpercent = number_format($lrpercent, 2, ".", "");
    
    
    /*if(!empty($gz)){
    	$gz = number_format($gz, 2, ".", "");
    }*/
    
    if(!empty($xsmoney)){
    	$xsmoney = number_format($xsmoney, 2, ".", "");
    }
    
    if(!empty($xscost)){
    	$xscost = number_format($xscost, 2, ".", "");
    }
  	
  	if(!empty($lirun)){
    	$lirun = number_format($lirun, 2, ".", "");
    }
   
    /*if(!empty($qtsrmoney)){
    	$qtsrmoney = number_format($qtsrmoney, 2, ".", "");
    }
    if(!empty($fymoney)){
    	$fymoney = number_format($fymoney, 2, ".", "");
    }*/
    if(!empty($shengli)){
    	$shengli = number_format($shengli, 2, ".", "");
    }

    if(!empty($jhmoney)){
    	$jhmoney = number_format($jhmoney, 2, ".", "");
    }
    
    /*if(!empty($pdlirun)){
    	$pdlirun = number_format($pdlirun, 2, ".", "");
    }
    if(!empty($ysktz)){
    	$ysktz = number_format($ysktz, 2, ".", "");
    }
    if(!empty($zhtzsr)){
    	$zhtzsr = number_format($zhtzsr, 2, ".", "");
    }
    if(!empty($zhtzgs)){
    	$zhtzgs = number_format($zhtzgs, 2, ".", "");
    }
    if(!empty($zzcx)){
    	$zzcx = number_format($zzcx, 2, ".", "");
    }
    if(!empty($jhthlirun)){
    	$jhthlirun = number_format($jhthlirun, 2, ".", "");
    }
    if(!empty($sptjlirun)){
    	$sptjlirun = number_format($sptjlirun, 2, ".", "");
    }

    if(!empty($pdlirun)){
    	$pdlirun = number_format($pdlirun, 2, ".", "");
    }

    if(!empty($gdzcewsr)){
    	$gdzcewsr = number_format($gdzcewsr, 2, ".", "");
    }
    if(!empty($gdzcbmcjsr)){
    	$gdzcbmcjsr = number_format($gdzcbmcjsr, 2, ".", "");
    }
    if(!empty($gdzcpgzc)){
    	$gdzcpgzc = number_format($gdzcpgzc, 2, ".", "");
    }
    if(!empty($gdzcbfzc)){
    	$gdzcbfzc = number_format($gdzcbfzc, 2, ".", "");
    }

    if(!empty($rkbkczc)){
    	$rkbkczc = number_format($rkbkczc, 2, ".", "");
    }
    if(!empty($gdzczj)){
    	$gdzczj = number_format($gdzczj, 2, ".", "");
    }*/
    
  	if ($bgcolor=="#FFFFFF") {
        $bgcolor="#F1F4F9";
    }else{
        $bgcolor="#FFFFFF";
    }
    
  	$t->set_var(array("listdate"	   => $listdate     ,
  			              "xscost"	     => $xscost       ,
  			              "bgcolor"      => $bgcolor      ,
  			              "xsmoney"      => $xsmoney      ,
  			             // "lrpercent"    => $lrpercent    ,
  			             // "qtsrmoney"    => $qtsrmoney    ,
  			             // "fymoney"      => $fymoney      ,
  			              "shengli"	     => $shengli      ,
  			              //"pdlirun"      => $pdlirun      ,   //盘点利润
                      //"ysktz"        => $ysktz        ,   //应收款调帐收入
                      //"zhtzsr"       => $zhtzsr       ,   //帐户调帐收入
                      //"zhtzgs"       => $zhtzgs       ,   //帐户调帐亏损
                      //"zzcx"         => $zzcx         ,   //组装拆卸利润
                      //"jhthlirun"    => $jhthlirun    ,   //进货退货利润
                      //"sptjlirun"    => $sptjlirun    ,   //商品调价利润
                      //"gdzcewsr"     => $gdzcewsr     ,   //固定资产额外收入
                      //"gdzcbmcjsr"   => $gdzcbmcjsr   ,   //固定资产变卖差价收入
                      //"gdzcpgzc"     => $gdzcpgzc     ,   //固定资产盘亏
                      //"gdzcbfzc"     => $gdzcbfzc     ,   //固定资产报废
                      //"nbyssr"       => $nbyssr       ,   //内部运输收入
                      //"nbysfy"       => $nbysfy       ,   //内部运输费用
                      //"rkbkczc"      => $rkbkczc      ,   //入库补库存支出
                      //"gdzczj"       => $gdzczj       ,   //固定资产折旧
  			              "tdcount"      => $count        ,
  			              "jhmoney"      => $jhmoney      ,
                      "begindatetime"=> $begindatetime,
                      "enddatetime"  => $enddatetime  ,
  			            //  "cusnum"       => $cusnum       ,
  			              //"gz"           => $gz           ,			              
  			              "lirun"        => $lirun        
  			              
  			   ));
  	$t->parse("bxbks", "BXBK", true);
}

if($count==0){
	$t->set_var(array("organname"	   => "" ,
			              "xscost"	     => "" ,
			              "bgcolor"      => "" ,
			              "xsmoney"      => "" ,
			            //  "qtsrmoney"    => "" ,
			              //"fymoney"      => "" ,
			              //"lrpercent"    => 0  ,
			              "shengli"      => "" ,
			             // "pdlirun"      => "" ,   //盘点利润
                    //"ysktz"        => "" ,   //应收款调帐收入
                    //"zhtzsr"       => "" ,   //帐户调帐收入
                    //"zhtzgs"       => "" ,   //帐户调帐亏损
                    //"zzcx"         => "" ,   //组装拆卸利润
                    //"jhthlirun"    => "" ,   //进货退货利润
                    //"sptjlirun"    => "" ,   //商品调价利润
                    //"gdzcewsr"     => "" ,   //固定资产额外收入
                    //"gdzcbmcjsr"   => "" ,   //固定资产变卖差价收入
                    //"gdzcpgzc"     => "" ,   //固定资产盘亏
                    //"gdzcbfzc"     => "" ,   //固定资产报废
                    //"rkbkczc"      => "" ,   //入库补库存支出
                    //"gdzczj"       => "" ,   //固定资产折旧
			              "tdcount"      => "" ,
			          //    "organid"      => "" ,
			              "jhmoney"      => "" ,
			              "lirun"        => "" ,
                    "begindatetime"=> "" ,
                    "enddatetime"  => "" ,
			            //  "gz"           => "" ,
			              "checkbox"     => ""
			   ));
	$t->parse("bxbks", "BXBK", true);
}

if($allxsmoney!=0){
	$avgpercent = round($alllirun/$allxsmoney*100,2);
}else{
  $avgpercent = 0;
}


//$allgz           = number_format($allgz, 2, ".", "");
$allxsmoney      = number_format($allxsmoney, 2, ".", "");
$allxscost       = number_format($allxscost, 2, ".", "");
$alllirun        = number_format($alllirun, 2, ".", "");
//$avgpercent      = number_format($avgpercent, 2, ".", "");
//$allqtsrmoney    = number_format($allqtsrmoney, 2, ".", "");
//$allfymoney      = number_format($allfymoney, 2, ".", "");
$allshengli      = number_format($allshengli, 2, ".", "");

$alljhmoney      = number_format($alljhmoney, 2, ".", "");
//$allquanyimoney  = number_format($allquanyimoney, 2, ".", "");

//$allpdlirun      = number_format($allpdlirun    , 2 , "." , "" );    //盘点利润
//$allysktz        = number_format($allysktz      , 2 , "." , "" );    //应收款调帐收入
//$allzhtzsr       = number_format($allzhtzsr     , 2 , "." , "" );    //帐户调帐收入
//$allzhtzgs       = number_format($allzhtzgs     , 2 , "." , "" );    //帐户调帐亏损
//$allzzcx         = number_format($allzzcx       , 2 , "." , "" );    //组装拆卸利润
//$alljhthlirun    = number_format($alljhthlirun  , 2 , "." , "" );    //进货退货利润
//$allsptjlirun    = number_format($allsptjlirun  , 2 , "." , "" );    //商品调价利润
//$allgdzcewsr     = number_format($allgdzcewsr   , 2 , "." , "" );    //固定资产额外收入
//$allgdzcbmcjsr   = number_format($allgdzcbmcjsr , 2 , "." , "" );    //固定资产变卖差价收入
//$allgdzcpgzc     = number_format($allgdzcpgzc   , 2 , "." , "" );    //固定资产盘亏
//$allgdzcbfzc     = number_format($allgdzcbfzc   , 2 , "." , "" );    //固定资产报废
//$allrkbkczc      = number_format($allrkbkczc    , 2 , "." , "" );    //入库补库存支出
//$allgdzczj       = number_format($allgdzczj     , 2 , "." , "" );    //固定资产折旧

$t->set_var("condition"      , $condition      );
$t->set_var("allxscost"      , $allxscost      );
$t->set_var("allxsmoney"     , $allxsmoney     );
$t->set_var("alllirun"       , $alllirun       );
//$t->set_var("avgpercent"     , $avgpercent     );
//$t->set_var("allqtsrmoney"   , $allqtsrmoney   );
//$t->set_var("allfymoney"     , $allfymoney     );
$t->set_var("allshengli"     , $allshengli     );
//$t->set_var("allgz"          , $allgz          );

//$t->set_var("allcusnum"      , $allcusnum      );
//$t->set_var("allquanyimoney" , $allquanyimoney );
$t->set_var("alljhmoney"     , $alljhmoney     );

//$t->set_var("allpdlirun"      , $allpdlirun   );    //盘点利润
//$t->set_var("allysktz"        , $allysktz     );    //应收款调帐收入
//$t->set_var("allzhtzsr"       , $allzhtzsr    );    //帐户调帐收入
//$t->set_var("allzhtzgs"       , $allzhtzgs    );    //帐户调帐亏损
//$t->set_var("allzzcx"         , $allzzcx      );    //组装拆卸利润
//$t->set_var("alljhthlirun"    , $alljhthlirun );    //进货退货利润
//$t->set_var("allsptjlirun"    , $allsptjlirun );    //商品调价利润
//$t->set_var("allgdzcewsr"     , $allgdzcewsr  );    //固定资产额外收入
//$t->set_var("allgdzcbmcjsr"   , $allgdzcbmcjsr);    //固定资产变卖差价收入
//$t->set_var("allgdzcpgzc"     , $allgdzcpgzc  );    //固定资产盘亏
//$t->set_var("allgdzcbfzc"     , $allgdzcbfzc  );    //固定资产报废
//$t->set_var("allrkbkczc"      , $allrkbkczc   );    //入库补库存支出
//$t->set_var("allgdzczj"       , $allgdzczj    );    //固定资产折旧

$t->set_var("condition",$condition);


$t->set_var("year",$year);
$t->set_var("month",$month);

$t->set_var("gotourl",$gotourl);

$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("skin",$loginskin);
$t->pparse("out", "yeartable_view");    # 最后输出页面

?>