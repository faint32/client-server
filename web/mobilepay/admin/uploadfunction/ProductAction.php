<?
function addelete($dateid,$scatid,$display)//��Ӳ�Ʒʱ,��������
{
	$db = new DB_test;	
	if($display==1)
	 {
		 $query="update tb_category_list set fd_cat_display='0' where fd_cat_scatid='$scatid' 
				 and fd_cat_dateid ='$dateid' ";
		 $db->query($query);	
	 }
}s
?>