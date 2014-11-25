<?
$thismenucode = "7001";
require ("../include/common.inc.php");
require("../function/AutogetFileimg.php");
$db = new DB_test;


$gourl = "tb_emailuser.php" ;
$gotourl = $gourl.$tempurl ;

switch($action){
	case "new":
	  if(empty($emailuser_name)||empty($emailuser_pwd)){
	    header("Location: $gotourl");
		exit();
	  }
	  $query = "INSERT INTO tb_emailuser(
		       fd_emailuser_name,fd_emailuser_pwd,fd_emailuser_host,
			   fd_emailuser_port,fd_emailuser_status,fd_emailuser_nick
			   ) values(
		       '$emailuser_name','$emailuser_pwd','$emailuser_host',
			   '$emailuser_port','$emailuser_status','$emailuser_nick'
			   )";
	  $db->query($query); 	
	  header("Location: $gotourl");	   
	break;
	case "edit":
	  if(empty($emailuser_name)||empty($emailuser_pwd)){
	    header("Location: $gotourl");
		exit();
	  }
	  $query = "update tb_emailuser set
		       fd_emailuser_name = '$emailuser_name',fd_emailuser_pwd = '$emailuser_pwd',fd_emailuser_host = '$emailuser_host',
			   fd_emailuser_port = '$emailuser_port',fd_emailuser_status = '$emailuser_status',fd_emailuser_nick = '$emailuser_nick'
			   where fd_emailuser_id = '$id'
			   ";
	  $db->query($query); 	
	  header("Location: $gotourl");	   
	break;	
	default:
	break;
}	

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("emailuser","emailuser.html"); 
if(!isset($id)){
	//新增记录
	$arrTemData = array(
				  'emailuser_id'=>'','emailuser_name'=>'',
				  'emailuser_nick'=>'','emailuser_host'=>'',
				  'emailuser_port'=>'25','emailuser_status'=>'1',
				  'emailuser_pwd'=>''
				  );
	$action = 'new';			  
}else{
	$query="select * from tb_emailuser where fd_emailuser_id='$id' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$arrTemData = array(
		              'emailuser_id'=>$db->f(fd_emailuser_id),'emailuser_name'=>$db->f(fd_emailuser_name),
					  'emailuser_nick'=>$db->f(fd_emailuser_nick),'emailuser_host'=>$db->f(fd_emailuser_host),
					  'emailuser_port'=>$db->f(fd_emailuser_port),'emailuser_status'=>$db->f(fd_emailuser_status),
					  'emailuser_pwd'=>$db->f(fd_emailuser_pwd)
					  );
		
		
	}
	$action = 'edit';
}

$arrTemTypeName = array('关闭','开启');
$arrTemTypeId = array('0','1');
$seltype = makeselect($arrTemTypeName,$arrTemData['emailuser_status'],$arrTemTypeId);  //所属分类

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var($arrTemData);
$t->set_var('action',$action);
$t->set_var('seltype',$seltype);
$t->set_var("id"              , $id             );  //id
$t->set_var("gotourl"         , $gotourl        );  // 转用的地址  
$t->set_var("skin",$loginskin);
$t->pparse("out", "emailuser");    # 最后输出页面

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
?>

