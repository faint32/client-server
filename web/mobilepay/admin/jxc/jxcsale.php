<?
$thismenucode = "2k204";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //�������ɵ��ݱ���ļ�                                                
require ("../function/changestorage.php");       //�����޸Ŀ���ļ�
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php");           //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�

$db  = new DB_test;
$db1 = new DB_test;

$gourl = "tb_jxcsale_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

if(!empty($action) && !empty($end_action)){
	if(!empty($listid)){
  	$query = "select * from tb_salelist where fd_selt_id = '$listid' 
  	          and (fd_selt_state = 9 or fd_selt_state = 1)";
  	$db->query($query);
  	if($db->nf()){
  		echo "<script>alert('�õ����Ѿ����ʻ��ߵȴ���ˣ��������޸ģ����֤')</script>"; 
  		$action ="";
  		$end_action="";
  	}
  }
}

switch($action){
	case "del":   //ɾ��ϸ�ڱ�����
	  for($i=0;$i<count($checkid);$i++){
        if(!empty($checkid[$i])){
			$query = "select * from tb_salelistdetail  where fd_stdetail_id='$checkid[$i]'";
			$db->query($query);
			if ($db->nf()) {
				while ($db->next_record()) {
					if($stdetail_paycardid){$stdetail_paycardid .=",".$db->f(fd_stdetail_paycardid);}else{$stdetail_paycardid=$db->f(fd_stdetail_paycardid);} 
			
				}
			}
			$query="delete from tb_salelistdetail where fd_stdetail_id = '$checkid[$i]'";
			$db->query($query);
			
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$checkid[$i]' and fd_tmpsale_type='sale'";
		    $db->query($query);
        }
		
    }
		if($stdetail_paycardid){
		$arr_stdetail_paycardid=explode(",",$stdetail_paycardid);	
		changepaycardstate($arr_stdetail_paycardid,'1');	
		}
		
		countallsalepaycard($listid,'tb_salelist','tb_salelistdetail');
		
	echo "<script>alert('ɾ���ɹ�!');location.href='jxcsale.php?listid=$listid';</script>";
	break;
		case "del_one":   //ɾ��ϸ�ڱ�����
			
			
			
			$old_paycardid=getdatepaycard("tb_salelistdetail","stdetail",$delseltid);
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
			$query="update  tb_salelistdetail set fd_stdetail_paycardid='$del_paycardid', fd_stdetail_quantity='$paycardnum' where fd_stdetail_id = '$delseltid'";
			$db->query($query);
			
			
			$query="update  tb_salelist_tmp set fd_tmpsale_paycardid='$del_paycardid'  where fd_tmpsale_seltid = '$delseltid' and fd_tmpsale_type='sale'";
      	    $db->query($query);
		
			$query = "update tb_paycard set fd_paycard_state='1' where fd_paycard_id='$delpaycard'";
			$db->query($query);
    
			countallsalepaycard($listid,'tb_salelist','tb_salelistdetail');
			
	echo "<script>alert('ɾ���ɹ�!');location.href='jxcsale.php?listid=$listid';</script>";
	  break;
	  case "new":  //��������
	
		  if (empty($listid)){  //�������id�ǲ����ڵ�
	  	
	  		listnumber_update(3);  //���浥��
	  	
	  	$query = "select * from tb_salelist where fd_selt_no = '$listno' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "���ݱ���Ѿ����ڣ����֤��";
	    }else{
	      
	      $query = "insert into tb_salelist(
	                fd_selt_no          ,   fd_selt_date  ,fd_selt_cusid   , fd_selt_cusno,
					fd_selt_cusname       ,	fd_selt_skfs  ,fd_selt_shaddress  ,fd_selt_ldr,fd_selt_dealwithman,
					fd_selt_saleprice     , fd_selt_allquantity,fd_selt_type
	                )values(
	                '$listno'           ,   '$date'      ,'$cusid'         , '$cusno',
					'$cusname'            ,   '$skfs'      ,'$shaddress','$ldr','$dealwithma',
					'$saleprice'     	    ,  '$allquantity' , '$type'
	                )";
	       $db->query($query);   //���뵥������
	       $listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
	    }
		
	  }else{   //�������id���Ѿ�����
	    $query = "select * from tb_salelist where fd_selt_no = '$listno' and fd_selt_id <> '$listid' ";
	  	$db->query($query);
	  	if($db->nf()){
	  		$error = "���ݱ���Ѿ����ڣ����֤��";
	    }else{
	      
	      $query = "update tb_salelist set 
	               fd_selt_no          = '$listno'    , fd_selt_cusid         = '$cusid'           ,
	               fd_selt_cusno      = '$cusno'      , fd_selt_cusname         = '$cusname'         ,
	               fd_selt_date        = '$date'      , fd_selt_shaddress      = '$shaddress'       ,
	               fd_selt_skfs        = '$skfs'      , fd_selt_ldr           ='$ldr'             ,
	               fd_selt_allquantity = '$allquantity', fd_selt_saleprice     = '$saleprice'       ,
	               fd_selt_type        = '$type',
				   fd_selt_dealwithman = '$dealwithman'
	               where fd_selt_id = '$listid' ";
	      $db->query($query);   //�޸ĵ�������
	    }
	  }	
	   echo "<script>alert('�ݴ�ɹ�!');location.href='jxcsale.php?listid=$listid';</script>";
	  break;
}



//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if($end_action=="endsave"){
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$date){
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$action="";
	}
}

switch($end_action){
	case "endsave":    //����ύ����

        $query = "update tb_salelist set
	                fd_selt_no           = '$listno'      ,  fd_selt_cusid       = '$cusid'      ,
	                fd_selt_cusno        = '$cusno'       ,  fd_selt_cusname     = '$cusname'    ,
	                fd_selt_date         = '$date'        , 
	                fd_selt_memo         = '$memo_z'      , fd_selt_skfs        = '$skfs'       ,
	                fd_selt_shaddress    = '$shaddress'    ,    fd_selt_ldr           ='$ldr',
				      fd_selt_dealwithman  = '$dealwithman'  ,   fd_selt_allquantity = '$allquantity',
				      fd_selt_saleprice    = '$saleprice'    ,
	                fd_selt_type         = '$type'
	                where fd_selt_id = '$listid' ";		
	      $db->query($query);   //�޸ĵ�������
			$query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
			$db->query($query);
			while($db->next_record())
			{
				$stdetail_id=$db->f(fd_stdetail_id);
					$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$stdetail_id' and fd_tmpsale_type='sale'";
					$db->query($query);
			}
		    $query = "update tb_salelist set  fd_selt_state  = '2'   where fd_selt_id  = '$listid' ";
	        $db->query($query);   //�޸ĵ�������
	        require("../include/alledit.2.php");
	      echo "<script>alert('�ύ�ɹ�!');location.href='$gotourl';</script>";	

	    
	  break;
		case "dellist":    //ɾ��������������
		 $query = "select * from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
		$db->query($query);
		while($db->next_record())
		{
			$stdetail_id=$db->f(fd_stdetail_id);
			if($stdetail_paycardid){$stdetail_paycardid .=",".$db->f(fd_stdetail_paycardid);}else{$stdetail_paycardid=$db->f(fd_stdetail_paycardid);} 
			
			
			$query="delete from tb_salelist_tmp where fd_tmpsale_seltid = '$stdetail_id' and fd_tmpsale_type='sale'";
			$db->query($query);
		}
			
			$arr_stdetail_paycardid=explode(",",$stdetail_paycardid);
			changepaycardstate($arr_stdetail_paycardid,'1');
	     
		 $query = "delete from tb_salelist where fd_selt_id = '$listid'";
	     $db->query($query);   //ɾ���ܱ������
	     
	     $query = "delete from tb_salelistdetail where fd_stdetail_seltid = '$listid' ";
	     $db->query($query);   //ɾ��ϸ�ڱ�����
		
	     require("../include/alledit.2.php");
	    echo "<script>alert('ɾ���ɹ�!');location.href='$gotourl';</script>";	
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("salelist","jxcsale.html"); 
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


if (empty($listid))
{		// ����
  
   $date  = date( "Y-m-d",time());
	$tijiao_dis="disabled";

}else{
  $tijiao_dis="";
   $query = "select * from tb_salelist where fd_selt_id = '$listid'";
   $db->query($query);
      if($db->nf()){
   
   	   $db->next_record();
   	   $listid         = $db->f(fd_selt_id);            //id��  
       $listno         = $db->f(fd_selt_no);            //���ݱ��
       $cusid         = $db->f(fd_selt_cusid);        //��Ӧ��id
       $cusno         = $db->f(fd_selt_cusno);        //��Ӧ�̱�� 
       $cusname       = $db->f(fd_selt_cusname);      //��Ӧ������
       $date           = $db->f(fd_selt_date);          //¼������
	   $ldr           = $db->f(fd_selt_ldr);          //¼����
	   $dealwithman    = $db->f(fd_selt_dealwithman);          //������
       $memo_z         = $db->f(fd_selt_memo);          //��ע
   	   $skfs           = $db->f(fd_selt_skfs);          //��ע
   	   $shaddress      = $db->f(fd_selt_shaddress);          //��ע
       $allquantity    = $db->f(fd_selt_allquantity);
       $saleprice      = $db->f(fd_selt_saleprice);
          $type        = $db->f(fd_selt_type);
	
   }

}





//��ʾ�б�
$t->set_block("salelist", "prolist"  , "prolists"); 
$query = "select * from tb_salelistdetail 
          where fd_stdetail_seltid = '$listid' order by fd_stdetail_id desc"; 
$db->query($query);
$ishavepaycard=$db->nf();
$count=0;//��¼��
$vallquantity=0;//�ܼ�
if($db->nf()){
	$save_dis="";
	while($db->next_record()){		
	   $vid       = $db->f(fd_stdetail_id);
	   $vpaycardid   = $db->f(fd_stdetail_paycardid);    //��Ʒid��
       $vprice    = $db->f(fd_stdetail_price)+0;   //����
       $vquantity = $db->f(fd_stdetail_quantity)+0;//����
       $vmemo     = $db->f(fd_stdetail_memo);      //��ע
	   $vproductid    = $db->f(fd_stdetail_productid);  
       $vmoney = $vprice * $vquantity;   //���
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
						 "count"          => $count           ,
                         "vquantity"    => $vquantity     ,
                         "vpaycardid" => $vpaycardid  ,
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "rowcount"     => $count         ,
                         "vmoney"       => $vmoney         , 
						  "vproductid"   =>$vproductid
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{
		  $t->parse("prolists", "", true);	
		 $save_dis="disabled";
}      
//��ȡ���ݱ��   
if(empty($listno))
{
	$listno=listnumber_view(3);
}

//�տʽ
$arr_skfs = array("�ֽ�","֧Ʊ","���","�ж�","����֧��");
$arr_skfsval = array("1","2","3","4","5");
$skfs = makeselect($arr_skfs,$skfs,$arr_skfsval);

$arr_type = array("auto","app");
$arr_typeval = array("ϵͳ����","�Զ�");
$type = makeselect($arr_typeval,$type,$arr_type);




/* 
//��ѯ�ͻ������Ŷ��
$query = "select fd_cus_credit , fd_cus_custypeid from tb_customer where fd_cus_id = '$cusid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$cus_credit = $db->f(fd_cus_credit);
	$cus_typeid = $db->f(fd_cus_custypeid);
	if(empty($error) && $cus_typeid==4){
		$error = "����ͻ��Ѿ����ܲ��������������ע�⣡";
	}
} */
$vallmoney = number_format($vallmoney, 2, ".", "");
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("tmpid"       , $tmpid         );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //���ݱ�� 
$t->set_var("cusid"        , $cusid        );      //�ͻ�id��
$t->set_var("cusno"        , $cusno        );      //�ͻ����
$t->set_var("cusname"      , $cusname      );      //�ͻ�����
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("ldr"      , $ldr      );      //¼����
$t->set_var("dealwithman"       , $dealwithman       );      //������
$t->set_var("skfs"         , $skfs         );      //�տʽ 
$t->set_var("shaddress"    , $shaddress    );
$t->set_var("productid"    , $productid     );      //��ƷID
$t->set_var("ishavepaycard", $ishavepaycard     );      //��ƷID
$t->set_var("save_dis", $save_dis     );      //��ƷID
$t->set_var("allquantity", $allquantity     );
$t->set_var("saleprice", $saleprice     );
$t->set_var("type", $type     );




$t->set_var("isxydisabled"  , $isxydisabled  );      //���α��水ť 

$t->set_var("count"        , $count        );
$t->set_var("vallquantity" , $vallquantity );
$t->set_var("vallmoney"    , $vallmoney    );      //�ܽ��





                                                    
$t->set_var("date"         , $date         );      


$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"        , $error        );      
$t->set_var("tijiao_dis"   , $tijiao_dis   ); 
                                            
$t->set_var("checkid"      , $checkid      );      //����ɾ����ƷID   

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salelist");    # ������ҳ��



?>

