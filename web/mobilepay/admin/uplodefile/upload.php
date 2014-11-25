<?
require ("../include/common.inc.php");
require ("./resize.php");//生成缩略图函数
require ("./upload.inc.php");//上传函数
require_once('../uploadfunction/basicfunction.php');


$db = new DB_test;
$t = new Template(".", "keep");  //调用一个模版
$t->set_file("upload"      ,"upload.html"  );  
$dir = "../authorpic";
$loginskin="./css/";
$default="checked";

if($act=='delete')
	{	
		uploaddelete($fd_cat_id);
		echo"<script>location.href='$cb?toact=close&act=delete&getvalid=$getvalid&return=';</script>"; 
	}	
	
	
//查出一级和二级的路径
$query="select * from tb_upload_scategoty left join tb_upload_fcategory on fd_scat_fcatid=fd_fcat_id where fd_scat_id='$scatid'";
$db->query($query);
if(!$db->nf()){
echo ("<script>alert('参数有误');window.history.back();</script>");
}else{
	$db->next_record();
	$fid=$db->f(fd_fcat_id);//一级ID
	$ffoldername=$db->f(fd_fcat_foldername);//一级文件名
	$sfoldername=$db->f(fd_scat_foldername);//二级文件名
	$uploads=$db->f(fd_scat_moreupload);	//是否多图片上传
	$d=$db->f(fd_scat_display);   //是否显示默认
 }
$path=$dir;//上传路径
if(isset($_FILES['upload']))
		{	//判定上传图片是否为空
			if($_FILES['upload']['size']>0 && '0'==$_FILES['upload']['error'])
			{		
				$type=$_FILES['upload']['type'];
					if($uploadtype=="1")
				{	
					
					$arrpictype=1;
				}
				
				$thumurl=upload($path,$type,'2010000',$arrpictype);//调用上传函数
				if($thumurl==1)
				{
					$action=""; //当导入文件类型不成功重新处理
				}else
				{
				$picurl=str_replace($path."/","",$thumurl);
				//$r=new image($path);//实例化生成缩略图类
				//$thumurl=$r->reImg($picurl,100,100,40);
				@chmod($thumurl,0777);                  //修改文件的系统权限
				}
		    }
		}
//删除
		
	switch($action)
	{	
		case 'upload':
		//插入记录
		if($scatid==8)//广告位添加时删除原有的数据
		{
			//cleardate($dateid,$scatid);
		}
		$query = "select * from tb_upload_category_list where fd_cat_name = '$filename' and fd_cat_url='$picurl' ";
		$db->query($query);
		if($db->nf()){ 
			die("<script>alert('文件名不能重复,请查证!');window.history.back();</script>");  
		}else{
			uploadnew($dateid,$fid,$scatid,$picurl,$display,$filename,$thumurl);
		}
		//回调数据
		$date[]=$thumurl; 
		//没有ID上传时
		$date[]=getimageid($picurl);
		$date=implode("@@",$date); 
		echo"<script>location.href='$cb?toact=close&getvalid=$getvalid&return=$date';</script>"; 
		//header('location:'.$cb.'?toact=close&return='.$date);
		// echo "<script> window.returnValue=\"$date\" ;if (window.opener != undefined ){window.opener.returnValue=\"$date\" ;}<script>";	 
		// echo"<script>alert('上传成功');window.close();<script>"; 
		 
		 
		break;
		case "edit":
		//编辑
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

// 判断权限 
$t->set_var("skin",$loginskin);
$t->pparse("out", "upload");    # 最后输出页面
?>