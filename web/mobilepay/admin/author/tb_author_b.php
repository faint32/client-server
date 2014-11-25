<?
$thismenucode = "2k302";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../third_api/readshopname.php");

class tb_author_b extends browse 
{
	 //var $prgnoware    = array("会员","会员管理");
	 var $prgnoware = array ("商户管理", "商户资料管理" );
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_author_id";
	 
	 var $browse_queryselect = "select * from tb_author
	 							              left join tb_authorindustry on fd_auindustry_id = fd_author_auindustryid
	 							              left join tb_authortype on fd_authortype_id = fd_author_authortypeid 
								              left join tb_sendcenter on fd_sdcr_id = fd_author_sdcrid
								            ";

	 var $browse_edit   = "author.php?listid=" ;
	 var $browse_field = array("fd_author_id","fd_author_slotpayfsetid","fd_author_slotscdmsetid","fd_author_bkcardpayfsetid","fd_author_bkcardscdmsetid","fd_author_username","fd_authortype_name","fd_author_idcard","fd_author_truename","fd_author_mobile","fd_author_email","fd_author_regtime","fd_author_state","fd_author_isstop","fd_author_shopid","fd_sdcr_name","fd_auindustry_name","fd_author_shoucardman","fd_author_shoucardno");
 	 var $browse_find = array(		// 查询条件
				"0" => array("明盛公户" , "fd_sdcr_name","TXT"),
				"1" => array("用户名" , "fd_author_username","TXT"),
				"2" => array("真实姓名" , "fd_author_truename","TXT"),
				"3" => array("手机号码" , "fd_author_mobile","TXT"),
				"4" => array("身份证号码" , "fd_author_idcard","TXT")
				); 
}

class  fd_author_id  extends browsefield {
        var $bwfd_fdname = "fd_author_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class  fd_author_slotpayfsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_slotpayfsetid";	// 数据库中字段名称
        var $bwfd_title = "信用卡费率套餐";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	
			$this->bwfd_show=getauthorset("tb_payfeeset","payfset",$this->bwfd_value);	
        	      		     
		      return $this->bwfd_show ;
  	    } 
}
class  fd_author_slotscdmsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_slotscdmsetid";	// 数据库中字段名称
        var $bwfd_title = "信用卡额度套餐";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	$this->bwfd_show=getauthorset("tb_slotcardmoneyset","scdmset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    } 
}
class  fd_author_bkcardpayfsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_bkcardpayfsetid";	// 数据库中字段名称
        var $bwfd_title = "借记卡费率套餐";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	$this->bwfd_show=getauthorset("tb_payfeeset","payfset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    }
}
class  fd_author_bkcardscdmsetid  extends browsefield {
        var $bwfd_fdname = "fd_author_bkcardscdmsetid";	// 数据库中字段名称
        var $bwfd_title = "借记卡额度套餐";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	$this->bwfd_show=getauthorset("tb_slotcardmoneyset","scdmset",$this->bwfd_value);	
		      return $this->bwfd_show ;
  	    } 
}
class fd_author_username  extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}
class fd_author_idcard  extends browsefield {
        var $bwfd_fdname = "fd_author_idcard";	// 数据库中字段名称
        var $bwfd_title = "身份证";	// 字段标题
        var $bwfd_format = "idcard";
}
class fd_author_password  extends browsefield {
        var $bwfd_fdname = "fd_author_password";	// 数据库中字段名称
        var $bwfd_title = "密码";	// 字段标题
}
class fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "真实名";	// 字段标题
}
class fd_author_mobile  extends browsefield {
        var $bwfd_fdname = "fd_author_mobile";	// 数据库中字段名称
        var $bwfd_title = "手机号码";	// 字段标题
}
class fd_author_email  extends browsefield {
        var $bwfd_fdname = "fd_author_email";	// 数据库中字段名称
        var $bwfd_title = "电子邮箱";	// 字段标题
}

class fd_authortype_name extends browsefield {
        var $bwfd_fdname = "fd_authortype_name";	// 数据库中字段名称
        var $bwfd_title = "商户分类";	// 字段标题
}
class fd_author_regtime extends browsefield {
        var $bwfd_fdname = "fd_author_regtime";	// 数据库中字段名称
        var $bwfd_title = "注册时间";	// 字段标题
}
class fd_author_state extends browsefield {
        var $bwfd_fdname = "fd_author_state";	// 数据库中字段名称
        var $bwfd_title = "状态";	// 字段标题
		        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "待审核";
        		     break;       		
        		case "9":
        		    $this->bwfd_show = "审核通过";
        		     break;
        		case "-1":
        		    $this->bwfd_show = "已注销";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_isstop extends browsefield {
        var $bwfd_fdname = "fd_author_isstop";	// 数据库中字段名称
        var $bwfd_title = "是否冻结";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_author_shopid extends browsefield {
        var $bwfd_fdname = "fd_author_shopid";	// 数据库中字段名称
        var $bwfd_title = "商户名";	// 字段标题
        function makeshow() { // 将值转为显示值
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getauthorshop ($this->bwfd_value);
		return $this->bwfd_show;
		}
       
}
class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// 数据库中字段名称
        var $bwfd_title = "明盛公户";	// 字段标题
       
}
class fd_auindustry_name extends browsefield {
        var $bwfd_fdname = "fd_auindustry_name";	// 数据库中字段名称
        var $bwfd_title = "商户所属行业";	// 字段标题
       
}
class fd_author_shoucardman extends browsefield {
        var $bwfd_fdname = "fd_author_shoucardman";	// 数据库中字段名称
        var $bwfd_title = "收款人";	// 字段标题
       
}
class fd_author_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_author_shoucardno";	// 数据库中字段名称
        var $bwfd_title = "收款卡号";	// 字段标题
       
}
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "authorsetmeal.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>套餐查看</span>";  
   
} 

class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorpaycard.php?type=check&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看刷卡器</span>";  
   
} 

class lk_view2 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorbank.php?type=check&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看信用卡</span>";  
   
} 

if(empty($order)){
	$order = "fd_author_regtime";
	$upordown = "desc";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_b_bu = new tb_author_b ;
$tb_author_b_bu->browse_skin = $loginskin ;
$tb_author_b_bu->browse_delqx = 1;  // 删除权限
$tb_author_b_bu->browse_addqx = 1;  // 新增权限
$tb_author_b_bu->browse_editqx = 1;  // 编辑权限
$tb_author_b_bu->browse_querywhere = "fd_author_isstop = 0 and fd_author_state = '9'";
$tb_author_b_bu->browse_link  = array("lk_view0","lk_view1","lk_view2"); 
$tb_author_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;

function getauthorset($tabname,$filename,$id)//获取商家套餐
{
	$db = new DB_test;
	$query = "select fd_".$filename."_name as name from  $tabname
	where fd_".$filename."_id='$id'";
	
	$db->query($query);
	$arr_data=$db->getFiledData();
	return $arr_data['0']['name'];
} 
?>
