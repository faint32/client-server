<?
$thismenucode = "2k109";
require ("../include/common.inc.php");

$db = new DB_test;
$db1 = new DB_test;

if($dimission != 2 && $type != 4){
  $gourl = "tb_staffer_b.php" ;
  $dimissiondate = "";
}

if($status == 2){
	$gourl = "tb_dimission_b.php" ;
}

if($status == 3){
	$gourl = "tb_retire_b.php" ;
}

if($dimission == 2){
	$gourl = "tb_dimission_b.php" ;
}


if($type == 4){
	$gourl = "tb_retire_b.php" ;
}

$gotourl = $gourl.$tempurl ;

require("../include/alledit.1.php");


switch ($action)
{
  case "delete":   // ��¼ɾ��
     $query="delete from tb_staffer where fd_sta_id='$id'";
     $db->query($query);
     require("../include/alledit.2.php");
     Header("Location: $gotourl");       
	break;
  case "new":   
        //�����¼        
      //include ("../autono/staffautono.php");
      $query = "select * from tb_staffer where fd_sta_stano = '$stano'";
      $db->query($query);
      if($db->nf()){  
      	$error = "Ա����Ų����ظ�,���֤!";  
      }else{           
        $query="INSERT INTO tb_staffer(
 		            fd_sta_stano           , fd_sta_name            , fd_sta_ename         , 
 		            fd_sta_deptid          , fd_sta_group           , fd_sta_duty          ,
 		            fd_sta_driver          , fd_sta_worktype        , fd_sta_birthday      ,
 		            fd_sta_degree          , fd_sta_zhichengid      , fd_sta_homememo      ,
 		            fd_sta_english         , fd_sta_photo           , fd_sta_idcard        ,
 		            fd_sta_nativeplace     , fd_sta_hkcity          , fd_sta_homeplace     ,
 		            fd_sta_nation          , fd_sta_polity          , fd_sta_nationality   ,
 		            fd_sta_faith           , fd_sta_bind            , fd_sta_caseplace     ,
 		            fd_sta_filesorg        , fd_sta_marriage        , fd_sta_consortname   ,
 		            fd_sta_degreexw        , fd_sta_jobtime         , fd_sta_rehire        ,
 		            fd_sta_dimissiondate   , fd_sta_nbemail         , fd_sta_jobchannel    ,
 		            fd_sta_dimimemo        , fd_sta_address         , fd_sta_postalcode    ,
 		            fd_sta_phone           , fd_sta_companyphone    , fd_sta_mobile        ,
 		            fd_sta_wbemail         , fd_sta_otherlink       , fd_sta_school        ,
 		            fd_sta_specialtytype   , fd_sta_worktime        , fd_sta_beginjobtime  ,   
 		            fd_sta_studyexperience , fd_sta_workexperience        ,
 		            fd_sta_weight          , fd_sta_tall            , fd_sta_sex           , 
 		            fd_sta_dimission       , fd_sta_nowaddress	    , fd_sta_out           ,
 		            fd_sta_type            , fd_sta_statusid        , fd_sta_agencyid      
  		          )VALUES (
  		          '$stano'               , '$name'                , '$ename'             , 
 		            '$deptid'              , '$group'               , '$duty'              ,
 		            '$driver'              , '$worktype'            , '$birthday'          ,
 		            '$degree'              , '$zhichengid'          , '$homememo'          ,
 		            '$english'             , '$picpath'             , '$idcard'            ,
 		            '$nativeplace'         , '$hkcity'              , '$homeplace'         ,
 		            '$nation'              , '$polity'              , '$nationality'       ,
 		            '$faith'               , '$bind'                , '$caseplace'         ,
 		            '$filesorg'            , '$marriage'            , '$consortname'       ,
 		            '$degreexw'            , '$jobtime'             , '$rehire'            ,
 		            '$dimissiondate'       , '$nbemail'             , '$jobchannel'        ,
 		            '$dimimemo'            , '$address'             , '$postalcode'        ,
 		            '$phone'               , '$companyphone'        , '$mobile'            ,
 		            '$wbemail'             , '$otherlink'           , '$school'            ,
 		            '$specialtytype'       , '$worktime'            , '$beginjobtime'      ,   
 		            '$studyexperience'     , '$workexperience'               ,
 		            '$weight'              , '$tall'                , '$sex'               , 
 		            '$dimission'           , '$nowaddress'  		    , '$out'               ,
 		            '$type'                , '$statusid'            , '$loginorganid'
                )";   
		      $db->query($query);
		   	  require("../include/alledit.2.php");    		     
		     Header("Location: $gotourl");
	     }
	$action="";	   	
	break;
  case "edit":   // �޸ļ�¼
      $query = "select * from tb_staffer where fd_sta_stano = '$stano' and fd_sta_id != '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "Ա����Ų����ظ�,���֤!";  
      }else{   

         $query = "update tb_staffer set
 		              fd_sta_stano            = '$stano'           , fd_sta_name            = '$name'           , fd_sta_ename         = '$ename'          , 
 		              fd_sta_deptid           = '$deptid'          , fd_sta_group           = '$group'          , fd_sta_duty          = '$duty'           ,
 		              fd_sta_driver           = '$driver'          , fd_sta_worktype        = '$worktype'       , fd_sta_birthday      = '$birthday'       ,
 		              fd_sta_degree           = '$degree'          , fd_sta_zhichengid      = '$zhichengid'     , fd_sta_homememo      = '$homememo'       ,
 		              fd_sta_english          = '$english'         , fd_sta_photo           = '$picpath'        , fd_sta_idcard        = '$idcard'         ,
 		              fd_sta_nativeplace      = '$nativeplace'     , fd_sta_hkcity          = '$hkcity'         , fd_sta_homeplace     = '$homeplace'      ,
 		              fd_sta_nation           = '$nation'          , fd_sta_polity          = '$polity'         , fd_sta_nationality   = '$nationality'    ,
 		              fd_sta_faith            = '$faith'           , fd_sta_bind            = '$bind'           , fd_sta_caseplace     = '$caseplace'      ,
 		              fd_sta_filesorg         = '$filesorg'        , fd_sta_marriage        = '$marriage'       , fd_sta_consortname   = '$consortname'    ,
 		              fd_sta_degreexw         = '$degreexw'        , fd_sta_jobtime         = '$jobtime'        , fd_sta_rehire        = '$rehire'         ,
 		              fd_sta_dimissiondate    = '$dimissiondate'   , fd_sta_nbemail         = '$nbemail'        , fd_sta_jobchannel    = '$jobchannel'     ,
 		              fd_sta_dimimemo         = '$dimimemo'        , fd_sta_address         = '$address'        , fd_sta_postalcode    = '$postalcode'     ,
 		              fd_sta_phone            = '$phone'           , fd_sta_companyphone    = '$companyphone'   , fd_sta_mobile        = '$mobile'         ,
 		              fd_sta_wbemail          = '$wbemail'         , fd_sta_otherlink       = '$otherlink'      , fd_sta_school        = '$school'         ,
 		              fd_sta_specialtytype    = '$specialtytype'   , fd_sta_worktime        = '$worktime'       , fd_sta_beginjobtime  = '$beginjobtime'   ,   
 		              fd_sta_studyexperience  = '$studyexperience' , fd_sta_workexperience  = '$workexperience'  ,
 		              fd_sta_weight           = '$weight'          , fd_sta_tall            = '$tall'           , fd_sta_sex           = '$sex'            ,
 		              fd_sta_dimission        = '$dimission'       , fd_sta_nowaddress      = '$nowaddress'     , fd_sta_out           = '$out'            ,
 		              fd_sta_type             = '$type'            , fd_sta_statusid        = '$statusid' 		             
  		            where fd_sta_id = '$id' ";
	      $db->query($query);
	      

	      
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");	  
	      
	   }
	   $action="";
    	break;
      default:
	  $action="";
      break;
}
// $action;
//echo $query;	
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("staffer","staffer.html"); 

if (empty($id))
{		// ����
   $action = "new";
   
   //���ɳ�������
   $byear    = date( "Y", mktime())-18 ;   
   $bmonth   = date( "m", mktime()) ;   
   $bday     = date( "d", mktime()) ;  
   
   //������ְʱ�� 
   $jyear    = date( "Y", mktime()) ;   
   $jmonth   = date( "m", mktime()) ;   
   $jday     = date( "d", mktime()) ;  
   
   //������ְʱ��
   $dyear    = date( "Y", mktime()) ;    
   $dmonth   = date( "m", mktime()) ;   
   $dday     = date( "d", mktime()) ;   
   
   //���ɿ�ʼ����ʱ��
   $bjyear   = date( "Y", mktime()) ;   
   $bjmonth   = date( "m", mktime()) ;   	
   $bjday   = date( "d", mktime()) ;   
   $display1 = "none";
   $display2 = "";
   //�Զ�����Ա�����
   //include ("../autono/staffautono.php");
} 
else
{			// �༭
  $query = "select * from tb_staffer where fd_sta_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
      $db->next_record();                                 //��ȡ��¼���� 
      $id              = $db->f(fd_sta_id);                  //id�� 
      $staffno         = $db->f(fd_sta_stano);               //���  
      $name            = $db->f(fd_sta_name);                //���� 
      $ename           = $db->f(fd_sta_ename);               //Ӣ���� 
      $sex             = $db->f(fd_sta_sex);                 //�Ա� 
      $deptid          = $db->f(fd_sta_deptid);              //��������       
      $duty            = $db->f(fd_sta_duty);                //ְλ 
      $driver          = $db->f(fd_sta_driver);              //��ʻִ��
   	  $worktype        = $db->f(fd_sta_worktype);            //��������
   	  $birthday        = $db->f(fd_sta_birthday);            //��������
   	  $degree          = $db->f(fd_sta_degree);              //ѧ��
   	  $group           = $db->f(fd_sta_group);               //�������     
   	  $zhichengid      = $db->f(fd_sta_zhichengid);          //ְ�� 
   	  $homememo        = $db->f(fd_sta_homememo);            //��ͥ���
   	  $english         = $db->f(fd_sta_english);             //Ӣ������
   	  $picpath         = $db->f(fd_sta_photo);               //ͼƬ��ַ
   	  $idcard          = $db->f(fd_sta_idcard);              //���֤
   	  $nativeplace     = $db->f(fd_sta_nativeplace);         //����
   	  $hkcity          = $db->f(fd_sta_hkcity);              //�������ڳ���
   	  $homeplace       = $db->f(fd_sta_homeplace);           //������
   	  $nation          = $db->f(fd_sta_nation);              //����
   	  $polity          = $db->f(fd_sta_polity);              //������ò
   	  $nationality     = $db->f(fd_sta_nationality);         //����       
   	  $faith           = $db->f(fd_sta_faith);               //�ڽ�����
   	  $bind            = $db->f(fd_sta_bind);                //�Ƿ��й�
   	  $caseplace       = $db->f(fd_sta_caseplace);           //��������
   	  $filesorg        = $db->f(fd_sta_filesorg);            //�йܻ���
   	  $marriage        = $db->f(fd_sta_marriage);            //����״��
   	  $consortname     = $db->f(fd_sta_consortname);         //��ż���� 
   	  $homememo        = $db->f(fd_sta_homememo);            //��ͥ��� 
   	  $degreexw        = $db->f(fd_sta_degreexw);            //ѧλ   
   	  $jobtime         = $db->f(fd_sta_jobtime);             //��ְʱ��
   	  $rehire          = $db->f(fd_sta_rehire);              //����¼�� 
   	  $dimissiondate   = $db->f(fd_sta_dimissiondate);       //��ְ����
   	  $nbemail         = $db->f(fd_sta_nbemail);             //��˾�ڲ�EMAIL 
   	  $jobchannel      = $db->f(fd_sta_jobchannel);          //��ְ����
   	  $dimimemo        = $db->f(fd_sta_dimimemo);            //��ְԭ��  
   	  $address         = $db->f(fd_sta_address);             //��ϵ��ַ
   	  $postalcode      = $db->f(fd_sta_postalcode);          //��������
   	  $phone           = $db->f(fd_sta_phone);               //סլ�绰
   	  $companyphone    = $db->f(fd_sta_companyphone);        //��λ�绰
   	  $mobile          = $db->f(fd_sta_mobile);              //�ֻ�
   	  $wbemail         = $db->f(fd_sta_wbemail);             //����EMAIL
   	  $otherlink       = $db->f(fd_sta_otherlink);           //������ϵ��ʽ
   	  $school          = $db->f(fd_sta_school);              //��ҵԺУ 
   	  $specialtytype   = $db->f(fd_sta_specialtytype);       //רҵ���
   	  $worktime        = $db->f(fd_sta_worktime);            //��������  
   	  $beginjobtime    = $db->f(fd_sta_beginjobtime);        //��ʼ����ʱ��
   	  $studyexperience = $db->f(fd_sta_studyexperience);     //ѧϰ����
   	  $workexperience  = $db->f(fd_sta_workexperience);      //��������   
   	  $account        = $db->f(fd_sta_account);            //��������
      $weight          = $db->f(fd_sta_weight);              //����
      $tall            = $db->f(fd_sta_tall);                //���
      $dimission       = $db->f(fd_sta_dimission);           //�Ƿ���ְ
      $nowaddress      = $db->f(fd_sta_nowaddress);          //��ס��ַ
      $out             = $db->f(fd_sta_out);                 //�Ƿ���Ƹ  
      $type            = $db->f(fd_sta_type);                //Ա������ 
      $statusid        = $db->f(fd_sta_statusid);              //������λ 

      if($dimission == 1){      	
        $display1 = "none";
        $display2 = "";

      }else{
        $display1 = "";
        $display2 = "none";
      }
      
      //��ɢ��������
      $temp_arr=explode("-",$birthday);     
      $byear    =  $temp_arr[0]; 
      $bmonth   =  $temp_arr[1];
      $bday     =  $temp_arr[2]; 
      
      //��ɢ��ְʱ��
      $temp_arr=explode("-",$jobtime);     
      $jyear    =  $temp_arr[0]; 
      $jmonth   =  $temp_arr[1];
      $jday     =  $temp_arr[2]; 
      
      //��ɢ��ְʱ��      
      if($dimissiondate!="0000-00-00"){
        $temp_arr=explode("-",$dimissiondate);     
        $dyear    =  $temp_arr[0]; 
        $dmonth   =  $temp_arr[1];
        $dday     =  $temp_arr[2]; 
      }
      
      
       
      //��ɢ��ʼ����ʱ��
      $temp_arr=explode("-",$beginjobtime);     
      $bjyear    =  $temp_arr[0]; 
      $bjmonth   =  $temp_arr[1];
      $bjday     =  $temp_arr[2];  
            
      $action = "edit";      	                     
  }
} 

if($status==1){
  $helptitle = "��ְԱ��";
  $help = "˵����<br><br>
           1������������ġ���֯���������������û����Ļ�����ְλ����λ��<br>&nbsp;&nbsp;&nbsp;��������<br><br>
           2��Ȼ����Ա������ġ�ְ�����ϡ�����<br><br>
           3�������Ŀ���ѡ��ϵͳ�̶������Ϻ��ֶ�����<br><br>
           4���ڡ��Ƿ���ְ�����ѡ����ְ������ְ����ѡ����ְ�����ϻ�<br>&nbsp;&nbsp;&nbsp;������ְԱ���ѡ����ְ���������Զ���������ְԱ��������<br>&nbsp;&nbsp;&nbsp;��<br><br>
           5����Ա���������С���ְ��������ͬ���������ٹ����������ݡ�,ѡ��<br>&nbsp;&nbsp;&nbsp;�����ݡ���������ϻ��Զ�����������Ա����������<br><br>
           6����*�Ǳ�������<br><br>
          ";
  $help_height = 460;        
}

if($status==2){
	$helptitle = "��ְԱ��";
	$help = "˵����<br><br>
           &nbsp;&nbsp;&nbsp;&nbsp;��ְԱ�������ǲ���ֱ��������ֻ����ʾ����ְԱ��������ġ���ְ��Ա������ʷ��¼������������ְԱ���Ľ���Ĳ������޸���ʷ��¼����ѡȡ����ְ��ʱ������ְ��Ա�ֻ���������ְԱ�����Ľ������ˡ�

          ";
  $help_height = 290;           
}

if($status==3){
	$helptitle = "����Ա��";
	$help = "˵����<br><br>ͬ������Ա�����͵�ѡȡ��ͬ�������ϻ�������Ӧ�Ľ�����.";
  $help_height = 260; 
}




//��ʾͼƬ
if(empty($picpath)){
	$pic = "��ʱû����Ƭ";
}else{
  $pic = "<img src=".$picpath." width=120 heigh=180>"; 
}

//�Ա������˵�
$arr_sex= array('��','Ů');
$arr_sex_val= array(1,2);
$sex = makeselect($arr_sex,$sex,$arr_sex_val); 

//�Ƿ���Ƹ�����˵�
$arr_out= array('��','��');
$arr_out_val= array(2,1);
$out = makeselect($arr_out,$out,$arr_out_val); 



//���ڲ��������˵�

  $query = "select * from tb_dept";     

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_dept[]= $db->f(fd_dept_name);
		$arr_deptid[]= $db->f(fd_dept_id);
	}
}  	
$deptid = makeselect($arr_dept,$deptid,$arr_deptid); 










//���������˵�
$arr_driver= array('A��','B��','C��','D��','E��');
$driver = makeselect($arr_driver,$driver,$arr_driver);

//�Ƿ���ְ�����˵�
$arr_dimission= array('��ְ','��ְ');
$arr_dimission_val= array(1,2);
$dimission = makeselect($arr_dimission,$dimission,$arr_dimission_val);

//�������������˵�
$arr_worktype= array('ȫְ','��ְ');
$arr_worktype_val= array(1,2);
$worktype = makeselect($arr_worktype,$worktype,$arr_worktype_val);

//��˾�й������˵�
$arr_bind= array('��','��');
$arr_bind_val= array(1,2);
$bind = makeselect($arr_bind,$bind,$arr_bind_val);

//Ա�����������˵�
$arr_type= array('ְ��','��ͬ��','�ٹ�','����','���ù�');
$arr_type_val= array(1,2,3,4,5);
$type = makeselect($arr_type,$type,$arr_type_val);


//���������˵�

$arr_nationality_name = array("�й�","����","Ӣ��","���ô�","�ձ�","������","�����","����","�ݿ�","����˹","���","�¹�","����","����","�¼���","̩��","��������");
$nationality = makeselect($arr_nationality_name,$nationality,$arr_nationality_name);
/*
$query = "select * from tb_country";               
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_nationality_name[]  = $db->f(fd_country_name);
		$arr_nationality_id[]    = $db->f(fd_country_id);
	}
}  	
$nationality = makeselect($arr_nationality_name,$nationality,$arr_nationality_id);
*/

////���������˵�
//if(!empty($nativeplace1)){
//  $query = "select * from tb_area where fd_area_fid='$nativeplace1'";               
//  $db->query($query);
//  if($db->nf()){
//  	while($db->next_record()){
//  		$arr_nativeplace2_name[]  = $db->f(fd_area_name);
//  		$arr_nativeplace2_id[]     = $db->f(fd_area_id);
//  	}
//  }  	
//  $nativeplace2 = makeselect($arr_nativeplace2_name,$nativeplace2,$arr_nativeplace2_id);
//}
//
//
////���������˵�
//$query = "select * from tb_area where fd_area_fid=''";               
//$db->query($query);
//if($db->nf()){
//	while($db->next_record()){
//		$arr_nativeplace1_name[]  = $db->f(fd_area_name);
//		$arr_nativeplace1_id[]        = $db->f(fd_area_id);
//	}
//}  	
//$nativeplace1 = makeselect($arr_nativeplace1_name,$nativeplace1,$arr_nativeplace1_id);
//
////�����������˵�
//if(!empty($nativeplace1)){
//  $query = "select * from tb_area where fd_area_fid='$homeplace1'";               
//  $db->query($query);
//  if($db->nf()){
//  	while($db->next_record()){
//  		$arr_homeplace2_name[]  = $db->f(fd_area_name);
//  		$arr_homeplace2_id[]    = $db->f(fd_area_id);
//  	}
//  }  	
//  $homeplace2 = makeselect($arr_homeplace2_name,$homeplace2,$arr_homeplace2_id);
//}
//
//
//
////�����������˵�
//$query = "select * from tb_area where fd_area_fid=''";               
//$db->query($query);
//if($db->nf()){
//	while($db->next_record()){
//		$arr_homeplace1_name[]  = $db->f(fd_area_name);
//		$arr_homeplace1_id[]        = $db->f(fd_area_id);
//	}
//}  	
//$homeplace1 = makeselect($arr_homeplace1_name,$homeplace1,$arr_homeplace1_id);
//
//
////���ڳ��������˵�
//if(!empty($hkcity1)){
//  $query = "select * from tb_area where fd_area_fid='$hkcity1'";               
//  $db->query($query);
//  if($db->nf()){
//  	while($db->next_record()){
//  		$arr_hkcity2_name[]  = $db->f(fd_area_name);
//  		$arr_hkcity2_id[]    = $db->f(fd_area_id);
//  	}
//  }  	
//  $hkcity2 = makeselect($arr_hkcity2_name,$hkcity2,$arr_hkcity2_id);
//}
//
//
////�������������˵�
//
//  $query = "select * from tb_area where fd_area_fid=''";               
//  $db->query($query);
//  if($db->nf()){
//  	while($db->next_record()){
//  		$arr_hkcity1_name[]  = $db->f(fd_area_name);
//  		$arr_hkcity1_id[]    = $db->f(fd_area_id);
//  	}
//  }  	
// $hkcity1 = makeselect($arr_hkcity1_name,$hkcity1,$arr_hkcity1_id);



//���������˵�                                        
$arr_nation = array('����','����','������','�ɹ���','����','����','ά�����','׳��','����','����','��������');          
$nation = makeselect($arr_nation,$nation,$arr_nation);


//������ò�����˵�
$arr_politics = array('�й���Ա','��Ա','Ⱥ��');
$polity = makeselect($arr_politics,$polity,$arr_politics);
   	
//ѧ�������˵�
$arr_degree = array('��ʿ','�о���','����','��ר','����','��ר','ְ��','����','Сѧ');
$degree = makeselect($arr_degree,$degree,$arr_degree);

//ѧλ�����˵�
$arr_degreexw = array("��ʿ��","��ʿ","˶ʿ","ѧʿ");
$degreexw = makeselect($arr_degreexw,$degreexw,$arr_degreexw);

//����¼�������˵�                                                
$arr_rehire     = array('��','��');  
$arr_rehire_val = array(1,2);                         
$rehire = makeselect($arr_rehire,$rehire,$arr_rehire_val);

 


$t->set_var("iframe"              ,  $iframe              );                 
$t->set_var("id"              ,  $id              );
$t->set_var("staffno"         ,  $staffno         );
$t->set_var("name"            ,  $name            );
$t->set_var("ename"           ,  $ename           );
$t->set_var("sex"             ,  $sex             );
$t->set_var("deptid"          ,  $deptid          );
$t->set_var("duty"            ,  $duty            );
$t->set_var("driver"          ,  $driver          );
$t->set_var("worktype"        ,  $worktype        );
$t->set_var("birthday"        ,  $birthday        );
$t->set_var("degree"          ,  $degree          );
$t->set_var("group"           ,  $group           );
$t->set_var("zhichengid"      ,  $zhichengid      );
$t->set_var("homememo"        ,  $homememo        );
$t->set_var("english"         ,  $english         );
$t->set_var("picpath"         ,  $picpath         );
$t->set_var("idcard"          ,  $idcard          );
$t->set_var("nativeplace"     ,  $nativeplace     );
$t->set_var("homeplace"       ,  $homeplace       );
$t->set_var("nation"          ,  $nation          );
$t->set_var("polity"          ,  $polity          );
$t->set_var("nationality"     ,  $nationality     );
$t->set_var("faith"           ,  $faith           );
$t->set_var("bind"            ,  $bind            );
$t->set_var("caseplace"       ,  $caseplace       );
$t->set_var("filesorg"        ,  $filesorg        );
$t->set_var("marriage"        ,  $marriage        );
$t->set_var("consortname"     ,  $consortname     );
$t->set_var("homememo"        ,  $homememo        );
$t->set_var("degreexw"        ,  $degreexw        );
$t->set_var("jobtime"         ,  $jobtime         );
$t->set_var("rehire"          ,  $rehire          );
$t->set_var("dimissiondat"    ,  $dimissiondat    );
$t->set_var("nbemail"         ,  $nbemail         );
$t->set_var("jobchannel"      ,  $jobchannel      );
$t->set_var("dimimemo"        ,  $dimimemo        );
$t->set_var("address"         ,  $address         );
$t->set_var("postalcode"      ,  $postalcode      );
$t->set_var("phone"           ,  $phone           );
$t->set_var("companyphone"    ,  $companyphone    );
$t->set_var("mobile"          ,  $mobile          );
$t->set_var("wbemail"         ,  $wbemail         );
$t->set_var("otherlink"       ,  $otherlink       );
$t->set_var("school"          ,  $school          );
$t->set_var("specialtytype"   ,  $specialtytype   );
$t->set_var("worktime"        ,  $worktime        );
$t->set_var("beginjobtime"    ,  $beginjobtime    );
$t->set_var("studyexperience" ,  $studyexperience );
$t->set_var("workexperience"  ,  $workexperience  );
$t->set_var("agencyid"        ,  $agencyid        );
$t->set_var("weight"          ,  $weight          );
$t->set_var("tall"            ,  $tall            );
$t->set_var("tall"            ,  $tall            );
$t->set_var("dimission"       ,  $dimission       );
$t->set_var("byear"           ,  $byear           );
$t->set_var("bmonth"          ,  $bmonth          );
$t->set_var("bday"            ,  $bday            );
$t->set_var("jyear"           ,  $jyear           );
$t->set_var("jmonth"          ,  $jmonth          );        
$t->set_var("jday"            ,  $jday            );    
$t->set_var("dyear"           ,  $dyear           );
$t->set_var("dmonth"          ,  $dmonth          );        
$t->set_var("dday"            ,  $dday            );      
$t->set_var("bjyear"          ,  $bjyear          );      
$t->set_var("bjmonth"         ,  $bjmonth         );      
$t->set_var("bjday"           ,  $bjday           );     
$t->set_var("hkcity"          ,  $hkcity          );                                                                                                              
$t->set_var("caseplace"       ,  $caseplace       );                                                        
$t->set_var("display1"        ,  $display1        );   
$t->set_var("display2"        ,  $display2        );    
$t->set_var("dimissiondate"   ,  $dimissiondate   );        
$t->set_var("pic"             ,  $pic             );    
$t->set_var("nowaddress"      ,  $nowaddress      );
$t->set_var("out"             ,  $out             );
$t->set_var("type"            ,  $type            );
$t->set_var("account"        ,  $account        );
$t->set_var("helptitle"       ,  $helptitle       );
$t->set_var("help"            ,  $help            );
$t->set_var("help_height"     ,  $help_height     );

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ              
$t->set_var("error"        , $error        );        

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "staffer");    # ������ҳ��


?>

