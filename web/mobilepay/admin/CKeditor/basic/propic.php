<?
$thismenucode = "sys";
require ("../include/common.inc.php");
require_once('../nusoaplib/nusoap.php');
require_once('../nusoapclient/AutoGetFileimg.php');

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
	case "edit":   //删除细节表数据
    $commjs = $commjs;
    $commcs = $commcs;
	$tradjs = $tradjs;
    $query = "update web_conf_procatrademark set fd_procatrad_commjs = '$commjs',fd_procatrad_commcs = '$commcs',
	          fd_procatrad_tradjs = '$tradjs' where fd_procatrad_id = '$listid'";
    $db->query($query);
	//echo $query;
    $toaction  = "";   
	  break;
	case "new":   //删除细节表数据
    $query = "select 1 from web_conf_procatrademark
	                           where fd_procatrad_trademarkid ='$trademarkid'
	                           and  fd_procatrad_procaid ='$procaid' 
                                ";
   $db->query($query);
   if($db->nf())
   {
     $error="该分类下已存在此商标";
   }else
   {
	 $query = "insert  web_conf_procatrademark (fd_procatrad_trademarkid,fd_procatrad_procaid,fd_procatrad_datetime)
	values(  '$trademarkid','$procaid',now())";
    $db->query($query);
	$listid = $db->insert_id();    //取出刚插入的记录的主关键值的id  
   }
    $toaction  = "";   
	break;
	case "del":   //删除细节表数据
	$propiclist= removeFilepath($vid);
	//$params1 = array('id'=>$vid);
	//$propiclist = $client->call('removeFilepath',$params1);

      $toaction="";
	  break;
	case "setdef":  //新增数据
	  	$propiclist= displayFilepath($vid);
//      	$params1 = array('id'=>$vid);
//      	$propiclist = $client->call('displayFilepath',$params1);
	    $toaction="";
	  break;
	  
	default:
	  $toaction="";
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("template","propic.html"); 

if (empty($listid))
{		// 新增
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
   	   $listid        = $db->f(fd_procatrad_id);            //id号  
       $brandname     = $db->f(fd_trademark_name);            //单据编号
       $trademarkid   = $db->f(fd_trademark_id); 
       $procaname     = $db->f(fd_proca_catname);            //单据编号
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

//显示列表
$t->set_block("template", "prolist"  , "prolists");
$scatid 	= "5";
$dateid   	= $listid;

$getFileimg = new AutogetFile();
$propiclist       = explode("@@",$getFileimg->AutogetFileimg(5,$dateid) );

//echo $propiclist[0];
   
	       $count++;//记录数
	       $propic            = $propiclist[0];
		   $vid               = $propiclist[1];
		  
		   $vpic      =  "<img  src='".$propic."' href='".$propic."' class='group2' title='".$vtitle."'  width='50' height='50'>";  
  
           $vdef       =  $propiclist[$i]['display']; 
       
   
     	   $vdef ='<a href="#" onClick="del_p('.$vid.')">删除</a>';
    
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






//$oFCKeditor = new FCKeditor('fccommjs')  ; 
//$oFCKeditor->BasePath = '../FCKeditor/' ;    
//$oFCKeditor->ToolbarSet = 'Normal' ;  
//$oFCKeditor->Width = '98%' ; 
//$oFCKeditor->Height = '300' ; 
//$oFCKeditor->Value      = $commjs;    
//$commjs = $oFCKeditor->CreateHtml();	
//$t->set_var("commjs"    , $commjs    );
//
//$oFCKeditor1 = new FCKeditor('fccommcs')  ; 
//$oFCKeditor1->BasePath = '../FCKeditor/' ;    
//$oFCKeditor1->ToolbarSet = 'Normal' ;  
//$oFCKeditor1->Width = '98%' ; 
//$oFCKeditor1->Height = '300' ; 
//$oFCKeditor1->Value      = $commcs;    
//$commcs = $oFCKeditor1->CreateHtml();	
//$t->set_var("commcs"    , $commcs    );
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

$t->set_var("newhidden"       , $newhidden       );      //单据id 
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("procaname"    , $procaname           );      //id 
$t->set_var("brandname"    , $brandname           ); 
$t->set_var("useful"    , $useful           );  
$t->set_var("trademarkid"    , $trademarkid           );  
$t->set_var("procaid"    , $procaid           );  
$t->set_var("error"    , $error           );  
$t->set_var("toaction"    , $toaction           );  
$t->set_var("gotourl"    , $gotourl           ); 


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # 最后输出页面



?>

