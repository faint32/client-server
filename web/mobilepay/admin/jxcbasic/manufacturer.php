<?
$thismenucode = "2k102";     
require("../include/common.inc.php");


$db=new db_test;
$gourl = "tb_manufacturer_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "new":    //������¼
	     $query="select * from tb_manufacturer where fd_manu_name='$name' or fd_manu_allname='$allname'";
	     $db->query($query);
	     if ($db->nf()>0){
	     	$error = "���������Ѿ����ڣ����������룡";
	     }else{
	     	$query="insert into tb_manufacturer (
	     			   fd_manu_no, fd_manu_name  , fd_manu_allname  ,fd_manu_address,fd_manu_linkman,
					   fd_manu_manphone ,fd_manu_xingfen,fd_manu_workstatus
	     			    )values(     
	     		      '$no','$name'    ,'$allname', '$address','$linkman','$manphone','$xingfen',
					  '$workstatus'
	     		      )";
	     	$db->query($query);
	      	  require("../include/alledit.2.php");
	     	  Header("Location: $gotourl");
	     }
	     break;
	      
	case "delete":   //ɾ����¼
	          $query="delete  from tb_manufacturer where fd_manu_id='$listid'";
	          $db->query($query);
	          require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	     break;
	case "edit":     //�༭��¼
	      $query="select * from tb_manufacturer where fd_manu_id<>'$listid' 
	                       and (fd_manu_name='$name' or fd_manu_allname='$allname' )";
	      $db->query($query);
	      if($db->nf()>0){
	      	  $error = "��������̻����Ѿ�����,���������";
	      }else{
	      	  $query="update tb_manufacturer set 
					  fd_manu_no = '$no',
					  fd_manu_name='$name'  ,
					  fd_manu_allname = '$allname' ,
					  fd_manu_address='$address',
					  fd_manu_linkman='$linkman',
					  fd_manu_manphone='$manphone' ,
					  fd_manu_xingfen='$xingfen',
					  fd_manu_workstatus='$workstatus'
	                  where fd_manu_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("manufacturer","manufacturer.html");

if(empty($listid)){
	  $action = "new";
}else{ // �༭
    $query = "select * from tb_manufacturer where fd_manu_id ='$listid'" ;
    $db->query($query);
    if($db->nf()){                                        
	   $db->next_record();                              
	    $id          = $db->f(fd_manu_id);                    
       	$name     = $db->f(fd_manu_name);                  
       	$memo	       = $db->f(fd_manu_memo);             
       	$no          = $db->f(fd_manu_no);                          
       	$allname     = $db->f(fd_manu_allname);                    
       	$linkman	       = $db->f(fd_manu_linkman);
		$manphone          = $db->f(fd_manu_manphone);                       
       	$address     = $db->f(fd_manu_address);                       
       	$manutype	       = $db->f(fd_manu_manutypeid);
		$xingfen     = $db->f(fd_manu_xingfen);                    
       	$workstatus	       = $db->f(fd_manu_workstatus);	
       	$action = "edit";                   
     }  	
}
                 
$arr_codeid=array(0,1,2,3,);
$arr_codename=array('ѡ��Ӫ״��','����','��ͣ��','ͣ��');

$workstatus = makeselect($arr_codename,$workstatus,$arr_codeid); 
$t->set_var("listid",$listid); 
$t->set_var("name",$name);

$t->set_var("no",$no); 
$t->set_var("allname",$allname);
$t->set_var("linkman",$linkman);
$t->set_var("address",$address); 
$t->set_var("manutype",$manutype);
$t->set_var("manphone",$manphone);
$t->set_var("xingfen",$xingfen);
$t->set_var("workstatus",$workstatus);


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "manufacturer"); //����������

?>