<?
$thismenucode = "2k103";     
require("../include/common.inc.php");


$db=new db_test;
$gourl = "tb_customer_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "new":    //������¼
	     $query="select * from tb_customer where fd_cus_no='$cusno' or fd_cus_name='$cusname' ";
	     $db->query($query);
	     if ($db->nf()){
	     	$error = "�˴������Ѿ����ڣ����������룡";
	     }else{
	     	$query="insert into tb_customer (
	     			   fd_cus_no, fd_cus_name  , fd_cus_allname  ,fd_cus_custypeid,fd_cus_address,fd_cus_linkman,
					   fd_cus_manphone ,fd_cus_memo  
	     			    )values(     
	     		      '$cusno','$cusname'    ,'$allname','$custype', '$address','$linkman','$manphone',  '$memo'  
	     		      )";
	     	$db->query($query);
	      	  require("../include/alledit.2.php");
	     	  Header("Location: $gotourl");
	     }
	     break;
	      
	case "delete":   //ɾ����¼
	          $query="delete  from tb_customer where fd_cus_id='$id'";
	          $db->query($query);
	          require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	     break;
	case "edit":     //�༭��¼
	      $query="select * from tb_customer where (fd_cus_name='$cusname' or fd_cus_no='$cusno')
	                       and fd_cus_id<>'$id' ";
	      $db->query($query);
	      if($db->nf()){
	      	  $error = "��������̻����Ѿ�����,���������";
	      }else{
	      	  $query="update tb_customer set 
					  fd_cus_no = '$cusno',
					  fd_cus_name='$cusname'  ,
					  fd_cus_allname = '$allname' ,
					  fd_cus_custypeid = '$custype',
					  fd_cus_address='$address',
					  fd_cus_linkman='$linkman',
					  fd_cus_manphone='$manphone' ,
					  fd_cus_memo='$memo' 
	                  where fd_cus_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("customer","customer.html");

if(empty($id)){
	  $action = "new";
}else{ // �༭
    $query = "select * from tb_customer where fd_cus_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                           //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	   $db->next_record();                                  //��ȡ��¼���� 
	    $id          = $db->f(fd_cus_id);               //id              
       	$cusname     = $db->f(fd_cus_name);          //�����              
       	$memo	       = $db->f(fd_cus_memo);             //���˵��
       	$cusno          = $db->f(fd_cus_no);               //id              
       	$allname     = $db->f(fd_cus_allname);          //�����              
       	$linkman	       = $db->f(fd_cus_linkman);
		$manphone          = $db->f(fd_cus_manphone);               //id              
       	$address     = $db->f(fd_cus_address);          //�����              
       	$custype	       = $db->f(fd_cus_custypeid);	
       	$action = "edit";                   
     }  	
}

$query="select fd_customertype_id ,fd_customertype_name  from tb_customertype";
$db->query($query);
    if($db->nf()){                                          
	   while($db->next_record()){                                  
	    $arr_custypeid[]   = $db->f(fd_customertype_id);                          
       	$arr_custypename[] = $db->f(fd_customertype_name);                      
	}		
} 

$custype=makeselect($arr_custypename,$custype,$arr_custypeid);

$t->set_var("id",$id); 
$t->set_var("cusname",$cusname);
$t->set_var("memo",$memo);
$t->set_var("cusno",$cusno); 
$t->set_var("allname",$allname);
$t->set_var("linkman",$linkman);
$t->set_var("address",$address); 
$t->set_var("custype",$custype);
$t->set_var("manphone",$manphone);

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "customer"); //����������

?>