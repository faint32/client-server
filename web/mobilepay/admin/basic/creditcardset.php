<?
$thismenucode = "2n103";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("creditcardset","creditcardset.html"); 
$gourl = "tb_creditcardset_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // �޸ļ�¼	
 
		$query = "delete from tb_creditcardset  where fd_creditcset_id = '$creditcsetid'";

		$db->query($query);
		echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_creditcardset where fd_creditcset_bankid='$bankid'";
		$db->query($query);
		if($db->nf())
		{
			$error="�����Ѵ���!";
		}else{
			$query="INSERT INTO tb_creditcardset (
 		            fd_creditcset_bankid,fd_creditcset_fee,fd_creditcset_arrivetime,fd_creditcset_feetype ,fd_creditcset_moneyqj
  		          )VALUES (
  		          '$bankid','$creditcsetfee','$creditcsetmode','%'   ,'$moneyqj	'  
				)";
		   $db->query($query);
			
		  echo ("<script>alert('����ɹ�!');location.href='$gotourl'</script>");
		}
		break;
	case "edit":   // �޸ļ�¼	
	
	$query = "select * from tb_creditcardset where fd_creditcset_bankid = '$bankid' and fd_creditcset_id <> '$creditcsetid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "�����������ظ�,���֤!";  
		  }else{
		  
		  echo $creditcsetmode;
         $query = "update tb_creditcardset set
 		               fd_creditcset_bankid   = '$bankid'  ,fd_creditcset_fee='$creditcsetfee',
					   fd_creditcset_arrivetime='$creditcsetmode',fd_creditcset_moneyqj='$moneyqj'
  		             where fd_creditcset_id = '$creditcsetid' ";
	      $db->query($query);
		  
	       echo ("<script>alert('�޸ĳɹ�!');location.href='$gotourl'</script>"); 
	    }   
			
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_creditcardset where fd_creditcset_id='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$creditcsetid=$db->f(fd_creditcset_id);
		 
		$bankid=$db->f(fd_creditcset_bankid);
		$creditcsetfee=$db->f(fd_creditcset_fee);
		$creditcsetmode=$db->f(fd_creditcset_arrivetime);
		$moneyqj=$db->f(fd_creditcset_moneyqj);
		
		$checked=$creditcsetfee ? "": "checked ";
		
		$action="edit";
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


$moneyqj = getmoneyqj(1,$moneyqj); 


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
$creditcsetmode = makeselect($arr_arrivename,$creditcsetmode,$arr_arriveid); 


$t->set_var("checked"     , $checked     );
$t->set_var("creditcsetid"     , $creditcsetid     );
$t->set_var("bankname"     , $bankname );
$t->set_var("creditcsetfee"     , $creditcsetfee );
$t->set_var("creditcsetmode"     , $creditcsetmode );
$t->set_var("moneyqj"     , $moneyqj );

$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "creditcardset");    # ������ҳ��


?>

