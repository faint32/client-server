<?
//$thismenucode = "2k227";
require ("../include/common.inc.php");

require ("../function/functionlistnumber.php");  //�����г����ݱ���ļ�                                                 
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php");           //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�
                                 

$db  = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep");          //����һ��ģ��
$gourl = "tb_jxcstock_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$query = "select * from tb_paycardstockdetail
			left join tb_paycard on fd_paycard_batches = fd_skdetail_batches
			left join tb_paycardstock on fd_stock_id = fd_skdetail_stockid
          	where fd_skdetail_batches = '$batches'";
$db->query($query);
if($db->nf()){
   $db->next_record();                              //��ȡ��¼����  
   $listno       = $db->f(fd_stock_no);             //���ݱ��
   $suppid       = $db->f(fd_stock_suppid);         //�ͻ�id
   $suppno       = $db->f(fd_stock_suppno);         //�ͻ���� 
   $suppname     = $db->f(fd_stock_suppname);       //�ͻ�����
   $stockorderno = $db->f(fd_stock_stockorderno);   //�������
   $date          = $db->f(fd_stock_date);           //¼������
   $iskickback   = $db->f(fd_stock_iskickback);     //�Ƿ񷴳�
   $staname      = $db->f(fd_sta_name);             //¼����
   $state        = $db->f(fd_stock_state);          //״̬

   $memo_z       = $db->f(fd_stock_memo);           //��ע
   $paymoney     = $db->f(fd_stock_allmoney);       //������



}        

$t->set_file("paycardview","paycardview.html"); 

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
$t->set_block("paycardview", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockdetail 
		 where fd_skdetail_batches = '$batches' 	"; 
$db->query($query);
$count=0;//��¼��
$vallquantity=0;//�ܼ�
if($db->nf()){
	while($db->next_record()){		
		$vid       = $db->f(fd_skdetail_id);
		$vpaycardid   = $db->f(fd_skdetail_paycardid);    //��Ʒid��
       $vprice    = $db->f(fd_skdetail_price)+0;   //����
       $vquantity = $db->f(fd_skdetail_quantity)+0;//����
       $vmemo     = $db->f(fd_skdetail_memo);      //��ע
	    $batches        = $db->f(fd_skdetail_batches);	 
		$vproductid    = $db->f(fd_skdetail_productid);   	
       $vproductid=$arr_product[$vproductid];	
        $vmoney = $vprice * $vquantity;
       $vallquantity +=$vquantity;
       $vallmoney +=$vmoney;

       
		   $count++;

		 
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
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         ,
						"vpaycardid" => $vpaycardid  		,
						  "batches"     =>$batches			,	
						  "vproductid"  =>$vproductid,
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
     
		  $t->parse("prolists", "", true);	
}           

$vallmoney = number_format($vallmoney, 2, ".", ",");



/* //��ʾ�ʻ�ѡ���б�
$query = "select * from tb_account where fd_account_id = '$accountid'" ;
$db->query($query);
if($db->nf()){
  $db->next_record();	
	$accountname  = $db->f(fd_account_name);    
} */

$t->set_var("suppid"        , $suppid         );      //�ͻ�ID
$t->set_var("suppno"        , $suppno         );      //�ͻ����
$t->set_var("suppname"      , $suppname       );      //�ͻ�����
$t->set_var("stockorderno"  , $stockorderno   );      //�ͻ�����            
     
$t->set_var("listid"        , $listid         );      //����ID
$t->set_var("listno"        , $listno         );      //���ݱ��          
         
$t->set_var("memo_z"        , $memo_z         );      //���ݱ�ע  
$t->set_var("now"           , $now            );      //¼��ʱ��   
$t->set_var("kickback_disabled" , $kickback_disabled  );   

$t->set_var("dyj"           , $dyj            );      //���˼�
$t->set_var("paymoney"      , $paymoney       );      //������

$t->set_var("vallmoney"      , $vallmoney       );    //�ܽ��

$t->set_var("vallquantity"  , $vallquantity   );      //������
$t->set_var("count"         , $count          );      //��¼��
$t->set_var("date"           , $date            );      //�� 


 
$t->set_var("action"        , $action         );        
$t->set_var("gotourl"       , $gotourl        );      // ת�õĵ�ַ
$t->set_var("error"         , $error          );         

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "paycardview");    # ������ҳ��



?>

