<?php
    error_reporting(0);
	if (!isset( $HTTP_RAW_POST_DATA ) ) {   
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');   
    }   
  
// fix for mozBlog and other cases where xml isn't on the very first line   
if ( isset($HTTP_RAW_POST_DATA) )   
    $HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA); 
 
    $target_path  = "./";//�����ļ�Ŀ¼ 
	echo var_dump(file_get_contents("php://input"));
	$files = file_get_contents("php://input");
	
	$file="test".date("Y-m-d H:i:s").".txt"; 
	$filehandle=fopen($file, "w"); 
	fwrite($filehandle,$files.var_dump($_FILES).var_dump($HTTP_RAW_POST_DATA)); 
	fclose($filehandle); 
//	echo var_dump($files); 
    $target_path = $target_path . basename( $files['pic']['name']);  
    if(move_uploaded_file($files['pic']['tmp_name'], $target_path)) { 
//	    $newname = date("Y").date("m").date("d").date("H").date("i").date("s").$filetype;
//            $oldfilename="./".$source_name;    //���ļ�����
//            $newfilename="./".$newname;        //���ļ�����
//            @chmod($oldfilename,0777);                  //�޸��ļ���ϵͳȨ��
//            @rename($oldfilename,$newfilename);         //�޸��ļ����ƺ��� 
       echo "The file ".  basename( $files['pic']['name']). " has been uploaded";  
    }  else{  
       echo "There was an error uploading the file, please try again!" . $files['pic']['error'];  
    }  
	
	
    ?>  