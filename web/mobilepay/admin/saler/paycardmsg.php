<?
$thismenucode = "2n004";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("paycardmsg","paycardmsg.html"); 
$gourl = "paycard.php";

$gotourl = $gourl.$tempurl ;

/* switch ($action)
{	
	case "delete":   // 修改记录	
 
		$query = "delete from tb_paycardactivelist  where fd_repaymoneyset_id  = '$repaymoneysetid'";

		$db->query($query);
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
    	break;	
	case "new":
		$query="select * from tb_paycardactivelist where fd_repaymoneyset_bankid ='$bankid'";
		$db->query($query);
		if($db->nf())
		{
			$error="银行已存在!";
		}else{
			$query="INSERT INTO tb_paycardactivelist (
 		            fd_repaymoneyset_bankid ,fd_repaymoneyset_fee,fd_repaymoneyset_tradememo,fd_repaymoneyset_feetype,fd_repaymoneyset_arrivetime,fd_repaymoneyset_moneyqj
  		          )VALUES (
  		          '$bankid','$repaymoneysetfee','$tradememo','%','$arrivetime' ,'$moneyqj'
				)";
		   $db->query($query);
			//echo $query;
			Header("Location:$gotourl");
		}
		break;
	case "edit":   // 修改记录	
	
	$query = "select * from tb_paycardactivelist where fd_repaymoneyset_bankid = '$bankid' and fd_repaymoneyset_id  <> '$repaymoneysetid'";
      $db->query($query);
      if($db->nf()){  
      	$error = "银行名不能重复,请查证!";  
		  }else{
         $query = "update tb_paycardactivelist set
 		               fd_repaymoneyset_bankid   = '$bankid'  ,fd_repaymoneyset_fee ='$repaymoneysetfee',
					   fd_repaymoneyset_arrivetime='$arrivetime',fd_repaymoneyset_moneyqj='$moneyqj'
  		             where fd_repaymoneyset_id  = '$repaymoneysetid' ";
	      $db->query($query);
		  
	      Header("Location: $gotourl");	  
	    }   
			
		break;
} */

	$query="select * from tb_paycard 
			left join tb_saler on fd_saler_id = fd_paycard_salerid
			left join tb_bank on  fd_bank_id = fd_paycard_bankid
			left join tb_author on fd_paycard_authorid=fd_author_id  
			where fd_paycard_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$arr_data['bankname']=$db->f(fd_bank_name);
		$arr_data['paycardno']=$db->f(fd_paycard_no);
		$arr_data['paycardtype']=$db->f(fd_paycard_type);
		$arr_data['activedate']=$db->f(fd_pcdactive_activedate);
		$arr_data['salertruename']=$db->f(fd_saler_truename);
		$arr_data['active']= $db->f(fd_paycard_active);
		$arr_data['datetime']= $db->f(fd_paycard_datetime);
        $arr_data['isnew']= $db->f(fd_paycard_isnew);
		$arr_data['authortruename']=$db->f(fd_author_truename);		
		$arr_data['mobile']= $db->f(fd_author_mobile);
	if($arr_data['active']==1)
	  {
		$arr_data['active']="激活";
	  }else{
		$arr_data['active']="未激活";
	  }
		$arr_data['isnew']=$arr_data['isnew'] ? "是": "否";
	}



$t->set_var($arr_data );


$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "paycardmsg");    # 最后输出页面


?>

