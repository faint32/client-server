<?
$thismenucode = "2004";
require ("../include/common.inc.php");

$db = new DB_test;


if($dimission != 2 && $type != 3){
  $gourl = "tb_staffer_b.php" ;
  $dimissiondate = "";
}
if($dimission == 2){
	$gourl = "tb_dimission_b.php" ;
}

if($type == 3){
	$gourl = "tb_retire_b.php" ;
}

$gotourl = $gourl.$tempurl ;



$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("print_staffer","print_staffer.html"); 


			// �༭
$query = "select * from tb_staffer 
          left join tb_dept      on fd_dept_id   = fd_sta_deptid 
          left join tb_jobs      on fd_jobs_id   = fd_sta_deptid 
          where fd_sta_id = '$staid' 
         " ;

$db->query($query);
if($db->nf())
{                                //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
    $db->next_record();                                 //��ȡ��¼���� 
    $id              = $db->f(fd_sta_id);                  //id�� 
    $staffno         = $db->f(fd_sta_stano);               //���  
    $name            = $db->f(fd_sta_name);                //���� 
    $ename           = $db->f(fd_sta_ename);               //Ӣ���� 
    $sex             = $db->f(fd_sta_sex);                 //�Ա�  
    if($sex == 1){
    	$sex = "��";
    }else{
      $sex = "Ů";
    }
    
    $dept            = $db->f(fd_dept_name);               //��������       
    $duty            = $db->f(fd_jobs_name);                //ְλ 
    $driver          = $db->f(fd_sta_driver);              //��ʻִ��
 	  $worktype        = $db->f(fd_sta_worktype);            //�������� 
 	  
 	  if($worktype == 1){
    	$worktype = "ȫְ";
    }else if($worktype == 2){
      $worktype = "��ְ";
    }
 	  
 	  $birthday        = $db->f(fd_sta_birthday);            //��������
 	  $degree          = $db->f(fd_sta_degree);              //ѧ��
 	  $group           = $db->f(fd_sta_group);               //�������     
 	  $account        = $db->f(fd_sta_account);               //�˺� 
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
 	  
 	  if($bind == 1){
    	$bind = "��";
    }else{
      $bind = "��";
    }
 	  
 	  $caseplace       = $db->f(fd_sta_caseplace);           //��������
 	  $filesorg        = $db->f(fd_sta_filesorg);            //�йܻ���
 	  $marriage        = $db->f(fd_sta_marriage);            //����״��
 	  $consortname     = $db->f(fd_sta_consortname);         //��ż���� 
 	  $homememo        = $db->f(fd_sta_homememo);            //��ͥ��� 
 	  $degreexw        = $db->f(fd_sta_degreexw);            //ѧλ   
 	  $jobtime         = $db->f(fd_sta_jobtime);             //��ְʱ��
 	  $rehire          = $db->f(fd_sta_rehire);              //����¼�� 
 	  
 	  if($rehire == 1){
    	$rehire = "��";
    }else{
      $rehire = "��";
    }
 	  
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
 	  $agency          = $db->f(fd_agency_name);             //��������
    $weight          = $db->f(fd_sta_weight);              //����
    $tall            = $db->f(fd_sta_tall);                //���
    $dimission       = $db->f(fd_sta_dimission);           //�Ƿ���ְ
    
    if($dimission == 1){
    	$dimission = "ȫְ";
    }else{
      $dimission = "��ְ";
    }
    
    $nowaddress      = $db->f(fd_sta_nowaddress);          //��ס��ַ
    $out             = $db->f(fd_sta_out);                 //�Ƿ���Ƹ 
    
    if($out == 1){
    	$out = "��";
    }else{
      $out = "��";
    }
     
    $type            = $db->f(fd_sta_type);                //Ա������   
    
    if($type == 1){
    	$type = "ְ��";
    }else if($type == 2){
      $type = "��ͬ��";
    }else if($type == 2){
      $type = "�ٹ�";
    }else if($type == 2){
      $type = "����";
    }
    
    $status          = $db->f(fd_status_name);              //������λ 

  
    
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
          
                 
}
 


//��ʾͼƬ
if(empty($picpath)){
	$pic = "��ʱû����Ƭ";
}else{
  $pic = "<img src=".$picpath." width=120 heigh=180>"; 
}


                  
$t->set_var("id"              ,  $id              );
$t->set_var("staffno"         ,  $staffno         );
$t->set_var("name"            ,  $name            );
$t->set_var("ename"           ,  $ename           );
$t->set_var("sex"             ,  $sex             );
$t->set_var("dept"            ,  $dept            );
$t->set_var("duty"            ,  $duty            );
$t->set_var("driver"          ,  $driver          );
$t->set_var("worktype"        ,  $worktype        );
$t->set_var("birthday"        ,  $birthday        );
$t->set_var("degree"          ,  $degree          );
$t->set_var("group"           ,  $group           );
$t->set_var("zhicheng"        ,  $zhicheng        );
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
$t->set_var("agency"          ,  $agency          );
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
$t->set_var("account"          ,  $account          );

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // ת�õĵ�ַ              
$t->set_var("error"        , $error        );        

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "print_staffer");    # ������ҳ��



?>

