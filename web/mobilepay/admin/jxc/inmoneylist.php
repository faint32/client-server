<?
$thismenucode = "2k218";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //�������ɵ��ݱ���ļ�    
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�

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
		echo "<script>alert('�õ����Ѿ��ǵȴ��������߹����ˣ��������޸ģ����֤')</script>"; 
		$action ="";
		$end_action="";
	}
}

switch($action){
	case "new":   //ɾ��ϸ�ڱ�����
	  	if($ischangelistno!=1){
	  		$listno = listnumber_update(8);  //���浥��
	  	}
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno'";
      $db->query($query);
      if($db->nf()){
    	  $error = "�õ����Ѿ����ڣ����֤��";
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
         $db->query($query);    //���븶�
         $listid = $db->insert_id();    //ȡ���ղ���ļ�¼�����ؼ�ֵ��id
		 //Header("Location: $gotourl");
      }
	  break;
	case "edit":  //��������
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "�õ����Ѿ����ڣ����֤��";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_no         = '$listno'      , fd_inmylt_clientid = '$cusid'      ,
                   fd_inmylt_clientname = '$cusname'     , fd_inmylt_staid    = '$loginstaid' ,
                   fd_inmylt_accountid  = '$accountid'   , fd_inmylt_money    = '$payallmoney',
                   fd_inmylt_date       = '$date'        , fd_inmylt_memo     = '$memo_z'     ,
                   fd_inmylt_dealwithman= '$dealwithman' , fd_inmylt_type     = '$clienttype' ,
                   fd_inmylt_clientno   = '$cusno'        
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //�޸ĸ��
		 Header("Location: $gotourl");
      }
	  break;
	default:
	  break;
}

//�жϵ��������Ƿ���ڽ�������ڣ�������ھͲ����Թ��ʡ�
if($end_action=="endsave"){
	$arr_tempdate = explode("/",$date);
	$listdate = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	
	$todaydate = date( "Y-m-d" ,mktime("0","0","0",date( "m", mktime()),date( "d", mktime()), date( "Y", mktime())));
	if($todaydate<$listdate){
		$error = "���󣺵������ڲ��ܴ��ڽ�������ڡ���ע�⣡";
		$end_action="";
	}
}


switch($end_action){
	case "endsave":    //����ύ����
	    $query = "update tb_inmoneylist set fd_inmylt_state = 1 , fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //�޸ĸ��
	       	   require("../include/alledit.2.php");
	           Header("Location: $gotourl");

	  break;
	case "dellist":    //ɾ��������������
	     $query = "delete from tb_inmoneylist  where fd_inmylt_id = '$listid'";
	     $db->query($query);   //ɾ���ܱ������
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
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
   
}else{   //������۵�id�ò���Ϊ��
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

if(empty($listno)){   //��ʾ��ʱ�ĵ��ݱ��
	$listno=listnumber_view("8");
}
//echo $listno."fd";
//��ѯ�ʻ�������
$query = "select * from tb_account where fd_account_isstop = '0'";
$db->query($query);   
if($db->nf()){
	while($db->next_record()){
		$arr_accid[]   = $db->f(fd_account_id); 
	  $arr_acc[]     = $db->f(fd_account_name);
	}
}
$accountid = makeselect($arr_acc,$accountid,$arr_accid);

$arr_clienttype = array("�ͻ�","��Ӧ��");
$arr_clientid   = array("1"   , "2" );
$clienttype = makeselect($arr_clienttype,$clienttype,$arr_clientid);

$t->set_var("isend_save"       , $isend_save       );      //����id 
$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //���ݱ�� 
$t->set_var("cusname"      , $cusname      );      //�ͻ�����
$t->set_var("cusno"        , $cusno        );      //�ͻ����
$t->set_var("cusid"        , $cusid        );      //�ͻ�id��
$t->set_var("clienttype"   , $clienttype   );      //�ͻ�����
$t->set_var("staname"      , $staname );      //������
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("accountid"    , $accountid    );      //�ʻ�
$t->set_var("payallmoney"  , $payallmoney  );      //������
$t->set_var("dealwithman"  , $dealwithman  );      //�տ���
$t->set_var("yfk_show"     , $yfk_show     );      //Ӧ�տ�

$t->set_var("count"        , $count        );      //�ʻ�
                                                    
$t->set_var("date"         , $date         );      //��
//$t->set_var("month"        , $month        );      //��
//$t->set_var("day"          , $day          );      //��  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"        , $error        );      

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "inmoneylist");    # ������ҳ��

?>
