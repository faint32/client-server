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
//  	   $menuarry[]   = $menu_code."��".$menu_upcode."��".$menu_name."��".$menu_jpg."��".$menu_url.$menu_hz;
//  }
//}


for($i=0;$i<count($menuarry);$i++){
	$temp_arr1 = $menuarry[$i] ;
    
	$temp_arr2 = explode("��",$temp_arr1);
	$a = $temp_arr2[0];   //����

	if($loginusermenu[$a][item]==1){// �ó��˵�
	$arrall[$a][code] = $a;
	if(empty($temp_arr2[1])){
	$arrall[$a][fcode] = 0 ;
	}else{
	$arrall[$a][fcode] = $temp_arr2[1];   //�ϼ�����
	}
	$arrall[$a][name] = $temp_arr2[2];      //����
	$arrall[$a][pic]  = $temp_arr2[3];      //ͼƬ
	$arrall[$a][url]  = $temp_arr2[4];      //���ӵ�ַ
	$arrall[$a][isunder] = $temp_arr2[5];   //�Ƿ����һ��
	}
}

$show = getallitem("3i","");

$t->set_var("show",$show);


$t->set_var("skin",$loginskin);
$t->pparse("out", "left");    # ������ҳ��

function getallitem($fid,$emshow){		// �ó�����ʾ���е���
	global $arrall ;
	global $n;
	$arryget = getarry($fid) ;		// �ó�������������
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
			if(($j == $ncount-1)){	//�Ƿ񱾼���ĩ
				$isend = 1 ;
			}else{
				$isend = 0 ;
			}
			
			if($isunder==1){	// ����ĩ��
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
  
function getarry($id){		// �ó�������������
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
  // $istype �ǽڵ�-1���Ƿ�֧��-0��$isend �Ƿ񱾼���ĩ0�ǣ�1 ���� ��$upcode �ϲ����
  // $name ��ʾ������,$n�ڵ����� ,$pic ͼƬ,$code ��� $url ��Ӧ���� 
	if($istype == 1){		// �ǽڵ�
		if($isend == 0){	// ������ĩ
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
		if($isend == 0){	// ������ĩ
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