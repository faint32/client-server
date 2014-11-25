<?
$thismenucode = "2k218";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //调用生成单据编号文件    
require ("../function/changemoney.php");         //调用应收应付金额文件
require ("../function/chanceaccount.php");       //调用修改帐户金额文件
require ("../function/cashglide.php");           //调用现金流水帐文件
require ("../function/currentaccount.php");      //调用往来对帐单文件

$db  = new DB_test;
$gourl = "tb_inmoneylist_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
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
	case "new":   //删除细节表数据
	  	if($ischangelistno!=1){
	  		$listno = listnumber_update(8);  //保存单据
	  	}
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno'";
      $db->query($query);
      if($db->nf()){
    	  $error = "该单据已经存在！请查证！";
      }else{
         $query = "insert into tb_inmoneylist(
                   fd_inmylt_no          ,  fd_inmylt_clientid    , fd_inmylt_type      ,
                   fd_inmylt_clientname  ,  fd_inmylt_staid       , fd_inmylt_accountid ,
                   fd_inmylt_money       ,  fd_inmylt_date        , fd_inmylt_memo      ,
                   fd_inmylt_dealwithman ,  fd_inmylt_clientno    ,fd_inmylt_state      
				  
                   )values(
                   '$listno'             , '$cusid'            , '$clienttype'    ,
                   '$cusname'            , '$loginstaid'       , '$accountid'     ,
                   '$payallmoney'        , '$date'             , '$memo_z'        ,
                   '$dealwithman'        , '$cusno'            , '0'              
				  
                   )";
         $db->query($query);    //插入付款单
         $listid = $db->insert_id();    //取出刚插入的记录的主关键值的id
		 //Header("Location: $gotourl");
      }
	  break;
	case "edit":  //新增数据
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "该单据已经存在！请查证！";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_no         = '$listno'      , fd_inmylt_clientid = '$cusid'      ,
                   fd_inmylt_clientname = '$cusname'     , fd_inmylt_staid    = '$loginstaid' ,
                   fd_inmylt_accountid  = '$accountid'   , fd_inmylt_money    = '$payallmoney',
                   fd_inmylt_date       = '$date'        , fd_inmylt_memo     = '$memo_z'     ,
                   fd_inmylt_dealwithman= '$dealwithman' , fd_inmylt_type     = '$clienttype' ,
                   fd_inmylt_clientno   = '$cusno'        
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //修改付款单
		 Header("Location: $gotourl");
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
	    $query = "update tb_inmoneylist set fd_inmylt_state = 1 , fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //修改付款单
	       	   require("../include/alledit.2.php");
	           Header("Location: $gotourl");

	  break;
	case "dellist":    //删除整条单据数据
	     $query = "delete from tb_inmoneylist  where fd_inmylt_id = '$listid'";
	     $db->query($query);   //删除总表的数据
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("inmoneylist","inmoneylist.html"); 
$t->set_block("inmoneylist", "prolist"  , "prolists"); 

$count = 0;
if(empty($listid)){
	 $year  = date( "Y", mktime()) ;
	 $month = date( "m", mktime()) ;
	 $day   = date( "d", mktime()) ;
	 $action ="new";
	 $staname=$loginstaname;
	$isend_save='disabled';
   
}else{   //如果销售单id好不是为空
   $isend_save='';
   $action ="edit";
   $query = "select * from tb_inmoneylist left join tb_staffer on fd_sta_id = fd_inmylt_staid where fd_inmylt_id = '$listid' ";
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
	  $staname     = $db->f(fd_sta_name);
   	  
   	  
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
//echo $listno."fd";
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

$t->set_var("isend_save"       , $isend_save       );      //单据id 
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //单据编号 
$t->set_var("cusname"      , $cusname      );      //客户名称
$t->set_var("cusno"        , $cusno        );      //客户编号
$t->set_var("cusid"        , $cusid        );      //客户id号
$t->set_var("clienttype"   , $clienttype   );      //客户类型
$t->set_var("staname"      , $staname );      //经手人
$t->set_var("memo_z"       , $memo_z       );      //备注
$t->set_var("accountid"    , $accountid    );      //帐户
$t->set_var("payallmoney"  , $payallmoney  );      //付款金额
$t->set_var("dealwithman"  , $dealwithman  );      //收款人
$t->set_var("yfk_show"     , $yfk_show     );      //应收款

$t->set_var("count"        , $count        );      //帐户
                                                    
$t->set_var("date"         , $date         );      //年
//$t->set_var("month"        , $month        );      //月
//$t->set_var("day"          , $day          );      //日  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // 转用的地址
$t->set_var("error"        , $error        );      

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "inmoneylist");    # 最后输出页面

?>
