<?
$thismenucode = "2n106";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("bank","bank.html"); 
$gourl = "tb_bank_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // 修改记录	
 
		$query = "delete from tb_bank  where fd_bank_id = '$bankid'";
		$db->query($query);
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_bank where fd_bank_name='$bankname'";
		$db->query($query);
		if($db->nf())
		{
			$error="银行已存在!";
		}else{
			$query="INSERT INTO tb_bank (
 		            fd_bank_name   ,fd_bank_time ,fd_bank_rates,fd_bank_bear
  		          )VALUES (
  		          '$bankname'     ,now() ,'$bankrates' ,'$bankbear'
				)";
		    $db->query($query);
			Header("Location:$gotourl");
		}
	case "edit":   // 修改记录	
  
	$query = "select * from tb_bank where fd_bank_name = '$bankname' and fd_bank_id <> '$bankid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "银行名不能重复,请查证!";  
		  }else{
         $query = "update tb_bank set
 		               fd_bank_name   = '$bankname'  ,fd_bank_rates='$bankrates' ,fd_bank_bear='$bankbear'
  		             where fd_bank_id = '$bankid' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
			
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_bank where fd_bank_id='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$bankid=$db->f(fd_bank_id);
		$bankname=$db->f(fd_bank_name);
		$bankrates=$db->f(fd_bank_rates);
		$bankbear=$db->f(fd_bank_bear);
		$action="edit";
	}
}
$t->set_var("bankid"     , $bankid     );
$t->set_var("bankname"     , $bankname );
$t->set_var("bankrates"     , $bankrates );
$t->set_var("bankbear"     , $bankbear );
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "bank");    # 最后输出页面


?>

