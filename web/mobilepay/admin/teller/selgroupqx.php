<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl= "tb_usegroup_b.php" ;
$gotourl = $gourl.$tempurl ;


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
//  	   $menu_url     = "../web/new1_linkmobile.php?pagename=".$menu_url;
//
//  	   $menuarry_tmp1[]   = $menu_code."±".$menu_upcode."±".$menu_name."±".$menu_jpg."±".$menu_url.$menu_hz;
//  }
//}

$menufile = "../include/menuarryfile.php" ;
$menuarry_tmp2 = file($menufile);

for($i=0; $i < count($menuarry_tmp1); $i++){
   $menuarry[] = $menuarry_tmp1[$i];
}

for($i=0; $i < count($menuarry_tmp2); $i++){
   $menuarry[] = $menuarry_tmp2[$i];
}

if(!empty($done)){		// 保存处理
	  for($i=0;$i<count($prgqx);$i++){
	  	if(empty($str_prgqx)){    //把权限功能组合为字符
	  		  $str_optqx ="";
	  		  $prgcode = $prgqx[$i];
	  		  for($k=0;$k<count($optqx[$prgcode]);$k++){
	  		  	if(empty($str_optqx)){
	  		  		$str_optqx =$optqx[$prgcode][$k];
	  		  	}else{
	  		  		$str_optqx .= "^".$optqx[$prgcode][$k];
	  		  	}
	  		  }
	  		  if(!empty($str_optqx)){
	  		  	 $str_optqx = "@".$str_optqx;
	  		  }
	  		  $str_prgqx = $prgqx[$i].$str_optqx;
	  	}else{
	  		  $str_optqx ="";
	  		  $prgcode = $prgqx[$i];
	  		  for($k=0;$k<count($optqx[$prgcode]);$k++){
	  		  	if(empty($str_optqx)){
	  		  		$str_optqx =$optqx[$prgcode][$k];
	  		  	}else{
	  		  		$str_optqx .= "^".$optqx[$prgcode][$k];
	  		  	}
	  		  }
	  		  if(!empty($str_optqx)){
	  		  	 $str_optqx = "@".$str_optqx;
	  		  }
	  		  $str_prgqx .= "±".$prgqx[$i].$str_optqx;
	  	}
	  }
	  $query = "update web_usegroup set fd_usegroup_qx = '$str_prgqx'where fd_usegroup_id = '$id'";
	  $db->query($query);
	  Header("Location: $gotourl");	
	            
}else{   //查询已经能有的权限
	  $query = "select * from web_usegroup where fd_usegroup_id = '$id' ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$groupqx = $db->f(fd_usegroup_qx);    //权限
	  	$gname   = $db->f(fd_usegroup_name);    //权限
	  	$arr_prgqx = explode("±",$groupqx);   

	  	for($i=0;$i<count($arr_prgqx);$i++){
	  		$temp_arr = explode("@",$arr_prgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //功能代号
	  		$prgqx_arr[$tmpcode]= 1;   //功能代号

	  		if(!empty($temp_arr[1])){
	  			$str_optqx=explode("^",$temp_arr[1]);    //进一步拆分新增等功能
	  			for($k=0;$k<count($str_optqx);$k++){
	  				$tmpoptqx = $str_optqx[$k];      //新增等权限代号
	  				$optqx_arr[$tmpcode][$tmpoptqx]=1;
	  			}
	  		}
	  	}
	  }
    $show = showqx($menuarry,$prgqx_arr,$optqx_arr) ;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("selgroupqx","selgroupqx.html"); 

$t->set_var("id"  ,$id);
$t->set_var("name",$gname);
$t->set_var("show",$show);
$t->set_var("gotourl",$gotourl);  // 转用的地址

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "selgroupqx");    # 最后输出页面

function showqx($menuarry , $prgqx_arr,$optqx_arr){
	global $loginskin;
	$show = "<table width=98% border=0 cellpadding=0 cellspacing=0>";
	for($i=0;$i<count($menuarry);$i++){
		  $temp_arr1 = $menuarry[$i] ;
    	$temp_arr2 = explode("±",$temp_arr1);
    	$code    = $temp_arr2[0];    //代号
    	$fcode   = $temp_arr2[1];    //父代号
    	$name    = $temp_arr2[2];	   //名称
    	$isnode  = $temp_arr2[5];	   //是否为节点，1为是，0为不是，也就是代表最底
    	$level   = $temp_arr2[6];	   //级别
    	$optqx   = $temp_arr2[7];	   //新增、修改、删除权限
    	$optqxcode = $temp_arr2[8]; 	//新增、修改、删除权限代号
    	
    	$arr_optqx = explode("^",$optqx);
    	$arr_optqxcode = explode("^",$optqxcode);
    	
    	$makeempty = makenbsp($level);     //宿行
    	
    	if($isnode ==1){   //如果是节点
    		 $checkbox ="";
    		 $show .= "<tr><td height=25 noWrap width='350' class='titlefont'>&nbsp;&nbsp;&nbsp;&nbsp;".$makeempty.$name."：</td></tr>";
    		 if($level==1){
    		    $show .= "<tr><td align='left' colspan='2'><div style='margin:0px 0px 0px 12px;'><img src='".$loginskin."shortline.jpg' ></div></td></tr>";
    	   }
    	}else{
    		 if($prgqx_arr[$code]==1){  //判断是否已经已有该权限
    		 	   $prg_check ="checked";
    		 	   //$prg_disabled ="disabled";
    		 }else{
    		 	   $prg_check ="";
    		 	   //$prg_disabled ="";
    		 }

    	   $checkbox ="<input id='code".$code."' type='checkbox' name='prgqx[]' value='$code' ".$prg_check." >";
    	   $show .= "<tr><td height=25 noWrap width='350' >".$makeempty.$checkbox.$name."</td>";
    	   
    	   $show .= "<td height=25 >";
    	    
    	   $optqxbox = "";
    	   $optqx_count=count($arr_optqx);   //新增、修改、删除权限数组
    	   for($optqx_i=0;$optqx_i< $optqx_count;$optqx_i++){
    	   	  $optcode = $arr_optqxcode[$optqx_i];
    	   	  
    	   	  $optcode = intval($optcode);
    	   	  if($optqx_arr[$code][$optcode]==1){  //判断是否已经已有该权限
    	   	  	  $opt_check ="checked";
    	   	  	  
    		    }else{
    		    	  $opt_check ="";
    		    }
    		    if(!empty($arr_optqx[$optqx_i])){
    	   	    $optqxbox .="<input id='opt".$arr_optqxcode[$optqx_i]."' type='checkbox' name='optqx[$code][]' value=".$arr_optqxcode[$optqx_i]." onclick=checkcode(".$code.") ".$opt_check." >";
    	   	    $optqxbox .="&nbsp;".$arr_optqx[$optqx_i]."&nbsp;&nbsp;";
    	   	  }
    	   }
         $show .= $optqxbox. "</td></tr>";
    	  
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