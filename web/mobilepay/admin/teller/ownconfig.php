<?
$thismenucode = "9103";
require ("../include/common.inc.php");
require("../include/class.phpmailer.php");

$db = new DB_test;

switch ($action)
{
	case "new":
	      //��������
        $query = "select * from tb_ownconfig where fd_owncg_telid = '$loginuser' ";
        $db->query($query);
        if($db->nf()){
        	$query = "update crm_ownconfig set 
        	         fd_owncg_smtp = '$smtp'   , fd_owncg_sendname = '$sendname' ,
        	         fd_owncg_email = '$email' , fd_owncg_password = '$password' ,
        	         fd_owncg_pop  = '$pop' 
        	         where fd_owncg_telid = '$loginuser'";
        	$db->query($query);
        }else{
          $query="insert into tb_ownconfig (
                 fd_owncg_smtp      ,   fd_owncg_sendname ,  fd_owncg_email , 
                 fd_owncg_password  ,   fd_owncg_telid    ,  fd_owncg_pop
                 )values(
                 '$smtp'            ,   '$sendname'       ,  '$email'  ,
                 '$password'        ,   '$loginuser'      ,  '$pop'
                 )";
          $db->query($query);       
        }
        //����ϰ������
        $query = "update tb_teller set 
                  fd_tel_browline = '$browline' , fd_tel_skin = '$telskin' 
                  where fd_tel_id = '$loginuser' ";
        $db->query($query);     
             
	    break;
	default:    
	    break;
}
//��������
$query = "select * from tb_ownconfig where fd_owncg_telid = '$loginuser'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$smtp     = $db->f(fd_owncg_smtp);
	$sendname = $db->f(fd_owncg_sendname);
	$email    = $db->f(fd_owncg_email);
	$password = $db->f(fd_owncg_password);
	$pop      = $db->f(fd_owncg_pop);
}

//����ϰ������
$query = "select * from tb_teller where fd_tel_id = '$loginuser'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$telskin   = $db->f(fd_tel_skin);
	$browline  = $db->f(fd_tel_browline);
}

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("ownconfig","ownconfig.html"); 
 
$arr_skin = array("�������")	;
$arr_skinval = array("0")	;
   	
$telskin = makeselect($arr_skin,$telskin,$arr_skinval);
   	
$t->set_var("id"           , $id           );           //id
//��������
$t->set_var("smtp"         , $smtp         );           //smtp������ 
$t->set_var("pop"          , $pop          );           //pop������  
$t->set_var("sendname"     , $sendname     );           //���������� 
$t->set_var("email"        , $email        );           //���� 
$t->set_var("password"     , $password     );           //���� 

//����ϰ������
$t->set_var("browline"     , $browline     );           //�����¼�� 
$t->set_var("telskin"      , $telskin      );           //ѡ��Ƥ�� 

$t->set_var("action"       , $action       );
$t->set_var("gotourl"      , $gotourl      );           // ת�õĵ�ַ
$t->set_var("error"        , $error        );

// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "ownconfig");    # ������ҳ��

//����ѡ��˵��ĺ���
function makeselect($arritem,$hadselected,$arry){ 
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}
?>

