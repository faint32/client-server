<?php 
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

if($type!='checkdetail')
{
	$idwhere="fd_authorbkcard_authorid='$listid'";
}else{
	$idwhere="fd_authorbkcard_paycardid='$listid'";
}

$aColumns = array("","fd_bank_name","fd_authorbkcard_no","fd_authorbkcard_cardname","fd_authorbkcard_ckr","fd_paycard_key");

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
	


	
	$arr_name=array("2"=>"到期前2天提醒","3"=>"到期前3天提醒","4"=>"到期前4天提醒","5"=>"到期前5天提醒","6"=>"到期前6天提醒","7"=>"到期前7天提醒");
	
 $query = "select 
		fd_bank_name,fd_authorbkcard_no,fd_authorbkcard_cardname,fd_authorbkcard_ckr,
		fd_authorbkcard_creditmoney,fd_authorbkcard_zdr,fd_authorbkcard_hkr,fd_authorbkcard_mxq,
		fd_authorbkcard_txr,fd_authorbkcard_dqr,fd_paycard_key,fd_authorbkcard_activetime,fd_authorbkcard_datetime
		from tb_authorbkcard   
		 left join tb_bank  on fd_bank_id = fd_authorbkcard_bankid
		left join tb_paycard  on fd_paycard_id = fd_authorbkcard_paycardid			 
		 where $idwhere $sWhere order by fd_authorbkcard_datetime desc";
$db->query($query);
$totoalcount=$db->nf()+0;
$count=0;
$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		
		$bank_name      = g2u($db->f(fd_bank_name));
		$paycardkey     = $db->f(fd_paycard_key);
		$cardno        = $db->f(fd_authorbkcard_no);
		$cardname   = g2u($db->f(fd_authorbkcard_cardname)) ;
		$ckr       = g2u($db->f(fd_authorbkcard_ckr)) ;
		$creditmoney	    = $db->f(fd_authorbkcard_creditmoney) ;
		$zdr 	= g2u($db->f(fd_authorbkcard_zdr));
		$hkr      = g2u($db->f(fd_authorbkcard_hkr));
		$mxq     = ($db->f(fd_authorbkcard_mxq));
		$txr        = $db->f(fd_authorbkcard_txr);
		$dqr   = $db->f(fd_authorbkcard_dqr) ;
		$activetime       = $db->f(fd_authorbkcard_activetime) ;
		$datetime	    = $db->f(fd_authorbkcard_datetime) ;
		
		$txr=g2u($arr_name[$txr]);
	   if(!$paycardkey)
	   {
		$paycardkey="未绑定刷卡器";
	   }
	   $arr_list[] = array("DT_RowId" => $vid ,
				"DT_RowClass" => "",
				$bank_name,
				g2u($paycardkey),
				$cardno,
				$cardname,
				$ckr,
				$creditmoney,
				$zdr,
				$hkr,
				$mxq,
				$txr,
				$dqr,
				$activetime,
				$datetime
				);	
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array("DT_RowId" => $vid ,
		"DT_RowClass" => "",
		$bank_name,
		g2u($paycardkey),
		$cardno,
		$cardname,
		$ckr,
		$creditmoney,
		$zdr,
		$hkr,
		$mxq,
		$txr,
		$dqr,
		$activetime,
		$datetime
		);	
   }
		
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>