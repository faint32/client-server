<?
$thismenucode = "1c101";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl = "tb_upload_fcategory_b.php" ;
$gotourl = $gourl.$tempurl ;
switch ($action)
{
  case "delete":   // 记录删除
	 //删除文件夹
	 $sql="select * from tb_upload_scategoty where fd_scat_fcatid='$id'";
	 $db->query($sql);
	 if($db->nf())
	 {
		$error="该一级分类有子分类，请先删除相关二级分类！";
	 }else{
	 $query="select * from tb_upload_fcategory where fd_fcat_id='$id'";
	 $db->query($query);
	 $db->next_record();
	 $dir .=$db->f(fd_fcat_foldername);
	 $result=file_exit($dir);
	 if($result)
	 {
		$error = "里面已经有文件不能删除!";
		
	 }else{
		 $query="delete from tb_upload_fcategory where fd_fcat_id='$id'";
		 $db->query($query);
		 @rmdir($dir);
		  Header("Location: $gotourl");    
	 } 
	 }
	break;
  case "new": 
 
        //插入记录
       $query = "select * from tb_upload_fcategory where fd_fcat_name = '$name' and fd_fcat_foldername='$foldername'";
      $db->query($query);
      if($db->nf()){  
      	$error = "类别名或者文件夹名不能重复,请查证!";  
      }else{  
        $query="INSERT INTO tb_upload_fcategory (
 		            fd_fcat_name     , fd_fcat_foldername  , fd_fcat_time
  		          )VALUES (
  		          '$name'          , '$foldername'       , now()
				)";
		    $db->query($query);

			$dir .=$foldername;
			@mkdirs($dir);//生成文件夹
			@chmod($dir,0777);
		 Header("Location: $gotourl");
	 }
		   	
	break;
  case "edit":   // 修改记录
      $query = "select * from tb_upload_fcategory where fd_fcat_name = '$name' and fd_fcat_id <> '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "类别名不能重复,请查证!";  
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

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("category","fcategory.html"); 

if (empty($id))
{		// 新增
   $action = "new";
}
else
{			// 编辑
  $query = "select * from tb_upload_fcategory where fd_fcat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //判断查询到的记录是否为空
      $db->next_record();                               //读取记录数据 
      $id           = $db->f(fd_fcat_id );                   //ID号 
	    $name         = $db->f(fd_fcat_name);          //类别名 
      $foldername   = $db->f(fd_fcat_foldername);    //文件名 
      $action = "edit";  
  }
}  		 	
$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );           //类别名 
$t->set_var("foldername"   , $foldername   );           //文件名 

$t->set_var("action"       , $action       );
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址
$t->set_var("error"        , $error        );

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "category");    # 最后输出页面
//生成文件夹函数
function mkdirs($dir,$mode=0777)
{
if(is_dir($dir)||@mkdir($dir,$mode)) return true;
if(!mkdirs(dirname($dir),$mode)) return false;
return @mkdir($dir,$mode);
}
//删除文件夹
function file_exit($path){
   $handle = @opendir($path); 
while (false !== ($file = readdir($handle))) {
   if($file == '.' || $file == '..'){
    continue;
   }
   $file_array[] = $file;
}
if($file_array == NULL){//没有文件
   @closedir($handle);
   return false;
}
@closedir($handle);
return true;//有文件
}
?>

