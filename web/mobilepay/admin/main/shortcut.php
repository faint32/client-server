<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("shortcut","shortcut.html");
$uid = $loginuser ;
$db = new DB_test;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);

//1001��1���������á�ico_main_hr.gif��../basic/jxc_area_b.php��0��
//1001���š�1Ϊ�ϼ����š���������Ϊ���ơ�ico_main_hr.gifΪͼƬ
//../basic/jxc_area_b.php���ӵ�ַ��0Ϊ�Ƿ�Ϊ���

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

    	$a = $temp_arr2[0];
    	
    	if($loginusermenu[$a][item]==1 && $shortcut_arr[$a]== 1){// �ó��˵�
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
$t->pparse("out", "shortcut");    # ������ҳ��

function getallitem($fid,$emshow){		// �ó�����ʾ���е���
	global $arrall ;
	global $loginskin;
	global $n;
	$arryget = getarry($fid) ;		// �ó�������������
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
			if(($j == $ncount-1)&&($fid > 0)){	//�Ƿ񱾼���ĩ
				$isend = 1 ;
			}else{
				$isend = 0 ;
			}
			if($isunder==1){	// ����ĩ��
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
  
function getarry($id){		// �ó�������������
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
  // $istype �ǽڵ�-1���Ƿ�֧��-0��$isend �Ƿ񱾼���ĩ0�ǣ�1 ���� ��$upcode �ϲ����
  // $name ��ʾ������,$n�ڵ����� ,$icon ͼƬ,$code ��� $prg ��Ӧ���� ,$f �Ƿ��ҳ
  //$leave������
  global $loginskin;
	if($istype == 1){		// �ǽڵ�
		if($isend == 0){	// ������ĩ
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
		if($isend == 0){	// ������ĩ
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
//----------��ݲ˵���ѡ�����еĸ����----------------
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