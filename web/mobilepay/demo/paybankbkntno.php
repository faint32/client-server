<?
header ('Content-Type:text/html;charset=utf-8');
require ("../include/common.q.inc.php");
echo bankpayorder($money);
$weburl = "../";
function bankpayorder($money) {
	global $weburl;
	// 1. ��ʼ��
	$money = round($money*100,0) + 0;
	$payurl = $weburl . "upmp/examples/purchase.php?money=$money&cardinfo=$cardinfo";
	
	echo file_get_contents($payurl);
	
	$ch = curl_init ();
	// 2. ����ѡ�����URL
	

	curl_setopt ( $ch, CURLOPT_URL, $payurl );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	
	// 3. ִ�в���ȡHTML�ĵ�����
	//$arr_output[] = curl_exec($ch);
	$output = curl_exec ( $ch );
	if ($output === FALSE) {
		echo "cURL Error: " . curl_error ( $ch );
		exit ();
	}
	echo $output;
	
	$result = ob_get_contents ();
	echo $result;
	$str = str_replace ( "(", "", str_replace ( ")", "", str_replace ( "Array", "", str_replace ( " ", "", $output ) ) ) );
	
	$arr_value = explode ( "[", $str );
	for($i = 0; $i < count ( $arr_value ); $i ++) {
		$arr_valuetmp = explode ( "]=>", $arr_value [$i] );
		$arr_bankpay [$arr_valuetmp [0]] = $arr_valuetmp [1];
	}
	// 4. �ͷ�curl���
	curl_close ( $ch );
	//echo $bkntno;
	$bkorderno = $arr_bankpay ['tn'];
	return $bkorderno;
}
?>