<?
$thismenucode = "2k212";
require ("../include/common.inc.php");                                                 
$db  = new DB_test;
if($backstate == 0){
  $gourl = "tb_jxcstockback_b.php" ;
}else if($backstate == 1){
	$gourl = "tb_jxcstockback_sp_b.php" ;
}


$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("stock_back_view","stock_back_view.html"); 

$query = "select * from tb_paycardstockback 
          where fd_stock_id = '$listid'";
$db->query($query);
if($db->nf()){
	   $db->next_record();
	   $listid       = $db->f(fd_stock_id);            //id��  
     $listno       = $db->f(fd_stock_no);            //���ݱ��
     $suppno       = $db->f(fd_stock_suppno);        //��Ӧ�̱�� 
     $suppname     = $db->f(fd_stock_suppname);      //��Ӧ������
     $date         = $db->f(fd_stock_date);          //¼������
     $memo_z       = $db->f(fd_stock_memo);          //��ע
     $allmoney     = $db->f(fd_stock_allmoney)+0;    //������ 
	 $dealwithman  = $db->f(fd_stock_dealwithman);   //������
	  $ldr         = $db->f(fd_stock_ldr);   //������

     if($allmoney==0){
     	 $allmoney ="";
     }


	   
}


//��Ʒ����
$query="select * from tb_product";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	
		$arr_product[$db->f(fd_product_id)]=$db->f(fd_product_name);
	}
} 


//��ʾ�б�
$t->set_block("stock_back_view", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockbackdetail 
          where fd_skdetail_stockid = '$listid' "; 
$db->query($query);
$ishavepaycard=$db->nf();
$count=0;//��¼��
$vallquantity=0;//�ܼ�
if($db->nf()){
	while($db->next_record()){		
	   $vid       = $db->f(fd_skdetail_id);
	   $vpaycardid   = $db->f(fd_skdetail_paycardid);    //��Ʒid��
       $vprice    = $db->f(fd_skdetail_price)+0;   //����
       $vquantity = $db->f(fd_skdetail_quantity)+0;//����
       $vmemo     = $db->f(fd_skdetail_memo);      //��ע
	   $vproductid    = $db->f(fd_skdetail_productid);  

	   $vbatches        = $db->f(fd_skdetail_batches);
     
	  $vallquantity +=$vquantity;

	   
       $vproductid=$arr_product[$vproductid];
		   
		   $count++;
		   $vdunprice = number_format($vdunprice, 4, ".", "");
		   $vmoney = number_format($vmoney, 2, ".", "");

		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
		   
		   
		   
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $vid           ,
                         "vquantity"    => $vquantity     ,
                         "vpaycardid" => $vpaycardid  ,
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         , 
						  "vbatches"     =>$vbatches      ,
						  "vproductid"   =>$vproductid
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
   
		  $t->parse("prolists", "", true);	
}      



$allmoney = number_format($allmoney, 2, ".", "");


$t->set_var("ishavepaycard", $ishavepaycard     );      //��ƷID
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //���ݱ�� 
$t->set_var("suppid"       , $suppid       );      //��Ӧ��id��
$t->set_var("suppno"       , $suppno       );      //��Ӧ�̱��
$t->set_var("suppname"     , $suppname     );      //��Ӧ������
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("dealwithman"  , $dealwithman        );  
$t->set_var("ldr"          , $ldr       );  

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("allmoney"    , $allmoney );    

                                             
$t->set_var("date"         , $date         );      //����

    
$t->set_var("gotourl"   , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"     , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   );                                      
$t->set_var("checkid"   , $checkid    );      //����ɾ����ƷID   

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "stock_back_view");    # ������ҳ��

?>

