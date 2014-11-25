<?
$thismenucode = "2k513";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_appmenu_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
		$query="select  * from tb_appmenu where fd_appmnu_name='$appmnu_name'";
		$db->query($query);
		if($db->nf()){
			$error = "该信息已经存在，不需要重复添加";
			}else{
	   $query="INSERT INTO tb_appmenu(
						   fd_appmnu_name,
						   fd_appmnu_pic,
						   fd_appmnu_order,
						   fd_appmnu_url,
						   fd_appmenu_default,
						   fd_appmenu_active,
						   fd_appmnu_scope,
						   fd_appmnu_no,
						   fd_appmnu_istabno,
						   fd_appmenu_authorstate,
						   fd_appmnu_amtypeid,
						   fd_appmnu_iscusfenrun,
						   fd_appmnu_isconst
						   )
				VALUES(
						   '$appmnu_name',
						   '$appmnu_pic',
						   '$appmnu_order',
						   '$appmnu_url',
						   '$appmenu_default',
						   '$appmenu_active',
						   '$appmenu_scope',
						   '$appmnu_no',
						   '$appmnu_istabno',
						   '$appmnu_authorstate',
						   '$amtypeid',
						   '$iscusfenrun',
						   '$isconst')";
			  
	   $db->query($query);
	  	
		updateappmenu();
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
   		}
		$action="";
	   break;
	 case "edit":

	   $query="select * from tb_appmenu where (fd_appmnu_name='$appmnu_name') and fd_appmnu_id<>'$listid'";
	   $db->query($query);
	   if($db->nf()){
			$error = "该信息已经存在！请查证！";   
		}else{
	   $query="update tb_appmenu set 
	   								fd_appmnu_name='$appmnu_name',
	   								fd_appmnu_pic='$appmnu_pic',
									fd_appmnu_order='$appmnu_order',
									fd_appmnu_url='$appmnu_url',
									fd_appmenu_default='$appmenu_default',
									fd_appmenu_active='$appmenu_active',
									fd_appmnu_scope='$appmenu_scope',
									fd_appmnu_no='$appmnu_no',
									fd_appmnu_istabno='$appmnu_istabno',
									fd_appmenu_authorstate='$appmnu_authorstate',
									fd_appmnu_amtypeid = '$amtypeid',
									fd_appmnu_iscusfenrun = '$iscusfenrun',
									fd_appmnu_isconst     = '$isconst'
									where fd_appmnu_id='$listid'";
	   //echo $appmnu_istabno;exit;
	   $db->query($query);
       //    echo $query;
	   updateappmenu();
	   require("../include/alledit.2.php");
	  Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_appmenu where fd_appmnu_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
		updateappmenu();
	   Header("Location: $gotourl");	
	   break;
	 default:
	 	$action = "";
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("appmenu","appmenu.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	
	$action="edit";
	$query="select * from tb_appmenu where fd_appmnu_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$appmnu_id = $db->f(fd_appmnu_id);
		$appmnu_name =$db->f(fd_appmnu_name);
		$appmnu_pic =$db->f(fd_appmnu_pic);
		$appmnu_order =$db->f(fd_appmnu_order);
		$appmnu_url =$db->f(fd_appmnu_url);
		$appmenu_default =$db->f(fd_appmenu_default);
		$appmenu_active =$db->f(fd_appmenu_active);	
		$appmenu_scope =$db->f(fd_appmnu_scope);
		$appmnu_no =$db->f(fd_appmnu_no);
		$appmnu_istabno =$db->f(fd_appmnu_istabno);		
		$appmnu_authorstate=$db->f(fd_appmenu_authorstate);
        $amtypeid=$db->f(fd_appmnu_amtypeid);
        $iscusfenrun = $db->f(fd_appmnu_iscusfenrun);
        $isconst = $db->f(fd_appmnu_isconst);

		//$authorlevel_remark =$db->f(fd_authorlevel_remark);		
	}
		
	
}
$arr_id=array("creditcard","bankcard","all");
$arr_name=array("信用卡","储蓄卡","两种卡都可以");
$appmenu_scope=makeselect($arr_name,$appmenu_scope,$arr_id);

$arr_isconstid =array("0","1");
$arr_isconstname=array("非固定功能","固定功能");
$isconst=makeselect($arr_isconstname,$isconst,$arr_isconstid);


$arr_iscusfenrunid =array("0","1");
$arr_iscusfenrunname=array("不计入代理商分润","计入代理商分润");
$iscusfenrun=makeselect($arr_iscusfenrunname,$iscusfenrun,$arr_iscusfenrunid);


$query = "select * from tb_appmenutype where fd_amtype_active = '1'";
$db->query($query);
if($db->nf())
{
    while($db->next_record())
    {
        $arr_amtypeid[] =$db->f(fd_amtype_id);
        $arr_amtypename[] =$db->f(fd_amtype_name);
    }
}
$amtypeid=makeselect($arr_amtypename,$amtypeid,$arr_amtypeid);
$arr_activeid=array("0","1");
$arr_activename=array("未激活","已激活");
$appmenu_active=makeselect($arr_activename,$appmenu_active,$arr_activeid);

$arr_autstaid=array("0","9");
$arr_autstaname=array("所有用户","认证用户");
$appmnu_authorstate=makeselect($arr_autstaname,$appmnu_authorstate,$arr_autstaid);
switch($appmnu_istabno)  //费率计算方式
{
	case "1":
	$checkd1 = "checked";
	break;
	default:
	$checkd2 = "checked";
	break;
}

$t->set_var("id"           , $id           );           //listid
$t->set_var("listid"     , $listid           );           //listid
$t->set_var("appmenu_scope"         , $appmenu_scope         );
$t->set_var("amtypeid"         , $amtypeid         );
$t->set_var("iscusfenrun"         , $iscusfenrun         );
$t->set_var("isconst"         , $isconst         );
$t->set_var("appmnu_name"         , $appmnu_name         );
$t->set_var("appmnu_pic"         , $appmnu_pic         );
$t->set_var("appmnu_order"         , $appmnu_order         );
$t->set_var("appmnu_url"         , $appmnu_url         );
$t->set_var("appmenu_default"         , $appmenu_default         );
$t->set_var("appmenu_active"         , $appmenu_active         );
$t->set_var("appmnu_no"         , $appmnu_no         );
$t->set_var("appmnu_istabno"         , $appmnu_istabno         );
$t->set_var("checkd1"         , $checkd1         );
$t->set_var("checkd2"         , $checkd2         );
$t->set_var("appmnu_authorstate"     , $appmnu_authorstate);

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "appmenu");    # 最后输出页面
function updateappmenu()//更新版本号
{
	$db = new DB_test;
	$query="select fd_appmnu_version as version  from tb_appmenu";
	$arr_data=$db->get_one($query);
	$version=$arr_data['version']+0.1;
	$query="update tb_appmenu set fd_appmnu_version=$version";
	$db->query($query);
}

?>

