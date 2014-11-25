<?
$thismenucode = "1c613";
require ("../include/common.inc.php");
require ("../../include/pageft.php");

require_once('../nusoapclient/AutoGetFileimg.php');

$db = new DB_test ;
$dberp = new DB_erp ;
$getFileimg = new AutogetFile();

switch($toaction){
	case "setdef":   //删除细节表数据
  
    $query1= "update web_conf_supplierlogo set fd_supplogo_tuijian = '$tuijian' where fd_supplierlogo_id='$vid'";
	$db->query($query1);

    $toaction  = "";   
	  break;
}
$t = new Template('.', "keep");
$t->set_file("supplogo","supplogo.html");
$t->set_block("supplogo", "BXBK", "bxbks");
$scatid=10;


//搜索
if(empty($search))
{
	$querywhere="";
}else{
	$querywhere="where fd_supplogo_suppname like '%$content%'";
}

$query="select fd_supplierlogo_id,fd_supplogo_suppid,fd_supplogo_suppname,fd_supplogo_tuijian from web_conf_supplierlogo 
  $querywhere order by fd_supplogo_tuijian desc ";
$db->query($query);

$total=$db->nf();
pageft($total,7,$str_url);

if($firstcount<0){
	$firstcount=0;
}
$query = "$query limit $firstcount,$displaypg";
$db->query($query);

if($db->nf()){
	while($db->next_record()){
		$fd_supplierlogo_id        = $db->f(fd_supplierlogo_id);
		$fd_supplogo_suppid        = $db->f(fd_supplogo_suppid);
		$fd_supplogo_suppname      = $db->f(fd_supplogo_suppname);
		$fd_supplogo_tuijian       = $db->f(fd_supplogo_tuijian);
		
		$getFileimg = new AutogetFile();
	    $csp_get_img       = explode("@@",$getFileimg->AutogetFileimg($scatid,$fd_supplierlogo_id));
		
	
		$thumimg= $csp_get_img[0];
		
	
		$thumimg=$thumimg ? "<img src='".$thumimg."' width=120 height=50/>":"";
		$tuijian=$fd_supplogo_tuijian=='1'? "推荐":"";
	
		$edit='<a href="#" onclick=uploadimg("'.$scatid.'","'.$fd_supplierlogo_id.'","","uploadfile","new","refeedback","preuploadfile",this); id="uploadfilecolorbox'.$fd_supplierlogo_id.'" >上传</a> | ';
		$edit .=$fd_supplogo_tuijian=='1'?'<a href="#" onClick=edit_p("'.$fd_supplierlogo_id.'",0)>取消推荐</a>':'<a href="#" onClick=edit_p("'.$fd_supplierlogo_id.'","1")>设为推荐</a>';
		
	  
	  $t->set_var(array(  "fd_supplierlogo_id"         => $fd_supplierlogo_id ,
	  		              "fd_supplogo_suppid"         => $fd_supplogo_suppid ,
						  "fd_supplogo_suppname"       => $fd_supplogo_suppname,
						  "tuijian"                    => $tuijian,
						  "thum"                       => $thumimg               ,
						  "edit"                       => $edit               ,
	  		   ));
	 $t->set_var("none" ,"");			   
	 $t->parse("bxbks", "BXBK", true);		   
	}
}else{
$t->set_var(array("fd_supplierlogo_id"         => ""  ,
				  "fd_supplogo_suppid"         => ""  ,
				  "thum"                       => ""  ,
				  "edit"                       => ""  ,
			   ));
	$t->set_var("none" ,"none");		   
	$t->parse("bxbks"  , "BXBK", true);
}

$t->set_var("scatid"  ,$scatid   );
$t->set_var("g_uppic" ,$g_uppic  );
$t->set_var("pagenav" ,$pagenav  );			   
$t->set_var("skin"    ,$loginskin);
$t->pparse("out"      , "supplogo"  );    # 最后输出页面