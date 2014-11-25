<?
require ("../FCKeditor/fckeditor.php");

//生成选择菜单的函数
function makeselect($arritem,$hadselected,$arry){
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}


function czadminlog($czpeopleid,$czpeole,$sql,$cztype,$navname,$navqx,$loglistid)//操作人id,操作人,操作内容,操作方式,菜单栏,菜单栏权限,商家ID
{
	$db=new DB_test;

		$query='insert into web_adminlog 
		(fd_log_czpeopleid,fd_log_czpeole,fd_log_sql,fd_log_cztype,fd_log_navname,fd_log_navqx,fd_log_cztime,fd_log_listid) 
		 values
		 ("'.$czpeopleid.'","'.$czpeole.'","'.$sql.'","'.$cztype.'","'.$navname.'","'.$navqx.'",now(),"'.$loglistid.'")';
		 $db->query($query);
	 
}

//获取仓库的信息
function getStorageInfo($shopWhere = '',$sdcrWhere = ''){
    $cacheName = md5($shopWhere.$sdcrWhere);
	static $arrStorage = array();
	
	if(empty($arrStorage[$cacheName])){	  
		$dbshop = new DB_shop;
		$db = new DB_test;
		$query = "select * from tb_storage where 1 $shopWhere";
		$dbshop->query($query);
		if($dbshop->nf()){
		  while($dbshop->next_record()){
			$storage_id = $dbshop->f('fd_storage_id');
			$storage_name = $dbshop->f('fd_storage_name');
			$arrStorage[$cacheName][$storage_id]['storage_name'] = $storage_name; 
		  }
		}
		
		
		$query = "select * from tb_sendcenter where 1 $sdcrWhere";
		$db->query($query);
		if($db->nf()){
		  while($db->next_record()){
			$storage_id = $db->f('fd_sdcr_id');
			$storage_name = $db->f('fd_sdcr_name');
			$arrStorage[$cacheName][$storage_id]['storage_name'] = $storage_name; 
		  }
		}
	}
		
	return $arrStorage[$cacheName];
}

function creatTableSql($tablename,$arrAttr){

  $temData = "ALTER TABLE `".$tablename."` ";
  $temDataList = "";
    for($i=0;$i<count($arrAttr);$i++){
	
	  if(empty($arrAttr[$i]['filed_name'])||empty($arrAttr[$i]['filed_type'])){
	    break;
	  }
	  
      if($arrAttr[$i]['filed_hasedit'] == '1'){
	    if(!empty($arrAttr[$i]['filed_old'])){
		  $temDataList .= ",CHANGE COLUMN `".$arrAttr[$i]['filed_old']."` `".$arrAttr[$i]['filed_name']."` ".$arrAttr[$i]['filed_type']." COMMENT '".$arrAttr[$i]['filed_remark']."'";
		}else{
		  $temDataList .= ",ADD COLUMN `".$arrAttr[$i]['filed_name']."` ".$arrAttr[$i]['filed_type']." COMMENT '".$arrAttr[$i]['filed_remark']."'";
		}
	    
	  }

	  
	}
	
  if(empty($temDataList)){
    return '';
  }	
  
  $temDataList = substr($temDataList,1);
  $temData .= $temDataList;
  $temData .= "";
  
  return $temData;
}

//获取交易金额区间
function  getmoneyqj($type,$moneyqj)
{
$db=new DB_test;
$query="select * from tb_moneyqj where fd_moneyqj_type='$type'";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$arr_moneyqjid[]=$db->f(fd_moneyqj_id);
		$start=$db->f(fd_moneyqj_start);
		$end=$db->f(fd_moneyqj_end);
		$arr_moneyqjname[]="大于".$start."小于等于".$end;
	}
}else{
	$arr_moneyqjname[]="请先设置金额区间";
}

$moneyqj = makeselect($arr_moneyqjname,$moneyqj,$arr_moneyqjid); 
return $moneyqj;
}



//获取入库,销售刷卡器的批次,供应商名
function stocksalepaycard($tblname,$parename)
{	
	$db  = new DB_test;

	
	if($parename=="skdetail")
	{
		$select="fd_".$parename."_batches as batches ,fd_stock_suppname,";
		$join=" lfet join  tb_paycardstock on fd_stock_id=fd_skdetail_stockid";
		
		
		
	}
	$query="select $select fd_".$parename."_paycardid as paycardid  from $tblname $join";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$paycardkey = $db->f(paycardid);
			
			if($strpaycardkey)
			{
				$strpaycardkey .=","."'$paycardkey'";
			}else{
				$strpaycardkey="'$paycardkey'";
			}
			if($parename=="skdetail")
			{
				$batches= $db->f(batches);
				$arr_suppname=$db->f(fd_stock_suppname);				
						$arr_paycardid=explode("-",$paycardkey);
							$startpaycardid=$arr_paycardid[0];
							$endpaycardid=$arr_paycardid[1];
							$arr_startint = preg_replace('/[^0-9]/',"",$startpaycardid);
							$arr_endint = preg_replace('/[^0-9]/',"",$endpaycardid);
							$arr_cart = preg_replace('/[0-9]/',"",$startpaycardid);
						for($arr_startint;$arr_startint<=$arr_endint;$arr_startint++) 
						{
							$key=$arr_cart.$arr_startint;
							$arr_newbatches[$key]=$batches;
							$arr_newsuppname[$key]=$arr_suppname;
						}		
			}
			
		}
	}
		$returvalue[]=$strpaycardkey;
		$returvalue[]=$arr_newbatches;
		$returvalue[]=$arr_newsuppname;		
	return $returvalue;
}
?>