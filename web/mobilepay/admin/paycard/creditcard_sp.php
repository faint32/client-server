<?
$thismenucode = "2n501";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
if($type=="check")
{
	$gourl = "tb_creditcard_h_b.php" ;
	$titlename="���ÿ�����֧����ʷ";
}elseif($type=="error"){
	$gourl = "tb_creditcard_error_b.php" ;
	$titlename="���ÿ�����֧���쳣";
}else{
	$gourl = "tb_creditcard_sp_b.php" ;
	$titlename="���ÿ�����֧������";
	$note = "<div style='margin-top:10px; font-size:12px; color:#060'>ע�⣺����������û�����ǰ�������ύ��ˣ�����ͨ�����������븶����ʵ��ݣ�</div>";
}
if($gotype=="sp"){
	$gourl = "tb_creditcard_sp_b.php" ;
	$titlename="���ÿ�����֧���쳣";	
	}

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
//,fd_ccglist_sendsms='$sendsms',fd_ccglist_shoucardmemo='$shoucardmemo'		
switch ($action){  
	case "pass":     //�༭��¼
		$query="update tb_creditcardglist set
		fd_ccglist_state='$isok',fd_ccglist_qrdatetime=now(), fd_ccglist_qrman='$loginstaname' 
		where fd_ccglist_id=$listid";
		   $db->query($query);
		   $query1="select * from tb_creditcardglist 
		   left join tb_paycard on fd_ccglist_paycardid = fd_paycard_id
		   where fd_ccglist_id=$listid ";
		   $db->query($query1);
		   while($db->next_record())
		   {
			$paycardid   = $db->f(fd_paycard_no);
			$memo	     = $db->f(fd_ccglist_memo);
			$paymode	 = $db->f(fd_ccglist_arrivemode);
			$payfee	     = $db->f(fd_ccglist_payfee);
		   }
		   	changeaccountno('in',$payfee,$paycardid,$memo,$paymode,'tb_creditcardglist',$listid);
		  
	    echo "<script>alert('�ѳɹ�ȷ��ת��!');location.href='$gotourl'</script>";
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("creditcard_sp","creditcard_sp.html");

if(!empty($listid)){
	$where = "fd_ccglist_id ='$listid'";
}
if(!empty($bkordernumber)){
	$where = "fd_ccglist_bkordernumber = '$bkordernumber' ";
}
//if(!empty($listid)){	
	 // �༭
    $query = "select * from tb_creditcardglist
	            left join tb_paycard on fd_ccglist_paycardid = fd_paycard_id
	            left join tb_author on fd_author_id = fd_ccglist_authorid
				left join tb_arrive on fd_arrive_id = fd_ccglist_arriveid
               where $where
             " ;
			 
    $db->query($query);
    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      $db->next_record();                                   //��ȡ��¼���� 
			$arrdata['no']             = $db->f(fd_ccglist_no);               //���  
			$arrdata['paycardno']      = $db->f(fd_paycard_no); 
			$arrdata['bkntno']         = $db->f(fd_ccglist_bkntno); 			
			$arrdata['authorname']	   = $db->f(fd_author_truename);
			$arrdata['paydate']	       = $db->f(fd_ccglist_paydate);
			
			$arrdata['shoucardno']	   = $db->f(fd_ccglist_shoucardno);
			$arrdata['shoucardbank']   = $db->f(fd_ccglist_shoucardbank);
			$arrdata['shoucardmobile'] = $db->f(fd_ccglist_shoucardmobile);
			$arrdata['shoucardman']    = $db->f(fd_ccglist_shoucardman);
			
		    $arrdata['fucardno']	   = $db->f(fd_ccglist_fucardno);
			$arrdata['fucardbank']	   = $db->f(fd_ccglist_fucardbank);
		    $arrdata['fucardman']	   = $db->f(fd_ccglist_fucardman);
			$arrdata['fucardmobile']   = $db->f(fd_ccglist_fucardmobile);			
			
			$arrdata['current']	       = $db->f(fd_ccglist_current);
			$arrdata['paymoney']	   = $db->f(fd_ccglist_paymoney);
			$arrdata['payfee']	       = $db->f(fd_ccglist_payfee);
			$arrdata['money']	       = $db->f(fd_ccglist_money);
			$arrdata['smsphone']	   = $db->f(fd_ccglist_smsphone);
			$arrdata['memo']	       = $db->f(fd_ccglist_memo);	
			$arrdata['arrivetime']	   = $db->f(fd_ccglist_arrivedate);
			$arrdata['payrq']	   = $db->f(fd_ccglist_payrq);
			//$arrdata['sendsms']	       = $db->f(fd_ccglist_sendsms);
		//	$arrdata['shoucardmemo']   = $db->f(fd_ccglist_shoucardmemo);
			$arrdata['arrvename']      = $db->f(fd_arrive_name);
			$arrdata['agentdate']      = $db->f(fd_ccglist_agentdate);
			if($arrdata['agentdate']=="0000-00-00 00:00:00"){
				$arrdata['agentdate']="-";
			}else{
				$arrdata['agentdate']=date("Y-m-d H:i:s",mktime($arrdata['agentdate']));
			}
			$arrdata['isagentpay']      = $db->f(fd_ccglist_isagentpay);
			if($arrdata['isagentpay']==1)
			{
				$arrdata['isagentpay']="��";
				if($type==""){
				$tj="
				<input type=button name=save3 class=button_save {isshow} value='�ύ' AccessKey=s  onClick='javascript:submit_save()' style='font-size:9pt'  onMouseOver='this.className='button_save_on'' onMouseOut='this.className='button_save''>";
				}
			}else{
				$arrdata['isagentpay']="��";
				$tj="";
				}			
			/* if($arrdata['shoucardmemo']==1)
			{
				$checksendsms1="checked";
			}
			if($arrdata['shoucardmemo']==0){
				$checksendsms0="checked";
			} */
			if($arrdata['payrq']==01)
			{
				$arrdata['payrq']="������";
			}elseif($arrdata['payrq']==00){
				$arrdata['payrq']="�������";
			}else{
				$arrdata['payrq']="����ȡ��";
			}
			if($type=="check")
			{
				$arrdata['qrman']	       = $db->f(fd_ccglist_qrman); 
				$arrdata['qrdatetime']	   = $db->f(fd_ccglist_qrdatetime);
				$checked="checked disabled";
				$isshow="style='display:none'";
			}else{
				$arrdata['qrdatetime']=date("Y-m-d H:i:s");
				$arrdata['qrman']=$loginstaname;
				 
			}
			if($type=="error")
			{
				$isshow="style='display:none'";
				$isshowqrmsg="style='display:none'";
			}

  }  	
//}
$t->set_var("isshowqrmsg"  ,$isshowqrmsg    );
$t->set_var("isshow"       ,$isshow         );
$t->set_var("check"        ,$checked         );
//$t->set_var("checksendsms1"        ,$checksendsms1         );
//$t->set_var("checksendsms0"        ,$checksendsms0         );
$t->set_var("listid"        ,$listid         );
$t->set_var("tj"        ,$tj         );
$t->set_var("note"        ,$note         );
$t->set_var($arrdata);

$t->set_var("titlename",$titlename);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "creditcard_sp"); //����������

?>