<?
$thismenucode = "1c102";
require ("../include/common.inc.php");

$db = new DB_test;

$gourl = "tb_upload_scategory_b.php" ;
$gotourl = $gourl.$tempurl ;
$title='二级分类设置';
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("chakan","chakan.html"); 
  $query = "select * from tb_upload_scategoty where fd_scat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //判断查询到的记录是否为空
      $db->next_record();                               //读取记录数据 
      $id           = $db->f(fd_scat_id );                   //ID号 
	  $name         = $db->f(fd_scat_name);          //类别名 
      $foldername   = $db->f(fd_scat_foldername);    //文件名 
      $fid          = $db->f(fd_scat_fcatid);
	  $display      = $db->f(fd_scat_display);
	  $upload      = $db->f(fd_scat_moreupload);
  }
  $query="select fd_fcat_foldername from tb_upload_fcategory where fd_fcat_id='$fid'";
  $db->query($query);
  $db->next_record();
  $fid=$db->f(fd_fcat_foldername);
	if($display)
	{
		$display='是';
	}
	else{
		$display='否';
	}
	if($upload)
	{
		$upload='多个上传图片';
	}else{
		$upload='单个上传图片';
	}

  	
$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );           //类别名 
$t->set_var("foldername"   , $foldername   );           //文件名 
$t->set_var('fid'          , $fid          );
$t->set_var('display'      , $display      );
$t->set_var('upload'       , $upload       );

$t->set_var('title'        , $title        );
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "chakan");    # 最后输出页面
