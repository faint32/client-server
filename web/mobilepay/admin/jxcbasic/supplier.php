<?
$thismenucode = "2k102";     
require("../include/common.inc.php");


$db=new db_test;
$gourl = "tb_supplier_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "new":    //新增记录
	     $query="select * from tb_supplier where fd_supp_no='$no' or fd_supp_name='$name'";
	     $db->query($query);
	     if ($db->nf()){
	     	$error = "此供应商已经存在，请重新输入！";
	     }else{
	     	$query="insert into tb_supplier (
	     			   fd_supp_no, fd_supp_name  , fd_supp_allname  ,fd_supp_address,fd_supp_linkman,
					   fd_supp_manphone ,fd_supp_memo ,fd_supp_xingfen,fd_supp_workstatus
	     			    )values(     
	     		      '$no','$name'    ,'$allname', '$address','$linkman','$manphone',  '$memo'  ,'$xingfen',
					  '$workstatus'
	     		      )";
	     	$db->query($query);
	      	  require("../include/alledit.2.php");
	     	  Header("Location: $gotourl");
	     }
	     break;
	      
	case "delete":   //删除记录
	          $query="delete  from tb_supplier where fd_supp_id='$id'";
	          $db->query($query);
	          require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	     break;
	case "edit":     //编辑记录
	      $query="select * from tb_supplier where (fd_supp_name='$name' or fd_supp_no='$no')
	                       and fd_supp_id<>'$id' ";
	      $db->query($query);
	      if($db->nf()>0){
	      	  $error = "这个供应商或者已经存在,请从新输入";
	      }else{
	      	  $query="update tb_supplier set 
					  fd_supp_no = '$no',
					  fd_supp_name='$name'  ,
					  fd_supp_allname = '$allname' ,
					  fd_supp_address='$address',
					  fd_supp_linkman='$linkman',
					  fd_supp_manphone='$manphone' ,
					  fd_supp_memo='$memo' ,
					  fd_supp_xingfen='$xingfen',
					  fd_supp_workstatus='$workstatus'
	                  where fd_supp_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("supplier","supplier.html");

if(empty($id)){
	  $action = "new";
}else{ // 编辑
    $query = "select * from tb_supplier where fd_supp_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                        
	   $db->next_record();                              
	    $id          = $db->f(fd_supp_id);                    
       	$name     = $db->f(fd_supp_name);                  
       	$memo	       = $db->f(fd_supp_memo);             
       	$no          = $db->f(fd_supp_no);                          
       	$allname     = $db->f(fd_supp_allname);                    
       	$linkman	       = $db->f(fd_supp_linkman);
		$manphone          = $db->f(fd_supp_manphone);                       
       	$address     = $db->f(fd_supp_address);                       
		$xingfen     = $db->f(fd_supp_xingfen);                    
       	$workstatus	       = $db->f(fd_supp_workstatus);
		switch($workstatus){
			case "0":
				$select0 = "selected";
			break;
			case "1":
				$select1 = "selected";
			break;
			case "2":
				$select2 = "selected";
			break;
			default:
				$select3 = "selected";
			break;
		
		}	
       	$action = "edit";                   
     }  	
}

$t->set_var("id",$id); 
$t->set_var("name",$name);
$t->set_var("memo",$memo);
$t->set_var("no",$no); 
$t->set_var("allname",$allname);
$t->set_var("linkman",$linkman);
$t->set_var("address",$address); 
$t->set_var("manphone",$manphone);
$t->set_var("xingfen",$xingfen);
$t->set_var("workstatus",$workstatus);
$t->set_var("select0",$select0);
$t->set_var("select1",$select1);
$t->set_var("select2",$select2);
$t->set_var("select3",$select3);

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "supplier"); //最后输出界面

?>