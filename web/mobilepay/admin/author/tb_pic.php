<?php

$thismenucode = "1c205";
require ("../include/common.inc.php");
$db = new DB_test ( );
$db1 = new DB_test ( );
require_once ('../nusoapclient/AutogetFileimg.php');
$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "tb_pic", "tb_pic.html" );
$query = "select * from tb_author";
$db->query ( $query );
if ($db->nf ()) {
	while ( $db->next_record () ) {
		$authorid [] = $db->f ( fd_author_id );
	}
}
if ($authorid != '') {
	$arr_authorid = $authorid;
	for($i = 0; $i < count ( $arr_authorid ); $i ++) {
		$dateid = $arr_authorid [$i];
		//echo $dateid."</br>";
		$getFileimg = new AutogetFile ( );
		
		//$propiclist  = explode("@@",$getFileimg->AutogetFileimg(1,$dateid));
		

		$query1 = "select * from tb_author where fd_author_id='$dateid'";
		$db1->query ( $query1 );
		if ($db1->nf ()) {
			$db1->next_record ();
			$protraname = $db1->f ( fd_author_username );
			$tradindexrec = $db1->f ( fd_procatrad_indexrec );
		}
		$preprocaid .= "<input type='checkbox' checked='true' title='$protraname' name='arr_count[]' value='$arr_authorid[$i]' onclick='copyItem(\"previewItem\",\"previewItem\");same(this);'>" . $protraname;
		$thumrul = $propiclist [0];
		if ($thumrul != "") {
			$showtitle = $tradindexrec ? "<span style='color:#0000ff'>取消首页推荐</span>" : "<span style='color:red'>设为首页推荐</span>";
			$vpic = "<img src='" . $propiclist [0] . "' title='" . $protraname . "' alt='" . $protraname . "'
				               width='100' style='height:40px;'>";
			$vedit = '<a href="#" onClick="del_p(' . $propiclist [1] . ')">删除</a>|<a href="#" onclick="set_indexrec(' . $dateid . ',' . $tradindexrec . ')">' . $showtitle . '</a>';
		} else {
			
			$vpic = "<img src='' width='100'  title='" . $protraname . "' alt='" . $protraname . "' style='height:40px;'>";
			$vedit = '<input type=hidden name=uploadfile  id="uploadfile' . $dateid . '" value="">
				 <input type="button" class="buttonsmall" name="upmorefiles" value="上传图片" 
				 onclick=uploadimg("36","' . $dateid . '","' . $propiclist [1] . '","uploadfile","new","refeedback","preuploadfile",this);  >';
		}
		
		$showselect = "none";
		
		$t->set_var ( array ("trid" => $trid, "imgid" => $imgid, "vid" => $dateid, "protraname" => $protraname, "vedit" => $vedit, "vpic" => $vpic, "bgcolor" => $bgcolor, "showselect" => $showselect ) );
		$t->parse ( "prolists", "prolist", true );
	}
} else {
	$trid = "tr1";
	$imgid = "img1";
	$t->set_var ( array ("trid" => $trid, "imgid" => $imgid, "vid" => "", "protraname" => "", "vpic" => "", "vedit" => "", "bgcolor" => "#ffffff", "showselect" => "" ) );
	$t->parse ( "prolists", "prolist", true );

}

$t->set_var ( "listid", $listid ); //单据id 
$t->set_var ( "ihotprotraname", $ihotprotraname ); //id 
$t->set_var ( "toptradmarkid", $toptradmarkid ); //id 
$t->set_var ( "iareaid", $iareaid ); //id 
$t->set_var ( "authorid", $authorid ); //id 
$t->set_var ( "preprocaid", $preprocaid ); //id 


$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址
$t->set_var ( "error", $error );

$t->set_var ( "checkid", $checkid ); //批量删除商品ID   


// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "tb_pic" ); # 最后输出页面


?>