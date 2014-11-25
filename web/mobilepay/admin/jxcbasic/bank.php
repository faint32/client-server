<?
$thismenucode = "2k106";
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
		$query="select * from tb_bank where fd_bank_name='$bankname' ";
		$db->query($query);
		if($db->nf())
		{
			$error="银行已存在!";
		}else{
			$query="INSERT INTO tb_bank (
 		            fd_bank_name   ,fd_bank_time ,fd_bank_rates,fd_bank_bear, fd_bank_no ,fd_bank_facilityfremarks,
					fd_bank_sendmesage ,	fd_bank_smsphone  ,fd_bank_activemobilesms,fd_bank_active
  		          )VALUES (
  		          '$bankname'     ,now() ,'$bankrates' ,'$bankbear','$bankno' ,'$facilityfremarks' ,
				  '$sendmesage'   ,'$smsphone'  , '$activemobilesms','$active'
				)";
		    $db->query($query);
			Header("Location:$gotourl");
		}
	case "edit":   // 修改记录	
  
	$query = "select * from tb_bank where (fd_bank_name = '$bankname' ) and fd_bank_id <> '$bankid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "银行名不能重复,请查证!";  
		  }else{
         $query = "update tb_bank set
 		               fd_bank_name   = '$bankname'  ,fd_bank_rates='$bankrates' ,fd_bank_bear='$bankbear' , fd_bank_no = '$bankno',
					   fd_bank_facilityfremarks='$facilityfremarks' ,fd_bank_sendmesage ='$sendmesage' , fd_bank_smsphone='$smsphone',
					   fd_bank_activemobilesms='$activemobilesms', fd_bank_active='$active'
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
		$bankno  = $db->f(fd_bank_no);
		$active  = $db->f(fd_bank_active);
		$sendmesage  = $db->f(fd_bank_sendmesage);
		$smsphone  = $db->f(fd_bank_smsphone);
		$activemobilesms  = $db->f(fd_bank_activemobilesms);
		$facilityfremarks  = $db->f(fd_bank_facilityfremarks);
		if($active){
			
			$checked1 = "checked";
		}else{
			
			$checked2 = "checked";
		}
		$action="edit";
	}
}
$arr_id=array(0,1);
$arr_name=array("否","是");
$activemobilesms=makeselect($arr_name,$activemobilesms,$arr_id);
$t->set_var("bankid"           , $bankid          );
$t->set_var("active"           , $active          );
$t->set_var("bankno"           , $bankno          );
$t->set_var("bankname"         , $bankname        );
$t->set_var("bankrates"        , $bankrates       );
$t->set_var("activemobilesms"  , $activemobilesms );
$t->set_var("sendmesage"       , $sendmesage      );
$t->set_var("smsphone"         , $smsphone        );
$t->set_var("facilityfremarks" ,$facilityfremarks );
$t->set_var("bankbear"         , $bankbear        );
$t->set_var("checked1"           , $checked1); 
$t->set_var("checked2"           , $checked2); 
$t->set_var("error"            , $error           );
$t->set_var("action"           , $action          );
$t->set_var("gotourl"          , $gotourl         );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "bank");    # 最后输出页面


?>

