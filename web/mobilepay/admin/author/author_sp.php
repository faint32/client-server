<?
$thismenucode = "2k301";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");
require ("../third_api/readshopname.php");
$getFileimg = new AutogetFile;
$db = new DB_test();

$gourl = "tb_author_sp_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "save" :
		$phone = $_POST[FCKeditor1];
		$query = "select * from tb_author where fd_author_username='$name' and fd_author_id<>'$listid'";
		$db->query($query);
		if ($db->nf()) {
			$error = "用户名有冲突，请重新查证！";
		} else {
			$query = "update tb_author set 
			           fd_author_state='$state' ,
			           fd_author_mobile='$mobile' ,
					   fd_author_idcard='$idcard',
					   fd_author_email='$email',
					   fd_author_isstop='0'," .
					   	"fd_author_state = '9'," .
					   	"fd_author_truename = '$truename',
					   fd_author_sdcrid = '$sdcrid'," .
					   "fd_author_auindustryid = '$auindustryid',
		               fd_author_slotpayfsetid = '$slotpayfsetid',
		               fd_author_slotscdmsetid = '$slotscdmsetid',
					   fd_author_bkcardpayfsetid='$bkcardpayfsetid',
					   fd_author_bkcardscdmsetid='$bkcardscdmsetid' ,
						fd_author_shoucardman = '$shoucardman',
		               fd_author_shoucardphone = '$shoucardphone',
					   fd_author_shoucardno='$shoucardno',
					   fd_author_shoucardbank='$shoucardbank'," .
					  "fd_author_authortypeid = '$authortypeid' ,
					  fd_author_datetime      = now()
					   						
					   where fd_author_id='$listid'";
			$db->query($query);
			//echo $query;
			if ($userpw) {
				$userpw = md5($userpw);
				$query = "update  tb_author set fd_author_password = '$userpw' where fd_author_id = '$listid'";
				$db->query($query);
			}
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
		case "edit" :
		$phone = $_POST[FCKeditor1];
		$query = "select * from tb_author where fd_author_username='$name' and fd_author_id<>'$listid'";
		$db->query($query);
		if ($db->nf()) {
			$error = "用户名有冲突，请重新查证！";
		} else {
			$query = "update tb_author set 
			           fd_author_state='$state' ,
			           fd_author_mobile='$mobile' ,
					   fd_author_idcard='$idcard',
					   fd_author_email='$email',
					   fd_author_isstop='$isstop',
					   fd_author_state = '$state',
					   fd_author_sdcrid = '$sdcrid'," .
					  "fd_author_truename = '$truename',
					   fd_author_auindustryid = '$auindustryid',
		               fd_author_slotpayfsetid = '$slotpayfsetid',
		               fd_author_slotscdmsetid = '$slotscdmsetid',
					   fd_author_bkcardpayfsetid='$bkcardpayfsetid',
					   fd_author_bkcardscdmsetid='$bkcardscdmsetid',  
						fd_author_shoucardman = '$shoucardman',
		               fd_author_shoucardphone = '$shoucardphone',
					   fd_author_shoucardno='$shoucardno',
					   fd_author_shoucardbank='$shoucardbank'," .
					  "fd_author_authortypeid = '$authortypeid',
					  fd_author_datetime      = now() 
					   where fd_author_id='$listid'";
			$db->query($query);

			if ($userpw) {
				$userpw = md5($userpw);
				$query = "update  tb_author set fd_author_password = '$userpw' where fd_author_id = '$listid'";
				$db->query($query);
			}
			require ("../include/alledit.2.php");
			//Header("Location: author_sp.php?list=$listid");
		}
		$action = "";
		break;	
	
	case "delete" :
		$query = "delete from tb_author where fd_author_id='$listid'";
		$db->query($query);
		
		$query = "update  tb_paycard set fd_paycard_authorid='' where fd_paycard_authorid='$listid'";
		$db->query($query);
		
		require ("../include/alledit.2.php");
		Header("Location: $gotourl");
		break;
}

$t = new Template(".", "keep"); //调用一个模版
$t->set_file("author_sp", "author_sp.html");

if (!isset ($listid)) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_author where fd_author_id='$listid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_author_id);
		$name = $db->f(fd_author_username);
		$truename = $db->f(fd_author_truename);
		$mobile = $db->f(fd_author_mobile);
		$zcdate = $db->f(fd_author_regtime);
		$idcard = $db->f(fd_author_idcard);
		$email = $db->f(fd_author_email);
		$state = $db->f(fd_author_state);
		$isstop = $db->f(fd_author_isstop);
		$audtid = $db->f(fd_author_auindustryid);
		$slotpayfsetid = $db->f(fd_author_slotpayfsetid);
		$slotscdmsetid = $db->f(fd_author_slotscdmsetid);
		$bkcardpayfsetid=$db->f(fd_author_bkcardpayfsetid);
		$bkcardscdmsetid=$db->f(fd_author_bkcardscdmsetid);
	
        $sdcrid    = $db->f(fd_author_sdcrid);
		$shopid = $db->f(fd_author_shopid);
		$shopname = getauthorshop($shopid);
		
		$shoucardman = $db->f(fd_author_shoucardman);
		$shoucardphone = $db->f(fd_author_shoucardphone);
		$shoucardno=$db->f(fd_author_shoucardno);
		$shoucardbank=$db->f(fd_author_shoucardbank);
		$authortypeid = $db->f(fd_author_authortypeid);
		if ($isstop) {

			$checked1 = "checked";
		} else {

			$checked2 = "checked";
		}
		switch ($state) {
			case "0" :
				$state = "待审核";
				break;
			case "9" :
				$state = "审核通过";
				break;
			case "-1" :
				$state = "注销";
				break;
			default :
				break;

		}
	}

}
$db = new DB_test;
$t->set_block("author_sp", "zzlist", "zzlists");
$query = "select * from tb_upload_scategoty where fd_scat_fcatid=1 ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {

		$zzname = $db->f(fd_scat_name);
		$zzid = $db->f(fd_scat_id);
		$fd = $db->f(fd_scat_foldername);
		
		$fdid = $fd . "id";
		$arr_pic = "";
		$arr_pic = explode("@@", $getFileimg->AutogetFileimg($zzid, $listid));
		$zzurl = $arr_pic[0];
		//echo $zzurl;
		$fdidval = "";

		if (!empty ($zzurl)) {
			$zzimg = "<img  height=60 width=60 src='" . $zzurl . "'  style='border-bottom: 1px solid #000000;border-top: 1px solid #000000;border-right: 1px solid #000000;border-left: 1px solid #000000;'/>";
		} else {
			$zzimg = "<span style=\"color:#3aa5d9\">图片<br>还未上传</span>";
		}

		if ($s == 0) {
			$tr1 = "<tr>";
			$tr2 = "";
			$s++;
		} else
			if ($s == 2) {
				$s = 0;
				$tr1 = "";
				$tr2 = "</tr>";

			} else {
				$tr1 = "";
				$tr2 = "";
				$s++;
			}
		//echo $zzimg."</br>";

		if ($zzurl)
			$fdidval = $zzid;
		$u_btn = show_btn($zzid, $listid, $fd, $zzurl);
		$t->set_var(array (
			"zzname" => $zzname,
			"fdid" => $fdid,
			"fdidval" => $fdidval,
			"fd" => $fd,
			"zzurl" => $zzurl,
			"u_btn" => $u_btn,
			"tr1" => $tr1,
			"tr2" => $tr2,
			"zzimg" => $zzimg,
			"listid" => $listid,

			
		));
		$t->parse("zzlists", "zzlist", true);
		//echo $zzname; 
	}

} else {
	$t->parse("zzlists", "", true);
}


$arr_auindustryid[] = "";
$arr_auindustry[] = "--选择商户行业--";
$query = "select * from tb_authorindustry ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_auindustryid[] = $db->f(fd_auindustry_id);
		$arr_auindustry[] = $db->f(fd_auindustry_name);
	}
}
$auindustryid = makeselect($arr_auindustry, $audtid, $arr_auindustryid);


$arr_authortypeid[] = "";
$arr_authortype[] = "--选择商户分类--";
$query = "select * from tb_authortype ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_authortypeid[] = $db->f(fd_authortype_id);
		$arr_authortype[] = $db->f(fd_authortype_name);
	}
}
$authortypeid = makeselect($arr_authortype, $authortypeid, $arr_authortypeid);

$arr_slotpayfsetid[] = "";
$arr_payfset[] = "--选择商户信用卡费率套餐--";
$query = "select * from tb_payfeeset where fd_payfset_scope='creditcard' and fd_payfset_auindustryid=".$audtid;
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		
		$arr_slotpayfsetid[] = $db->f(fd_payfset_id);
		$arr_payfset[] = $db->f(fd_payfset_name);
	}
}
$slotpayfsetid = makeselect($arr_payfset, $slotpayfsetid, $arr_slotpayfsetid);

$arr_slotscdmsetid[] = "";
$arr_scdmset[] = "--选择商户信用卡额度套餐--";
$query = "select * from tb_slotcardmoneyset where fd_scdmset_scope='creditcard' and fd_scdmset_auindustryid =".$audtid;
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_slotscdmsetid[] = $db->f(fd_scdmset_id);
		$arr_scdmset[] = $db->f(fd_scdmset_name);
	}
}

$slotscdmsetid = makeselect($arr_scdmset, $slotscdmsetid, $arr_slotscdmsetid);

$arr_bkcardscdmsetid[] = "";
$arr_bkcardscdmset[] = "--选择商户借记卡额度套餐--";
$query = "select * from tb_slotcardmoneyset where fd_scdmset_scope='bankcard' and fd_scdmset_auindustryid =".$audtid;
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_bkcardscdmsetid[] = $db->f(fd_scdmset_id);
		$arr_bkcardscdmset[] = $db->f(fd_scdmset_name);
	}
}

$bkcardscdmsetid = makeselect($arr_bkcardscdmset, $bkcardscdmsetid, $arr_bkcardscdmsetid);


$arr_bkcardpayfsetid[] = "";
$arr_bkcardpayfset[] = "--选择商户借记卡费率套餐--";
$query = "select * from tb_payfeeset where fd_payfset_scope='bankcard' and fd_payfset_auindustryid=".$audtid;
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		
		$arr_bkcardpayfsetid[] = $db->f(fd_payfset_id);
		$arr_bkcardpayfset[] = $db->f(fd_payfset_name);
	}
}
$bkcardpayfsetid = makeselect($arr_bkcardpayfset, $bkcardpayfsetid, $arr_bkcardpayfsetid);

$arr_sdcrid[] = "";
$arr_sdcrname[] = "--选择所属刷卡公司--";
$query = "select * from tb_sendcenter where fd_sdcr_active = '1' ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_sdcrid[] = $db->f(fd_sdcr_id);
		$arr_sdcrname[] = $db->f(fd_sdcr_name);
	}
}
$sdcrid = makeselect($arr_sdcrname, $sdcrid, $arr_sdcrid);

$t->set_var("sdcrid", $sdcrid);
$t->set_var("slotscdmsetid", $slotscdmsetid);
$t->set_var("slotpayfsetid", $slotpayfsetid);
$t->set_var("auindustryid", $auindustryid);
$t->set_var("bkcardscdmsetid", $bkcardscdmsetid);
$t->set_var("bkcardpayfsetid", $bkcardpayfsetid);

$oFCKeditor = new FCKeditor('FCKeditor1');
$oFCKeditor->BasePath = '../FCKeditor/';
$oFCKeditor->ToolbarSet = 'Normal';
$oFCKeditor->Wlistidth = '568';
$oFCKeditor->Height = '440';
$oFCKeditor->Value = $content;
$fckeditor = $oFCKeditor->CreateHtml();
$t->set_var("sdcrid", $sdcrid);
$t->set_var("listid", $listid);
$t->set_var("shopname", $shopname);
$t->set_var("shopid", $shopid);
$t->set_var("checked1", $checked1);
$t->set_var("checked2", $checked2);
$t->set_var("id", $id); //listid
$t->set_var("truename", $truename); //listid
$t->set_var("name", $name);
$t->set_var("mobile", $mobile);
$t->set_var("zcdate", $zcdate);
$t->set_var("idcard", $idcard);
$t->set_var("email", $email);
$t->set_var("state", $state);
$t->set_var("isstop", $isstop);
$t->set_var("shopid", $shopid);
$t->set_var("userpw", "");
$t->set_var("select0", $select0);
$t->set_var("select1", $select1);
$t->set_var("select2", $select2);
$t->set_var("select3", $select3);

$t->set_var("shoucardman", $shoucardman);
$t->set_var("shoucardphone", $shoucardphone);
$t->set_var("shoucardno", $shoucardno);
$t->set_var("shoucardbank", $shoucardbank);
$t->set_var("authortypeid", $authortypeid);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址              
$t->set_var("error", $error);
$t->set_var("fckeditor", $fckeditor);
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author_sp"); # 最后输出页面
function show_btn($scatid, $dateid, $getvalid, $getval) {
	if (!empty ($getval)) {
		$btn = "<input class=\"jrljsqscipt\" type=\"button\" name=\"" . $getvalid . "colorbox\" id=\"" . $getvalid . "colorbox\" value=\"重传\" onclick=\"uploadimg(" . $scatid . ",'" . $dateid . "','','" . $getvalid . "','new','refeedback','pre" . $getvalid . "id')\"   />";
		$btn .= "&nbsp;&nbsp;<input class=\"jrljsqscipt\" name=\"\" type=\"button\" id='showimg' value=\"查看\" onclick=\"showbanklogo('" . $getvalid . "');return false;\" />";
	} else {
		$btn = "<input class=\"jrljsqscipt\" type=\"button\" name=\"" . $getvalid . "colorbox\" id=\"" . $getvalid . "colorbox\" value=\"上传\" onclick=\"uploadimg(" . $scatid . ",'" . $dateid . "','','" . $getvalid . "','new','refeedback','pre" . $getvalid . "id')\"   />";
	}

	return $btn;
}
?>

