<?php
$thismenucode = "sys";
require ("../include/common.inc.php");
include ("../include/pageft.php");

$db = new DB_test();
$db1 = new DB_test();

if($brows_rows != "") {
	 $loginreportline = $brows_rows;
}

switch ($action) {
	case "selmore" : //�������� 
        $query = "update tb_agentpaymoneylist set fd_agpm_state = 1 ,fd_agpm_spman = '$loginstaname',fd_agpm_spdate=now() where fd_agpm_id = '$selid'";
		$db->query($query);
		$action = "";
		break;

	default :
		break;
}


if (!empty ($sel_condit)) {
	$querywhere .= " and " . $sel_condit . " like '%" . $txtCondit . "%'";
}
if (!empty ($selectpaytype)) {
	$querywhere .= " and fd_agpm_paytype ='$selectpaytype'";
}

$t = new Template(".", "keep"); //����һ��ģ��
$t->set_file("paymoneylist_dzview", "paymoneylist_dzview.html");

//��ʾ�б�
$t->set_block("paymoneylist_dzview", "prolist", "prolists");
$query = "select 
				case 
				when fd_agpm_payrq ='01' then '������'
				when fd_agpm_payrq ='00' then '�������'
				else 'ȡ������' END  payrq,
				case 
				when fd_agpm_payfeedirct ='s' then ' �տ'
				when fd_agpm_payfeedirct ='f' then '���'
				END  payfeedirct,
					fd_appmnu_name  as	 paytype,
				 fd_agpm_id               as agpm_id,
                 fd_agpm_bkordernumber    as bkordernumber,
                 fd_agpm_bkntno           as bkntno,
                 fd_paycard_key           as paycardkey,
                 fd_author_truename       as author,
                 fd_agpm_paydate          as paydate,
                 fd_agpm_fucardno         as fucardno,
                 fd_agpm_current          as current,
                 
                 fd_agpm_paymoney         as paymoney ,
                 fd_agpm_payfee           as payfee,
                 case 
                 when fd_agpm_payfeedirct ='s' then (fd_agpm_paymoney - fd_agpm_payfee)
                 when fd_agpm_payfeedirct ='f' then fd_agpm_paymoney
                 else fd_agpm_paymoney END money,
                 fd_agpm_arrivemode       as arrivemode,
                 fd_agpm_arrivedate       as arrivedate,
                 fd_sdcr_name             as sdcrname,
                 fd_agpm_memo             as memo
          from tb_agentpaymoneylist   
          left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid
          left join tb_sendcenter  on fd_sdcr_id  = fd_agpm_sdcrid
		   left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
          where fd_agpm_payrq = '00' and fd_agpm_state = 0 and  fd_appmnu_istabno = 1  $querywhere
          order by fd_agpm_paydate desc";
$db->query($query);
//and fd_agpm_paytype <>'recharge'
$total = $db->num_rows($result);
pageft($total, 20, $url);
if ($firstcount < 0) {
	$firstcount = 0;
}

$count =$firstcount;

$query = "$query limit $firstcount,$displaypg";	  
$db->query($query);
$rows = $db->num_rows();
$arr_result = $db->getFiledData('');
$count = 0;
foreach ($arr_result as $key => $value) {
	     $count++;
	     $value['count']= $count;	
	     $value['arrivemode'] = "T+".$value['arrivemode'];
	     if($value['payfeedirct']=="���"){$value['paymoney']=$value['paymoney']+$value['payfee'];}
		 $all_paymoney += $value['paymoney'];
	     $all_payfee += $value['payfee'];
	     $all_money += $value['money'];
	     $value['action']='<a href="#" onclick="submit_sel(\''.$value['agpm_id'].'\',\''.$value['bkordernumber'].'\')">��������</a>';     
	     $t->set_var($value);
	     $t->parse("prolists", "prolist", true);
}

if($count == 0){

  $t->parse("prolists", "", true);
}

$querywhere = urlencode($querywhere);
$t->set_var("pagenav", $pagenav);          //��ҳ����
$t->set_var("brows_rows", $brows_rows);

$arr_temp = array (
	"-��ѡ��-",
	"����������",
	"���н�����ˮ��",
	"�ͻ�����",
	"��ʢ����"
);
$arr_temp2 = array (
	"",
	"fd_agpm_bkordernumber",
	"fd_agpm_bkntno",
	"fd_author_truename",
	"fd_sdcr_name"
);
$condition = makeselect($arr_temp, $sel_condit, $arr_temp2);

$arr_paytypeid=array("","coupon","creditcard","recharge","repay","order","tfmg","suptfmg");
$arr_paytypename=array("��������","�������ȯ","���ÿ�����","��ֵ","������","��������","ת�˻��","����ת��");
$selectpaytype=makeselect($arr_paytypename,$paytype,$arr_paytypeid);

$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var("pagenav", $pagenav); //��ҳ����
$t->set_var("selectpaytype", $selectpaytype);
$t->set_var("conditions", $condition);
$t->set_var("txtCondit", $txtCondit);

$t->set_var("listid", $listid); //����id 
$t->set_var("payorin", $payorin); //������տ�

$t->set_var("count", $count);
$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   );

$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // ת�õĵ�ַ
$t->set_var("error", $error);

$t->set_var("checkid", $checkid); //����ɾ����ƷID   

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "paymoneylist_dzview"); # ������ҳ��
?>