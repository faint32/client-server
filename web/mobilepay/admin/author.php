<?
$thismenucode = "2k302";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");
require ("../third_api/readshopname.php");

$getFileimg = new AutogetFile;
$db = new DB_test;
$gourl = "tb_author_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch($action){
	 case "edit":
	   $query = "select * from tb_author where fd_author_username = '$name' and fd_author_id <> '$listid'";
	   $db->query($query);
	   if($db->nf()){
		  $error = "用户名有冲突，请重新查证！"; 
		}else{
	   $phone = $_POST[FCKeditor1];
	   $query="update tb_author set 
			   fd_author_username='$name'  , 
	           fd_author_truename='$truename' ,
	           fd_author_mobile='$mobile' ,
			   fd_author_idcard='$idcard',
			   fd_author_email='$email',
			   fd_author_state='$state',
			   fd_author_regtime='$zcdate',
			   fd_author_isstop='$isstop',
			   fd_author_shopid='$shopid',
			   fd_author_shoucardman = '$shoucardman',
			   fd_author_shoucardphone = '$shoucardphone',
			   fd_author_shoucardno='$shoucardno',
			   fd_author_shoucardbank='$shoucardbank'
			   where fd_author_id='$listid'";
	   $db->query($query);
	  
	   if($userpw){
	    	$userpw = md5($userpw);
	   $query="update  tb_author set fd_author_password = '$userpw' where fd_author_id = '$listid'";
	   $db->query($query);
	    }
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_author where fd_author_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","author.html"); 

if(empty($listid)){
	$action="new";
}
else{
	$action="edit";
	$query="select * from tb_author
			left join tb_authorindustry on fd_auindustry_id = fd_author_auindustryid
			left join tb_sendcenter on fd_sdcr_id = fd_author_sdcrid
	 		where fd_author_id='$listid' ";
			//left join tb_payfeeset on fd_payfset_id = fd_author_slotpayfsetid
			//left join tb_payfeeset on fd_payfset_id = fd_author_bkcardpayfsetid
			//left join tb_slotcardmoneyset on fd_scdmset_id = fd_author_slotscdmsetid
			//left join tb_slotcardmoneyset on fd_scdmset_id = fd_author_bkcardscdmsetid
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$name    =$db->f(fd_author_username);
		$truename      =$db->f(fd_author_truename);
		$mobile =$db->f(fd_author_mobile);
		$zcdate    =$db->f(fd_author_regtime);
		$idcard    =$db->f(fd_author_idcard);
		$email      =$db->f(fd_author_email);
		$state =$db->f(fd_author_state);
		$isstop    =$db->f(fd_author_isstop);
		
		$auindustryid = $db->f(fd_author_auindustryid);
		$slotpayfsetid = $db->f(fd_author_slotpayfsetid);
		$slotscdmsetid = $db->f(fd_author_slotscdmsetid);
	
		$bkpayfsetid = $db->f(fd_author_bkcardpayfsetid);
		$bkscdmsetid = $db->f(fd_author_bkcardscdmsetid);
		
		$sdcrname = $db->f(fd_sdcr_name);
		$auindustry = $db->f(fd_auindustry_name);
/*		$payfset = $db->f(fd_payfset_name);
		$scdmset = $db->f(fd_scdmset_name);
		$bkcardscdmset = $db->f(fd_scdmset_name);
		$bkcardpayfset = $db->f(fd_payfset_name);*/
		
		//$password    =$db->f(fd_author_password);
		$shopid =$db->f(fd_author_shopid);
		$shopname = getauthorshop($shopid);
		$shoucardman = $db->f(fd_author_shoucardman);
		$shoucardphone = $db->f(fd_author_shoucardphone);
		$shoucardno=$db->f(fd_author_shoucardno);
		$shoucardbank=$db->f(fd_author_shoucardbank);	
		$authortypeid = $db->f(fd_author_authortypeid);	
		if($isstop){
			
			$checked1 = "checked";
		}else{
			
			$checked2 = "checked";
		}
		switch($state){
			case "0":
				$select0 = "selected";
			break;
			case "9":
				$select1 = "selected";
			break;
			case "-1":
				$select2 = "selected";
			break;
			default:
				$select3 = "selected";
			break;
		
		}
		
		}
	
}

$db = new DB_test;
$t->set_block("author", "zzlist", "zzlists");
$query = "select * from tb_upload_scategoty where fd_scat_fcatid=1 ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		
		   $zzname     = $db->f(fd_scat_name);
		   $zzid       = $db->f(fd_scat_id);
		   $fd         = $db->f(fd_scat_foldername);
		   $fdid       = $fd."id";
		   $arr_pic    = "";
		  $arr_pic    =  explode("@@",$getFileimg->AutogetFileimg($zzid,$listid));
		   $zzurl      = $arr_pic[0];
		   //echo $zzurl;
	       $fdidval    = "";
	    
	     if(!empty($zzurl)){
	     	 $zzimg    =  "<img  height=60 width=60 src='".$zzurl."'  style='border-bottom: 1px solid #000000;border-top: 1px solid #000000;border-right: 1px solid #000000;border-left: 1px solid #000000;'/>"; 
	     }else{
	       $zzimg    =  "<span style=\"color:#3aa5d9\">图片<br>还未上传</span>";
	     }
	     
	     if($s == 0){
     	   $tr1 = "<tr>";
     	   $tr2 = "";
     	   $s++;
       }else if($s == 2){
         $s = 0;
         $tr1 = "";
     	   $tr2 = "</tr>";
     	   
       }else{
         $tr1 = "";
     	   $tr2 = "";
     	   $s++;
       }
	   //echo $zzimg."</br>";
	     
		   if($zzurl)$fdidval=$zzid;
		     $u_btn = show_btn($zzid,$listid,$fd,$zzurl); 
			  $t->set_var(array("zzname"  => $zzname    ,   
			                     "fdid"    => $fdid      , 
			                     "fdidval" => $fdidval   ,
			                     "fd"      => $fd        , 
			                     "zzurl"   => $zzurl     ,   
			                     "u_btn"   => $u_btn     ,   			                     
			                     "tr1"     => $tr1       , 	
			                     "tr2"     => $tr2       , 
			                     "zzimg"   => $zzimg     ,
								 "listid"  => $listid	, 
								 		                     		           					   
	                   ));
	       $t->parse("zzlists"  , "zzlist", true);
		  
		  //]echo $zzname; 
	     }
		 
}else{
	$t->parse("zzlists"  , "", true);
}

$arr_auindustryid[] = "";
$arr_auindustry[] = "--选择商户分类--";
$query = "select * from tb_authorindustry ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_auindustryid[] = $db->f(fd_auindustry_id);
		$arr_auindustry[] = $db->f(fd_auindustry_name);
	}
}
$auindustryid = makeselect($arr_auindustry, $audtid, $arr_auindustryid);

/*--选择商户信用卡费率套餐--*/
$query = "select fd_payfset_name from tb_payfeeset where fd_payfset_id = '$slotpayfsetid'";
$db->query($query);
if ($db->nf()) {
		$db->next_record();
		$slotpayfset = $db->f(fd_payfset_name);
}


/*--选择商户信用卡额度套餐--*/
$query = "select fd_scdmset_name from tb_slotcardmoneyset where fd_scdmset_id = '$slotscdmsetid'";
$db->query($query);
if ($db->nf()) {
		$db->next_record();
		$slotscdmset = $db->f(fd_scdmset_name);
}
/*--选择商户信用卡费率套餐--*/
$query = "select fd_payfset_name from tb_payfeeset  where fd_payfset_id = '$bkpayfsetid'";
$db->query($query);
if ($db->nf()) {
		$db->next_record();
		$bkcardpayfset = $db->f(fd_payfset_name);
}


/*--选择商户信用卡额度套餐--*/
$query = "select fd_scdmset_name from tb_slotcardmoneyset where fd_scdmset_id = '$bkscdmsetid'";
$db->query($query);
if ($db->nf()) {
		$db->next_record();
		$bkcardscdmset = $db->f(fd_scdmset_name);
}


$arr_authortypeid[] = "";
$arr_authortype[] = "--选择商户分类--";
$query = "select * from tb_authortype ";
$db->query($query);
if ($db->nf()) {
	while ($db->next_record()) {
		$arr_authortypeid[] = $db->f(fd_authortype_id);
		$arr_authortype[] = $db->f(fd_authortype_name);
	}
}
$authortypeid = makeselect($arr_authortype, $authortypeid, $arr_authortypeid);

$t->set_var("sdcrname", $sdcrname);
$t->set_var("slotscdmset", $slotscdmset);
$t->set_var("slotpayfset", $slotpayfset);
$t->set_var("bkcardscdmset", $bkcardscdmset);
$t->set_var("bkcardpayfset", $bkcardpayfset);
$t->set_var("auindustry", $auindustry);
$oFCKeditor = new FCKeditor('FCKeditor1')  ; 
$oFCKeditor->BasePath = '../FCKeditor/' ;    
$oFCKeditor->ToolbarSet = 'Normal' ;  
$oFCKeditor->Wlistidth = '568' ; 
$oFCKeditor->Height = '440' ; 
$oFCKeditor->Value      = $content;    
$fckeditor = $oFCKeditor->CreateHtml();	
$t->set_var("listid"     ,   $listid);
$t->set_var("authortypeid", $authortypeid);
$t->set_var("shopname"           , $shopname); 
$t->set_var("shopid"           , $shopid); 
$t->set_var("checked1"           , $checked1); 
$t->set_var("checked2"           , $checked2); 
$t->set_var("id"           , $id           );           //listid
$t->set_var("truename"     , $truename           );           //listid
$t->set_var("name"         , $name         );
$t->set_var("mobile"         , $mobile         );
$t->set_var("zcdate"         , $zcdate         );
$t->set_var("idcard"         , $idcard        );
$t->set_var("email"        , $email        );
$t->set_var("state"         , $state         );
$t->set_var("isstop"         , $isstop        );
$t->set_var("shopid"         , $shopid        );
$t->set_var("userpw"        , $userpw        );
$t->set_var("select0"        , $select0        );
$t->set_var("select1"        , $select1        );
$t->set_var("select2"        , $select2        );
$t->set_var("select3"        , $select3        );

$t->set_var("shoucardman", $shoucardman);
$t->set_var("shoucardphone", $shoucardphone);
$t->set_var("shoucardno", $shoucardno);
$t->set_var("shoucardbank", $shoucardbank);

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );        
$t->set_var("fckeditor"    , $fckeditor    );
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

function show_btn($scatid,$dateid,$getvalid,$getval){
    if(!empty($getval)){
      $btn = "<input class=\"jrljsqscipt\" type=\"button\" name=\"".$getvalid."colorbox\" id=\"".$getvalid."colorbox\" value=\"重传\" onclick=\"uploadimg(".$scatid.",'".$dateid."','','".$getvalid."','new','refeedback','pre".$getvalid."id')\"   />";
      $btn .= "&nbsp;&nbsp;<input class=\"jrljsqscipt\" name=\"\" type=\"button\" id='showimg' value=\"查看\" onclick=\"showbanklogo('".$getvalid."');return false;\" />";
    }else{
      $btn = "<input class=\"jrljsqscipt\" type=\"button\" name=\"".$getvalid."colorbox\" id=\"".$getvalid."colorbox\" value=\"上传\" onclick=\"uploadimg(".$scatid.",'".$dateid."','','".$getvalid."','new','refeedback','pre".$getvalid."id')\"   />";
    }
    
    return $btn;
}
?>

