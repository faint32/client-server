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


	$query = "select * from tb_inmoneylist where fd_inmylt_id = '$listid' 
	          and fd_inmylt_state = 1 ";
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
							  $staname     = $db->f(fd_inmylt_chronicler);
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
				 $date        = $db->f(fd_inmylt_date);
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
	       	   
	       	   $query = "update tb_inmoneylist set fd_inmylt_state = 9 , fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //�޸ĸ��
             
	       	   require("../include/alledit.2.php");
	           Header("Location: $gotourl");
	        
	    }
	  break;
	case "nopass":    //ɾ��������������
	    $query = "update tb_inmoneylist set fd_inmylt_state = 0 ,fd_inmylt_memo='$memo_z', fd_inmylt_datetime = now() 
                       where fd_inmylt_id = '$listid'";
             $db->query($query);    //�޸ĸ��
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	  break;
	default:
	  break;
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("inmoneylist_sp","inmoneylist_sp.html"); 
$t->set_block("inmoneylist_sp", "prolist"  , "prolists"); 

$count = 0;
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
$t->set_var("staname"      , $staname );           //������
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("accountid"    , $accountid    );      //�ʻ�
$t->set_var("payallmoney"  , $payallmoney  );      //������
$t->set_var("dealwithman"  , $dealwithman  );      //�տ���
$t->set_var("yfk_show"     , $yfk_show     );      //Ӧ�տ�
$t->set_var("date"     , $date     ); 
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

$t->pparse("out", "inmoneylist_sp");    # ������ҳ��

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

