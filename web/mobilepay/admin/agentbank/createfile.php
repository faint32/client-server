<?php
$thismenucode = "";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php"); //�������ɵ��ݱ���ļ�    
require ("../function/changemoney.php"); //����Ӧ��Ӧ������ļ�
require ("../function/chanceaccount.php"); //�����޸��ʻ�����ļ�
require ("../function/cashglide.php"); //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php"); //�����������ʵ��ļ�


$db  = new DB_test();
$db1 = new DB_test();

$gourl = "../finance/tb_agentpaymoney_out_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

if(!empty($action) or !empty ($end_action) or !empty ($listid)) {
    $query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' and fd_pymylt_state != 2 ";
    $db->query($query);
    if($db->nf()){
        echo "<script>alert('�õ����Ѿ����ڱ����裬�������޸ģ����֤')</script>";      
        $action = "";
        $end_action = "";
    }
}


//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if($end_action == "endsave") {
    $todaydate = date ( "Y-m-d" );
    if ($todaydate < $date) {
        $error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
        $end_action = "";
    }
}

//�ж����ش�������0
if($end_action == "endsave") {
    $query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid' and fd_pymylt_times = 0 ";
    $db->query($query);
    if($db->nf()){
        echo "<script>alert('�뵼��TXT�ļ�')</script>";
        $action = "";
        $end_action = "";
    }
}

switch ($end_action) {
    case "endsave" :
            $query = "update tb_paymoneylist set fd_pymylt_state = 3, fd_pymylt_datetime = now()
                where fd_pymylt_id = '$listid'";
            $db->query ( $query ); //�޸ĸ��
            require ("../include/alledit.2.php");
            Header ( "Location: $gotourl" );
        break;
    case "dellist" : //ɾ��������������
        $query = "update tb_paymoneylist set fd_pymylt_state = 0, fd_pymylt_memo = '$thmemo',fd_pymylt_dealwithman = '$loginstaname'
              where fd_pymylt_id = '$listid'";
        $db->query ( $query );
        require ("../include/alledit.2.php");
        Header ( "Location: $gotourl" );
        break;
    case "agentpay" : //ȷ�ϸ���
        switch ($isagentpay)
        {
            case 'coupon':
                $table = 'couponsale';
                $where = 'couponsale';
                break;
            case 'creditcard':
                $table = 'creditcardglist';
                $where = 'ccglist';
                break;
            case 'recharge':
                $table = 'rechargeglist';
                $where = 'rechargelist';
                break;
            case 'repay':
                $table = 'repaymoneyglist';
                $where = 'repmglist';
                break;
            case 'tfmg':
                $table = 'transfermoneyglist';
                $where = 'tfmglist';
                break;
             case 'suptfmg':
                $table = 'transfermoneyglist';
                $where = 'tfmglist';
                break;
            default:
                break;
        }
        $query = "UPDATE tb_agentpaymoneylist SET fd_agpm_isagentpay = '2' WHERE fd_agpm_bkordernumber = '".$bkordernumber."'";
        $db->query ( $query );
        break;
    default :
        break;
}

$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "createfile", "createfile.html" );

$count = 0;

$action = "edit";
$query = "select * from tb_paymoneylist  where fd_pymylt_id = '$listid' ";
$db->query ( $query );
if ( $db->nf() )
{
	$db->next_record ();
	$listno = $db->f(fd_pymylt_no);
	$dealwithman = $db->f(fd_pymylt_dealwithman);
	$money = $db->f(fd_pymylt_money);
	$date = $db->f(fd_pymylt_date);
	$fkdate = $db->f(fd_pymylt_fkdate);
	$memo_z = $db->f(fd_pymylt_memo);
    $times = $db->f(fd_pymylt_times);
    $isallagentpay = $db->f(fd_pymylt_isagentpay);
    $paytype = $db->f(fd_pymylt_paytype);
}
 $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
		$arr_couponset = $db->get_one($query);
		$couponfee = substr($arr_couponset['fee'], 0, -1); //���������� 
$t->set_block ( "createfile", "prolist", "prolists" );
$query = "select
                 case 
                 when fd_agpm_isagentpay ='0' then 'ȷ�ϸ���'
                 when fd_agpm_isagentpay ='1' then '�Ѹ���'
                 when fd_agpm_isagentpay ='2' then 'ȡ������'
                 END  isagentpay,
                 case
                 when fd_agpm_payfeedirct ='s' then ' �տ'
                 when fd_agpm_payfeedirct ='f' then '���'
                 END  payfeedirct,
                 fd_agpm_bkordernumber            as bkordernumber,
                 fd_pymyltdetail_id               as vid,
                 fd_agpm_bkntno                   as bkntno,
                 fd_paycard_key                   as paycardkey,
                 fd_author_truename               as author,
                 case
                 when fd_agpm_paytype ='coupon' then '�������ȯ'
                 when fd_agpm_paytype ='creditcard' then '���ÿ�����'" .
                 "when fd_agpm_paytype ='recharge' then        '��ֵ'" .
                 "when fd_agpm_paytype ='repay' then       '������'" .
                 "when fd_agpm_paytype ='order' then '��������'" .
                 "when fd_agpm_paytype ='tfmg' then 'ת�˻��'" .
                 "when fd_agpm_paytype ='suptfmg' then '����ת��'
                 else '����ҵ��' END  paytype,
                 fd_agpm_paytype                  as paytypee,
                 fd_agpm_paydate                  as paydate,
                 fd_agpm_shoucardno               as shoucardno,
                 fd_agpm_shoucardbank             as shoucardbank,
                 fd_agpm_shoucardman              as shoucardman,
                 fd_agpm_shoucardmobile           as shoucardmobile,
                 fd_agpm_current                  as current,
                 fd_agpm_paymoney                 as paymoney ,
                 fd_agpm_payfee                   as payfee,
                 case
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney-fd_agpm_payfee)
                 else fd_agpm_paymoney END money,
                 fd_agpm_arrivemode               as arrivemode,
                 fd_agpm_arrivedate               as arrivedate,
                 fd_paycardaccount_accountname    as accountname,
                 fd_sdcr_name                     as sdcrname,
                 fd_paycardaccount_accountnum     as accountnum
                 from tb_paymoneylistdetail  
                 left join tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id
                 left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
                 left join tb_author  on fd_author_id = fd_agpm_authorid
                 left join tb_paycardaccount  on fd_paycard_paycardaccount = fd_paycardaccount_id
                 left join tb_sendcenter on fd_sdcr_id = fd_agpm_sdcrid
                 where fd_pymyltdetail_paymoneylistid = '$listid'
                 order by fd_agpm_bkntno"; 
$db->query($query);
$arr_result = $db->getFiledData('');

$count = 0; 
$list_disabled = "disabled";

foreach ( $arr_result as $key => $value )
{
    $count++;
    $value['count']= $count;
    $value['arrivemode']= "T+".$value['arrivemode'];
      if($value['paytypee']=='coupon')
		{
			$value['payfee'] = $value['paymoney']  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$value['money'] = round($value['money'] -$value['payfee'],2);
			//echo $value['money'];
		}else
		{
			$value['money']  = round($value['money'],2);
			$value['payfee'] = "0"; 
		}
    $all_paymoney += $value['paymoney'];
    $all_payfee += $value['payfee'];
    $all_money += $value['money'];
    
    $list_disabled = "";
    $t->set_var($value);
    $t->parse("prolists", "prolist", true);
}

if ( empty($arr_result) )
{
  $t->parse("prolists", "", true);
}

if ( empty($listid) )
{ //����Ѿ��ݴ棬�ύ��ɾ����ť���ã����򲻿���
	$tijiao_dis = "disabled";
}
else
{
  $tijiao_dis = "";
}

// $arr = array($all_paymoney, $all_payfee ,$all_money);
// $arr1 = cheackPrice($arr);
$all_paymoney       = number_format($all_paymoney, 2, ".", "");
$all_payfee         = number_format($all_payfee, 2, ".", "");
$all_money          = number_format($all_money, 2, ".", "");
// $all_paymoney       = $arr1[0];
// $all_payfee         = $arr1[1];
// $all_money          = $arr1[2];
$downloadurl        = 'downloadfile.php?listid='.$listid;

// function cheackPrice($arr , $num = 2)
// {
//     $temparr = $arr;
//     $countnum = count($temparr);
//     for ($i = 0; $i < $countnum; $i++)
//     {
//       if (is_numeric($temparr[$i]))
//       {
//         $temparr[$i] = number_format( $temparr[$i] , $num , "." , "" );
//       }
//       else
//       {
//         $temparr[$i] = $temparr[$i];
//       }
//     }
//     return $temparr;
// }

switch ($paytype) {
    case 'coupon':
        $paytype = "�������ȯ";
        break;
    case 'creditcard':
        $paytype = "���ÿ�����";
        break;
    case 'recharge':
        $paytype = "��ֵ";
        break;
    case 'repay':
        $paytype = "������";
        break;
    case 'order':
        $paytype = "��������";
        break;
    case 'tfmg':
        $paytype = "ת�˻��";
        break;
    case 'suptfmg':
        $paytype = "����ת��";
        break;
    default:
        $paytype = "����ҵ��";
        break;
}

$t->set_var ( "tijiao_dis" , $tijiao_dis );
$t->set_var ( "listno" , $listno );
$t->set_var ( "listid" , $listid );
$t->set_var ( "dealwithman" , $dealwithman );
$t->set_var ( "money" , $money );
$t->set_var ( "date" , $date ); 
$t->set_var ( "fkdate" , $fkdate ); 
$t->set_var ( "memo_z" , $memo_z );
$t->set_var ( "paytype" , $paytype );
$t->set_var ( "count" , $count );
$t->set_var ( "times" , $times );
$t->set_var ( "isagentpay" , $isagentpay );
$t->set_var ( "list_disabled" , $list_disabled );

$t->set_var( "all_paymoney" , $all_paymoney );
$t->set_var( "all_payfee" , $all_payfee );
$t->set_var( "all_money" , $all_money );

$t->set_var ( "action" , $action );
$t->set_var ( "gotourl" , $gotourl ); // ת�õĵ�ַ
$t->set_var ( "downloadurl" , $downloadurl ); // ���ص�ַ
$t->set_var ( "error" , $error );

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse ( "out", "createfile" ); # ������ҳ��
?>