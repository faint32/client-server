<?
$thismenucode = "2k212";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");

$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcstockback_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

if(!empty($action) or !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_paycardstockback where fd_stock_id = '$listid' 
  	          and (fd_stock_state = 1 or fd_stock_state = 9)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('�õ����Ѿ����ʻ��ߵȴ������У��������޸ģ����֤')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}

switch($action){
	case "del":   //ɾ��ϸ�ڱ�����
	  for($i=0;$i<count($checkid);$i++){
        if(!empty($checkid[$i])){		
		
			$query = "select * from tb_paycardstockbackdetail  where fd_skdetail_id='$checkid[$i]'";
			$db->query($query);
			if ($db->nf()) {
				while ($db->next_record()) {
					if($skdetail_paycardid){$skdetail_paycardid .=",".$db->f(fd_skdetail_paycardid);}else{$skdetail_paycardid=$db->f(fd_skdetail_paycardid);} 
				}
			}	
			
			
			$query="delete from tb_paycardstockbackdetail where fd_skdetail_id = '$checkid[$i]'";
			$db->query($query);
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$checkid[$i]' and fd_tmpsale_type='stockback'";			
      	  
			$db->query($query);
        }
    }
		if($skdetail_paycardid)
		{
			$arr_skdetail_paycardid=explode(",",$skdetail_paycardid);	
			changepaycardstate($arr_skdetail_paycardid,'1');
		}
	countallbackpaycard($listid);//ͳ��ˢ��������,���

   echo "<script>alert('ɾ���ɹ�!');location.href='jxcstockback.php?listid=$listid';</script>";	 
     break;
	case "del_one":   //ɾ��ϸ�ڱ�����
	$old_paycardid=getdatepaycard("tb_paycardstockbackdetail","skdetail",$delskdetailid);

	foreach($old_paycardid as $value)
	{
		if($value !=$delpaycard)
		{
			if($del_paycardid)
			{
				$del_paycardid .=",".$value;
			}else{
				$del_paycardid=$value;
			}
			
		}
	}
	$arr_num=explode(",",$del_paycardid);
	$paycardnum=count($arr_num);
	$query="update  tb_paycardstockbackdetail set fd_skdetail_paycardid='$del_paycardid', fd_skdetail_quantity='$paycardnum' where fd_skdetail_id = '$delskdetailid'";
	$db->query($query);
	
	
	$query="update  tb_salelist_tmp set fd_tmpsale_paycardid='$del_paycardid'  where fd_tmpsale_seltid = '$delskdetailid' and fd_tmpsale_type='stockback'";
	$db->query($query);

	$query = "update tb_paycard set fd_paycard_state='1' where fd_paycard_id='$delpaycard'";
	$db->query($query);
		
	countallbackpaycard($listid);//ͳ��ˢ��������,���

echo "<script>alert('ɾ���ɹ�!');location.href='jxcstockback.php?listid=$listid';</script>";
	  break;
	case "new":  //��������
	  if(empty($listid)){  //�������id�ǲ����ڵ�
	  	
	  		listnumber_update(2);  //���浥��
	  	
	  	$query = "select * from tb_paycardstockback where fd_stock_no = '$listno' ";
		
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "���ݱ���Ѿ����ڣ����֤��";
	    }else{
	      $query = "insert into tb_paycardstockback(
	                fd_stock_no          ,   fd_stock_suppid     , fd_stock_suppno           ,
	                fd_stock_suppname    ,   fd_stock_date       , fd_stock_ldr         ,
	                fd_stock_dealwithman ,   fd_stock_memo        
	                )values(
	                '$listno'           ,   '$suppid'          , '$suppno'             ,
	                '$suppname'         ,   '$date'            , '$loginstaname'       ,
	                '$dealwithman'      ,   '$memo_z'           
	                )";
	       $db->query($query);   //���뵥������
	       $listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
	    }
	  }else{   //�������id���Ѿ�����
	    $query = "select * from tb_paycardstockback where fd_stock_no = '$listno' and fd_stock_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "���ݱ���Ѿ����ڣ����֤��";
	    }else{
	      
	      $query = "update tb_paycardstockback set 
	               fd_stock_no          = '$listno'      ,  fd_stock_suppid           = '$suppid'           ,
	               fd_stock_suppno      = '$suppno'      ,  fd_stock_suppname         = '$suppname'         ,
	               fd_stock_date        = '$date'        ,   fd_stock_ldr             = '$loginstaname'     ,
	                fd_stock_dealwithman = '$dealwithman' ,  fd_stock_memo            = '$memo_z'
	               where fd_stock_id = '$listid' ";
	      $db->query($query);   //�޸ĵ�������
	    }
	  }
		
		echo "<script>alert('����ɹ�!');location.href='jxcstockback.php?listid=$listid';</script>";
	  break;
}

switch($end_action){
	case "endsave":    //����ύ����
		$query="select * from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid'";
		$db->query($query);
		if($db->nf()){
			 $arr_tempdate = explode("/",$date);
			 $date = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
			 if($loginopendate>$date){
				  $error = "���󣺵������ڲ���С�������½���¿�ʼ����";
			 }else{	
				$query = "update tb_paycardstockback set fd_stock_state = 1 where fd_stock_id = '$listid'";
			   //echo $query;
			   $db->query($query);  //�޸ĵ���״̬Ϊ����״̬
			
				require("../include/alledit.2.php");
			
				
			echo "<script>alert('����ɹ�!');location.href='$gotourl';</script>";
			}
		}else{
			$error = "��û���ˢ����,�����ˢ����!";
		}
	  break;
	case "dellist":    //ɾ��������������
		$query = "select * from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid' ";
			$db->query($query);
			while($db->next_record())
			{
				$skdetail_id=$db->f(fd_skdetail_id);
				if($skdetail_paycardid){$skdetail_paycardid .=",".$db->f(fd_skdetail_paycardid);}else{$skdetail_paycardid=$db->f(fd_skdetail_paycardid);} 
				$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$skdetail_id' and fd_tmpsale_type='stockback'";
				$db->query($query);
			}
		$arr_skdetail_paycardid=explode(",",$skdetail_paycardid);	
		changepaycardstate($arr_skdetail_paycardid,'1');
	    
		$query = "delete from tb_paycardstockback where fd_stock_id = '$listid'";
	     $db->query($query);   //ɾ���ܱ������
	     
	     $query = "delete from tb_paycardstockbackdetail where fd_skdetail_stockid = '$listid' ";
	     $db->query($query);   //ɾ��ϸ�ڱ�����
	     require("../include/alledit.2.php");
		 echo "<script>alert('ɾ���ɹ�!');location.href='$gotourl';</script>";	
	  break;
	default:
	  break;
}
//��Ʒ����
$query="select * from tb_product";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		
		$arr_productname[$db->f(fd_product_id)]=$db->f(fd_product_name);
		$arr_productno[$db->f(fd_product_id)]=$db->f(fd_product_no);
	}
} 

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("stock","jxcstockback.html"); 
if(empty($listid))
{		// ����
  $tijiao_dis="disabled";
  $date  = date( "Y-m-d",time());
}else{
	$tijiao_dis="";
   $query = "select * from tb_paycardstockback where fd_stock_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $listid         = $db->f(fd_stock_id);            //id��  
       $listno         = $db->f(fd_stock_no);            //���ݱ��
       $suppid         = $db->f(fd_stock_suppid);        //��Ӧ��id
       $suppno         = $db->f(fd_stock_suppno);        //��Ӧ�̱�� 
       $suppname       = $db->f(fd_stock_suppname);      //��Ӧ������
       $date           = $db->f(fd_stock_date);          //¼������
       $memo_z         = $db->f(fd_stock_memo);          //��ע
   	   $dealwithman    = $db->f(fd_stock_dealwithman);   //������   
   	   
   }
}


//��ʾ�б�
$t->set_block("stock", "prolist"  , "prolists"); 
$query = "select * from tb_paycardstockbackdetail 
          where fd_skdetail_stockid = '$listid' order by fd_skdetail_id desc "; 
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
	   
       $vproductid=$arr_productname[$vproductid];
		   
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
						 "count"          => $count           ,
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
		  $save_dis="";
	}
}else{
		$save_dis="disabled";
		  $t->parse("prolists", "", true);	
}      


if(empty($listno)){ //��ʾ��ʱ�ĵ��ݱ��
	$listno=listnumber_view("2");
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
$t->set_var("ldr"          , $loginstaname       );  
$t->set_var("save_dis", $save_dis     );      //��ƷID

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("vallmoney"    , $vallmoney );                                                   
$t->set_var("date"         , $date         );      //����

    
$t->set_var("gotourl"   , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"     , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   );                                      
$t->set_var("checkid"   , $checkid    );      //����ɾ����ƷID   

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "stock");    # ������ҳ��



?>

