<?
$thismenucode = "sys";
require ("../include/common.inc.php");
$db = new DB_test ;

$showtime = $begindate."---".$enddate ;
$gourl = "storageview.php";
$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
//$gotourl = "commglide_query.php";

//echo $begindate;
$t = new Template('.', "keep");
$t->set_file("commglide_show","commglide_show.html");
$t->set_block("commglide_show", "BXBK", "bxbks");
if(!empty($commid))
{
	$querywhere .= " and fd_coge_commid = '$commid' ";
}

$cogebalance = 0;
$query = "select * from tb_commglide where  fd_coge_date<'$begindate'  
		  and fd_coge_organid = '$loginorganid'  $querywhere
           order by fd_coge_datetime asc";  
$db->query($query);
if($db->nf()){
	while($db->next_record()){
    $addquantity = $db->f(fd_coge_addquantity)+0;
    $lessen   = $db->f(fd_coge_lessen)+0;
    
    $cogebalance += $addquantity - $lessen ;
  }
}
$vbalance = $cogebalance;  //余额

if($isshowkickback!=1){
	$sqlwhere = " and fd_coge_iskickback = 0";
}

$query = "select * from tb_commglide where  1 
		 
		  ".$sqlwhere." $querywhere order by fd_coge_datetime asc";  
$db->query($query);
$count=0;
if($db->nf()){
	while($db->next_record()){
    $vdate     = $db->f(fd_coge_date);	   	   
    $vlistid   = $db->f(fd_coge_listid);
    $vlistno   = $db->f(fd_coge_listno);
    $vlisttype = $db->f(fd_coge_listtype);
    $vlistdate = $db->f(fd_coge_listdate);
    $vaddquantity = $db->f(fd_coge_addquantity)+0;
    $vlessen   = $db->f(fd_coge_lessen)+0;
    $vmemo     = $db->f(fd_coge_memo);
    $vmakename = $db->f(fd_coge_makename);
    $iskickback = $db->f(fd_coge_iskickback);
    
    $vbalance = $vbalance+$vaddquantity-$vlessen;
    
    $tmp_quantity = $vaddquantity-$vlessen;
    
   // $vmemo = $vmemo.abs($vaddquantity-$vlessen);
    
		$alladdnum += $vaddquantity;
		$alllessen += $vlessen;
		$allbalance = +$vbalance;
		
    if(!empty($vaddquantity)){
    	$vaddquantity = number_format($vaddquantity, 6, ".", ",")+0;
    }
    
    if(!empty($vlessen)){
    	$vlessen = number_format($vlessen, 6, ".", ",")+0;
    }
    
    if(!empty($vbalance)){
    	$vformatbalance = number_format($vbalance, 6, ".", ",")+0;
    }else{
      $vformatbalance = $vbalance;
    }

		$count++ ;
		
		if($bgcolor=="#ffffff"){            
      $bgcolor="#F1F4F9";            
    }else{                
      $bgcolor="#ffffff";                  
    }  
    
    if($iskickback==0){
    	$tr_color = "#000000";
    }else{
      $tr_color = "#ff0000";
    }
    
    
    switch($vlisttype){
    	case "1":
    	  $vlisttype = "对外进货单";
    	  $viewname = "<a onclick=\"chaxun('../jxc/stockview.php','listid',".$vlistid.")\" style='cursor:hand'>查看详细</a>";
		  $vlistnourl="<a onclick=\"chaxun('../jxc/stockview.php','listid',".$vlistid.")\" style='cursor:hand'>".$vlistno."</a>";
    	  break;
    	case "2":
    	  $vlisttype = "对外退货单";
    	  $viewname = "<a onclick=\"chaxun('../jxc/stockbackview.php','listid',".$vlistid.")\" style='cursor:hand'>查看详细</a>";
		  $vlistnourl="<a onclick=\"chaxun('../jxc/stockbackview.php','listid',".$vlistid.")\" style='cursor:hand'>".$vlistno."</a>";
    	  break;
    	case "3":
    	  $vlisttype = "对外销售单";
    	  $viewname = "<a onclick=\"chaxun('../jxc/salelistview.php','listid',".$vlistid.")\" style='cursor:hand'>查看详细</a>";
		  $vlistnourl = "<a onclick=\"chaxun('../jxc/salelistview.php','listid',".$vlistid.")\" style='cursor:hand'>".$vlistno."</a>";
    	  break;
    	case "4":
    	  $vlisttype = "对外销售退货单";
    	  $viewname = "<a onclick=\"chaxun('../jxc/salebacklistview.php','listid',".$vlistid.")\" style='cursor:hand'>查看详细</a>";
		  $vlistnourl = "<a onclick=\"chaxun('../jxc/salebacklistview.php','listid',".$vlistid.")\" style='cursor:hand'>".$vlistno."</a>";
    	  break;
      case "0":
    	  $vlisttype = "期初开帐";
    	  break;

    }
		$t->set_var(array("vdate"	       => $vdate     ,
				              "vlistno"	     => $vlistno   ,
				              "vlisttype"    => $vlisttype ,
				              "vaddquantity" => $vaddquantity ,
				              "vlessen"      => $vlessen   ,
				              "vbalance"     => $vformatbalance  ,
				              "bgcolor"      => $bgcolor   ,
				              "vmakename"    => $vmakename ,
							  "vlistnourl"    => $vlistnourl ,
				              "vlistdate"    => $vlistdate ,
				              "viewname"     => $viewname  ,
				              "tr_color"     => $tr_color  ,
				              "vmemo"        => $vmemo
				   ));
		$t->parse("bxbks", "BXBK", true);
	 }
	 	
}else{
	  $t->set_var(array("vdate"	       => ""  ,
				              "vlistno"	     => ""  ,
				              "vlisttype"    => ""  ,
				              "vaddquantity" => ""  ,
				              "vlessen"      => ""  ,
				              "vbalance"     => ""  ,
				              "vlistdate"    => ""  ,
				              "vmakename"    => ""  ,
							  "vlistnourl"    => ""  ,
				              "bgcolor"      => "#ffffff"     ,
				              "tr_color"     => "#000000"     ,
				              "viewname"     => ""  ,
				              "vmemo"        => ""
				   ));
		$t->parse("bxbks", "BXBK", true);
	
}




$query = "select * from tb_paycardstockquantity where  fd_skqy_commid = '$commid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$nowquantity = $db->f(fd_skqy_quantity);
}

$query = "select * from tb_product where  fd_product_id = '$commid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$commname = $db->f(fd_product_name);
	$commbar  = $db->f(fd_product_no);
}


$nowquantity = number_format($nowquantity, 6, ".", ",");

$allbalance = number_format($allbalance, 2, ".", "")+0;
$alladdnum = number_format($alladdnum, 4, ".", "")+0;
$alllessen = number_format($alllessen, 4, ".", "")+0;


$t->set_var("allbalance",$allbalance);
$t->set_var("alladdnum",$alladdnum);
$t->set_var("alllessen",$alllessen);

$t->set_var("showtime",$showtime);
$t->set_var("commname",$commname);
$t->set_var("storagename",$storagename);
$t->set_var("commbar",$commbar);
$t->set_var("cogebalance",$cogebalance);

$t->set_var("nowquantity",$nowquantity);

$t->set_var("gotourl",$gotourl);

$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("skin",$loginskin);
$t->pparse("out", "commglide_show");    # 最后输出页面

?>