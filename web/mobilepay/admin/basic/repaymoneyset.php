<?
$thismenucode = "2n105";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("repaymoneyset","repaymoneyset.html"); 
$gourl = "tb_repaymoneyset_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // �޸ļ�¼	
 
		$query = "delete from tb_repaymoneyset  where fd_repaymoneyset_id  = '$repaymoneysetid'";

		$db->query($query);
		echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_repaymoneyset where fd_repaymoneyset_bankid ='$bankid'";
		$db->query($query);
		if($db->nf())
		{
			$error="�����Ѵ���!";
		}else{
			$query="INSERT INTO tb_repaymoneyset (
 		            fd_repaymoneyset_bankid ,fd_repaymoneyset_fee,fd_repaymoneyset_tradememo,fd_repaymoneyset_feetype,fd_repaymoneyset_arrivetime,fd_repaymoneyset_moneyqj
  		          )VALUES (
  		          '$bankid','$repaymoneysetfee','$tradememo','%','$arrivetime' ,'$moneyqj'
				)";
		   $db->query($query);
			//echo $query;
		  echo ("<script>alert('����ɹ�!');location.href='$gotourl'</script>");
		}
		break;
	case "edit":   // �޸ļ�¼	
	
	$query = "select * from tb_repaymoneyset where fd_repaymoneyset_bankid = '$bankid' and fd_repaymoneyset_id  <> '$repaymoneysetid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "�����������ظ�,���֤!";  
		  }else{
         $query = "update tb_repaymoneyset set
 		               fd_repaymoneyset_bankid   = '$bankid'  ,fd_repaymoneyset_fee ='$repaymoneysetfee',
					   fd_repaymoneyset_arrivetime='$arrivetime',fd_repaymoneyset_moneyqj='$moneyqj'
  		             where fd_repaymoneyset_id  = '$repaymoneysetid' ";
	      $db->query($query);
		  
	       echo ("<script>alert('�޸ĳɹ�!');location.href='$gotourl'</script>");
	    }   
			
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_repaymoneyset where fd_repaymoneyset_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$repaymoneysetid=$db->f(fd_repaymoneyset_id );
		 
		$bankid=$db->f(fd_repaymoneyset_bankid );
		$repaymoneysetfee=$db->f(fd_repaymoneyset_fee );
		$tradememo=$db->f(fd_repaymoneyset_tradememo);
		$arrivetime=$db->f(fd_repaymoneyset_arrivetime);
		$moneyqj=$db->f(fd_repaymoneyset_moneyqj);
		
		
		$checked=$repaymoneysetfee ? "": "checked ";
		$action="edit";
	}
}
$moneyqj = getmoneyqj(3,$moneyqj); 

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

$query="select * from  tb_arrive ";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$arr_arriveid[]=$db->f(fd_arrive_id);
		$arr_arrivename[]=$db->f(fd_arrive_name);
	}
}
$arrivetime = makeselect($arr_arrivename,$arrivetime,$arr_arriveid); 

$t->set_var("moneyqj"     , $moneyqj );
$t->set_var("checked"     , $checked     );
$t->set_var("repaymoneysetid"     , $repaymoneysetid     );
$t->set_var("bankname"     , $bankname );
$t->set_var("repaymoneysetfee"     , $repaymoneysetfee );
$t->set_var("tradememo"     , $tradememo );
$t->set_var("arrivetime"     , $arrivetime );

$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "repaymoneyset");    # ������ҳ��


?>

