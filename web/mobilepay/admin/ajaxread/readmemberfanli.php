<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
require_once('../function/readsalebackdunshu.php');

$db=new db_test;




$aColumns = array("","fd_organmem_comnpany");
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
$query = "select * from web_setfanli";
$db->query($query);
if($db->nf()){
	$db->next_record();
    $nowyn        = $db->f(fd_setfanli_beginmonth);
	$monthdunshu  = $db->f(fd_setfanli_monthdunshu);
	$monthfanli   = $db->f(fd_setfanli_monthfanli);
	$allmoney     = $db->f(fd_setfanli_allmoney);
	
}

$query = "select sum(fd_fanlilist_fanli) as usemoney,fd_fanlilist_memberid from web_memfanlilist  
          group by fd_fanlilist_memberid";
$db->query($query);
if($db->nf()){
	while($db->next_record())
{
   // $id           = $db->f(fd_salercard_id);
	$memberid     = $db->f(fd_fanlilist_memberid);
	$usemoney     = $db->f(usemoney);
	$arr_usemoney[$memberid] = $usemoney;
}
}

$query = "select max(CONCAT(fd_fanlilist_month, '-01')) as maxbatch from web_memfanlilist  group by fd_fanlilist_listtype";
$db->query($query);
if($db->nf()){
	$db->next_record();
    $maxbatch   = $db->f(maxbatch);
}
if($maxbatch<>"")
{
   $lastyn = $maxbatch;

   $nowyn = date('Y-m', strtotime("$lastyn +1 month 0 day")); 
} 

$vmoney    = "";
$vquantity = "";
$vjxid     = "";
$vmember   = "";
$vfanli    = "";
$vofanli   = "";


$selectarray = array("赠送账户","充值账户");	
//$state1 = "订单完成";
//$state1 = u2g($state1);
$query="select fd_organmem_comnpany,fd_organmem_id,sum(fd_order_alldunshu) as allquantity  ,
          month(fd_order_date) as monthdate ,year(fd_order_date) as yeardate 
          from web_order
          left join tb_organmem on fd_organmem_id = fd_order_memeberid   
		      where fd_order_state = '7' and fd_order_zf!='1' and 
			  (fd_organmem_mcardid is not null 
			   or fd_order_youhui = '1')
			  
			  and fd_order_date like '%$nowyn%' 
		      and fd_organmem_type = 0
		      group by fd_organmem_id,
          year(fd_order_date),month(fd_order_date)
          $sWhere ";
$db->query($query);
//echo $query;
$totoalcount=$db->nf()+0;	

$query .= " limit $iDisplayStart,$iDisplayLength ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
        $memberid   = $db->f(fd_organmem_id);
		$vmember   = trim($db->f(fd_organmem_comnpany));
		$monthdate  = $db->f(monthdate)+0;
		if($monthdate<10){$monthdate = "0".$monthdate;}
		$yeardate   = $db->f(yeardate);
		$s_month    = $yeardate."-".$monthdate;
		$vquantity  = $db->f(allquantity);
		$tquantity =readsalebackdunshu($yeardate,$monthdate,$memberid);
		$vquantity =($vquantity-$tquantity);
		$vmonthfanli = (($vquantity)/$monthdunshu)*$monthfanli;
		
		$usemonthfanli = $arr_usemoney[$memberid]+0;
		
		$allfanlimoney  = $usemonthfanli +$vmonthfanli;
		
		$allsyfanlimoney  = $allmoney-$usemonthfanli-$vmonthfanli;
	
	
	   $vfanli        ="<input type='text' value='".$vmonthfanli."' name='arr_fanli[]'>
	                    <input type='hidden' value='".$memberid."'  name='arr_memberid[]'>
						 <input type='hidden' value='".$s_month."'  name='arr_month[]'>";
	
       $account = makeselect($selectarray,$hadselected,$selectarray);
	   $account = "<select name='arr_account[]'>".trim($account)."</select>";
	   
	  // $vedit =  '<a class="edit" onclick="edit('.$memberid.')">&nbsp;</a><a class="del" onclick="del('.$memberid.')">&nbsp;</a>';		   
	   $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $s_month,
		                 $vmember,
						 $vquantity,
						 $vfanli,
						 $usemonthfanli,
						 $allfanlimoney,
						 $allsyfanlimoney,
						 $account,
						 $vedit);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $s_month,
		                 $vmember,
						 $vfanli,
						 $usemonthfanli,
						 $allfanlimoney,
						 $allsyfanlimoney,
						 "",
						 "",
						 $vedit
				          );
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);


?>