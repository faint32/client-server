<?
//上传图片公用的增删改
function uploadnew($dateid,$fid,$scatid,$picurl,$display,$filename,$thumurl)
{
	$db = new DB_test;	
    if($display==1)
	{
		 $query="update tb_upload_category_list set fd_cat_display='0' where fd_cat_scatid='$scatid' 
					         and fd_cat_dateid ='$dateid' ";
		 $db->query($query);		 
	 }
	$query="INSERT INTO tb_upload_category_list (
			fd_cat_dateid      , fd_cat_fcatid  ,fd_cat_scatid , fd_cat_url,
			fd_cat_display ,fd_cat_time,     fd_cat_name,fd_cat_thumurl
		  )VALUES (
		   '$dateid'           ,'$fid'           ,'$scatid'        , '$picurl',
		   '$display'          ,now()            ,'$filename'      , '$thumurl'
		)";
	 $db->query($query);
}
function uploadshow($fd_cat_id)//显现数据
{
	$db = new DB_test;
	$fd_cat_id=$fd_cat_id+0;
	$query="select * from tb_upload_category_list where fd_cat_id='$fd_cat_id'";
	$db->query($query);
	if($db->nf()){
	$db->next_record();
	$id=$db->f(fd_cat_id);
	$filename=$db->f(fd_cat_name);
	$url=$db->f(fd_cat_url);
	$thumurl=$db->f(fd_cat_thumurl);
	$display=$db->f(fd_cat_display);
	$action="edit";
	}else
	{
	$action="upload";	
	}
	$result=array(
	"id"      =>$id,
	"filename"=>$filename,
	"url"     =>$url,
	"thumurl" =>$thumurl,
	"display" =>$display,
	"action"  =>$action,
	"query"  =>$query,
	);
	return $result;
}
function uploadedit($filename,$display=0,$picurl=0,$thumurl=0,$fd_cat_id)
{
	$db = new DB_test;
	
	$where="where fd_cat_id='$fd_cat_id'";
	
	
	if($picurl)
	{	
			move($fd_cat_id);//调用回收旧数据函数	
			//更新信息
			$query="update tb_upload_category_list set fd_cat_name='$filename',
			fd_cat_display='$display',fd_cat_url='$picurl',fd_cat_time=now(),fd_cat_thumurl='$thumurl' $where";
	}else{	
			$query="update tb_upload_category_list set fd_cat_name='$filename',
			fd_cat_display='$display',fd_cat_time=now() $where";
		}
			$db->query($query);
}
function uploaddelete($fd_cat_id)
{
	$db = new DB_test;	
	move($fd_cat_id);//调用回收旧数据函数
	$query="delete  from tb_upload_category_list where fd_cat_id='$fd_cat_id'";
	$db->query($query);
}
//移走旧数据
function move($fd_cat_id)
{	
	$db = new DB_test;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_upload_category_list  where fd_cat_id='$fd_cat_id'";
	$db->query($query);
	$db->next_record();
	$fd_cat_dateid=$db->f(fd_cat_dateid);
	$fd_cat_scatid=$db->f(fd_cat_scatid);
	$fd_cat_name=$db->f(fd_cat_name);
	$fd_cat_url=$db->f(fd_cat_url);
	$fd_cat_thumurl=$db->f(fd_cat_thumurl);
	$fd_cat_cancel=$db->f(fd_cat_cancel);
	$query="INSERT INTO tb_upload_old_image (
						fd_cat_dateid  ,fd_cat_scatid , fd_cat_url,
						fd_cat_cancel ,fd_cat_time,  fd_cat_thumurl,
						fd_cat_name
					   )VALUES (
					   '$fd_cat_dateid'     ,'$fd_cat_scatid'   , '$fd_cat_url',
					   '$fd_cat_cancel'     ,now()              , '$fd_cat_thumurl',
					   '$fd_cat_name'
					   )";
	$db->query($query);
}
?>