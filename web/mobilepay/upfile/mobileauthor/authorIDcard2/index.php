<?php  
    error_reporting(0);
    $target_path  = "./";//�����ļ�Ŀ¼ 
	 $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);  
    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {  
       echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";  
    }  else{  
       echo "There was an error uploading the file, please try again!" . $_FILES['uploadedfile']['error'];  
    }  
    
	
	 
$GLOBALS['HTTP_RAW_POST_DATA'];

$GLOBALS['HTTP_RAW_POST_DATA'];
$filename="teststreamdate".('Y-m-d H:i:s').".jpg";//Ҫ���ɵ�ͼƬ����

$xmlstr =  $GLOBALS['HTTP_RAW_POST_DATA'];
if(empty($xmlstr)) $xmlstr = file_get_contents('php://input');

$jpg = $xmlstr;//�õ�post�����Ķ�����ԭʼ����
$file = fopen("./".$filename,"w");//���ļ�׼��д��
fwrite($file,$jpg);//д��
fclose($file);//�ر�



    ?>  