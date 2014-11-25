<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;


$aColumns = array("","fd_paycard_key","fd_paycard_newspaydata","fd_cus_allname");

 $sSearch=u2g($sSearch);
    $sWhere = "";
    if ($sSearch != "" )
    {
        $sWhere = "and  (";
        for ( $i=1 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= $aColumns[$i]." LIKE '%".trim($sSearch)."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
     
    /* Individual column filtering */
    for ( $i=1 ; $i<count($aColumns) ; $i++ )
    {
		$b_s="bSearchable_".$i;
		$s_s="sSearch_".$i;
        if ( $$b_s == "true" && $$s_s != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "AND ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".trim($$s_s)."%' ";
        }
    }
	
$arr_appmnu=getauthorpaycardmenu();
$nowdate=date("Y-m",time());
	
$query="select sum(fd_agpm_paymoney) as paymoney,COUNT(fd_agpm_paycardid) as paycardcount ,
		fd_agpm_paytype as  paytype ,fd_agpm_paycardid as paycardid
		from tb_agentpaymoneylist   
		group by fd_agpm_paycardid ,fd_agpm_paytype ";
   $db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$paycardid  = $db->f(paycardid);
			$paytype    = $db->f(paytype);
			$arr_data[$paycardid][$paytype]['paymoney']  = $db->f(paymoney);
			$arr_data[$paycardid][$paytype]['paycardcount']  = $db->f(paycardcount);
		 }
	}
	
 $query = "select
		group_concat(fd_agpm_paytype)  as paytype,
		fd_paycard_newspaydata as newspaydata,
		sum(fd_agpm_paymoney) as paymoney,	
		COUNT(fd_agpm_paycardid) as paycardcount,
		fd_paycard_key as paycardkey ,
		fd_cus_allname as allname ,
		fd_agpm_paycardid as paycardid,
		fd_paycard_everymoney as everymoney ,
		fd_paycard_everycounts as everycounts,
		fd_paycard_neverymoney as neverymoney,
		fd_paycard_nallmoney as nallmoney
		from tb_paycard   
		left join tb_agentpaymoneylist  on fd_paycard_id = fd_agpm_paycardid
		left join tb_customer  on fd_paycard_cusid = fd_cus_id			 
		 where fd_agpm_payrq='00' and fd_paycard_authorid='$authorid' and DATE_FORMAT(fd_agpm_paydate,'%Y-%m')='$nowdate' $sWhere group by fd_agpm_paycardid ";
$db->query($query);
$totoalcount=$db->nf()+0;
$count=0;
$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		
		$paycardid      = $db->f(paycardid);
		$paycardkey     = $db->f(paycardkey);
		$allname        = $db->f(allname);
		$paycardcount   = $db->f(paycardcount);
		$paymoney       = $db->f(paymoney);
		$paytype	    = $db->f(paytype);
		$newspaydata 	= $db->f(newspaydata);
		$everymoney 	= $db->f(everymoney)+0;
		$everycounts 	= $db->f(everycounts)+0;
		$neverymoney 	= $db->f(neverymoney)+0;
		$nallmoney 	    = $db->f(nallmoney)+0;
		if(!$allname)
		{
			$allname="该刷卡器未销售!";
		}
		
		$url='checkauthorbank';
		$paycardkey='<a href="#" onclick="checkdetail(this,\''.$paycardid.'\',\''.$url.'\')" style="color:blue;">'.$paycardkey.'</a>';
		
		$url='../report/consp_data';
		
		
		$paycardcount='<a href="#" onclick="checkdetail(this,\''.$paycardid.'\',\''.$url.'\')" style="color:blue;">'.$paycardcount.'</a>';
		
		$paymoney='<a href="#" onclick="checkdetail(this,\''.$paycardid.'\',\''.$url.'\')" style="color:blue;">'.$paymoney.'</a>';
	   $arr_list[$count] = array("DT_RowId" => $vid ,
				"DT_RowClass" => "",
				g2u($paycardkey),
				$everymoney,
				$everycounts,
				$neverymoney,
				$nallmoney,
				g2u($allname),
				g2u($paycardcount),
				g2u($paymoney),
				$newspaydata);	
		$arr_paytype=explode(",",$paytype);
		$arr_paytype=array_unique($arr_paytype);
		
			foreach($arr_paytype as $value)
			{
				
				$new_data[$paycardid][$value]['paymoney']=$arr_data[$paycardid][$value]['paymoney'];
				$new_data[$paycardid][$value]['paycardcount']=$arr_data[$paycardid][$value]['paycardcount'];
				
				
			}
	
		 foreach($arr_appmnu as $key=>$v)
		{
			 $arr_list[$count][]=$new_data[$paycardid][$key]['paymoney'];
			  $arr_list[$count][]=$new_data[$paycardid][$key]['paycardcount'];
		}	
		//array_push($arr_list,);				
		$strpaytype="";		
		$count++;	
     }
   }
   else
   {     
	 $arr_list[$count] = array("DT_RowId" => $vid ,
				"DT_RowClass" => "",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"");	
     $vmember  = "暂无数据";
   }
		
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>