<?
$thismenucode = "2k219";
require ("../include/common.inc.php");
require ("../function/functionlistnumber.php");  //�������ɵ��ݱ���ļ�    
require ("../function/changemoney.php");         //����Ӧ��Ӧ������ļ�
require ("../function/chanceaccount.php");       //�����޸��ʻ�����ļ�
require ("../function/cashglide.php");           //�����ֽ���ˮ���ļ�
require ("../function/currentaccount.php");      //�����������ʵ��ļ�
$db  = new DB_test;

$gourl = "tb_inmoneylist_sp_b.php" ;
$gotourl = $gourl.$tempurl ;
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
	
	case "save":  //ͨ��
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "�õ����Ѿ����ڣ����֤��";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_state = '9'       
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //�޸ĸ��
      }
	  break;
	  case "nopass":  //��ͨ��
      $query = "select * from tb_inmoneylist where fd_inmylt_no = '$listno' and fd_inmylt_id  <> '$listid'";
      $db->query($query);
      if($db->nf()){
    	  $error = "�õ����Ѿ����ڣ����֤��";
      }else{
         $query = "update tb_inmoneylist set
                   fd_inmylt_state = '0'       
                   where fd_inmylt_id = '$listid'";
         $db->query($query);    //�޸ĸ��
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
	     $arr_tempdate = explode("/",$date);
	     $date = date( "Y-m-d" ,mktime("0","0","0",$arr_tempdate[1],$arr_tempdate[2],$arr_tempdate[0]));
	     if($loginopendate>$date){
	     	  $error = "���󣺵������ڲ���С�������½���¿�ʼ����";
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
	           
     	       //�����������ʵ�
	           $ctatmemo     = "�տ��ȡ".$allmoney."Ԫ";
	           $cactlisttype = "8";
	           $addmoney = 0;
	           currentaccount($vclienttype , $vclientid  , $addmoney ,$allmoney , $ctatmemo , $cactlisttype , $loginstaname , $listid , $listno ,$date );
     	       
             changemoney($vclienttype , $vclientid ,$allmoney , 1 );  //�޸�Ӧ��Ӧ�������0��������1������

	          // changeaccount($accountid , $allmoney , 0);    //�����޸��ʻ����ĺ���
	           
	           //�����ʻ���ˮ��
	        	 $chgememo     = "�տ��ȡ".$allmoney."Ԫ";
	           $chgelisttype = "8";
	           $cogetype = 0; //0Ϊ�տ� �� 1Ϊ����
	        	 cashglide($accountid , $allmoney , $chgememo , $chgelisttype , $loginstaname , $listid , $listno , $cogetype ,$date );
	       	   
	       	   $query = "update tb_inmoneylist set fd_inmylt_state = 1 , fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //�޸ĸ��
             
	       	   require("../include/alledit.2.php");
	           Header("Location: $gotourl");
	        
	    }
	  break;
	
	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("inmoneylist_view_b","inmoneylist_view_b.html"); 
$t->set_block("inmoneylist_view_b", "prolist"  , "prolists"); 

$count = 0;
if(empty($listid)){
	 $year  = date( "Y", mktime()) ;
	 $month = date( "m", mktime()) ;
	 $day   = date( "d", mktime()) ;
	 $action ="new";
   
}else{   //������۵�id�ò���Ϊ��
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

if(empty($listno)){   //��ʾ��ʱ�ĵ��ݱ��
	$listno=listnumber_view("8");
}

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

$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("listno"       , $listno       );      //���ݱ�� 
$t->set_var("cusname"      , $cusname      );      //�ͻ�����
$t->set_var("cusno"        , $cusno        );      //�ͻ����
$t->set_var("cusid"        , $cusid        );      //�ͻ�id��
$t->set_var("clienttype"   , $clienttype   );      //�ͻ�����
$t->set_var("staname"      , $loginstaname );      //������
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("accountid"    , $accountid    );      //�ʻ�
$t->set_var("payallmoney"  , $payallmoney  );      //������
$t->set_var("dealwithman"  , $dealwithman  );      //�տ���
$t->set_var("yfk_show"     , $yfk_show     );      //Ӧ�տ�

$t->set_var("count"        , $count        );      //�ʻ�
                                                    
$t->set_var("year"         , $year         );      //��
$t->set_var("month"        , $month        );      //��
$t->set_var("day"          , $day          );      //��  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"        , $error        );      

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "inmoneylist_view_b");    # ������ҳ��

//����ѡ��˵��ĺ���
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

