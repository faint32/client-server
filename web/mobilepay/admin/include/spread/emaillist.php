<?
$thismenucode = "7002";
require ("../include/common.inc.php");
require("../function/AutogetFileimg.php");
require("../function/class.phpmailer.php");
$db = new DB_test;

$gourl = "tb_emaillist.php" ;
$gotourl = $gourl.$tempurl ;

switch($action){
	case "new":
	  if(empty($emaillist_title)||empty($emaillist_file)){
	    header("Location: $gotourl");
		exit();
	  }
	  $query = "INSERT INTO tb_emaillist(
		       fd_emaillist_title,fd_emaillist_file,fd_emaillist_date
			   ) values(
		       '$emaillist_title','$emaillist_file','".time()."'
			   )";
	  $db->query($query); 	
	  header("Location: $gotourl");	   
	break;
	case "edit":
	  if(empty($emaillist_title)||empty($emaillist_file)){
	    header("Location: $gotourl");
		exit();
	  }
	  $query = "update tb_emaillist set
		       fd_emaillist_title = '$emaillist_title',fd_emaillist_file = '$emaillist_file' where fd_emaillist_id = '$id'";
	  $db->query($query); 	
	  header("Location: $gotourl");	   
	break;	
	case "send":
	  if(empty($id)){
	    header("Location: $gotourl");
		exit();
	  }
	  
	  $arrUseremal = array();
	  $query = "select * from tb_user where fd_user_isshop = '0' and fd_user_email <> ''";
	  $db->query($query);
	  if($db->nf()){
	    while($db->next_record()){
		  $arrUseremal[] = array(
		                         'user_email'=>$db->f(fd_user_email),'user_id'=>$db->f(fd_user_id),'user_name'=>$db->f(fd_user_name)
						         );
		}
	  }else{
	    echo '<script>alert("not user email");location.href="$gotourl";</script>';
		exit();	  
	  }
	  
	  $query = "select * from tb_emaillist where fd_emaillist_id = '$id'";
	  $db->query($query);
	  if($db->nf()){
	    $db->next_record();
	    $title = $db->f(fd_emaillist_title);
		$file = $db->f(fd_emaillist_file);
		$body = getemailcontent($file);
	  }else{
	    echo '<script>alert("not data");location.href="$gotourl";</script>';
		exit();	 	  
	  }
	  
	  if(empty($body)){
	    echo '<script>alert("not bodt");location.href="$gotourl";</script>';
		exit();	  
	  }
	  $err = 0;
	  $query = "select * from tb_emailuser where fd_emailuser_status = '1'";
	  $db->query($query);
	  if($db->nf()){
	      $db->next_record();
		  $emailuser_name = $db->f(fd_emailuser_name);
		  $emailuser_pwd = $db->f(fd_emailuser_pwd);
		  $mailuser_host = $db->f(fd_emailuser_host);
		  $emailuser_port = $db->f(fd_emailuser_port);
		  $emailuser_nick = $db->f(fd_emailuser_nick);
		  
		  for($j=0;$j<count($arrUseremal);$j++){
			  $mail = new PHPMailer(true); //New instance, with exceptions enabled   
			  $mail->IsSMTP();                           // tell the class to use SMTP   
			  $mail->SMTPAuth   = true;                  // enable SMTP authentication   
			  $mail->Port       = $emailuser_port;                // set the SMTP server port   
			  $mail->Host       = $mailuser_host; // SMTP server   
			  $mail->Username   = $emailuser_name;     // SMTP server username   
			  $mail->Password   = $emailuser_pwd;            // SMTP server password   
			  $mail->From       = $emailuser_name;   
			  $mail->FromName   = $emailuser_nick;   
			  $mail->AddAddress($arrUseremal[$j]['user_email'],$arrUseremal[$j]['user_name']);   
			  $mail->Subject  = $title;   
			  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test   
			  $mail->WordWrap   = 80; // set word wrap  
			  $mail->MsgHTML($body);   
              $mail->IsHTML(true); // send as HTML
	          $mail->CharSet = "gb2312"; 
              $mail->Encoding = "base64";
			  $emailuserinfo = "@".$arrUseremal[$j]['user_email'].";".$arrUseremal[$j]['user_name'];
	          if($mail->Send()){
			    $emailuserinfo = $emailuserinfo."发送成功\r\n";
			  }else{
			    $emailuserinfo = $emailuserinfo."发送失败\r\n";
			  }
			  emailtolog($file,$emailuserinfo);
			  unset($mail);		  
		  } 	  
	  }else{
	    echo '<script>alert("not email");location.href="$gotourl";</script>';
		exit();
	  }

	  $query = "update tb_emaillist set
		       fd_emaillist_fsdate = '".time()."' where fd_emaillist_id = '$id'";
	  $db->query($query);	
	  
	  header("Location: $gotourl");	   
	break;	
	default:
	break;
}	

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("emaillist","emaillist.html"); 


if(!isset($id)){
	//新增记录
	$arrTemData = array(
		              'emaillist_id'=>'','emaillist_title'=>'',
					  'emaillist_date'=>'','emaillist_file'=>'',
					  'emaillist_fsdate'=>''
				  );
	$action = 'new';			  
}else{
	$query="select * from tb_emaillist where fd_emaillist_id='$id' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$arrTemData = array(
		              'emaillist_id'=>$db->f(fd_emaillist_id),'emaillist_title'=>$db->f(fd_emaillist_title),
					  'emaillist_date'=>$db->f(fd_emaillist_date),'emaillist_file'=>$db->f(fd_emaillist_file),
					  'emaillist_fsdate'=>$db->f(fd_emaillist_fsdate)
					  );
		
		
	}
	$action = 'edit';
}


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var($arrTemData);
$t->set_var('action',$action);
$t->set_var("id"              , $id             );  //id
$t->set_var("gotourl"         , $gotourl        );  // 转用的地址  
$t->set_var("skin",$loginskin);
$t->pparse("out", "emaillist");    # 最后输出页面

//生成选择菜单的函数
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

function getemailcontent($file){
  ob_start();
  include "template/$file/index.php"; 
  $info = ob_get_contents();
  ob_clean(); 
  return $info;
}

function emailtolog($file,$val){
  $filenam = "template/$file/log.txt"; 
  $file = fopen($filenam,"a+");
  fwrite($file,$val);
  fclose($file);
}

?>

