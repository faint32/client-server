<?php
$thismenucode = "2k302";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");
require ("../third_api/readshopname.php");

$getFileimg = new AutogetFile;
$db = new DB_test;
$gourl = "tb_author_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","authorsetmeal.html"); 

//费率套餐

$arr_payfee=getauthorset('tb_payfeeset','payfset');

//额度套餐
$arr_scardmoney=getauthorset('tb_slotcardmoneyset','scdmset');

$query="select * from tb_author where fd_author_id='$listid' ";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$truename        = $db->f(fd_author_truename);
	$slotpayfsetid   = $db->f(fd_author_slotpayfsetid);
	$slotscdmsetid   = $db->f(fd_author_slotscdmsetid);
	$bkcardpayfsetid = $db->f(fd_author_bkcardpayfsetid);
	$bkcardscdmsetid = $db->f(fd_author_bkcardscdmsetid);
}

$arr_payfeename=array("fd_payfset_name"=>"套餐名","fd_auindustry_name"=>"商户所属行业","fd_arrive_name"=>"到帐周期",
						"fd_payfset_defeedirct"=>"费率扣款方向","fd_payfset_mode"=>"费率类型",
						"fd_payfset_fee"=>"费率百分比(%)","fd_payfset_minfee"=>"最低费率","fd_payfset_maxfee"=>"封顶费率",
						"fd_payfset_fixfee"=>"固定手续费"
						);

$arr_scardmoneyname=array("fd_scdmset_name"=>"套餐名","fd_auindustry_name"=>"商户所属行业",
						"fd_scdmset_neverymoney"=>"日额度（元）","fd_scdmset_nallmoney"=>"月额度（元）",
						"fd_scdmset_everymoney"=>"每日每笔限额","fd_scdmset_everycounts"=>"每日刷卡次数","fd_scdmset_severymoney"=>"审批日额度（元）",
						"fd_scdmset_sallmoney"=>"审批月额度（元）"
						);

$arr_authoruse=array(	"fd_scdmset_neverymoney"=>"今日已用额度（元）","fd_scdmset_nallmoney"=>"今月已用额度（元）",
						"fd_scdmset_everymoney"=>"","fd_scdmset_everycounts"=>"每日已刷卡次数","fd_scdmset_severymoney"=>"今日已审批额度（元）",
						"fd_scdmset_sallmoney"=>"今月审批额度（元）"
						);

//商户信用卡费率套餐--";
$showslotpayfsetid = getpayfeedata($arr_payfeename,$arr_payfee,$slotpayfsetid);

//商户信用卡额度套餐--";
$showslotscdmsetid = getscardmoneydata($arr_scardmoneyname,$arr_scardmoney,$slotscdmsetid,$listid,$arr_authoruse);

//商户借记卡费率套餐--";
$showbkcardpayfsetid = getpayfeedata($arr_payfeename,$arr_payfee,$bkcardpayfsetid);


//商户借记卡额度套餐--"
$showbkcardscdmsetid = getscardmoneydata($arr_scardmoneyname,$arr_scardmoney,$bkcardscdmsetid,$listid,$arr_authoruse);


$t->set_var("showslotpayfsetid"  , $showslotpayfsetid  );
$t->set_var("showslotscdmsetid"  , $showslotscdmsetid  );
$t->set_var("showbkcardpayfsetid", $showbkcardpayfsetid);
$t->set_var("showbkcardscdmsetid", $showbkcardscdmsetid);
$t->set_var("truename"           , $truename           );//listid
$t->set_var("gotourl"            , $gotourl            );//转用的地址
$t->set_var("error"              , $error              );
// 判断权限
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

function getauthorset($tabname,$filename)//获取商家套餐
{
	$db = new DB_test;
	$query = "select * from $tabname left join tb_arrive on fd_arrive_id=fd_".$filename."_arriveid left join  tb_authorindustry on fd_auindustry_id=fd_".$filename."_auindustryid";
	$db->query($query);

	$arr_data=$db->getFiledData();

	foreach($arr_data as  $value)
	{
		foreach($value as $key=> $v)
		{
			$id=$value['fd_'.$filename.'_id'];
			
			if($key!="fd_".$filename."_id")
			{
				$arr_newdata[$id][$key]=$v;
			}
		}
	}
	return $arr_newdata;
}

function getpayfeedata($arr_payfeename,$arr_payfee,$payfsetid)//显示费率套餐
{
	$count=1;
	$showcontent .="<tr>";
foreach($arr_payfeename as $key=>$value)
{
	if($arr_payfee[$payfsetid]['fd_payfset_mode']=="fix"){
		if($key=="fd_payfset_fee" || $key=="fd_payfset_minfee" || $key=="fd_payfset_maxfee" )
		{
			continue ;
		}
	}else{
		
		 if($key=="fd_payfset_fixfee" )
		{
			continue;
		}
	}
	$payfeevalue=$arr_payfee[$payfsetid][$key];
	if($payfeevalue=="fix"){$payfeevalue="固定费率";}
	if($payfeevalue=="per"){$payfeevalue="浮动费率";}
	if($payfeevalue=="f"){$payfeevalue="付款方";}
	if($payfeevalue=="s"){$payfeevalue="收款方";}
	$showcontent .="<th>$value</th><td><input class='input disabled' type='text' value='$payfeevalue'></td>";
	if($count % 2==0)
	{
		$showcontent .="</tr><tr>";
	}
	$count++;
}
$showcontent .="</tr>";

return $showcontent;
}

function getscardmoneydata($arr_scardmoneyname,$arr_scardmoney,$scdmsetid,$authorid,$arr_authoruse)//显示额度套餐
{
	$date_authorues = getauthoruse($authorid,$scope,$scdmsetid);
	$newdate=date("Y-m-d",time());
	$newdate1=date("Y-m",time());
	$count=1;
	$showcontent .="<tr>";
foreach($arr_scardmoneyname as $key=>$value)
{
	$scardmoneyvalue=$arr_scardmoney[$scdmsetid][$key];
	$scope=$arr_scardmoney[$scdmsetid]['fd_scdmset_scope'];	
	/* if($count=="9")
	{
		$colspan="colspan=3";
	} */

	if($key=="fd_scdmset_nallmoney")//今月已用额度
	{
		$color="style=color:red;cursor:pointer;";
		$funcion="onclick=checkpaycounts('$authorid',this,'$scope','$scdmsetid','use@one')";
	}elseif($key=="fd_scdmset_sallmoney")//今月审批额度
	{
		$color="style=color:red;cursor:pointer;";
		$funcion="onclick=checkpaycounts('$authorid',this,'$scope','$scdmsetid','use@all')";
	}elseif($key=="fd_scdmset_neverymoney")//今日已用额度
	{
		$color="style=color:red;cursor:pointer;";
		$funcion="onclick=checkpaycounts('$authorid',this,'$scope','$scdmsetid','sp@one')";
	}elseif($key=="fd_scdmset_severymoney")//今日已审批额度
	{
		$color="style=color:red;cursor:pointer;";
		$funcion="onclick=checkpaycounts('$authorid',this,'$scope','$scdmsetid','sp@all')";
	}else{
		$color="";
		$funcion="";
	}

	$showcontent .="<th>$value</th><td $colspan ><input class='input disabled' type='text'  value='$scardmoneyvalue'></td>";

	if($key!="fd_scdmset_name" and  $key !='fd_auindustry_name')
	{
		if($key=="fd_scdmset_sallmoney" or $key=="fd_scdmset_severymoney")
		{
			 $showvalue=$date_authorues[$newdate1][$scope][$key];
		}else {
			
			 $showvalue=$date_authorues[$newdate][$scope][$key];
		}
		$showcontent .="<th>$arr_authoruse[$key]</th><td $colspan ><input class='input disabled' type='text' $color $funcion   value='$showvalue'></td>";
		$showcontent .="</tr><tr>";
	}else{
		if($count % 2==0)
		{
			$showcontent .="</tr><tr>";
		}
	}
	$count++;
}
	$showcontent .="</tr>";


	return $showcontent;
}


function getauthoruse($authorid,$scope,$scdmsetid)
{
	$db = new DB_test;
	$newdate=date("Y-m",time());

	$query="select count(*) as paycount,sum(fd_agpm_paymoney) as paymoney,fd_agpm_paydate as paydate, date_format(fd_agpm_paydate,'%Y-%m') as newdate ,fd_paycard_scope as scope from tb_agentpaymoneylist left join tb_paycard on fd_agpm_paycardid=fd_paycard_id where fd_agpm_authorid='$authorid' and date_format(fd_agpm_paydate,'%Y-%m')='$newdate'  and fd_agpm_payrq='00' group by fd_agpm_paydate,scope";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$paydate=$db->f(paydate);
			$newdate=$db->f(newdate);
			$scope=$db->f(scope);
			$date_authorues[$paydate][$scope]['fd_scdmset_nallmoney']=$db->f(paymoney)+0;//今月已用额度
			$date_authorues[$paydate][$scope]['']='';
			$date_authorues[$paydate][$scope]['fd_scdmset_everycounts'] =$db->f(paycount)+0;//每日已刷卡次数
			$date_authorues[$newdate][$scope]['fd_scdmset_sallmoney']+=$db->f(paymoney);//今月审批额度
		}
	}

	$query="SELECT date_format(fd_pmreq_reqdatetime,'%Y-%m-%d') AS reqdatetime ,date_format(fd_pmreq_reqdatetime,'%Y-%m') AS datetime ,SUM(fd_pmreq_reqmoney) AS repmoney ,fd_scdmset_scope AS scope FROM tb_slotcardmoneyreq LEFT JOIN tb_slotcardmoneyset ON fd_scdmset_id = fd_pmreq_paymsetid WHERE fd_pmreq_authorid='$authorid' AND fd_pmreq_paymsetid='$scdmsetid' AND date_format(fd_pmreq_reqdatetime,'%Y-%m') = '$newdate' AND fd_pmreq_state <>'0' GROUP BY reqdatetime,scope ";
	$db->query($query);

	if( $db->nf() )
	{
		while( $db->next_record() )
		{
			$reqdatetime=$db->f(reqdatetime);
			$scope=$db->f(scope);
			$datetime=$db->f(datetime);
			$date_authorues[$reqdatetime][$scope]['fd_scdmset_neverymoney']=$db->f(repmoney)+0;//今日已用额度
			$date_authorues[$datetime][$scope]['fd_scdmset_severymoney']+=$db->f(repmoney);//今日已审批额度
		}
	}
	return $date_authorues;
}

?>

