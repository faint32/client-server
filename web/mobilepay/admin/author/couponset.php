<?
$thismenucode = "sys";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("couponset","couponset.html"); 
$gourl = "yewupayset.php";

$gotourl = $gourl.$tempurl ;
switch ($action)
{	
	case "new":

			$query="INSERT INTO tb_couponset (
 		            fd_couponset_fee,
					fd_couponset_payfeedirct,
					fd_couponset_arriveid," .
				   "fd_couponset_maxfee
  		          )VALUES (
  		          '$fee%','$whenpayfee','$arriveid'    
				        )";
		   $db->query($query);
		  require ("../include/alledit.2.php");
			Header("Location: $gotourl"); 	
		break;
	case "edit":   // 修改记录	

         $query = "update tb_couponset set
 		               fd_couponset_fee   = '$fee%',
					   fd_couponset_payfeedirct   = '$whenpayfee',
					   fd_couponset_arriveid	='$arriveid', fd_couponset_maxfee = '$maxfee' 
  		             where fd_couponset_id = '$listid' ";
	      $db->query($query);
		require ("../include/alledit.2.php");
		Header("Location: $gotourl"); 	  
		break;
}

	$query="select * from tb_couponset";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$listid=$db->f(fd_couponset_id);
		$fee=$db->f(fd_couponset_fee);
		$whenpayfee=$db->f(fd_couponset_payfeedirct);
		$arriveid=$db->f(fd_couponset_arriveid);
		$maxfee  =$db->f(fd_couponset_maxfee);
		$fee = substr($fee,0,-1);
		$action="edit";
	}else{
		$action="new";
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
$t->set_var("listid"     , $listid     );
$t->set_var("fee"     , $fee     );
$t->set_var("whenpayfee"     , $whenpayfee     );
$t->set_var("arriveid"     , $arriveid     );
$t->set_var("maxfee"       , $maxfee       );
$t->set_var("checkdwhenpayfee1", $checkdwhenpayfee1);
$t->set_var("checkdwhenpayfee2", $checkdwhenpayfee2);
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "couponset");    # 最后输出页面


?>

