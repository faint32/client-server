<?
$thismenucode = "2k213";
require ("../include/common.inc.php");                                            
require ("../function/changestorage.php"); //�����޸Ŀ���ļ�
require ("../function/changemoney.php"); //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php"); //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php"); //�����޸��ʻ�����ļ�
require ("../function/cashglide.php"); //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php"); //�����������ʵ��ļ�
//require ("../function/makeavgprice.php");        //��������ƽ������
require ("../function/checkstorage.php"); //���ü���Ƿ�Ҫ�����֧�� 
$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcstockback_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
if(!empty($end_action)){
	$query = "select * from tb_paycardstockback where fd_stock_id = '$listid' 
	          and fd_stock_state = 9";
	$db->query($query);
	if($db->nf()){
		echo "<script>alert('�õ����Ѿ����ʣ��������޸ģ����֤')</script>"; 
		$action ="";
		$end_action="";
	}
}


//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if($end_action=="endsave"){
	$arr_tempdate = explode("/",$date);
	$listdate = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$listdate){
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$end_action="";
	}
}

switch($end_action){
	case "endsave":    //����ύ����
		
	      if($loginopendate>$date){
	     	  $error = "���󣺵������ڲ���С�������½���¿�ʼ����";
	     }else{	
	       $query = "select * from tb_paycardstockback where fd_stock_id = '$listid'";
          $db->query($query);
          if($db->nf()){
          	   $db->next_record();
               $listno       = $db->f(fd_stock_no);            //���ݱ��
               $suppid       = $db->f(fd_stock_suppid);        //��Ӧ��id��
               $suppno       = $db->f(fd_stock_suppno);        //��Ӧ�̱�� 
               $suppname     = $db->f(fd_stock_suppname);      //��Ӧ������
               $allmoney     = $db->f(fd_stock_allmoney)+0;    //������
			   $allquantity     = $db->f(fd_stock_allquantity)+0;    //������
               $accountid    = $db->f(fd_stock_accountid);     //�����ʻ�
          }
	        $query = "select * from tb_paycardstockbackdetail 
					left join tb_product on fd_product_id=fd_skdetail_productid
	                  where fd_skdetail_stockid = '$listid'";
	        $db->query($query);
	        if($db->nf()){
			
	        	while($db->next_record()){
	        		$paycardid         = $db->f(fd_skdetail_paycardid);
	        		$quantity          = $db->f(fd_skdetail_quantity);
	        		$price             = $db->f(fd_skdetail_price);
					$productid         = $db->f(fd_skdetail_productid);
					$batches           = $db->f(fd_skdetail_batches);
					$productname       = $db->f (fd_product_name );	
					if($strpaycardid){$strpaycardid .=",".$paycardid;}else{$strpaycardid=$paycardid;}	

					
					//noteprice($listid,$price,$paycardid);
					

	        		//�޸Ĳֿ�
	        		updatestorage($productid,$quantity,$price,$storageid,1);  //0��������1����
	        		
					//��Ʒ��ˮ��
					//��ȡˢ�����豸��
					$paycardkey=getpaycardkey($paycardid);
	        		$cogememo = $date."��".$productname."����,".$quantity."��ˢ����,ˢ�����豸��Ϊ:".$paycardkey;
	        		$cogelisttype = "2";
	        		$cogetype = 1; //0Ϊ���� �� 1Ϊ����
	        		commglide($storageid , $productid , $quantity , $cogememo , $cogelisttype , $loginstaname , $listid , $listno , $cogetype ,$date);     		
	        		

	        	}

	           		$arr_backpaycarid=explode(",",$paycardid);
					foreach($arr_backpaycarid as $value) 
 	        		{
						
						$query="select fd_paycard_memo,fd_paycard_stockprice from tb_paycard where fd_paycard_id='$value'";
						$db->query($query);
						if($db->nf())
						{
							$db->next_record();
							$paycard_memo=$db->f(fd_paycard_memo);
							$stockprice=$db->f(fd_paycard_stockprice);
							
						}
						$query="update tb_paycard set fd_paycard_memo='$paycard_memo,$date ��ˢ�����˻�' 
						where fd_paycard_id='$value'";	
						$db->query($query);
					}  
					
	          //���ɷ����������ʵ�
	          $ctatmemo     = "�˻ظ���Ӧ��".$suppname.$productname."����".$allquantity."��ˢ����,�˿�Ϊ".$allmoney."Ԫ";
	          $cactlisttype = "2";
	          currentaccount(2 , $suppid , $allmoney ,0 , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$date );

	        	changemoney(2 , $suppid ,$allmoney , 0 );  //0��������1������
	        		        		        	
	        	if($allmoney<>0){
	        	   //�����ʻ���ˮ��
	        	 $chgememo     = "��".$productname."����".$allquantity."��ˢ�����տ�".$allmoney."Ԫ";
	             $chgelisttype = "2";
	             $cogetype = 0; //0Ϊ���� �� 1Ϊ����
	        	  cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype ,$date);
	        	   
	        	   //�޸��ʻ����
	        	  // changeaccount($accountid , $allmoney , 1); //0������ʻ���1������ʻ�
	          }
	        }
	         $query = "update tb_paycardstockback set
	                 fd_stock_state   = 9  ,  fd_stock_datetime = now()             
	                 where fd_stock_id = '$listid' ";
		
	       $db->query($query);   //�޸ĵ�������
	        require("../include/alledit.2.php");
	      echo "<script>alert('�����ɹ�!');location.href='$gotourl';</script>";
	     } 
	  break;
		case "dellist":    //������ͨ��
       $query = "update tb_paycardstockback set fd_stock_state ='0'  where fd_stock_id = '$listid' ";
	     $db->query($query);   //�޸ĵ�������
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("stock_sp","jxcstockback_sp.html"); 

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
     if($paymoney==0){
     	 $paymoney ="";
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
$t->set_block("stock_sp", "prolist"  , "prolists"); 
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
       $vmoney      = $vprice * $vquantity;   //���
	   $vbatches        = $db->f(fd_skdetail_batches);
     
	  $vallquantity +=$vquantity;
       $vallmoney +=$vmoney;
	   
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



$vallmoney = number_format($vallmoney, 2, ".", "");


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

$t->pparse("out", "stock_sp");    # ������ҳ��






?>

