<?php
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("sale_listprint","sale_listprint.html");
$t->set_block("sale_listprint", "BXBK", "bxbks");

$year  = date( "Y", mktime()) ;
$month = date( "m", mktime()) ;
$day   = date( "d", mktime()) ;


$query = "select * from tb_salelist where fd_selt_id  = '$listid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$listno = $db->f(fd_selt_no);
	$suppname = $db->f(fd_selt_cusname);
}

$count=0;
$memo ="";
$query = "select * from tb_salelistdetail  
         left join tb_produre on fd_produre_id = fd_stdetail_commid 
         left join tb_storage on fd_storage_id = fd_stdetail_storageid 
         left join tb_guige on fd_guige_id = fd_produre_spec
         where fd_stdetail_seltid = '$listid' order by fd_stdetail_commbar ";
$db->query($query);  
$rowcount = $db->nf();
if($rowcount<17){  //判断行数，如果小于20行默认20行
	$rowcount = 17;
}

if($db->nf()){
	while($db->next_record()){
		$commbar     = $db->f(fd_stdetail_commbar);
		$commname    = $db->f(fd_stdetail_commname);
		$guige        = $db->f(fd_guige_name);
		$unit        = $db->f(fd_stdetail_unit);
		$quantity    = $db->f(fd_stdetail_quantity);
		$relation1   = $db->f(fd_produre_relation1);
		$relation2   = $db->f(fd_produre_relation2);
		$relation3   = $db->f(fd_produre_relation3);
		$xsstoragename= $db->f(fd_storage_name);
		
		if($count==0){
		  $memo = "<td rowspan='".$rowcount."' class=listcellrow>&nbsp;";
	  }else{
	    $memo = "";
   	}
		
		$dunshu = changekg($relation3 , $unit , $quantity);
		$jiangshu = changejian($relation1 , $relation2 , $relation3 , $unit , $quantity);
		$lingshu = changeling($relation1 , $relation2 , $relation3 , $unit , $quantity);
		
		if ($bgcolor=="#FFFFFF") {
        $bgcolor="#F1F4F9";
    }else{
        $bgcolor="#FFFFFF";
    }
    
    $alldunshu += $dunshu;
    $alljiangshu += $jiangshu;
    $alllingshu += $lingshu;
    
    $jiangshu = number_format($jiangshu, 2, ".", ",");
     $jiangshu = "";
    $dunshu = number_format($dunshu, 4, ".", ",");
    $lingshu = number_format($lingshu, 3, ".", ",");
    
    $count++;
		$t->set_var(array("count"	       => $count    ,
		                  "bgcolor"      => $bgcolor  ,
				              "commbar"      => $commbar  ,
				              "commname"     => $commname ,
				              "guige"        => $guige    ,
				              "lingshu"      => $lingshu  ,
				              "jiangshu"     => $jiangshu ,
				              "xsstoragename"=> $xsstoragename,
				              "memo"         => $memo     ,
				              "dunshu"       => $dunshu 
				   ));
		$t->parse("bxbks", "BXBK", true);
	}
}
if($count<17){
	$tmpcount = 17-$count;
	if($count==0){
		$memo = "<td rowspan='".$rowcount."' class=listcellrow>&nbsp;";
	}else{
	  $memo = "";
  }
	for($i=0;$i<$tmpcount;$i++){
		$count++;
		if ($bgcolor=="#FFFFFF") {
        $bgcolor="#F1F4F9";
    }else{
        $bgcolor="#FFFFFF";
    }
    $t->set_var(array("count"	       => $count ,
				              "commbar"      => "" ,
				              "bgcolor"      => $bgcolor ,
				              "commname"     => "" ,
				              "guige"        => "" ,
				              "lingshu"      => "" ,
				              "jiangshu"     => "" ,
				              "xsstoragename"=> "" ,
				              "memo"         => $memo,
				              "dunshu"       => "" 
				   ));
		$t->parse("bxbks", "BXBK", true);
	}
}

$printtime = date("Y年m月d日 H时i分",mktime(date("H"), date("i"), 0, date("m")  , date("d"), date("Y")));

$alldunshu = number_format($alldunshu, 4, ".", ",");
$alljiangshu = number_format($alljiangshu, 2, ".", ",");
 $alljiangshu = "";

$alllingshu = number_format($alllingshu, 3, ".", ",");

$t->set_var("alldunshu",$alldunshu);
$t->set_var("alljiangshu",$alljiangshu);
$t->set_var("alllingshu",$alllingshu);

$t->set_var("staname",$loginstaname);
$t->set_var("suppname",$suppname);
$t->set_var("shorganname",$loginorganname);
$t->set_var("listno",$listno);
$t->set_var("printtime",$printtime);
$t->set_var("count",$count);
$t->set_var("skin",$loginskin);
$t->pparse("out", "sale_listprint"); //   # 最后输出页面

?> 