<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$query = "select fd_paycard_salerid,count(*) as counts from tb_paycard  group by fd_paycard_salerid  ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $id                     = $db->f(fd_paycard_salerid);            //id号  
       $arr_membercounts[$id]  = g2u($db->f(counts));  
	       
	}}
$aColumns = array("","a.fd_saler_truename","a.fd_saler_username","a.fd_saler_phone","b.fd_saler_truename",'a.fd_saler_zjl');



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

	
$query = "SELECT 1 FROM `tb_saler` as a left join tb_saler as b on a.fd_saler_sharesalerid = b.fd_saler_id where 1 
								$sWhere  ";
   $db->query($query);
   $totoalcount=$db->nf()+0;	
$count=0;
$query = "SELECT * FROM  tb_saler 
		 left join tb_salerlevel on fd_salerlevel_id=fd_saler_level
		 where 1 $sWhere limit $iDisplayStart,$iDisplayLength ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	   $vid             = $db->f(fd_saler_id);            //id号  
       $vtruename       = g2u($db->f(fd_saler_truename));       
       $vidcard         = g2u($db->f(fd_saler_idcard)); 
    
	   $vphone          = g2u($db->f(fd_saler_phone));
	   $vusername       = g2u($db->f(fd_saler_username));
       $vsalerlevel     = g2u($db->f(fd_salerlevel_name));
       $vstate          = $db->f(fd_saler_state);

		$vpaycardnum=paycardnum($vid);
	   if($vstate==0)
	   {
		   $vstate="初始";
	   }else if($vstate==1)
	   {
		   $vstate="正常";
	   }
	   else if($vstate==-1)
	   {
		   $vstate="冻结";
	   }
	    $vstate=($vstate);
	   $vedit =  '<a class="edit" onclick="edit('.$vid.')">编辑</a><a class="del" onclick="del('.$vid.')">删除</a>';		   
	   $arr_list[] = array(
	                   "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
					
		               $vtruename,
						$vidcard,
						$vpaycardnum,
						$vsalerlevel,
						$vstate,
						$vusername,
						$vphone,
						g2u($vedit));
     }
   }
   else
   {     
     $vmembernum  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
						$vtruename,
						$vidcard,
						$vpaycardnum,
						$vsalerlevel,
						$vstate,
						$vusername,
						$vphone,
						$vedit);
   }
        $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);
//显示帐户列表
function paycardnum($salerid)
{
$db  = new DB_test;

$query = "select * from tb_paycard where fd_paycard_salerid='$sharesalerid'" ;
$db->query($query);

return $db->nf();
}
?>