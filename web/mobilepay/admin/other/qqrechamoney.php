<?
$thismenucode = "2n901";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("qqrechamoney","qqrechamoney.html"); 
$gourl = "tb_qqrechamoney_b.php";

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action)
{	
	case "delete":   // 修改记录	
 
		$query = "delete from tb_qqrechamoney  where fd_qqrecham_id = '$qqrechamoneyid'";
		$db->query($query);
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_qqrechamoney where fd_qqrecham_money='$qqrechamoneymoney'";
		$db->query($query);
		if($db->nf())
		{
			$error="面额已存在!";
		}else{
			$query="INSERT INTO tb_qqrechamoney (
 		            fd_qqrecham_money    ,fd_qqrecham_addtime ,fd_qqrecham_paymoney,
 		            fd_qqrecham_isdefault,fd_qqrecham_memo,fd_qqrecham_costmoney
  		          )VALUES (
  		          '$qqrechamoneymoney'     ,now() ,'$qqrechamoneypaymoney' ,'$qqrechamoneyisdefault','$memo','$costmoney'
				)";
		    $db->query($query);
			Header("Location:$gotourl");
		}
	case "edit":   // 修改记录	
  
	$query = "select * from tb_qqrechamoney where fd_qqrecham_money = '$qqrechamoneymoney' and fd_qqrecham_id <> '$qqrechamoneyid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "面额名不能重复,请查证!";  
		  }else{
         $query = "update tb_qqrechamoney set
 		               fd_qqrecham_money   = '$qqrechamoneymoney'  ,fd_qqrecham_paymoney='$qqrechamoneypaymoney' ,fd_qqrecham_isdefault='$qqrechamoneyisdefault',fd_qqrecham_memo = '$memo', fd_qqrecham_costmoney = '$costmoney'
  		             where fd_qqrecham_id = '$qqrechamoneyid' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
			
		break;
}
if(empty($listid))
{
	$action="new";
}else{
	$query="select * from tb_qqrechamoney where fd_qqrecham_id='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$qqrechamoneyid=$db->f(fd_qqrecham_id);
		$qqrechamoneymoney=$db->f(fd_qqrecham_money);
		$qqrechamoneypaymoney=$db->f(fd_qqrecham_paymoney);
		$qqrechamoneyisdefault=$db->f(fd_qqrecham_isdefault);
        $qqrechamoneymemo=$db->f(fd_qqrecham_memo);
        $qqrechamoneyactive=$db->f(fd_qqrecham_active);
        $costmoney =$db->f(fd_qqrecham_costmoney);
		$action="edit";
	}
}


$arr_activeid=array("0","1");
$arr_activename=array("未激活","已激活");
$qqrechamoneyactive=makeselect($arr_activename,$qqrechamoneyactive,$arr_activeid);

$arr_defaultid=array("0","1");
$arr_defaultname=array("否","默认");
$qqrechamoneyisdefault=makeselect($arr_defaultname,$qqrechamoneyisdefault,$arr_defaultid);

$t->set_var("costmoney"    , $costmoney     );
$t->set_var("qqrechamoneyisdefault"    , $qqrechamoneyisdefault     );
$t->set_var("qqrechamoneyactive"        , $qqrechamoneyactive     );
$t->set_var("qqrechamoneyid"            , $qqrechamoneyid     );
$t->set_var("qqrechamoneymoney"         , $qqrechamoneymoney );
$t->set_var("qqrechamoneypaymoney"     , $qqrechamoneypaymoney );
$t->set_var("qqrechamoneyisdefault"     , $qqrechamoneyisdefault );
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "qqrechamoney");    # 最后输出页面


?>

