<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl= "tb_teller_b.php" ;
$gotourl = $gourl.$tempurl ;

$menufile = "../include/menuarryfile.php" ;
$menuarry = file($menufile);
if(!empty($done)){		// 保存处理
	  for($i=0;$i<count($prg_shortcut);$i++){
	  	if(empty($str_prgqx)){    //把权限功能组合为字符
	  		  $str_prgqx = $prg_shortcut[$i];
	  	}else{
	  		  $str_prgqx .= "±".$prg_shortcut[$i];
	  	}
	  }
	  $query = "update web_teller set fd_tel_shortcut = '$str_prgqx' where fd_tel_id = '$loginuser'";
	  $db->query($query);	            
}

//------------查询权限和快捷菜单---------------------
	  $query = "select * from web_teller 
	           left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid
	           where fd_tel_id = '$loginuser' ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$telqx = $db->f(fd_tel_doprg);        //用户权限
	  	$groupqx = $db->f(fd_usegroup_qx);    //用户组权限
	  	$telname = $db->f(fd_tel_name);       //用户名字
	  	$shortcut = $db->f(fd_tel_shortcut);  //用于已经选择的快捷件
	  	
	  	//用户组权限分切
	  	$arr_prgqx = explode("±",$groupqx);   
	  	for($i=0;$i<count($arr_prgqx);$i++){
	  		$temp_arr = explode("@",$arr_prgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //功能代号
	  		$prgqx_arr[$tmpcode]= 1;   //功能代号
	  	}
	  	//---------------------------------------------
	  	
	  	//用户权限分切	
	  	$arr_telprgqx = explode("±",$telqx);   
	  	for($i=0;$i<count($arr_telprgqx);$i++){
	  		$temp_arr = explode("@",$arr_telprgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //功能代号
	  		$prgqx_arr[$tmpcode]= 1;   //功能代号
	  	}
	  	//---------------------------------
	  	
	  	//用户快捷菜单
	  	$arr_shortcut = explode("±",$shortcut);   
	  	for($i=0;$i<count($arr_shortcut);$i++){
	  		$tmpkjcode = $arr_shortcut[$i];   //功能代号
	  		$shortcut_arr[$tmpkjcode]= 1;     //功能代号
	  	}
	  	//---------------------------------
	  }
	  
	  

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("shortcutmenu","shortcutmenu.html"); 

for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("±",$temp_arr1);
    	$tmpcode = $temp_arr2[0];    //代号
    	
    	$arr_allmenu[code][$i]    = $temp_arr2[0];    //代号
    	
    	$arr_fcode[$tmpcode]   = $temp_arr2[1];    //父代号
}

for($i=0;$i<count($arr_allmenu[code]);$i++){
    	$menucode = $arr_allmenu[code][$i];    //代号

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
$t->set_var("gotourl",$gotourl);  // 转用的地址

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "shortcutmenu");    # 最后输出页面

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
    	$temp_arr2 = explode("±",$temp_arr1);
    	$code    = $temp_arr2[0];    //代号
    	$fcode   = $temp_arr2[1];    //父代号
    	$name    = $temp_arr2[2];	   //名称
    	$isnode  = $temp_arr2[5];	   //是否为节点，1为是，0为不是，也就是代表最底
    	$level   = $temp_arr2[6];	   //级别
    	
    	$makeempty = makenbsp($level);     //宿行
    	if($menu_arr[$code]==1){    //是否有该权限
    	   if($isnode ==1){   //如果是节点    		 
    		    $show .= "<tr><td height=25 noWrap width='98%' class='titlefont'>&nbsp;&nbsp;&nbsp;&nbsp;".$makeempty.$name."</td></tr>";
    		    if($level==1){
    		    $show .= "<tr><td align='left' colspan='2'>&nbsp;&nbsp;<img src='".$loginskin."shortline.jpg' ></td></tr>";
    	   }
    		    $nodecount=0;
    		 }else{
    		
    		    if($shortcut_arr[$code]==1){  //判断该用户所属的组是否已经已有该权限
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

//代码宿行
function makenbsp($level){
	$empstr = "" ;
	for($i=1;$i<$level;$i++){	// 缩行对齐
		$empstr .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
	}
	return $empstr ;
}

?>