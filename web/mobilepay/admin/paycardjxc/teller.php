<?
$thismenucode = "2k103"; 
require ("../include/common.inc.php");
$db = new DB_test;
$gourl = "tellerlist.php?listid=$listid" ;
$gotourl = $gourl.$tempurl ;



switch ($action){
	    case "del_all":   // 记录删除
		
	for($i=0;$i<count($checkid);$i++){
       if(!empty($checkid[$i])){
       	  $query = "delete from tb_cus_teller where  fd_tel_id= '$checkid[$i]'";
          $db->query($query);
       }
    }
       Header("Location: $gotourl?listid=$listid");       
	break;
    case "delete":   // 记录删除
       $query = "delete from tb_cus_teller where fd_tel_id = '$telid'";
       $db->query($query);
       Header("Location: $gotourl");       
	break;
    case "new":   // 进行编辑，要提供数据
       $query = "select * from tb_cus_teller where fd_tel_name ='$telname' ";
	     $db->query($query);
       if($db->nf()>0){
	         $error = "此用户名已经存在，请重新输入！";
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
    case "edit":   // 修改记录
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

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("teller","teller.html"); 


if (!isset($telid)){		// 新增
   $recsts = 0 ;
   $chgpass = 1 ;
   $checkterm = 1;
   $action = "new";
   
}else{			// 编辑
   $query = "select * from tb_cus_teller 
             where fd_tel_id = '$telid'";
   $db->query($query);
	if($db->nf()){                                //判断查询到的记录是否为空
	   $db->next_record();                           //读取记录数据 
       	   $telname         = $db->f(fd_tel_name) ;            //用户名称 
       	   $intime       = $db->f(fd_tel_intime);           //进入时间
       	   $outtime      = $db->f(fd_tel_outtime);          //离开时间   
       	   $errornum     = $db->f(fd_tel_error) ;           //密码错误次数    
       	   $inip         = $db->f(fd_tel_inip);             //进入ip
       	   $memo         = $db->f(fd_tel_memo) ;            //备注        
       	   $recsts       = $db->f(fd_tel_recsts) ;          //用户状态 
       	   $isin         = $db->f(fd_tel_isin);             //是否登陆
       	   $partname     = $db->f(fd_part_name);            //分行名称  
       	   $area         = $db->f(fd_area_name);            //地区 
       	   $province     = $db->f(fd_province_name);        //省份  
       	   $city         = $db->f(fd_city_name);            //城市  
       	   $area         = $area.$province.$city;       	       	   
       	   $selgroup     = $db->f(fd_tel_usegroupid);       //用户所属组
       	   
       	   
       	   
       	   
       	   
       	   if($isin ==1){ 
       	   	$isin = "是" ;
       	   }else{
       	   	$isin = "否" ;       	   	
           }

       	   $org          = $db->f(fd_tel_org) ;             //所属机构                
       	   $chgpass      = $db->f(fd_tel_chgpass);          //修改密码
       	   $checkterm    = $db->f(fd_tel_checkterm);        //限制终端
       	   $interm       = $db->f(fd_tel_term);             //最后使用终端

       	   $termid       = $db->f(fd_tel_appointterm);      //指定终端
       	   $staid        = $db->f(fd_tel_staffer);          //职员id号
       	   
       	   $query = "select * from tb_staffer where fd_sta_id = '$staid'";
   	       $db->query($query);
	         $db->next_record();
       	   $stano      = $db->f(fd_sta_stano) ;
       	   $staname    = $db->f(fd_sta_name) ;
       	   $action = "edit";
       	}
}
	   
           //生成recsts选择
       	   $arr_recsts = array("正常","暂停") ;
       	   $arr_valuerecsts = array("0","1") ;
       	   $recsts = makeselect($arr_recsts,$recsts,$arr_valuerecsts);

       	         	   
       	   
       	   //生成chgpass选择
       	   $arr_chgpass = array("需要修改","不需要修改") ;
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

//生成chgpass选择

if($loginuser ==1){
  $arr_state = array("普通用户","高级用户","超级用户") ;
  $arr_statevalue = array(0,1,2) ;
}else{
  $arr_state = array("普通用户","高级用户") ; 
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
$t->set_var("gotourl",$gotourl);  // 转用的地址
$t->set_var("error",$error);

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "teller");    # 最后输出页面



?>