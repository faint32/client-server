<?
//require_once('../nusoaplib/nusoap.php');
//require ("../include/common.inc.php");

class AutogetFile {

 function AutogetFileimg($scatid,$dateid)
{ 	
	global $g_showpic,$g_propic;
	$db = new DB_file;
  	$query="select * from tb_upload_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' and fd_cat_dateid<>'0' order by fd_cat_id desc";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		
			$catid		= $db->f(fd_cat_id);
			$filename	= $db->f(fd_cat_name);
			$picname	= $db->f(fd_cat_url);
			$thumrul	= $db->f(fd_cat_thumurl);
			$display	= $db->f(fd_cat_display);
		    $thumrul	= str_replace("../","",$thumrul);
      
		  if(@eregi('http',$thumrul)){
			//  $vpic = $g_propic.$thumrul;
			  $vpic = $thumrul;
			}else{
			  $vpic = $g_propic.$thumrul;
			  //$vpic = $thumrul;
			}
			
			$returnarray= $vpic."@@".$catid."@@".$picname;

		
	}else{     
	      				
	       
			
	}
	return $returnarray;

}

function saveFileid($dateid,$catid)
{   
	$db = new DB_file;
	if($catid)
	{
	
	$query="update tb_upload_category_list set fd_cat_dateid='$dateid'
			where fd_cat_dateid='0' and fd_cat_id in($catid)";
	$db->query($query);	
	//echo $query;
	}
	//return $query;
}


function getmoreFileimg($scatid,$dateid)
{
	global $g_showpic;
	$db = new DB_file;
  	$query="select * from tb_upload_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' order by fd_cat_display desc";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$catid		= $db->f(fd_cat_id);
			$filename	= $db->f(fd_cat_name);
			$picname	= $db->f(fd_cat_url);
			$thumrul	= $db->f(fd_cat_thumurl);
			$display	= $db->f(fd_cat_display);
			
			$thumrul	= str_replace("../","",$thumrul);
			
			$picurl      =  $g_showpic.$thumrul;
		    $arr_list[] = array(
		                "vid"     =>$catid,
						"filename"=>$filename,
						"picname" =>$picname,
						"picurl"  =>$picurl,						
						"display" =>$display
						);
		}
	}else
	{
		$arr_list[] = array(
		                "vid"     =>"",
						"filename"=>"",
						"picname" =>"",
						"picurl"  =>"",						
						"display" =>""
						);
	}
	$returnarray   = $arr_list;
	return $returnarray;
}

}

?>