<?
$thismenucode = "2k502";
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "tb_payfeeset_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
switch($mode)  //费率计算方式
{
	case "fix":
	$minfee = "";
	$maxfee = "";
	$maxfee = "";
	break;
	default:
	$fixfee = "";
	break;
}
switch ($action) {
	case "new" :
		$query = "select 1 from tb_payfeeset where  fd_payfset_auindustryid='$auindustryid' and 1=2 ";
		$db->query($query);
		if($mode=="fix"){$sqltle=',fd_payfset_fixfee';$sqlvalue=",'$fixfee'";}
		if($mode=="per"){$sqltle=',fd_payfset_fee,fd_payfset_minfee,fd_payfset_maxfee';$sqlvalue="'$fee%','$minfee','$maxfee'";}
		if ($db->nf()) {
			$error = "该行业已经添加了费率，不需要重复添加";
		} else {
			$query = "INSERT INTO tb_payfeeset(
											    fd_payfset_no,fd_payfset_auindustryid,fd_payfset_mode,
												fd_payfset_arriveid ,fd_payfset_defeedirct,fd_payfset_name	,fd_payfset_scope 								
												$sqltle ,fd_payfset_datetime) VALUES(
											   '$no','$auindustryid','$mode','$arriveid','$defeedirct','$name','$scope',
											   $sqlvalue,now())";
			
			$db->query($query);
			//echo $query;
			$listid = $db->insert_id();
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "edit" :
		if($mode=="fix"){$sqlvalue=",fd_payfset_fixfee='$fixfee',fd_payfset_fee='',fd_payfset_minfee='',fd_payfset_maxfee=''";}
		if($mode=="per"){$sqlvalue=",fd_payfset_fixfee='',fd_payfset_fee='$fee%',fd_payfset_minfee='$minfee',fd_payfset_maxfee='$maxfee'";}
		$query = "select 1 from tb_payfeeset where  fd_payfset_auindustryid='$auindustryid'and 1=2 and  fd_payfset_id<>'$listid' ";
		$db->query($query);
		if ($db->nf()) {
			$error = "该行业的费率已经添加，不需要修改";
		} else {
			$query = "update tb_payfeeset set fd_payfset_no='$no',fd_payfset_mode ='$mode', fd_payfset_scope = '$scope'," .
					 "fd_payfset_arriveid ='$arriveid',fd_payfset_defeedirct ='$defeedirct',fd_payfset_name ='$name',
					  fd_payfset_auindustryid='$auindustryid' $sqlvalue ,fd_payfset_datetime=now() where fd_payfset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "delete" :
		$query="select * from tb_author where fd_author_bkcardpayfsetid='$listid' or fd_author_slotpayfsetid='$listid'";
		$db->query($query);
		if($db->nf())
		{
			$error='该套餐已绑定商户,请先取消绑定!';
		}else{
			$query = "delete from tb_payfeeset where fd_payfset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		break;
	default :
		$action = "";
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author", "payfeeset.html");

if (!isset ($listid)) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_payfeeset where fd_payfset_id='$listid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_payfset_id);
		$mode = $db->f(fd_payfset_mode);
		$no = $db->f(fd_payfset_no);
		$auindustryid = $db->f(fd_payfset_auindustryid);
		$fee = substr($db->f(fd_payfset_fee),0,-1);
		$minfee = $db->f(fd_payfset_minfee);
		$maxfee = $db->f(fd_payfset_maxfee);
		$defeedirct = $db->f(fd_payfset_defeedirct);
		$arriveid = $db->f(fd_payfset_arriveid);
		$scope 	  = $db->f(fd_payfset_scope);
		$name 	  = $db->f(fd_payfset_name);
		$fixfee   = $db->f(fd_payfset_fixfee);
	}

}
switch($defeedirct)  //扣除费率方
{
	case "s":
	$checkdefeedirct2 = "checked";
	break;
	default:
	$checkdefeedirct1 = "checked";
	break;
}
switch($mode)  //费率计算方式
{
	case "fix":
	$checkmode1 = "checked";
	break;
	default:
	$checkmode2 = "checked";
	break;
}
$t->set_var("checkmode1", $checkmode1);
$t->set_var("checkmode2", $checkmode2);
$t->set_var("checkdefeedirct1", $checkdefeedirct1);
$t->set_var("checkdefeedirct2", $checkdefeedirct2);
//

//
//$arr_defeeno = array (
//	"f",
//	"s"
//);
//$arr_defee = array (
//	"付款方",
//	"收款方"
//);
//$defeedirct = makeselect($arr_defee, $defeedirct, $arr_defeeno);
//
//$arr_modeno = array (
//	"fix",
//	"per"
//);
//$arr_mode = array (
//	"固定费率",
//	"浮动费率"
//);
//$mode = makeselect($arr_mode, $mode, $arr_modeno);

$arr_scopeno = array (
	"creditcard",
	"bankcard"
);
$arr_scopename = array (
	"信用卡",
	"储蓄卡"
);
$scope = makeselect($arr_scopename, $scope, $arr_scopeno);

$query = "select * from tb_authorindustry ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_auindustryid[] = $db->f(fd_auindustry_id);
		$arr_auindustry[] = $db->f(fd_auindustry_name);
	}
}
$auindustryid = makeselect($arr_auindustry, $auindustryid, $arr_auindustryid);
$query = "select * from tb_arrive ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_arriveid[] = $db->f(fd_arrive_id);
		$arr_arrivename[] = $db->f(fd_arrive_name);
	}
}
$arriveid = makeselect($arr_arrivename, $arriveid, $arr_arriveid);

$t->set_var("listid", $listid); //listid
$t->set_var("no", $no);
$t->set_var("auindustryid", $auindustryid);
$t->set_var("arriveid", $arriveid);
$t->set_var("fee", $fee);
$t->set_var("minfee", $minfee);
$t->set_var("maxfee", $maxfee);
$t->set_var("fixfee", $fixfee);
$t->set_var("defeedirct", $defeedirct);
$t->set_var("arriveid", $arriveid);
$t->set_var("scope", $scope);
$t->set_var("name", $name);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址    
$t->set_var("fckeditor", $fckeditor);
$t->set_var("error", $error);
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author"); # 最后输出页面
?>

