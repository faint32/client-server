<?
$thismenucode = "10n009";
require ("../include/common.inc.php");



$db  = new DB_test;
$db1 = new DB_test;

$gourl = "transactionmonitoring.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
 

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("checkpaycard","checkpaycard.html"); 





$query = "select * from tb_paycard 
left join tb_author on fd_paycard_authorid=fd_author_id
where fd_paycard_id='$paycardid' ";
$db->query($query);
if($db->nf())
{	
	$db->next_record();
	
	   $vpaycardkey     = $db->f(fd_paycard_key);            //id号  
	   $authortruename  = $db->f(fd_author_truename);
     
}

$search_wherequery=getsearchdata($search_begintime,$search_endtime,$search_time,'fd_agpm_paydate');//代付金额

$ccglist_wherequery=getsearchdata($search_begintime,$search_endtime,$search_time,'fd_ccglist_paydate');//信用卡还款

$arr_ccglist=getpaycardrecords($ccglist_wherequery,'tb_creditcardglist','ccglist');
   
$tfmglist_wherequery=getsearchdata($search_begintime,$search_endtime,$search_time,'fd_tfmglist_paydate');//转账汇款
$arr_tfmglist=getpaycardrecords($tfmglist_wherequery,'tb_transfermoneyglist','tfmglist');

$repmglist_wherequery=getsearchdata($search_begintime,$search_endtime,$search_time,'fd_repmglist_paydate');//贷款还款
$arr_repmglist=getpaycardrecords($repmglist_wherequery,'tb_repaymoneyglist','repmglist');

$couponsale_wherequery=getsearchdata($search_begintime,$search_endtime,$search_time,'fd_couponsale_datetime');//抵用券
$arr_couponsale=getpaycardrecords($couponsale_wherequery,'tb_couponsale','couponsale');

//显示列表
$t->set_block("checkpaycard", "prolist"  , "prolists"); 
$query = "select * ,sum(fd_agpm_paymoney) as  paymoney, count(fd_agpm_fucardno) as paycardnum ,max(fd_agpm_paydate) as paydate  from tb_agentpaymoneylist 
          where fd_agpm_paycardid = '$paycardid'  $search_wherequery   group by fd_agpm_fucardno ORDER BY `fd_agpm_paydate`  ASC "; 
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		
	     $vid            = $db->f(fd_agpm_id);
	     $fucardno       = $db->f(fd_agpm_fucardno);
		 $fucardman      = $db->f(fd_agpm_fucardman);
		 $paydate        = $db->f(paydate);
		 $bkntno         = $db->f(fd_agpm_bkntno);
		$paymoney        = $db->f(paymoney);
		$paycardnum        = $db->f(paycardnum);
		$ccglist=$arr_ccglist[$fucardno];
		$tfmglist=$arr_tfmglist[$fucardno];
		$repmglist=$arr_repmglist[$fucardno];
		$couponsale=$arr_couponsale[$fucardno];
		  
		$allpaycardnum+=$paycardnum;
		$allccglist  +=$ccglist;
		$alltfmglist +=$tfmglist;
		$allrepmglist+=$repmglist;
		$allcouponsale+=$couponsale;
		  $count++;

			
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
		   
		   
		   
		   $t->set_var(array("trid"     => $trid          ,
                         "imgid"        => $imgid         ,
                         "bgcolor"      => $bgcolor       ,
                         "rowcount"     => $count         ,
						 "fucardno"     => $fucardno         ,
						 "fucardman"    => $fucardman         ,
						 "paydate"      => $paydate         ,
						 "bkntno"       => $bkntno         ,
						 "paymoney"     => $paymoney         ,
						 "paycardnum"   => $paycardnum         ,
						 "ccglist"      => $ccglist         ,
						 "tfmglist"     => $tfmglist         ,
						 "repmglist"    => $repmglist         ,
						 "couponsale"   => $couponsale         ,							  
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
} else{
	$t->parse("prolists", "", true);
}


$t->set_var("allpaycardnum",$allpaycardnum);
$t->set_var("allccglist",$allccglist);
$t->set_var("alltfmglist",$alltfmglist);
$t->set_var("allrepmglist",$allrepmglist);
$t->set_var("allcouponsale",$allcouponsale);

$t->set_var("vpaycardkey",$vpaycardkey);

$t->set_var("authortruename",$authortruename);
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->set_var("gotourl",$gotourl);

$t->pparse("out", "checkpaycard");    # 最后输出页面


function getsearchdata($search_begintime,$search_endtime,$search_time,$tblname)//获取时间查询
{
	if(!empty($search_begintime) and !empty($search_endtime))
{
	$search_wherequery .="and $tblname between  '$search_begintime' and '$search_endtime' ";
}

if(!empty($search_time))
{
	switch($search_time)
	{
		case 'now':
		$search_wherequery .="and  to_days($tblname) = to_days(now())";
		break;
		case 'week':
		$search_wherequery .="and  DATE_SUB(CURDATE(), INTERVAL 7 DAY)<= date($tblname)";
		break;
		case 'onemonth':
		$search_wherequery .="and DATE_SUB(CURDATE(), INTERVAL  1 MONTH)<=date($tblname)";
		break;
		case 'threemonth':
		$search_wherequery .="and DATE_SUB(CURDATE(), INTERVAL  3 MONTH)<=date($tblname)";
		break;
		
	}
	
}

if(empty($search_begintime) and empty($search_endtime) and empty($search_time))
{
	
	$search_wherequery .="and date_format($tblname,'%Y-%m')=date_format(now(),'%Y-%m')";
}

return $search_wherequery;
}


function getpaycardrecords($wherequery,$tblname,$parename)//获取刷卡器记录
{
	$db  = new DB_test;
	if($parename !="couponsale")
	{
		$select=', sum(fd_'.$parename.'_paymoney) as paymoney';
		$groupby='fd_'.$parename.'_fucardno';
	}else{
		$groupby='fd_'.$parename.'_creditcardno';
		$select=',count('.$groupby.') as num ';
	}
	$query='select * '.$select.'  from '.$tblname.' where 1 and  fd_'.$parename.'_state =9  '.$wherequery.' group by '.$groupby.' ' ;
	$db->query($query);
	if($db->nf())
	{	
		while($db->next_record())
		{
			if($parename !="couponsale")
			{
				$fucardno=$db->f('fd_'.$parename.'_fucardno');
				$arr_data[$fucardno] = $db->f(paymoney); 
			}else{
				$creditcardno=$db->f('fd_'.$parename.'_creditcardno');
				$arr_data[$creditcardno] =$db->f(num);
			}	
		}
	}
	return $arr_data;
}
?>

