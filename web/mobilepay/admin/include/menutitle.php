<?php

require ("../include/getmenuarrayfile.php");
$temp_arr1 = getmenuarrayfile($thismenucode);
$temp_arr2 = explode("@@",$temp_arr1); 
//echo var_dump($temp_arr2);
$t->set_var("menuname"   ,$temp_arr2[0]."&nbsp;");
$t->set_var("titlememo"  ,$temp_arr2[1]);

?>