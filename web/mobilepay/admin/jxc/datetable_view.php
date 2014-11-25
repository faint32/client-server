<?php
$thismenucode = "2k221";
require ("../include/common.inc.php");

$db = new DB_test ;
$dbtj = new DB_test ;
$gourl = "datetable_query.php";
$gotourl = $gourl . $tempurl;
$t = new Template('.', "keep");
$t->set_file("datetable_view","datetable_view.html");
$t->set_block("datetable_view", "BXBK", "bxbks");

$begindate = date( "Y/m/d" ,mktime(0,0,0,$month,$day,$year));
$enddate = date( "Y/m/d" ,mktime(0,0,0,$month,$day,$year));

//���۵�
$query = "select sum(fd_selt_allmoney) as allmoney , sum(fd_selt_allcost) as allcost , fd_selt_date from tb_salelist where fd_selt_date >= '$begindate' and fd_selt_date <= '$enddate' and fd_selt_state =9 group by fd_selt_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$xsmoney    = $db->f(allmoney);
		$listdate   = $db->f(fd_selt_date);        //����
    $xscost     = $db->f(allcost);
		
		$arr_xsmoney[$listdate] += $xsmoney;
		$arr_xscost[$listdate] += $xscost;
  }
}



//�����˻���
$query = "select sum(fd_selt_allmoney) as allmoney , sum(fd_selt_allcost) as allcost, fd_selt_date from tb_salelistback where fd_selt_date >= '$begindate' and fd_selt_date <= '$enddate' and fd_selt_state =9 group by fd_selt_date" ;

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate    = $db->f(fd_selt_date);             //����

		$arr_xsmoney[$listdate] -= $db->f(allmoney);
		$arr_xscost[$listdate] -= $db->f(allcost);
  }
}


//�������뵥
/*$query = "select fd_incomelist_allmoney , fd_incomelist_date 
         from tb_incomelist 
         where fd_incomelist_date >= '$begindate' and fd_incomelist_date <= '$enddate' and fd_incomelist_state =2 
         and fd_incomelist_kickback = 0  " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$qtsrmoney   = $db->f(fd_incomelist_allmoney);
		$listdate    = $db->f(fd_incomelist_date);        //����
		
		$arr_qtsrmoney[$listdate] += $qtsrmoney;
		
  }
}*/

//���õ�
/*$query = "select fd_payoutlist_allmoney , fd_payoutlist_date 
         from tb_payoutlist 
         where fd_payoutlist_date >= '$begindate' and fd_payoutlist_date <= '$enddate' and fd_payoutlist_state =2 
         and fd_payoutlist_kickback = 0  ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$fymoney   = $db->f(fd_payoutlist_allmoney);
		$listdate  = $db->f(fd_payoutlist_date);        //����
		$arr_fymoney[$listdate] += $fymoney;
  }
}*/

//�̵�����
/*$query = "select fd_checknote_profit , fd_checknote_date from tb_checknote  
         left join tb_storage on fd_storage_id = fd_checknote_storageid
         where fd_checknote_date >= '$begindate' and fd_checknote_date <= '$enddate' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$pdlirun   = $db->f(fd_checknote_profit);
		$listdate  = $db->f(fd_checknote_date);        //����
		$arr_pdlirun[$listdate] += $pdlirun;
  }
}*/


//�ⲿӦ�տ����
/*$query = "select (fd_ysyfat_nowmoney-fd_ysyfat_oldmoney) as lirun , fd_ysyfat_date
	        from tb_ysyfadjust
          where fd_ysyfat_date >= '$begindate' and fd_ysyfat_date <= '$enddate' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$wbysktz   = $db->f(lirun);
		$listdate  = $db->f(fd_ysyfat_date);        //����
		$arr_wbysktz[$listdate] += $wbysktz;
  }
}*/

//�ʻ���������
/*$query = "select fd_atat_money , fd_atat_date from tb_accountadjust
          left join tb_account on fd_account_id = fd_atat_accountid
          where fd_atat_date >= '$begindate' and fd_atat_date <= '$enddate'  ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$zhtz     = $db->f(fd_atat_money);
		$listdate  = $db->f(fd_atat_date);        //����
		if($zhtz>0){
		  $arr_zhtzsr[$listdate] += $zhtz;
	  }else{
	    $arr_zhtzgs[$listdate] += -$zhtz;
	  }
  }
}*/

//��װ��ж����
/*$query = "select sum(fd_loaddown_lirun) as allmoney  , fd_loaddown_date 
          from tb_loaddown  
          where fd_loaddown_date >= '$begindate' and fd_loaddown_date <= '$enddate' and fd_loaddown_staus =1 
          and fd_loaddown_backstaus = 0 group by fd_loaddown_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$zzcx     = $db->f(allmoney);
		$listdate = $db->f(fd_loaddown_date);        //����
		$arr_zzcx[$listdate] += $zzcx;
  }
}*/

//�ⲿ�����˻�����
/*$query = "select (fd_stockback_allmoney - fd_stockback_allcost) as lirun , fd_stockback_date 
         from tb_stockback  
         where fd_stockback_date >= '$begindate' and fd_stockback_date <= '$enddate' and fd_stockback_state =1 
         and fd_stockback_iskickback = 0 "  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$jhthlirun = $db->f(lirun);
		$listdate  = $db->f(fd_stockback_date);        //����
		
		$arr_jhthlirun[$listdate] += $jhthlirun;
  }
}*/

//��Ʒ��������
/*$query = "select fd_ccp_lirun , fd_ccp_date 
         from tb_commoditychangeprice     
         where fd_ccp_date >= '$begindate' and fd_ccp_date <= '$enddate' 
         and fd_ccp_staus = 1" ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$sptjlirun = $db->f(fd_ccp_lirun);
		$listdate  = $db->f(fd_ccp_date);        //����
		$arr_sptjlirun[$listdate] += $sptjlirun;
  }
}*/


//�̶��ʲ���������
/*$query = "select fd_bfes_money , fd_bfes_date  
          from tb_buyfixedassets
          where fd_bfes_date >= '$begindate' and fd_bfes_date <= '$enddate' and fd_bfes_state =2 
          and fd_bfes_accountid = '0' "  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(fd_bfes_date);        //����
	  $arr_gdzcewsr[$listdate] += $db->f(fd_bfes_money);
  }
}*/

//�̶��ʲ������������
/*$query = "select (fd_clear_skmoney-fd_clear_zcmoney) as lirun , fd_clear_qlrq  
          from tb_clear
          where fd_clear_qlrq >= '$begindate' and fd_clear_qlrq <= '$enddate' and fd_clear_status =2 
          and fd_clear_qlfs = '����' " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(fd_clear_qlrq);        //����
	  $arr_gdzcbmcjsr[$listdate] += $db->f(lirun);
  }
}*/

//�̶��ʲ����Ϻ���Ӯ
/*$query = "select fd_clear_zcmoney  , fd_clear_qlrq , fd_clear_qlfs
          from tb_clear
          where fd_clear_qlrq >= '$begindate' and fd_clear_qlrq <= '$enddate' and fd_clear_status =2 
          and (fd_clear_qlfs = '�̿�' or fd_clear_qlfs = '����' )"  ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate  = $db->f(fd_clear_qlrq);        //����
		$qlfs      = $db->f(fd_clear_qlfs);        //����
		if($qlfs=="�̿�"){
			$arr_gdzcpgzc[$listdate] += $db->f(fd_clear_zcmoney);
		}else{
	    $arr_gdzcbfzc[$listdate] += $db->f(fd_clear_zcmoney);
	  }
  }
}*/

//��ⲹ���֧��
/*$query = "select (fd_iocy_aftermoney + fd_iocy_formermoney) as allgain , fd_iocy_date
	        from tb_intocompensatory
          where fd_iocy_date >= '$begindate' and fd_iocy_date <= '$enddate' 
          $str_sqlwhere " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(fd_iocy_date);        //����
	  $arr_rkbkczc[$listdate] += $db->f(allgain);
  }
}*/

//�̶��ʲ��۾�
/*$query = "select fd_onezj_zjprice , fd_onezj_zjdate  
          from tb_onezj
          where fd_onezj_zjdate >= '$begindate' and fd_onezj_zjdate <= '$enddate' 
          and fd_onezj_state =2 ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$listdate   = $db->f(fd_onezj_zjdate);        //����
	  $arr_gdzczj[$listdate] += $db->f(fd_onezj_zjprice);
  }
}*/


//������
$query = "select sum(fd_stock_allmoney) as allmoney  , fd_stock_date 
          from tb_paycardstock  
          where fd_stock_date >= '$begindate' and fd_stock_date <= '$enddate' and fd_stock_state =9 
          group by fd_stock_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney    = $db->f(allmoney);
		$listdate   = $db->f(fd_stock_date);        //����
		
		$arr_jhmoney[$listdate] += $cgmoney;
  }
}

//�˻���
$query = "select sum(fd_stock_allmoney) as allmoney  , fd_stock_date 
          from tb_paycardstockback  
          where fd_stock_date >= '$begindate' and fd_stock_date <= '$enddate' and fd_stock_state =9 
           group by fd_stock_date";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cgmoney   = $db->f(allmoney);
		$listdate  = $db->f(fd_stock_date);        //����
		
		$arr_jhmoney[$listdate] -= $cgmoney;
  }
}

$arr_tmpbegindate = explode("/",$begindate);
$begindatecount = mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2],$arr_tmpbegindate[0]);

$arr_tmpenddate = explode("/",$enddate);
$enddatecount = mktime("0","0","0",$arr_tmpenddate[1],$arr_tmpenddate[2],$arr_tmpenddate[0]);
$daycount = date("z",$enddatecount-$begindatecount);  //�������������������
for($i=0;$i<=$daycount;$i++){
	$listdate = date("Y-m-d",mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2]+$i,$arr_tmpbegindate[0]));
	$arr_flagdate[$listdate] = 1;
}

$count=0;
while(list($key , $value)=@each($arr_flagdate)){	
	
	//$arr_tmpdate = explode("-",$key);
  //$listdate = $arr_tmpdate[0]."��".$arr_tmpdate[1]."��".$arr_tmpdate[2]."��";
  $listdate = $key;
  $tmpdate    = $key;
  
    $xsmoney    = $arr_xsmoney[$tmpdate];     //��������
    $xscost     = $arr_xscost[$tmpdate];      //���۳ɱ�
    //$qtsrmoney  = $arr_qtsrmoney[$tmpdate];   //�������뵥
    //$fymoney    = $arr_fymoney[$tmpdate];     //���õ�
		
		//$cusnum       = $arr_wlcusnum[$tmpdate];

    $jhmoney    = $arr_jhmoney[$tmpdate];     //�������
    
    //$pdlirun    = $arr_pdlirun[$tmpdate];        //�̵�����
    //$ysktz      = $arr_wbysktz[$tmpdate];        //�ⲿӦ�տ����
    //$zhtzsr     = $arr_zhtzsr[$tmpdate];         //�ʻ���������
    //$zhtzgs     = $arr_zhtzgs[$tmpdate];         //�ʻ����ʿ���
    //$zzcx       = $arr_zzcx[$tmpdate];           //��װ��ж����
    //$jhthlirun  = $arr_jhthlirun[$tmpdate];      //�����˻�����
    //$sptjlirun  = $arr_sptjlirun[$tmpdate];      //��Ʒ��������
    //$gdzcewsr   = $arr_gdzcewsr[$tmpdate];       //�̶��ʲ���������
    //$gdzcbmcjsr = $arr_gdzcbmcjsr[$tmpdate];     //�̶��ʲ������������
    //$gdzcpgzc   = $arr_gdzcpgzc[$tmpdate];       //�̶��ʲ��̿�
    //$gdzcbfzc   = $arr_gdzcbfzc[$tmpdate];       //�̶��ʲ�����
   // $rkbkczc    = $arr_rkbkczc[$tmpdate];        //��ⲹ���֧��
    //$gdzczj     = $arr_gdzczj[$tmpdate];         //�̶��ʲ��۾�
    
   // $gz         = $arr_gz[$tmpdate];//����
    
    $count++ ;
    
    $lirun = $xsmoney - $xscost;
    
    //$shengli = $lirun+$qtsrmoney+$pdlirun+$ysktz+$zhtzsr+$zzcx+$jhthlirun+$sptjlirun+$gdzcewsr+$gdzcbmcjsr;
	//$shengli = $shengli-$fymoney-$zhtzgs-$gdzcpgzc-$gdzcbfzc-$rkbkczc-$gdzczj-$gz;
	$shengli = $lirun;
    $shengli = $shengli;
    
    $allxscost +=$xscost;
    $allxsmoney +=$xsmoney;
    $alllirun   +=$lirun;
    //$allgz   +=$gz;
    
    //$allqtsrmoney +=$qtsrmoney;
    //$allfymoney   +=$fymoney;
    $allshengli   +=$shengli;
   
		//$allcusnum       += $cusnum;
    
   // $allpdlirun      += $pdlirun;        //�̵�����
    //$allysktz        += $ysktz;          //Ӧ�տ��������
   // $allzhtzsr       += $zhtzsr;         //�ʻ���������
   // $allzhtzgs       += $zhtzgs;         //�ʻ����ʿ���
    //$allzzcx         += $zzcx;           //��װ��ж����
   // $alljhthlirun    += $jhthlirun;      //�����˻�����
   // $allsptjlirun    += $sptjlirun;      //��Ʒ��������
    //$allgdzcewsr     += $gdzcewsr;       //�̶��ʲ���������
    //$allgdzcbmcjsr   += $gdzcbmcjsr;     //�̶��ʲ������������
    //$allgdzcpgzc     += $gdzcpgzc;       //�̶��ʲ��̿�
    //$allgdzcbfzc     += $gdzcbfzc;       //�̶��ʲ�����
  //  $allrkbkczc      += $rkbkczc;        //��ⲹ���֧��
    //$allgdzczj       += $gdzczj;         //�̶��ʲ��۾�
    
    $alljhmoney      += $jhmoney;
    
    if($xsmoney!=0){
    	$lrpercent = round($lirun/$xsmoney*100,2);
    	$xspercent = 100;
    }else{
      $lrpercent = 0;
    	$xspercent = 0;
    }
    
    $lrpercent = number_format($lrpercent, 2, ".", "");
    
   /* if(!empty($gz)){
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
    
   /* if(!empty($pdlirun)){
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
    }*/

     /*if(!empty($gdzcewsr)){
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
    }*/

    /*if(!empty($rkbkczc)){
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
  			              //"lrpercent"    => $lrpercent    ,
  			             // "qtsrmoney"    => $qtsrmoney    ,
  			            //  "fymoney"      => $fymoney      ,
  			              "shengli"	     => $shengli      ,
  			            //  "pdlirun"      => $pdlirun      ,   //�̵�����
                      //"ysktz"        => $ysktz        ,   //Ӧ�տ��������
                     // "zhtzsr"       => $zhtzsr       ,   //�ʻ���������
                     // "zhtzgs"       => $zhtzgs       ,   //�ʻ����ʿ��� 
                                                          //��װ��ж����  "zzcx"         => $zzcx         , 
                    //  "jhthlirun"    => $jhthlirun    ,   //�����˻�����
                    //  "sptjlirun"    => $sptjlirun    ,   //��Ʒ��������
															//�̶��ʲ���������  "gdzcewsr"     => $gdzcewsr     , 
															//�̶��ʲ������������   "gdzcbmcjsr"   => $gdzcbmcjsr   , 
															//�̶��ʲ��̿�  "gdzcpgzc"     => $gdzcpgzc     ,
															//�̶��ʲ����� "gdzcbfzc"     => $gdzcbfzc     ,
                     // "nbyssr"       => $nbyssr       ,   //�ڲ���������
                     // "nbysfy"       => $nbysfy       ,   //�ڲ��������
                    //  "rkbkczc"      => $rkbkczc      ,   //��ⲹ���֧��
                       									  //�̶��ʲ��۾�  "gdzczj"       => $gdzczj       , 
  			             "tdcount"      => $count        ,
  			              "jhmoney"      => $jhmoney      ,
  			            //  "cusnum"       => $cusnum       ,
  			            //  "gz"           => $gz           ,
  			              "lirun"        => $lirun        
  			              
  			   ));
  	$t->parse("bxbks", "BXBK", true);
}

if($count==0){
	$t->set_var(array("organname"	   => "" ,
			              "xscost"	     => "" ,
			              "bgcolor"      => "" ,
			              "xsmoney"      => "" ,
			             // "qtsrmoney"    => "" ,
			            //  "fymoney"      => "" ,
			            //  "lrpercent"    => 0  ,
			              "shengli"      => "" ,
			           //   "pdlirun"      => "" ,   //�̵�����
                 //   "ysktz"        => "" ,   //Ӧ�տ��������
                 //   "zhtzsr"       => "" ,   //�ʻ���������
                 //   "zhtzgs"       => "" ,   //�ʻ����ʿ���
                                             //��װ��ж����   "zzcx"         => "" , 
                //    "jhthlirun"    => "" ,   //�����˻�����
               //     "sptjlirun"    => "" ,   //��Ʒ��������
                                             //�̶��ʲ��������� "gdzcewsr"     => "" , 
                                             //�̶��ʲ������������  "gdzcbmcjsr"   => "" , 
                                             //�̶��ʲ��̿� "gdzcpgzc"     => "" ,
                                           //�̶��ʲ�����  "gdzcbfzc"     => "" ,
                 //   "rkbkczc"      => "" ,   //��ⲹ���֧��
                                             //�̶��ʲ��۾� "gdzczj"       => "" ,
			             "tdcount"      => "" ,
			         //     "organid"      => "" ,
			              "jhmoney"      => "" ,
			              "lirun"        => "" ,
			          //    "gz"           => "" ,
			              "checkbox"     => ""
			   ));
	$t->parse("bxbks", "BXBK", true);
}

if($allxsmoney!=0){
	$avgpercent = round($alllirun/$allxsmoney*100,2);
}else{
  $avgpercent = 0;
}

$allxsmoney      = number_format($allxsmoney, 2, ".", "");
$allxscost       = number_format($allxscost, 2, ".", "");
$alllirun        = number_format($alllirun, 2, ".", "");
//$avgpercent      = number_format($avgpercent, 2, ".", "");
//$allqtsrmoney    = number_format($allqtsrmoney, 2, ".", "");
//$allfymoney      = number_format($allfymoney, 2, ".", "");
$allshengli      = number_format($allshengli, 2, ".", "");
//$allgz           = number_format($allgz, 2, ".", "");



$alljhmoney      = number_format($alljhmoney, 2, ".", "");
//$allquanyimoney  = number_format($allquanyimoney, 2, ".", "");

//$allpdlirun      = number_format($allpdlirun    , 2 , "." , "" );    //�̵�����
//$allysktz        = number_format($allysktz      , 2 , "." , "" );    //Ӧ�տ��������
//$allzhtzsr       = number_format($allzhtzsr     , 2 , "." , "" );    //�ʻ���������
//$allzhtzgs       = number_format($allzhtzgs     , 2 , "." , "" );    //�ʻ����ʿ���
//$allzzcx         = number_format($allzzcx       , 2 , "." , "" );    //��װ��ж����
//$alljhthlirun    = number_format($alljhthlirun  , 2 , "." , "" );    //�����˻�����
//$allsptjlirun    = number_format($allsptjlirun  , 2 , "." , "" );    //��Ʒ��������
//$allgdzcewsr     = number_format($allgdzcewsr   , 2 , "." , "" );    //�̶��ʲ���������
//$allgdzcbmcjsr   = number_format($allgdzcbmcjsr , 2 , "." , "" );    //�̶��ʲ������������
//$allgdzcpgzc     = number_format($allgdzcpgzc   , 2 , "." , "" );    //�̶��ʲ��̿�
//$allgdzcbfzc     = number_format($allgdzcbfzc   , 2 , "." , "" );    //�̶��ʲ�����
//$allrkbkczc      = number_format($allrkbkczc    , 2 , "." , "" );    //��ⲹ���֧��
//$allgdzczj       = number_format($allgdzczj     , 2 , "." , "" );    //�̶��ʲ��۾�

//$t->set_var("allgz"          , $allgz          );
//$t->set_var("condition"      , $condition      );
$t->set_var("allxscost"      , $allxscost      );
$t->set_var("allxsmoney"     , $allxsmoney     );
$t->set_var("alllirun"       , $alllirun       );
//$t->set_var("avgpercent"     , $avgpercent     );
//$t->set_var("allqtsrmoney"   , $allqtsrmoney   );
//$t->set_var("allfymoney"     , $allfymoney     );
$t->set_var("allshengli"     , $allshengli     );

//$t->set_var("allcusnum"      , $allcusnum      );
//$t->set_var("allquanyimoney" , $allquanyimoney );
$t->set_var("alljhmoney"     , $alljhmoney     );

//$t->set_var("allpdlirun"      , $allpdlirun   );    //�̵�����
//$t->set_var("allysktz"        , $allysktz     );    //Ӧ�տ��������
//$t->set_var("allzhtzsr"       , $allzhtzsr    );    //�ʻ���������
//$t->set_var("allzhtzgs"       , $allzhtzgs    );    //�ʻ����ʿ���
//$t->set_var("allzzcx"         , $allzzcx      );    //��װ��ж����
//$t->set_var("alljhthlirun"    , $alljhthlirun );    //�����˻�����
//$t->set_var("allsptjlirun"    , $allsptjlirun );    //��Ʒ��������
//$t->set_var("allgdzcewsr"     , $allgdzcewsr  );    //�̶��ʲ���������
//$t->set_var("allgdzcbmcjsr"   , $allgdzcbmcjsr);    //�̶��ʲ������������
//$t->set_var("allgdzcpgzc"     , $allgdzcpgzc  );    //�̶��ʲ��̿�
//$t->set_var("allgdzcbfzc"     , $allgdzcbfzc  );    //�̶��ʲ�����
//$t->set_var("allrkbkczc"      , $allrkbkczc   );    //��ⲹ���֧��
//$t->set_var("allgdzczj"       , $allgdzczj    );    //�̶��ʲ��۾�

$t->set_var("condition",$condition);


$t->set_var("begindate",$begindate);
$t->set_var("enddate",$enddate);
$t->set_var("gotourl",$gotourl);
$t->set_var("count",$count);
$t->set_var("error",$error);
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "datetable_view");    # ������ҳ��

?>