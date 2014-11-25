<?
$thismenucode = "2k305";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_poswarn_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $warnlevellevel = $_POST['warnlevellevel'];
	   $warnleveltype = $_POST['warnleveltype'];
	   $creditcard = $_POST['creditcard'];
	   $cashcard = $_POST['cashcard'];
	   $postnum = $_POST['postnum'];
	   $average = $_POST['average'];
	   $scale = $_POST['scale'];
	   $query="INSERT INTO tb_warnlevel(
			   fd_warnlevel_level,fd_warnlevel_typeid,fd_warnlevel_creditcard,fd_warnlevel_cashcard,fd_warnlevel_postnum,
			   fd_warnlevel_average,fd_warnlevel_scale) VALUES(
			   '$warnlevellevel','$warnleveltype','$creditcard','$cashcard','$postnum','$average','$scale')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
	   break;
	 case "edit":
	   $warnlevellevel = $_POST['warnlevellevel'];
	   $warnleveltype = $_POST['warnleveltype'];
	   $creditcard = $_POST['creditcard'];
	   $query="update tb_warnlevel set 
	   			fd_warnlevel_level='$warnlevellevel',fd_warnlevel_typeid='$warnleveltype',
				fd_warnlevel_creditcard='$creditcard', fd_warnlevel_cashcard='$cashcard',
				fd_warnlevel_postnum='$postnum',fd_warnlevel_average='$average',fd_warnlevel_scale='$scale'
				where fd_warnlevel_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
	   break;
	 case "delete":
	   $query="delete from tb_warnlevel where fd_warnlevel_id='$listid'";
	   $db->query($query);
	   //require("../include/alledit.2.php");

	   //Header("Location: $gotourl");	
	   break;
	 default:
	 	$action="";
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("poswarn","poswarn.html"); 

if(!isset($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_warnlevel where fd_warnlevel_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$warnlevalid = $db->f(fd_warnlevel_id);
		$warnlevellevel =$db->f(fd_warnlevel_level);	
		$warnleveltype =$db->f(fd_warnlevel_typeid);	
		$creditcard =$db->f(fd_warnlevel_creditcard);
		$cashcard=$db->f(fd_warnlevel_cashcard);
		$postnum=$db->f(fd_warnlevel_postnum);
		$average=$db->f(fd_warnlevel_average);
		$scale=$db->f(fd_warnlevel_scale);		
	}
		
	
}
    $arrlevel = array('highest','high','middle','low'); 
	$arrlevelval = array('极高','高','中','低'); 
	$levelsel = makeselect($arrlevelval,$warnlevellevel,$arrlevel);
	
$query="select fd_auindustry_id,fd_auindustry_name from tb_authorindustry";	
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	$arr_auinid[]=$db->f(fd_auindustry_id);
	$arr_auinname[]=$db->f(fd_auindustry_name);
	}
}
$auindustrysel = makeselect($arr_auinname,$warnleveltype,$arr_auinid);

$t->set_var("id"           , $id           );    
$t->set_var("listid"           , $listid           );        //listid
$t->set_var("poswarn_id"     , $poswarn_id           );        
$t->set_var("warnlevellevel"         , $warnlevellevel         );
$t->set_var("warnleveltype"         , $warnleveltype         );
$t->set_var("creditcard"         , $creditcard         );
$t->set_var("levelsel"         , $levelsel         );
$t->set_var("auindustrysel"         , $auindustrysel         );
$t->set_var("cashcard"     , $cashcard           );        
$t->set_var("postnum"         , $postnum         );
$t->set_var("average"         , $average         );
$t->set_var("scale"         , $scale         );

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "poswarn");    # 最后输出页面

?>

