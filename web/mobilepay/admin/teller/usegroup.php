<?
$thismenucode = "9102";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl= "tb_usegroup_b.php" ;
$gotourl = $gourl.$tempurl ;

switch ($action){
    case "delete":   // ��¼ɾ��
       $query = "delete from web_usegroup  where fd_usegroup_id = $id ";
       $db->query($query);
       Header("Location: $gotourl");       
	     break;
    case "new":   // ���б༭��Ҫ�ṩ����
       $query = "select * from web_usegroup where fd_usegroup_name = '$name' ";
	     $db->query($query);
	     if($db->nf()>0){
	         $error = "�����Ѿ����ڣ����������룡";
	     }else{
           $query="INSERT INTO web_usegroup (
 		               fd_usegroup_name   , fd_usegroup_memo      
  		             )VALUES (
  		             '$name' ,'$memo'      )";
		       $db->query($query);
	         Header("Location: $gotourl");	
	     }
	     break;
    case "edit":   // �޸ļ�¼
       $query = " update web_usegroup set
 		            fd_usegroup_name ='$name' ,fd_usegroup_memo ='$memo'              
  		          where fd_usegroup_id = $id ";
	     $db->query($query);
	     Header("Location: $gotourl");	     
    	break;
    default:
        break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("usegroup","usegroup.ihtml"); 


if (!isset($id)){		// ����
   $action = "new";
   
}else{			// �༭
   $query = "select * from web_usegroup where fd_usegroup_id = $id " ;
   $db->query($query);
	 if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	     $db->next_record();                           //��ȡ��¼���� 
	     $id     = $db->f(fd_usegroup_id);           //ID                     
       $name   = $db->f(fd_usegroup_name);         //����      
       $memo   = $db->f(fd_usegroup_memo);         //��ע
       $action = "edit";                   
       	   	      	                     
    }
}  

       	   
$t->set_var("id"  ,$id  );
$t->set_var("name",$name);
$t->set_var("memo",$memo);

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "usegroup");    # ������ҳ��

?>