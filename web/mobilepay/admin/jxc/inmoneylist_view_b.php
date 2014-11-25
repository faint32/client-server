<?
$thismenucode = "2k219";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //调用生成单据编号文件    
require ("../function/changemoney.php");         //调用应收应付金额文件
require ("../function/chanceaccount.php");       //调用修改帐户金额文件
require ("../function/cashglide.php");           //调用现金流水帐文件
require ("../function/currentaccount.php");      //调用往来对帐单文件
$db  = new DB_test;

$gourl = "tb_inmoneylist_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
if(!empty($action) or !empty($end_action)){
	$query = "select * from tb_inmoneylist where fd_inmylt_id = '$listid' 
	          and fd_inmylt_state = 1 or fd_inmylt_state = 2 ";
	$db->query($query);
	if($db->nf()){
		echo "<script>alert('该单据已经是等待审批或者过帐了，不能再修改，请查证')</script>"; 
		$action ="";
		$end_action="";
	}
}

switch($action){
	
	case "save":  //通过
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "该单据已经存在！请查证！";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_state = '9'       
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //修改付款单
      }
	  break;
	  case "nopass":  //不通过
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "该单据已经存在！请查证！";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_state = '0'       
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //修改付款单
      }
	  break;
	default:
	  break;
}

//判断单据日期是否大于今天的日期，如果大于就不可以过帐。
if($end_action=="endsave"){
	$arr_tempdate = explode("/",$date);
	$listdate = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$listdate){
		$error = "错误：单据日期不能大于今天的日期。请注意！";
		$end_action="";
	}
}


switch($end_action){
	case "endsave":    //最后提交数据
	     $arr_tempdate = explode("/",$date);
	     $date = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	     if($loginopendate>$date){
	     	  $error = "错误：单据日期不能小于上月月结后本月开始日期";
	     }else{	 
	           $allmoney=0;
	           $query = "select * from tb_inmoneylist where fd_inmylt_id = '$listid'";
	           $db->query($query);
	           if($db->nf()){
	           	 $db->next_record();
	           	 $allmoney    = $db->f(fd_inmylt_money);
	           	 $vclienttype = $db->f(fd_inmylt_type);
	           	 $vclientid   = $db->f(fd_inmylt_clientid);
	           }
	           
     	       //生成往来对帐单
	           $ctatmemo     = "收款单收取".$allmoney."元";
	           $cactlisttype = "8";
	           $addmoney = 0;
	           currentaccount($vclienttype , $vclientid  , $addmoney ,$allmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$date );
     	       
             changemoney($vclienttype , $vclientid ,$allmoney , 1 );  //修改应收应付款函数，0代表正，1代表负数

	          // changeaccount($accountid , $allmoney , 0);    //调用修改帐户金额的函数
	           
	           //生成帐户流水帐
	        	 $chgememo     = "收款单收取".$allmoney."元";
	           $chgelisttype = "8";
	           $cogetype = 0; //0为收款 ， 1为付款
	        	 cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype ,$date );
	       	   
	       	   $query = "update tb_inmoneylist set fd_inmylt_state = 1 , fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //修改付款单
             
	       	   require("../include/alledit.2.php");
	           Header("Location: $gotourl");
	        
	    }
	  break;
	
	default:
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("inmoneylist_view_b","inmoneylist_view_b.html"); 
$t->set_block("inmoneylist_view_b", "prolist"  , "prolists"); 

$count = 0;
if(empty($listid)){
	 $year  = date( "Y", mktime()) ;
	 $month = date( "m", mktime()) ;
	 $day   = date( "d", mktime()) ;
	 $action ="new";
   
}else{   //如果销售单id好不是为空
   $action ="edit";
   $query = "select * from tb_inmoneylist where fd_inmylt_id = '$listid' ";
   $db->query($query);
   if($db->nf()){
   	  $db->next_record();
   	  $listno     = $db->f(fd_inmylt_no);
   	  $accountid  = $db->f(fd_inmylt_accountid);
   	  $cusid      = $db->f(fd_inmylt_clientid);
   	  $cusname    = $db->f(fd_inmylt_clientname);
   	  $cusno      = $db->f(fd_inmylt_clientno);
   	  $clienttype = $db->f(fd_inmylt_type);
   	  $date       = $db->f(fd_inmylt_date);
   	  $payallmoney = $db->f(fd_inmylt_money);
   	  $dealwithman = $db->f(fd_inmylt_dealwithman);
   	  $memo_z      = $db->f(fd_inmylt_memo);
   	  
   	  
   	   $query = "select * from tb_ysyfmoney where fd_ysyfm_type ='$clienttype' 
   	             and fd_ysyfm_companyid = '$cusid' ";
       $db->query($query);
       if($db->nf()){
       	 $db->next_record();
       	 $yfk_show = $db->f(fd_ysyfm_money)+0;
       }else{
         $yfk_show ="0";
       }
   	  
   	  $arr_temp = explode("-",$date);
   	  $year = $arr_temp[0];
   	  $month = $arr_temp[1];
   	  $day = $arr_temp[2];
   }
}

if(empty($listno)){   //显示暂时的单据编号
	$listno=listnumber_view("8");
}

//查询帐户下拉框
$query = "select * from tb_account where fd_account_isstop = '0'";
$db->query($query);   
if($db->nf()){
	while($db->next_record()){
		$arr_accid[]   = $db->f(fd_account_id); 
	  $arr_acc[]     = $db->f(fd_account_name);
	}
}
$accountid = makeselect($arr_acc,$accountid,$arr_accid);

$arr_clienttype = array("客户","供应商");
$arr_clientid   = array("1"   , "2" );
$clienttype = makeselect($arr_clienttype,$clienttype,$arr_clientid);

$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("cusname"      , $cusname      );      //客户名称
$t->set_var("cusno"        , $cusno        );      //客户编号
$t->set_var("cusid"        , $cusid        );      //客户id号
$t->set_var("clienttype"   , $clienttype   );      //客户类型
$t->set_var("staname"      , $loginstaname );      //经手人
$t->set_var("memo_z"       , $memo_z       );      //备注
$t->set_var("accountid"    , $accountid    );      //帐户
$t->set_var("payallmoney"  , $payallmoney  );      //付款金额
$t->set_var("dealwithman"  , $dealwithman  );      //收款人
$t->set_var("yfk_show"     , $yfk_show     );      //应收款

$t->set_var("count"        , $count        );      //帐户
                                                    
$t->set_var("year"         , $year         );      //年
$t->set_var("month"        , $month        );      //月
$t->set_var("day"          , $day          );      //日  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "inmoneylist_view_b");    # 最后输出页面

//生成选择菜单的函数
/*function makeselect($arritem,$hadselected,$arry){ 
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}*/



?>

