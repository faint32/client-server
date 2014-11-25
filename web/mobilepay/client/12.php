<?php
/*
 *	$Id: wsdlclient6.php,v 1.1 2004/01/26 07:15:20 snichol Exp $
 *
 *	WSDL client sample.
 *
 *	Service: WSDL
 *	Payload: rpc/encoded
 *	Transport: http
 *	Authentication: none
 */
require_once('../nusoaplib/nusoap.php');

$client = new soapclient('http://192.168.1.5/apksoap/sever/autoGetMainMenu.php?wsdl', true);
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
$client->soap_defencoding = 'UTF-8';
$params = array(
	'database' => $key);

$result = $client->call('getMainMenu', $params);


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
