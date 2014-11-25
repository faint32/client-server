<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

	
	
$aColumns = array("","");

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
$salepaycardbackstate=getsalepaycardbackstate();
 $query = "select fd_paycard_id, fd_paycard_batches, fd_paycard_key, 
		fd_paycard_stockprice,
		fd_paycard_posstate,
		fd_product_suppname,
		fd_product_name,fd_paycard_scope
		from tb_paycard
		left join tb_product on fd_product_id=fd_paycard_product
		left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid  
		where fd_paycard_posstate='4' $sWhere";
$db->query($query);
$totoalcount=$db->nf()+0;

$query = "  $query  limit $iDisplayStart,$iDisplayLength";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
		$paycardid         = $db->f(fd_paycard_id);
		if($salepaycardbackstate[$paycardid]['state']=="9")
		{
		
		$batches    = $db->f(fd_paycard_batches);
		$paycardkey = $db->f(fd_paycard_key);
		$stockprice	 = $db->f(fd_paycard_stockprice);
		$suppname	 = g2u($db->f(fd_product_suppname));		
		$productname = g2u($db->f(fd_product_name));
		$scope	 = $db->f(fd_paycard_scope);
		if($scope=="creditcard"){$scope="信用卡";}
		if($scope=="bankcard"){$scope="储蓄卡";}
			$action="<span style='cursor:pointer;color:blue;' onclick='changestate($paycardid)'>重新出售</span>";
			$arr_list[$count] = array(
					"DT_RowId" => $vid ,
					"DT_RowClass" => "",
					g2u($action),
					$paycardid,
					$batches,
					$paycardkey,
					$suppname,
					$productname,
					$stockprice,
					g2u($scope),
					$salepaycardbackstate[$paycardid]['date'],
					g2u($salepaycardbackstate[$paycardid]['memo'])
					);	
		
		}		
		
		
     }
	
   }
   else
   {     
     $vmember  = "暂无数据";
   }
   
	if(!$arr_list)
	{
		$arr_list[$count] = array(
		"DT_RowId" => $vid ,
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
		""
		);
	}
		
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
		echo  json_encode($returnarray);
		
function getsalepaycardbackstate()
{
$db = new DB_test;
	$query="select fd_selt_state as state,fd_stdetail_paycardid as paycardid,fd_selt_date as date,fd_stdetail_memo as memo   from tb_salelistbackdetail
	left join tb_salelistback on fd_selt_id=fd_stdetail_seltid";
	$db->query($query);
	$arr_data=$db->getFiledData();
	foreach($arr_data as  $value)
	{
		$arr_pacardid=explode(",",$value['paycardid']);
		foreach($arr_pacardid as $v1)
		{
			$arr_state[$v1]['state']=$value['state'];
			$arr_state[$v1]['date']=$value['date'];
			$arr_state[$v1]['memo']=$value['memo'];
		}
	}
	return $arr_state;
}		
?>