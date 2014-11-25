<? 
require("../include/common.inc.php");
include ("../include/pageft.php");
$db=new db_test;
$gourl = "app_interface_b.php?listid=$listid" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$t = new Template(".","keep");
$t->set_file("app_interface","app_interface.html");
switch ($action){
	case "new":	
		$query="select * from web_test_interface where fd_interface_no='$intfno' or fd_interface_name='$intfname'";
		$db->query($query);
		if($db->nf()){
			$error="该接口已经存在！";
		}else{
			$query="INSERT INTO web_test_interface (fd_interface_name,fd_interface_demo,fd_interface_no,fd_interface_ischeck,
					fd_interface_apinamefunc,fd_interface_apiname,fd_interface_active,fd_interface_sortorder,
					fd_interface_appmenuid,fd_interface_xml) 
					VALUES('$intfname','$demo','$intfno','$ischeck','$apinamefunc','$apiname','$active','$orderby','$listid','$intfxml')";
			$db->query($query);
			require("../include/alledit.2.php");
			Header("Location: $gotourl");
		}
	$action="";
	break;
	
	case "edit":
	   $query="select * from web_test_interface where fd_interface_no='$intfno' and fd_interface_id <>'$intfid'";
	   $db->query($query);
	   if($db->nf()){
		   $error="该接口已经存在,请查证!";
		}else{
	   $query="update web_test_interface set 
	           fd_interface_name='$intfname' ,
	           fd_interface_demo='$demo' ,
	           fd_interface_no='$intfno',
			   fd_interface_ischeck = '$ischeck',
	           fd_interface_apinamefunc = '$apinamefunc',
			   fd_interface_apiname='$apiname',
			   fd_interface_xml='$intfxml',
			   fd_interface_sortorder='$orderby',
			   fd_interface_active='$active'
			  where fd_interface_id='$intfid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	   
	 case "delete":
	   $query="delete from web_test_interface where fd_interface_id='$intfid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
		Header("Location: $gotourl");
	    //echo "<script>alert('删除成功!');location.href='$gotourl';</script>";	 
	   break;
	 default:
	   break;
}
if(empty($intfid)){
	$action="new";
}else{	
	 // 编辑
	$action="edit";
    $query = "select * from web_test_interface 
					where fd_interface_id = ".$intfid;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
    
		  
			$intfname    = $db->f(fd_interface_name);               //编号  
       		$demo        = $db->f(fd_interface_demo);             //部门名   
			$intfno      = $db->f(fd_interface_no); 
			$ischeck     = $db->f(fd_interface_ischeck);
			$apinamefunc = $db->f(fd_interface_apinamefunc);   
		    $apiname     = $db->f(fd_interface_apiname); 
		    $active      = $db->f(fd_interface_active);
			$intfxml	 = $db->f(fd_interface_xml);	
			$orderby	 = $db->f(fd_interface_sortorder);		  
  }  	
}
	switch($active)  
		{
			case "1":
			$checkactive1 = "checked";
			break;
			default:
			$checkactive2 = "checked";
			break;
		}
	switch($ischeck)
		{
			case "1":
			$checkischeck1 = "checked";
			break;
			default:
			$checkischeck2 = "checked";
			break;
		}
$t->set_var($arrdata);
$t->set_var("listid"          ,$listid         );
$t->set_var("intfid"          ,$intfid         );
$t->set_var("orderby"          ,$orderby         );
$t->set_var("active",$active);
$t->set_var("ischeck"  ,$ischeck);
$t->set_var("intfname",$intfname);
$t->set_var("demo"  ,$demo);
$t->set_var("intfno",$intfno);
$t->set_var("apinamefunc",$apinamefunc);
$t->set_var("apiname",$apiname);
$t->set_var("intfxml",$intfxml);
$t->set_var("checkactive1",$checkactive1);
$t->set_var("checkactive2",$checkactive2);
$t->set_var("checkischeck1",$checkischeck1);
$t->set_var("checkischeck2",$checkischeck2);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "app_interface"); //最后输出界面

?>