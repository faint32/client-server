<?
$thismenucode = "2k206";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //�����г����ݱ���ļ�                                                 
require ("../function/changestorage.php");       //�����޸Ŀ���ļ�
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/commglide.php");           //������Ʒ��ˮ���ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�
require ("../function/checkstorage.php");        //���ü���Ƿ�Ҫ�����֧��

$db  = new DB_test;
$db2 = new DB_test;

$t = new Template(".", "keep");          //����һ��ģ��

$gourl = "tb_jxcsale_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");


switch ($action){

	case "draft":       	    
     //תΪ�ݸ嵥   	   
     $query = "select * from tb_salelist where fd_selt_id = '$listid'";
	   $db->query($query);
	   if($db->nf()){
	        $db->next_record();                          //��ȡ��¼����     
          $cusid         = $db->f(fd_selt_cusid);       //�ͻ�id   
          $cusno         = $db->f(fd_selt_cusno);       //�ͻ���� 
          $cusname       = $db->f(fd_selt_cusname);     //�ͻ����� 
          $now           = $db->f(fd_selt_date);         //¼������ 
         $datetime           = $db->f(fd_selt_datetime);         //¼������ 
          $memo          = $db->f(fd_selt_memo);         //��ע  
          $skfs          = $db->f(fd_selt_skfs);          //�տʽ



          $listno = listnumber_update(3);  //���浥��
          
          $query="INSERT INTO tb_salelist(
 	                fd_selt_no           , fd_selt_cusid          , fd_selt_cusno       ,
 	                fd_selt_cusname      , fd_selt_date           ,
 	                fd_selt_memo         , fd_selt_allmoney       , 
					fd_selt_skfs        ,fd_selt_datetime
                  )VALUES (
                  '$listno'             , '$cusid'              ,   '$cusno'           ,
                  '$cusname'            , '$now'                ,   
                  '$memo'               , '$paymoney'           ,   
                   '$skfs'               ,'$datetime'       
                  )";
       
	        $db->query($query);
	        $oldid = $db->insert_id();    
    }
    if(!empty($oldid)){
       $query = "select * from tb_salelistdetail 
                 where fd_stdetail_seltid = '$listid'"; 
       $db->query($query);
       if($db->nf()){
       	while($db->next_record()){		
       		  $proid       = $db->f(fd_stdetail_paycardid);    //��ƷID

            $proprice    = $db->f(fd_stdetail_price);     //�۸�



       
            $query="INSERT INTO tb_salelistdetail (
 	                   fd_stdetail_seltid     , fd_stdetail_paycardid  ,  fd_stdetail_price             
                     )VALUES (
                     '$oldid'               , '$proid'            ,  '$proprice'         
                    )";
	           $db2->query($query);          
                    
         }
       }
     }	        
     break;
 
}


$query = "select * from tb_salelist  where fd_selt_id = '$listid'";
$db->query($query);
if($db->nf()){
      $db->next_record();                              //��ȡ��¼����  
      $listno       = $db->f(fd_selt_no);            //���ݱ��
      $cusid        = $db->f(fd_selt_cusid);         //�ͻ�id
      $cusno        = $db->f(fd_selt_cusno);         //�ͻ���� 
      $cusname      = $db->f(fd_selt_cusname);       //�ͻ�����
      $date          = $db->f(fd_selt_date);          //¼������

      $state        = $db->f(fd_selt_state);          //״̬

      $memo_z       = $db->f(fd_selt_memo);           //��ע

      $skfs         = $db->f(fd_selt_skfs);          //�տʽ

}         


	$t->set_file("salelistview","salelistview.html"); 

	$arr_data=stocksalepaycard('tb_paycardstockdetail','skdetail');
	$arr_batches=$arr_data[1];
	$arr_suppname=$arr_data[2];

//��ʾ�б�
$t->set_block("salelistview", "prolist"  , "prolists"); 
$query = "select * from tb_salelistdetail 
          where fd_stdetail_seltid = '$listid'"; 
$db->query($query);
$count=0;//��¼��
$vallquantity=0;//�ܼ�
if($db->nf()){
	while($db->next_record()){		
		$vid       = $db->f(fd_stdetail_id);
		$vpaycardid   = $db->f(fd_stdetail_paycardid);    //��Ʒid��
       $vprice    = $db->f(fd_stdetail_price)+0;     //����
       $vquantity = $db->f(fd_stdetail_quantity)+0;  //����
       $vmemo     = $db->f(fd_stdetail_memo);      //��ע
	    $vbatches=$arr_batches[$vpaycardid];
		$vsuppname=$arr_suppname[$vpaycardid];
       $vallmoney +=$vprice;
          $count++;
		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   $vmoney = number_format($vmoney, 2, ".", "");	   
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
                         "vpaycardid" => $vpaycardid  ,
                         "vmemo"        => $vmemo         ,
                         "bgcolor"      => $bgcolor       ,
                         "vprice"       => $vprice        ,
                         "vsuppname" => $vsuppname  ,
                         "vbatches"    => $vbatches     ,        
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
	$isshow="none";
	  $action = "edit";
}else{

		  $t->parse("prolists", "", true);	
}  		  


$vallmoney = round($vallmoney, 2);




//�տʽ
$arr_skfs = array("","�ֽ�","֧Ʊ","���","�ж�");
$skfs = $arr_skfs[$skfs];

 
$t->set_var("cusid"        , $cusid         );      //�ͻ�ID
$t->set_var("cusno"        , $cusno         );      //�ͻ����
$t->set_var("cusname"      , $cusname       );      //�ͻ�����       


$t->set_var("listid"        , $listid         );      //����ID
$t->set_var("listno"        , $listno         );      //���ݱ��          
       
$t->set_var("memo_z"        , $memo_z         );      //���ݱ�ע  
$t->set_var("now"           , $now            );      //¼��ʱ��   

$t->set_var("skfs"          , $skfs           );      //�տʽ 



$t->set_var("vallmoney"     , $vallmoney   );      //�ܽ��

$t->set_var("count"         , $count          );      //��¼��
                                                      
$t->set_var("date"          , $date           );      //��


$t->set_var("action"        , $action         );        
$t->set_var("gotourl"       , $gotourl        );      // ת�õĵ�ַ
$t->set_var("error"         , $error          );      
                                   
                                   
// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salelistview");    # ������ҳ��


?>

