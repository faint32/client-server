<?
$thismenucode = "1c208";
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl="authorpayset.php";
$t = new Template(".","keep");

switch($action)
{
	case "save":
	
	 for($i=0;$i<count($arr_wlroadid);$i++){
		 $wlroadid      =$arr_wlroadid[$i];
		 $arr_wldpid1        =$arr_wldpid[$wlroadid];
		 //echo var_dump($arr_wldpid1);
		  for($j=0;$j<count($arr_wldpid1);$j++)
		  {
		   
			$wlmoney       =$arr_wlmoney[$wlroadid][$j];
			//echo $wlmoney."<>";
			$wldunpriceid  =$arr_wldpid1[$j];
			$query = "select * from web_authorpayset where fd_authorpayset_wldpid = '$wldunpriceid' and fd_authorpayset_wlroadid = '$wlroadid'
			          and fd_authorpayset_organmemid = '$organmemid'";
			$db->query($query);
			if($db->nf())
			{
				$db->next_record();
				$vlid  = $db->f(fd_authorpayset_id);
			    $query1="update web_authorpayset set fd_authorpayset_money = '$wlmoney' where
				 fd_authorpayset_id ='$vlid'";
      	        $db2->query($query1); 
			}else
			{
		    $query="insert into web_authorpayset(fd_authorpayset_wldpid,
			        fd_authorpayset_wlroadid,fd_authorpayset_money,fd_authorpayset_organmemid) values('$wldunpriceid','$wlroadid','$wlmoney','$organmemid')";
      	    $db->query($query); 
			}
		  }
      
    }
	    
	$action="";
	 echo "<script>alert('修改成功');location.href='authorpayset.php'</script>";
	//require("../include/alledit.2.php");
	//Header("Location: $gotourl");
	break;
	default:
	$action="";
	break;
	
}

$t->set_file("template","authorpayset.html");
$t->set_block("template", "BXBK"  , "BXBKs"); 

$arr_text= array("编号","商户类型","卡类型","单笔手续费","单笔限额（万元）
","单日限额(万元)","月限额（万元）","每笔手续费（元）","单月还款次数");



for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}
$query = "select * from tb_authortype  _wldunprice "; 
$db->query($query);
$count=0;//记录数
if($db->nf()){
	while($db->next_record()){		
		   $arr_authortypeid[]       = $db->f(fd_authortype_id);
		   $arr_authortypename[]       = $db->f(fd_authortype_name);
		   // $arr_wldp[]= $vid; 
	}
}
$query = "select * from tb_paycardtype "; 
$db->query($query);
$count=0;//记录数
if($db->nf()){
	while($db->next_record()){		
		   $arr_paycardtypeid[]       = $db->f(fd_paycardtype_id);
		   $arr_paycardtypename[]       = $db->f(fd_paycardtype_name);
	}
}
$arr_wldp=array("","","","","","");
foreach($arr_authortypeid as $atypekey=>$authortypeid)
{
	foreach($arr_paycardtypeid as $ptypekey=>$paycardtypeid)
	{
		   $authortypename = $arr_authortypename[$atypekey];
		   $paycardtypename= $arr_paycardtypename[$ptypekey];
		   
		   
		   $show .= "<tr><td>".$vroad."<input type='hidden' value='".$vid."'   name='arr_wlroadid[]'></td>";
		   $show .= "<td>".$authortypename."</td>";
		   $show .= "<td>".$paycardtypename."</td>";
		   for($i=0;$i<count($arr_wldp);$i++)
		   {
		   	$wldp          = $arr_wldp[$i];
			$money         = $arr_money[$wldp][$vid][$organmemid]; 
			$vmoney        = "<input type='text'  class='input full' value='".$money."' name='arr_wlmoney[$vid][]'>
	                          <input type='hidden' value='".$wldp."'  name='arr_wldpid[$vid][]'>";
		     $show .= "<td>".$vmoney."</td>";
		   }
		   $show .= "</tr>";
	}
}

$t->set_var("theadth"           , $theadth ); 
$t->set_var("show"              , $show );  

include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");

?>