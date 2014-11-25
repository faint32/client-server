<?
$thismenucode = "2k509";     
require("../include/common.inc.php");

$db=new db_test;

$gourl = "tb_slotcardmoneyreq_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "pass":     //编辑记录
		$query="update tb_slotcardmoneyreq set 
			fd_pmreq_state='9',fd_pmreq_qrdatetime='$qrdatetime', fd_pmreq_qrman='$qrman' ,fd_pmreq_memo='$memo',fd_pmreq_repmoney='$repmoney'
			where fd_pmreq_id=$listid";
		   $db->query($query);		
	    echo "<script>alert('已成功审批!');location.href='$gourl'</script>";
	      break;
		case "nopass":     //编辑记录
		$query="update tb_slotcardmoneyreq set 
			fd_pmreq_state='1',fd_pmreq_qrdatetime='$qrdatetime', fd_pmreq_qrman='$qrman' ,fd_pmreq_memo='$memo'
			where fd_pmreq_id=$listid";
		   $db->query($query);
	    echo "<script>alert('已确定审批不通过!');location.href='$gourl'</script>";
	      break;	  
}
      	   

$t = new Template(".","keep");
$t->set_file("slotcardmoneyreq_sp","slotcardmoneyreq_sp.html");

	 // 编辑
    $query = "select * from tb_slotcardmoneyreq 
			left join tb_author on fd_author_id = fd_pmreq_authorid 
	        left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid			
            where fd_pmreq_id ='$listid'" ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
			$arrdata['reqno']             = $db->f(fd_pmreq_reqno);               //编号  
			$arrdata['truename']      = $db->f(fd_author_truename); 
			$arrdata['paymsetname']         = $db->f(fd_scdmset_name); 			
			$arrdata['scope']	    = $db->f(fd_scdmset_scope);
			$arrdata['nallmoney']	= $db->f(fd_scdmset_nallmoney);
			$arrdata['sallmoney']	= $db->f(fd_scdmset_sallmoney);
			$arrdata['reqmoney']    = $db->f(fd_pmreq_reqmoney);
			$arrdata['reqdatetime'] = $db->f(fd_pmreq_reqdatetime);
			$arrdata['memo']        = $db->f(fd_pmreq_memo);
			$arrdata['qrdatetime']  =date("Y-m-d H:i:s");
			$arrdata['qrman']       =$loginstaname;
			$authorid               = $db->f(fd_pmreq_authorid); 			
			$arrdata['usemoney']=getusemoney($authorid);
			$arrdata['restmoney']=getrestmoney($authorid);	 
			if($arrdata['scope']=="creditcard"){$arrdata['scope']="信用卡";}	 
			if($arrdata['scope']=="bankcard"){$arrdata['scope']="储蓄卡";}	 
		
		
  }  	

$t->set_var("listid"        ,$listid         );
$t->set_var($arrdata);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "slotcardmoneyreq_sp"); //最后输出界面

?>