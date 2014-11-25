<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;

$aColumns = array("","fd_author_truename");



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

$query = "SELECT * FROM `tb_authoraccountglist`
left join tb_paycard on fd_paycard_id=fd_accglist_paycardid 
where fd_accglist_authorid='$authorid' $sWhere";
$db->query($query);
$totoalcount=$db->nf()+0;	
$count=0;
$query = "$query limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_accglist_no);            //idКХ  
       $vpaycardid       = $db->f(fd_paycard_key);       
       $vaddmoney       = $db->f(fd_accglist_money); 
	   $vpaymode        = g2u($db->f(fd_accglist_paytype));
       $vtime           = $db->f(fd_accglist_datetime);
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",					
		                $vid,
						g2u($vtype),
						$vpaycardid,
						$vaddmoney,
						$vpaymode,
						$vtime
						);
     }
   }
   else
   {     
     $vmembernum  = "днЮоЪ§Он";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$vid,
						$vtype,
						$vpaycardid,
						$vaddmoney,
						$vpaymode,
						$vtime);
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
?>