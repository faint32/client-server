<?
header('P3P: CP=CAO PSA OUR'); 
ob_start();
session_start();
include ("../include/config.inc.php");

//------------------�������session--------------------
session_unregister("find_whatdofind"); 
session_unregister("find_howdofind");  
session_unregister("find_findwhat"); 
unset($find_whatdofind);
unset($find_howdofind);
unset($find_findwhat);

session_unregister("thismenucode"); 
unset($thismenucode);
//------------------�������session--------------------

session_register("loginuser");          // ��½id����������Ȩ�û�
session_register("loginname");          // ��½�û�������������Ȩ�û�
session_register("logintrueuser");      // ��ʵ��½id
session_register("logintruename");      // ��ʵ��½�û���
session_register("loginbrowline");      // �û�browϰ��
session_register("loginruning");        // �û���ǰ�ĳ���id
session_register("loginusermenu");      // �û���ִ�г���
session_register("loginuserqx");        // �û���ִ�г���
session_register("logintimes");         // �û��򿪵Ĵ�����
session_register("loginlastchecktime"); // �û������ʱ��
session_register("loginskin");          // �û�ʹ�õĽ�����
session_register("loginoutputfield");   //excel�����ֶ�

session_register("loginstaid");         // ְԱid��
session_register("loginstaname");       // ְԱ����

session_register("loginopendate");      // ���¿�ʼ����
session_register("loginonlinetime");    //�û���½ʱ��

session_register("loginshoworganid");   //�鿴����������Ȩ��
session_register("arr_loginshoworgan"); //�鿴����������Ȩ��
session_register("loginbacktype");      //��������ת����
session_register("loginorganid");       //��������
session_register("loginorganno");       //�����������
session_register("loginorganname");     //������������
session_register("loginorgantype");     //������������
session_register("loginsdcrid");        //������������id��
session_register("loginstorageid");     //�����ֿ�id��
session_register("loginmscompanyid");   //������˾id��

session_register("loginisinvestor");    //�Ƿ�ɶ��û�
session_register("loginissuper");       //�Ƿ񳬼��û�
session_register("loginareaid");        //����Ƭ��
session_register("loginisshowarea");    //�Ƿ���Ȩ�鿴����
session_register("loginishavezb");      //�Ƿ�����ܲ�����


// �ó��û���ְԱ��,����,ְ����������
$db = new erp_test ;
$query = "select * from tb_teller 
          left join tb_usegroup on fd_usegroup_id = fd_tel_usegroupid
          left join tb_staffer on fd_sta_id = fd_tel_staffer
          where fd_tel_id = '$teller_userid'";
$db->query($query);
if($db->nf()){
  $db->next_record();
  $loginbrowline   = $db->f(fd_tel_browline);
  $loginstaid      = $db->f(fd_tel_staffer);
  $loginuser       = $db->f(fd_tel_id);
  $loginname       = $db->f(fd_tel_name);
  $loginonlinetime = $db->f(fd_tel_onlinetime);
  $loginindextype  = $db->f(fd_tel_pagetype);
  $loginorganid    = $db->f(fd_tel_organid);       //��������
  $loginstorageid  = $db->f(fd_tel_storageid);     //�����ֿ�
  $loginsdcrid     = $db->f(fd_tel_sdcrid);        //������������
  $showorganid     = $db->f(fd_tel_showorganqx);   //�鿴�ķ���
  $loginbacktype   = $db->f(fd_tel_isbackbrowse);  //��������ת����
  $usegrouptype    = $db->f(fd_usegroup_type);     //�����û�������
  $loginareaid     = $db->f(fb_tel_areaid);        //����Ƭ��
  $loginmscompanyid= $db->f(fd_tel_mscompanyid);   //������˾
  $loginisshowarea = $db->f(fd_tel_isshowarea);    //�Ƿ���Ȩ�鿴����    
  $loginishavezb   = $db->f(fd_tel_ishavezb);      //�Ƿ�����ܲ�   
  $loginstaname    = $db->f(fd_sta_name); 
  
  $loginskin       = "../skin/skin" . $db->f(fd_tel_skin) . "/" ;  
  
  $loginshoworganid = fun_showorgan($showorganid);  //�鿴����Ȩ��
  $arr_loginshoworgan = fun_showorganid($showorganid);  //�鿴����Ȩ��
  
  if($usegrouptype ==0){
  	if($link_issuper==0){
  		$loginissuper   = $link_issuper;
      $loginorganid   = $link_organid;    //��������
      $loginorganno   = $link_organno;    //�����������
      $loginorganname = $link_organname;  //������������
      $loginorgantype = $link_organtype;  //������������
      $loginsdcrid    = $link_sdcrid;     //������������
  	}else{
  	  $loginissuper = 1 ;     //�ǳ����û�
  	  $loginorganno   = ""; //�����������
      $loginorganname = ""; //������������
      $loginorgantype = ""; //������������
    }
  }else{
    $loginissuper = 0;      //���ǳ����û�
    $query = "select fd_agency_no , fd_agency_easyname , fd_agency_type
              from tb_agency where fd_agency_id = '$loginorganid' ";
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$loginorganno   = $db->f(fd_agency_no);       //�����������
    	$loginorganname = $db->f(fd_agency_easyname); //������������
    	$loginorgantype = $db->f(fd_agency_type);     //������������
    }
  }
  if($link_sdcrid>0){
  	$loginsdcrid    = $link_sdcrid;     //������������
  }
  $loginusermenu = makeqx($loginuser);   //�û���ִ�еĳ���
  //print_r($loginusermenu);
}

$gotourl = $pagename;
Header("Location: $gotourl");

//�ҳ����е�Ȩ��
function fun_showorgan($showorganid){
	$arr_organid = explode("��",$showorganid);
	for($i=0;$i<count($arr_organid);$i++){
		$tmporganid = $arr_organid[$i];
		$arr_tmpqx[$tmporganid]=1;
	}
	return $arr_tmpqx;
}

//�ҳ����е�Ȩ��
function fun_showorganid($showorganid){
	$arr_organid = explode("��",$showorganid);
	for($i=0;$i<count($arr_organid);$i++){
		$tmporganid = $arr_organid[$i];
		if($tmporganid!=""){
	  	$arr_tmpqx[]=$tmporganid;
	  }
	}
	return $arr_tmpqx;
}

//�ҳ����е�Ȩ��
function muqx($qxfp_arr,$arr_prgfcode){
	    
	while(list($key,$val)=@each($qxfp_arr)){
		$arr_tmpqx[$key][item]=1;  //������Ȩ��
		$level = $arr_prgfcode[$key][level];
		$i=$level;
		$code = $key;
		while($i>0){
			$code = $arr_prgfcode[$code][fcode];
			$arr_tmpqx[$code][item]=1;  //�ù��ܵ���һ��Ȩ��
			if($arr_prgfcode[$code][level]==1){
				$i=1;
			}
			$i--;
		}
	}
	return $arr_tmpqx;
}

function makeqx($loginuser){
	global $loginuserqx;
	$db = new erp_test ;
	
	$menufile = "http://www.papersystem.cn/ms2011/include/menuarryfile.php" ;
  $menuarry = file($menufile);

//1001��1���������á�ico_main_hr.gif��../basic/tb_area_b.php��0��
//1001���š�1Ϊ�ϼ����š���������Ϊ���ơ�ico_main_hr.gifΪͼƬ
//../basic/tb_area_b.php���ӵ�ַ��0Ϊ�Ƿ�Ϊ���

	$query = "select * from tb_teller 
	         left join tb_usegroup on fd_usegroup_id = fd_tel_usegroupid
	         where fd_tel_id = '$loginuser'";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
  	$str_quanxian = $db->f(fd_tel_doprg);  //���û���ӵ�е�Ȩ��
  	$str_groupqx  = $db->f(fd_usegroup_qx);  //��������Ȩ��

  	if(!empty($str_groupqx)){   //��Ȩ��
  			$tmp_gorupqx = explode("��",$str_groupqx);
  			for($i=0;$i<count($tmp_gorupqx);$i++){
  				 $tmp_zqxfp = explode("@",$tmp_gorupqx[$i]);
  		     $zqxfp=$tmp_zqxfp[0];
  		     $arr_qxfp[$zqxfp]=1;   //��ִ�г���
  		     
  		     if(!empty($tmp_zqxfp[1])){   //��ִ��Ȩ��
	  			    $str_optqx=explode("^",$tmp_zqxfp[1]);    //��һ����������ȹ���
	  			    for($k=0;$k<count($str_optqx);$k++){
	  			     	$tmpoptqx = $str_optqx[$k];      //������Ȩ�޴���
	  				    $loginuserqx[$zqxfp][$tmpoptqx]=1;    //��ִ��Ȩ��
	  			    }
	  		   }
  	    }
    }
    if(!empty($str_quanxian)){   //��Ȩ��
  			$tmp_gorupqx = explode("��",$str_quanxian);
  			for($i=0;$i<count($tmp_gorupqx);$i++){
  				 $tmp_zqxfp = explode("@",$tmp_gorupqx[$i]);
  		     $zqxfp=$tmp_zqxfp[0];
  		     $arr_qxfp[$zqxfp]=1;   //��ִ�г���
  		     
  		     if(!empty($tmp_zqxfp[1])){   //��ִ��Ȩ��
	  			    $str_optqx=explode("^",$tmp_zqxfp[1]);    //��һ����������ȹ���
	  			    for($k=0;$k<count($str_optqx);$k++){
	  			     	$tmpoptqx = $str_optqx[$k];      //������Ȩ�޴���
	  				    $loginuserqx[$zqxfp][$tmpoptqx]=1;    //��ִ��Ȩ��
	  			    }
	  		   }
  	    }
    }
  	$temp_arrqx = explode("��",$str_quanxian);   
  }
  for($i=0;$i<count($temp_arrqx);$i++){
  	$tmp_qxfp = explode("@",$temp_arrqx[$i]);
  	$qxfp=$tmp_qxfp[0];
  	$arr_qxfp[$qxfp]=1;
  }

//---------------------------------------
  for($i=0;$i<count($menuarry);$i++){
	   $temp_arr1 = $menuarry[$i] ;
    	
     $temp_arr2 = explode("��",$temp_arr1);
     $tmpcode = $temp_arr2[0];
     if(empty($temp_arr2[1])){
			   $arr_prgfcode[$tmpcode][fcode] = 0 ;
	   }else{
			   $arr_prgfcode[$tmpcode][fcode] = $temp_arr2[1];
	   }
	   $arr_prgfcode[$tmpcode][isunder] = $temp_arr2[5];
    $arr_prgfcode[$tmpcode][level] = $temp_arr2[6];
   }//-------------------------------------

   //�ҳ����еĳ���
   $arr_tmpqx = muqx($arr_qxfp,$arr_prgfcode);
   
   return $arr_tmpqx;
}
?>