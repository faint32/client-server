<?
$thismenucode = "1c613";
require ("../include/common.inc.php");
require_once('../nusoapclient/AutoGetFilepath.php');
$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_showpro_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$term=$condition;
//echo $action;
switch($action){

	  
	case "add":  //��������
	   if(!empty($arr_tradmarkid))
	   {
	   $toptradmarkid=implode(",", $arr_tradmarkid);
	   }
	  
        $query = "update web_usefultype set fd_usefultype_tradlogo      = '$toptradmarkid'   where fd_usefultype_id = '$listid' ";
	    $db->query($query);   //�޸ĵ�������
	   // echo $query;
	  	$action   = "";
	  break;
	  
    case "del":   //ɾ��ϸ�ڱ�����
	$propiclist= removeFilepath($vid);
	//$params1 = array('id'=>$vid);
	//$propiclist = $client->call('removeFilepath',$params1);

      $toaction="";
	  break;
	default:
	 
		  $action   = "";
	  break;
}




$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("usefultype","showpro.html"); 

if(empty($listid)){		// ����
   $action = "new";
}else{
   $query = "select * from web_usefultype where fd_usefultype_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_usefultype_id);                 //id��  
       $name = $db->f(fd_usefultype_name);      //���ݱ��
     
   }
}

//ԭ��Ʒ��ʾ�б�
$t->set_block("usefultype", "prolist"  , "prolists"); 

        $query = "select fd_useful_name from  tb_useful 
                             where fd_useful_id = '$fd_useful_usefultypeid'";                              
		$db->query($query);
		//echo $query;
		if($db->nf()){
		while($db->next_record()){
					$usefultype_usefulname = $db->f(fd_useful_name);
		
		  
		  
		   
		 
		   
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $dateid           ,
                         "usefultype_usefulname"    => $usefultype_usefulname     ,
                         "vedit"    => $vedit     ,
                         "vpic"    => $vpic     ,
                           "bgcolor"      => $bgcolor,
                        
				          ));
		  $t->parse("prolists", "prolist", true);	
}
}else
{
     $trid  = "tr1";
		 $imgid = "img1";
     $t->set_var(array("trid"          => $trid    ,
                        "imgid"        => $imgid   ,
                        "vid"          => ""       ,
                        "usefultype_usefulname"    => ""       ,
                        "vpic"    => ""       ,
                        "vedit"    => ""       ,
                        "bgcolor"      => "#ffffff",
                       
				          ));
		  $t->parse("prolists", "prolist", true);	
     
}
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("procaname"    , $procaname     );      //id 
$t->set_var("toptradmarkid", $toptradmarkid     );      //id 
$t->set_var("procaid"      , $procaid     );      //id 

                                                 
$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"        , $error        );      
                                            
$t->set_var("checkid"      , $checkid    );      //����ɾ����ƷID   

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "usefultype");    # ������ҳ��
//����ѡ��˵��ĺ���
function getname($arritem,$hadselected,$arry){ 
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= $arritem[$i];
     }else{
       	// $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}


?>

