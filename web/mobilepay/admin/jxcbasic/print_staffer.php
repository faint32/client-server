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



$t = new Template(".", "keep");          //调用一个模版
$t->set_file("print_staffer","print_staffer.html"); 


			// 编辑
$query = "select * from tb_staffer 
          left join tb_dept      on fd_dept_id   = fd_sta_deptid 
          left join tb_jobs      on fd_jobs_id   = fd_sta_deptid 
          where fd_sta_id = '$staid' 
         " ;

$db->query($query);
if($db->nf())
{                                //判断查询到的记录是否为空
    $db->next_record();                                 //读取记录数据 
    $id              = $db->f(fd_sta_id);                  //id号 
    $staffno         = $db->f(fd_sta_stano);               //编号  
    $name            = $db->f(fd_sta_name);                //姓名 
    $ename           = $db->f(fd_sta_ename);               //英文名 
    $sex             = $db->f(fd_sta_sex);                 //性别  
    if($sex == 1){
    	$sex = "男";
    }else{
      $sex = "女";
    }
    
    $dept            = $db->f(fd_dept_name);               //所属部门       
    $duty            = $db->f(fd_jobs_name);                //职位 
    $driver          = $db->f(fd_sta_driver);              //驾驶执照
 	  $worktype        = $db->f(fd_sta_worktype);            //工作性质 
 	  
 	  if($worktype == 1){
    	$worktype = "全职";
    }else if($worktype == 2){
      $worktype = "兼职";
    }
 	  
 	  $birthday        = $db->f(fd_sta_birthday);            //出生日期
 	  $degree          = $db->f(fd_sta_degree);              //学历
 	  $group           = $db->f(fd_sta_group);               //社会团体     
 	  $account        = $db->f(fd_sta_account);               //账号 
 	  $homememo        = $db->f(fd_sta_homememo);            //家庭情况
 	  $english         = $db->f(fd_sta_english);             //英语能力
 	  $picpath         = $db->f(fd_sta_photo);               //图片地址
 	  $idcard          = $db->f(fd_sta_idcard);              //身份证
 	  $nativeplace     = $db->f(fd_sta_nativeplace);         //籍贯
 	  $hkcity          = $db->f(fd_sta_hkcity);              //户口所在城市
 	  $homeplace       = $db->f(fd_sta_homeplace);           //出生地
 	  $nation          = $db->f(fd_sta_nation);              //民族
 	  $polity          = $db->f(fd_sta_polity);              //政治面貌
 	  $nationality     = $db->f(fd_sta_nationality);         //国籍       
 	  $faith           = $db->f(fd_sta_faith);               //宗教信仰
 	  $bind            = $db->f(fd_sta_bind);                //是否托管
 	  
 	  if($bind == 1){
    	$bind = "否";
    }else{
      $bind = "是";
    }
 	  
 	  $caseplace       = $db->f(fd_sta_caseplace);           //档案城市
 	  $filesorg        = $db->f(fd_sta_filesorg);            //托管机构
 	  $marriage        = $db->f(fd_sta_marriage);            //婚姻状况
 	  $consortname     = $db->f(fd_sta_consortname);         //配偶姓名 
 	  $homememo        = $db->f(fd_sta_homememo);            //家庭情况 
 	  $degreexw        = $db->f(fd_sta_degreexw);            //学位   
 	  $jobtime         = $db->f(fd_sta_jobtime);             //入职时间
 	  $rehire          = $db->f(fd_sta_rehire);              //重新录用 
 	  
 	  if($rehire == 1){
    	$rehire = "否";
    }else{
      $rehire = "是";
    }
 	  
 	  $dimissiondate   = $db->f(fd_sta_dimissiondate);       //离职日期
 	  $nbemail         = $db->f(fd_sta_nbemail);             //公司内部EMAIL 
 	  $jobchannel      = $db->f(fd_sta_jobchannel);          //入职渠道
 	  $dimimemo        = $db->f(fd_sta_dimimemo);            //离职原因  
 	  $address         = $db->f(fd_sta_address);             //联系地址
 	  $postalcode      = $db->f(fd_sta_postalcode);          //邮政编码
 	  $phone           = $db->f(fd_sta_phone);               //住宅电话
 	  $companyphone    = $db->f(fd_sta_companyphone);        //单位电话
 	  $mobile          = $db->f(fd_sta_mobile);              //手机
 	  $wbemail         = $db->f(fd_sta_wbemail);             //个人EMAIL
 	  $otherlink       = $db->f(fd_sta_otherlink);           //其他联系方式
 	  $school          = $db->f(fd_sta_school);              //毕业院校 
 	  $specialtytype   = $db->f(fd_sta_specialtytype);       //专业类别
 	  $worktime        = $db->f(fd_sta_worktime);            //工作年限  
 	  $beginjobtime    = $db->f(fd_sta_beginjobtime);        //开始工作时间
 	  $studyexperience = $db->f(fd_sta_studyexperience);     //学习经历
 	  $workexperience  = $db->f(fd_sta_workexperience);      //工作经历   
 	  $agency          = $db->f(fd_agency_name);             //所属机构
    $weight          = $db->f(fd_sta_weight);              //体重
    $tall            = $db->f(fd_sta_tall);                //身高
    $dimission       = $db->f(fd_sta_dimission);           //是否在职
    
    if($dimission == 1){
    	$dimission = "全职";
    }else{
      $dimission = "兼职";
    }
    
    $nowaddress      = $db->f(fd_sta_nowaddress);          //现住地址
    $out             = $db->f(fd_sta_out);                 //是否外聘 
    
    if($out == 1){
    	$out = "是";
    }else{
      $out = "否";
    }
     
    $type            = $db->f(fd_sta_type);                //员工类型   
    
    if($type == 1){
    	$type = "职工";
    }else if($type == 2){
      $type = "合同工";
    }else if($type == 2){
      $type = "临工";
    }else if($type == 2){
      $type = "退休";
    }
    
    $status          = $db->f(fd_status_name);              //所属岗位 

  
    
    //拆散出生日期
    $temp_arr=explode("-",$birthday);     
    $byear    =  $temp_arr[0]; 
    $bmonth   =  $temp_arr[1];
    $bday     =  $temp_arr[2]; 
    
    //拆散入职时间
    $temp_arr=explode("-",$jobtime);     
    $jyear    =  $temp_arr[0]; 
    $jmonth   =  $temp_arr[1];
    $jday     =  $temp_arr[2]; 
    
    //拆散离职时间      
    if($dimissiondate!="0000-00-00"){
      $temp_arr=explode("-",$dimissiondate);     
      $dyear    =  $temp_arr[0]; 
      $dmonth   =  $temp_arr[1];
      $dday     =  $temp_arr[2]; 
    }
    
    
     
    //拆散开始工作时间
    $temp_arr=explode("-",$beginjobtime);     
    $bjyear    =  $temp_arr[0]; 
    $bjmonth   =  $temp_arr[1];
    $bjday     =  $temp_arr[2];  
          
                 
}
 


//显示图片
if(empty($picpath)){
	$pic = "暂时没有照片";
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
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );        

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "print_staffer");    # 最后输出页面



?>

