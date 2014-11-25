<?php
require ("../include/common.inc.php");
require ("../include/log.php");
// require ("../function/changestockquantity.php");
$db  = new DB_test;
$db2 = new DB_test;

$t = new Template( "." , "keep" );          //����һ��ģ��

$gourl = "tb_salelistview_b.php";
$gotourl = $gourl.$tempurl;

$query = "SELECT * FROM tb_salelist WHERE fd_selt_id = '$listid'";
$db->query($query);
$db->next_record();                          //��ȡ��¼����
$id         = $db->f(fd_selt_id);            //id��
$no         = $db->f(fd_selt_no);            //���ݱ��
$cusid      = $db->f(fd_selt_cusid);         //�ͻ�id
$cusno      = $db->f(fd_selt_cusno);         //�ͻ����
$cusname    = $db->f(fd_selt_cusname);       //�ͻ�����
$now        = $db->f(fd_selt_date);          //¼������
$dept       = $db->f(fd_dept_name);          //����
$staid      = $db->f(fd_selt_staid);         //¼����ID
$storage    = $db->f(fd_storage_name);       //�ֿ�
$memo       = $db->f(fd_selt_memo);          //��ע
$state      = $db->f(fd_selt_state);         //״̬
$dealwithman= $db->f(fd_selt_dealwithman);   //������ID

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

//��ʾ��Ʒ�б�


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
			$lprobatches        = $db->f(fd_paycard_batches);         //���κ�
			$lprokey            = $db->f(fd_paycard_key);             //ˢ�����豸��
			$lprosuppname       = $db->f(fd_product_suppname);        //��Ӧ��
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

	$count=0;//��Ʒ��¼��
	$lprosumnum=0;//��Ʒ������
	$lprosumprice=0;//��Ʒ�ܼ�
	$rows1 = $db->num_rows();
	$t->set_block("salelistview" , "BXBK" , "BXBKS");
	if($db->nf())
	{
		while($db->next_record())
		{
			$lprono       = $db->f(fd_stdetail_id);           //��Ʒ���
			$lproname     = $db->f(fd_stdetail_seltid);       //����ID
			$lprobar      = $db->f(fd_stdetail_paycardid);    //ˢ�����豸id
			$lprice       = $db->f(fd_stdetail_price);        //��Ʒ����
			$lquantity    = $db->f(fd_stdetail_quantity);     //��Ʒ����
			$lpromemo     = $db->f(fd_stdetail_memo);         //��Ʒ��ע
			$vproductid   = $db->f(fd_stdetail_productid);
			$lprosum      = $lquantity * $lprice;               //��Ʒ�ܼ�
			$lprosumnum   = $lquantity + $lprosumnum;           //ͳ����Ʒ������
			$lprosumprice = $lprosum + $lprosumprice;           //ͳ����Ʒ�ܼ�
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


$t->set_var("cusid"        , $cusid        )          ;      //�ͻ�ID
$t->set_var("cusno"        , $cusno        )          ;      //�ͻ����
$t->set_var("cusname"      , $cusname      )          ;      //�ͻ�����
$t->set_var("staid"        , $loginstaid   )          ;      //Ա��ID
$t->set_var("staname"      , $loginstaname )          ;      //Ա������
$t->set_var("storage"      , $storage      )          ;      //�ֿ�
$t->set_var("state"        , $state        )          ;      //����״̬
$t->set_var("id"           , $id           )          ;      //����ID
$t->set_var("no"           , $no           )          ;      //���ݱ��
$t->set_var("dept"         , $dept         )          ;      //����
$t->set_var("memo"         , $memo         )          ;      //���ݱ�ע
$t->set_var("now"          , $now          )          ;      //¼��ʱ��
$t->set_var("dissave"      , $dissave      )          ;
$t->set_var("dealwithman"  , $dealwithman  )          ;
$t->set_var("year"         , $year         )          ;      //��
$t->set_var("month"        , $month        )          ;      //��
$t->set_var("day"          , $day          )          ;      //��
$t->set_var("action"       , $action       )          ;
$t->set_var("gotourl"      , $gotourl      )          ;      //ת�õĵ�ַ
$t->set_var("error"        , $error        )          ;
$t->set_var("dpt"          , $dpt          )          ;
$t->set_var("checkid"      , $checkid      )          ;      //����ɾ����ƷID

// �ж�Ȩ��
include("../include/checkqx.inc.php");
$t->set_var("skin" , $loginskin);
$t->pparse("out" , "salelistview");    # ������ҳ��
?>