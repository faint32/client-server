<?      

require ("../include/config.inc.php");

$db     = new DB_test;  
$db2    = new DB_test;  
$dberp  = new DB_erp;	

$sdcrid = 1;
       
$query  = "select sum(fd_orderdetail_quantity) as quantity,fd_orderdetail_icommid,fd_orderdetail_icommbar,fd_orderdetail_icommname,
           fd_orderdetail_fqmoney,fd_orderdetail_price,fd_produre_relation3,fd_orderdetail_storageid,fd_orderdetail_productid
           from web_orderdetail 
           left join tb_produre on fd_orderdetail_icommid = fd_produre_id
           where fd_orderdetail_orderid = '4615'
           group by fd_orderdetail_icommid
          ";
$db->query($query); 
if($db->nf()){
  while($db->next_record()){
  	   $quantity = $db->f(quantity);	        	   
  	   $commid    = $db->f(fd_orderdetail_icommid);
  	   $commbar   = $db->f(fd_orderdetail_icommbar);
  	   $commname  = $db->f(fd_orderdetail_icommname);
  	   $fqmoney   = $db->f(fd_orderdetail_fqmoney);
  	   $price     = $db->f(fd_orderdetail_price);
  	   
  	   $ocommid   = $db->f(fd_orderdetail_productid);
  	   	        	  	        	   
  	   $relation3 = $db->f(fd_produre_relation3); 
  	   
  	   $storageid = $db->f(fd_orderdetail_storageid);
  	    
       $dunshu = changekg2($relation3 , '令' , $quantity);                 
       $money = ($price+$fqmoney)*$dunshu;
       $price = round($money/$quantity,3);
  	   
  	   //判断成本价
  	   $query = "select * from tb_storagecost 
  	             where fd_sect_commid = '$commid' and fd_sect_organid = '1' and fd_sect_sdcrid = '$sdcrid'";
  	   echo $query."<br>";
  	   $dberp->query($query);
  	   if(!$dberp->nf()){
  	     $query = "select * from tb_storagecost 
  	               where fd_sect_commid = '$ocommid' and fd_sect_organid = '1' and fd_sect_sdcrid = '$sdcrid'";
  	     $dberp->query($query);  
  	     if($dberp->nf()){
  	     	 $dberp->next_record();
  	     	 $ocost = $dberp->f(fd_sect_cost);
  	     	 $costmoney = ($ocost+$fqmoney)*$dunshu;
  	     	 $cost = round($costmoney/$quantity,3);
  	       
  	       $query="INSERT INTO tb_storagecost (
                   fd_sect_organid      , fd_sect_sdcrid  , fd_sect_commid   ,
                   fd_sect_cost   
                   )VALUES (
                   '1'                  , '$sdcrid'       , '$commid'        , 
                   '$cost'         
                   )";
           echo $query."<br>";
           $dberp->query($query);	
  	     }        
  	   }
	    
  }
}

//计算商品数量的吨数
function changekg2($relation3 , $unit , $quantity){
	switch($unit){
		case "令":
		     $kg = $quantity * $relation3;  //一令有多小千克
		     $dunquantity = $kg/1000;
		     break;
		case "千克":
		     $dunquantity = $quantity/1000;
		     break;
		case "吨":
		     $dunquantity = $quantity;
		     break;
	}
  return $dunquantity ;
}
?>	      