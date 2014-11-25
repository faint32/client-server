<?
$thismenucode = "2n003";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("transfermoneyset","transfermoneyset.html"); 
$gourl = "tb_transfermoneyset_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // 修改记录	
 
		$query = "delete from tb_transfermoneyset  where fd_transfermoneyset_id  = '$transfermoneysetid'";

		$db->query($query);
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_transfermoneyset where fd_transfermoneyset_bankid ='$bankid'";
		$db->query($query);
		if($db->nf())
		{
			$error="银行已存在!";
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
	case "edit":   // 修改记录	
	/* 
	$query = "select * from tb_transfermoneyset where fd_bankset_name = '$bankid' and fd_transfermoneyset_id  <> '$transfermoneysetid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "银行名不能重复,请查证!";  
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

$arr_modename=array("实时","T+1(自然日)","T+1(工作日)","T+2(工作日)","T+2(自然日)","T+3(工作日)");
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
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "transfermoneyset");    # 最后输出页面


?>

