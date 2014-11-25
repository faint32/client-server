<?

$thismenucode = "7n003";     
require("../include/common.inc.php");

$db=new db_test;

$gourl = "tb_version_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
echo $action;
switch ($action){  
	case "new":    //新增记录

		$query="insert into tb_version (
							fd_version_no    ,  fd_version_apptype    ,   fd_version_updatetime		,	fd_version_datetime	,
							fd_version_isnew,	fd_version_downurl ,   fd_version_clearoldinfo	,   fd_version_newcontent,
							fd_version_strupdate
							)values(
							'$no'            ,  '$apptype'   	,    '$updatetime'		, 	now()    ,
							'$isnew' 		 ,	'$downurl'	,	 '$clearoldinfo'	,	'$newcontent',
							'$strupdate'
							)"; 
		$db->query($query);
	      Header("Location: $gotourl");
	     break;
	      
	case "delete":   //删除记录
	      $query="delete  from tb_version where fd_version_id='$id'";
	      $db->query($query);
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
	      
	     break;
	case "edit":     //编辑记录

		  $query="update tb_version set 
						 fd_version_no='$no'                    ,  fd_version_downurl='$downurl'     , 
						 fd_version_datetime=now()   				,  fd_version_isnew='$isnew'		, fd_version_updatetime='$updatetime',  
						 fd_version_clearoldinfo='$clearoldinfo'  			,  fd_version_newcontent='$newcontent'		, fd_version_apptype='$apptype',
						 fd_version_strupdate='$strupdate'
						 where fd_version_id ='$id'	";
		  $db->query($query);
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("version","version.html");

if(empty($id)){
	
	  $checked1 = "checked";
	
	  $action = "new";
}else{ // 编辑
    $query = "select * from tb_version
               where fd_version_id ='$id'
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
       	$no            = $db->f(fd_version_no);               //编号  
       	$apptype          = $db->f(fd_version_apptype);                          
        $updatetime          = $db->f(fd_version_updatetime);   
		$datetime		   = $db->f(fd_version_datetime); 
		$isnew		   = $db->f(fd_version_isnew);
		$downurl	   = $db->f(fd_version_downurl);
		$clearoldinfo		   = $db->f(fd_version_clearoldinfo);
		$newcontent		   = $db->f(fd_version_newcontent);
		$strupdate	   = $db->f(fd_version_strupdate);
		
		if($isnew){
			
			$checked1 = "checked";
		}else{
			
			$checked2 = "checked";
		}
		
		if($strupdate){
			
			$checked3 = "checked";
		}else{
			
			$checked4 = "checked";
		}
		if($clearoldinfo){
			
			$checked5 = "checked";
		}else{
			
			$checked6 = "checked";
		}
		switch($apptype){
			case "1":
				$select1 = "selected";
			break;
			case "2":
				$select2 = "selected";
			break;
			case "3":
				$select3 = "selected";
			break;
			case "4":
				$select4 = "selected";
			break;
			default:
				$select0 = "selected";
			break;
		
		}
       	$action = "edit";                   
     }  	
}





$t->set_var("id"         ,$id         );
$t->set_var("no"         ,$no         );
$t->set_var("apptype"       ,$apptype       );
$t->set_var("datetime"        ,$datetime        );

$t->set_var("updatetime"       	  ,$updatetime        );
$t->set_var("clearoldinfo"       	  ,$clearoldinfo        );
$t->set_var("newcontent"       	  ,$newcontent        );
$t->set_var("strupdate"         ,$strupdate        );
$t->set_var("downurl"         ,$downurl        );
$t->set_var("checked1"        ,$checked1        );
$t->set_var("checked2"        ,$checked2        );
$t->set_var("checked3"        ,$checked3        );
$t->set_var("checked4"        ,$checked4        );
$t->set_var("checked5"        ,$checked5        );
$t->set_var("checked6"        ,$checked6        );
$t->set_var("select0"        ,$select0        );
$t->set_var("select1"        ,$select1        );
$t->set_var("select2"        ,$select2        );
$t->set_var("select3"        ,$select3        );
$t->set_var("select4"        ,$select4        );


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "version"); //最后输出界面

?>