<?php
	require ("../include/common.inc.php");
	require("collection.class.php");
	$arr_result = $arr = array();
	$db = new DB_test();

	$arr = array(
	 	array(
	 		'collectionNumber' => 'F' , //���պ�
	 		'ersionNumber' => '03' , //�汾��
	 		'merchantId' => '000191400201746' , //�̻�ID//�����������
	 		'businessType' => '09900' , //ҵ������
	 	),
	 	array(),
	 );
     $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
	$arr_couponset = $db->get_one($query);
	$couponfee = substr($arr_couponset['fee'], 0, -1); //���������� 
	
	$query = "select 
		fd_pymyltdetail_id				as vid,
		fd_author_truename				as author,
		fd_agpm_shoucardno				as shoucardno,
		fd_agpm_shoucardbank			as shoucardbank,
		fd_agpm_shoucardman			as shoucardman,
		fd_agpm_shoucardmobile			as shoucardmobile,
		fd_agpm_current				as current,
		fd_agpm_paymoney				as paymoney ,
		fd_agpm_payfee					as payfee,
		fd_agpm_money					as money,
		fd_paycardaccount_accountname	as accountname,
		fd_paycardaccount_accountnum	as accountnum,
		fd_bank_no						as bankno," .
	   "fd_agpm_paytype				    as paytype
		from tb_paymoneylistdetail
		left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id
		left join  tb_bank on fd_bank_name = fd_agpm_shoucardbank
		left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
		left join tb_author  on fd_author_id  = fd_agpm_authorid 
		left join  tb_paycardaccount  on fd_paycard_paycardaccount = fd_paycardaccount_id
		where fd_pymyltdetail_paymoneylistid = '$listid'
		order by fd_agpm_bkntno";
	$db->query($query);
	$arr_result = $db->getFiledData('');

	foreach ($arr_result as $key => $value)
	{
		
		
		
		if($value['paytype']=='coupon')
		{
			//$paymoney = round($value['paymoney'] * 100 * (1-$fee*0.01),0);
			
			$value['payfee'] = $value['paymoney']  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$paymoney = $value['paymoney'] -$value['payfee'];
			$paymoney = round($paymoney * 100,0);
		}else
		{
			$paymoney = round($value['paymoney'] * 100,0);
		}
		
		
		
		$arr[1][$key]['e_user_code'] = '';//���������û����
		$arr[1][$key]['bank_code'] = $value['bankno'];//���д���
		$arr[1][$key]['account_type'] = '00';//�ʺ�����
		$arr[1][$key]['account_no'] = $value['shoucardno'];//�˺�
		$arr[1][$key]['account_name'] = $value['shoucardman']; //�˻���
		$arr[1][$key]['province'] = '';//����������ʡ
		$arr[1][$key]['city'] = '';//������������
		$arr[1][$key]['account_prop'] = '0';//����������
		$arr[1][$key]['amount'] = $paymoney;//���
		// $arr[1][$key]['currency'] = $value['current'];//��������
		$arr[1][$key]['currency'] = 'CNY';//��������
		$arr[1][$key]['protocol'] = '';//Э���
		$arr[1][$key]['protocol_userid'] = '';//Э���û����
		$arr[1][$key]['id_type'] = '';//����֤������
		$arr[1][$key]['id'] = '';//֤����
		$arr[1][$key]['tel'] = $value['shoucardmobile'];//�ֻ���
		$arr[1][$key]['cust_userid'] = '';//�Զ����û���
		$arr[1][$key]['clearing_account'] = '';//����˺�
		$arr[1][$key]['remark'] = '';//��ע
		$arr[1][$key]['remark_one'] = '';//��ע1
		$arr[1][$key]['remark_two'] = '';//��ע2
		$arr[1][$key]['feedback'] = '';//������
		$arr[1][$key]['reason'] = '';//ԭ��
	}

	$collection = new Collection();
	$date = '';
	$count = count($arr[1]);
	$collection->collectionNumber = $arr[0]['collectionNumber'];//���պ�
	$collection->ersionNumber = $arr[0]['ersionNumber'];//�汾��
	$collection->merchantId = $arr[0]['merchantId'];//�̻�ID//�����������
	$collection->businessType = $arr[0]['businessType'];//ҵ������
	$collection->recordsAll = $count;//�ܼ�¼��

	for ($i = 0; $i < $count; $i++)
	{
		$date .= 
		str_pad( ( $i+1 ) , 7, '0', STR_PAD_LEFT ) . ',' . 
		$arr[1][$i]['e_user_code'] . ',' . 
		$arr[1][$i]['bank_code'] . ',' . 
		$arr[1][$i]['account_type'] . ',' . 
		$arr[1][$i]['account_no'] . ',' . 
		$arr[1][$i]['account_name'] . ',' . 
		$arr[1][$i]['province'] . ',' . 
		$arr[1][$i]['city'] . ',' . 
		$arr[1][$i]['bank_name'] . ',' . 
		$arr[1][$i]['account_prop'] . ',' . 
		$arr[1][$i]['amount'] . ',' . 
		$arr[1][$i]['currency'] . ',' . 
		$arr[1][$i]['protocol'] . ',' . 
		$arr[1][$i]['protocol_userid'] . ',' . 
		$arr[1][$i]['id_type'] . ',' . 
		$arr[1][$i]['id'] . ',' . 
		$arr[1][$i]['tel'] . ',' . 
		$arr[1][$i]['cust_userid'] . ',' . 
		$arr[1][$i]['clearing_account'] . ',' . 
		$arr[1][$i]['remark'] . ','.
		$arr[1][$i]['remark_one'] . ',' . 
		$arr[1][$i]['remark_two'] . ',' . 
		$arr[1][$i]['feedback'] . ',' . 
		$arr[1][$i]['reason'] . "\r\n";
		$collection->amountAll += $arr[1][$i]['amount'];
	}
	$collection->GetContent($date);
 ?>