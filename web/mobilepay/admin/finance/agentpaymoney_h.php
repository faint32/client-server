<?
$thismenucode = "2n602";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php"); //�������ɵ��ݱ���ļ�    
require ("../function/changemoney.php"); //����Ӧ��Ӧ������ļ�
require ("../function/chanceaccount.php"); //�����޸��ʻ�����ļ�
require ("../function/cashglide.php"); //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php"); //�����������ʵ��ļ�

$db  = new DB_test ( );
$db1 = new DB_test ( );
$gourl = "tb_agentpaymoney_sp_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

if (! empty ( $action ) or ! empty ( $end_action ) or ! empty ( $listid )) {
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' 
	          and (fd_pymylt_state = 2 or fd_pymylt_state = 2 )";
	$db->query ( $query );
	if ($db->nf ()) {
		echo "<script>alert('�õ����Ѿ������ˣ��������޸ģ����֤')</script>";
		
		$action = "";
		$end_action = "";
	}
}


//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if ($end_action == "endsave") {
	$todaydate = date ( "Y-m-d" );
	if ($todaydate < $date) {
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$end_action = "";
	}
}

switch ($end_action) {
	case "endsave" :
	
			$query = "update tb_paymoneylist set fd_pymylt_state = 1, fd_pymylt_dealwithman= '$dealwithman' ,fd_pymylt_datetime = now()
                       where fd_pymylt_id = '$listid'";
			$db->query ( $query ); //�޸ĸ��
			require ("../include/alledit.2.php");
			Header ( "Location: $gotourl" );
		
		break;
	
	case "back" : //ɾ��������������
		//--------------------start----------------------
		$query = "update tb_paymoneylist set fd_pymylt_state = 0, fd_pymylt_datetime = now()
                       where fd_pymylt_id = '$listid'";
		$db->query ( $query ); //�޸ĸ��
		
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		break;
	
	default :
		break;
}

$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "paymoneylist", "agentpaymoney_sp.html" );

$count = 0;
if (empty ( $listid )) {
	$date = date ( "Y-m-d" );
	$action = "new";

} else { //������۵�id�ò���Ϊ��
	$action = "edit";
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' ";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$listno = $db->f ( fd_pymylt_no );
		$accountid = $db->f ( fd_pymylt_accountid );
		$cusid = $db->f ( fd_pymylt_clientid );
		$cusno = $db->f ( fd_pymylt_clientno );
		$cusname = $db->f ( fd_pymylt_clientname );
		$clienttype = $db->f ( fd_pymylt_type );
		$date = $db->f ( fd_pymylt_date );
		$payallmoney = $db->f ( fd_pymylt_money );
		$dealwithman = $db->f ( fd_pymylt_dealwithman );
		$memo_z = $db->f ( fd_pymylt_memo );
		$sklinkman = $db->f ( fd_pymylt_sklinkman );
		$insidecomid = $db->f ( fd_pymylt_insidecompanyid );
		$receiptno = $db->f ( fd_pymylt_receiptno );
		$payapplydetailid = $db->f ( fd_pymylt_payapplydetailid );
		$payapplyno = $db->f ( fd_pymylt_payapplyno );
		$paytype = $db->f ( fd_pymylt_paytype );
		$sqorganid = $db->f ( fd_pymylt_sqorganid );
		$staid = $db->f ( fd_pymylt_staid );
	}
}
$t->set_block ( "paymoneylist", "prolist", "prolists" );
$query = "select fd_agpm_bkntno as bkntno,fd_pymyltdetail_id as agpmid,	fd_agpm_shoucardno as shoucardno,
                 fd_agpm_shoucardbank as shoucardbank,fd_agpm_shoucardman as shoucardman,
                 fd_agpm_shoucardmobile as shoucardmobile,
                 fd_agpm_paymoney as paymoney,fd_agpm_paydate as paydate 
                 from tb_paymoneylistdetail  left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id  order by fd_agpm_no"; 
$db->query($query);
$arr_result = $db->getFiledData('');
//echo var_dump($arr_result);
foreach($arr_result as $val)
{	
$t->set_var($val);
$t->parse("prolists", "prolist", true);	
}
if(empty($arr_result))
{
$t->parse("prolists", "", true);	
}
if (empty ( $listno )) { //��ʾ��ʱ�ĵ��ݱ��
	$listno = listnumber_view ( "9" );
}



if (empty ( $listid )) { //����Ѿ��ݴ棬�ύ��ɾ����ť���ã����򲻿���
	$tijiao_dis = "disabled";
} else {
	$tijiao_dis = "";
}
$t->set_var ( "tijiao_dis", $tijiao_dis );

//echo $sqorganid;
//require ("ypaylist.php");  --��ʱ���ε�
$t->set_var ( "tijiao_dis", $tijiao_dis ); //����id  
$t->set_var ( "sqorganid", $sqorganid ); //����id 
$t->set_var ( "listid", $listid ); //����id 
$t->set_var ( "listno", $listno ); //���ݱ�� 
$t->set_var ( "cusno", $cusno ); //�ͻ����
$t->set_var ( "cusname", $cusname ); //�ͻ�����
$t->set_var ( "cusid", $cusid ); //�ͻ�id��
$t->set_var ( "cusno", $cusno ); //�ͻ����
$t->set_var ( "clienttype", $clienttype ); //�ͻ�����
$t->set_var ( "staid", $staid ); //¼����id
$t->set_var ( "wpaymoney", $wpaymoney ); //¼����id
$t->set_var ( "fppaymoney", $fppaymoney ); //����id 
//$staname= getstaname($staid);
$t->set_var ( "staname", $staname ); //������
$t->set_var ( "memo_z", $memo_z ); //��ע
$t->set_var ( "insidecompanyid", $insidecompanyid ); //�ڲ��ֹ�˾
$t->set_var ( "accountid", $accountid ); //�ʻ�
$t->set_var ( "payallmoney", $payallmoney ); //������
$t->set_var ( "dealwithman", $dealwithman ); //ҵ��Ա
$t->set_var ( "yfk_show", $yfk_show ); //Ӧ����
$t->set_var ( "sklinkman", $sklinkman ); //ҵ��Ա
$t->set_var ( "receiptno", $receiptno ); //�վݺ���
$t->set_var ( "payapplydetailid", $payapplydetailid ); //����������ϸid
$t->set_var ( "payapplyno", $payapplyno ); //����������
$t->set_var ( "paytype", $paytype ); //��������
$t->set_var ( "count1", $count1 ); //������


$t->set_var ( "date", $date ); //������ 


$t->set_var ( "count1", $count1 ); //�ӱ��¼��


$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ
$t->set_var ( "error", $error );

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "paymoneylist" ); # ������ҳ��


//����ѡ��˵��ĺ���(���д���)
function makeallselect($arritem, $hadselected, $arry) {
	$x .= "<select name='accountid' onChange='changeaccount()'>";
	for($i = 0; $i < count ( $arritem ); $i ++) {
		if ($hadselected == $arry [$i]) {
			$x .= "<option selected value='$arry[$i]'>" . $arritem [$i] . "</option>";
		} else {
			$x .= "<option value='$arry[$i]'>" . $arritem [$i] . "</option>";
		}
	}
	$x .= "</select>";
	return $x;
}

?>

