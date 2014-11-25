<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

$arr_ihotprocatraid = explode(",",$ihotprocatraid);

$query="select fd_author_id, fd_author_username from tb_author";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$check="";
		$procatradid=$db->f(fd_author_id);
		$protradname=$db->f(fd_author_username);
		for($i=0;$i<count($arr_ihotprocatraid);$i++)
		{
			
			if($arr_ihotprocatraid[$i]==$procatradid)
			{
				$check='checked="true"';
			}
			
		}
		$showcheck.='<div style="width:120px;float:left;"><input type="checkbox" '.$check.' title="'.$procatradid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$protradname.'">'.$protradname.'</div>';
	}
}
$showcheck .="<div style='clear:both;'></div>";
echo $showcheck;


?>