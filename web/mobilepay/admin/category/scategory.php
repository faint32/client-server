<?
$thismenucode = "1c102";
require ("../include/common.inc.php");
echo $action;
$db = new DB_test;
$num="";
$gourl = "tb_upload_scategory_b.php" ;
$gotourl = $gourl.$tempurl ;
$dir="../file/";	
switch ($action)
{
  case "delete":   // ��¼ɾ��
 
	$query = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";  
	$db->query($query);
	$db->next_record();
	$folder=$db->f(fd_fcat_foldername);
	$dir .=$folder.'/';
	$dir .=$foldername;
	$result=file_exit($dir);
	 if($result)
	 {
		$error = "�ļ���������ͼƬ,����ɾ��!";
		
	 }else{
     $query="delete from tb_upload_scategoty where fd_scat_id='$id'";
     $db->query($query);
	   @rmdir($dir);
     Header("Location: $gotourl");  
	}     
	break;
  case "new": 
        //�����¼
       $query = "select * from tb_upload_scategoty where fd_scat_name = '$name' and fd_scat_foldername='$foldername' ";
      $db->query($query);
      if($db->nf()){  
      	$error = "����������ļ����������ظ�,���֤!";  
      }else {
        $query="INSERT INTO tb_upload_scategoty (
 		           fd_scat_name      , fd_scat_foldername   ,fd_scat_fcatid ,
 		           fd_scat_display   ,fd_scat_moreupload    ,fd_scat_time
  		          )VALUES (
  		         '$name'          , '$foldername'              ,'$fid' ,
				   '$display'  ,$upload    ,   now()
				)";
				
		    $db->query($query);
			//��ȡ�����ļ���
			$query = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";  
			$db->query($query);
			$db->next_record();
			$folder=$db->f(fd_fcat_foldername);
			$dir .=$folder.'/';
			//�����ļ��е�·�� 
			$dir .=$foldername;
			@mkdirs($dir);//�����ļ���
			@chmod($dir,0777);
		    Header("Location: $gotourl");
	 }
	$action="";	   	
	break;
  case "edit":   // �޸ļ�¼
  		
      $query = "select * from tb_upload_scategoty where (fd_scat_name = '$name' or fd_scat_foldername = '$foldername' ) and fd_scat_id != '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "����������ظ������ļ��������޸�,���֤!";  
		  }else{
         $query = "update tb_upload_scategoty set
 		              fd_scat_name   = '$name'    ,  fd_scat_foldername    = '$foldername' , 
					        fd_scat_fcatid    = '$fid'   , fd_scat_display    ='$display',
					        fd_scat_moreupload ='$upload'
  		            where fd_scat_id = '$id' ";
	      $db->query($query);
	      //Header("Location: $gotourl");	  
	    }
		$action="";
    	break;
      default:
      break;
      }

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("category","scategory.html"); 

if (empty($id))
{		// ����
   $action = "new";
}
else
{			// �༭
  $query = "select * from tb_upload_scategoty where fd_scat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
      $db->next_record();                               //��ȡ��¼���� 
      $id           = $db->f(fd_scat_id );            //ID�� 
	    $name         = $db->f(fd_scat_name);          //����� 
      $foldername   = $db->f(fd_scat_foldername);    //�ļ��� 
      $fid          = $db->f(fd_scat_fcatid);
	    $display      = $db->f(fd_scat_display);
	    $upload      = $db->f(fd_scat_moreupload);
	    $action = "edit";
  }
}

$arr_display = array("��","��");
$arr_displayid = array("0","1");
$display = makeselect($arr_display,$display,$arr_displayid); 

$arr_upload = array("�����ϴ�ͼƬ","����ϴ�ͼƬ");
$arr_uploadid = array("0","1");
$upload = makeselect($arr_upload,$upload,$arr_uploadid); 

$query = "select * from tb_upload_fcategory";     
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_dept[]= $db->f(fd_fcat_name);
		$arr_deptid[]= $db->f(fd_fcat_id);		
	}	
}
$fid = makeselect($arr_dept,$fid,$arr_deptid); 

  	
$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );           //����� 
$t->set_var("foldername"   , $foldername   );           //�ļ��� 
$t->set_var('fid'          , $fid          );
$t->set_var('display'      , $display      );
$t->set_var('upload'       , $upload       );

$t->set_var('title'        , $title        );
$t->set_var("action"       , $action       );
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ
$t->set_var("error"        , $error        );

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "category");    # ������ҳ��
//�����ļ��к���
function mkdirs($dir,$mode=0777)
{
if(is_dir($dir)||@mkdir($dir,$mode)) return true;
if(!mkdirs(dirname($dir),$mode)) return false;
return @mkdir($dir,$mode);
}




//ɾ���ļ���
function file_exit($path){
   $handle = @opendir($path); 
   $arr=array();
while (false !== ($file = readdir($handle))) {
   if($file == '.' || $file == '..'){
    continue;
   }
   $file_array[] = $file;
}
if($file_array == NULL){//û���ļ�
   @closedir($handle);
   return false;
}else{	
	//�ж��ļ��������Ƿ���ͼƬ
		foreach($file_array as $value)
		{
			$arr =explode('.',$value);
		
		switch($arr[1])
	{
		case 'jpg':
		return true;
		break;
		case 'jpeg':
		return true;
		break;
		case 'gif':
		return true;
		break;
		case 'png':
		return true;
		break;
		case 'pjpeg':
		return true;
		break;
	}
	
	}
	return false;
	
}
closedir($handle);
//���ļ�
}
?>

