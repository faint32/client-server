<?
require ("../include/common.inc.php");
require ("../function/changekg.php");

$db = new DB_test ;
$count = 0;
$t = new Template('.', "keep");
$t->set_file("stockprint","stockprint.html");

$query = "select * from tb_paycardstock where fd_stock_id = '$listid' ";
$db->query($query);
if($db->nf()){
	$db->next_record();
	 $no           = $db->f(fd_stock_no);             //���ݱ��
	 $suppid       = $db->f(fd_stock_suppid);         //�ͻ�id
	 $suppno       = $db->f(fd_stock_suppno);         //�ͻ���� 
	 $suppname     = $db->f(fd_stock_suppname);       //�ͻ�����
	 $now          = $db->f(fd_stock_date);           //¼������
	 $dealwithman  = $db->f(fd_stock_dealwithman);    //������
	 $ldr 		   = $db->f(fd_stock_ldr);    //¼����
}

//��ʾ��Ʒ�б�
$t->set_block("stockprint", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail  
                 left join tb_product on fd_product_id = fd_skdetail_productid
          where fd_skdetail_stockid = '$listid'"; 
$db->query($query);
$rows = $db->num_rows();
$count=0;//��Ʒ��¼��
$prosumnum=0;//��Ʒ������
$prosumprice=0;//��Ʒ�ܼ�

if($db->nf()){
	while($db->next_record()){		
		   $lprono       = $db->f(fd_product_no);    //��Ʒ��� 
		   $lproname     = $db->f(fd_product_name);  //��Ʒ����
		   $batches      = $db->f(fd_skdetail_batches);  //��Ʒ������
		   $lprice       = $db->f(fd_skdetail_price);      //��Ʒ����
		   $lstdetailid  = $db->f(fd_skdetail_id);        //��Ʒ����ID��
		   $lquantity    = $db->f(fd_skdetail_quantity);  //��Ʒ����
		   $lpromemo     = $db->f(fd_skdetail_memo);      //��Ʒ��ע
		 	$paycardkey     = $db->f(fd_skdetail_paycardid);
		    
      		 $lprosum      = $lquantity*$lprice;              //��Ʒ�ܼ�
		   $lprosumnum   = $lquantity+$lprosumnum;          //ͳ����Ʒ������
		   $lprosumprice = $lprosum+$lprosumprice;        //ͳ����Ʒ�ܼ�
		    $lprosum  = number_format($lprosum, 2, ".", "");
		   $count++;
		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
        $bgcolor="#F1F4F9"; 
        $s=0;               
       }else{                
   	    $bgcolor="#ffffff"; 
        $s=1;               
       }     
		   
		   
		   $t->set_var(array("lproid"       => $lproid            ,
                         "lstdetailid"  => $lstdetailid       ,
                         "lprono"       => $lprono            ,
                         "lproname"     => $lproname          ,
                         "batches"      => $batches           ,
						 "paycardkey"      => $paycardkey           ,
                         "lprice"       => $lprice            ,    
		                     "lquantity"    => $lquantity         ,
				                 "lpromemo"     => $lpromemo          ,
				                 "lprosum"      => $lprosum           ,
				                 "trid"         => $trid             ,
				                 "imgid"        => $imgid            ,
				                 "count"        => $count            ,
				                 "lprosumnum"    => $lprosumnum        ,
				                 "lprosumprice"  => $lprosumprice      ,
				                 "bgcolor"      => $bgcolor           ,
				                 "datashow"     => ""                ,
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
      $t->set_var(array("lproid"       => ""           ,
                        "lstdetailid"  => ""           ,
                        "lprono"       => ""           , 
                        "lproname"     => ""           ,
                        "batches"      => ""           ,  
						"paycardkey"      => ""           ,
                        "lprice"       => ""           ,      
		                    "lquantity"    => ""           ,
				                "lpromemo"     => ""           ,    
				                "lprosum"      => ""           , 
				                "trid"         => "0"          ,
				                "imgid"        => ""           ,
				                "count"        => ""           ,
				                "lprosumnum"   => ""           ,
				                "lprosumprice" => ""           ,
				               
				                "datashow"     => "none"       ,
		              ));
		  $t->parse("prolists", "prolist", true);
}

$lprosumprice     = number_format($lprosumprice, 2, ".", "");

$t->set_var ("lprosum"           , $lprosum       ); 
$t->set_var ("lprosumprice"           , $lprosumprice       );  
$t->set_var ("count"            , $count        );
$t->set_var ("lprosumnum"            , $lprosumnum        );

$t->set_var ("no"           , $no       ); 
$t->set_var ("suppname"           , $suppname       );  
$t->set_var ("now"            , $now        );
$t->set_var ("dealwithman"            , $dealwithman        );
$t->set_var ("rmbname"            , $rmbname        );
$t->set_var ("ldr"            , $ldr        );
$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ
$t->set_var ( "error", $error );

// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse("out", "stockprint"); //   # ������ҳ��

?> 