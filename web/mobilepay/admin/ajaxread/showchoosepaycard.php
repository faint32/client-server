<?   
header('Content-Type:text/html;charset=GB2312'); 
require("../include/common.inc.php");

require_once('../include/json.php');
$db=new db_test;


$query = "SELECT * FROM `tb_salelist_tmp`
where fd_tmpsale_id='$tmpid' ";
$db->query($query);
if($db->nf())
{
	$db->next_record();
	$str_tmppaycardid= $db->f(fd_tmpsale_paycardid);            //idºÅ    
}
$arr_tmppaycardid=explode(",",$str_tmppaycardid);
foreach($arr_tmppaycardid  as $value)
{
	if($strpaycardkey)
	{
		$strpaycardkey .=","."'$value'";
	}else{
		$strpaycardkey="'$value'";
	}
}


$aColumns = array("","fd_paycard_key","fd_paycard_batches","fd_product_manufacturerno","fd_product_manufacturername");



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
$query = "SELECT * FROM `tb_paycard`
left join tb_product on fd_product_id=fd_paycard_product 
where fd_paycard_key in($strpaycardkey) $sWhere";
$db->query($query);
$totoalcount=$db->nf()+0;	
$count=0;
$query = "$query limit $iDisplayStart,$iDisplayLength ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		 
	   $vpaycardkey          = $db->f(fd_paycard_key);            //idºÅ  
       $vmanufacturername    = $db->f(fd_product_manufacturername);       
       $vbatches             = $db->f(fd_paycard_batches); 
	  
	   	   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",					
		                $vbatches,
						$vpaycardkey,
						$vmanufacturername					
						);
     }
   }
   else
   {     
     $vmembernum  = "ÔÝÎÞÊý¾Ý";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $vbatches,
						$vpaycardkey,
						$vmanufacturername
						);
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
?>