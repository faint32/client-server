<?
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
	
		for($i = 0; $i < count($checkid); $i++){
			if(!empty ($checkid[$i])){
				$query="select * from tb_paymoneylistdetail where fd_pymyltdetail_agpmid='$checkid[$i]'";
				$db->query($query);
				if($db->nf())
				{
					$query="select  fd_agpm_no as agpm_no from tb_agentpaymoneylist where fd_agpm_id = '$checkid[$i]' ";
					$arr_data=$db->get_one($query);
				
					if($agpm_no){$agpm_no .=",".$arr_data['agpm_no'];}else{$agpm_no =$arr_data['agpm_no'];}
				}
			
			}
		}
			
			if($agpm_no)
			{
				$error="���ݱ��:$agpm_no ���ڴ���������,�޷��˻�";
			}else{
				foreach($checkid as $value){
				$query = "update tb_agentpaymoneylist set fd_agpm_state = 0 ,fd_agpm_spman = '$loginstaname',fd_agpm_spdate=now()
                  where fd_agpm_id = '$value'";
				 //$db->query($query);  
				 }
			}
		
		   
		$action = "";
		break;

	default :
		break;
}


if (!empty ($sel_condit)) {
	$querywhere .= " and " . $sel_condit . " like '%" . $txtCondit . "%'";
}

$t = new Template(".", "keep"); //����һ��ģ��
$t->set_file("paymoneylist_lsview", "paymoneylist_lsview.html");

//��ʾ�б�
$t->set_block("paymoneylist_lsview", "prolist", "prolists");
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
                 fd_agpm_no               as agpm_no,
                 fd_agpm_bkntno           as bkntno,
                 fd_paycard_key           as paycardkey,
				 fd_agpm_bkordernumber    as bkordernumber,
                 fd_author_truename       as author,
                 fd_agpm_paydate          as paydate,
                 fd_agpm_fucardno       as fucardno,
                 fd_agpm_paymoney         as paymoney ,
                 fd_agpm_payfee           as payfee,
                 fd_agpm_money            as money,
                 fd_agpm_arrivemode       as arrivemode,
                 fd_agpm_arrivedate       as arrivedate,
                 fd_agpm_memo             as memo
          from tb_agentpaymoneylist   
          left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
          where fd_agpm_payrq = '00' and fd_agpm_state = 1 and  fd_appmnu_istabno = 1   $querywhere
          order by fd_agpm_bkordernumber desc";
$db->query($query);
//AND FD_AGPM_PAYTYPE <>'RECHARGE'
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
	          
	     $t->set_var($value);
	     $t->parse("prolists", "prolist", true);
}

if($count == 0){
  $t->parse("prolists", "", true);
}

$querywhere = urlencode($querywhere);
$t->set_var("pagenav", $pagenav); //��ҳ����
$t->set_var("brows_rows", $brows_rows);

$arr_temp = array (
	"-��ѡ��-",
	"���ݱ��",
	"����������",
	"���н�����ˮ��",
	"�ͻ�����"
);
$arr_temp2 = array (
	"",
	"fd_agpm_no",
	"fd_agpm_bkordernumber",
	"fd_agpm_bkntno",
	"fd_author_truename"
);
$condition = makeselect($arr_temp, $sel_condit, $arr_temp2);


$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var("pagenav", $pagenav); //��ҳ����

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

$t->pparse("out", "paymoneylist_lsview"); # ������ҳ��
?>

