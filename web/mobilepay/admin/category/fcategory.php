<?
$thismenucode = "1c101";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl = "tb_upload_fcategory_b.php" ;
$gotourl = $gourl.$tempurl ;
switch ($action)
{
  case "delete":   // ��¼ɾ��
	 //ɾ���ļ���
	 $sql="select * from tb_upload_scategoty where fd_scat_fcatid='$id'";
	 $db->query($sql);
	 if($db->nf())
	 {
		$error="��һ���������ӷ��࣬����ɾ����ض������࣡";
	 }else{
	 $query="select * from tb_upload_fcategory where fd_fcat_id='$id'";
	 $db->query($query);
	 $db->next_record();
	 $dir .=$db->f(fd_fcat_foldername);
	 $result=file_exit($dir);
	 if($result)
	 {
		$error = "�����Ѿ����ļ�����ɾ��!";
		
	 }else{
		 $query="delete from tb_upload_fcategory where fd_fcat_id='$id'";
		 $db->query($query);
		 @rmdir($dir);
		  Header("Location: $gotourl");    
	 } 
	 }
	break;
  case "new": 
 
        //�����¼
       $query = "select * from tb_upload_fcategory where fd_fcat_name = '$name' and fd_fcat_foldername='$foldername'";
      $db->query($query);
      if($db->nf()){  
      	$error = "����������ļ����������ظ�,���֤!";  
      }else{  
        $query="INSERT INTO tb_upload_fcategory (
 		            fd_fcat_name     , fd_fcat_foldername  , fd_fcat_time
  		          )VALUES (
  		          '$name'          , '$foldername'       , now()
				)";
		    $db->query($query);

			$dir .=$foldername;
			@mkdirs($dir);//�����ļ���
			@chmod($dir,0777);
		 Header("Location: $gotourl");
	 }
		   	
	break;
  case "edit":   // �޸ļ�¼
      $query = "select * from tb_upload_fcategory where fd_fcat_name = '$name' and fd_fcat_id <> '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "����������ظ�,���֤!";  
		  }else{
         $query = "update tb_upload_fcategory set
 		               fd_fcat_name   = '$name'  
  		             where fd_fcat_id = '$id' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
    	break;
      default:
      break;
      }

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("category","fcategory.html"); 

if (empty($id))
{		// ����
   $action = "new";
}
else
{			// �༭
  $query = "select * from tb_upload_fcategory where fd_fcat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
      $db->next_record();                               //��ȡ��¼���� 
      $id           = $db->f(fd_fcat_id );                   //ID�� 
	    $name         = $db->f(fd_fcat_name);          //����� 
      $foldername   = $db->f(fd_fcat_foldername);    //�ļ��� 
      $action = "edit";  
  }
}  		 	
$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );           //����� 
$t->set_var("foldername"   , $foldername   );           //�ļ��� 

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
while (false !== ($file = readdir($handle))) {
   if($file == '.' || $file == '..'){
    continue;
   }
   $file_array[] = $file;
}
if($file_array == NULL){//û���ļ�
   @closedir($handle);
   return false;
}
@closedir($handle);
return true;//���ļ�
}
?>

