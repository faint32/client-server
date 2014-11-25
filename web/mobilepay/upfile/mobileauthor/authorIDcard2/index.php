<?php  
    error_reporting(0);
    $target_path  = "./";//接收文件目录 
	 $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);  
    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {  
       echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";  
    }  else{  
       echo "There was an error uploading the file, please try again!" . $_FILES['uploadedfile']['error'];  
    }  
    
	
	 
$GLOBALS['HTTP_RAW_POST_DATA'];

$GLOBALS['HTTP_RAW_POST_DATA'];
$filename="teststreamdate".('Y-m-d H:i:s').".jpg";//要生成的图片名字

$xmlstr =  $GLOBALS['HTTP_RAW_POST_DATA'];
if(empty($xmlstr)) $xmlstr = file_get_contents('php://input');

$jpg = $xmlstr;//得到post过来的二进制原始数据
$file = fopen("./".$filename,"w");//打开文件准备写入
fwrite($file,$jpg);//写入
fclose($file);//关闭



    ?>  