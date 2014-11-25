<?php
header('Content-Type:text/html;charset=utf-8'); 
require_once('../nusoaplib/nusoap.php');
require_once('../include/common.q.inc.php');
$client = new soapclient($weburl.'wsdlRegUser.php?wsdl', true);
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
$client->soap_defencoding = 'UTF-8';
$params = array(
	'regphone' => '13802501862','regpassword' => '123456');

$result = $client->call('regUserPwd', $params);


//$p = $result;

//$login = $xml->cmmname;//
print_r($result);
// Check for a fault
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
?>
