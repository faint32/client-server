<?
require_once('../nusoaplib/nusoap.php');
require ("../include/common.q.inc.php");

function getFilepath($scatid,$dateid)
{
	$db = new DB_test;
  	$query="select * from tb_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' order by fd_cat_display desc";
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
	$db = new DB_test;	
	move($id);//调用回收旧数据函数
  	$query="delete from  tb_category_list where fd_cat_id='$id'";
	$db->query($query);
	//把旧图片移走

	$returnvalue="success";
	return $returnvalue;
}
function displayFilepath($id)  //设为默认 
{
	$db = new DB_test;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_category_list where  fd_cat_id='$id'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$dateid=$db->f(fd_cat_dateid);
		$scatid=$db->f(fd_cat_scatid);
	}
    $query="update tb_category_list set fd_cat_display='0' where fd_cat_scatid='$scatid' 
			and fd_cat_dateid ='$dateid' ";
	$db->query($query);	
	
    $query="update tb_category_list set fd_cat_display='1' where fd_cat_id='$id'";
	$db->query($query);//把旧图片移走

	$returnvalue="success";
	return $returnvalue;
}

$soap = new soap_server();
$soap->configureWSDL('AutoGetFilepathwsdl', 'urn:AutoGetFilepathwsdl');
$soap->wsdl->schemaTargetNamespace = 'urn:AutoGetFilepathwsdl';

$soap->register('getFilepath',																						// method
    array('scatid' => 'xsd:string','dateid' => 'xsd:string'),												// 
    array('return' => 'xsd:Array'),																		// output parameters
    'urn:AutoGetFilepathwsdl',																			// namespace
    'urn:AutoGetFilepathwsdl#getFilepath',															// soapaction
    'rpc',																												
    'encoded',																											
    '读取图片列表'																										
);
$soap->register('removeFilepath',																						
    array('id' => 'xsd:string'),												
     array('return' => 'xsd:string'),																		
    'urn:AutoGetFilepathwsdl',																			
    'urn:AutoGetFilepathwsdl#removeFilepath',															
    'rpc',																												
    'encoded',																											
    '删除图片'																										
);
$soap->register('displayFilepath',																						
    array('id' => 'xsd:string'),												
     array('return' => 'xsd:string'),																		
    'urn:AutoGetFilepathwsdl',																			
    'urn:AutoGetFilepathwsdl#removeFilepath',															
    'rpc',																												
    'encoded',																											
    '设为默认图片'																										
);

$soap->service($HTTP_RAW_POST_DATA);

//移走旧数据
function move($fd_cat_id)
{	
	$db = new DB_test;
	$query="select fd_cat_dateid,fd_cat_name,fd_cat_scatid,fd_cat_url,fd_cat_thumurl ,fd_cat_cancel from tb_category_list where  fd_cat_id='$fd_cat_id'";
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