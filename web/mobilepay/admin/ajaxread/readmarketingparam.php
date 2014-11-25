<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

//$showcheck ='<onclick="selectall();" id="selitmes">全选</a><br>';
if($param=="fd_produre_trademarkid")
{
$query = "select * from tb_trademark order by fd_trademark_name ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$trademarkid=$db->f(fd_trademark_id);
		$trademarkname=$db->f(fd_trademark_name);
		
		$showcheck.='<font style="width:120px;"><input type="checkbox" title="'.$trademarkid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$trademarkname.'">'.$trademarkname.'</font>';
	
	}
}
}else if($param=="fd_produre_level")
{
$query = "select * from tb_productlevel order by fd_productlevel_name ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$productlevelid=$db->f(fd_productlevel_id);
		$productlevelname=$db->f(fd_productlevel_name);
		
		$showcheck.='<font style="width:120px;"><input type="checkbox"  title="'.$productlevelid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$productlevelname.'">'.$productlevelname.'</font>';
	
	}
}
}else if($param=="fd_produre_spec")
{
$query = "select * from tb_guige order by fd_guige_name";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$guigeid=$db->f(fd_guige_id);
		$guigename=$db->f(fd_guige_name);
		
		$showcheck.='<font style="width:80px;"><input type="checkbox" title="'.$guigeid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$guigename.'">'.$guigename.'</font>';
	
	}
}
}else if($param=="fd_produre_kgweight")
{
$query = "select * from tb_kgweight order by fd_kgweight_name";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$kgweightid=$db->f(fd_kgweight_id);
		$kgweightname=$db->f(fd_kgweight_name);
		
		$showcheck.='<font style="width:60px;"><input type="checkbox" title="'.$kgweightid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$kgweightname.'克">'.$kgweightname.'克</font>';
	
	}
}
}else if($param=="fd_produre_catalog")
{
$query = "select * from tb_procatalog order by fd_proca_catname";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$procaid=$db->f(fd_proca_id);
		$procaname=$db->f(fd_proca_catname);
		
		$showcheck.='<font style="width:120px;"><input type="checkbox" title="'.$procaid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$procaname.'">'.$procaname.'</font>';
	
	}
}
}
echo $showcheck;


?>