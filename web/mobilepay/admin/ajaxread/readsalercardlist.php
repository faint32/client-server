<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;
// --会员表待确认
$query = "select * from tb_organmem ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $id             = $db->f(fd_organmem_id);            //id号  
       $arr_truename[$id]  = trim($db->f(fd_organmem_username));  
	       
}} 
if(!empty($member))
{
	$querywhere ="  and fd_salercard_memberid>0 ";
}
$aColumns = array("","fd_saler_truename","fd_salercard_zjl","fd_salercard_no","fd_organmem_username");

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
	
     $query = "select 1 from web_salercard 
left join web_saler on fd_saler_id = fd_salercard_salerid 
left join tb_organmem on  fd_salercard_memberid = fd_organmem_id where 1 $querywhere
								$sWhere  ";
   $db->query($query);
   $totoalcount=$db->nf()+0;
   
   				  
$query = "select * from web_salercard 
left join web_saler on fd_saler_id = fd_salercard_salerid
left join tb_organmem on  fd_salercard_memberid = fd_organmem_id  
where fd_salercard_zf <> '1'   $querywhere $sWhere limit $iDisplayStart,$iDisplayLength  ";
$db->query($query);
//echo $query;
if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_salercard_id);            //id号  
       $vtruename       = g2u($db->f(fd_saler_truename));        
       $vidcard         = g2u($db->f(fd_saler_idcard)); 
       $vno             = g2u($db->f(fd_salercard_no));
	   $vphone          = g2u($db->f(fd_saler_phone));
	   $vmemberid       = g2u($db->f(fd_salercard_memberid));
       $vsharesaler     = g2u(selectsalercard($db->f(fd_saler_sharesalerid)));
	   $vtype           = $db->f(fd_saler_salertype);
	   $vmember         = g2u($arr_truename[$vmemberid]);
       $vinitialmoney     = $db->f(fd_salercard_initialmoney)+0;
	   $vratiomoney       = $db->f(fd_salercard_ratiomoney)+0;
	   $vratiodun         = $db->f(fd_salercard_ratiodun)+0;
	   $vzjl         = g2u($db->f(fd_salercard_zjl));
	   $vsalemoney         = g2u($db->f(fd_salercard_usemoney));
	    $vzf         = g2u($db->f(fd_salercard_zf));
	   if($vratiodun>0)
	   {
	     $vperent            =$vratiodun.'吨/￥'.$vratiomoney;
	   }else
	   {
		  $vperent           = "任意支配"; 
	   }
	   $vperent              = g2u($vperent);
	   $vinitialmoney        = $vinitialmoney."/".$vsalemoney;
	   if($vtype==1)
	   {
		   $vtype="网导员";
	   }else if($vtype==2)
	   {
		   $vtype="网导经理";
	   }
	   $vtype=g2u($vtype);
	   $vedit =  '<a class="edit" onclick="edit('.$vid.')">&nbsp;</a>&nbsp;&nbsp;<a class="del" onclick="del('.$vid.')">&nbsp;</a>';
	   		   
	   $arr_list[] = array("DT_RowId" => $vid ,
                        "DT_RowClass" => "",
		                $vtruename,
						$vidcard,
						$vtype,
						$vsharesaler,						
						$vzjl,
						$vno,
						$vinitialmoney,
						$vperent,
						$vstate,
						$vmember,
						$vedit);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
		                $vtruename,
						$vidcard,
						$vtype,
						$vsharesaler,
						$vzjl,
						$vno,
						$vstate,
						$vno,
						$vstate,
						g2u($vmember) ,
						$vedit);
   }
      
		 $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
//显示帐户列表
function selectsalercard($sharesalerid)
{
$db  = new DB_test;

//显示帐户选择列表
$query = "select * from web_saler where fd_saler_id='$sharesalerid'" ;
$db->query($query);
if($db->nf()){
   while($db->next_record()){		
		   $arr_salerid     = $db->f(fd_saler_id)  ; 
		   $arr_saler       = $db->f(fd_saler_truename);    
   }
}
return $arr_saler;
}
?>