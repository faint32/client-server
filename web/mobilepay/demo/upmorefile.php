<?php
require ("../include/common.inc.php");
$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("upmorefile","upmorefile.html"); 
echo fopen("php://input",r);
if($action=="upload")
{
	
	$source = $userfile1;
	$source_name=$userfile1_name;
	$dirpart="./".$source_name;   //�����ļ���·��
	if(eregi("\.jpg",$source_name)|| eregi("\.gif",$source_name)
	   ||eregi("\.bmp",$source_name)){
	      if (file_exists($dirpart)){ //�ж�ͼƬ�Ƿ����
		        echo "<script>alert(\"��ͼƬ�Ѵ��ڣ�����������\");</script>";
				//exit;
	      }else{
             $isok=@copy($source,$dirpart); 
			
			$filetype = mimetype($source_name);
  
            $newname = date("Y").date("m").date("d").date("H").date("i").date("s").$filetype;
            $oldfilename="./".$source_name;    //���ļ�����
            $newfilename="./".$newname;        //���ļ�����
            @chmod($oldfilename,0777);                  //�޸��ļ���ϵͳȨ��
            @rename($oldfilename,$newfilename);         //�޸��ļ����ƺ���
			
			if($isok){
   
            	 $str .= $newfilename."ͼƬ���ϴ�<br>";
            }
            else{
            	echo "<script>alert(\".$str.�ļ��ϴ�ʧ��!.\");</script>";
            	$str="undefined";
            }  
			
           

            echo "<script>alert(\".$str.\");</script>";
						
            echo "<script> window.returnValue=\"$newfilename\" ;if (window.opener != undefined ){window.opener.returnValue=\"$newfilename\" ;}</script>";
             echo "<script>window.close()</script>";
        }
   }else{
  	          echo "<script>alert(\"��������Щ��ʽ��ͼƬ��jpg,gif,bmp\");</script>";
			  //exit;
   }
}

$t->set_var("dir"   , $dir   );

$t->set_var("skin",$loginskin);
$t->pparse("out", "upmorefile");    # ������ҳ��
//�ж��ļ����ͺ���                        
function mimetype($fichier)         
{                                                                   
  if(eregi("\.bmp",$fichier)){
    $nom_type=".bmp";
  }else if(eregi("\.gif",$fichier)){
    $nom_type=".gif";
  }else if(eregi("\.jpg",$fichier)){
    $nom_type=".jpg";
  }else{
     echo "<script>alert('ͼƬ���Ͳ���!');</script>";
  }
  
  return $nom_type;                                                     
}
?>