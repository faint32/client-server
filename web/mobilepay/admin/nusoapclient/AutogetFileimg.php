<?
//require_once('../nusoaplib/nusoap.php');
//require ("../include/common.inc.php");
class AutogetFile {
 function AutogetFileimg($scatid,$dateid)
{
	global $g_showpic;
	$dbfile = new DB_file;
  	$query="select * from tb_category_list where fd_cat_scatid='$scatid' and fd_cat_dateid = '$dateid' and fd_cat_dateid<>'0' order by fd_cat_display desc";
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
			$vpic       = $g_showpic.$thumrul;
			$returnarray= $vpic."@@".$catid;
		
	}else
	
	{     
	      
	        $thisclass =  new oldimgfile(); //��ʼ����ʵ�� 
		    $returnarray  = $thisclass->readoldfiles($scatid,$dateid);
	}
	return $returnarray;
}

function saveFileid($dateid,$catid)
{   
	$dbfile = new DB_file;
	if($catid)
	{
	
	$query="update tb_category_list set fd_cat_dateid='$dateid'
			where fd_cat_dateid='0' and fd_cat_id in($catid)";
	$dbfile->query($query);	
	//echo $query;
	}
	//return $query;
}

function getmoreFileimg($scatid,$dateid)
{
	global $g_showpic;
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
		 $thisclass =  new oldimgfile(); //��ʼ����ʵ�� 
		 $arr_list  = $thisclass->readoldfiles($scatid,$dateid);
		 if($arr_list=="")
		 {
			  $arr_list[] = array(
		                "vid"     =>$catid,
						"filename"=>$filename,
						"picname" =>$picname,
						"picurl"  =>$picurl,						
						"display" =>$display
						);
		 }
	}
	$returnarray   = $arr_list;
	return $returnarray;
}
}

class oldimgfile
{
function readoldfiles($scatid,$id)
{
  $db = new DB_test;
  global $g_propic;
  switch($scatid)
  {
	  case "1":   //��Ա- ��ҵӪҵִ��
	  		  $query = "select fd_organmem_yyfile from tb_organmem where fd_organmem_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_organmem_yyfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	  case "2":   //��Ա- ˰��Ǽ�֤
	  		  $query = "select fd_organmem_swfile from tb_organmem where fd_organmem_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_organmem_swfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "3":  //��Ա- һ����˰���ʸ�֤
	   		  $query = "select fd_organmem_fpfile from tb_organmem where fd_organmem_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_organmem_fpfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "4":  //��Ա- ��Ʊ����ͼƬ
	   		  $query = "select fd_organmem_fpfile from tb_organmem where fd_organmem_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_organmem_fpfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "6":  //����- ������֤��
	   		  $query = "select fd_ks_hegfile from web_kesu where fd_ks_id = '$id' " ;
			  $db->query($query);
			  
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul     = $db->f(fd_ks_hegfile);          //id��   
			    $arr_thumrul = explode("@@@",$thumrul);
				for($i=0;$i<count($arr_thumrul);$i++)
				{
					if($arr_thumrul[$i]!="")
					{
						$arr_list[]['picurl'] = $g_propic.$arr_thumrul[$i];
					}
				}
			    $vpic        =  $arr_list;
			  }
	 
	  break;
	   case "7":  //����- ��Ʒ��ͼƬ
	   		  $query = "select fd_ks_ksfile from web_kesu where fd_ks_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_ks_ksfile);          //id��   
			    $arr_thumrul = explode("@@@",$thumrul);
				for($i=0;$i<count($arr_thumrul);$i++)
				{
					if($arr_thumrul[$i]!="")
					{
						$arr_list[]['picurl'] = $g_propic.$arr_thumrul[$i];
					}
				}
			    $vpic        =  $arr_list;
			  }
	  break;
	   case "11": //��Ʊ����- Ӫҵִ��
	   		$query = "select fd_fpsave_newyyfile from web_fpsave where fd_fpsave_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_fpsave_newyyfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "12": //��Ʊ����- ˰��Ǽ�֤
	   		$query = "select fd_fpsave_newswfile from web_fpsave where fd_fpsave_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_fpsave_newswfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "13": //��Ʊ����- һ����˰���ʸ�֤
	   		$query = "select fd_fpsave_newbankfile from web_fpsave where fd_fpsave_id = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_ks_newbankfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "14": //��Ʊ����- ��Ʊ����ͼƬ
	   		  $query = "select fd_fpsave_newfpfile from web_fpsave where fd_fpsave_id  = '$id' " ;
			  $db->query($query);
			  if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
				$db->next_record();                   //��ȡ��¼���� 
				$thumrul    = $db->f(fd_fpsave_newfpfile);          //id��   
			    $thumrul	= str_replace("../","",$thumrul);
			    $vpic       =  $thumrul;
			  }
	  break;
	   case "15": //����-���֤���� 
	  break;
  }
      if($vpic<>"" && !is_array($vpic)) 
	   {
		   $vpic = $g_propic.$vpic;
		}
	   return $vpic;
	   
	}

}
function removeFilepath($id)
{
	$dbfile = new DB_file;	
	move($id);//���û��վ����ݺ���
  	$query="delete from  tb_category_list where fd_cat_id='$id'";
	$dbfile->query($query);
	//�Ѿ�ͼƬ����

	$returnvalue="success";
	return $returnvalue;
}
function displayFilepath($id)  //��ΪĬ�� 
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
	$dbfile->query($query);//�Ѿ�ͼƬ����

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
//    '��ȡͼƬ�б�'																										
//);
//$soap->register('removeFilepath',																						
//    array('id' => 'xsd:string'),												
//     array('return' => 'xsd:string'),																		
//    'urn:AutoGetFilepathwsdl',																			
//    'urn:AutoGetFilepathwsdl#removeFilepath',															
//    'rpc',																												
//    'encoded',																											
//    'ɾ��ͼƬ'																										
//);
//$soap->register('displayFilepath',																						
//    array('id' => 'xsd:string'),												
//     array('return' => 'xsd:string'),																		
//    'urn:AutoGetFilepathwsdl',																			
//    'urn:AutoGetFilepathwsdl#removeFilepath',															
//    'rpc',																												
//    'encoded',																											
//    '��ΪĬ��ͼƬ'																										
//);
//
//$soap->service($HTTP_RAW_POST_DATA);

//���߾�����
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