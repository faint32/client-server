<?
$thismenucode = "2n003";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("transfermoneyset","transfermoneyset.html"); 
$gourl = "tb_transfermoneyset_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // �޸ļ�¼	
 
		$query = "delete from tb_transfermoneyset  where fd_transfermoneyset_id  = '$transfermoneysetid'";

		$db->query($query);
		echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_transfermoneyset where fd_transfermoneyset_bankid ='$bankid'";
		$db->query($query);
		if($db->nf())
		{
			$error="�����Ѵ���!";
		}else{
			$query="INSERT INTO tb_transfermoneyset (
 		            fd_transfermoneyset_bankid ,fd_transfermoneyset_fee,fd_transfermoneyset_arrivemode,fd_transfermoneyset_feetype,fd_transfermoneyset_arrivetime
  		          )VALUES (
  		          '$bankid','$transfermoneysetfee','$transfermoneysetmode','%','$arrivetime' 
				)";
		   $db->query($query);
			//echo $query;
			Header("Location:$gotourl");
		}
		break;
	case "edit":   // �޸ļ�¼	
	/* 
	$query = "select * from tb_transfermoneyset where fd_bankset_name = '$bankid' and fd_transfermoneyset_id  <> '$transfermoneysetid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "�����������ظ�,���֤!";  
		  }else{
         $query = "update tb_transfermoneyset set
 		               fd_bankset_name   = '$bankid'  ,fd_transfermoneyset_fee ='$notransfermoneysetfee',
					   fd_transfermoneyset_arrivemode='$transfermoneysetmode'
  		             where fd_transfermoneyset_id  = '$transfermoneysetid' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
			 */
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_transfermoneyset where fd_transfermoneyset_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$transfermoneysetid=$db->f(fd_transfermoneyset_id );
		 
		$bankid=$db->f(fd_transfermoneyset_bankid );
		$transfermoneysetfee=$db->f(fd_transfermoneyset_fee );
		$transfermoneysetmode=$db->f(fd_transfermoneyset_arrivemode);
		$arrivetime=$db->f(fd_transfermoneyset_arrivetime);
		
		$disabled="disabled=disabled";
		$readonly="readonly";
		$checked=$transfermoneysetfee ? "disabled=disabled": "checked ";
		$display="style='display:none'";
		
	}
}

$query="select * from tb_bank";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$arr_bankid[]=$db->f(fd_bank_id);
		$arr_bankname[]=$db->f(fd_bank_name);
	}
}
$bankname = makeselect($arr_bankname,$bankid,$arr_bankid); 

$arr_modename=array("ʵʱ","T+1(��Ȼ��)","T+1(������)","T+2(������)","T+2(��Ȼ��)","T+3(������)");
$transfermoneysetmode = makeselect($arr_modename,$transfermoneysetmode,$arr_modename); 

$t->set_var("display"     , $display     );
$t->set_var("checked"     , $checked     );
$t->set_var("readonly"     , $readonly     );
$t->set_var("disabled"     , $disabled     );
$t->set_var("transfermoneysetid"     , $transfermoneysetid     );
$t->set_var("bankname"     , $bankname );
$t->set_var("transfermoneysetfee"     , $transfermoneysetfee );
$t->set_var("transfermoneysetmode"     , $transfermoneysetmode );
$t->set_var("arrivetime"     , $arrivetime );

$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "transfermoneyset");    # ������ҳ��


?>

