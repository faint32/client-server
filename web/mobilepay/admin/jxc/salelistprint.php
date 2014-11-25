<?php
require ("../include/common.inc.php");
require ("../include/log.php");
// require ("../function/changestockquantity.php");
$db  = new DB_test;
$db2 = new DB_test;

$t = new Template( "." , "keep" );          //调用一个模版

$gourl = "tb_salelistview_b.php";
$gotourl = $gourl.$tempurl;

$query = "SELECT * FROM tb_salelist WHERE fd_selt_id = '$listid'";
$db->query($query);
$db->next_record();                          //读取记录数据
$id         = $db->f(fd_selt_id);            //id号
$no         = $db->f(fd_selt_no);            //单据编号
$cusid      = $db->f(fd_selt_cusid);         //客户id
$cusno      = $db->f(fd_selt_cusno);         //客户编号
$cusname    = $db->f(fd_selt_cusname);       //客户名称
$now        = $db->f(fd_selt_date);          //录单日期
$dept       = $db->f(fd_dept_name);          //部门
$staid      = $db->f(fd_selt_staid);         //录单人ID
$storage    = $db->f(fd_storage_name);       //仓库
$memo       = $db->f(fd_selt_memo);          //备注
$state      = $db->f(fd_selt_state);         //状态
$dealwithman= $db->f(fd_selt_dealwithman);   //经手人ID

$temp_arr   = explode("-",$now);
$year       = $temp_arr[0];
$month      = $temp_arr[1];
$day        = $temp_arr[2];


$t->set_file( "salelistview" , "salelistprint.html" );


if ($iskickback != 0 or $loginopendate > $now)
{
	$dissave = "disabled";
}
else
{
	$dissave = "";
}

//显示商品列表


$query = "SELECT * FROM tb_salelistdetail WHERE fd_stdetail_seltid = '$listid'";
$db->query($query);

if($db->nf())
{
	$db->next_record();
	$paycardid = $db->f(fd_stdetail_paycardid);

	$arr_paycardid1 = explode( "," , $paycardid );
	foreach ($arr_paycardid1 as $va)
	{
		if ($strpaycardid)
		{
			$strpaycardid .= ","."'$va'";
		}
		else
		{
			$strpaycardid = "'$va'";
		}
	}
}

if ( $strpaycardid )
{
	$query = "SELECT fd_paycard_batches , fd_paycard_key , fd_product_suppname FROM tb_paycard LEFT JOIN tb_product ON fd_paycard_product = fd_product_id WHERE fd_paycard_id IN ($strpaycardid)";

	$db->query($query);
	$rows2 = $db->num_rows();
	$t->set_block( "salelistview" , "prolist" , "prolists" );
	if( $db->nf() )
	{
		while( $db->next_record() )
		{
			$lprobatches        = $db->f(fd_paycard_batches);         //批次号
			$lprokey            = $db->f(fd_paycard_key);             //刷卡器设备号
			$lprosuppname       = $db->f(fd_product_suppname);        //供应商
			$count++;
			$trid = "tr".$count;
			$imgid = "img".$count;
			if($s == 1)
			{
				$bgcolo = "#F1F4F9";
				$s = 0;
			}
			else
			{
				$bgcolor = "#ffffff";
				$s = 1;
			}
			$t->set_var(array(
				"lprobatches"       => $lprobatches       ,
				"lprokey"           => $lprokey           ,
				"lprosuppname"      => $lprosuppname      ,
			));
			$t->parse("prolists" , "prolist" , true);
		}
	}
	else
	{
		$t->set_var(array(
				"lprobatches"       => ""       ,
				"lprokey"           => ""       ,
				"lprosuppname"      => ""       ,
		));
		$t->parse("prolists" , "prolist" , true);
	}

	$i=0;
	if(6-$rows2>0)
	{
		while($i<(6-$rows2))
		{
			$t->set_var(array(
			"lprobatches"       => ""       ,
			"lprokey"           => ""       ,
			"lprosuppname"      => ""       ,
			));
			$t->parse("prolists", "prolist", true);
			$i++;
		}
	}

	$query = "select * from tb_product";
	$db->query($query);
	if ($db->nf()) {
		while ($db->next_record()) {
			$arr_product[$db->f(fd_product_id)] = $db->f(fd_product_name);
		}
	}


	$query = "SELECT * FROM tb_salelistdetail WHERE fd_stdetail_seltid = '$listid'";
	$db->query($query);

	$count=0;//商品记录数
	$lprosumnum=0;//商品总数量
	$lprosumprice=0;//商品总价
	$rows1 = $db->num_rows();
	$t->set_block("salelistview" , "BXBK" , "BXBKS");
	if($db->nf())
	{
		while($db->next_record())
		{
			$lprono       = $db->f(fd_stdetail_id);           //商品编号
			$lproname     = $db->f(fd_stdetail_seltid);       //单据ID
			$lprobar      = $db->f(fd_stdetail_paycardid);    //刷卡器设备id
			$lprice       = $db->f(fd_stdetail_price);        //商品单价
			$lquantity    = $db->f(fd_stdetail_quantity);     //商品数量
			$lpromemo     = $db->f(fd_stdetail_memo);         //商品备注
			$vproductid   = $db->f(fd_stdetail_productid);
			$lprosum      = $lquantity * $lprice;               //商品总价
			$lprosumnum   = $lquantity + $lprosumnum;           //统计商品总数量
			$lprosumprice = $lprosum + $lprosumprice;           //统计商品总价
			$vproductid = $arr_product[$vproductid];
			$count++;
			$trid  = "tr".$count;
			$imgid = "img".$count;

			if($s==1)
			{
				$bgcolor = "#F1F4F9";
				$s = 0;
			}
			else
			{
				$bgcolor = "#ffffff";
				$s = 1;
			}

			$t->set_var(array(
					"lprono"       => $lprono           ,
					"lproname"     => $lproname         ,
					"lprobar"      => $lprobar          ,
					"lprice"       => $lprice           ,
					"lquantity"    => $lquantity        ,
					"lpromemo"     => $lpromemo         ,
					"lprosum"      => $lprosum          ,
					"trid"         => $trid             ,
					"imgid"        => $imgid            ,
					"count"        => $count            ,
					"lprosumnum"   => $lprosumnum       ,
					"lprosumprice" => $lprosumprice     ,
					"bgcolor"      => $bgcolor          ,
					"vproductid"   => $vproductid       ,
					"datashow"     => ""                ,
			));
			$t->parse("BXBKS" , "BXBK" , true);
		}
	}
	else
	{
		$t->set_var(array(
		"lprono"       => ""           ,
		"lproname"     => ""           ,
		"lprobar"      => ""           ,
		"lprice"       => ""           ,
		"lquantity"    => ""           ,
		"lpromemo"     => ""           ,
		"lprosum"      => ""           ,
		"trid"         => "0"          ,
		"imgid"        => ""           ,
		"count"        => ""           ,
		"lprosumnum"   => ""           ,
		"lprosumprice" => ""           ,
		"vproductid"   => ""           ,
		"datashow"     => "none"       ,
		));
		$t->parse("BXBKS" , "BXBK", true);
	}
}

unset($i);
$i = 0;
if((6 - $rows1) > 0)
{
	while($i < (6-$rows1))
	{
		$t->set_var(array(
		"lprobatches"  => ""           ,
		"lprokey"      => ""           ,
		"lprosuppname" => ""           ,
		"lproid"       => ""           ,
		"lstdetailid"  => ""           ,
		"lprono"       => ""           ,
		"lproname"     => ""           ,
		"lprobar"      => ""           ,
		"lprice"       => ""           ,
		"lunit"        => ""           ,
		"lquantity"    => ""           ,
		"lpromemo"     => ""           ,
		"lprosum"      => ""           ,
		"trid"         => "0"          ,
		"imgid"        => ""           ,
		"count"        => ""           ,
		"datashow"     => "none"       ,
		"vproductid"   => ""           ,
		));
		$t->parse("BXBKS", "BXBK", true);
		$i++;
	}
}


$t->set_var("cusid"        , $cusid        )          ;      //客户ID
$t->set_var("cusno"        , $cusno        )          ;      //客户编号
$t->set_var("cusname"      , $cusname      )          ;      //客户名称
$t->set_var("staid"        , $loginstaid   )          ;      //员工ID
$t->set_var("staname"      , $loginstaname )          ;      //员工姓名
$t->set_var("storage"      , $storage      )          ;      //仓库
$t->set_var("state"        , $state        )          ;      //单据状态
$t->set_var("id"           , $id           )          ;      //单据ID
$t->set_var("no"           , $no           )          ;      //单据编号
$t->set_var("dept"         , $dept         )          ;      //部门
$t->set_var("memo"         , $memo         )          ;      //单据备注
$t->set_var("now"          , $now          )          ;      //录单时间
$t->set_var("dissave"      , $dissave      )          ;
$t->set_var("dealwithman"  , $dealwithman  )          ;
$t->set_var("year"         , $year         )          ;      //年
$t->set_var("month"        , $month        )          ;      //月
$t->set_var("day"          , $day          )          ;      //日
$t->set_var("action"       , $action       )          ;
$t->set_var("gotourl"      , $gotourl      )          ;      //转用的地址
$t->set_var("error"        , $error        )          ;
$t->set_var("dpt"          , $dpt          )          ;
$t->set_var("checkid"      , $checkid      )          ;      //批量删除商品ID

// 判断权限
include("../include/checkqx.inc.php");
$t->set_var("skin" , $loginskin);
$t->pparse("out" , "salelistview");    # 最后输出页面
?>