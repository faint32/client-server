<?   
require("../include/common.inc.php");
require("../include/json.php");
header('Content-Type:text/html;charset=utf-8'); 
$db=new db_test;

$query = "select * from web_i_procahotshop  where fd_phs_areaid = '$areaid' ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $fd_phs_procaid = $db->f(fd_phs_procaid);
   	     $fd_phs_procaname= g2u($db->f(fd_phs_procaname));
         $select[] = array("id"=>$fd_phs_procaid,"title"=>$fd_phs_procaname); 
  }
}
if(empty($select))
{
	 $select[] = array("id"=>"","title"=>""); 
}
echo json_encode($select);

?>