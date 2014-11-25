<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8');
require ("../include/common.inc.php");
require_once ('../include/json.php');
$db = new db_test;
$count = 0;

$aColumns = array (
	"fd_saler_truename",
	"fd_paycard_datetime",
	"fd_paycard_no",
	"fd_bank_name",
	"fd_author_truename"
);

$sSearch = u2g($sSearch);
$sWhere = "";
if ($sSearch != "") {
	$sWhere = "and  (";
	for ($i = 1; $i < count($aColumns); $i++) {
		$sWhere .= $aColumns[$i] . " LIKE '%" . trim($sSearch) . "%' OR ";
	}
	$sWhere = substr_replace($sWhere, "", -3);
	$sWhere .= ')';
}

/* Individual column filtering */
for ($i = 1; $i < count($aColumns); $i++) {
	$b_s = "bSearchable_" . $i;
	$s_s = "sSearch_" . $i;
	if ($$b_s == "true" && $$s_s != '') {
		if ($sWhere == "") {
			$sWhere = "AND ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .= $aColumns[$i] . " LIKE '%" . trim($$s_s) . "%' ";
	}
}

$query = "select 1 from tb_paycard 
left join tb_saler on fd_saler_id = fd_paycard_salerid 
left join tb_bank on  fd_bank_id = fd_paycard_bankid 
left join tb_author on fd_paycard_authorid=fd_author_id
where 1 $sWhere  ";
$db->query($query);
$totoalcount = $db->nf() + 0;

$query = "select * from tb_paycard 
left join tb_saler on fd_saler_id = fd_paycard_salerid
left join tb_bank on  fd_bank_id = fd_paycard_bankid
left join tb_author on fd_paycard_authorid=fd_author_id  
where  1  $sWhere limit $iDisplayStart,$iDisplayLength  ";
$db->query($query);
//echo $query;
if ($db->nf()) {
	while ($db->next_record()) {
		$vid = $db->f(fd_paycard_id); //id号  
		$vno = $db->f(fd_paycard_no); //id号  
		$vtruename = g2u($db->f(fd_saler_truename));
		$vbankname = g2u($db->f(fd_bank_name));
		$vstatus = $db->f(fd_paycard_active);
		$vdatetime = $db->f(fd_paycard_datetime);
		$visnewcard = $db->f(fd_paycard_isnew);
		$vpaycardtype = $db->f(fd_paycard_scope);
		$authortruename = g2u($db->f(fd_author_truename));
		$authormobile = $db->f(fd_author_mobile);
		if ($vstatus == 1) {
			$vstatus = "激活";
		} else {
			$vstatus = "未激活";
		}
		if ($vpaycardtype == "creditcard") {
			$vpaycardtype = "信用卡";
		}
		elseif ($vpaycardtype == "bankcard") {
			$vpaycardtype = "储蓄卡";
		}

		$visnewcard = $visnewcard ? "是" : "否";
		$vstatus = g2u($vstatus);
		$checkall = '<INPUT  type=checkbox class=checkbox value=' . $vid . ' rel=arr_paycard name=arr_list[]>';

		$vedit = '<a href="paycardmsg.php?listid=' . $vid . '" class="edit" >查看</a>';
		$count++;
		$arr_list[] = array (
			"DT_RowId" => $vid,
			"DT_RowClass" => "",
			$checkall,
			$count,
			$vno,
			g2u($vpaycardtype),
			$authortruename,
			$authormobile,
			$vtruename,
			$vbankname,
			$vdatetime,
			$vstatus,
			g2u($visnewcard),
			""
		);
	}
} else {
	$vmember = "暂无数据";
	$arr_list[] = array (
		"DT_RowId" => $vid,
		"DT_RowClass" => "",
		$checkall,
		$count,
		$vid,
		$vpaycardtype,
		$authormobile,
		$vtruename,
		$vtruename,
		$vbankname,
		$vdatetime,
		$vstatus,
		$visnewcard,
		""
	);
}

$returnarray['sEcho'] = intval($sEcho);
$returnarray['iTotalRecords'] = $totoalcount;
$returnarray['iTotalDisplayRecords'] = $totoalcount;
$returnarray['aaData'] = $arr_list;
$returnvalue = json_encode($returnarray);
echo json_encode($returnarray);
?>