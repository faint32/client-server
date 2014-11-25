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

	  
	case "add":  //新增数据
	   if(!empty($arr_tradmarkid))
	   {
	   $toptradmarkid=implode(",", $arr_tradmarkid);
	   }
	  
        $query = "update web_usefultype set fd_usefultype_tradlogo      = '$toptradmarkid'   where fd_usefultype_id = '$listid' ";
	    $db->query($query);   //修改单据资料
	   // echo $query;
	  	$action   = "";
	  break;
	  
    case "del":   //删除细节表数据
	$propiclist= removeFilepath($vid);
	//$params1 = array('id'=>$vid);
	//$propiclist = $client->call('removeFilepath',$params1);

      $toaction="";
	  break;
	default:
	 
		  $action   = "";
	  break;
}




$t = new Template(".", "keep");          //调用一个模版
$t->set_file("usefultype","showpro.html"); 

if(empty($listid)){		// 新增
   $action = "new";
}else{
   $query = "select * from web_usefultype where fd_usefultype_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid        = $db->f(fd_usefultype_id);                 //id号  
       $name = $db->f(fd_usefultype_name);      //单据编号
     
   }
}

//原商品显示列表
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

$t->pparse("out", "usefultype");    # 最后输出页面
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

