<?
$thismenucode = "2k301";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../third_api/readshopname.php");
class tb_author_sp_b extends browse {
	//var $prgnoware = array ("会员", "未审核会员" );
	var $prgnoware = array ("商户管理", "待审核商户" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_author_id";
	
	var $browse_queryselect = "select * from tb_author
	 							left join tb_authorindustry on fd_auindustry_id = fd_author_auindustryid
	 						   left join tb_authortype on fd_authortype_id = fd_author_authortypeid 
								left join tb_sendcenter on fd_sdcr_id = fd_author_sdcrid ";
	
	var $browse_edit = "author_sp.php?listid=";
	var $browse_editname = "审核";
		 var $browse_delsql = "delete from tb_author where fd_author_id = '%s'" ;
	var $browse_field = array ("fd_author_id","fd_author_username", "fd_author_truename", "fd_author_regtime","fd_author_shopid","fd_author_shoucardman","fd_author_shoucardphone","fd_author_shoucardbank","fd_author_shoucardno","fd_author_datetime",  "fd_author_state", "fd_author_isstop","fd_author_slotpayfsetid","fd_author_slotscdmsetid","fd_author_bkcardpayfsetid","fd_author_bkcardscdmsetid", "fd_authortype_name",  "fd_author_mobile","fd_sdcr_name","fd_auindustry_name" );
	var $browse_find = array (// 查询条件
				"0" => array("编号" , "fd_author_id","TXT"),
				"1" => array("用户名" , "fd_author_username","TXT"),
				"2" => array("真实姓名" , "fd_author_truename","TXT"),
				"3" => array("手机号码" , "fd_author_mobile","TXT"),
				"4" => array("身份证号码" , "fd_author_idcard","TXT"),
				"5" => array("时间" , "fd_author_datetime","TXT")
				
				);
	function dodelete(){ //  删除过程
		$db = new DB_test();
		for($i=0; $i<count($this->browse_check); $i++)
		{
			$query = "update  tb_paycard set fd_paycard_authorid='' where fd_paycard_authorid=".$this->browse_check[$i]."";
			$db->query($query);
					
			$query = sprintf($this->browse_delsql,$this->browse_check[$i]);
			$this->db->query($query); //删除点击的记录
		}
	}		
}

class fd_author_shoucardphone extends browsefield {
	var $bwfd_fdname = "fd_author_shoucardphone"; // 数据库中字段名称
	var $bwfd_title = "收款人电话"; // 字段标题
}

class fd_author_shoucardbank extends browsefield {
	var $bwfd_fdname = "fd_author_shoucardbank"; // 数据库中字段名称
	var $bwfd_title = "收款银行"; // 字段标题
}

class fd_author_id extends browsefield {
	var $bwfd_fdname = "fd_author_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
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
class fd_author_username extends browsefield {
	var $bwfd_fdname = "fd_author_username"; // 数据库中字段名称
	var $bwfd_title = "用户名"; // 字段标题
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // 数据库中字段名称
	var $bwfd_title = "真实名"; // 字段标题
}
class fd_author_mobile extends browsefield {
	var $bwfd_fdname = "fd_author_mobile"; // 数据库中字段名称
	var $bwfd_title = "手机号码"; // 字段标题
}
class fd_author_email extends browsefield {
	var $bwfd_fdname = "fd_author_email"; // 数据库中字段名称
	var $bwfd_title = "电子邮箱"; // 字段标题
}
class fd_authortype_name extends browsefield {
        var $bwfd_fdname = "fd_authortype_name";	// 数据库中字段名称
        var $bwfd_title = "商户分类";	// 字段标题
}

class fd_author_datetime extends browsefield {
	var $bwfd_fdname = "fd_author_datetime"; // 数据库中字段名称
	var $bwfd_title = "修改时间"; // 字段标题
}

class fd_author_regtime extends browsefield {
	var $bwfd_fdname = "fd_author_regtime"; // 数据库中字段名称
	var $bwfd_title = "注册时间"; // 字段标题
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
        var $bwfd_title = "所属总仓";	// 字段标题
       
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
	var $bwlk_fdname = array (// 所需数据库中字段名称
"0" => array ("fd_author_id" ) );
	var $bwlk_prgname = "author_sp.php?listid=";
	var $bwlk_title = "<span style='color:#0000ff'>审核</span>";

}
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorpaycard.php?type=author_sp&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看刷卡器</span>";  
   
} 

class lk_view2 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_author_id") 
   			    );
   var $bwlk_prgname = "checkauthorbank.php?type=author_sp&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看信用卡</span>";  
   
} 
if(empty($order)){
	//$order = "fd_author_id";
	$order = "fd_author_regtime";
	$upordown = "desc";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = 100;
}

$tb_author_sp_b_bu = new tb_author_sp_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_author_sp_b_bu->browse_querywhere = "(fd_author_state = 0  or  fd_author_state is Null )  and fd_author_isstop = 0";
$tb_author_sp_b_bu->browse_link = array ("lk_view1","lk_view2" );
$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );

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
