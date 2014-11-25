<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;

$aColumns = array("","fd_payfee_no","fd_payfee_addmoney","fd_paycard_no","fd_payfee_datetime",'fd_payfee_paymode');



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

	
$query = "SELECT * FROM `tb_payfeelist`
left join tb_paycard on fd_paycard_id=fd_payfee_paycardid 
where fd_payfee_tabname='$tabname' $sWhere";
$db->query($query);
$totoalcount=$db->nf()+0;	
$count=0;
$query = "$query limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		 
	   $vid             = $db->f(fd_payfee_id);            //idКХ  
	   $vpayfeeno             = $db->f(fd_payfee_no);            //idКХ  
       $vpaycardid       = $db->f(fd_paycard_no);       
       $vaddmoney       = $db->f(fd_payfee_addmoney); 
	   $vlessmoney      = $db->f(fd_payfee_lessmoney);
	   $vpaymode        = g2u($db->f(fd_payfee_paymode));
       $vtime           = $db->f(fd_payfee_datetime);
	   $vpaymemo       = g2u($db->f(fd_payfee_memo));
	   
	   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",					
		                $vpayfeeno,
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
						$vpayfeeno,
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