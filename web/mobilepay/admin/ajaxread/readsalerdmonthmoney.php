<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;


$aColumns = array("","fd_saler_truename",'fd_saler_zjl');
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
$query = "select * from  web_salerrewards where fd_rewards_type  = '1'";   //激活奖励机制
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $cardmoney      = $db->f(fd_rewards_upmoney);
	}}
$query = "select * from  web_salerrewards where fd_rewards_type  = '2'";   //销售奖励
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $ordermoney      = $db->f(fd_rewards_upmoney);      
	}}
//开卡数有效记录
$query = "select fd_saler_sharesalerid,count(*) as counts from web_salercard 
left join web_saler on fd_salercard_salerid = fd_saler_id  
where fd_salercard_memberid >0 and fd_salercard_state='1'  and fd_salercard_opendate like '%$beginmonth%'   
group by fd_saler_sharesalerid  ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $id                 = $db->f(fd_salercard_salerid);            //id号  
       $arr_membercounts[$id]  = g2u($db->f(counts));  
	       
}}
$query = "select fd_saler_sharesalerid ,sum(fd_organorder_allquantity) as allquantity  
          from tb_organorder
          left join web_salercard on fd_salercard_memberid = fd_organorder_organmemid  
		  left join web_saler on fd_salercard_salerid = fd_saler_id 
		  where fd_organorder_allquantity>'0' and fd_organorder_date  like '%$beginmonth%'   group by fd_saler_sharesalerid ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $tmpsalerid                           = $db->f(fd_saler_sharesalerid);            
	   $varr_allquantity[$tmpsalerid]       = $db->f(allquantity)+0;    
	}}		
$query = "SELECT * FROM `web_saler`  where fd_saler_salertype='2' 
								$sWhere  ";
   $db->query($query);
   $totoalcount=$db->nf()+0;	
$count=0;
$query .= " limit $iDisplayStart,$iDisplayLength ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_saler_id);            //id号  
       $vtruename       = g2u($db->f(fd_saler_truename));       
       $vidcard         = g2u($db->f(fd_saler_idcard)); 
       $vtjman          = g2u($db->f(fd_saler_tjman));
	   $vphone          = g2u($db->f(fd_saler_phone));
	   $vusername       = g2u($db->f(fd_saler_username));
	   $vzjl             = g2u($db->f(fd_saler_zjl));
	   $vpassword       = g2u($db->f(fd_saler_password));
	   $vmembernum      = g2u($arr_membercounts[$vid])+0;
       $vsharesaler     = g2u(selectsaler($db->f(fd_saler_sharesalerid)));
	   $vtype           = g2u($db->f(fd_saler_salertype));
	   $vstate          = $db->f(fd_saler_state);
	   $vallquanity     =  $varr_allquantity[$vid];
	    if($vtype==1)
	   {
		   $vtype="网导员";
	   }else if($vtype==2)
	   {
		   $vtype="网导经理";
	   }
	   $vtype=g2u($vtype);
	   
	   
	   $cardsmoney =$cardmoney*$vmembernum;
	   $ordersmoney=$ordermoney*$vallquanity;
	   //if($j==1) $money=$upmoney;
	   
	   $vedit =  '<a class="edit" onclick="edit('.$vid.')">&nbsp;</a><a class="del" onclick="del('.$vid.')">&nbsp;</a>';		   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $beginmonth,
		                 $vtruename,
						
						$vtype,
						$vsharesaler,
						 $vzjl,
						$vmembernum,
						$cardsmoney,
						$vallquanity,
						$ordersmoney);
     }
   }
   else
   {     
     $vmembernum  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$beginmonth,
						  $vzjl,
		                 $vtruename,
						$vidcard,
						$vtype,
						$vsharesaler,
						g2u($vmembernum),
						$vstate,
						$vusername,
					
						$vedit   
                             
				          );
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
//显示帐户列表
function selectsaler($sharesalerid)
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