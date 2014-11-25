<?
function clearFilepath($id)
{
	$db = new DB_test;		
  	$query="update tb_ggwgl set  fd_ggwgl_url='',fd_ggwgl_thumurl='' where fd_ggwgl_id='$id'";
	$db->query($query);
	$returnvalue="success";
	return $returnvalue;
}
?>