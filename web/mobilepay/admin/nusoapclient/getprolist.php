<?
require_once('../nusoaplib/nusoap.php');



$client=new soapclient('http://www.ms56.net/managementfile/nusoap/AutoGetFilepath.php?wsdl',true);
$err = $client->getError();

$scatid 	= "5";
$dateid   	= $listid;
$params1 = array('scatid'=>$scatid,'dateid'=>$dateid);
$propiclist = $client->call('getFilepath',$params1);
print ($propiclist);


	//if($client->fault)
//	{
//	echo '<h2>Fault (This is expected)</h2><pre>';
//	print_r($reversed);
//	echo '</pre>';
//	}else{
//	$err = $client->getError();
//	if($err)
//	{
//	echo '<h2>Error</h2><pre>' . $err . '</pre>';
//	}else{
//	echo '<h2>Result</h2><pre>'; print_r($reversed);
//	echo '</pre>';
//	}
//	}
//	
//echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>