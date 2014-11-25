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
  case "delete":   // 记录删除
     $query="delete from tb_staffer where fd_sta_id='$id'";
     $db->query($query);
     require("../include/alledit.2.php");
     Header("Location: $gotourl");       
	break;
  case "new":   
        //插入记录        
      //include ("../autono/staffautono.php");
      $query = "select * from tb_staffer where fd_sta_stano = '$stano'";
      $db->query($query);
      if($db->nf()){  
      	$error = "员工编号不能重复,请查证!";  
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
  case "edit":   // 修改记录
      $query = "select * from tb_staffer where fd_sta_stano = '$stano' and fd_sta_id != '$id'";
      $db->query($query);
      if($db->nf()){  
      	$error = "员工编号不能重复,请查证!";  
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
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("staffer","staffer.html"); 

if (empty($id))
{		// 新增
   $action = "new";
   
   //生成出生日期
   $byear    = date( "Y", mktime())-18 ;   
   $bmonth   = date( "m", mktime()) ;   
   $bday     = date( "d", mktime()) ;  
   
   //生成入职时间 
   $jyear    = date( "Y", mktime()) ;   
   $jmonth   = date( "m", mktime()) ;   
   $jday     = date( "d", mktime()) ;  
   
   //生成离职时间
   $dyear    = date( "Y", mktime()) ;    
   $dmonth   = date( "m", mktime()) ;   
   $dday     = date( "d", mktime()) ;   
   
   //生成开始工作时间
   $bjyear   = date( "Y", mktime()) ;   
   $bjmonth   = date( "m", mktime()) ;   	
   $bjday   = date( "d", mktime()) ;   
   $display1 = "none";
   $display2 = "";
   //自动生成员工编号
   //include ("../autono/staffautono.php");
} 
else
{			// 编辑
  $query = "select * from tb_staffer where fd_sta_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                                //判断查询到的记录是否为空
      $db->next_record();                                 //读取记录数据 
      $id              = $db->f(fd_sta_id);                  //id号 
      $staffno         = $db->f(fd_sta_stano);               //编号  
      $name            = $db->f(fd_sta_name);                //姓名 
      $ename           = $db->f(fd_sta_ename);               //英文名 
      $sex             = $db->f(fd_sta_sex);                 //性别 
      $deptid          = $db->f(fd_sta_deptid);              //所属部门       
      $duty            = $db->f(fd_sta_duty);                //职位 
      $driver          = $db->f(fd_sta_driver);              //驾驶执照
   	  $worktype        = $db->f(fd_sta_worktype);            //工作性质
   	  $birthday        = $db->f(fd_sta_birthday);            //出生日期
   	  $degree          = $db->f(fd_sta_degree);              //学历
   	  $group           = $db->f(fd_sta_group);               //社会团体     
   	  $zhichengid      = $db->f(fd_sta_zhichengid);          //职称 
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
   	  $caseplace       = $db->f(fd_sta_caseplace);           //档案城市
   	  $filesorg        = $db->f(fd_sta_filesorg);            //托管机构
   	  $marriage        = $db->f(fd_sta_marriage);            //婚姻状况
   	  $consortname     = $db->f(fd_sta_consortname);         //配偶姓名 
   	  $homememo        = $db->f(fd_sta_homememo);            //家庭情况 
   	  $degreexw        = $db->f(fd_sta_degreexw);            //学位   
   	  $jobtime         = $db->f(fd_sta_jobtime);             //入职时间
   	  $rehire          = $db->f(fd_sta_rehire);              //重新录用 
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
   	  $account        = $db->f(fd_sta_account);            //所属机构
      $weight          = $db->f(fd_sta_weight);              //体重
      $tall            = $db->f(fd_sta_tall);                //身高
      $dimission       = $db->f(fd_sta_dimission);           //是否在职
      $nowaddress      = $db->f(fd_sta_nowaddress);          //现住地址
      $out             = $db->f(fd_sta_out);                 //是否外聘  
      $type            = $db->f(fd_sta_type);                //员工类型 
      $statusid        = $db->f(fd_sta_statusid);              //所属岗位 

      if($dimission == 1){      	
        $display1 = "none";
        $display2 = "";

      }else{
        $display1 = "";
        $display2 = "none";
      }
      
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
            
      $action = "edit";      	                     
  }
} 

if($status==1){
  $helptitle = "在职员工";
  $help = "说明：<br><br>
           1、必须在下面的“组织机构管理”里先设置基本的机构、职位、岗位、<br>&nbsp;&nbsp;&nbsp;部门资料<br><br>
           2、然后在员工管理的“职称资料”设置<br><br>
           3、其他的可以选择系统固定的资料和手动输入<br><br>
           4、在“是否在职”里可选择“在职”或“离职”，选择“在职”资料会<br>&nbsp;&nbsp;&nbsp;保存在职员工里，选择“离职”保存后会自动跳到“离职员工”界面<br>&nbsp;&nbsp;&nbsp;里<br><br>
           5、在员工类型中有“在职”，“合同工”，“临工”，“退休”,选择<br>&nbsp;&nbsp;&nbsp;“退休”保存后资料会自动跳到“退休员工”界面里<br><br>
           6、打*是必填资料<br><br>
          ";
  $help_height = 460;        
}

if($status==2){
	$helptitle = "离职员工";
	$help = "说明：<br><br>
           &nbsp;&nbsp;&nbsp;&nbsp;离职员工界面是不能直接新增，只是显示“在职员工”界面的“离职”员工的历史记录，但可以在离职员工的界面的操作区修改历史记录，当选取“在职”时，此离职人员又会跳到“在职员工”的界面里了。

          ";
  $help_height = 290;           
}

if($status==3){
	$helptitle = "退休员工";
	$help = "说明：<br><br>同样由于员工类型的选取不同，其资料会跳到相应的界面里.";
  $help_height = 260; 
}




//显示图片
if(empty($picpath)){
	$pic = "暂时没有照片";
}else{
  $pic = "<img src=".$picpath." width=120 heigh=180>"; 
}

//性别下拉菜单
$arr_sex= array('男','女');
$arr_sex_val= array(1,2);
$sex = makeselect($arr_sex,$sex,$arr_sex_val); 

//是否外聘下拉菜单
$arr_out= array('否','是');
$arr_out_val= array(2,1);
$out = makeselect($arr_out,$out,$arr_out_val); 



//所在部门下拉菜单

  $query = "select * from tb_dept";     

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_dept[]= $db->f(fd_dept_name);
		$arr_deptid[]= $db->f(fd_dept_id);
	}
}  	
$deptid = makeselect($arr_dept,$deptid,$arr_deptid); 










//驾照下拉菜单
$arr_driver= array('A类','B类','C类','D类','E类');
$driver = makeselect($arr_driver,$driver,$arr_driver);

//是否在职下拉菜单
$arr_dimission= array('在职','离职');
$arr_dimission_val= array(1,2);
$dimission = makeselect($arr_dimission,$dimission,$arr_dimission_val);

//工作性质下拉菜单
$arr_worktype= array('全职','兼职');
$arr_worktype_val= array(1,2);
$worktype = makeselect($arr_worktype,$worktype,$arr_worktype_val);

//公司托管下拉菜单
$arr_bind= array('否','是');
$arr_bind_val= array(1,2);
$bind = makeselect($arr_bind,$bind,$arr_bind_val);

//员工类型下拉菜单
$arr_type= array('职工','合同工','临工','退休','试用工');
$arr_type_val= array(1,2,3,4,5);
$type = makeselect($arr_type,$type,$arr_type_val);


//国籍下拉菜单

$arr_nationality_name = array("中国","美国","英国","加拿大","日本","西班牙","意大利","荷兰","捷克","俄罗斯","瑞典","德国","法国","韩国","新加坡","泰国","马来西亚");
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

////籍贯下拉菜单
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
////籍贯下拉菜单
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
////出生地下拉菜单
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
////出生地下拉菜单
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
////户口城市下拉菜单
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
////档案城市下拉菜单
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



//民族下拉菜单                                        
$arr_nation = array('汉族','藏族','朝鲜族','蒙古族','回族','满族','维吾尔族','壮族','彝族','苗族','其它民族');          
$nation = makeselect($arr_nation,$nation,$arr_nation);


//政治面貌下拉菜单
$arr_politics = array('中共党员','团员','群众');
$polity = makeselect($arr_politics,$polity,$arr_politics);
   	
//学历下拉菜单
$arr_degree = array('博士','研究生','本科','大专','高中','中专','职中','初中','小学');
$degree = makeselect($arr_degree,$degree,$arr_degree);

//学位下拉菜单
$arr_degreexw = array("博士后","博士","硕士","学士");
$degreexw = makeselect($arr_degreexw,$degreexw,$arr_degreexw);

//重新录用下拉菜单                                                
$arr_rehire     = array('否','是');  
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
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );        

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "staffer");    # 最后输出页面


?>

