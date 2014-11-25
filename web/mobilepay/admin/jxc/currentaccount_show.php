<?php
$thismenucode = "sys";
require ("../include/common.inc.php");
$db = new DB_test ;

$showtime = $begindate."---".$enddate ;

switch ($backurl) {
    case 'yf':
        $gotourl = $backurl."money_show.php";
        break;
    case 'ys':
        $gotourl = $backurl."money_show.php";
        break;
    case 'date':
        $gotourl = "datetable_view.php?begindate=".$begindate."&enddate=".$enddate;
        $display = 'none';
        break;
    case 'month':
        $arr_tmpbegindate = explode("-",$begindate);
        $gotourl = "monthtable_view.php?year=".$arr_tmpbegindate[0]."&month=".$arr_tmpbegindate[1];
        $display = 'none';
        break;
    case 'year':
        $arr_tmpbegindate = explode("/",$begindate);
        $gotourl = "yeartable_view.php?begindate=".$begindate."&enddate=".$enddate."&year=".$arr_tmpbegindate[0];
        $display = 'none';
        break;
    default:
        break;
}

require("../include/alledit.1.php");
$t = new Template('.', "keep");
$t->set_file("currentaccount_show","currentaccount_show.html");
$t->set_block("currentaccount_show", "BXBK", "bxbks");

if ( $datetype == 0 )
{
    $datefiled = " fd_ctat_listdate ";
}
else
{
    $datefiled = " fd_ctat_date ";
}

if ( !empty($clienttype) )
{
    $clienttypefiled = "fd_ctat_linktype = '$clienttype' and ";
}

if ( !empty($cusid) )
{
    $cusidfiled = "fd_ctat_linkid = '$cusid' and ";
}

$query = "select * from tb_currentaccount where ".$clienttypefiled." ".$cusidfiled." ".$datefiled." <'$begindate' order by fd_ctat_datetime asc";
$db->query($query);
if( $db->nf() )
{
    while( $db->next_record() )
    {
        $addmoney = $db->f(fd_ctat_addmoney)+0;
        $lessen   = $db->f(fd_ctat_lessen)+0;
        $ctatbalance += $addmoney-$lessen;
    }
}
$vbalance = $ctatbalance;  //余额

if($isshowkickback!=1)
{
    $sqlwhere = " and fd_ctat_iskickback = 0";
}

$query = "select * from tb_currentaccount where ".$clienttypefiled." ".$cusidfiled." ".$datefiled." >='$begindate' and ".$datefiled." <='$enddate' ".$sqlwhere." order by fd_ctat_datetime asc";

$db->query($query);
$count=0;
if($db->nf())
{
    while( $db->next_record() )
    {
        $vdate     = $db->f(fd_ctat_date);
        $vlistid   = $db->f(fd_ctat_listid);
        $vlistno   = $db->f(fd_ctat_listno);
        $vlisttype = $db->f(fd_ctat_listtype);
        $vlistdate = $db->f(fd_ctat_listdate);
        $vaddmoney = $db->f(fd_ctat_addmoney)+0;
        $vlessen   = $db->f(fd_ctat_lessen)+0;
        //$vbalance  = $db->f(fd_ctat_balance)+0;
        $vmemo     = $db->f(fd_ctat_memo);
        $vmakename = $db->f(fd_ctat_makename);
        $iskickback = $db->f(fd_ctat_iskickback);

        $vbalance = $vbalance+$vaddmoney-$vlessen;

        $count++ ;

        $alladdmoney += $vaddmoney;
        $alllessen += $vlessen;

        if(!empty($vaddmoney)){
        $vaddmoney = number_format($vaddmoney, 2, ".", "");
    }
    
    if( !empty($vlessen) )
    {
        $vlessen = number_format($vlessen, 2, ".", "");
    }
    
    if( !empty($vbalance) )
    {
        $vformatbalance = number_format($vbalance, 2, ".", "");
    }
    else
    {
      $vformatbalance = $vbalance;
    }

    if( $s == 1 )
    {
        $bgcolor = "#F1F4F9";
        $s = 0;
    }
    else
    {
        $bgcolor = "#ffffff";
        $s = 1;
    }
    
    if( $iskickback == 0 )
    {
        $tr_color = "#000000";
    }
    else
    {
        $tr_color = "#ff0000";
    }

    switch( $vlisttype )
    {
        case "0":
            $vlisttype = "期初";
            $viewname = "<span  style='cursor:hand'></span>";
            break;
        case "1":
            $vlisttype = "对外进货单";
            $viewname = "<span onclick=\"chaxun('../jxc/stockview.php','listid',".$vlistid.",this)\"  style='cursor:hand'>查看详细</span>";
            break;
        case "2":
            $vlisttype = "对外退货单";
            $viewname = "<span onclick=\"chaxun('../jxc/stockbackview.php','listid',".$vlistid.",this)\" style='cursor:hand'>查看详细</span>";
            break;
        case "3":
            $vlisttype = "对外销售单";
            $viewname = "<span onclick=\"chaxun('../jxc/salelistview.php','listid',".$vlistid.",this)\" style='cursor:hand'>查看详细</span>";
            break;
        case "4":
            $vlisttype = "对外销售退货单";
            $viewname = "<span onclick=\"chaxun('../jxc/salebacklistview.php','listid',".$vlistid.",this)\" style='cursor:hand'>查看详细</span>";
            break;
        case "8":
            $vlisttype = "对外收款单";
            $viewname = "<span onclick=\"chaxun('../jxc/inmoneylist_view.php','listid',".$vlistid.")\" style='cursor:hand'>查看详细</span>";
            break;
    }

    $t->set_var(array(
        "vdate"        => $vdate           ,
        "vlistno"      => $vlistno         ,
        "vlisttype"    => $vlisttype       ,
        "vaddmoney"    => $vaddmoney       ,
        "vlessen"      => $vlessen         ,
        "vbalance"     => $vformatbalance  ,
        "bgcolor"      => $bgcolor         ,
        "vlistdate"    => $vlistdate       ,
        "vmakename"    => $vmakename       ,
        "viewname"     => $viewname        ,
        "tr_color"     => $tr_color        ,
        "vmemo"        => $vmemo
    ));
        $t->parse("bxbks", "BXBK", true);
    }

}
else
{
    $t->set_var(array(
        "vdate"        => ""            ,
        "vlistno"      => ""            ,
        "vlisttype"    => ""            ,
        "vaddmoney"    => ""            ,
        "vlessen"      => ""            ,
        "vbalance"     => ""            ,
        "vlistdate"    => ""            ,
        "vmakename"    => ""            ,
        "bgcolor"      => "#ffffff"     ,
        "viewname"     => ""            ,
        "tr_color"     => "#000000"     ,
        "vmemo"        => ""
    ));
    $t->parse( "bxbks" , "BXBK" , true );
}

$nowysmoney = "0.00";
$nowyfmoney = "0.00";

$query = "select fd_ysyfm_money from tb_ysyfmoney where fd_ysyfm_companyid = '$cusid' and fd_ysyfm_type = '$clienttype'";
$db->query($query);

if($db->nf()){
	$db->next_record();
	$ysysmoney = $db->f(fd_ysyfm_money);
}

if($ysysmoney>0)
{
    $nowysmoney = $ysysmoney;
}
else
{
    $nowyfmoney = -$ysysmoney;
}

if($clienttype==1)
{
    $clienttype ="客户";
    $query = "select * from tb_customer where fd_cus_id = '$cusid'";
    $db->query($query);
    if( $db->nf() )
    {
        $db->next_record();
        $cusname = $db->f(fd_cus_name);
    }
}
else
{
    $clienttype ="供应商";
    $query = "select * from tb_supplier where fd_supp_id = '$cusid'";
    $db->query($query);
    if( $db->nf() )
    {
        $db->next_record();
        $cusname = $db->f(fd_supp_name);
    }
}

$nowysmoney = number_format($nowysmoney, 2, ".", "");
$nowyfmoney = number_format($nowyfmoney, 2, ".", "");
$ctatbalance = number_format($ctatbalance, 2, ".", "");
$alladdmoney = number_format($alladdmoney, 2, ".", "");
$alllessen = number_format($alllessen, 2, ".", "");

$t->set_var("showtime",$showtime);
$t->set_var("cusname",$cusname);
$t->set_var("clienttype",$clienttype);
$t->set_var("ctatbalance",$ctatbalance);
$t->set_var("alladdmoney",$alladdmoney);
$t->set_var("alllessen",$alllessen);
$t->set_var("nowysmoney",$nowysmoney);
$t->set_var("nowyfmoney",$nowyfmoney);
$t->set_var("gotourl",$gotourl);
$t->set_var("display",$display);
$t->set_var("count",$count);
$t->set_var("error",$error);
$t->set_var("skin",$loginskin);
$t->pparse("out", "currentaccount_show");    # 最后输出页面
?>