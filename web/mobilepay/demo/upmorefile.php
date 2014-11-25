<?php
require ("../include/common.inc.php");
$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("upmorefile","upmorefile.html"); 
echo fopen("php://input",r);
if($action=="upload")
{
	
	$source = $userfile1;
	$source_name=$userfile1_name;
	$dirpart="./".$source_name;   //保存文件的路径
	if(eregi("\.jpg",$source_name)|| eregi("\.gif",$source_name)
	   ||eregi("\.bmp",$source_name)){
	      if (file_exists($dirpart)){ //判断图片是否存在
		        echo "<script>alert(\"该图片已存在，请重新输入\");</script>";
				//exit;
	      }else{
             $isok=@copy($source,$dirpart); 
			
			$filetype = mimetype($source_name);
  
            $newname = date("Y").date("m").date("d").date("H").date("i").date("s").$filetype;
            $oldfilename="./".$source_name;    //旧文件名称
            $newfilename="./".$newname;        //新文件名称
            @chmod($oldfilename,0777);                  //修改文件的系统权限
            @rename($oldfilename,$newfilename);         //修改文件名称函数
			
			if($isok){
   
            	 $str .= $newfilename."图片已上传<br>";
            }
            else{
            	echo "<script>alert(\".$str.文件上传失败!.\");</script>";
            	$str="undefined";
            }  
			
           

            echo "<script>alert(\".$str.\");</script>";
						
            echo "<script> window.returnValue=\"$newfilename\" ;if (window.opener != undefined ){window.opener.returnValue=\"$newfilename\" ;}</script>";
             echo "<script>window.close()</script>";
        }
   }else{
  	          echo "<script>alert(\"请输入这些格式的图片：jpg,gif,bmp\");</script>";
			  //exit;
   }
}

$t->set_var("dir"   , $dir   );

$t->set_var("skin",$loginskin);
$t->pparse("out", "upmorefile");    # 最后输出页面
//判断文件类型函数                        
function mimetype($fichier)         
{                                                                   
  if(eregi("\.bmp",$fichier)){
    $nom_type=".bmp";
  }else if(eregi("\.gif",$fichier)){
    $nom_type=".gif";
  }else if(eregi("\.jpg",$fichier)){
    $nom_type=".jpg";
  }else{
     echo "<script>alert('图片类型不对!');</script>";
  }
  
  return $nom_type;                                                     
}
?>