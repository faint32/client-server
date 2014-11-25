<?
$thismenucode = "6n006";     
require("../include/common.inc.php");
$db=new db_test;
$db2=new db_test;
$db3=new db_test;
if(!empty($password))
{
	$password = md5($password);
}
switch($action)
{
	case "save":
	if(!empty($id))
	{
	$query = "select * from tb_saler where fd_saler_id  = '$id'";
    $db->query($query);
    if($db->nf()){   
    	    	$query="select * from tb_saler where fd_saler_username  = '$username' and fd_saler_id <> '$id'";
  $db2->query($query);
  if($db2->nf()>0){
  	$error = "你输入用户名已被注册，请查证！";
  }else{
    $query="select * from tb_saler where fd_saler_phone   = '$phone' and fd_saler_id <> '$id'";
    $db2->query($query);
    if($db2->nf()>0){
      $error = "你输入手机已被注册，请查证！";
    }else{   
    	 $query="select * from tb_saler where fd_saler_idcard   = '$idcard' and fd_saler_id <> '$id'";
    $db2->query($query);
    if($db2->nf()>0){
      $error = "你输入身份证已被注册，请查证！";
    }else{          
	$query = "update tb_saler set fd_saler_idcard ='$idcard',
	          fd_saler_truename ='$truename'                , fd_saler_phone ='$phone'                 , 
			fd_saler_sharesalerid = '$sharesalerid'  ,
			fd_saler_username     = '$username'      ,
			  fd_saler_salertype     ='$type'               , fd_saler_state       = '1'          ,       
			  where fd_saler_id = '$id'"; 
	$db->query($query);  
	if(!empty($password))
	{
	$query = "update tb_saler set  fd_saler_password = '$password'  where fd_saler_id = '$id'"; 
	$db->query($query);  	
	}
	//echo $query;
    }
  }
}
}
	}
	else
   {
		$query="select * from tb_saler where fd_saler_username  = '$username'";
  $db2->query($query);
  if($db2->nf()>0){
  	$error = "你输入用户名已被注册，请查证！";
  }else{
    $query="select * from tb_saler where fd_saler_phone   = '$phone'";
    $db2->query($query);
    if($db2->nf()>0){
      $error = "你输入手机已被注册，请查证！";
    }else{

      $query="select * from tb_saler where fd_saler_idcard   = '$idcard'";
    $db2->query($query);
    if($db2->nf()>0){
      $error = "你输入身份证已被注册，请查证！";
    }else{ 
	$query = "insert into tb_saler
	              (fd_saler_idcard    ,   fd_saler_truename     ,
	               fd_saler_phone     ,  
				   fd_saler_memberid  ,   fd_saler_sharesalerid ,
				   fd_saler_state     ,  
				   fd_saler_salertype ,   fd_saler_username     ,
				   fd_saler_password  )
	          values
			      ('$idcard'          ,  '$truename'      ,
				   '$phone'           ,  
				   '$memberid'        ,  '$sharesalerid'  ,
				   '$state'           ,  
				   '$type'            ,  '$username'      ,
				   '$password'      )";
	$db->query($query); 
    }
  }
}
}
	$action="";
	$id    ="";
	$phone ="";
	$sharesaleid="";
	$state="";
	$idcard="";
	$username="";
	$password="";
	$zjl="";
	$tjman="";
	$type="";
	$truename="";
	break;
	case "del":
	$query = "delete from  tb_saler where fd_saler_id = '$id'";
	$db->query($query); 
    $action="";
	$id    ="";
	$phone ="";
	$sharesaleid="";
	$state="";
	$idcard="";
	$type="";
	$username="";
	$password="";
	$zjl="";
	$tjman="";
	$truename="";
	break;
	default:
	$phone ="";
	$sharesaleid="";
	$state="";
	$idcard="";
    $username="";
	$password="";
	$zjl="";
	$tjman="";
	$type="";
	$truename="";
	break;
}

$t = new Template(".","keep");
$t->set_file("template","salersp.html");



if($id<>"")
{
	$query = "select * from tb_saler where fd_saler_id = '$id'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		   $id             = $db->f(fd_saler_id);            //id号  
		   $truename       = $db->f(fd_saler_truename);         //客户id
		   $idcard         = $db->f(fd_saler_idcard); 
		   $phone          = $db->f(fd_saler_phone);
		   $username       = $db->f(fd_saler_username );
		   $salerlevel     =$db->f(fd_saler_level);
		
		  
	}
}

$query = "select * from tb_salerlevel " ;
$db->query($query);
if($db->nf()){
   while($db->next_record()){		
		   $arr_salerlevelid[]     = $db->f(fd_salerlevel_id)  ; 
		   $arr_salerlevelname[]       = $db->f(fd_salerlevel_name);    
   }
}
$salerlevel = makeselect($arr_salerlevelname,$salerlevel,$arr_salerlevelid);

$checkall= '<INPUT onclick=CheckAll() type=checkbox class=checkbox value=on name=chkall>';
$arr_text = array("真实姓名","身份证","刷卡器数量","网导等级","状态","登录名","手机号码","编辑修改");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	$tfoot   .=' <input type="checkbox" value="1" size="1" class="checkbox" checked="checked" onClick="fnShowHide('.$i.');">'.$arr_text[$i];
}






$t->set_var("truename"     , $truename      );      
$t->set_var("idcard"       , $idcard        );

$t->set_var("id"           , $id        );

$t->set_var("salerlevel" , $salerlevel  );
$t->set_var("phone"        , $phone  );    

$t->set_var("theadth"           , $theadth     ); 
$t->set_var("checktype2"        , $checktype2  );    
$t->set_var("checktype1"        , $checktype1  ); 
$t->set_var("username"          , $username  );    
$t->set_var("password"          , $password  );    

$t->set_var("action",$action);
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


function g2u($str)
{
	$value=iconv("gb2312", "UTF-8", $str);
	return $value;
} 
?>