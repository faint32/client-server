<?
$thismenucode = "sys";
require ("../include/common.inc.php");
require_once('../nusoaplib/nusoap.php');
require_once('../nusoapclient/AutogetFileimg.php');

//$client=new soapclient('http://localhost/managementfile/nusoap/AutoGetFilepath.php?wsdl',true);
//$err = $client->getError();
$db  = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;
$dbfile = new DB_file;


$gourl = "tb_propic_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
//echo var_dump($_POST);
switch($toaction){

	case "del":   //ɾ��ϸ�ڱ�����
	$propiclist= removeFilepath($vid);
	//$params1 = array('id'=>$vid);
	//$propiclist = $client->call('removeFilepath',$params1);

      $toaction="";
	  break;
	case "setdef":  //��������
	  	$propiclist= displayFilepath($vid);
//      	$params1 = array('id'=>$vid);
//      	$propiclist = $client->call('displayFilepath',$params1);
	    $toaction="";
	  break;
	  
	default:
	  $toaction="";
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("template","tradlogo.html"); 

if (empty($listid))
{		// ����
   $toaction = "new";
   $newhidden ="none";
}else{
    $toaction = "edit";
	 $newhidden ="";
   $query = "select fd_trademark_name,fd_trademark_id,fd_proca_catname,fd_procatrad_id,fd_proca_id,fd_procatrad_commjs,
   							 fd_procatrad_tradjs,fd_procatrad_commcs from web_conf_procatrademark
	                            left join tb_trademark on fd_trademark_id = fd_procatrad_trademarkid
	                            left join tb_procatalog on fd_proca_id = fd_procatrad_procaid
                              where fd_procatrad_id = '$listid'
                                ";
							//	echo $query;
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_procatrad_id);            //id��  
       $brandname     = $db->f(fd_trademark_name);            //���ݱ��
       $trademarkid   = $db->f(fd_trademark_id); 
       $procaname     = $db->f(fd_proca_catname);            //���ݱ��
       $procaid       = $db->f(fd_proca_id);
	  
	   $commjs        = $db->f(fd_procatrad_commjs);
	   $tradjs        = $db->f(fd_procatrad_tradjs);
	   $commcs        = $db->f(fd_procatrad_commcs);
	  // echo $tradjs;
        
   }
}
$query = "select  fd_proca_id ,fd_proca_catname  from tb_procatalog   order by fd_proca_no";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $arr_nav_procaid[]   = $db->f(fd_proca_id);
		 $arr_nav_procaname[] = $db->f(fd_proca_catname);
	}
}
// echo $procaid;
$procaid = makeselect($arr_nav_procaname,$procaid,$arr_nav_procaid);  

$query = "select  fd_trademark_id ,fd_trademark_name  from tb_trademark   order by fd_trademark_id";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $arr_nav_trademarkid[]   = $db->f(fd_trademark_id);
		 $arr_nav_trademarkname[] = $db->f(fd_trademark_name);
	}
}
// echo $trademarkid;
$trademarkid = makeselect($arr_nav_trademarkname,$trademarkid,$arr_nav_trademarkid);  

//��ʾ�б�
$t->set_block("template", "prolist"  , "prolists");
$scatid 	= "9";
$dateid   	= $listid;
//$params1 = array('scatid'=>$scatid,'dateid'=>$dateid);
//$propiclist = $client->call('getFilepath',$params1);
$getFileimg = new AutogetFile();
$propiclist       = explode("@@",$getFileimg->AutogetFileimg(9,$dateid) );
$count=0;//��¼��

for($i=0;$i<count($propiclist);$i++)
{    
	       $count++;//��¼��
	
		   $vid   = $propiclist[1]; 
		  
		   $vpic      =  "<img  onclick='submit_tipshowpic(\"".$propiclist[0]."\");' src='".$propiclist[0]."' class='group2' title='".$vtitle."' >";  
		 
           $vdef       =  $propiclist[$i]['display']; 
       
     
     	$vdef ='<a href="#" onClick="del_p('.$vid.')">ɾ��</a>';
   
		   $count++;
		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
  
		   
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $vid           ,
                         "vdef"    => $vdef     ,
                         "vpic"    => $vpic     ,
                         "vurl"    => $vurl     ,
                           "bgcolor"      => $bgcolor,
                        
				          ));
		  $t->parse("prolists", "prolist", true);	
}





//$oFCKeditor = new FCKeditor('fccommjs')  ; 
//$oFCKeditor->BasePath = '../FCKeditor/' ;    
//$oFCKeditor->ToolbarSet = 'Normal' ;  
//$oFCKeditor->Width = '98%' ; 
//$oFCKeditor->Height = '300' ; 
//$oFCKeditor->Value      = $commjs;    
//$commjs = $oFCKeditor->CreateHtml();	

//
//$oFCKeditor1 = new FCKeditor('fccommcs')  ; 
//$oFCKeditor1->BasePath = '../FCKeditor/' ;    
//$oFCKeditor1->ToolbarSet = 'Normal' ;  
//$oFCKeditor1->Width = '98%' ; 
//$oFCKeditor1->Height = '300' ; 
//$oFCKeditor1->Value      = $commcs;    
//$commcs = $oFCKeditor1->CreateHtml();	

//
//$oFCKeditor2 = new FCKeditor('fctradjs')  ; 
//$oFCKeditor2->BasePath = '../FCKeditor/' ;    
//$oFCKeditor2->ToolbarSet = 'Normal' ;  
//$oFCKeditor2->Width = '98%' ; 
//$oFCKeditor2->Height = '300' ; 
//$oFCKeditor2->Value      = $tradjs;    
//$tradjs = $oFCKeditor2->CreateHtml();	

//echo htmlspecialchars($tradjs,ENT_QUOTES);  
$t->set_var("tradjs"    , $tradjs    );
$t->set_var("commcs"    , $commcs    );
$t->set_var("commjs"    , $commjs    );
$t->set_var("newhidden"       , $newhidden       );      //����id 
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("procaname"    , $procaname           );      //id 
$t->set_var("brandname"    , $brandname           ); 
$t->set_var("useful"    , $useful           );  
$t->set_var("trademarkid"    , $trademarkid           );  
$t->set_var("procaid"    , $procaid           );  
$t->set_var("error"    , $error           );  
$t->set_var("toaction"    , $toaction           );  
$t->set_var("gotourl"    , $gotourl           ); 


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # ������ҳ��



?>
