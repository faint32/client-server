<?
$thismenucode = "2n304";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
$gourl = "tb_coupon_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){ 
	case "delete":
	$query="delete from tb_coupon where fd_coupon_id ='$listid' ";
	$db->query($query);	
	require("../include/alledit.2.php");
	echo "<script>alert('删除成功!');location.href='$gourl'</script>";
	break;
	case "new":     //编辑记录
	$no=makeorderno("tb_coupon","coupon","cou");
	$query="insert into tb_coupon (fd_coupon_no,fd_coupon_money,fd_coupon_active,fd_coupon_datetime,fd_coupon_memo,fd_coupon_limitnum)
			values
			('$no','$money' ,'$active',now(),'$memo','$limitnum')";
	$db->query($query);	  
	require("../include/alledit.2.php");
	echo "<script>alert('保存成功!');location.href='$gourl'</script>";
	break;
	case "edit":
	$query="update tb_coupon set fd_coupon_no='$no',fd_coupon_money='$money',fd_coupon_active='$active',fd_coupon_limitnum = '$limitnum',
fd_coupon_memo='$memo' where fd_coupon_id ='$listid' ";
	$db->query($query);	  
	require("../include/alledit.2.php");
	echo "<script>alert('修改成功!');location.href='$gourl'</script>";
	break;
}

$t = new Template(".","keep");
$t->set_file("coupon","coupon.html");

if(!empty($listid)){
	
	 // 编辑
    $query = "select *from tb_coupon  where fd_coupon_id ='$listid'" ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
       	$arrdata['no']           = $db->f(fd_coupon_no);               //编号   
		$arrdata['bkntno']       = $db->f(fd_coupon_bkntno); 
		$arrdata['money']        = $db->f(fd_coupon_money);   
		$arrdata['active']	     = $db->f(fd_coupon_active); 
		$arrdata['datetime']	 = $db->f(fd_coupon_datetime);
		$arrdata['memo']	 = $db->f(fd_coupon_memo);
        $arrdata['limitnum']	 = $db->f(fd_coupon_limitnum);

		if($arrdata['active']=="0")
		{
			$select0="checked";
		}
		if($arrdata['active']=="1")
		{
			$select1="checked";
		}		
  }
$action="edit";  
$editshow="style=display:block";
$defalut="";
}else{
	$action="new";
		$arrdata['no']       = ""; 
       	$arrdata['couponno'] = "";           
		$arrdata['bkntno']   = "";
		$arrdata['money']    =""; 
		$arrdata['active']	 =""; 
		$arrdata['datetime'] ="";
		$arrdata['memo']	 = "";
		$arrdata['active']   ="";
        $arrdata['limitnum']   ="";
	$isshow="style=display:none";	
	$editshow="style=display:none";
	$defalut="checked";
}

$t->set_var($arrdata);
$t->set_var("listid",$listid );
$t->set_var("defalut",$defalut );
$t->set_var("select0",$select0 );
$t->set_var("select1",$select1 );
$t->set_var("isshow",$isshow);
$t->set_var("editshow",$editshow);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "coupon"); //最后输出界面

?>