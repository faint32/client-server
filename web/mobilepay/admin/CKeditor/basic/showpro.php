<?
$thismenucode = "1c611";
require ("../include/common.inc.php");
require_once('../nusoapclient/AutogetFileimg.php');
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
	  
        $query = "update web_conf_showpro set fd_csp_tradlogo      = '$toptradmarkid'   where fd_csp_id = '$listid' ";
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
$t->set_file("conf_showpro","showpro.html"); 

if(empty($listid)){		// ����
   $action = "new";
}else{
   $query = "select * from web_conf_showpro where fd_csp_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_csp_id);                 //id��  
       $toptradmarkid = $db->f(fd_csp_tradlogo);      //���ݱ��
       $procaname     = $db->f(fd_csp_procaname);          //¼������
	   $procaid       = $db->f(fd_csp_procatid);          //¼������
   }
}

//ԭ��Ʒ��ʾ�б�
$t->set_block("conf_showpro", "prolist"  , "prolists"); 
if($toptradmarkid<>'')
{
$arr_tradid = explode(",",$toptradmarkid);
$getFileimg = new AutogetFile();
for($i=0;$i<count($arr_tradid);$i++)
{    
           $scatid 	= "9";
		   $dateid   	= $arr_tradid[$i];
		   
		  
		   $getFileimg = new AutogetFile();
		   $propiclist       = explode("@@",$getFileimg->AutogetFileimg(9,$dateid) );
		   
		    $query = "select fd_trademark_name from  tb_trademark 
                             where fd_trademark_id = '$dateid'";                              
	
			$db->query($query);
			//echo $query;
			if($db->nf()){
				while($db->next_record()){
					$csp_trademarkname = $db->f(fd_trademark_name);
			}}
		
		  
		   $thumrul	= $propiclist[0];
		  // echo $thumrul;
		   if($thumrul<>"")
			 {
				   
		   		$vpic      = "<img src='".$propiclist[0]."' title='".$csp_trademarkname."' alt='".$csp_trademarkname."'
				               width='100' style='height:40px;'>";  
		   		$vedit='<a href="#" onClick="del_p('.$propiclist[1].')">ɾ��</a>';
			 }
			 else
			 {
				 $vpic      ="<img src='' width='100'  title='".$csp_trademarkname."' alt='".$csp_trademarkname."' style='height:40px;'>";
				 $vedit='<input type=hidden name=uploadfile  id="uploadfile'.$dateid.'" value="">
				 <input type="button" class="buttonsmall" name="upmorefiles" value="�ϴ�ͼƬ" 
				 onclick=uploadimg("9","'.$dateid.'","'.$propiclist[1].'","uploadfile","new","refeedback","preuploadfile",this);  >';
			 }
		   
		  
		  
		   
		 
		   
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $dateid           ,
                         "csp_trademarkname"    => $csp_trademarkname     ,
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
                        "csp_trademarkname"    => ""       ,
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

$t->pparse("out", "conf_showpro");    # ������ҳ��
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

