<?
$thismenucode = "2k512";
require ("../include/common.inc.php");

$db = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;
$gourl = "tb_sendcenter_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch($action){
	 case "new":
	   $query2="select * from tb_sendcenter where fd_sdcr_name='$sdcr_name'";
	   $db2->query($query2);
	   if($db->nf()){
		   $error="该银联商户号已存在!";
		}else{
	   $query="INSERT INTO tb_sendcenter(
			   fd_sdcr_name,
	           fd_sdcr_active,
	           fd_sdcr_merid,
	           fd_sdcr_securitykey,
			   fd_sdcr_provcode,
			   fd_sdcr_citycode," .
			  "fd_sdcr_payfee  ," .
			  "fd_sdcr_agentfee," .
			  "fd_sdcr_minpayfee ,
			  fd_sdcr_tradeurl,
			  fd_sdcr_queryurl
				) VALUES(
	           '$sdcr_name','$sdcr_active','$sdcr_merid','$sdcr_securitykey','$sdcr_provcode','$sdcr_citycode'," .
	           "'$payfee%','$agentfee','$minpayfee','$tradeurl','$queryurl')";
	  
	   $db->query($query);
	   require("../include/alledit.2.php");
	  echo "<script>alert('保存成功!');location.href='$gotourl';</script>";	 
		}
	   break;
	 case "edit":
	   $query2="select * from tb_sendcenter where fd_sdcr_name='$sdcr_name' and fd_sdcr_id <>'$listid'";
	   $db2->query($query2);
	   if($db->nf()){
		   $error="该银联商户号已存在!";
		}else{
	   $phone = $_POST[FCKeditor1];
	   $query="update tb_sendcenter set 
	           fd_sdcr_name='$sdcr_name' ,
	           fd_sdcr_active='$sdcr_active' ,
	           fd_sdcr_payfee='$payfee%'," .
	          "fd_sdcr_agentfee = '$agentfee'," .
	          "fd_sdcr_minpayfee = '$minpayfee',
			   fd_sdcr_merid='$sdcr_merid',
			   fd_sdcr_securitykey='$sdcr_securitykey',
			   fd_sdcr_provcode='$sdcr_provcode',
			   fd_sdcr_citycode='$sdcr_citycode',
			   fd_sdcr_tradeurl='$tradeurl',
			   fd_sdcr_queryurl='$queryurl'
			  where fd_sdcr_id='$listid'";
	   $db->query($query);
	 
	   require("../include/alledit.2.php");
	   echo "<script>alert('修改成功!');location.href='$gotourl';</script>";
		}
	
	   break;
	 case "delete":
	   $query="delete from tb_sendcenter where fd_sdcr_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	    echo "<script>alert('删除成功!');location.href='$gotourl';</script>";	 
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("sendcenter","sendcenter.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_sendcenter where fd_sdcr_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$sdcr_name      =$db->f(fd_sdcr_name);
		$sdcr_active      =$db->f(fd_sdcr_active);
		$sdcr_merid    =$db->f(fd_sdcr_merid);
		$sdcr_securitykey    =$db->f(fd_sdcr_securitykey);
		$sdcr_provcode      =$db->f(fd_sdcr_provcode);
		$sdcr_citycode =$db->f(fd_sdcr_citycode);
		$payfee        =substr($db->f(fd_sdcr_payfee),0,-1);
		$agentfee        =$db->f(fd_sdcr_agentfee);
		$minpayfee     = $db->f(fd_sdcr_minpayfee);
		$tradeurl      =$db->f(fd_sdcr_tradeurl);
		$queryurl     = $db->f(fd_sdcr_queryurl);
		}
	
}
//省
if(empty($sdcr_provcode))
{
	$sdcr_provcode = "";
}
$provinces_tmp = $sdcr_provcode;
$query = "select * from tb_provinces order by fd_provinces_code asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){	
       $arr_provinces_code[]  = $db->f(fd_provinces_code)  ;
       $arr_provinces_name[]  = $db->f(fd_provinces_name)  ; 
	   if($sdcr_provcode==$db->f(fd_provinces_code))
	   {
		   $tmpaddress = $db->f(fd_provinces_name)  ;
	   }
 	}
}
$sdcr_provcode = makeselect($arr_provinces_name,$sdcr_provcode,$arr_provinces_code);

//市
$city_tmp = $sdcr_citycode;
$query = "select * from tb_city where left(fd_city_code,2) = '$provinces_tmp'
          order by fd_city_code asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){	
       $arr_city_code[]  = $db->f(fd_city_code)  ;
       $arr_city_name[]  = $db->f(fd_city_name)  ;   
	   
	    if($sdcr_citycode==$db->f(fd_city_code))
	   {
		   $tmpaddress .= $db->f(fd_city_name)  ;
	   }    
 
 	}
}
$sdcr_citycode = makeselect($arr_city_name,$sdcr_citycode,$arr_city_code);

$arr_activeid=array("0","1");
$arr_activename=array("否","是");
$sdcr_active = makeselect($arr_activename,$sdcr_active,$arr_activeid); 

$t->set_var("listid"     ,   $listid);
$t->set_var("sdcr_name"    , $sdcr_name    );
$t->set_var("sdcr_merid"    , $sdcr_merid    );
$t->set_var("sdcr_securitykey"    , $sdcr_securitykey    );
$t->set_var("sdcr_provcode"    , $sdcr_provcode    );
$t->set_var("agentfee"         , $agentfee    );
$t->set_var("payfee"           , $payfee    );
$t->set_var("minpayfee"           , $minpayfee    );
$t->set_var("sdcr_provcode"    , $sdcr_provcode    );
$t->set_var("sdcr_citycode"    , $sdcr_citycode    );
$t->set_var("sdcr_active"    , $sdcr_active    );
$t->set_var("tradeurl"    , $tradeurl    );
$t->set_var("queryurl"    , $queryurl    );
$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );        

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "sendcenter");    # 最后输出页面

?>

