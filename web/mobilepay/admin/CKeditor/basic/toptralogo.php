<?
$thismenucode = "1c611";
require ("../include/common.inc.php");
require_once('../nusoapclient/AutogetFileimg.php');
$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_indexnav_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$term=$condition;

switch($action){

	  
		case "add":  //��������
	   if(!empty($arr_tradmarkid))
	   {
	   $tradlogo=implode(",", $arr_tradmarkid);
	   }
	  
        $query = "update web_usefultype set fd_usefultype_toptradlogo      = '$tradlogo'  where fd_usefultype_id = '$listid' ";
	    $db->query($query);   //�޸ĵ�������
	   // echo $query;
	  	$action   = "";
	  break;
	  
	  case "del":  //ɾ������
	  
	 removeFilepath($vid);
      $action   = "";
	  
	  break;
 
	default:
	 
		  $action   = "";
	  break;
}




$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("usefultype","toptralogo.html"); 

if(empty($listid)){		// ����
   $action = "new";
}else{
   $query = "select * from web_usefultype where fd_usefultype_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_usefultype_id);          //id�� 
	   $no            = $db->f(fd_usefultype_no);          //id��  
       $procaname     = $db->f(fd_usefultype_name);       //���ݱ��
	   $procaid       = $db->f(fd_usefultype_procaid);       //���ݱ��
	   $toptradlogo   = $db->f( fd_usefultype_toptradlogo);       //���ݱ��
	  
   }
  
else
{
	$procaid ="";
}}

//ԭ��Ʒ��ʾ�б�
$t->set_block("usefultype", "prolist"  , "prolists"); 
if($toptradlogo<>'')
{
$arr_tradid = explode(",",$toptradlogo);
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
		
		  $preprocaid .="<input type='checkbox' checked='true' title='$csp_trademarkname' name='arr_content[]' value='$arr_tradid[$i]' onclick='copyItem(\"previewItem\",\"previewItem\");same(this);'>".$csp_trademarkname;
		   $thumrul	= $propiclist[0];

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
$t->set_var("toptradlogo"  , $toptradlogo     );      //id 
$t->set_var("preprocaid"  , $preprocaid     );      //id 
                                                 
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

