<?
$thismenucode = "2k510";     
require("../include/common.inc.php");

$db=new db_test;

$gourl = "tb_slotcardmoneyreq_h_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

      	   

$t = new Template(".","keep");
$t->set_file("slotcardmoneyreq_h","slotcardmoneyreq_h.html");

	 // �༭
    $query = "select * from tb_slotcardmoneyreq 
			left join tb_author on fd_author_id = fd_pmreq_authorid 
	        left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid			
            where fd_pmreq_id ='$listid'" ;
			
    $db->query($query);
    if($db->nf()){                                            //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	      $db->next_record();                                   //��ȡ��¼���� 
			$arrdata['reqno']             = $db->f(fd_pmreq_reqno);               //���  
			$arrdata['truename']      = $db->f(fd_author_truename); 
			$arrdata['paymsetname']         = $db->f(fd_scdmset_name); 			
			$arrdata['scope']	      = $db->f(fd_scdmset_scope);
			$arrdata['nallmoney']	       = $db->f(fd_scdmset_nallmoney);
			$arrdata['sallmoney']	   = $db->f(fd_scdmset_sallmoney);
			$arrdata['reqmoney']      = $db->f(fd_pmreq_reqmoney);
			$arrdata['reqdatetime']    = $db->f(fd_pmreq_reqdatetime);
			$arrdata['memo']          = $db->f(fd_pmreq_memo);
			$arrdata['qrdatetime']    = $db->f(fd_pmreq_qrdatetime);
			$arrdata['qrman']         = $db->f(fd_pmreq_qrman);
			$arrdata['repmoney']      = $db->f(fd_pmreq_repmoney);	 
			if($arrdata['scope']=="creditcard"){$arrdata['scope']="���ÿ�";}	 
			if($arrdata['scope']=="bankcard"){$arrdata['scope']="���";}	 
		

		
  }  	

$t->set_var("listid"        ,$listid         );
$t->set_var($arrdata);
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);
// �ж�Ȩ�� 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "slotcardmoneyreq_h"); //����������

?>