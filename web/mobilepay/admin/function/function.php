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

function unescape($str){
        preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/",$str,$matches); //prt($matches);
        $ar = &$matches[0];
        $c = "";
        foreach($ar as $val){
                if (substr($val,0,1)!="%"){ //如果是字母数字+-_.的ascii码
                        $c .=$val;
                }elseif(substr($val,1,1)!="u"){ //如果是非字母数字+-_.的ascii码
                        $x = hexdec(substr($val,1,2));
                        $c .=chr($x);
                }else{ //如果是大于0xFF的码
                        $val = intval(substr($val,2),16);
                        if($val <= 0x7F){        // 0000-007F
                                $c .= chr($val);
                        }elseif($val <= 0x800) { // 0080-0800
                                $c .= chr(0xC0 | ($val / 64));
                                $c .= chr(0x80 | ($val % 64));
                        }else{                // 0800-FFFF
                                $c .= chr(0xE0 | (($val / 64) / 64));
                                $c .= chr(0x80 | (($val / 64) % 64));
                                $c .= chr(0x80 | ($val % 64));
                        }
                }
        }
        return $c;
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

function getrestmoney($authorid)//获取剩余金额
{
	$db=new db_test;
	$data=date("Y-m-d",time());
	$query="select * from   tb_agentpaymoneylist
			left join tb_slotcardmoneyreq on fd_agpm_authorid = fd_pmreq_authorid
			left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid
			where fd_agpm_authorid='$authorid'  and fd_agpm_paydate = '$data'";
	$db->query($query);	
	    if($db->nf()){                                            //判断查询到的记录是否为空
	      while($db->next_record()){                                   //读取记录数据 
			$sallmoney=$db->f(fd_scdmset_sallmoney);
			$allpaymoney +=$db->f(fd_agpm_paymoney);
		}
		return $sallmoney-$allpaymoney;
  } 		
}


function getusemoney($authorid)//获取已用金额
{
	$db=new db_test;
	$data=date("Y-m-d",time());
	$query="select * from tb_slotcardmoneyreq
			left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid 
			where fd_pmreq_authorid='$authorid' and fd_pmreq_state='9' and fd_pmreq_reqdatetime like '$data%'";
	$db->query($query);	
	    if($db->nf()){                                            //判断查询到的记录是否为空
	      while($db->next_record()){                                   //读取记录数据 
			$allreqmoney +=$db->f(fd_pmreq_reqmoney);
		}
		return $allreqmoney;
  } 		
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
			$paycardid = $db->f(paycardid);
			
			if($strpaycardid)
			{
				$strpaycardid .=","."'$paycardid'";
			}else{
				$strpaycardid="'$paycardid'";
			}
			if($parename=="skdetail")
			{
				$batches= $db->f(batches);
				$arr_suppname=$db->f(fd_stock_suppname);				
						$arr_paycardid=explode("-",$paycardid);
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
		$returvalue[]=$strpaycardid;
		$returvalue[]=$arr_newbatches;
		$returvalue[]=$arr_newsuppname;		
	return $returvalue;
}


//获取刷卡器销售总价格
function getdallsaleprice($strpaycardid)
{
	$db  = new DB_test;
	$query="select * from tb_paycard";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			
			$arr_savesaleprice[$db->f(fd_paycard_id)]=$db->f(fd_paycard_saleprice);
		}
	}

	$arr_savepaycard=explode(",",$strpaycardid);
	
	foreach($arr_savepaycard as $value)
	{
		$vallmoney +=$arr_savesaleprice[$value];
	}
		return $vallmoney;
}



//获取要删除的刷卡器
function getdatepaycard($tblname,$parame,$listid)
{
	$db  = new DB_test;
	$query='select * from '.$tblname.' where fd_'.$parame.'_id="'.$listid.'"';
	$db->query($query);
	if($db->nf())
	{	
	
		$db->next_record();
		$str_paycardid=$db->f('fd_'.$parame.'_paycardid');
		
	}
	$arr_paycardid=explode(",",$str_paycardid);
	return $arr_paycardid;
}

 //统计刷卡器退货数量和金额 
function countallbackpaycard($listid)
{
	
		$db  = new DB_test;  
		$query="select fd_skdetail_quantity,fd_skdetail_paycardid from tb_paycardstockbackdetail where fd_skdetail_stockid=$listid";
		$db->query($query);
		if($db->nf())
		{
			while($db->next_record())
			{
			
			
					$quantity=$db->f(fd_skdetail_quantity);
					$paycardid=$db->f(fd_skdetail_paycardid);
					
					if($tmp_paycardid){$tmp_paycardid .=",".$paycardid;}else{$tmp_paycardid .=$paycardid;}
					$allquantity +=$quantity;
			}
		}
			
			$arr_backpaycarid=explode(",",$tmp_paycardid);
			foreach($arr_backpaycarid as $value) 
			{
				$query="select fd_paycard_stockprice from tb_paycard where fd_paycard_id='$value'";
				$db->query($query);
				if($db->nf())
				{
					$db->next_record();
					$stockprice=$db->f(fd_paycard_stockprice);
				}
				$allmoney +=$stockprice;
			}
	   $query = "update tb_paycardstockback set fd_stock_allmoney =$allmoney,fd_stock_allquantity  = '$allquantity'
	             where fd_stock_id = '$listid'";  	   
	   $db->query($query);
	  	 
}

 //生成刷卡器批次号
function makebatches($suppno,$listid)
{
	
 
 	  $db2  = new DB_test;
	  $year  = trim(date( "Y", mktime())) ;   
       $month = trim(date( "m", mktime())) ;   
       $day   = trim(date( "d", mktime())) ;   
		$strlenght=strlen($suppno);
       $nowdate = $year.$month.$day  ;  
       $query = "select fd_skdetail_batches  from tb_paycardstockdetail where fd_skdetail_batches like '$suppno%' order by fd_skdetail_batches  desc";
	  $db2->query($query);
       if($db2->nf()){
	     $db2->next_record();
         $orderno   = $db2->f(fd_skdetail_batches);	
         $orderdate = substr($orderno,$strlenght,8);       //截取前8位判断是否当前日期   
         if($nowdate == $orderdate){
			$newlenght=$strlenght+11;
        	 $orderno = substr($orderno,$newlenght,14) + 1 ;	  //是当前日期流水帐加1
        	 if($orderno < 10){
        	   $orderno="00000".$orderno  ;      	  	
        	 }else if($orderno < 100){
        	   $orderno="0000".$orderno   ;     	  	
        	 }else if($orderno < 1000){
        	   $orderno="000".$orderno   ;     	  	
        	 }else if($orderno < 10000){
        	   $orderno="00".$orderno   ;     	  	
        	 }else{
        	   $orderno=$orderno;
         	 }        	  
        	 $orderno=$suppno.$nowdate.$orderno;       	  
        }else{

        	$orderno = $suppno.$nowdate."000001" ;       	    //不是当前日期,为5位流水帐,开始值为1。
        }         
       }else{
	       $orderno  = $suppno.$nowdate."000001" ;      	
       }
	  	return  $orderno;
}

//更新刷卡器信息,找出刷卡器ID
 function updatepaycard($paycardid,$price,$date,$cusid)
{
	$db = new DB_test;
	$arr_paycarid=explode(",",$paycardid);
	
	foreach($arr_paycarid as $value1)
	{
		
		$query="select * from tb_paycard where fd_paycard_id = '$value1'";
		$db->query($query);
		if($db->nf())
		{
			$db->next_record();
			$stockprice = $db->f(fd_paycard_stockprice); //单价
			$paycard_memo=$db->f(fd_paycard_memo);
		}
		$query = "update tb_paycard set fd_paycard_saleprice = '$price' , fd_paycard_cusid='$cusid'  ,fd_paycard_memo='$paycard_memo,$date 该刷卡器销售出库'              
				where fd_paycard_id = '$value1' ";
		$db->query($query); 
	}
		
		
} 
function getpaycardkey($paycardid)
{
	$db = new DB_test;
	$arr_paycarid=explode(",",$paycardid);
	foreach ($arr_paycarid as $va) {
	if ($strpaycardid) {
		$strpaycardid .= "," . "'$va'";
	} else {
		$strpaycardid .= "'$va'";
	}
		}
	$query="select group_concat(fd_paycard_key) as paycardkey from tb_paycard where fd_paycard_id IN ($strpaycardid)" ;
	$arr_data=$db->get_one($query);
	
	return  $arr_data['paycardkey'];
}

function getauthorpaycardmenu()//获取商户刷卡器统计报表菜单
{


	$db=new db_test;
	$query = "select
	fd_appmnu_name  as paytype,
	fd_appmnu_no
	from tb_appmenu
	where   fd_appmnu_no <>'' and  fd_appmnu_istabno = 1";
   $db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$appmnu_tabtype  = $db->f(fd_appmnu_no);
		    $arr_appmnu[$appmnu_tabtype]  = $db->f(paytype);
		}
	}
	return $arr_appmnu;
}

function getallpaymoney()
{
$db=new db_test;
$query="select sum(fd_agpm_paymoney) as paymoney
		from tb_agentpaymoneylist 
		where fd_agpm_payrq='00'";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	
		$allpaymoney  = $db->f(paymoney);
	 }
}
return $allpaymoney;
}


function changepaycardstate($arr_paycardid ,$state)
{
	$db = new DB_test;
	
		foreach($arr_paycardid as $value)
	{
		$query = "update tb_paycard set fd_paycard_state='$state' where fd_paycard_id='$value'";
		$db->query($query);
	}
}


//保存留小数位
function changenum($resernum,$value)
{
	if($value and $value !="0")
	{
		$value=sprintf("%.".$resernum."f",$value);
	}else{
		$value="";
	}
	return $value;
}


function countallsalepaycard($listid,$ftabname,$stabname)//统计销售或销售退货刷卡器数量
{
	$db  = new DB_test;  
	$query="select sum(fd_stdetail_quantity) as allquantity from $stabname where fd_stdetail_seltid=$listid group by fd_stdetail_seltid ";
	$arr_date=$db->get_one($query);
	$allquantity=$arr_date['allquantity'];

    $memo_z ="系统补货".$allquantity."台刷卡器";
	$query = "update $ftabname set fd_selt_allquantity  = '$allquantity',fd_selt_memo = '$memo_z'
	             where fd_selt_id = '$listid'";  	   
	$db->query($query);
}
?>