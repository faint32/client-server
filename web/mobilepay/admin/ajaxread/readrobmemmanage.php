<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$db2=new db_test;
switch($saler_type)
{
	case "0":
	$salerquery=" and fd_getmemberglide_odg='$salerid'";
	break;
	case "1":
	$salerquery=" and fd_getmemberglide_idg='$salerid'";
	break;
}
$aColumns = array("","fd_getmemberglide_odgname","fd_getmemberglide_idgname","fd_getmemberglide_date","fd_getmemberglide_memo");

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
	
$query = "select * from web_getmemberglide where 1  $salerquery $sWhere";
$db->query($query);
//echo $query;
$totoalcount=$db->nf()+0;	
if($iDisplayStart>$totoalcount) $iDisplayStart=0;
if($iDisplayStart>$totoalcount) $iDisplayStart=0;
$query .= " limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
	   $getmemdate          = $db->f(fd_getmemberglide_date); 
	   $getmemodgname       = g2u($db->f(fd_getmemberglide_odgname));   
       $getmemidgname       = g2u($db->f(fd_getmemberglide_idgname)); 
	   $getmemmemo          = g2u($db->f(fd_getmemberglide_memo));        
	   $getmemodg           = $db->f(fd_getmemberglide_odg); 
	   $getmemidg           = $db->f(fd_getmemberglide_idg); 
	    $getmemid          = $db->f(fd_getmemberglide_memid); 
	   $getmemonewsname=g2u(getnewssaler($getmemodg));//被抢注现在名
	   
	   $getmeminewsname=g2u(getnewssaler($getmemidg));//抢注现在名
	   
	   	   $query="select fd_organmem_comnpany,fd_organmem_mobile from tb_organmem where fd_organmem_id='$getmemid'";
	   $db2->query($query);
	   if($db2->nf())
	   {
		$db2->next_record();
		  $getmemcompany        = g2u($db2->f(fd_organmem_comnpany));
	      $getmemmobile        = g2u($db2->f(fd_organmem_mobile)); 		  
	   }
	   $getcompanybile=$getmemcompany ."($getmemmobile)";
	   //$vedit =  ' <b class="editable_select" > Edit me!</b>';		   
	   $arr_list[] = array(
	                   "DT_RowId"     => $vid ,
                        "DT_RowClass" => ""   ,
						$getcompanybile,
		                $getmemidgname,
						$getmeminewsname,
						$getmemodgname,
						$getmemonewsname,
						$getmemmemo,
						$getmemdate,
					
						
						);
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
						"",
						"",
						"",						
				          );
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
function getnewssaler($value)
{
	$db=new db_test;
	$query="select fd_saler_truename from web_saler where fd_saler_id='$value'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		
		   $salername = $db->f(fd_saler_truename); 
		  return   $salername;
   }
	
}
?>