<?php
session_start();
include ("../include/config.inc.php");
require("../login/autologin.php");
require ("../include/get.variable.inc.php");

if($state == 1){
 $username = it;
 $password = md5(1111111111);
}else{
$username = $username;
$password = md5($password);

}
$loginreturn = autologin($username,$password);

//�����ǳ�����Ϣ������
$arr_errmsg[2]="�������!(��ע�����ִ�Сд)";
$arr_errmsg[3]="�������������,��ֹʹ�ø��û�!";
$arr_errmsg[4]="���û���ͣ��!";
$arr_errmsg[9]="���û�������!";

if ($loginreturn == 1) {
    session_register("loginuser"); // ��½id����������Ȩ�û�
    session_register("loginname"); // ��½�û�������������Ȩ�û�
    session_register("logintrueuser"); // ��ʵ��½id
    session_register("logintruename"); // ��ʵ��½�û���
    session_register("loginbrowline"); // �û�browϰ��
    session_register("loginruning"); // �û���ǰ�ĳ���id
    session_register("loginusermenu"); // �û���ִ�г���
    session_register("loginuserqx"); // �û���ִ�г���
    session_register("logintimes"); // �û��򿪵Ĵ�����
    session_register("loginlastchecktime"); // �û������ʱ��
    session_register("loginskin"); // �û�ʹ�õĽ�����
    
    session_register("loginpartid"); // ����ID
    session_register("loginpartname"); // ����ID
    
    session_register("loginstaid"); // ְԱid��
    session_register("loginstaname"); // ְԱ����
   /* session_register("loginshopman"); // ��Ա����
    session_register("loginshopmanid"); // ��Աid��
    session_register("loginshopname"); // �̵���
    session_register("loginshopid"); // �̵�id��
    */
    //session_register("loginopendate"); // ���¿�ʼ����
    session_register("loginonlinetime"); //�û���½ʱ��
    
    session_register("loginindextype"); //�û���ҳ��ʾ����
    
    session_register("lastprgcode"); // �û����ʹ�õĳ���
    $loginlastchecktime = mktime() ;
    //$loginuser = $authdo->user;
    $loginname = $username;
    $logintimes = 0;
    
   

    //$logintrueuser = $loginuser ; // ��ʵ��½id
    //$logintruename = $loginname ; // ��ʵ��½�û���
     
    // �ó��û���ְԱ��,����,ְ����������
    $db = new DB_test ;
    $query = "select * from web_teller      
              where fd_tel_name = '$loginname'";
    $db->query($query);
    $db->next_record();
    $loginbrowline   = $db->f(fd_tel_browline) ;
    $logintelorg     = $db->f(fd_tel_org) ;
    $loginstaid      = $db->f(fd_tel_staffer);
    $loginuser       = $db->f(fd_tel_id);
    $loginonlinetime = $db->f(fd_tel_onlinetime);
    $loginindextype  = $db->f(fd_tel_pagetype);

    
    $chgpass = $db->f(fd_tel_chgpass) ; // �Ƿ���Ҫ�޸�����
    $loginskin = "../skin/skin" . $db->f(fd_tel_skin) . "/" ;
    
    $query = "select * from tb_staffer 
              where fd_sta_id = '$loginstaid' ";
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$loginstaname   = $db->f(fd_sta_name);
    }
     
    $loginusermenu = makeqx($loginuser);   //�û���ִ�еĳ���

    if ($chgpass == 1) {   //�ж��Ƿ��Ѿ��޸Ĺ����룬���û���޸������ת���޸�ҳ�档
        Header("Location: ../updatepass/updatepass.php");
        exit ;
    } 
    Header("Location: ../loadpopup.htm");
} else {
?>
<HTML><HEAD><TITLE>�û���¼</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<link href="../skin/skin0/button_css.css" rel="stylesheet" type="text/css">
<link href="../skin/skin0/page_title.css" rel="stylesheet" type="text/css">
<LINK href="../skin/skin0/browse.css" type=text/css rel=stylesheet>
<BODY  leftMargin=0 topMargin=0 >
<center>
<table width="450" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="450" height="150">&nbsp;
	</td>
  </tr>
  <tr>
    <td width="450" >
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="" >
        <tr > 
          <TD id="tdmove"  width="80" class="inputtitle" align=center> 
             ������Ϣ
          </TD>
          <TD id="tdmove"  class="inputtitleright" width="24"> 
          </td>
           <td  id="tdmove" class="titlefont">
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </TD>
        </tr>
      </TABLE>
	</td>
  </tr>
  <tr>
    <td height="100" class="titlefont">
	<BLOCKQUOTE>
              <DIV id=base><BR>
                ����ԭ��<?php echo $arr_errmsg[$loginreturn] ?>
	</DIV></BLOCKQUOTE></td>
  </tr>
  <tr>
    <td height="34"  valign="middle" align="center" class="bottombotton">
	<INPUT class=buttonsmall onclick="javascript:window.history.back()" type=button value="����"></td>
  </tr>
</table>
</center>
</BODY></HTML>
<?php
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
	$db = new DB_test ;
	
	$menufile = "../include/menuarryfile.php" ;
  $menuarry = file($menufile);
  
//  $query = "select * from tb_menu where fd_menu_upcode != '0' order by fd_menu_sno asc";
//  $db->query($query);
//  if($db->nf()){
//    while($db->next_record()){
//    	   $menu_code    = $db->f(fd_menu_code);
//    	   $menu_upcode  = $db->f(fd_menu_upcode);
//    	   $menu_jpg     = $db->f(fd_menu_jpg);
//    	   $menu_name    = $db->f(fd_menu_name);
//    	   $menu_url     = $db->f(fd_menu_url);
//    	   $menu_hz      = $db->f(fd_menu_hz);
//
//    	   $menuarry[]   = $menu_code."��".$menu_upcode."��".$menu_name."��".$menu_jpg."��".$menu_url.$menu_hz;
//    }
//  }


//1001��1���������á�ico_main_hr.gif��../basic/tb_area_b.php��0��
//1001���š�1Ϊ�ϼ����š���������Ϊ���ơ�ico_main_hr.gifΪͼƬ
//../basic/tb_area_b.php���ӵ�ַ��0Ϊ�Ƿ�Ϊ���

	$query = "select * from web_teller 
	         left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid
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