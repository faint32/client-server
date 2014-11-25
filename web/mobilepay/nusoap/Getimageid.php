<?php
//没有ID上传图片的操作
function getimageid($picurl)  //获得上传图片ID 
{
	$db = new DB_test;
    $query="select fd_cat_id from tb_category_list where  fd_cat_url ='$picurl'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$id=$db->f(fd_cat_id);
	}
	
	return $id;
}
?>