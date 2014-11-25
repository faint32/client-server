<?
$thismenucode = "2n801";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("mobilerechamoney","mobilerechamoney.html"); 
$gourl = "tb_mobilerechamoney_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // 修改记录
		$query = "delete from tb_mobilerechamoney  where fd_recham_id = '$mobilerechamoneyid'";
		$db->query($query);
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_mobilerechamoney where fd_recham_money='$mobilerechamoneymoney'";
		$db->query($query);
		if($db->nf())
		{
			$error="面额已存在!";
		}else{
			$query="INSERT INTO tb_mobilerechamoney (
 		            fd_recham_money    ,fd_recham_addtime ,fd_recham_paymoney,
 		            fd_recham_isdefault,fd_recham_memo,fd_recham_costmoney
  		          )VALUES (
  		          '$mobilerechamoneymoney'     ,now() ,'$mobilerechamoneypaymoney' ,'$mobilerechamoneyisdefault','$memo','$costmoney'
				)";
		    $db->query($query);
			Header("Location:$gotourl");
		}
	case "edit":   // 修改记录	
  
	$query = "select * from tb_mobilerechamoney where fd_recham_money = '$mobilerechamoneymoney' and fd_recham_id <> '$mobilerechamoneyid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "面额名不能重复,请查证!";  
		  }else{
         $query = "update tb_mobilerechamoney set
 		               fd_recham_money   = '$mobilerechamoneymoney'  ,fd_recham_paymoney='$mobilerechamoneypaymoney' ,fd_recham_isdefault='$mobilerechamoneyisdefault',fd_recham_memo = '$memo',fd_recham_costmoney = '$costmoney'
  		             where fd_recham_id = '$mobilerechamoneyid' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
			
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_mobilerechamoney where fd_recham_id='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$mobilerechamoneyid=$db->f(fd_recham_id);
		$mobilerechamoneymoney=$db->f(fd_recham_money);
		$mobilerechamoneypaymoney=$db->f(fd_recham_paymoney);
		$mobilerechamoneyisdefault=$db->f(fd_recham_isdefault);
        $mobilerechamoneymemo=$db->f(fd_recham_memo);
        $mobilerechamoneyactive=$db->f(fd_recham_active);
        $costmoney  = $db->f(fd_recham_costmoney);
		$action="edit";
	}
}


$arr_activeid=array("0","1");
$arr_activename=array("未激活","已激活");
$mobilerechamoneyactive=makeselect($arr_activename,$mobilerechamoneyactive,$arr_activeid);

$arr_defaultid=array("0","1");
$arr_defaultname=array("否","默认");
$mobilerechamoneyisdefault=makeselect($arr_defaultname,$mobilerechamoneyisdefault,$arr_defaultid);


$t->set_var("costmoney"        , $costmoney     );
$t->set_var("mobilerechamoneyisdefault"        , $mobilerechamoneyisdefault     );
$t->set_var("mobilerechamoneyactive"        , $mobilerechamoneyactive     );
$t->set_var("mobilerechamoneyid"            , $mobilerechamoneyid     );
$t->set_var("mobilerechamoneymoney"         , $mobilerechamoneymoney );
$t->set_var("mobilerechamoneypaymoney"     , $mobilerechamoneypaymoney );
$t->set_var("mobilerechamoneyisdefault"     , $mobilerechamoneyisdefault );
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "mobilerechamoney");    # 最后输出页面


?>

