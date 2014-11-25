<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
session_start();

$aColumns = array("","fd_saler_truename");
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
$query = "select * from  web_salerrewards where fd_rewards_type  = '1'";   //激活提成制度
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $cardmoney      = $db->f(fd_rewards_selfmoney);
		$cardbmonth      = $db->f(fd_rewards_bmonth);
		$cardemonth      = $db->f(fd_rewards_emonth);
	}}
$query = "select * from  web_salerrewards where fd_rewards_type  = '2'";   //销售提成
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $ordermoney      = $db->f(fd_rewards_selfmoney);  
		$orderbmonth      = $db->f(fd_rewards_bmonth); 
		$orderemonth      = $db->f(fd_rewards_emonth);      
	}}
$state="已发货";
$state=u2g($state);
//开卡数有效记录
$query = "select fd_salercard_salerid,count(*) as counts,month(fd_salercard_opendate) as monthdate from web_salercard where 
fd_salercard_memberid >0 and fd_salercard_state='1' and fd_salercard_opendate like '%$beginmonth%'  
and fd_salercard_opendate >='$cardbmonth' and fd_salercard_opendate <='$cardemonth'
group by fd_salercard_salerid,monthdate  ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $id                 = $db->f(fd_salercard_salerid);            //id号  
	   $month              = $db->f(monthdate); 
       $arr_membercounts[$id][$month]  = g2u($db->f(counts)); 
	   
	       
}}
$query = "select fd_salercard_salerid ,sum(fd_order_alldunshu) as allquantity  ,month(fd_order_date) as monthdate 
          from web_order
          left join web_salercard on fd_salercard_memberid = fd_order_memeberid 
		  and fd_order_state='$state' 
		  and fd_order_date >='$orderbmonth' and fd_order_date <='$orderemonth'  
		  where fd_order_alldunshu>'0' and fd_order_date  like '%$beginmonth%' group by fd_salercard_salerid,monthdate ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $tmpsalerid                                      = $db->f(fd_salercard_salerid); 
	   $arr_salerid[]                                   = $tmpsalerid;
 	   $tmpmonth                                        = $db->f(monthdate);            
	   $varr_allquantity[$tmpsalerid][$tmpmonth]        = $db->f(allquantity)+0;    
	}}
	//echo $query;
$query = "select * from  web_salerrewards where fd_rewards_type  = '1'";   //激活提成制度
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $upcardmoney      = $db->f(fd_rewards_upmoney);
	}}
$query = "select * from  web_salerrewards where fd_rewards_type  = '2'";   //销售提成
$db->query($query);
$count=0;//记录数
$vallquantity=0;//总价
if($db->nf()){
	while($db->next_record()){		        
        $upordermoney      = $db->f(fd_rewards_upmoney);      
	}}
//开卡数有效记录
$query = "select fd_saler_sharesalerid,count(*) as counts ,month(fd_salercard_opendate) as monthdate from web_salercard 
left join web_saler on fd_salercard_salerid = fd_saler_id  
where fd_salercard_memberid >0 and fd_salercard_state='1' 
and fd_salercard_opendate >='$cardbmonth' and fd_salercard_opendate <='$cardemonth'   and fd_salercard_opendate like '%$beginmonth%'   
group by fd_saler_sharesalerid ,monthdate ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $id                 = $db->f(fd_saler_sharesalerid);            //id号  
       $month              = $db->f(monthdate); 
       $arr_upmembercounts[$id][$month]  = g2u($db->f(counts));  
	       
}}
$query = "select fd_saler_sharesalerid ,sum(fd_order_alldunshu) as allquantity ,month(fd_order_date) as monthdate  
          from web_order
          left join web_salercard on fd_salercard_memberid = fd_order_memeberid  
		  left join web_saler on fd_salercard_salerid = fd_saler_id 
		  where fd_order_alldunshu>'0' and fd_order_date  like '%$beginmonth%'  
		  and fd_order_state='$state' 
		  and fd_order_date >='$orderbmonth' and fd_order_date <='$orderemonth' group by monthdate ,fd_saler_sharesalerid ";
$db->query($query);

if($db->nf())
{
	while($db->next_record())
	{
	   $tmpsalerid                                      = $db->f(fd_saler_sharesalerid); 
	   $tmpmonth                                        = $db->f(monthdate);            
	   $varr_upallquantity[$tmpsalerid][$tmpmonth]      = $db->f(allquantity)+0;  
	   $arr_salerid[]                                   = $tmpsalerid;     
	}}	
	
if(!empty($type))
{
	$querywhere = " and fd_saler_salertype = '$type'";
}
$arr_month=explode("-",$beginmonth);
$s_month = $arr_month[1]*1;

$arr_id = explode(",",implode(",",array_unique($arr_salerid)));
for($i=0;$i<count($arr_id);$i++)
{
	$querywhere2= " and fd_saler_id = '$arr_id[$i]'";
$query = "select * from  web_saler  where 1 $querywhere2  $querywhere $sWhere  ";
$db->query($query);
$totoalcount=$db->nf()+0;	
//$query .= " limit $iDisplayStart,$iDisplayLength ";
//$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	  // echo $query;
	   $vid             = $db->f(fd_saler_id);            //id号  
       $vtruename       = g2u($db->f(fd_saler_truename));       
       $vidcard         = g2u($db->f(fd_saler_idcard)); 
       $vtjman          = g2u($db->f(fd_saler_tjman));
	   $vphone          = g2u($db->f(fd_saler_phone));
	   $vusername       = g2u($db->f(fd_saler_username));
	   $vzjl            = g2u($db->f(fd_saler_zjl));
	   $vpassword       = g2u($db->f(fd_saler_password));
	  
       $vsharesaler     = g2u(selectsaler($db->f(fd_saler_sharesalerid)));
	   $vtype           = g2u($db->f(fd_saler_salertype));
	   $vstate          = $db->f(fd_saler_state);
	   $vallquanity     =  $varr_allquantity[$vid][$s_month]+0; 
	   $vmembernum      = g2u($arr_membercounts[$vid][$s_month])+0;
	   $valldownquanity     =  $varr_upallquantity[$vid][$s_month]+0; 
	   $vdownmembernum      = g2u($arr_upmembercounts[$vid][$s_month])+0;
	    if($vtype==1)
	   {
		   $vtype="网导员";
	   }else if($vtype==2)
	   {
		   $vtype="网导经理";
	   }
	   $vtype=trim($vtype);
	   
	   
	   $cardsmoney =$cardmoney*$vmembernum;
	   $ordersmoney=$ordermoney*$vallquanity;
	   
	   $downcardsmoney =$upcardmoney*$vdownmembernum;
	   $downordersmoney=$upordermoney*$valldownquanity;
	   $allmoney=0;
	   $allmoney = $cardsmoney+$ordersmoney+$downcardsmoney+$downordersmoney;
	   //if($j==1) $money=$upmoney;
	   
	 
			   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                       "DT_RowClass" => "",
						$beginmonth,
		                $vtruename,
					    $vtype    ,
						$vmembernum,
						$cardsmoney,
						$vallquanity,
						$ordersmoney,
						$vdownmembernum,
						$downcardsmoney,
						$valldownquanity,
						$downordersmoney,
						$allmoney);   
     }
   }
}
 if($arr_list=="")
   {     
     $vmembernum  = "";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						 $beginmonth,
		                 $vtruename,
						
						$vtype,
						$vsharesaler,
						 $vzjl,
						 $vtype,
						$vsharesaler,
						 $vzjl,
						g2u($vmembernum) ,
						$cardsmoney,
						$vallquanity,
						$ordersmoney 
                             
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