<?php
class ProPrice{

    /**
     +----------------------------------------------------------
     * 获取商品单价
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $agencyid 机构id
     * @param string $sdcrid 商家id
	 * @param string $produreid 商品id
	 * @param string $producetype 商品类型id
	 * @param string $storageid 商品仓库id
	 * @param string $pricetype 价格类型 （0=本位价格,20=令价,21=吨价)
     +----------------------------------------------------------
     * @return string 
     +----------------------------------------------------------
     */    
	function getsdcrprice($agencyid,$sdcrid,$produreid,$producetype,$storageid,$pricetype=0){
		global $g_lprice_point;
		global $g_dprice_point;

		$db  = new DB_test;
		$query = "select fd_nbsepe_sdcrid,fd_nbsepe_produreid,fd_produre_catalog,fd_nbsepe_lingprice,fd_nbsepe_dunprice,fd_produre_unit
			  from tb_websaleprice left join tb_produre on fd_produre_id = fd_nbsepe_produreid
			  where  fd_nbsepe_iskc = '1'  and   fd_nbsepe_sdcrid = '".$sdcrid."'
			  and fd_nbsepe_produreid  = '".$produreid."' and fd_nbsepe_producetype  = '".$producetype."' 
			  and fd_nbsepe_isshow='1'";
       // echo $query;
		$db->query($query); 
		if($db->nf()){
		  while($db->next_record()){
			  $areaid      = $db->f(fd_nbsepe_sdcrid);
			  $produreid     = $db->f(fd_nbsepe_produreid);
			  $boxprocaid    = $db->f(fd_produre_catalog);
			  $unitid        = $db->f(fd_produre_unit);
			  if($unitid==20)
			  { 
				 $returnvalue = round($db->f(fd_nbsepe_lingprice)*1.04,3);
			  }
			  if($unitid==21)
			  {	 
				  $returnvalue  = round($db->f(fd_nbsepe_dunprice)*1.04,0);
			  }
			  if($pricetype==20)
			  {
				 $returnvalue = round($db->f(fd_nbsepe_lingprice)*1.04,3);
			  }
			  if($pricetype==21)
			  {
				 $returnvalue  = round($db->f(fd_nbsepe_dunprice)*1.04,0); 
			  }
		   }
		 }
		
		$dbshop = new DB_shop;
		$query = "select fd_skqy_lingprice ,fd_skqy_dunprice,fd_skqy_unitid,
			fd_skqy_shopid,fd_skqy_commid  ,fd_skqy_rate 
			from tb_shopkcquantity where fd_skqy_shopid = '$sdcrid' and fd_skqy_commid= '$produreid'
			and fd_skqy_producetype = '$producetype' and fd_skqy_storageid= '$storageid'
			";
  
	   $dbshop->query($query);
	
	   if($dbshop->nf()){
	     while($dbshop->next_record()){
	       $unitid        = $dbshop->f(fd_skqy_unitid);
		   $vlingprice    = $dbshop->f(fd_skqy_lingprice);
		   $vdunprice     = $dbshop->f(fd_skqy_dunprice);
		   $rate          = $dbshop->f(fd_skqy_rate);
		 
		  if($unitid==20){
			  $returnvalue = formatprice($vlingprice*$rate,$g_lprice_point);                	
		  }
		  if($unitid==21){
			  $returnvalue = formatprice($vdunprice*$rate,$g_dprice_point);
		  }
		  if($pricetype==20)
		  {
				 $returnvalue = formatprice($vlingprice*$rate,$g_lprice_point);      
		  }
		 if($pricetype==21)
		  {
				 $returnvalue = formatprice($vdunprice*$rate,$g_dprice_point);
		  }
		  
		
	    }
	  }	
	  
		return $returnvalue+0;
	}



}
?>