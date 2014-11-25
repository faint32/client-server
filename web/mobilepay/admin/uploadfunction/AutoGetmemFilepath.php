<?
require_once('../nusoaplib/nusoap.php');
require ("../include/common.q.inc.php");

function getmemkpFilepath($fcatid)  //获得会员开票所有的证书信息 
{
	$db = new DB_test;
	
	$query="select * from tb_scategoty where fd_scat_fcatid='$fcatid'";
		$db->query($query);
		if($db->nf())
		{
		while($db->next_record())
		{	//没有上传显示空白
			$arr_scatid[]=$db->f(fd_scat_id);
			$arr_fcatid[]=$db->f(fd_scat_fcatid);
		}
		}
	for($i=0;$i<count($arr_scatid);$i++)
	{	
  	$query="select * from tb_category_list where fd_cat_scatid='$arr_scatid[$i]'";
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
					    "scatid"  =>$arr_scatid[$i],
						"filename"=>$filename,
						"picname" =>$picname,
						"picurl"  =>$thumrul,						
						"display" =>$display
						);
		}
	}else
	{
		 $arr_list[] = array(
		                "vid"     => "",
					    "scatid"  => $arr_scatid[$i],
						"filename"=> "",
						"picname" => "",
						"picurl"  => "",						
						"display" => ""
						);
		
	}
	}
	$returnarray   = $arr_list;
	return $returnarray;
}



$soap = new soap_server();
$soap->configureWSDL('AutoGetFilepathwsdl', 'urn:AutoGetFilepathwsdl');
$soap->wsdl->schemaTargetNamespace = 'urn:AutoGetFilepathwsdl';

$soap->register('getmemkpFilepath',																						// method
    array('fcatid' => 'xsd:string'),												// 
    array('return' => 'xsd:Array'),																		// output parameters
    'urn:AutoGetFilepathwsdl',																			// namespace
    'urn:AutoGetFilepathwsdl#getmemkpFilepath',															// soapaction
    'rpc',																												
    'encoded',																											
    '读取图片列表'																										
);

$soap->service($HTTP_RAW_POST_DATA);


?>