<?
$thismenucode = "2k401";
require ("../include/common.inc.php");

$db  = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;


$gourl = "bankcardset.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
//echo $toaction;
switch($toaction){
	case "del":   //ɾ��ϸ�ڱ�����
      		  $query="delete from tb_authorbkcard where fd_authorbkcard_id = '$id'";
      	      $db->query($query); 
              $vid = "";
	  break;
	case "save":  //��������

	    if(empty($vid)){
		
		   $query = "select* from tb_paycard where fd_paycard_id = '$listid'";
		   $db->query($query);
		   if($db->nf()){
			   $db->next_record();
			   $paycard_authorid = $db->f(fd_paycard_authorid);             
			   $paycard_id      = $db->f(fd_paycard_id);  
			}
			   
	    	$query = "insert into tb_authorbkcard (
	    	fd_authorbkcard_bank , fd_authorbkcard_isactive ,fd_authorbkcard_cardno,
			fd_authorbkcard_name , fd_authorbkcard_authorid ,fd_authorbkcard_paycardid
	    	)values(
	    	'$authorbkcard_bank' ,  '$authorbkcard_isactive' ,'$authorbkcard_cardno',
			'$authorbkcard_name' ,  '$paycard_authorid' ,'$paycard_id'
	    	)
	    	";
	    	$db->query($query);
	    	
	    }else{
	    	$query = "update tb_authorbkcard set 
			          fd_authorbkcard_bank = '$authorbkcard_bank'   , fd_authorbkcard_isactive = '$authorbkcard_isactive',
					  fd_authorbkcard_cardno = '$authorbkcard_cardno'  , fd_authorbkcard_name = '$authorbkcard_name'
	    	          where fd_authorbkcard_id = '$vid'  ";
	   
	    	$db->query($query);
	    }
	
		$vid = "";
	  break;
	  
	default:
	
	  $action="";
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("template","bankcardset.html"); 
//��ʾ�б�
$t->set_block("template", "prolist"  , "prolists"); 

if(!empty($vid)){
   $query = "select* from tb_authorbkcard where fd_authorbkcard_id = '$vid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $curid = $db->f('fd_authorbkcard_id') ;            //id��  
       $curauthorid = $db->f('fd_authorbkcard_authorid');            //���ݱ��
       $curisactive = $db->f('fd_authorbkcard_isactive');            //���ݱ��
	   $curcardno  = $db->f('fd_authorbkcard_cardno');            //���ݱ��
	   $curname = $db->f('fd_authorbkcard_name');
	   $curbank = $db->f('fd_authorbkcard_bank');
	   $curpaycardid = $db->f('fd_authorbkcard_paycardid');	   
   }
	

}

if(!empty($listid)){
   $query = "select* from tb_paycard where fd_paycard_id = '$listid'";
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $paycard_authorid = $db->f(fd_paycard_authorid);             
       $paycard_id      = $db->f(fd_paycard_id);    
	   $count=0;
	   $query = "select * from tb_authorbkcard where fd_authorbkcard_authorid = '$paycard_authorid' and fd_authorbkcard_paycardid = '$paycard_id'";
	   $db->query($query);
	   if($db->nf()){
		   while($db->next_record()){
		   
				if($s==1){            
				  $bgcolor="#F1F4F9";  
				  $s=0;                
				}else{                
				  $bgcolor="#ffffff";  
				  $s=1;                
				}
				$count++;
			   $t->set_var(array("id"         => $db->f('fd_authorbkcard_id')          ,
							 "authorid"        => $db->f('fd_authorbkcard_authorid')         ,
							 "isactive"          => $db->f('fd_authorbkcard_isactive') ? '��' : '��' ,
							 "cardno"    => $db->f('fd_authorbkcard_cardno')     ,
							 "name"    => $db->f('fd_authorbkcard_name')     ,
							 "bank"    => $db->f('fd_authorbkcard_bank')     ,
							 "paycardid"    => $db->f('fd_authorbkcard_paycardid')     ,
							 "bgcolor"      => $bgcolor,
							  ));
			  $t->parse("prolists", "prolist", true);		     
		   }	   
	   }else{
	     $t->parse("prolists", "", true);
	   }

   }
	

}
$arractiveid = array('0','1'); 
$arractivename = array('��','��');    
$selactive = makeselect($arractivename,$curisactive,$arractiveid);

$t->set_var("searchcontent"       , $searchcontent       );      //����id 
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("selactive"    , $selactive           );      //id 


$t->set_var("vid"    , $curid           );
$t->set_var("listid"    , $listid           );
$t->set_var("curauthorid"    , $curauthorid           );
$t->set_var("curisactive"    , $curisactive           );
$t->set_var("curcardno"    , $curcardno           );
$t->set_var("curname"    , $curname           );
$t->set_var("curbank"    , $curbank           );
$t->set_var("curpaycardid"    , $curpaycardid           );

$t->set_var("moneytype"    , $moneytype           );
$t->set_var("disabled"    , $disabled           );
$t->set_var("toaction"    , $toaction           );  
$t->set_var("gotourl"    , $gotourl           ); 


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # ������ҳ��



?>

