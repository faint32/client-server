<?
$thismenucode = "2k103"; 
require ("../include/common.inc.php");
$db = new DB_test;
$gourl = "tellerlist.php?listid=$listid" ;
$gotourl = $gourl.$tempurl ;



switch ($action){
	    case "del_all":   // ��¼ɾ��
		
	for($i=0;$i<count($checkid);$i++){
       if(!empty($checkid[$i])){
       	  $query = "delete from tb_cus_teller where  fd_tel_id= '$checkid[$i]'";
          $db->query($query);
       }
    }
       Header("Location: $gotourl?listid=$listid");       
	break;
    case "delete":   // ��¼ɾ��
       $query = "delete from tb_cus_teller where fd_tel_id = '$telid'";
       $db->query($query);
       Header("Location: $gotourl");       
	break;
    case "new":   // ���б༭��Ҫ�ṩ����
       $query = "select * from tb_cus_teller where fd_tel_name ='$telname' ";
	     $db->query($query);
       if($db->nf()>0){
	         $error = "���û����Ѿ����ڣ����������룡";
       }else{
           $pass = md5($GLOBALS["pass"]) ;
           $query="INSERT INTO tb_cus_teller(
 		               fd_tel_name      , fd_tel_pass       , fd_tel_error ,
 		               fd_tel_memo      , fd_tel_usegroupid , fd_tel_staffer ,
 		               fd_tel_recsts    , fd_tel_chgpass    , fd_tel_outtime ,
					   fd_tel_cusid
 		               )values(
  		             '$telname'     ,  '$pass'     , '$errornum' , 
  		             '$memo'     ,  '$selgroup' , '$staid'  ,
  		             '$recsts'   ,  '$chgpass'  , now()     ,
					 '$listid'		
  		             )";
	   $db->query($query);
	   Header("Location: $gotourl");	
	}
	break;
    case "edit":   // �޸ļ�¼
        if(!empty($pass) and !empty($mailpass)){
           $pass = md5($GLOBALS["pass"]) ;
           $mailpass = bin2hex($mailpass) ;                       
           $query = " update tb_cus_teller set
 	    	              fd_tel_name = '$telname'  , fd_tel_pass ='$pass'  , fd_tel_error ='$errornum'   ,
 	    	              fd_tel_memo  ='$memo'  , fd_tel_staffer = '$staid' , fd_tel_usegroupid = '$selgroup' , 
 	    	              fd_tel_recsts='$recsts' ,  fd_tel_chgpass ='$chgpass'   ,
 	    	              fd_tel_state='$state'
  	    	            where fd_tel_id='$telid' ";
  	       $db->query($query);
	         Header("Location: $gotourl");
    	     break;
        }else if(!empty($pass) and empty($mailpass)){
          $pass = md5($GLOBALS["pass"]) ;
          $query = " update tb_cus_teller set
 	    	           fd_tel_name = '$telname'  , fd_tel_pass ='$pass'  , fd_tel_error ='$errornum'   ,
 	    	           fd_tel_memo  ='$memo'  , fd_tel_staffer = '$staid' , fd_tel_usegroupid = '$selgroup' , 
 	    	           fd_tel_recsts='$recsts' ,  fd_tel_chgpass ='$chgpass' ,
 	    	           fd_tel_state='$state'
  	    	         where fd_tel_id='$telid'";
  	      $db->query($query);
	        Header("Location: $gotourl");
    	    break;	
    	  }else if(empty($pass) and !empty($mailpass)){
    	    $mailpass = bin2hex($mailpass) ;  
    	    $query = " update tb_cus_teller set
 	    	             fd_tel_name = '$telname'  , fd_tel_error ='$errornum'   ,
 	    	             fd_tel_memo  ='$memo'  , fd_tel_staffer = '$staid'   ,
 	    	             fd_tel_recsts='$recsts', fd_tel_chgpass ='$chgpass'  ,
 	    	             fd_tel_usegroupid = '$selgroup' 
  	    	           where fd_tel_id='$telid' ";  
  	    $db->query($query);
	    Header("Location: $gotourl");
    	    break;
    	 }else{
    	   $query = "update tb_cus_teller set
 	    	          fd_tel_name = '$telname'  , fd_tel_error ='$errornum'   ,
 	    	          fd_tel_memo  ='$memo'  , fd_tel_staffer = '$staid'   ,
 	    	          fd_tel_recsts='$recsts' , fd_tel_chgpass ='$chgpass' ,
 	    	          fd_tel_usegroupid = '$selgroup'  
  	    	        where fd_tel_id='$telid' ";
  	     $db->query($query);
	       Header("Location: $gotourl");
    	   break;  
    	 }
    default:
        break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("teller","teller.html"); 


if (!isset($telid)){		// ����
   $recsts = 0 ;
   $chgpass = 1 ;
   $checkterm = 1;
   $action = "new";
   
}else{			// �༭
   $query = "select * from tb_cus_teller 
             where fd_tel_id = '$telid'";
   $db->query($query);
	if($db->nf()){                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
	   $db->next_record();                           //��ȡ��¼���� 
       	   $telname         = $db->f(fd_tel_name) ;            //�û����� 
       	   $intime       = $db->f(fd_tel_intime);           //����ʱ��
       	   $outtime      = $db->f(fd_tel_outtime);          //�뿪ʱ��   
       	   $errornum     = $db->f(fd_tel_error) ;           //����������    
       	   $inip         = $db->f(fd_tel_inip);             //����ip
       	   $memo         = $db->f(fd_tel_memo) ;            //��ע        
       	   $recsts       = $db->f(fd_tel_recsts) ;          //�û�״̬ 
       	   $isin         = $db->f(fd_tel_isin);             //�Ƿ��½
       	   $partname     = $db->f(fd_part_name);            //��������  
       	   $area         = $db->f(fd_area_name);            //���� 
       	   $province     = $db->f(fd_province_name);        //ʡ��  
       	   $city         = $db->f(fd_city_name);            //����  
       	   $area         = $area.$province.$city;       	       	   
       	   $selgroup     = $db->f(fd_tel_usegroupid);       //�û�������
       	   
       	   
       	   
       	   
       	   
       	   if($isin ==1){ 
       	   	$isin = "��" ;
       	   }else{
       	   	$isin = "��" ;       	   	
           }

       	   $org          = $db->f(fd_tel_org) ;             //��������                
       	   $chgpass      = $db->f(fd_tel_chgpass);          //�޸�����
       	   $checkterm    = $db->f(fd_tel_checkterm);        //�����ն�
       	   $interm       = $db->f(fd_tel_term);             //���ʹ���ն�

       	   $termid       = $db->f(fd_tel_appointterm);      //ָ���ն�
       	   $staid        = $db->f(fd_tel_staffer);          //ְԱid��
       	   
       	   $query = "select * from tb_staffer where fd_sta_id = '$staid'";
   	       $db->query($query);
	         $db->next_record();
       	   $stano      = $db->f(fd_sta_stano) ;
       	   $staname    = $db->f(fd_sta_name) ;
       	   $action = "edit";
       	}
}
	   
           //����recstsѡ��
       	   $arr_recsts = array("����","��ͣ") ;
       	   $arr_valuerecsts = array("0","1") ;
       	   $recsts = makeselect($arr_recsts,$recsts,$arr_valuerecsts);

       	         	   
       	   
       	   //����chgpassѡ��
       	   $arr_chgpass = array("��Ҫ�޸�","����Ҫ�޸�") ;
       	   $arr_valuechgpass = array("1","0") ;
       	   $chgpass = makeselect($arr_chgpass,$chgpass,$arr_valuechgpass);

$query ="select * from web_usegroup";    
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_groupid[] = $db->f(fd_usegroup_id);
		$arr_group[] = $db->f(fd_usegroup_name);
	}
}   
$selgroup = makeselect($arr_group,$selgroup,$arr_groupid);

//����chgpassѡ��

if($loginuser ==1){
  $arr_state = array("��ͨ�û�","�߼��û�","�����û�") ;
  $arr_statevalue = array(0,1,2) ;
}else{
  $arr_state = array("��ͨ�û�","�߼��û�") ; 
  $arr_statevalue = array(0,1) ;  
}

$arr_statevalue = array(0,1,2) ;
$state = makeselect($arr_state,$state,$arr_statevalue);
    
$t->set_var("listid",$listid);           	   
$t->set_var("telid",$telid); 
$t->set_var("telname",$telname);
$t->set_var("intime",$intime);
$t->set_var("outtime",$outtime);
$t->set_var("errornum",$errornum); 
$t->set_var("inip",$inip);
$t->set_var("memo",$memo);
$t->set_var("recsts",$recsts); 
$t->set_var("isin",$isin);
$t->set_var("term",$term);              
$t->set_var("checkterm",$checkterm);              
$t->set_var("chgpass",$chgpass);
$t->set_var("staid",$staid);
$t->set_var("stano",$stano);             
$t->set_var("staname",$staname);             
$t->set_var("selgroup",$selgroup);

$t->set_var("partid"  ,$partid);
$t->set_var("partname",$partname);
$t->set_var("area"    ,$area);
$t->set_var("group"    ,$group);
$t->set_var("state"    ,$state);

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ
$t->set_var("error",$error);

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "teller");    # ������ҳ��



?>