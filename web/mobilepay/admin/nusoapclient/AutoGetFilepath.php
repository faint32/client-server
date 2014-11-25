<?
//require_once('../nusoaplib/nusoap.php');
//require ("../include/common.inc.php");
class AutogetFile {

 function AutogetFileimg($scatid,$dateid)
{
	$dbfile = new DB_file;
  	$query="select * from tb_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' order by fd_cat_display desc";
	$dbfile->query($query);
	if($dbfile->nf())
	{
		while($dbfile->next_record())
		{
			$catid		= $dbfile->f(fd_cat_id);
			$filename	= $dbfile->f(fd_cat_name);
			$picname	= $dbfile->f(fd_cat_url);
			$thumrul	= $dbfile->f(fd_cat_thumurl);
			$display	= $dbfile->f(fd_cat_display);
			
		     $thumrul	= str_replace("../file/","",$thumrul);
			
			$vpic      =  "http://www.ms56.net/managementfile/file/".$thumrul;
		   
		}
	}else
	{
		    $vpic       =  "http://www.ms56.net/managementfile/file/".$thumrul;
	}
//	$dbfile->close();

	$returnarray   = $vpic."@@".$catid;
	return $returnarray;
}
}
function getFilepath($scatid,$dateid)
{
	$dbfile = new DB_file;
  	$query="select * from tb_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' order by fd_cat_display desc";
	$dbfile->query($query);
	if($dbfile->nf())
	{
		while($dbfile->next_record())
		{
			$catid		= $dbfile->f(fd_cat_id);
			$filename	= $dbfile->f(fd_cat_name);
			$picname	= $dbfile->f(fd_cat_url);
			$thumrul	= $dbfile->f(fd_cat_thumurl);
			$display	= $dbfile->f(fd_cat_display);
		    $arr_list[] = array(
		                "vid"     =>$catid,
						"filename"=>$filename,
						"picname" =>$picname,
						"picurl"  =>$thumrul,						
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


function removeFilepath($id)
{
	$dbfile = new DB_file;	
	move($id);//调用回收旧数据函数
  	$query="delete from  tb_category_list where fd_cat_id='$id'";
	$dbfile->query($query);
	//把旧图片移走

	$returnvalue="success";
	return $returnvalue;
}
function displayFilepath($id)  //设为默认 
{
	$dbfile = new DB_file;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_category_list where  fd_cat_id='$id'";
	$dbfile->query($query);
	if($dbfile->nf())
	{
		$dbfile->next_record();
		$dateid=$dbfile->f(fd_cat_dateid);
		$scatid=$dbfile->f(fd_cat_scatid);
	}
    $query="update tb_category_list set fd_cat_display='0' where fd_cat_scatid='$scatid' 
			and fd_cat_dateid ='$dateid' ";
	$dbfile->query($query);	
	
    $query="update tb_category_list set fd_cat_display='1' where fd_cat_id='$id'";
	$dbfile->query($query);//把旧图片移走

	$returnvalue="success";
	return $returnvalue;
}

//$soap = new soap_server();
//$soap->configureWSDL('AutoGetFilepathwsdl', 'urn:AutoGetFilepathwsdl');
//$soap->wsdl->schemaTargetNamespace = 'urn:AutoGetFilepathwsdl';
//
//$soap->register('getFilepath',																						// method
//    array('scatid' => 'xsd:string','dateid' => 'xsd:string'),												// 
//    array('return' => 'xsd:Array'),																		// output parameters
//    'urn:AutoGetFilepathwsdl',																			// namespace
//    'urn:AutoGetFilepathwsdl#getFilepath',															// soapaction
//    'rpc',																												
//    'encoded',																											
//    '读取图片列表'																										
//);
//$soap->register('removeFilepath',																						
//    array('id' => 'xsd:string'),												
//     array('return' => 'xsd:string'),																		
//    'urn:AutoGetFilepathwsdl',																			
//    'urn:AutoGetFilepathwsdl#removeFilepath',															
//    'rpc',																												
//    'encoded',																											
//    '删除图片'																										
//);
//$soap->register('displayFilepath',																						
//    array('id' => 'xsd:string'),												
//     array('return' => 'xsd:string'),																		
//    'urn:AutoGetFilepathwsdl',																			
//    'urn:AutoGetFilepathwsdl#removeFilepath',															
//    'rpc',																												
//    'encoded',																											
//    '设为默认图片'																										
//);
//
//$soap->service($HTTP_RAW_POST_DATA);

//移走旧数据
function move($fd_cat_id)
{	
	$dbfile = new DB_file;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_category_list where  fd_cat_id='$fd_cat_id'";
	$dbfile->query($query);
	$dbfile->next_record();
	$fd_cat_dateid=$dbfile->f(fd_cat_dateid);
	$fd_cat_scatid=$dbfile->f(fd_cat_scatid);
	$fd_cat_name=$dbfile->f(fd_cat_name);
	$fd_cat_url=$dbfile->f(fd_cat_url);
	$fd_cat_thumurl=$dbfile->f(fd_cat_thumurl);
	$fd_cat_cancel=$dbfile->f(fd_cat_cancel);
	$query="INSERT INTO tb_old_image (
						fd_cat_dateid  ,fd_cat_scatid , fd_cat_url,
						fd_cat_cancel ,fd_cat_time,  fd_cat_thumurl,
						fd_cat_name
					  )VALUES (
					   '$fd_cat_dateid'     ,'$fd_cat_scatid'   , '$fd_cat_url',
					   '$fd_cat_cancel'     ,now()              , '$fd_cat_thumurl',
					   '$fd_cat_name'
					  )";
	$dbfile->query($query);
}


?>