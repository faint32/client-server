<?
$thismenucode = "1c102";
require ("../include/common.inc.php");

$db = new DB_test;

$gourl = "tb_upload_scategory_b.php" ;
$gotourl = $gourl.$tempurl ;
$title='������������';
	
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("chakan","chakan.html"); 
  $query = "select * from tb_upload_scategoty where fd_scat_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
      $db->next_record();                               //��ȡ��¼���� 
      $id           = $db->f(fd_scat_id );                   //ID�� 
	  $name         = $db->f(fd_scat_name);          //����� 
      $foldername   = $db->f(fd_scat_foldername);    //�ļ��� 
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
		$display='��';
	}
	else{
		$display='��';
	}
	if($upload)
	{
		$upload='����ϴ�ͼƬ';
	}else{
		$upload='�����ϴ�ͼƬ';
	}

  	
$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );           //����� 
$t->set_var("foldername"   , $foldername   );           //�ļ��� 
$t->set_var('fid'          , $fid          );
$t->set_var('display'      , $display      );
$t->set_var('upload'       , $upload       );

$t->set_var('title'        , $title        );
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ
// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "chakan");    # ������ҳ��
