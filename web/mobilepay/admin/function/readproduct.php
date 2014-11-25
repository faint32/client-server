<?
//计算商品数量的吨数
function readproduct($commid){
	 $db = new DB_test;
	 
	 $query = "select fd_kgweight_name,fd_produre_long,fd_produre_width,fd_produre_relation1,fd_produre_relation2,fd_produre_relation3 ,
	                  fd_guige_name
	           from tb_produre 
	           left join tb_kgweight   on fd_kgweight_id = fd_produre_kgweight 
	           left join tb_guige on fd_guige_id = fd_produre_spec 
	           where fd_produre_id = '$commid'";
	 $db->query($query);
	 if($db->nf()){
	   $db->next_record();
	   $kgweight  = $db->f(fd_kgweight_name);
	   $long      = $db->f(fd_produre_long);
	   $width     = $db->f(fd_produre_width);
	   $relation1 = $db->f(fd_produre_relation1);
	   $relation2 = $db->f(fd_produre_relation2);
	   $relation3 = $db->f(fd_produre_relation3);   
	   $spec      = $db->f(fd_guige_name);   
	 }
	 
	 return $kgweight."@@@".$long."@@@".$width."@@@".$relation1."@@@".$relation2."@@@".$relation3."@@@".$spec;
}


?>