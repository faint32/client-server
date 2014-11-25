<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new DB_test;


$query = "select fd_proca_id,fd_proca_catname from tb_procatalog ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$procaid=$db->f(fd_proca_id);
		$procaname=$db->f(fd_proca_catname);
		
		$showcheck.='<font style="width:120px;"><input type="radio" title="'.$procaid.'" name="arr_content[]" onclick="addPreItem(this)" 
		value="'.$procaname.'">'.$procaname.'</font>';
	
	}
}
echo $showcheck;


?>