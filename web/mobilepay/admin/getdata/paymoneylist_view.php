<?
$thismenucode = "2k708";
require ("../include/common.inc.php");
require ("../function/chanceaccount.php");
require ("../function/functionlistnumber.php");
require ("../function/accountcussent.php");      //�����ʻ��������ʵ��ļ�

$db  = new DB_test;
$db1 = new DB_test;


$gourl = "tb_paymoneyhistory_b.php" ;
$gotourl = $gourl.$tempurl ;

$t = new Template(".", "keep");          //����һ��ģ��

if($actiontype=="draft"){
	$query = "select * from tb_paymoneylist where fd_pymylt_id = '$listid'";
  $db->query($query);   //��ȡ�ʻ�id��
  if($db->nf()){
  	$db->next_record();
  	$accountid = $db->f(fd_pymylt_accountid);
  	$paymoney  = $db->f(fd_pymylt_money);
  	$dealwithman = $db->f(fd_pymylt_dealwithman);
  	$cusid       = $db->f(fd_pymylt_clientid);
  	$cusno       = $db->f(fd_pymylt_clientno);
  	$clienttype  = $db->f(fd_pymylt_type);
  	$cusname     = $db->f(fd_pymylt_clientname);
  	$staid       = $db->f(fd_pymylt_staid);
  	$date        = $db->f(fd_pymylt_date);
  	$memo_z      = $db->f(fd_pymylt_memo);
  	$sklinkman   = $db->f(fd_pymylt_sklinkman);
  	
  	$listno = listnumber_update(9);  //���浥��
  	
  	$query = "insert into tb_paymoneylist( 
              fd_pymylt_no          ,  fd_pymylt_clientid    , fd_pymylt_type          ,
              fd_pymylt_clientname  ,  fd_pymylt_staid       , fd_pymylt_accountid     ,
              fd_pymylt_money       ,  fd_pymylt_date        , fd_pymylt_memo          ,
              fd_pymylt_dealwithman ,  fd_pymylt_clientno    , fd_pymylt_sklinkman
              )values(
              '$listno'             , '$cusid'            , '$clienttype'    ,
              '$cusname'            , '$staid'            , '$accountid'     ,
              '$paymoney'           , '$date'             , '$memo_z'        ,
              '$dealwithman'        , '$cusno'            , '$sklinkman'
              )";
    $db1->query($query);    //���븶�
  }
}

$allbalance=0;  //�ܹ����
if(empty($listid)){
	 $t->set_file("paymoneylist_view","paymoneylist_view.html");
	 $t->set_block("paymoneylist_view", "prolist"  , "prolists");  
   $error = "���󣺸õ��ݲ����ڣ�";
}else{   //������۵�id�ò���Ϊ��
   $action ="edit";
   $query = "select * from tb_paymoneylist 
             left join tb_account on fd_acc_id = fd_pymylt_authorid
             left join tb_staffer on fd_sta_id = fd_pymylt_staid
             where fd_pymylt_id = '$listid' ";
   $db->query($query);
   if($db->nf()){
   	  $db->next_record();
   	  $listno       = $db->f(fd_pymylt_no);
   	  $accountname  = $db->f(fd_acc_username);
   	  $cusid        = $db->f(fd_pymylt_clientid);
   	  $cusno        = $db->f(fd_pymylt_clientno);
   	  $cusname      = $db->f(fd_pymylt_clientname);
   	  $clienttype   = $db->f(fd_pymylt_type);
   	  $date         = $db->f(fd_pymylt_date);
   	  $payallmoney  = $db->f(fd_pymylt_money);
   	  $staname      = $db->f(fd_sta_name);
   	  $iskickback   = $db->f(fd_pymylt_kickback);
   	  $dealwithman  = $db->f(fd_pymylt_dealwithman);
   	  $memo_z       = $db->f(fd_pymylt_memo);
   	  $sklinkman    = $db->f(fd_pymylt_sklinkman);
   	  $billid       = $db->f(fd_pymylt_billid);
   	  
   	  $arr_temp = explode("-",$date);
   	  $year = $arr_temp[0];
   	  $month = $arr_temp[1];
   	  $day = $arr_temp[2];
   }
   if($iskickback==0){
      $t->set_file("paymoneylist_view","paymoneylist_view.html"); 
   }else{
      $t->set_file("paymoneylist_view","backpaymoneylist_view.html"); 
   }
}


//��ѯ����������
$query = "select * from tb_account where fd_acc_id = '$accountid'";
$db->query($query);   
if($db->nf()){
	$db->next_record();
	$billaccountname = $db->f(fd_acc_username);
}


//$thismenuqx[4]!=1 //�Ƿ��з������Ȩ��
if($iskickback=="1" or $loginopendate > $date or $thismenuqx[4]!=1 or $billid > 0){
  $iskickback = "disabled"; 
}else{
  $iskickback = ""; 
}

$t->set_var("iskickback"         , $iskickback         ); 	


switch($clienttype){
	case "1":
	  $clienttype = "�ͻ�";
	  break;
	case "2":
	  $clienttype = "��Ӧ��";
	  break;
}


$t->set_var("listid"       , $listid       );      //����id 
$t->set_var("listno"       , $listno       );      //���ݱ�� 
$t->set_var("cusno"        , $cusno        );      //�ͻ����
$t->set_var("cusname"      , $cusname      );      //�ͻ�����
$t->set_var("cusid"        , $cusid        );      //�ͻ�id��
$t->set_var("clienttype"   , $clienttype   );      //�ͻ�����
$t->set_var("staname"      , $staname      );      //������
$t->set_var("memo_z"       , $memo_z       );      //��ע
$t->set_var("accountname"  , $accountname  );      //�ʻ�
$t->set_var("payallmoney"  , $payallmoney  );      //������
$t->set_var("dealwithman"  , $dealwithman  );      //������
$t->set_var("sklinkman"    , $sklinkman    );      //�տ���
                                   
$t->set_var("year"         , $year         );      //��
$t->set_var("month"        , $month        );      //��
$t->set_var("day"          , $day          );      //��  

$t->set_var("action"       , $action       );        
$t->set_var("gotourl"      , $gotourl      );      // ת�õĵ�ַ
$t->set_var("error"        , $error        );      
                                           
// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "paymoneylist_view");    # ������ҳ��

?>

