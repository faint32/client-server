<?
function getmenuarrayfile($code)
{
$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);
for($i=0;$i<count($menuarry);$i++){
    	$temp_arr1 = $menuarry[$i] ; 
   	     //echo $temp_arr1."<br>";
    	$temp_arr2 = explode("¡À",$temp_arr1);       
       // echo var_dump($temp_arr2);
    	$a = $temp_arr2[0];	
		$b = $temp_arr2[2];	
			//echo $a;		   		     
		if($a==$code)
		{
			$name=$b;
			
		    $break;
		}
}
require ("../include/titlememo.php");
return $name."@@".$titlememo;
}

 
?>