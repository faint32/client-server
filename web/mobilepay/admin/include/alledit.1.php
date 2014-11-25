<?
$selecteditarry = explode("&",$_SERVER['QUERY_STRING']);
$selectedittemp =explode("=",$selecteditarry[count($selecteditarry)- 2]);
$selectedittempid= $selectedittemp[0]; 
if(isset($selecteditvalue)){
	$showeditnexthidden = "";
  if(count($selecteditvalue) > 0 ){		
  	session_register("shownoweditcount");
  	if($shownoweditcount == ""){ $shownoweditcount = 1;}elseif($shownoweditcount > count($selecteditvalue)){$shownoweditcount = count($selecteditvalue);}
	  $$selectedittempid = $selecteditvalue[0];		
  }
}else{
	
  $showeditnexthidden = "display:none";
}



if($action == "nextedit"){
$action = "";

if($shownoweditcount < count($selecteditvalue)){
$shownoweditcount ++ ;
}else{
$shownoweditcount = 1;
}
if(isset($selecteditvalue)){	       
	       $tempselvalue =  array_shift($selecteditvalue);               
	       array_push($selecteditvalue,$tempselvalue); 
	      
	      	if(count($selecteditvalue) == 0 ){
	        $gotourl2 = $gotourl;
	        }else{
  	      $gotourl2 = $PHP_SELF."?".$_SERVER['QUERY_STRING'] ;
          }	 
		  echo $gotourl2; 
	      Header("Location: $gotourl2");
 } 
}

if($action == "prvedit"){
$action = "";
if($shownoweditcount > 1){
$shownoweditcount -- ;
}else{
$shownoweditcount = count($selecteditvalue);
}
if(isset($selecteditvalue)){
	             $tempselvalue = array_pop($selecteditvalue); 	
	           array_unshift($selecteditvalue,$tempselvalue);
	      
	      	if(count($selecteditvalue) == 0 ){
	        $gotourl2 = $gotourl;
	        }else{
  	      $gotourl2 = $PHP_SELF."?".$_SERVER['QUERY_STRING'] ;
          }	  
	      Header("Location: $gotourl2");
 } 
}

   $showeditdiv = "<div style='background-color:#FFFFFF;border:1px solid #666666;filter:Alpha(Opacity=60);POSITION: absolute; TOP:5px;right:28px;font-size:13px;".$showeditnexthidden.";' >
   <span style='width:80px;height:20px; color:blue;font-weight:bold;' valign=middle>".$shownoweditcount."/".count($selecteditvalue)."条<span><img src='../include/images/left_allow.gif' width='22' height='24' onclick='prvedit()' style='cursor:hand' title='点击为上一条记录'><img src='../include/images/right_allow.gif' width='22' height='24' onclick='nextedit()' style='cursor:hand' title='点击为下一条记录'></div>";


?>