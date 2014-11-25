<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("shortcut","shortcut.html");
$uid = $loginuser ;
$db = new DB_test;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);

//1001±1±地区设置±ico_main_hr.gif±../basic/jxc_area_b.php±0±
//1001代号、1为上级代号、地区设置为名称、ico_main_hr.gif为图片
//../basic/jxc_area_b.php连接地址、0为是否为最底

$query = "select * from web_teller where fd_tel_id = '$loginuser' ";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$str_shortcut = $db->f(fd_tel_shortcut);  //用于已经选择的快捷件
	
	//用户快捷菜单
	$arr_shortcut = explode("±",$str_shortcut);   
	for($i=0;$i<count($arr_shortcut);$i++){
	  $tmpkjcode = $arr_shortcut[$i];   //功能代号
	  $shortcut_arr[$tmpkjcode]= 1;     //功能代号
	}
}
//得出所有的编号和父编号
for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("±",$temp_arr1);
    	$tmpcode = $temp_arr2[0];    //代号
    	
    	$arr_allmenu[$i]    = $temp_arr2[0];    //代号
    	$arr_fcode[$tmpcode]= $temp_arr2[1];    //父代号
}

for($i=0;$i<count($arr_allmenu);$i++){
    	$menucode = $arr_allmenu[$i];    //代号

    	if($shortcut_arr[$menucode]==1){      	
      	if($arr_fcode[$menucode]!=0){
      		$tmpfcode = $arr_fcode[$menucode];
      		$shortcut_arr[$tmpfcode]=1;
      		menufcode($tmpfcode);
      	}
    	}
}

for($i=0;$i<count($menuarry);$i++){
    	$temp_arr1 = $menuarry[$i] ;
    	
    	$temp_arr2 = explode("±",$temp_arr1);

    	$a = $temp_arr2[0];
    	
    	if($loginusermenu[$a][item]==1 && $shortcut_arr[$a]== 1){// 得出菜单
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
$show = getallitem(0,"");
//$dofrist = "loader('main.php');" ;
$t->set_var("show",$show);
$t->set_var("dofrist",$dofrist);

$t->set_var("skin",$loginskin);
$t->pparse("out", "shortcut");    # 最后输出页面

function getallitem($fid,$emshow){		// 得出并显示所有的行
	global $arrall ;
	global $loginskin;
	global $n;
	$arryget = getarry($fid) ;		// 得出本级的所有行
	$show = "" ;
	if(is_array($arryget)){
		$ncount = count($arryget) ;
		for($j=0;$j<$ncount;$j++){
			$n++ ;
			$usen = $n ;
			$a = $arryget[$j][code];
			$code = $a ;			// $code = $arrall[$a][code];
			$name = $arrall[$a][name]; 
			$fcode = $arrall[$a][fcode] ;
			$leave = $arrall[$a][leave] ;
			$icon =  $arrall[$a][icon] ;	
			$program = $arrall[$a][program] ;
		 	$isunder = $arrall[$code][isunder] ; 
			//$openframe = $arrall[$a][openframe] ;
			$upcode = $emshow ;
			if(($j == $ncount-1)&&($fid > 0)){	//是否本级最末
				$isend = 1 ;
			}else{
				$isend = 0 ;
			}
			if($isunder==1){	// 不是末级
				if($isend == 0){
					
					//if($leave<2){
						//$emshow1 = $emshow."<div style='margin:0px 0px 0px 2px;'><img src='".$loginskin."b.gif' align=left border=\"0\"></div>" ;
					//}else{
					  $emshow1 = "<div style='margin:0px 0px 0px 2px;'><img src='".$loginskin."t.jpg' align=left border=\"0\"></div>".$emshow ;
				  //}
				}else{
					$emshow1 = $emshow."<img src='".$loginskin."t.jpg' align=left border=\"0\">" ;
				}
				$show .= showmenu($isunder,$isend,$emshow,$name,$icon,$code,$program,$leave) ;
				$show2 = getallitem($code,$emshow1);
				if(!empty($show2)){
					if($leave==1){
					  $show .= "<div ID=\"el".$code."Child\" CLASS=\"child\" style='margin:2px 0px 0px 0px;'>\n" ;
					  $show .= $show2 ;
					  $show .= "</div>\n" ;
				  }else{
				    $show .= $show2 ;
				  }
				}
			}else{
				$show .= showmenu($isunder,$isend,$emshow,$name,$icon,$code,$program,$leave) ;
			}
		}
	}
	return $show ;
}
  
function getarry($id){		// 得出本级的所有行
 	global $arrall;
 	@reset($arrall);
 	$i = 0 ;
	while(list($a) = @each($arrall)){
		if($arrall[$a][fcode] == $id){
		 	$arrsome[$i][code] = $arrall[$a][code];
			$i++ ;
		}
	}
	return $arrsome ;
}
    

function showmenu($istype,$isend,$upcode,$name,$uicon,$code,$prg,$leave){
  // $istype 是节点-1还是分支点-0，$isend 是否本级最末0是，1 不是 ，$upcode 上层代码
  // $name 显示的名称,$n节点序列 ,$icon 图片,$code 编号 $prg 对应程序 ,$f 是否分页
  //$leave代表级别
  global $loginskin;
	if($istype == 1){		// 是节点
		if($isend == 0){	// 本级最末
			//$icon = "p.gif";
			$icon = "left_line.jpg";
			$javafun = "expandIt" ;
		}else{		
			//$icon = "lp.gif";
			$icon = "left_line2.jpg";
			$javafun = "expandItEnd" ;
		}
		if($leave==1){
			    $show  = "<div style='margin:2px 0px 0px 0px;'><table border=0 cellspacing=0><tr><td style='cursor:hand' CLASS='parent' ID='el".$code."Img' valign=middle background='".$loginskin."/img/left_name2.jpg' height=27; width=151 onClick=\"".$javafun."('el".$code."'); swimg('el".$code."Img');return false; \"> <div style='height=20; width=100; margin:0px 0px 0px 37px;vertical-align:middle' >" ;
      		$show .= "<nobr>".$upcode ;
		      /*if(empty($prg)){
	      		$prg = "../main/page.php?code=".$code ;
      		}*/
      		$show .= "&nbsp;<font color=#000000 class='heada'>".$name."</font></nobr>" ;      				
      		$show .= "</div>\n</td></tr></table></div>";
		}else{
      		$show = "<div style='background:#E7EDFA;width=149;' align=left height=19px'><nobr>".$upcode ;
	        
	        $show .= "<div style='margin:0px 0px 0px 20px;height=19px;position:relative'><img src='".$loginskin."img/".$icon."' border='0' height=24px>" ;
	        $show .= "<span class=\"item\" style='color=#000000;top:10px;position:absolute;'>".$name."</span>" ;      
	        $show .= "</nobr></div></div>" ;
	  }
      		
	}else{
		if($isend == 0){	// 本级最末
			$icon = "left_line.jpg";
		}else{		
			$icon = "left_line2.jpg";
		}
		require ("../include/titlememo.php");
	        $show = "<div style='background:#E7EDFA;width=149;' align=left height=19px'><nobr>".$upcode ;	        
	        $show .= "<div style='margin:0px 0px 0px 20px;height=19px;position:relative'><img src='".$loginskin."img/".$icon."' border='0' height=24px>" ;
	        $show .= "<span class=\"item\" onclick=javascript:loader('".$prg."') style='cursor:hand;top:10px;position:absolute;color=#000000' ".$titlememo.">".$name."</span>" ;      
	        $show .= "</nobr></div></div>" ;
	}
	return $show ;
}
//----------快捷菜单中选出所有的父编号----------------
function menufcode($tmpfcode){
	global $shortcut_arr;
	global $arr_fcode;
      	
   if($arr_fcode[$tmpfcode]!=0){
      $tmpfcode = $arr_fcode[$tmpfcode];
      $shortcut_arr[$tmpfcode]=1;
      menufcode($tmpfcode);
   }
}
?>