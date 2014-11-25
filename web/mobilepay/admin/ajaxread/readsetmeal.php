<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


if($type=="scdmsetid")
{	
	$query = "select * from tb_slotcardmoneyset
			 left join  tb_authorindustry  on fd_scdmset_auindustryid=fd_auindustry_id";
	$db->query($query);
	$totoalcount=$db->nf()+0;
	$query = "$query limit $iDisplayStart,$iDisplayLength  ";
	$db->query($query);

	if($db->nf())
	{	
		while($db->next_record())
		{

		   $vid             = $db->f(fd_scdmset_id);            
		   $scdmset_name    = g2u($db->f(fd_scdmset_name));           
		   $auindustry_name = g2u($db->f(fd_auindustry_name));             
		   $nallmoney       = $db->f(fd_scdmset_nallmoney);
		   $sallmoney       = $db->f(fd_scdmset_sallmoney);
		   $scope           = $db->f(fd_scdmset_scope);
		   $everymoney      = $db->f(fd_scdmset_everymoney);
		   $everycounts     = $db->f(fd_scdmset_everycounts);	   
		   $neverymoney     = $db->f(fd_scdmset_neverymoney);	  
		   $severymoney     = $db->f(fd_scdmset_severymoney);	  
	
			if($scope=="creditcard"){$scope="信用卡";}
			if($scope=="bankcard"){$scope="储蓄卡";}
		   $arr_authornum=getauthormeal($type);
		    $authornum='<a href="#" onclick="checkauthor('.$vid.',\'scdmsetid\',this)">'.$arr_authornum[$vid].'</a>';
		   $arr_list[] = array("DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$scdmset_name,
							$auindustry_name,
							$authornum,
							$nallmoney,
							$sallmoney,
							g2u($scope),
							$everymoney,
							$everycounts,
							$neverymoney,
							$severymoney
							);
		 }
	}
	else
	{     
		 $vmember  = "暂无数据";
		 $arr_list[] = array(
							"DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$scdmset_name,
							$auindustry_name,
							$authornum,
							$nallmoney,
							$sallmoney,
							$scope,
							$everymoney,
							$everycounts,
							$neverymoney,
							$severymoney
							);
	}
}else{
	$query = "select * from tb_payfeeset
	 left join  tb_authorindustry  on fd_payfset_auindustryid=fd_auindustry_id
	  left join  tb_arrive  on fd_payfset_arriveid=fd_arrive_id";
	$db->query($query);
	$totoalcount=$db->nf()+0;
	$query = "$query limit $iDisplayStart,$iDisplayLength  ";
	$db->query($query);

	if($db->nf())
	{	
		while($db->next_record())
		{

		   $vid             = $db->f(fd_payfset_id);            
		   $scdmset_name    = g2u($db->f(fd_payfset_name));           
		   $auindustry_name = g2u($db->f(fd_auindustry_name));   
		   $scope           = $db->f(fd_payfset_scope);		   
		   $arrivename       = g2u($db->f(fd_arrive_name));
		   $defeedirct       = $db->f(fd_payfset_defeedirct);
		   $mode      = $db->f(fd_payfset_mode);
		   $fee     = $db->f(fd_payfset_fee);	   
		   $minfee     = $db->f(fd_payfset_minfee);	  
		   $maxfee     = $db->f(fd_payfset_maxfee);	  
		   $fixfee     = $db->f(fd_payfset_fixfee);	  
		
		   $arr_authornum=getauthormeal($type);
		   $authornum='<a href="#" onclick="checkauthor('.$vid.',\'payfsetid\',this)">'.$arr_authornum[$vid].'</a>';
		   if($scope=="creditcard"){$scope="信用卡";}
			if($scope=="bankcard"){$scope="储蓄卡";}
			
			if($defeedirct=="f"){$defeedirct="付款方";}else{$defeedirct="收款方";}
			
			if($mode=="fix"){$mode="固定费率";}else{$mode="浮动费率";}
		   
		   $arr_list[] = array("DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$scdmset_name,
							$auindustry_name,
							$authornum,
							g2u($scope),
							$arrivename,
							g2u($defeedirct),
							g2u($mode),
							$fixfee,
							$fee,
							$minfee,
							$maxfee
							);
		 }
	}
	else
	{     
		 $vmember  = "暂无数据";
		   $arr_list[] = array("DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$scdmset_name,
							$auindustry_name,
							$authornum,
							g2u($scope),
							$arrivename,
							g2u($defeedirct),
							g2u($mode),
							$fixfee,
							$fee,
							$minfee,
							$maxfee
							);
	}

}      
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
function getauthormeal($type)
{
	$db=new db_test;
	$query='select fd_author_'.$type.',count(fd_author_'.$type.') as authornum from tb_author group by  fd_author_'.$type.'';
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$id=$db->f('fd_author_'.$type.'');
			$returnvalue[$id]=$db->f(authornum);
		}
	}
	return $returnvalue;
}
?>