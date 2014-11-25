<?
function addelete($dateid,$scatid)//删除广告上传图片
{
	$db = new DB_test;	
	clearFilepath($dateid);
	//删除图片
	$query="select *from tb_category_list where fd_cat_dateid='$dateid' and fd_cat_scatid='$scatid'";
	$db->query($query);
	$db->next_record();
	$url=$db->f(fd_cat_url);
	$thumurl=$db->f(fd_cat_thumurl);
	@unlink($url);
	@unlink($thumurl);
	//删除数据
	$del="delete from tb_category_list  where fd_cat_dateid='$dateid' and fd_cat_scatid='$scatid'";
	$db->query($del);
}
function clearFilepath($id)
{
	$db = new DB_test;	
  	$query="update web_conf_ggw set  fd_ggwgl_url='',fd_ggwgl_thumurl='' where fd_ggwgl_id='$id'";
	$db->query($query);
	$returnvalue="success";
	return $returnvalue;
}
?>