<?php
//û��ID�ϴ�ͼƬ�Ĳ���
function getimageid($picurl)  //����ϴ�ͼƬID 
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
function Noidshow($fd_cat_id)//��������
{
	$db = new DB_test;	
	$query="select *from tb_category_list where fd_cat_id='$fd_cat_id'";
	$db->query($query);
	if($db->nf()){
	$db->next_record();
	$id=$db->f(fd_cat_id);
	$filename=$db->f(fd_cat_name);
	$url=$db->f(fd_cat_url);
	$thumurl=$db->f(fd_cat_thumurl);
	$display=$db->f(fd_cat_display);
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
function Noidedit($fd_cat_id,$filename,$display=0,$picurl=0,$thumurl=0)
{
	$db = new DB_test;	
	if($picurl)
	{	
			move($dateid,$scatid);//���û��վ����ݺ���	
			//ɾ��ͼƬ
			$query="select *from tb_category_list where fd_cat_dateid='$dateid' and fd_cat_scatid='$scatid'";
			$db->query($query);
			$db->next_record();
			$url=$db->f(fd_cat_url);
			$thumrul=$db->f(fd_cat_thumurl);
			@unlink($url);
			@unlink($thumrul);
			//������Ϣ
			$query="update tb_category_list set fd_cat_name='$filename',
			fd_cat_display='$display',fd_cat_url='$picurl',fd_cat_time=now(),fd_cat_thumurl='$thumurl'
			where fd_cat_id='$fd_cat_id'";
	}else{	
			$query="update tb_category_list set fd_cat_name='$filename',
			fd_cat_display='$display',fd_cat_time=now()
			where fd_cat_id='$fd_cat_id'";
		}
			$db->query($query);
}
function Noiddelete($fd_cat_id)
{
	$db = new DB_test;	
	moves($fd_cat_id);//���û��վ����ݺ���
	$query="delete from tb_category_list  where fd_cat_id='$fd_cat_id'";
	$db->query($query);
	die("<script>alert('ɾ���ɹ�');window.close();</script>");	
}
//���߾�����
function moves($dateid,$scatid)
{	
	$db = new DB_test;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_category_list where fd_cat_id='$fd_cat_id'";
	$db->query($query);
	$db->next_record();
	$fd_cat_dateid=$db->f(fd_cat_dateid);
	$fd_cat_scatid=$db->f(fd_cat_scatid);
	$fd_cat_name=$db->f(fd_cat_name);
	$fd_cat_url=$db->f(fd_cat_url);
	$fd_cat_thumurl=$db->f(fd_cat_thumurl);
	$fd_cat_cancel=$db->f(fd_cat_cancel);
	$query="INSERT INTO tb_old_image (
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