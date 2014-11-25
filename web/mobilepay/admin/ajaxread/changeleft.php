<?   
header('Content-Type:text/html;charset=GB2312'); 
require("../include/common.inc.php");
require("union.php");

$value = u2g($value);


$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);

//1001±1±地区设置±ico_main_hr.gif±../basic/jxc_area_b.php±0±
//1001代号、1为上级代号、地区设置为名称、ico_main_hr.gif为图片
//../basic/jxc_area_b.php连接地址、0为是否为最底

for($i=0;$i<count($menuarry);$i++){
    	$temp_arr1 = $menuarry[$i] ; 
   	
    	$temp_arr2 = explode("±",$temp_arr1);       
      
    	$a = $temp_arr2[0];		
		
    	if($loginusermenu[$a][item]==1){// 得出菜单
		     if (strstr($temp_arr2[2], $value) and $temp_arr2[4] <> ""){			 		   		     
		          
		         $arrname[] =  $temp_arr2[2];
		         $arrprogram[] = $temp_arr2[4];
				 $arrunder[] = $temp_arr2[5];
		         	 
			 }else{
			 $arrall[$a][code] = $a;
		     if(empty($temp_arr2[1])){
			      $arrall[$a][fcode] = 0 ;
		     }else{
			      $arrall[$a][fcode] = $temp_arr2[1];
		     }			 
		     $arrall[$a][name] = $temp_arr2[2];
		     $arrall[$a][icon] = $temp_arr2[3];
		     $arrall[$a][program] = $temp_arr2[4];
		     $arrall[$a][isunder] = $temp_arr2[5];
		     $arrall[$a][leave] = $temp_arr2[6];;
	 }  
    }  
}
if (!empty($value)){
  	 for($i=0;$i<count($arrname);$i++){
            $name  = $arrname[$i];
            $prg = $arrprogram[$i];
			$check = $arrunder[$i];
			if($check=='0')
			{
	        require ("../include/titlememo.php");
	        $show .= "<div style='left:8px;width:150px;height=19px;position:relative;background:#E7EDFA;'><img src='".$loginskin."img/left_line.jpg' border='0' height=24px>";
	        $show .= "<span class=\"item\" onclick=javascript:loader('".$prg."') style='cursor:hand;top:10px;position:absolute;color=#000000' ".$titlememo.">".$name."</span>" ;      
	        $show .= "</div>" ;
			}

    }
  
}else{
$show = 1;
}

echo $show;


  
?>