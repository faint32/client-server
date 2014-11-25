<?
$thismenucode = "1c602";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("帮助管理","帮助类型");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_helpset_id";
	 
	 var $browse_queryselect = "select * from web_helpset 
	                            ";
	// var $browse_delsql = "delete from web_helpset where fd_helpset_id = '%s'" ;
	// var $browse_new    = "helpset.php" ;
	 var $browse_edit   = "helpset.php?id=" ;
	 //var $browse_outtoexcel ="excelwriter_procatalog.php";
	 //var $browse_inputfile = "input_procatalog.php";

	 var $browse_field = array("fd_helpset_name");
 	 var $browse_find = array(		// 查询条件
				"0" => array("帮助类型" , "fd_helpset_name","TXT"),

				);
	 
	 function dodelete(){	//  删除过程.
	 	   $ishvaeshow = 0 ;
	 	   $valname ="";
  	   for($i=0;$i<count($this->browse_check);$i++){
  	   	  $ishvaeflage = 0 ;
  	   	  $query = "select * from web_help where fd_help_type = '".$this->browse_check[$i]."'";
  	   	  $this->db->query($query);    //查询商品单位
  	   	  if($this->db->nf()){
  	   	  	$ishvaeflage = 1 ;
  	   	  	$ishvaeshow = 1 ;
  	   	  }
  	   	  if($ishvaeflage==0){
  	   	    $query = sprintf($this->browse_delsql,$this->browse_check[$i]);
      	   	$this->db->query($query);  //删除点击的记录
  	   	  }else{
  	   	    $query = "select * from web_helpset where fd_helpset_id = '".$this->browse_check[$i]."'";
  	   	  	$this->db->query($query);  //查询类别
  	   	    if($this->db->nf()){
  	   	    	$this->db->next_record();
  	   	    	$name = $this->db->f(fd_helpset_name);
  	   	    	$valname .= $name."、 ";
  	   	    }
  	   	  }
       }
       if($ishvaeshow==1){
       	  echo "<script>alert('以下帮助类型是已经绑定帮助了不能删除: ".$valname."')</script>";
       }
    }

}

class fd_helpset_name extends browsefield {
        var $bwfd_fdname = "fd_helpset_name";	// 数据库中字段名称
        var $bwfd_title = "帮助类型";	// 字段标题
}



if(empty($order)){
	$order = "fd_helpset_name";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
