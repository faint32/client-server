<?php
session_start();
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("left","left.html");
$db=new db_test;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);
//$query = "select * from tb_menu where fd_menu_upcode != '0' order by fd_menu_sno asc";
//$db->query($query);
//if($db->nf()){
//  while($db->next_record()){
//  	   $menu_code    = $db->f(fd_menu_code);
//  	   $menu_upcode  = $db->f(fd_menu_upcode);
//  	   $menu_jpg     = $db->f(fd_menu_jpg);
//  	   $menu_name    = $db->f(fd_menu_name);
//  	   $menu_url     = $db->f(fd_menu_url);
//  	   $menu_hz      = $db->f(fd_menu_hz);
//
//       if($menu_jpg == "arrow.jpg"){
//         $menu_jpg = "";
//       }
//  	   $menuarry[]   = $menu_code."±".$menu_upcode."±".$menu_name."±".$menu_jpg."±".$menu_url.$menu_hz;
//  }
//}


for($i=0;$i<count($menuarry);$i++){
	$temp_arr1 = $menuarry[$i] ;
    
	$temp_arr2 = explode("±",$temp_arr1);
	$a = $temp_arr2[0];   //代号

	if($loginusermenu[$a][item]==1){// 得出菜单
	$arrall[$a][code] = $a;
	if(empty($temp_arr2[1])){
	$arrall[$a][fcode] = 0 ;
	}else{
	$arrall[$a][fcode] = $temp_arr2[1];   //上级代号
	}
	$arrall[$a][name] = $temp_arr2[2];      //名称
	$arrall[$a][pic]  = $temp_arr2[3];      //图片
	$arrall[$a][url]  = $temp_arr2[4];      //连接地址
	$arrall[$a][isunder] = $temp_arr2[5];   //是否最后一级
	}
}

$show = getallitem("3i","");

$t->set_var("show",$show);


$t->set_var("skin",$loginskin);
$t->pparse("out", "left");    # 最后输出页面

function getallitem($fid,$emshow){		// 得出并显示所有的行
	global $arrall ;
	global $n;
	$arryget = getarry($fid) ;		// 得出本级的所有行
	$show = "" ;

	if(is_array($arryget)){
		$ncount = count($arryget) ;
		for($j=0;$j<$ncount;$j++){
			$n++ ;
			$usen = $n ;
			$a = $arryget[$j][code];
			$code = $a ;
			$name = $arrall[$a][name]; 			
			$fcode = $arrall[$a][fcode] ;
		 	$isunder = $arrall[$code][isunder] ; 
		 	$url = $arrall[$code][url] ; 
		 	$pic = $arrall[$code][pic] ; 
		 	
		 	
			$upcode = $emshow ;
			if(($j == $ncount-1)){	//是否本级最末
				$isend = 1 ;
			}else{
				$isend = 0 ;
			}
			
			if($isunder==1){	// 不是末级
				if($isend == 0){
					$emshow1 = $emshow."<img src=\"../Images/t.gif\" align='absmiddle' border=\"0\">";
				}else{
					$emshow1 = $emshow."<img src=\"../Images/b.gif\" align='absmiddle' border=\"0\">" ;
				}
				$show .= showmenu($isunder,$isend,$emshow,$name,$code,$url,$pic) ;
				$show2 = getallitem($code,$emshow1);
				if(!empty($show2)){
					$show .= "<div id=\"el".$code."Child\" class=\"child\">\n" ;
					$show .= $show2 ;
					$show .= "</div>\n" ;
				}
			}else{
				$show .= showmenu($isunder,$isend,$emshow,$name,$code,$url,$pic) ;
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
		if($a!=0){
		  if($arrall[$a][fcode] == $id){
		   	$arrsome[$i][code] = $arrall[$a][code];
		  	$i++ ;
		  }
	  }
	}
	return $arrsome ;
}
    
function showmenu($istype,$isend,$upcode,$name,$code,$url,$pic){
  // $istype 是节点-1还是分支点-0，$isend 是否本级最末0是，1 不是 ，$upcode 上层代码
  // $name 显示的名称,$n节点序列 ,$pic 图片,$code 编号 $url 对应程序 
	if($istype == 1){		// 是节点
		if($isend == 0){	// 本级最末
			$icon = "p.gif";
			$javafun = "expandIt" ;
		}else{		
			$icon = "lp.gif";
			$javafun = "expandItEnd" ;
		}
		$show  = "<div id='el".$code."Parent' class='parent'>" ;
		$show .= "<div>".$upcode ;
		$show .= "<span class=\"item\"  onClick=\"".$javafun."('el".$code."'); return false;\" style='cursor:hand'>" ;
		$show .= "<img name='imEx' src='../Images/".$icon."' border='0' align='absmiddle' alt='+' id='el".$code."Img'>";
		$show .= "</span>";
		$show .= "<span class='heada' style='cursor:hand' onClick=\"".$javafun."('el".$code."');loader('".$url."','".$code."')\">&nbsp;<font color='black' class='heada'>".$name."</font></span></div>" ;      		
		$show .= "</div>\n";
	}else{
		if($isend == 0){	// 本级最末
			$icon = "c.gif";
		}else{		
			$icon = "l.gif";
		}
	  $show = "<nobr>".$upcode ;
	  $show .= "<img src=\"../Images/".$icon."\" align='absmiddle' border='0' >" ;
	  $show .= "&nbsp;";
	  $show .= "<a class=\"item\" HREF=javascript:loader('".$url."','".$code."') style='color=#000000;cursor:hand'>".$name."</a>" ;      
	  $show .= "</nobr><br>\n" ;
	}
	return $show ;
}
?>