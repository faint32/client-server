<?
require ("../include/common.inc.php");
require ("./resize.php");//��������ͼ����
require ("./upload.inc.php");//�ϴ�����
require_once('../uploadfunction/basicfunction.php');


$db = new DB_test;
$t = new Template(".", "keep");  //����һ��ģ��
$t->set_file("upload"      ,"upload.html"  );  
$dir = "../authorpic";
$loginskin="./css/";
$default="checked";

if($act=='delete')
	{	
		uploaddelete($fd_cat_id);
		echo"<script>location.href='$cb?toact=close&act=delete&getvalid=$getvalid&return=';</script>"; 
	}	
	
	
//���һ���Ͷ�����·��
$query="select * from tb_upload_scategoty left join tb_upload_fcategory on fd_scat_fcatid=fd_fcat_id where fd_scat_id='$scatid'";
$db->query($query);
if(!$db->nf()){
echo ("<script>alert('��������');window.history.back();</script>");
}else{
	$db->next_record();
	$fid=$db->f(fd_fcat_id);//һ��ID
	$ffoldername=$db->f(fd_fcat_foldername);//һ���ļ���
	$sfoldername=$db->f(fd_scat_foldername);//�����ļ���
	$uploads=$db->f(fd_scat_moreupload);	//�Ƿ��ͼƬ�ϴ�
	$d=$db->f(fd_scat_display);   //�Ƿ���ʾĬ��
 }
$path=$dir;//�ϴ�·��
if(isset($_FILES['upload']))
		{	//�ж��ϴ�ͼƬ�Ƿ�Ϊ��
			if($_FILES['upload']['size']>0 && '0'==$_FILES['upload']['error'])
			{		
				$type=$_FILES['upload']['type'];
					if($uploadtype=="1")
				{	
					
					$arrpictype=1;
				}
				
				$thumurl=upload($path,$type,'2010000',$arrpictype);//�����ϴ�����
				if($thumurl==1)
				{
					$action=""; //�������ļ����Ͳ��ɹ����´���
				}else
				{
				$picurl=str_replace($path."/","",$thumurl);
				//$r=new image($path);//ʵ������������ͼ��
				//$thumurl=$r->reImg($picurl,100,100,40);
				@chmod($thumurl,0777);                  //�޸��ļ���ϵͳȨ��
				}
		    }
		}
//ɾ��
		
	switch($action)
	{	
		case 'upload':
		//�����¼
		if($scatid==8)//���λ���ʱɾ��ԭ�е�����
		{
			//cleardate($dateid,$scatid);
		}
		$query = "select * from tb_upload_category_list where fd_cat_name = '$filename' and fd_cat_url='$picurl' ";
		$db->query($query);
		if($db->nf()){ 
			die("<script>alert('�ļ��������ظ�,���֤!');window.history.back();</script>");  
		}else{
			uploadnew($dateid,$fid,$scatid,$picurl,$display,$filename,$thumurl);
		}
		//�ص�����
		$date[]=$thumurl; 
		//û��ID�ϴ�ʱ
		$date[]=getimageid($picurl);
		$date=implode("@@",$date); 
		echo"<script>location.href='$cb?toact=close&getvalid=$getvalid&return=$date';</script>"; 
		//header('location:'.$cb.'?toact=close&return='.$date);
		// echo "<script> window.returnValue=\"$date\" ;if (window.opener != undefined ){window.opener.returnValue=\"$date\" ;}<script>";	 
		// echo"<script>alert('�ϴ��ɹ�');window.close();<script>"; 
		 
		 
		break;
		case "edit":
		//�༭
		uploadedit($filename,$display,$picurl,$thumurl,$fd_cat_id);

	    $query="select fd_cat_thumurl,fd_cat_url from tb_upload_category_list where fd_cat_id='$fd_cat_id'";
		$db->query($query);
		if($db->nf()){
		$db->next_record();
		$thum=$db->f(fd_cat_thumurl);
		}
		$returnvalue =$thum."@@".$fd_cat_id; 
		
		echo"<script>location.href='$cb?toact=close&getvalid=$getvalid&return=$returnvalue';</script>"; 	 
		break;
	}
	
	if($act=="new")
	{
		$action="upload";
	}
	if($act=='edit')
	{	
		$arr_result=uploadshow($fd_cat_id);
		
		$filename=$arr_result['filename'];
		$display =$arr_result['display'];
		$action  =$arr_result['action'];
		$fpcheck1=$display ?" checked" :"";
		$fpcheck2=$display ? "": "checked";		
	}
$t->set_var('filename'     , $filename    );
$t->set_var('display'      , $display     );
$t->set_var('fpcheck1'     , $fpcheck1    );
$t->set_var('fpcheck2'     , $fpcheck2    );
$t->set_var('action'       , $action      );
$t->set_var('act'          , $act      );
$t->set_var('default'      , $default     );
$t->set_var('fd_cat_id'    , $fd_cat_id   );
$t->set_var('fid'          , $fid         );
$t->set_var('dateid'       , $dateid      );
$t->set_var('scatid'       , $scatid      );
$t->set_var('cb'           , $cb          );
$t->set_var('getvalid'     , $getvalid    );
$t->set_var('uploadtype'   , $uploadtype    );

// �ж�Ȩ�� 
$t->set_var("skin",$loginskin);
$t->pparse("out", "upload");    # ������ҳ��
?>