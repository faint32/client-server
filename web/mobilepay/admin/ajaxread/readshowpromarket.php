<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;
$arr_toptradmarkid = explode(",",$toptradmarkid);

$query = "select fd_trademark_name,fd_procatrad_id,fd_trademark_id,fd_proca_catname,fd_procatrad_id,fd_proca_id,fd_procatrad_commjs,
   							 fd_procatrad_tradjs,fd_procatrad_commcs from web_conf_procatrademark
	                            left join tb_trademark on fd_trademark_id = fd_procatrad_trademarkid
	                            left join tb_procatalog on fd_proca_id = fd_procatrad_procaid
                             where fd_procatrad_procaid in($procaid) group by fd_trademark_id";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$trademarkid=$db->f(fd_trademark_id);
		$trademarkname=$db->f(fd_trademark_name);
			for($i=0;$i<count($arr_toptradmarkid);$i++)
		{
			if($arr_toptradmarkid[$i]==$trademarkid)
			{
				
				$check='checked="true"';
			}else{
				$check="";
			}
		}
		
		$showcheck.='<div style="width:120px;float:left;"><input type="checkbox" '.$check.' title="'.$trademarkid.'" name="arr_count[]" onclick="addPreItem()" 
		value="'.$trademarkname.'">'.$trademarkname.'</div>';
	
	}
}
echo $showcheck;


?>