<?
$thismenucode = "1c607";
require ("../include/common.inc.php");

$db  = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;


$gourl = "tb_propic_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($toaction){
	case "save":   //ɾ��ϸ�ڱ�����
    $allcontent = $_POST[FCKeditor1];
   // $allcontent=htmlspecialchars($allcontent,ENT_QUOTES);  
  
    	$query = "update web_conf_procatrademark set fd_procatrad_memo = '$allcontent' where fd_procatrad_id = '$listid'";
      $db->query($query);
    
      	
    $toaction  = "";   
	  break;

	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("template","tradjs.html"); 

if (empty($listid))
{		// ����
   $toaction = "new";
}else{
   $query = "select fd_trademark_name,fd_trademark_id,fd_proca_catname,fd_procatrad_id,fd_procatrad_memo from web_conf_procatrademark
	                            left join tb_trademark on fd_trademark_id = fd_procatrad_trademarkid
	                            left join tb_procatalog on fd_proca_id = fd_procatrad_procaid
                              where fd_procatrad_id = '$listid'
                                ";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid       = $db->f(fd_procatrad_id);            //id��  
       $brandname       = $db->f(fd_trademark_name);            //���ݱ��
       $brandid       = $db->f(fd_trademark_id); 
       $procaname       = $db->f(fd_proca_catname);            //���ݱ��
       $procaid       = $db->f(fd_proca_id);
	   $xx    =$db->f(fd_procatrad_memo);
        
   }
}


$oFCKeditor = new FCKeditor('FCKeditor1')  ; 
$oFCKeditor->BasePath = '../FCKeditor/' ;    
$oFCKeditor->ToolbarSet = 'Normal' ;  
$oFCKeditor->Width = '600' ; 
$oFCKeditor->Height = '440' ; 
$oFCKeditor->Value      = $xx;    
$fckeditor = $oFCKeditor->CreateHtml();	

$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("procaname"    , $procaname           );      //id 
$t->set_var("brandname"    , $brandname           ); 
$t->set_var("useful"    , $useful           );  
$t->set_var("brandid"    , $brandid           );  
$t->set_var("procaid"    , $procaid           );  
$t->set_var("error"    , $error           );  
$t->set_var("toaction"    , $toaction           );  
$t->set_var("fckeditor"    , $fckeditor    );
$t->set_var("gotourl"    , $gotourl           ); 


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # ������ҳ��



?>

