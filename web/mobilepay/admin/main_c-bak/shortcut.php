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
	$str_shortcut = $db->f(fd_tel_shortcut);  //�����Ѿ�ѡ��Ŀ�ݼ�
	
	//�û���ݲ˵�
	$arr_shortcut = explode("��",$str_shortcut);   
	for($i=0;$i<count($arr_shortcut);$i++){
	  $tmpkjcode = $arr_shortcut[$i];   //���ܴ���
	  $shortcut_arr[$tmpkjcode]= 1;     //���ܴ���
	}
}
//�ó����еı�ź͸����
for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("��",$temp_arr1);
    	$tmpcode = $temp_arr2[0];    //����
    	
    	$arr_allmenu[$i]    = $temp_arr2[0];    //����
    	$arr_fcode[$tmpcode]= $temp_arr2[1];    //������
}

for($i=0;$i<count($arr_allmenu);$i++){
    	$menucode = $arr_allmenu[$i];    //����

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
    $temp_arr2 = explode("��",$temp_arr1);
    $a = $temp_arr2[0];   //����
    
    if($loginusermenu[$a][item]==1 && $shortcut_arr[$a]== 1){// �ó��˵�
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

$show = getallitem(0,"");

$t->set_var("show",$show);


$t->set_var("skin",$loginskin);
$t->pparse("out", "shortcut");    # ������ҳ��

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
    

function showmenu($istype,$isend,$upcode,$name,$code,$url){
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
    $show  = "<div ID='el".$code."Parent' CLASS='parent'>" ;
    $show .= "<nobr>".$upcode ;
    $show .= "<span class=\"item\"  onClick=\"".$javafun."('el".$code."');return false;\">" ;
    $show .= "<img NAME='imEx' SRC='../Images/".$icon."' BORDER='0' align=absmiddle ALT='+' ID='el".$code."Img'></span>";
    $show .= "<span class='heada'  onClick=\"".$javafun."('el".$code."');\">&nbsp;<font color='black' class='heada'>".$name."</font></span></nobr>" ;      		
    $show .= "</div>\n";
	}else{
		if($isend == 0){	// ������ĩ
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