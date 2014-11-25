<?php
session_start();
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("shortcut","shortcut.html");
$db=new db_test;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);

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
    $a = $temp_arr2[0];   //代号
    
    if($loginusermenu[$a][item]==1 && $shortcut_arr[$a]== 1){// 得出菜单
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

$show = getallitem(0,"");

$t->set_var("show",$show);


$t->set_var("skin",$loginskin);
$t->pparse("out", "shortcut");    # 最后输出页面

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
					$emshow1 = $emshow."<img src=\"../Images/t.gif\" align=absmiddle border=\"0\">";
				}else{
					$emshow1 = $emshow."<img src=\"../Images/b.gif\" align=absmiddle border=\"0\">" ;
				}
				$show .= showmenu($isunder,$isend,$emshow,$name,$code,$url) ;
				$show2 = getallitem($code,$emshow1);
				if(!empty($show2)){
					$show .= "<div ID=\"el".$code."Child\" CLASS=\"child\">\n" ;
					$show .= $show2 ;
					$show .= "</div>\n" ;
				}
			}else{
				$show .= showmenu($isunder,$isend,$emshow,$name,$code,$url) ;
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
    

function showmenu($istype,$isend,$upcode,$name,$code,$url){
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
    $show  = "<div ID='el".$code."Parent' CLASS='parent'>" ;
    $show .= "<nobr>".$upcode ;
    $show .= "<span class=\"item\"  onClick=\"".$javafun."('el".$code."');return false;\">" ;
    $show .= "<img NAME='imEx' SRC='../Images/".$icon."' BORDER='0' align=absmiddle ALT='+' ID='el".$code."Img'></span>";
    $show .= "<span class='heada'  onClick=\"".$javafun."('el".$code."');\">&nbsp;<font color='black' class='heada'>".$name."</font></span></nobr>" ;      		
    $show .= "</div>\n";
	}else{
		if($isend == 0){	// 本级最末
			$icon = "c.gif";
		}else{		
			$icon = "l.gif";
		}
	  $show = "<nobr>".$upcode ;
	  $show .= "<img src=\"../Images/".$icon."\" align=absmiddle border='0' >" ;
	  $show .= "<a class=\"item\" HREF=javascript:addshowdiv('".$name."','".$code."','".$url."'); style='color=#000000'>".$name."</a>" ;      
	  $show .= "</nobr><br>\n" ;
	}
	return $show ;
}

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