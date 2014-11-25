<?
$thismenucode = "2n101";
require ("../include/common.inc.php");

$db  = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;


$gourl = "moneyqj.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
//echo $toaction;
switch($toaction){
	case "del":   //删除细节表数据
      		  $query="delete from tb_moneyqj where fd_moneyqj_id = '$vid'";
      	    $db->query($query); 
   Header("Location:$gotourl");
	  break;
	case "save":  //新增数据

	    if(empty($id)){
	    	$query = "insert into tb_moneyqj (
	    	fd_moneyqj_start , fd_moneyqj_end ,fd_moneyqj_type
	    	)values(
	    	'$start' ,  '$end' ,'$moneytype'
	    	)
	    	";
	    	$db->query($query);
	    	
	    }else{
	    	$query = "update tb_moneyqj set fd_moneyqj_start = '$start'  , fd_moneyqj_end = '$end'
	    	          where fd_moneyqj_id = '$id'  ";
	   
	    		$db->query($query);
	    }
	
		Header("Location:$gotourl");
	  break;
	  
	default:
	
	  $action="";
	  break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("template","moneyqj.html"); 

if (empty($listid))
{		// 新增
   $toaction = "new";
}else{
  
}


if(!empty($vid)){
	  $query = "select* from tb_moneyqj where fd_moneyqj_id = '$vid'";
                             
   $db->query($query);
   if($db->nf()){
   	   $db->next_record();
   	   $id         = $db->f(fd_moneyqj_id);            //id号  
       $start      = $db->f(fd_moneyqj_start)+0;            //单据编号
       $end        = $db->f(fd_moneyqj_end)+0;            //单据编号
	   $moneytype  = $db->f(fd_moneyqj_type)+0;            //单据编号
	  $disabled="disabled=disabled";
   }
	

}

$arr_code=array(0,1,2,3);
$arr_name=array("请选择","信用卡还款","转账汇款","信贷还款");
$moneytype=makeselect($arr_name,$moneytype,$arr_code);

$arr_searchname=array("显示全部","信用卡还款","转账汇款","信贷还款");
if($searchcontent)
{
	$searchquery="and fd_moneyqj_type='$searchcontent'";
}
$searchcontent=makeselect($arr_searchname,$searchcontent,$arr_code);
//显示列表
$t->set_block("template", "prolist"  , "prolists"); 
$query = "select * from tb_moneyqj where 1 $searchquery"; 
$db->query($query);
$count=0;//记录数
if($db->nf()){
	while($db->next_record()){		
		   $vid       = $db->f(fd_moneyqj_id);
		   $vstart   = $db->f(fd_moneyqj_start)+0;  
		   $vend   = $db->f(fd_moneyqj_end)+0; 
			$vmoneytype  = $db->f(fd_moneyqj_type);  		   
		   $count++;
		   
		   $trid  = "tr".$count;
		   $imgid = "img".$count;
		   
		   if($s==1){            
          $bgcolor="#F1F4F9";  
          $s=0;                
        }else{                
          $bgcolor="#ffffff";  
          $s=1;                
        }   
		
		   $vmoneytype=$arr_name[$vmoneytype];
		   $t->set_var(array("trid"         => $trid          ,
                         "imgid"        => $imgid         ,
                         "vid"          => $vid           ,
                         "vstart"    => $vstart     ,
                         "vend"    => $vend     ,
						 "vmoneytype"    => $vmoneytype     ,
                           "bgcolor"      => $bgcolor,
                        
				          ));
		  $t->parse("prolists", "prolist", true);	
	}
}else{

		  $t->parse("prolists", "", true);	
}      

$t->set_var("searchcontent"       , $searchcontent       );      //单据id 
$t->set_var("listid"       , $listid       );      //单据id 
$t->set_var("id"           , $id           );      //id 
$t->set_var("end"    , $end           );      //id 
$t->set_var("start"    , $start           ); 
$t->set_var("error"    , $error           );
$t->set_var("moneytype"    , $moneytype           );
$t->set_var("disabled"    , $disabled           );
$t->set_var("toaction"    , $toaction           );  
$t->set_var("gotourl"    , $gotourl           ); 


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "template");    # 最后输出页面



?>

