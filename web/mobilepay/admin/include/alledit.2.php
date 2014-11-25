<?
if(isset($selecteditvalue)){
	array_shift($selecteditvalue); 	 
	if(count($selecteditvalue) == 0 ){
	  $gotourl2 = $gotourl;
	}else{
  	$gotourl2 = $PHP_SELF."?".$_SERVER['QUERY_STRING'] ;
  }
	Header("Location: $gotourl2");
	exit;
}
?>