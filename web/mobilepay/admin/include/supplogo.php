<?
$thismenucode = "1c613";
require ("../include/common.inc.php");
require ("../include/pageft.php");
require_once('../../include/global.session.php');
require_once('../nusoap/basicfunction.php');
$db = new DB_msweb ;
$t = new Template('.', "keep");
$t->set_file("supplogo","supplogo.html");
$t->set_block("supplogo", "BXBK", "bxbks");
$scatid=10;


$query = "select fd_supp_id,fd_supp_name from  tb_supplier 
         group by fd_supp_id order by fd_supp_name asc";
   $dberp->query($query);
   if($dberp->nf()){
    while($dberp->next_record()){
    	$suppid = $dberp->f(fd_supp_id);
		$arr_suppname[$suppid] = $dberp->f(fd_supp_name);
    	
	}
    }	

//搜索
if(empty($search))
{
	$querywhere="";
}else{
	$querywhere="where fd_supplierlogo_id like '%$content%'";
}

$query="select fd_supplierlogo_id,fd_supplogo_suppid from web_conf_supplierlogo  $querywhere";
$db->query($query);

$total=$db->nf();
pageft($total,10,$str_url);

if($firstcount<0){
	$firstcount=0;
}
$query = "$query limit $firstcount,$displaypg";
$db->query($query);

if($db->nf()){
	while($db->next_record()){
		$fd_supplierlogo_id        = $db->f(fd_supplierlogo_id);
		$fd_supplogo_suppid        = $db->f(fd_supplogo_suppid);
		$fd_supplogo_suppname      = $arr_suppname[$fd_supplogo_suppid]; 
		$result=uploadshow($fd_supplogo_suppid,$scatid);
		$thum=$result["thumurl"];
		$thum=$thum ? "<img src='$g_showpic$thum' />":"";
		
		$e='<a href=javascript:supploge("'.$g_uppic.'","'.$scatid.'","'.$fd_supplogo_suppid.'"); >修改</a>';
		$a='<a href=javascript:deleteimg("'.$g_uppic.'","'.$scatid.'","'.$fd_supplogo_suppid.'");>删除</a>';
		$edit=$thum ? $e.'| &nbsp;'.$a : $e.'| &nbsp;<a href="javascript:;">删除</a>'; 
	  
	  $t->set_var(array(  "fd_supplierlogo_id"         => $fd_supplierlogo_id ,
	  		              "fd_supplogo_suppid"         => $fd_supplogo_suppid ,
						  "fd_supplogo_suppname"       => $fd_supplogo_suppname,
						  "thum"                       => $thum               ,
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