<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl= "tb_teller_b.php" ;
$gotourl = $gourl.$tempurl ;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);
if(!empty($done)){		// ���洦��
	  for($i=0;$i<count($prg_shortcut);$i++){
	  	if(empty($str_prgqx)){    //��Ȩ�޹������Ϊ�ַ�
	  		  $str_prgqx = $prg_shortcut[$i];
	  	}else{
	  		  $str_prgqx .= "��".$prg_shortcut[$i];
	  	}
	  }
	  $query = "update web_teller set fd_tel_shortcut = '$str_prgqx' where fd_tel_id = '$loginuser'";
	  $db->query($query);	            
}

//------------��ѯȨ�޺Ϳ�ݲ˵�---------------------
	  $query = "select * from web_teller 
	           left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid
	           where fd_tel_id = '$loginuser' ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$telqx = $db->f(fd_tel_doprg);        //�û�Ȩ��
	  	$groupqx = $db->f(fd_usegroup_qx);    //�û���Ȩ��
	  	$telname = $db->f(fd_tel_name);       //�û�����
	  	$shortcut = $db->f(fd_tel_shortcut);  //�����Ѿ�ѡ��Ŀ�ݼ�
	  	
	  	//�û���Ȩ�޷���
	  	$arr_prgqx = explode("��",$groupqx);   
	  	for($i=0;$i<count($arr_prgqx);$i++){
	  		$temp_arr = explode("@",$arr_prgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //���ܴ���
	  		$prgqx_arr[$tmpcode]= 1;   //���ܴ���
	  	}
	  	//---------------------------------------------
	  	
	  	//�û�Ȩ�޷���	
	  	$arr_telprgqx = explode("��",$telqx);   
	  	for($i=0;$i<count($arr_telprgqx);$i++){
	  		$temp_arr = explode("@",$arr_telprgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //���ܴ���
	  		$prgqx_arr[$tmpcode]= 1;   //���ܴ���
	  	}
	  	//---------------------------------
	  	
	  	//�û���ݲ˵�
	  	$arr_shortcut = explode("��",$shortcut);   
	  	for($i=0;$i<count($arr_shortcut);$i++){
	  		$tmpkjcode = $arr_shortcut[$i];   //���ܴ���
	  		$shortcut_arr[$tmpkjcode]= 1;     //���ܴ���
	  	}
	  	//---------------------------------
	  }
	  
	  

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("shortcutmenu","shortcutmenu.html"); 

for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("��",$temp_arr1);
    	$tmpcode = $temp_arr2[0];    //����
    	
    	$arr_allmenu[code][$i]    = $temp_arr2[0];    //����
    	
    	$arr_fcode[$tmpcode]   = $temp_arr2[1];    //������
}

for($i=0;$i<count($arr_allmenu[code]);$i++){
    	$menucode = $arr_allmenu[code][$i];    //����

    	if($prgqx_arr[$menucode]==1){      	
      	if($arr_fcode[$menucode]!=0){
      		$tmpfcode = $arr_fcode[$menucode];
      		$prgqx_arr[$tmpfcode]=1;
      		menufcode($tmpfcode);
      	}
    	}
}

$show = showqx($menuarry , $prgqx_arr,$shortcut_arr);

$t->set_var("id"  ,$id);
$t->set_var("name",$telname);
$t->set_var("show",$show);
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "shortcutmenu");    # ������ҳ��

function menufcode($tmpfcode){
	global $prgqx_arr;
	global $arr_fcode;
      	
   if($arr_fcode[$tmpfcode]!=0){
      $tmpfcode = $arr_fcode[$tmpfcode];
      $prgqx_arr[$tmpfcode]=1;
      menufcode($tmpfcode);
   }
}

function showqx($menuarry , $menu_arr , $shortcut_arr){
	global $loginskin;
	$show = "<table width=98% border=0 cellpadding=0 cellspacing=0>";
	$nodecount=0;
	for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("��",$temp_arr1);
    	$code    = $temp_arr2[0];    //����
    	$fcode   = $temp_arr2[1];    //������
    	$name    = $temp_arr2[2];	   //����
    	$isnode  = $temp_arr2[5];	   //�Ƿ�Ϊ�ڵ㣬1Ϊ�ǣ�0Ϊ���ǣ�Ҳ���Ǵ������
    	$level   = $temp_arr2[6];	   //����
    	
    	$makeempty = makenbsp($level);     //����
    	if($menu_arr[$code]==1){    //�Ƿ��и�Ȩ��
    	   if($isnode ==1){   //����ǽڵ�    		 
    		    $show .= "<tr><td height=25 noWrap width='98%' class='titlefont'>&nbsp;&nbsp;&nbsp;&nbsp;".$makeempty.$name."</td></tr>";
    		    if($level==1){
    		    $show .= "<tr><td align='left' colspan='2'>&nbsp;&nbsp;<img src='".$loginskin."shortline.jpg' ></td></tr>";
    	   }
    		    $nodecount=0;
    		 }else{
    		
    		    if($shortcut_arr[$code]==1){  //�жϸ��û����������Ƿ��Ѿ����и�Ȩ��
    		 	     $prg_check ="checked";
    		    }else{
    		 	     $prg_check ="";
    		    }
    		    
    	      $checkbox ="<input id='code".$code."' type='checkbox' name='prg_shortcut[]' value='$code' ".$prg_check." >";
    	      
    	      if($nodecount==0){
    	         $show .= "<tr><td height=25 noWrap width='98%' >".$makeempty.$checkbox.$name;
    	         $nodecount++;
    	      }elseif($nodecount<5 and $nodecount>0){
    	         $nodecount++;
    	         $show .= "&nbsp;&nbsp;&nbsp;".$checkbox.$name;
    	      }elseif($nodecount==5){
    	         $show .= "&nbsp;&nbsp;&nbsp;".$checkbox.$name."</td></tr>";
    	         $nodecount=0;
    	      }
    	      
         }
      }
    
	}
	$show .= "</table>";
	return $show;	
}

//��������
function makenbsp($level){
	$empstr = "" ;
	for($i=1;$i<$level;$i++){	// ���ж���
		$empstr .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
	}
	return $empstr ;
}

?>