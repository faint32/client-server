<?php
$thismenucode = "2002";   
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("dept_framework","dept_framework.ihtml");
$db=new db_test;

//$menufile = "menuarryfile.php" ;
//$menuarry = file($menufile);

$num=0;
$query = "select * from tb_dept order by fd_dept_id asc ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$deptid = $db->f(fd_dept_id);
		$fid = $db->f(fd_dept_fid);
		$name = $db->f(fd_dept_name);
		
		$arr_deptid [$num] = $deptid;
		$arr_fid [$num] = $fid;
		$arr_name [$num] = $name;
		//if($fid!=0){
	  	$arr_isunder[$fid] = 1;
	  //}
	  $num++;
	}
}

for($i=0;$i<count($arr_deptid);$i++){
	$a = $arr_deptid[$i];
	$arrall[$a][code] = $a;
	if(empty($arr_fid[$i])){
		$arrall[$a][fcode] = 0 ;
	}else{
		$arrall[$a][fcode] = $arr_fid[$i];
	}
	$arrall[$a][name] = $arr_name[$i];
	$tmpfid = $arr_fid[$i];
	
	if($arr_isunder[$tmpfid]==1){
		$arrall[$tmpfid][isunder] = 1;
	}else{
	  $arrall[$tmpfid][isunder] = 0;
  }
}
/*
for($i=0;$i<count($menuarry);$i++){
    	$temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("��",$temp_arr1);
    	$a = $temp_arr2[0];

		$arrall[$a][code] = $a;
		if(empty($temp_arr2[1])){
			$arrall[$a][fcode] = 0 ;
		}else{
			$arrall[$a][fcode] = $temp_arr2[1];
		}
		$arrall[$a][name] = $temp_arr2[2];
		$arrall[$a][isunder] = $temp_arr2[3];
}*/

$show = getallitem(0,"");

$t->set_var("show",$show);


$t->set_var("skin",$loginskin);
$t->pparse("out", "dept_framework");    # ������ҳ��

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
			$upcode = $emshow ;
			if(($j == $ncount-1)){	//�Ƿ񱾼���ĩ
				$isend = 1 ;
			}else{
				$isend = 0 ;
			}
			
			if($isunder==1){	// ����ĩ��
				if($isend == 0){
					$emshow1 = $emshow."<img src='../Images/t.gif' align=absmiddle border=\"0\">";
				}else{
					$emshow1 = $emshow."<img src='../Images/b.gif' align=absmiddle border=\"0\">" ;
				}
				$show .= showmenu($isunder,$isend,$emshow,$name,$code) ;
				$show2 = getallitem($code,$emshow1);
				if(!empty($show2)){
					$show .= "<div ID=\"el".$code."Child\" CLASS=\"child\">\n" ;
					$show .= $show2 ;
					$show .= "</div>\n" ;
				}
			}else{
				$show .= showmenu($isunder,$isend,$emshow,$name,$code) ;
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
    

function showmenu($istype,$isend,$upcode,$name,$code){
  // $istype �ǽڵ�-1���Ƿ�֧��-0��$isend �Ƿ񱾼���ĩ0�ǣ�1 ���� ��$upcode �ϲ����
  // $name ��ʾ������,$n�ڵ����� ,$icon ͼƬ,$code ��� $prg ��Ӧ���� ,$f �Ƿ��ҳ
  
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
    $show .= "<span class=\"item\"  onClick=\"".$javafun."('el".$code."'); nochange(); return false;\">" ;
    $show .= "<img NAME='imEx' SRC='../Images/".$icon."' BORDER='0' align=absmiddle ALT='+' ID='el".$code."Img'></span>";
    $show .= "<span class='heada'  onClick=\"".$javafun."('el".$code."');nochange();\">&nbsp;<font color='black' class='heada'>".$name."</font></span></nobr>" ;      		
    $show .= "</div>\n";
	}else{
		if($isend == 0){	// ������ĩ
			$icon = "c.gif";
		}else{		
			$icon = "l.gif";
		}
	  $show = "<nobr>".$upcode ;
	  $show .= "<img src='../Images/".$icon."' align=absmiddle border='0' >" ;
	  $show .= "<span class=\"heada\" >".$name."</span>" ;      
	  $show .= "</nobr><br>\n" ;
	}
	return $show ;
}
?>