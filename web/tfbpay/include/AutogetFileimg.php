<?
//require_once('../nusoaplib/nusoap.php');
//require ("../include/common.inc.php");
class AutogetFile {
 function AutogetFileimg($scatid,$dateid)
{
	
	//global "";
	global $g_propic;
	$dbfile = new db_test;
  	$query="select * from tb_upload_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' and fd_cat_dateid<>'0' 
	        order by fd_cat_id desc ";
	$dbfile->query($query);
	if($dbfile->nf())
	{
		    $dbfile->next_record();
			$catid		= $dbfile->f(fd_cat_id);
			$filename	= $dbfile->f(fd_cat_name);
			$picname	= $dbfile->f(fd_cat_url);
			$thumrul	= $dbfile->f(fd_cat_thumurl);
			$display	= $dbfile->f(fd_cat_display);
		    $thumrul	= str_replace("../","",$thumrul);
			$vpic       = "".$thumrul;
			$returnarray= $vpic."@@".$catid;
		
	}
	if($returnarray=="" and ($scatid==5 or $scatid ==35))
	{
		$returnarray = $g_propic."images/null.jpg@@";
	}
	return $returnarray;
}

 function AutogetFileimg_id($scatid,$id)
{
	
	//global "";
	global $g_propic;
	$dbfile = new db_test;
  	$query="select * from tb_upload_category_list where fd_cat_scatid='$scatid' and fd_cat_id = '$id'";
	$dbfile->query($query);
	if($dbfile->nf())
	{
		  $dbfile->next_record();
			$catid		= $dbfile->f(fd_cat_id);
			$filename	= $dbfile->f(fd_cat_name);
			$picname	= $dbfile->f(fd_cat_url);
			$thumrul	= $dbfile->f(fd_cat_thumurl);
			$display	= $dbfile->f(fd_cat_display);
		    $thumrul	= str_replace("../","",$thumrul);
			$vpic       = "".$thumrul;
			$returnarray= $vpic."@@".$catid;
		
	}else
	
	{     
	      
	        $thisclass =  new oldimgfile(); //��ʼ����ʵ�� 
		    $returnarray  = $thisclass->readoldfiles($scatid,$dateid);
			
	}
	if($returnarray=="" and ($scatid==5 or $scatid ==35))
	{
		$returnarray = $g_propic."images/null.jpg@@";
	}
	return $returnarray;
}

function saveFileid($dateid,$catid)
{   
	$dbfile = new db_test;
	if($catid)
	{
	
	$query="update tb_upload_category_list set fd_cat_dateid='$dateid'
			where fd_cat_dateid='0' and fd_cat_id in($catid)";
	$dbfile->query($query);	
	//echo $query;
	}
	//return $query;
}

function getggimg($scatid,$dateid)//��ȡ���λͼƬ
{
	$db = new DB_test;
	$arr_img  = explode("@@",$this->AutogetFileimg($scatid,$dateid) ); //��ѯ���
	
	$query = "select fd_ggwgl_link from tb_upload_ggwgl where fd_ggwgl_id = '$dateid'";
	
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$ggurl        = $db->f(fd_ggwgl_link);
		
	}
	return $arr_img[0]."@@".$ggurl;
}
function getmoreFileimg($scatid,$dateid,$limitnum = 6)
{
	global $g_showpic;
	$db = new DB_test;
	if($dateid)
	{
		$querywhere = " and fd_cat_dateid='$dateid'";
	}else
	{
		$queryorder = " limit 0,$limitnum";
	}
  	$query="select fd_cat_thumurl as thumrul,fd_cat_urllink as urllink,fd_cat_no as no,fd_cat_id as id,fd_cat_url as url,fd_cat_id as id,fd_cat_name as content,fd_cat_display as display from tb_upload_category_list where fd_cat_scatid='$scatid' $querywhere  order by fd_cat_no asc $queryorder";
	if ($db->execute($query)) {
			$arr_yewuval =  $db->get_all($query); 
	}
 // echo $query;
	return $arr_yewuval;
}
}


?>