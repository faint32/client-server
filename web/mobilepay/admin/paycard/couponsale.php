<?
$thismenucode = "2n302";     
require("../include/common.inc.php");
require("../function/changecouponglide.php");

$db=new db_test;
$gourl = "tb_couponsale_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "pass":     //�༭��¼

		  $query="update tb_couponsale set 
						 fd_couponsale_state='9'            ,  fd_couponsale_czman='$loginstaname'     , 
						 fd_couponsale_czdatetime=now() 		,  fd_couponsale_memo='$memo'		
						 where fd_couponsale_id ='$listid'"; 
		  $db->query($query);
		  
		  
		    $query = "select fd_couponsale_id,fd_couponsale_no,
	            fd_couponsale_money,fd_couponsale_paycardid,
	            fd_couponsale_authorid,fd_couponsale_couponno
	            from tb_couponsale
               where fd_couponsale_id ='$listid'
             " ;
    $db->query($query);
    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      $db->next_record();                                   //��ȡ��¼���� 
	      $listid       = $db->f(fd_couponsale_id); 
	      $no       = $db->f(fd_couponsale_no);               //���  
       	$couponno       = $db->f(fd_couponsale_couponno);             //������              
        $money       = $db->f(fd_couponsale_money);   
		    $paycardid		   = $db->f(fd_couponsale_paycardid); 
		    $authorid	 = $db->f(fd_couponsale_authorid);
		    $memo = "�����Ż݄�".$couponno."���ѽ��".$money."Ԫ";
		    changecoupon($listid,$no,"sale",$money,$couponno,$authorid,$paycardid,$memo);
	    }
		  
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("couponsale","couponsale.html");

if(!empty($listid)){
	
	 // �༭
    $query = "select fd_couponsale_id,
					fd_couponsale_no,
					fd_couponsale_bkntno,
					fd_couponsale_datetime,
					fd_couponsale_creditcardno,
					fd_couponsale_creditcardbank,
					fd_couponsale_creditcardman,
					fd_couponsale_creditcardphone,
	            	fd_couponsale_money,
					fd_paycard_no,
					fd_author_username,
					fd_author_truename,
	            	fd_author_idcard,
					fd_couponsale_couponno,
					fd_couponsale_isagentpay,
					fd_couponsale_agentdate
	            from tb_couponsale
	            left join tb_paycard on fd_couponsale_paycardid = fd_paycard_id
	            left join tb_author on fd_author_id = fd_couponsale_authorid
               where fd_couponsale_id ='$listid'
             " ;
    $db->query($query);
    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      $db->next_record();                                   //��ȡ��¼���� 
       	$arrdata['no']       = $db->f(fd_couponsale_no);               //���  
       	$arrdata['couponno']       = $db->f(fd_couponsale_couponno);             //������   
		$arrdata['bkntno']       = $db->f(fd_couponsale_bkntno); 
				
			$arrdata['money']       = $db->f(fd_couponsale_money);   
		    $arrdata['paycardno']		   = $db->f(fd_paycard_no); 
		    $arrdata['authorusername']	 = $db->f(fd_author_username);
		    $arrdata['authorname']	   = $db->f(fd_author_truename);
		    $arrdata['idcard']		   = $db->f(fd_author_idcard);
		    $arrdata['datetime']	 = $db->f(fd_couponsale_datetime);    
			$arrdata['creditcardno']	 = $db->f(fd_couponsale_creditcardno);   
			$arrdata['creditcardbank']	 = $db->f(fd_couponsale_creditcardbank);   
			$arrdata['creditcardman']	 = $db->f(fd_couponsale_creditcardman);   
			$arrdata['creditcardphone']	 = $db->f(fd_couponsale_creditcardphone); 
			$arrdata['payrq']	   = $db->f(fd_couponsale_payrq);
			$arrdata['agentdate']      = $db->f(fd_couponsale_agentdate);
			if($arrdata['agentdate']=="0000-00-00 00:00:00"){
				$arrdata['agentdate']="-";
			}else{
				$arrdata['agentdate']=date("Y-m-d H:i:s",mktime($arrdata['agentdate']));
			}
			$arrdata['isagentpay']      = $db->f(fd_couponsale_isagentpay);
			if($arrdata['isagentpay']==1)
			{
				$arrdata['isagentpay']="��";
				$tj="
				<input type=button name=save3 class=button_save value='����' AccessKey=s  onClick='javascript:submit_save()' style='font-size:9pt'  onMouseOver='this.className='button_save_on'' onMouseOut='this.className='button_save''>";
			}else{
				$arrdata['isagentpay']="��";
				$tj="";
				}
			if($arrdata['payrq']==01)
			{
				$arrdata['payrq']="������";
			}elseif($arrdata['payrq']==00){
				$arrdata['payrq']="�������";
			}else{
				$arrdata['payrq']="����ȡ��";
			}  
  }  	
}

$t->set_var($arrdata);
$t->set_var("listid"          ,$listid         );
$t->set_var("tj"        ,$tj         );
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "couponsale"); //����������

?>