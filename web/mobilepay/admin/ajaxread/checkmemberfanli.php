<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;




$aColumns = array("","fd_fanlilist_month",'fd_organmem_comnpany');
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


$vmoney    = "";
$vquantity = "";
$vjxid     = "";
$vmember   = "";
$vfanli    = "";
$vofanli   = "";


$selectarray = array("赠送账户","充值账户");	

$query="select * from web_memfanlilist
        left join tb_organmem on fd_organmem_id = fd_fanlilist_memberid  where fd_fanlilist_state='$state' 
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
		$vmember   = g2u($db->f(fd_organmem_comnpany));
		$s_month  = $db->f(fd_fanlilist_month);
		$vfanli   = $db->f(fd_fanlilist_fanli)+0;
		//echo $vfanli;
		$vaccount  = $db->f(fd_fanlilist_account);
		
		
		  $vfanli        =$vfanli."<input type='hidden' value='".$vfanli."' name='arr_fanli[]'>
	                    <input type='hidden' value='".$memberid."'  name='arr_memberid[]'>
						 <input type='hidden' value='".$s_month."'  name='arr_month[]'>";
						 
       $account = makeselect($selectarray,$vaccount,$selectarray);
	   $account = "<select name='arr_account[]' >".g2u($account)."</select>";
	   	   
	   $arr_list[] = array(
	                    "DT_RowId" => $vmember ,
                        "DT_RowClass" => "",
						 $s_month,
		                 $vmember,
						
						 $vfanli,
						
						 $account);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						
						 "",
						 "",
						  "",
						 ""
						 				          );
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);



?>