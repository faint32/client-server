<?
$thismenucode = "2k109";
require ("../include/common.inc.php");
$db = new DB_test;
$db1 = new DB_test;

//if($dimission != 2 && $type != 3){
// $gourl = "tb_staffer_b.php" ;
  //$dimissiondate = "";
//}
//if($dimission == 2){
	$gourl  = "tb_dimission_b.php" ;
	$gourl2 = "tb_staffer_b.php" ;
//}

//if($type == 3){
	//$gourl = "tb_retire_b.php" ;
//}

$gotourl = $gourl.$tempurl ;
$gotourl2 = $gourl2.$tempurl ;

require("../include/alledit.1.php");


switch ($action)
{
  case "delete":   // ��¼ɾ��
     $query="delete from tb_staffer where fd_sta_id='$id'";
     $db->query($query);
     Header("Location: $gotourl");       
	break;
  
  case "edit":   // �޸ļ�¼      
         $query = "update tb_staffer set
 		               fd_sta_dimissiondate  = '$dimissiondate'  ,  fd_sta_dimimemo   =  '$dimimemo'   ,  fd_sta_dimission  = '2'   
  		             where fd_sta_id = '$id' ";
	       $db->query($query);
	       
	    
	      
	      Header("Location: $gotourl");	  

   break;
   default:
   break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("dimission_set","dimission_set.html"); 

		// �༭
$query = "select * from tb_staffer where fd_sta_id = '$staid' " ;
$db->query($query);
if($db->nf())
{                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
    $db->next_record();                                 //��ȡ��¼���� 
    $id              = $db->f(fd_sta_id);                  //id�� 
  
 	  $dimissiondate   = $db->f(fd_sta_dimissiondate);       //��ְ����
 	  $dimimemo        = $db->f(fd_sta_dimimemo);            //��ְԭ��   	  
    $staffname       = $db->f(fd_sta_name);                //Ա������
    $staffno         = $db->f(fd_sta_stano);               //Ա�����
        
   
              
    $action = "edit";      	                     
}
 
if($dimissiondate=="0000-00-00")
{
	$dimissiondate=date("Y-m-d");
}
                  
$t->set_var("id"              ,  $staid           );
$t->set_var("staffname"       ,  $staffname       );
$t->set_var("staffno"         ,  $staffno         );
$t->set_var("dimimemo"        ,  $dimimemo        );
$t->set_var("dimission"       ,  $dimission       );
$t->set_var("dyear"           ,  $dyear           );
$t->set_var("dmonth"          ,  $dmonth          );
$t->set_var("dimissiondate"   ,  $dimissiondate            );
$t->set_var("display1"        ,  $display1        );   
$t->set_var("display2"        ,  $display2        );   

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ  
$t->set_var("gotourl2"      , $gotourl2      );  // ת�õĵ�ַ                  
$t->set_var("error"        , $error        );        

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "dimission_set");    # ������ҳ��


?>

