<? 
require("../include/common.inc.php");
include ("../include/pageft.php");

$db=new db_test;
$gourl = "authorpaycard_query.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

      	   

$t = new Template(".","keep");
$t->set_file("authorpaycard","authorpaycard.html");
$t->set_block ( "authorpaycard_query", "prolist", "prolists" );
if(!empty($listid)){
	
	 // 编辑
    $query = "select fd_agpm_bkntno as bkntno,
				 count(fd_agpm_paycardid) as paynum,		
				 sum(fd_agpm_payfee) as payfee,
				 sum(fd_agpm_sdcrpayfeemoney) as sdcrpayfeemoney,
				 sum(fd_agpm_sdcragentfeemoney) as sdcragentfeemoney,
				 fd_agpm_shoucardno as shoucardno,
                 fd_agpm_shoucardbank as shoucardbank,
				 fd_agpm_shoucardman as shoucardman,
                 fd_agpm_shoucardmobile as shoucardmobile,
				 fd_agpm_paytype as paytype,
				 fd_agpm_paydate as paydate,
				 fd_agpm_no as no,
				 fd_agpm_id as agpmid,	
				 fd_author_truename as authorname,
				 fd_paycard_key as paycardkey,
				 fd_paycardtype_name as paycardtype,
				 fd_paycard_scope as paycardscope,
                 fd_agpm_paymoney as paymoney,
				 fd_agpm_state as agpmstate,
				 fd_paycard_batches as batches,
				 fd_agpm_money as money,
				 fd_agpm_paymoney as paymoney from tb_agentpaymoneylist   
				 left join tb_author on fd_author_id = fd_agpm_authorid
				 left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
				 left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid
               where fd_agpm_id ='$listid' group by fd_agpm_paycardid order by fd_agpm_no
             " ;
   $db->query($query);
$total= $db->num_rows($result);
pageft($total,20,$url);
if($firstcount < 0){
	$firstcount = 0 ;
}
$query = "$query limit $firstcount,$displaypg"; 
//echo $query;			  
$db->query($query);
$rows = $db->num_rows();
$arr_result = $db->getFiledData('');
foreach($arr_result as $value)
{	
$t->set_var($value);
$cssy = $value[payfee]-$value[sdcrpayfeemoney]-$value[sdcragentfeemoney];
$t->parse("prolists", "prolist", true);	
}
}

$t->set_var($arrdata);
$t->set_var("listid"          ,$listid         );
$t->set_var("cssy",$cssy);


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "authorpaycard"); //最后输出界面

?>