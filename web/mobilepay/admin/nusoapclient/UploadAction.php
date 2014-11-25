<?
//上传图片公用的增删改

function uploadshow($dateid,$scatid)//显现数据
{
	$dbfile = new DB_file;	
	$query="select *from tb_category_list where fd_cat_dateid='$dateid' and fd_cat_scatid='$scatid'";
	$dbfile->query($query);
	if($dbfile->nf()){
	$dbfile->next_record();
	$id=$dbfile->f(fd_cat_id);
	$filename=$dbfile->f(fd_cat_name);
	$url=$dbfile->f(fd_cat_url);
	$thumurl=$dbfile->f(fd_cat_thumurl);
	$display=$dbfile->f(fd_cat_display);
	$action="edit";
	}
	$result=array(
	"id"      =>$id,
	"filename"=>$filename,
	"url"     =>$url,
	"thumurl" =>$thumurl,
	"display" =>$display,
	"action"  =>$action,
	);
	return $result;
}

?>