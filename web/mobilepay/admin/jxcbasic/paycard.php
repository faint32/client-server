<?
$thismenucode = "2k401";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("paycard","paycard.html"); 
$gourl = "tb_paycard_b.php";
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
 switch ($action)
{	
	case "new":

		$query="select * from tb_paycard where fd_paycard_key='$paycardkey'";
		$db->query($query);
		if($db->nf()){
			$error="���豸�Ѵ���!";
		}else{
	   $query="INSERT INTO tb_paycard(
			   fd_paycard_key,fd_paycard_batches,fd_paycard_active,fd_paycard_activetime,fd_paycard_datetime) VALUES(
			   '$paycardkey','$batches','$paycard_active','$activetime',now())";

	   $db->query($query);
	   $listid = $db->insert_id(); 
	   
	   if($paycard_active==1){
		   $query="select * from tb_paycardactive where fd_pcdactive_paycardid='$listid'";
		   $db->query($query);
		   if($db->nf()){
		   }else{
		   $query="INSERT INTO tb_paycardactive(
			   fd_pcdactive_paycardid,fd_pcdactive_activedate,fd_pcdactive_datetime) VALUES(
			   '$listid','$activetime',now())";
			$db->query($query);
		   }
			$query="select * from tb_paycardactivelist where fd_pcdactive_paycardid='$listid'";
		   $db->query($query);
		   if($db->nf()){
		   }else{
			$query="INSERT INTO tb_paycardactivelist(
			   fd_pcdactive_paycardid,fd_pcdactive_activedate,fd_pcdactive_datetime) VALUES(
			   '$listid','$activetime',now())";
			$db->query($query);
		   }
		}
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
   		$action="";
	   break;

	case "edit":   // �޸ļ�¼
/* 		$query="select * from tb_paycard where fd_paycard_key='$paycardkey' and fd_paycard_id <>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error="���豸�Ѵ���!���֤��";
		}else{	
	     $query = "update tb_paycard set
		 				fd_paycard_key	=	'$paycardkey',
						fd_paycard_batches	= '$batches',
			   			fd_paycard_isnew	= '$isnew',
						fd_paycard_activetime = '$activetime',
			   			fd_paycard_isnew	= '$isnew',
						fd_paycard_activetime = '$activetime',
 		                fd_paycard_active   = '$paycard_active' 
  		             where fd_paycard_id ='$listid'";
		  $db->query($query);
		  if($paycard_active==1){
			$query="select * from tb_paycardactive where fd_pcdactive_paycardid='$listid'";
		   $db->query($query);
		   if($db->nf()){
		   }else{
		   $query="INSERT INTO tb_paycardactive(
			   fd_pcdactive_paycardid,fd_pcdactive_activedate,fd_pcdactive_datetime) VALUES(
			   '$listid','$activetime',now())";
			$db->query($query);
		   }
			$query="select * from tb_paycardactivelist where fd_pcdactive_paycardid='$listid'";
		   $db->query($query);
		   if($db->nf()){
		   }else{
			$query="INSERT INTO tb_paycardactivelist(
			   fd_pcdactive_paycardid,fd_pcdactive_activedate,fd_pcdactive_datetime) VALUES(
			   '$listid','$activetime',now())";
			$db->query($query);
		   }
		} */
		
		$query = "update tb_paycard set
		 				fd_paycard_posstate	 =	'$selposstate '
  		           ,fd_paycard_active = 1   where fd_paycard_id ='$listid'";
		  $db->query($query);
	     require("../include/alledit.2.php");
		 echo "<script>alert('�޸ĳɹ�!');location.href='$gotourl';</script>";	
		$action="";
		break;
	   
	   case "delete":
	   $query="delete from tb_paycardactive where fd_pcdactive_paycardid='$listid'";
	   $db->query($query);
	   $query="delete from tb_paycard where fd_paycard_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	   default:
	   break;
} 


if(empty($listid)){
  $action = "new";
  
}else{
  $action = "edit"; 

	$query="select * from tb_paycard
								left join tb_bank on fd_bank_id= fd_paycard_bankid
								left join tb_author on fd_author_id=fd_paycard_authorid
								left join tb_product on fd_product_id=fd_paycard_product
								left join tb_customer on fd_cus_id=fd_paycard_cusid
								left join tb_saler on fd_saler_id = fd_paycard_salerid
								left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid 
			where fd_paycard_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$bankname=$db->f(fd_bank_name);
		$paycardkey=$db->f(fd_paycard_key);
		$batches=$db->f(fd_paycard_batches);
		$salertruename=$db->f(fd_saler_truename);
		$activetime= $db->f(fd_paycard_activetime);
        $isnew= $db->f(fd_paycard_isnew);	
		$mobile= $db->f(fd_author_mobile);
		$authorname = $db->f(fd_author_truename);
		$proname = $db->f(fd_product_name);
		$cusname = $db->f(fd_cus_name);
		$paycardtype = $db->f(fd_paycardtype_name);
		$datetime =$db->f(fd_paycard_datetime);
		$posstate = $db->f(fd_paycard_posstate);
		$scope  = $db->f(fd_paycard_scope);
		
		switch ($scope) {
			case "creditcard" :
				$scope = "���ÿ�";
				break;
			case "bankcard" :
				$scope = "��ǿ�";
				break;
		}
		//return $this->bwfd_show;
		
		$stockprice = $db->f(fd_paycard_stockprice);
		$saleprice =$db->f(fd_paycard_saleprice);
		
/* 		$query = "select * from tb_paycardtype where fd_paycardtype_id = '".$paycardtype."'";
		$db->query($query);
		if($db->nf()){
		  $db->next_record();
		  $paycardtype_name = $db->f('fd_paycardtype_name');
		}	 */	
		
	}
}
    $arractiveid = array('0','1',"2","3"); 
	$arractiveval = array('ͣ��','����',"����","����"); 
	$selposstate = makeselect($arractiveval,$posstate,$arractiveid);

	$arraisnew = array('0','1'); 
	$arraisnewval = array('��','��'); 
	$selisnew = makeselect($arraisnewval,$isnew,$arraisnew);

$t->set_var("listid"              ,  $listid           );
$t->set_var("paycardkey"              ,  $paycardkey           );
$t->set_var("batches"     , $batches     );
$t->set_var("mobile"     , $mobile     );
$t->set_var("bankname"     , $bankname     );
$t->set_var("salertruename"     , $salertruename     );
$t->set_var("datetime"     , $datetime     );
$t->set_var("scope"     , $scope     );

$t->set_var("authorname"     , $authorname     );
$t->set_var("activetime"     , $activetime     );
$t->set_var("proname"     , $proname     );
$t->set_var("cusname"     , $cusname     );
$t->set_var("stockprice"     , $stockprice     );
$t->set_var("saleprice"     , $saleprice     );
$t->set_var("paycardtype"     , $paycardtype     );

$t->set_var("selposstate"     , $selposstate     );
$t->set_var("selisnew"     , $selisnew     );
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "paycard");    # ������ҳ��


?>

