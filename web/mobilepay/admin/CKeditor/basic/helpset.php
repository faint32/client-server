<?
$thismenucode = "1c602";     
require("../include/common.inc.php");


$db=new db_test;
$gourl = "tb_helpset_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch ($toaction){  
	case "new":    //������¼
	     $query="select * from web_helpset where fd_helpset_name='$name'";
	     $db->query($query);
	     if ($db->nf()>0){
	      	$error = "�������Ѿ����ڣ����������룡";
	     }else{
	      	$query="insert into web_helpset (
	     	        fd_helpset_name   
	     	        )values(
	     	        '$name'   
	     	        )";
	       	$db->query($query);
	        if($loginbacktype==1){  //�ж���תҳ��
	     	   	 Header("Location: helpset.php");
	     	   }else{
	     	     Header("Location: $gotourl");
	         }
	     }
	     break;
	      
	case "delete":   //ɾ����¼
	     $query = "select * from web_help where fd_help_type = '$id'";
	     $db->query($query);
	     if($db->nf()){
	      $error = "�������Ѱ󶨣�����ɾ��";
	    }
	    
	    if(empty($error)){
	     $query="delete  from web_helpset where fd_helpset_id='$id'";
	     $db->query($query);
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	   }
	     break;
	case "edit":     //�༭��¼
	      $query="select * from web_helpset where fd_helpset_id<>'$id' 
	              and fd_helpset_name='$name'";
	      $db->query($query);
	      if($db->nf()>0){
	      	  $error = "�������Ѿ�����,���������";
	      }else{
	      	  $query="update web_helpset set 
	      	          fd_helpset_name   = '$name'  
	                  where fd_helpset_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("template","helpset.html");

if(empty($id)){
	  $toaction = "new";
}else{ // �༭
    $query = "select * from web_helpset where fd_helpset_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                       //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      $db->next_record();                              //��ȡ��¼���� 
	      $id         = $db->f(fd_helpset_id);                         
       	$name       = $db->f(fd_helpset_name);                    
       	$toaction = "edit";                   
     }  	
}



$t->set_var("id",$id); 
$t->set_var("name",$name);

$t->set_var("toaction",$toaction);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //����������

?>