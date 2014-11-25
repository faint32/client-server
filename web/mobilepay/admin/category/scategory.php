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
  case "delete":   // 记录删除
 
	$query = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";  
	$db->query($query);
	$db->next_record();
	$folder=$db->f(fd_fcat_foldername);
	$dir .=$folder.'/';
	$dir .=$foldername;
	$result=file_exit($dir);
	 if($result)
	 {
		$error = "文件夹里面有图片,不能删除!";
		
	 }else{
     $query="delete from tb_upload_scategoty where fd_scat_id='$id'";
     $db->query($query);
	   @rmdir($dir);
     Header("Location: $gotourl");  
	}     
	break;
  case "new": 
        //插入记录
       $query = "select * from tb_upload_scategoty where fd_scat_name = '$name' and fd_scat_foldername='$foldername' ";
      $db->query($query);
      if($db->nf()){  
      	$error = "类别名或者文件夹名不能重复,请查证!";  
      }else {
        $query="INSERT INTO tb_upload_scategoty (
 		           fd_scat_name      , fd_scat_foldername   ,fd_scat_fcatid ,
 		           fd_scat_display   ,fd_scat_moreupload    ,fd_scat_time
  		          )VALUES (
  		         '$name'          , '$foldername'              ,'$fid' ,
				   '$display'  ,$upload    ,   now()
				)";
				
		    $db->query($query);
			//获取父类文件夹
			$query = "select * from tb_upload_fcategory where fd_fcat_id='$fid'";  
			$db->query($query);
			$db->next_record();
			$folder=$db->f(fd_fcat_foldername);
			$dir .=$folder.'/';
			//子类文件夹的路径 
			$dir .=$foldername;
			@mkdirs($dir);//生成文件夹
			@chmod($dir,0777);
		    Header("Location: $gotourl");
	 }
	$action="";	   	
	break;
  case "edit":   // 修改记录
  		
      $query = "select * from tb_upload_scategoty where (fd_scat_name = '$name' or fd_scat_foldername = '$foldername' ) and fd_scat_id != '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "类别名不能重复或者文件名不能修改,请查证!";  
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

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("category","scategory.html"); 

if (empty($id))
{		// 新增
   $action = "new";
}
else
{			// 编辑
  $query = "select * from tb_upload_scategoty where fd_scat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //判断查询到的记录是否为空
      $db->next_record();                               //读取记录数据 
      $id           = $db->f(fd_scat_id );            //ID号 
	    $name         = $db->f(fd_scat_name);          //类别名 
      $foldername   = $db->f(fd_scat_foldername);    //文件名 
      $fid          = $db->f(fd_scat_fcatid);
	    $display      = $db->f(fd_scat_display);
	    $upload      = $db->f(fd_scat_moreupload);
	    $action = "edit";
  }
}

$arr_display = array("否","是");
$arr_displayid = array("0","1");
$display = makeselect($arr_display,$display,$arr_displayid); 

$arr_upload = array("单个上传图片","多个上传图片");
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
$t->set_var("name"         , $name         );           //类别名 
$t->set_var("foldername"   , $foldername   );           //文件名 
$t->set_var('fid'          , $fid          );
$t->set_var('display'      , $display      );
$t->set_var('upload'       , $upload       );

$t->set_var('title'        , $title        );
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
   $arr=array();
while (false !== ($file = readdir($handle))) {
   if($file == '.' || $file == '..'){
    continue;
   }
   $file_array[] = $file;
}
if($file_array == NULL){//没有文件
   @closedir($handle);
   return false;
}else{	
	//判读文件夹下面是否有图片
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
//有文件
}
?>

