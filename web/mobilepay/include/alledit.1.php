<?
$selecteditarry = explode("&",$_SERVER['QUERY_STRING']);
$selectedittemp =explode("=",$selecteditarry[count($selecteditarry)- 2]);
$selectedittempid= $selectedittemp[0]; 
if(isset($selecteditvalue)){
  if(count($selecteditvalue) == 0 ){	
	$gotourl2 = $gotourl;
	Header("Location: $gotourl2");
	}else{		
	$$selectedittempid = $selecteditvalue[0];		
	$gotourl2 = $PHP_SELF."?".$_SERVER['QUERY_STRING'] ;
  }
}
?>