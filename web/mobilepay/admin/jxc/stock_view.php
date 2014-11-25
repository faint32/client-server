<?
$thismenucode = "1206";
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;

$t = new Template('.', "keep");
$t->set_file("stock_view","stock_view.html");
$t->set_block("stock_view", "BXBK", "bxbks");

$count=0;
$allquantity=0;
$allmoney=0;
if($isstock==1){  //进货单
  
  if($iskickback==1){
  	$kickback_where = "  ";
  }else{
    $kickback_where = " and fd_stock_iskickback = 0  ";
  }
  $query = "select fd_stock_allmoney , fd_stock_no , fd_stock_id , fd_stock_date ,
            fd_stock_suppname , fd_stock_alldunquantity  from tb_stock  
            where fd_stock_date >= '$begindate' and fd_stock_date <= '$enddate' 
            and fd_stock_state =2 ".$kickback_where." 
            and fd_stock_suppid = '$suppid' order by fd_stock_date desc" ;
  $db->query($query);
  if($db->nf()){
  	while($db->next_record()){
  		$money       = $db->f(fd_stock_allmoney)+0;
  		$orangid     = $db->f(fd_stock_organid);
  		$listno      = $db->f(fd_stock_no);
  		$listid      = $db->f(fd_stock_id);
  		$datetime    = $db->f(fd_stock_date);
  		$suppname    = $db->f(fd_stock_suppname);
  		$dunshu      = $db->f(fd_stock_alldunquantity)+0;
  		
  		$memo="从【".$suppname."】进货。 ";
      
      $alldunshu +=$dunshu ;
      $allmoney +=$money ;
      
      $dunshu = number_format($dunshu, 4, ".", ",");
      $money = number_format($money, 2, ".", ",");
      
      $viewname = "<span onclick=\"chaxun('../stock/stockview.php','listid',".$listid.")\">查看详细</span>";
      $count++;
      if ($bgcolor=="#FFFFFF") {
          $bgcolor="#F1F4F9";
      }else{
          $bgcolor="#FFFFFF";
      }
		  $t->set_var(array("bgcolor"      => $bgcolor     ,
		  		              "viewname"     => $viewname    ,
		  		              "listno"       => $listno      ,
		  		              "datetime"     => $datetime    ,
		  		              "dunshu"       => $dunshu      ,
		  		              "tdcount"      => $count       ,
		  		              "memo"         => $memo        ,
		  		              "money"        => $money
		  		   ));
		  $t->parse("bxbks", "BXBK", true);
		  
		  
  	}
  }
}

if($isbackstock==1){  //退货单

  if($iskickback==1){
  	$kickback_where = "  ";
  }else{
    $kickback_where = " and fd_stockback_iskickback = 0 ";
  }
  $query = "select fd_stockback_allmoney , fd_stockback_alldunshu , fd_stockback_no ,
            fd_stockback_id , fd_stockback_date , fd_stockback_suppname from tb_stockback  
            where fd_stockback_date >= '$begindate' and fd_stockback_date <= '$enddate' 
            and fd_stockback_state =1 ".$kickback_where."
            and fd_stockback_suppid = '$suppid' order by fd_stockback_date desc" ;
  $db->query($query);
  if($db->nf()){
  	while($db->next_record()){
  		$money       = -$db->f(fd_stockback_allmoney)+0;
  		$orangid     = $db->f(fd_stockback_organid);
  		$dunshu      = -$db->f(fd_stockback_alldunshu)+0;
  		$listno      = $db->f(fd_stockback_no);
  		$listid      = $db->f(fd_stockback_id);
  		$datetime    = $db->f(fd_stockback_date);
  		$suppname    = $db->f(fd_stockback_suppname);
  		
  		$memo="从【".$suppname."】退货。 ";
  		$allpaymoney =$paymoney ;
      $allmoney =$money ;
      
      $dunshu = number_format($dunshu, 4, ".", ",");
      $money = number_format($money, 2, ".", ",");
      
  		$count++;
  		$viewname = "<span onclick=\"chaxun('../stock/stockbackview.php','listid',".$listid.")\">查看详细</span>";
  		
      if ($bgcolor=="#FFFFFF") {
          $bgcolor="#F1F4F9";
      }else{
          $bgcolor="#FFFFFF";
      }
		  $t->set_var(array("bgcolor"      => $bgcolor     ,
		  		              "viewname"     => $viewname    ,
		  		              "listno"       => $listno      ,
		  		              "datetime"     => $datetime    ,
		  		              "tdcount"      => $count       ,
		  		              "dunshu"       => $dunshu      ,
		  		              "memo"         => $memo        ,
		  		              "money"        => $money
		  		   ));
		  $t->parse("bxbks", "BXBK", true);
		  
  	}
  }
}

if($count==0){
    $t->set_var(array("bgcolor"      => "" ,
				              "viewname"     => "" ,
				              "dunshu"       => "" ,
				              "listno"       => "" ,
				              "datetime"     => "" ,
				              "tdcount"      => "" ,
				              "memo"         => "" ,
				              "money"        => ""
				   ));
		$t->parse("bxbks", "BXBK", true);
}


$alldunshu = number_format($alldunshu, 4, ".", ",");
$allmoney = number_format($allmoney, 2, ".", ",");

$t->set_var("alldunshu",$alldunshu);
$t->set_var("allmoney",$allmoney);
$t->set_var("count",$count);
$t->set_var("begindate",$begindate);
$t->set_var("enddate",$enddate);

$t->set_var("error",$error);

$t->set_var("skin",$loginskin);
$t->pparse("out", "stock_view");    # 最后输出页面

?>