<?
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "yewupayset.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");
switch($mode)  //费率计算方式
{
	case "fix":
	$minperfee = "";
	$maxperfee = "";
	$sqperfee = "";
	break;
	default:
	$perfee = "";
	break;
}
switch ($action) {
	case "edit" :
	$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype='tfmg'";
		$db->query($query);
		if ($db->nf()) {
			$query = "update tb_transfermoneyset set fd_transfermoneyset_dayslotcardcount='$dayslotcardcount',
					  fd_transfermoneyset_slotcardmoney ='$slotcardmoney',fd_transfermoneyset_perfee ='$perfee',
					  fd_transfermoneyset_datemaxcount ='$datemaxcount',fd_transfermoneyset_maxperfee ='$maxperfee',
					  fd_transfermoneyset_minperfee='$minperfee',fd_transfermoneyset_sqperfee ='$sqperfee%',
					  fd_transfermoneyset_mode ='$mode',fd_transfermoneyset_defeedirct='$defeedirct',
					  fd_transfermoneyset_arriveid='$arriveid'
					  where fd_transfermoneyset_id='$listid'";
			//echo $sqperfee;exit;
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
			}else{
				$query="INSERT INTO tb_transfermoneyset (
 		            fd_transfermoneyset_dayslotcardcount,fd_transfermoneyset_slotcardmoney,
					fd_transfermoneyset_perfee,fd_transfermoneyset_datemaxcount ,
					fd_transfermoneyset_maxperfee,fd_transfermoneyset_minperfee,
					fd_transfermoneyset_sqperfee,fd_transfermoneyset_mode,fd_transfermoneyset_defeedirct,
					fd_transfermoneyset_arriveid
  		          )VALUES (
  		          '$dayslotcardcount','$slotcardmoney','$perfee','$datemaxcount'   ,'$maxperfee	',
				  '$minperfee' ,  '$sqperfee%', '$mode','$defeedirct','$arriveid'
				)";
		   $db->query($query);
		    require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "delete" :
		$query = "delete from tb_transfermoneyset where fd_transfermoneyset_id='$listid'";
		$db->query($query);
		require ("../include/alledit.2.php");

		Header("Location: $gotourl");
		break;
	default :
		$action = "";
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author", "transfermoneyset.html");

	$action = "edit";
	$query = "select * from tb_transfermoneyset where fd_transfermoneyset_tfmgtype='tfmg'";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_transfermoneyset_id);
		$slotcardmoney = $db->f(fd_transfermoneyset_slotcardmoney);  //每笔最大额度 
		$dayslotcardcount = $db->f(fd_transfermoneyset_dayslotcardcount);
		$minperfee = $db->f(fd_transfermoneyset_minperfee);
		$sqperfee = substr($db->f(fd_transfermoneyset_sqperfee),0,-1);
		$datemaxcount = $db->f(fd_transfermoneyset_datemaxcount);
		$perfee = $db->f(fd_transfermoneyset_perfee);
		$maxperfee 	  = $db->f(fd_transfermoneyset_maxperfee);
		$mode =$db->f(fd_transfermoneyset_mode);
		$defeedirct=$db->f(fd_transfermoneyset_defeedirct);
		$arriveid=$db->f(fd_transfermoneyset_arriveid);
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
//

//
//$arr_defeedayslotcardcount = array (
//	"f",
//	"s"
//);
//$arr_defee = array (
//	"付款方",
//	"收款方"
//);
//$datemaxcount = makeselect($arr_defee, $datemaxcount, $arr_defeedayslotcardcount);
//
//$arr_slotcardmoneydayslotcardcount = array (
//	"fix",
//	"per"
//);
//$arr_slotcardmoney = array (
//	"固定费率",
//	"浮动费率"
//);
//$slotcardmoney = makeselect($arr_slotcardmoney, $slotcardmoney, $arr_slotcardmoneydayslotcardcount);
$t->set_var("arriveid", $arriveid);
$t->set_var("listid", $listid); //listid
$t->set_var("dayslotcardcount", $dayslotcardcount);
$t->set_var("perfee", $perfee);
$t->set_var("sqperfee", $sqperfee);
$t->set_var("minperfee", $minperfee);
$t->set_var("datemaxcount", $datemaxcount);
$t->set_var("perfee", $perfee);
$t->set_var("slotcardmoney", $slotcardmoney);
$t->set_var("maxperfee", $maxperfee);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址    
$t->set_var("fckeditor", $fckeditor);
$t->set_var("error", $error);
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author"); # 最后输出页面
?>

