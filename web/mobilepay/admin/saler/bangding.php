<?
if(!$thismenucode)
{
	$thismenucode = "2n006";
}

require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("bangding","bangding.html"); 
if($url)
{
	$gourl='../basic/tb_'.$url.'_b.php';
}else{
	$gourl = "paycard.php";
}
$gotourl = $gourl.$tempurl ;
if($bangdingtype=="del")
{
	$listid=implode(",",$arr_list);
	$query="delete from tb_paycard where fd_paycard_id in($listid)";
	$db->query($query);
	echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
}

if($bangdingtype=="plinput")
{
	for($startpaycardid;$startpaycardid<=$endpaycardid;$startpaycardid++)
	{
		$query="insert into tb_paycard (fd_paycard_no) values('$startpaycardid')";
		$db->query($query);
	}
	
	echo ("<script>alert('����ɹ�!');location.href='$gotourl'</script>");
}


 switch ($action)
{	
	case "bank":   // �޸ļ�¼	
		
		$query="update  tb_paycard  set fd_paycard_bankid=$bangdingmode where fd_paycard_id in($listid)";
		$db->query($query);

		echo ("<script>alert('�󶨳ɹ�!');location.href='$gotourl'</script>");
	  
    	break;	
	case "saler":

		   if($url)
		   {
			$query="update tb_paycardactivelist  set fd_pcdactive_salerid=$bangdingmode where fd_pcdactive_id='$listid'";
		   }else{
			$query="update  tb_paycard set fd_paycard_salerid=$bangdingmode where fd_paycard_id in($listid)";
		   }
		   $db->query($query);
			echo ("<script>alert('�󶨳ɹ�!');location.href='$gotourl'</script>");
		
		break;


} 
if(!empty($arr_list))
{
	$listid=implode(",",$arr_list);
}


if($bangdingtype=="bank")
{
	
	$query="select * from tb_bank";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record())
		{
			$arr_selectcode[]=$db->f(fd_bank_id);
			$arr_selectname[]=$db->f(fd_bank_name);
		}
		$tdname="������";
	}
}else{
	$query="select * from tb_saler
		 left join tb_salerlevel on fd_salerlevel_id=fd_saler_level
	";
	$db->query($query);
	if($db->nf())
	{
		while($db->next_record()){
			$arr_selectcode[]=$db->f(fd_saler_id); 
			$truename=$db->f(fd_saler_truename);
			$phone=$db->f(fd_saler_phone);
			$level=$db->f(fd_salerlevel_name);
			$arr_selectname[]=$truename." ".$level." ��ϵ�绰:".$phone;
		}
		
	}
	$tdname="������";
}

$bangdingmode = makeselect($arr_selectname,$bangdingmode,$arr_selectcode); 




$t->set_var("bangdingmode"     , $bangdingmode );
$t->set_var("tdname"     , $tdname     );
$t->set_var("listid"     , $listid     );
$t->set_var("action"     , $bangdingtype     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "bangding");    # ������ҳ��


?>

