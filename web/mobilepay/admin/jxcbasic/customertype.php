<?
$thismenucode = "2k105";     
require("../include/common.inc.php");
$db=new db_test;
$gourl = "tb_customertype_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "new":    //������¼
	     $query="select * from tb_customertype where  fd_customertype_name='$cusname' ";
	     $db->query($query);
	     if ($db->nf()){
	     	$error = "�˴������Ѿ����ڣ����������룡";
	     }else{
	     	$query="insert into tb_customertype (
	     			   fd_customertype_no, fd_customertype_name  
	     			    )values(     
	     		      '$cusno','$cusname'    
	     		      )";
	     	$db->query($query);
	      	  require("../include/alledit.2.php");
	     	  Header("Location: $gotourl");
	     }
	     break;
	      
	case "delete":   //ɾ����¼
	          $query="delete  from tb_customertype where fd_customertype_id='$id'";
	          $db->query($query);
	          require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	     break;
	case "edit":     //�༭��¼
	      $query="select * from tb_customertype where fd_customertype_name='$cusname'
	                       and fd_customertype_id<>'$id' ";
	      $db->query($query);
	      if($db->nf()){
	      	  $error = "��������������Ѿ�����,���������";
	      }else{
	      	  $query="update tb_customertype set 
					  fd_customertype_no = '$cusno',
					  fd_customertype_name='$cusname'  
	                  where fd_customertype_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("customertype","customertype.html");

if(empty($id)){
	  $action = "new";
}else{ // �༭
    $query = "select * from tb_customertype where fd_customertype_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                           //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	   $db->next_record();                                  //��ȡ��¼���� 
	    $id          = $db->f(fd_customertype_id);               //id      
        $cusno          = $db->f(fd_customertype_no);               //id
       	$cusname     = $db->f(fd_customertype_name);          //�����              
       	$action = "edit";                   
     }  	
}



$t->set_var("id",$id); 
$t->set_var("cusname",$cusname);
$t->set_var("cusno",$cusno); 


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "customertype"); //����������

?>