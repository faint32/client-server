<?
$thismenucode = "1c611";
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

	  
	case "add":  //新增数据
	   if(!empty($arr_tradmarkid))
	   {
	   $toptradmarkid=implode(",", $arr_tradmarkid);
	   }
	  
        $query = "update web_conf_showpro set fd_csp_adpro      = '$toptradmarkid'   where fd_csp_id = '$listid' ";
	    $db->query($query);   //修改单据资料
	   // echo $query;
	  	$action   = "";
	  break;
	  
 
	default:
	 
		  $action   = "";
	  break;
}




$t = new Template(".", "keep");          //调用一个模版
$t->set_file("conf_showpro","adpro.html"); 

if(empty($listid)){		// 新增
   $action = "new";
}else{
   $query = "select * from web_conf_showpro where fd_csp_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_csp_id);                 //id号  
       $toptradmarkid = $db->f(fd_csp_adpro);      //单据编号
       $procaname     = $db->f(fd_csp_procaname);          //录单日期
	   $procaid       = $db->f(fd_csp_procatid);          //录单日期
   }
}

//原商品显示列表
$t->set_block("conf_showpro", "prolist"  , "prolists"); 
if($toptradmarkid<>'')
{
$arr_tradid = explode(",",$toptradmarkid);
$getFileimg = new AutogetFile();
for($i=0;$i<count($arr_tradid);$i++)
{    
       
		     $dateid   	= $arr_tradid[$i];
		    $query = "select fd_trademark_name from  tb_trademark 
                             where fd_trademark_id = '$dateid'";                              
	
		$db->query($query);
		//echo $query;
		if($db->nf()){
		while($db->next_record()){
					$csp_trademarkname = $db->f(fd_trademark_name);
		}}
		
		  
		   
		  if(($i+1)%6==0){$csp_trademarkname .="<br>";}
		$preprocaid .="<input type='checkbox' checked='true' title='$usefultype_usefulname' name='arr_content[]' value='$arr_tradid[$i]' onclick='copyItem(\"previewItem\",\"previewItem\");same(this);'>".$usefultype_usefulname;
		   $t->set_var(array("trid"     => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $dateid           ,
                         "csp_trademarkname" => $csp_trademarkname     ,
                         "vedit"        => $vedit     ,
                         "vpic"         => $vpic     ,
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
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("procaname"    , $procaname     );      //id 
$t->set_var("toptradmarkid", $toptradmarkid     );      //id 
$t->set_var("procaid"      , $procaid     );      //id 

                                                 
$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      
                                            
$t->set_var("checkid"      , $checkid    );      //批量删除商品ID   

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "conf_showpro");    # 最后输出页面
//生成选择菜单的函数
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

