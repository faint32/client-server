<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
$db = new DB_test();
$gourl = "tb_slotcardmoneyset_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
		$query = "select 1 from tb_slotcardmoneyset where  fd_scdmset_auindustryid='$auindustryid' and fd_scdmset_mode = '$mode' and fd_scdmset_scope = '$scope' ";
		$db->query($query);
		if ($db->nf()) {
			$error = "�Ѿ�����˶�ȣ�����Ҫ�ظ����";
		} else {
			$query = "INSERT INTO tb_slotcardmoneyset(
						   fd_scdmset_mode,fd_scdmset_no,fd_scdmset_auindustryid,fd_scdmset_nallmoney, fd_scdmset_sallmoney,fd_scdmset_scope,fd_scdmset_everymoney,fd_scdmset_everycounts,fd_scdmset_neverymoney,fd_scdmset_severymoney," .
						   		"fd_scdmset_name,fd_scdmset_datetime,fd_scdmset_bkcount,fd_scdmset_changebkcount) VALUES(
						   '$mode','$no','$auindustryid','$nallmoney','$sallmoney','$scope','$everymoney','$everycounts'," .
						   		"'$neverymoney','$severymoney'," .
						   		"'$name',now(),'$bkcount','$changebkcount')";
			$db->query($query);
			//echo $query;
			$listid = $db->insert_id();
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "edit" :
		$query = "select 1 from tb_slotcardmoneyset where  fd_scdmset_auindustryid='$auindustryid' and fd_scdmset_mode = '$mode' and fd_scdmset_scope = '$scope' and fd_scdmset_id <> '$listid'";
		$db->query($query);
		if ($db->nf()) {
			$error = "�Ѿ��ظ����������޸ģ�";
		} else {
			$query = "update tb_slotcardmoneyset set fd_scdmset_mode='$mode',fd_scdmset_no='$no',
									  fd_scdmset_auindustryid='$auindustryid',fd_scdmset_sallmoney='$sallmoney',
									  fd_scdmset_nallmoney='$nallmoney',fd_scdmset_scope = '$scope',
									   fd_scdmset_everymoney = '$everymoney',fd_scdmset_name = '$name',
									   fd_scdmset_severymoney = '$severymoney' , fd_scdmset_neverymoney = '$neverymoney',
									   fd_scdmset_everycounts ='$everycounts', fd_scdmset_bkcount ='$bkcount',
									    fd_scdmset_changebkcount ='$changebkcount',
									  fd_scdmset_datetime = now()  where fd_scdmset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		$action = "";
		break;
	case "delete":
		$query="select * from tb_author where fd_author_bkcardscdmsetid='$listid' or fd_author_slotscdmsetid='$listid'";
		$db->query($query);
		if($db->nf())
		{
			$error='���ײ��Ѱ��̻�,����ȡ����!';
			}else{
			$query = "delete from tb_slotcardmoneyset where fd_scdmset_id='$listid'";
			$db->query($query);
			require ("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
		break;
	default :
		break;
}

$t = new Template(".", "keep"); //����һ��ģ��
$t->set_file("author", "slotcardmoneyset.html");

if (!isset ($listid)) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_slotcardmoneyset where fd_scdmset_id='$listid' ";
	$db->query($query);
	if ($db->nf()) {
		$db->next_record();
		$listid = $db->f(fd_scdmset_id);
		$mode = $db->f(fd_scdmset_mode);
		$no = $db->f(fd_scdmset_no);
		$auindustryid = $db->f(fd_scdmset_auindustryid);
		$sallmoney = $db->f(fd_scdmset_sallmoney);   // ÿ�������ܶ�
		$nallmoney = $db->f(fd_scdmset_nallmoney);   // ÿ�������ܶ�
		$everymoney = $db->f(fd_scdmset_everymoney); // ÿ�������ܶ�
		$everycounts = $db->f(fd_scdmset_everycounts);// ÿ������ˢ������
		$scope = $db->f(fd_scdmset_scope);            // ˢ������ ���ÿ����߽�ǿ�   
		$severymoney = $db->f(fd_scdmset_severymoney); // ÿ�������ܶ�
		$neverymoney = $db->f(fd_scdmset_neverymoney); // ÿ�������ܶ�
		$name    = $db->f(fd_scdmset_name);
		$bkcount    = $db->f(fd_scdmset_bkcount);
		$changebkcount    = $db->f(fd_scdmset_changebkcount);
		
		
		
	}

}
//
$arr_modeno = array (
	"date",
	"month"
);
$arr_mode = array (
	"���ײ�",
	"���ײ�"
);
$mode = makeselect($arr_mode, $mode, $arr_modeno);

$arr_scopeno = array (
	"creditcard",
	"bankcard"
);
$arr_scopename = array (
	"���ÿ�",
	"���"
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

$t->set_var("listid", $listid); //listid
$t->set_var("mode", $mode);
$t->set_var("no", $no);
$t->set_var("name", $name);
$t->set_var("auindustryid", $auindustryid);
$t->set_var("sallmoney", $sallmoney);
$t->set_var("nallmoney", $nallmoney);
$t->set_var("everymoney", $everymoney);
$t->set_var("everycounts", $everycounts);
$t->set_var("severymoney", $severymoney);
$t->set_var("neverymoney", $neverymoney);
$t->set_var("bkcount", $bkcount);
$t->set_var("changebkcount", $changebkcount);
$t->set_var("scope", $scope);
$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ    
$t->set_var("fckeditor", $fckeditor);
$t->set_var("error", $error);
// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "author"); # ������ҳ��
?>

