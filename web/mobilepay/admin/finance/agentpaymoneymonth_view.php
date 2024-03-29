<?
//$thismenucode = "2k222";
require ("../include/common.inc.php");

$db = new DB_test ;
$dbtj = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("agentpaymoneymonth_view","agentpaymoneymonth_view.html");
$t->set_block("agentpaymoneymonth_view", "BXBK", "bxbks");

$begindate = date( "Y/m/d" ,mktime(0,0,0,$month,1,$year));
$enddate = date( "Y/m/d" ,mktime(0,0,0,$month+1,0,$year));

//已核对出款
$query = "select  fd_agpm_id               as agpm_id,
                  sum(fd_agpm_payfee)      as payfee,
                  sum(fd_agpm_agentmoney)  as money,
				  sum(fd_agpm_sdcragentfeemoney) as agentfeemoney,
				  date(fd_agpm_agentdate)        as paydate
		  from tb_agentpaymoneylist 
		  left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  left join tb_paymoneylistdetail on fd_pymyltdetail_agpmid = fd_agpm_id 
		  left join tb_paymoneylist on fd_pymyltdetail_paymoneylistid = fd_pymylt_id
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = '1' and fd_pymylt_state ='9' and date(fd_agpm_agentdate) >= '$begindate' and date(fd_agpm_agentdate) <= '$enddate' group by fd_agpm_agentdate";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$yskpayfee    = $db->f(payfee);	//手续费
		$yskmoney    = $db->f(money); 
		$agentfeemoney    = $db->f(agentfeemoney); 
		$listdate   = $db->f(paydate);  //日期	
		$arr_yskmoney[$listdate] += $yskmoney;
		$arr_yskpayfee[$listdate] += $yskpayfee;
		$arr_agentfeemoney[$listdate] += $agentfeemoney;


  }
}

//已出款
/*$query = "select  fd_agpm_id               as agpm_id,
                  fd_agpm_no               as agpm_no,
				  fd_agpm_paymoney         as paymoney ,
                  fd_agpm_payfee           as payfee,
                  sum(fd_agpm_money)            as money,
				  fd_agpm_paydate          as paydate,
				  fd_pymylt_state          as pymyltstate,
				  fd_pymylt_date           as pymyltdate,
				  fd_pymylt_fkdate         as pymyltfkdate
		  from tb_agentpaymoneylist   
          left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  left join tb_paymoneylistdetail on fd_pymyltdetail_agpmid = fd_agpm_id 
		  left join tb_paymoneylist on fd_pymyltdetail_paymoneylistid = fd_pymylt_id
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = 1 and fd_pymylt_fkdate >= '$begindate' and fd_pymylt_fkdate <= '$enddate' and  fd_pymylt_state ='9'
           group by fd_pymylt_fkdate";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$yckmoney    = $db->f(money);
		$listdate   = $db->f(pymyltfkdate);        //日期	
		$arr_yckmoney[$listdate] += $yckmoney;

  }
}*/

//等待审批
$query = "select  fd_agpm_id               as agpm_id,
                  sum(fd_agpm_payfee)      as payfee,
                  sum(fd_agpm_agentmoney)       as money,
				  sum(fd_agpm_sdcragentfeemoney) as ddagentfeemoney,
				  date(fd_agpm_agentdate)          as paydate
		  from tb_agentpaymoneylist
		  left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  join tb_paymoneylistdetail on fd_pymyltdetail_agpmid = fd_agpm_id 
		  join tb_paymoneylist on fd_pymyltdetail_paymoneylistid = fd_pymylt_id 
          where fd_agpm_payrq = '00' and fd_pymylt_state ='3' and fd_agpm_isagentpay = '1' and date(fd_agpm_agentdate) >= '$begindate' and date(fd_agpm_agentdate) <= '$enddate' group by fd_agpm_agentdate";

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$ddspmoney    = $db->f(money);
		$ddagentfeemoney    = $db->f(ddagentfeemoney);
		$ddsppayfee    = $db->f(payfee);	//未审核出款手续
		$listdate   = $db->f(paydate);        //日期	
		$arr_ddspmoney[$listdate] += $ddspmoney;
		$arr_ddsppayfee[$listdate] += $ddsppayfee;
		$arr_ddagentfeemoney[$listdate] += $ddagentfeemoney;

  }
}
$arr_tmpbegindate = explode("/",$begindate);
$begindatecount = mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2],$arr_tmpbegindate[0]);

$arr_tmpenddate = explode("/",$enddate);
$enddatecount = mktime("0","0","0",$arr_tmpenddate[1],$arr_tmpenddate[2],$arr_tmpenddate[0]);
$daycount = date("z",$enddatecount-$begindatecount);  //两个日期相隔的天数。
for($i=0;$i<=$daycount;$i++){
	$listdate = date("Y-m-d",mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2]+$i,$arr_tmpbegindate[0]));
	$arr_flagdate[$listdate] = 1;
}

$count=0;
while(list($key , $value)=@each($arr_flagdate)){	
	
	//$arr_tmpdate = explode("-",$key);
  //$listdate = $arr_tmpdate[0]."年".$arr_tmpdate[1]."月".$arr_tmpdate[2]."日";
  $listdate = $key;
  $tmpdate    = $key;
  
    $yskmoney    = $arr_yskmoney[$tmpdate];     //已审核出款
	$yskpayfee    = $arr_yskpayfee[$tmpdate];     //已审核出款手续费
	$agentfeemoney    = $arr_agentfeemoney[$tmpdate];     //已审核出款成本
	
	$ddsppayfee    = $arr_ddsppayfee[$tmpdate];     //未审核出款手续
	$ddspmoney    = $arr_ddspmoney[$tmpdate];     //未审核出款
    $ddagentfeemoney    = $arr_ddagentfeemoney[$tmpdate];     //已审核出款成本

    
    
    $count++ ;
    
    $quanmoney  = $yskmoney + $ddspmoney;		//代付款出款总额
	$quanpayfee  = $yskpayfee + $ddsppayfee;		//代付款出款总手续费
	$quanagentfeemoney  = $agentfeemoney + $ddagentfeemoney;	//代付款出款总成本
	 //已审核出款
    $allyskmoney +=$yskmoney;
	$allyskpayfee +=$yskpayfee;
	$allagentfeemoney +=$agentfeemoney;
	//未审核出款
	$allddagentfeemoney +=$ddagentfeemoney;
	$allddsdpayfee +=$ddsppayfee;
	$allddspmoney +=$ddspmoney;
	//代付款出款总额
    $allquanmoney +=$quanmoney;
	$allquanpayfee +=$quanpayfee;
	$allquanagentfeemoney +=$quanagentfeemoney;
	

    if(!empty($yskmoney)){
    	$yskmoney = number_format($yskmoney, 2, ".", "");
    }
	if(!empty($yskpayfee)){
    	$yskpayfee = number_format($yskpayfee, 2, ".", "");
    }
    
    if(!empty($ddsppayfee)){
    	$ddsppayfee = number_format($ddsppayfee, 2, ".", "");
    }
  	
  	if(!empty($ddspmoney)){
    	$ddspmoney = number_format($ddspmoney, 2, ".", "");
    }
	
  	if(!empty($agentfeemoney)){
    	$agentfeemoney = number_format($agentfeemoney, 2, ".", "");
    }
    
    if(!empty($ddagentfeemoney)){
    	$ddagentfeemoney = number_format($ddagentfeemoney, 2, ".", "");
    }
   
    if(!empty($quanmoney)){
    	$quanmoney = number_format($quanmoney, 2, ".", "");
    }else{
		$quanmoney = "";
	}
	if(!empty($quanagentfeemoney)){
    	$quanagentfeemoney = number_format($quanagentfeemoney, 2, ".", "");
    }else{
		$quanagentfeemoney = "";
	}
	if(!empty($quanpayfee)){
    	$quanpayfee = number_format($quanpayfee, 2, ".", "");
    }else{
		$quanpayfee = "";
	}
    
	if ($bgcolor=="#FFFFFF") {
        $bgcolor="#F1F4F9";
    }else{
        $bgcolor="#FFFFFF";
    }
	
  	$t->set_var(array(	"listdate"	   => $listdate     ,
						  "agentfeemoney"	=>	$agentfeemoney	,
  			              "yskmoney"	=> $yskmoney       ,
						  "yskpayfee"	=>	$yskpayfee	,
						  
  			              "ddsppayfee"	=> $ddsppayfee      ,
						  "ddspmoney"	=> $ddspmoney      , 
						  "ddagentfeemoney"	=>	$ddagentfeemoney	, 
						  
  			              "quanmoney"   => $quanmoney	,
						  "quanpayfee"	=>	$quanpayfee	,
						  "quanagentfeemoney"	=>	$quanagentfeemoney	, 
						  "bgcolor"      => $bgcolor      ,
						    "tdcount"      => $count        	              
  			   ));
  	$t->parse("bxbks", "BXBK", true);
}

if($count==0){
	$t->set_var(array("yskmoney"	   => "" ,
			              "yckmoney"	=> "" ,
						  "yskpayfee"	=>	"",
						  "quanmoney"   => "",
						  "quanpayfee"	=>"",
						  "quanagentfeemoney"	=>"", 
						  "ddsppayfee"	     => "",
			              "ddspmoney"      => "" ,
						  "agentfeemoney"	=>	""	,
						  "ddagentfeemoney"	=>	""	, 
						   "bgcolor"      => "" ,
						   "tdcount"      => "" 
			   ));
	$t->parse("bxbks", "BXBK", true);
}

$allyskmoney      = number_format($allyskmoney, 2, ".", "");
$allyskpayfee       = number_format($allyskpayfee, 2, ".", "");
$allddsdpayfee        = number_format($allddsdpayfee, 2, ".", "");
$allddspmoney        = number_format($allddspmoney, 2, ".", "");
$allquanmoney      = number_format($allquanmoney, 2, ".", "");
$allddagentfeemoney      = number_format($allddagentfeemoney, 2, ".", "");
$allagentfeemoney      = number_format($allagentfeemoney, 2, ".", "");
$allquanpayfee      = number_format($allquanpayfee, 2, ".", "");
$allquanagentfeemoney      = number_format($allquanagentfeemoney, 2, ".", "");

$t->set_var("month",$month);
$t->set_var("year",$year);
$t->set_var("condition"      , $condition      );
$t->set_var("allyskmoney"      , $allyskmoney      );
$t->set_var("allyskpayfee"     , $allyskpayfee     );
$t->set_var("allddsdpayfee"     , $allddsdpayfee     );
$t->set_var("allddspmoney"       , $allddspmoney       );
$t->set_var("allquanmoney"       , $allquanmoney       );
$t->set_var("allagentfeemoney"       , $allagentfeemoney       );
$t->set_var("allddagentfeemoney"       , $allddagentfeemoney       );
$t->set_var("allquanpayfee"       , $allquanpayfee       );
$t->set_var("allquanagentfeemoney"       , $allquanagentfeemoney       );
$t->set_var("condition",$condition);
$t->set_var("begindate",$begindate);
$t->set_var("enddate",$enddate);
$t->set_var("count",$count);
$t->set_var("error",$error);
$t->set_var("skin",$loginskin);

$t->pparse("out", "agentpaymoneymonth_view");    # 最后输出页面

?>