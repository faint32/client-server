<?php
$thismenucode = "9101";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl = "tellerlist.php?listid=$listid" ;
$gotourl = $gourl.$tempurl ;

$menufile = "menuarryfile.php" ;
$menuarry = file($menufile);
if(!empty($done)){		// 保存处理
	  for($i=0;$i<count($prgqx);$i++){
	  	$arr_prgqxtmp[$prgqx[$i]] = 1;
	  }
	  while(list($key,$val)=@each($optqx)){
	  	$arr_prgqxtmp[$key] = 1;
	  }
	  if(!empty($optqx)){
	  reset($optqx);
	  }
	  //for($i=0;$i<count($prgqx);$i++){
	  while(list($key,$val)=@each($arr_prgqxtmp)){
	  	if(empty($str_prgqx)){    //把权限功能组合为字符
	  		  $str_optqx ="";
	  		  $prgcode = $key;
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
	  		  $str_prgqx = $key.$str_optqx;
	  	}else{
	  		  $str_optqx ="";
	  		  $prgcode = $key;
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
	  		  $str_prgqx .= "±".$key.$str_optqx;
	  	}
	  }
	  $query = "update tb_cus_teller set fd_tel_doprg = '$str_prgqx'where fd_tel_id = '$telid'";
	 
	  $db->query($query);
	  
	  Header("Location: $gotourl");
	            
}else{   //查询已经能有的权限
	
	  $query = "select * from tb_cus_teller 
	           where fd_tel_id = '$telid' ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$telqx = $db->f(fd_tel_doprg);    //用户权限
	  	$telname = $db->f(fd_tel_name);    //用户名字
	  	//$userqxtype  = $db->f(fd_usegroup_type);  //权限类型
	  	$userqxtype = $loginorgantype;

	  	//---------------------------------------------
	  	
	  	//用户权限分切
	  	
	  	$arr_telprgqx = explode("±",$telqx);   
	  	for($i=0;$i<count($arr_telprgqx);$i++){
	  		$temp_arr = explode("@",$arr_telprgqx[$i]);
	  		$tmpcode = $temp_arr[0];   //功能代号
	  		$telprgqx_arr[$tmpcode]= 1;   //功能代号

	  		if(!empty($temp_arr[1])){
	  			$str_optqx=explode("^",$temp_arr[1]);    //进一步拆分新增等功能
	  			for($k=0;$k<count($str_optqx);$k++){
	  				$tmpoptqx = $str_optqx[$k];      //新增等权限代号
	  				$teloptqx_arr[$tmpcode][$tmpoptqx]=1;
	  			}
	  		}
	  	}
	  	//---------------------------------
	  }
    $show = showqx($menuarry,$prgqx_arr,$optqx_arr,$telprgqx_arr,$teloptqx_arr) ;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("seltelqx","seltelqx.html"); 

$t->set_var("telid"  ,$telid);
$t->set_var("name",$telname);
$t->set_var("show",$show);
$t->set_var("gotourl",$gotourl);  // 转用的地址

$addqx = $thismenuqx[1];  // 增加
$editqx = $thismenuqx[2];  // 修改
if(($addqx != 1) and ($editqx != 1)){
	$dissave = "disabled" ;
}

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "seltelqx");    # 最后输出页面

function showqx($menuarry , $prgqx_arr,$optqx_arr,$telprgqx_arr,$teloptqx_arr){
	global $loginskin;
	global $userqxtype;
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
    	$optqxtype = $temp_arr2[9]; 	//权限类型
    	
    	$arr_qxtype = explode("^",$optqxtype);
    	if(trim($arr_qxtype[0])==$userqxtype || trim($arr_qxtype[1])==$userqxtype || trim($arr_qxtype[2])==$userqxtype || trim($arr_qxtype[3])==$userqxtype || trim($arr_qxtype[4])==$userqxtype  || trim($arr_qxtype[5])==$userqxtype){  //把符合条件的菜单列出来
        		
        	$arr_optqx = explode("^",$optqx);
        	$arr_optqxcode = explode("^",$optqxcode);
        	
        	$makeempty = makenbsp($level);     //宿行
        	
        	if($isnode ==1){   //如果是节点
        		 $checkbox ="";
	    		 if($level==1){ $k = 0 ;$j = 0;}else{ $j = 0; $k ++ ;}	
	    		 if($optqx==1){
	    		 $style = "style='display:none'";
	    		 }else{
	    		 $style = "";
	    		 }		 		 
	    		 
        		 $show .= "<tr><td height=25 noWrap width='350' class='titlefont'>&nbsp;&nbsp;&nbsp;&nbsp;".$makeempty.$name."<INPUT id='code".$fcode.$k."' onclick=Checkdept(this) type=checkbox ".$style." name=".$code." ></td></tr>";
        		 if($level==1){
        		    $show .= "<tr><td align='left' colspan='2'><div style='margin:0px 0px 0px 12px;'><img src='".$loginskin."shortline.jpg' ></div></td></tr>";
        	   }
        	}else{
        		$j ++ ;
        		 if($prgqx_arr[$code]==1 or $telprgqx_arr[$code]==1){  //判断该用户所属的组是否已经已有该权限
        		 	   $prg_check ="checked";
        		 	   if($prgqx_arr[$code]==1){
        		 	      $prg_disabled ="disabled";
        		 	   }else{
        		 	   	  $prg_disabled ="";
        		 	   }
        		 }else{
        		 	   $prg_check ="";
        		 	   $prg_disabled ="";
        		 }
        		 if($fcode < 10 ){$fcode = $fcode ."0";} 
        	   $checkbox ="<input id='code".$fcode.$j."' type='checkbox' name='prgqx[]' value='$code' ".$prg_check."  ".$prg_disabled." >";
        	   $show .= "<tr><td height=25 noWrap width='350' >".$makeempty.$checkbox.$name."</td>";
        	   
        	   $show .= "<td height=25 >";
        	    
        	   $optqxbox = "";
        	   $optqx_count=count($arr_optqx);   //新增、修改、删除权限数组
        	   for($optqx_i=0;$optqx_i< $optqx_count;$optqx_i++){
        	   	  $optcode = $arr_optqxcode[$optqx_i];
      
        	   	  $optcode = intval($optcode);
        	   	  if($optqx_arr[$code][$optcode]==1 or $teloptqx_arr[$code][$optcode]==1){  //判断该用户所属的组是否已经已有该权限
        	   	  	  $opt_check ="checked";
        	   	  	  if($optqx_arr[$code][$optcode]==1){
        	   	  	     $opt_disabled ="disabled";
        	   	  	  }else{
        	   	  	  	 $opt_disabled ="";
        	   	  	  }
        		    }else{
        		    	  $opt_check ="";
        		    	  $opt_disabled ="";
        		    }
                if(!empty($arr_optqx[$optqx_i])){
	    		$arr_optqxcode[$optqx_i] = str_replace(array("\r","\n"), array('',''), $arr_optqxcode[$optqx_i]);
        	   	      $optqxbox .="<input id='opt".$fcode.$j.$arr_optqxcode[$optqx_i]."' type='checkbox' name='optqx[$code][]' value=".$arr_optqxcode[$optqx_i]." onclick=checkcode('".$fcode.$j."') ".$opt_check." ".$opt_disabled." >";
        	   	      $optqxbox .="&nbsp;".$arr_optqx[$optqx_i]."&nbsp;&nbsp;";
        	   	  }
        	   }
             $show .= $optqxbox. "</td></tr>";
        	  
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