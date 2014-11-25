<?
//$fname功能名，$operate相关操作，$username用户名
$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);

for($i=0;$i<count($menuarry);$i++){
	   $temp_arr1 = $menuarry[$i] ;
    	
     $temp_arr2 = explode("±",$temp_arr1);
     $tmpcode = $temp_arr2[0];
     
  if($tmpcode == $thismenucode){
  $fname = $temp_arr2[2]; 
 }
}

$operate = $action;
$username = $loginname;

if($operate == ""){
	$operate = "browse";
 }

  $ip=$_SERVER["REMOTE_ADDR"];   
  $lyear	= date("Y",mktime());
  $lmonth	= date("m",mktime());
  $lday	= date("d",mktime()); 
  $time = $lyear."-".$lmonth."-".$lday;
	$dir = "../logs";	
	@chmod($dir.'/logs.txt', 0777);
	$string = "$username|##|$ip|##|$time|##|$fname|##|$operate\n";
	$filehandle=@fopen($dir.'/logs.txt',"a");
	if(!$filehandle) {
		echo "日志目录出错，请在上级目录新建logs目录！";
	}
	
	@flock($filehandle, 2);
	@fwrite($filehandle, $string);
	@fclose($filehandle);

?>