<?
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "yewupayset.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
switch($mode)  //费率计算方式
{
	case "fix":
	$minfee = "";
	$maxfee = "";
	$sqfee = "";
	break;
	default:
	$fee = "";
	break;
}
switch ($action) {
	case "edit" :
		$query = "select * from tb_creditcardset";
		$db->query($query);
		if ($db->nf()) {
			$query = "update tb_creditcardset set fd_creditcset_dayslotcard='$dayslotcard',
					  fd_creditcset_slotcardmoney ='$slotcardmoney',fd_creditcset_fee ='$fee',
					  fd_creditcset_maxmoney ='$maxmoney',fd_creditcset_maxfee ='$maxfee',
					  fd_creditcset_minfee='$minfee',fd_creditcset_sqfee ='$sqfee%',fd_creditcset_monthslotcard='$monthslotcard',
					  fd_creditcset_mode='$mode',fd_creditcset_payfeedirct='$defeedirct',fd_creditcset_arriveid='$arriveid',
					  fd_creditcset_whenpayfee='$whenpayfee'
					  where fd_creditcset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		} else {
			$query="INSERT INTO tb_creditcardset (
 		            fd_creditcset_slotcardmoney,fd_creditcset_fee,
					fd_creditcset_maxmoney,fd_creditcset_maxfee ,
					fd_creditcset_minfee,fd_creditcset_sqfee,
					fd_creditcset_monthslotcard,fd_creditcset_mode,
					fd_creditcset_dayslotcard,fd_creditcset_payfeedirct,
					fd_creditcset_arriveid,fd_creditcset_whenpayfee
  		          )VALUES (
  		          '$slotcardmoney','$fee','$maxmoney','$maxfee'   ,'$minfee	',
				  '$sqfee%' ,  '$monthslotcard', '$mode' , '$dayslotcard','$defeedirct','$arriveid','$whenpayfee'
				)";
		   $db->query($query);
		    require ("../include/alledit.2.php");
			Header("Location: $gotourl");
	}
		$action = "";
		break;
	case "delete" :
		$query = "delete from tb_creditcardset";
		$db->query($query);
		require ("../include/alledit.2.php");

		Header("Location: $gotourl");
		break;
	default :
		$action = "";
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author", "creditcardset.html");

	$action = "edit";
	$query = "select * from tb_creditcardset ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_creditcset_id);
		$slotcardmoney = $db->f(fd_creditcset_slotcardmoney);
		$dayslotcard = $db->f(fd_creditcset_dayslotcard);
		$minfee = $db->f(fd_creditcset_minfee);
		$sqfee = substr($db->f(fd_creditcset_sqfee),0,-1);
		$maxmoney = $db->f(fd_creditcset_maxmoney);
		$fee = $db->f(fd_creditcset_fee);
		$maxfee 	  = $db->f(fd_creditcset_maxfee);
		$monthslotcard = $db->f(fd_creditcset_monthslotcard);
		$mode = $db->f(fd_creditcset_mode);
		$defeedirct=$db->f(fd_creditcset_payfeedirct);
		$arriveid=$db->f(fd_creditcset_arriveid);
		$whenpayfee=$db->f(fd_creditcset_whenpayfee);
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
switch($defeedirct)  //扣除费率方
{
	case "s":
	$checkdefeedirct2 = "checked";
	break;
	default:
	$checkdefeedirct1 = "checked";
	break;
}
switch($whenpayfee)  //费率扣取节点
{
	case "post":
	$checkdwhenpayfee1 = "checked";//通付宝刷卡时
	break;
	default:
	$checkdwhenpayfee2 = "checked";//支金代付款时
	break;
}

$query = "select * from tb_arrive ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_arriveid[] = $db->f(fd_arrive_id);
		$arr_arrivename[] = $db->f(fd_arrive_name);
	}
}
$arriveid = makeselect($arr_arrivename, $arriveid, $arr_arriveid);

$t->set_var("checkmode1", $checkmode1);
$t->set_var("checkmode2", $checkmode2);
$t->set_var("checkdefeedirct1", $checkdefeedirct1);
$t->set_var("checkdefeedirct2", $checkdefeedirct2);
$t->set_var("checkdwhenpayfee1", $checkdwhenpayfee1);
$t->set_var("checkdwhenpayfee2", $checkdwhenpayfee2);

$t->set_var("listid", $listid); //listid
$t->set_var("dayslotcard", $dayslotcard);
$t->set_var("fee", $fee);
$t->set_var("sqfee", $sqfee);
$t->set_var("minfee", $minfee);
$t->set_var("maxmoney", $maxmoney);
$t->set_var("fee", $fee);
$t->set_var("slotcardmoney", $slotcardmoney);
$t->set_var("monthslotcard", $monthslotcard);
$t->set_var("maxfee", $maxfee);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址    
$t->set_var("fckeditor", $fckeditor);
$t->set_var("arriveid", $arriveid);
$t->set_var("error", $error);
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author"); # 最后输出页面

?>

