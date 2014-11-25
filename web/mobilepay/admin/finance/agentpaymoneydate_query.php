<?
$thismenucode = "sys";
require ("../include/common.inc.php");
include ("../include/pageft.php");

$db = new DB_test();
$db1 = new DB_test();

if($brows_rows != "") {
	$loginreportline = $brows_rows;
}


$t = new Template(".", "keep"); //调用一个模版
$t->set_file("agentpaymoneydate_query", "agentpaymoneydate_query.html");

$year = date("Y", mktime()) ;
$month = date("m", mktime()) ;
$day = date("d", mktime()) ;
for($i=2;$i>=0;$i--){
	$arr_year[] = $year-$i;
}
$year = makeselect($arr_year,$year,$arr_year);

for($i=1;$i<=12;$i++){
	if($i<10){
		$arr_month[] = "0".$i;
	}else{
		$arr_month[] = $i;
	}
}
$month = makeselect($arr_month,$month,$arr_month);

for($i=1;$i<=31;$i++){
	$arr_day[] = $i;
}
$day = makeselect($arr_day,$day,$arr_day);
$t->set_var("year",$year);
$t->set_var("month",$month);
$t->set_var("day",$day);

$shoukuanstatas  = array("代付款申请","代付款审批","代付款出账","代付款核对","完成");
$shoukuanstatas = makeselectmy($shoukuanstatas);
$t->set_var("shoukuanstatas",$shoukuanstatas);

if (!empty ($_POST["shoukuanstatas"])) {
	$querywhere .= " and fd_pymylt_state = ".$_POST["shoukuanstatas"];
}
if (!empty ($_POST["day"]) && !empty ($_POST["month"]) && !empty ($_POST["year"])) {
		$selectdate =  $_POST["year"]."-".$_POST["month"]."-".$_POST["day"];
		$querywhere .= " and fd_agpm_agentdate like '%".$selectdate. "%'";
}

//显示列表
$t->set_block("agentpaymoneydate_query", "prolist", "prolists");
$query = "select fd_agpm_id               as agpm_id,
                 fd_agpm_no               as agpm_no,
                 fd_agpm_bkntno           as bkntno,
                 fd_paycard_key           as paycardkey,
                 fd_author_truename       as author,
                 fd_agpm_paytype          as paytype,
                 fd_agpm_paydate          as paydate,
                 fd_agpm_shoucardno       as shoucardno,
                 fd_agpm_shoucardbank     as shoucardbank,
                 fd_agpm_shoucardman      as shoucardman,
                 fd_agpm_shoucardmobile   as shoucardmobile,
                 fd_agpm_current          as current,
                 fd_agpm_paymoney         as paymoney ,
                 fd_agpm_payfee           as payfee,
                 fd_agpm_agentmoney       as money,
                 fd_agpm_arrivemode       as arrivemode,
                 fd_agpm_arrivedate       as arrivedate,
                 fd_agpm_memo             as memo,
				 fd_pymylt_state          as pymyltstate,
				 fd_pymylt_datetime       as pymyltdatetime
          from tb_agentpaymoneylist   
          left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
          left join tb_author  on fd_author_id  = fd_agpm_authorid 
		  join tb_paymoneylistdetail on fd_pymyltdetail_agpmid = fd_agpm_id 
		  join tb_paymoneylist on fd_pymyltdetail_paymoneylistid = fd_pymylt_id
          where fd_agpm_payrq = '00' and fd_agpm_isagentpay = '1' $querywhere
          order by fd_agpm_no";
$db->query($query);
$total = $db->num_rows($result);
pageft($total, 20, $url);
if ($firstcount < 0) {
	$firstcount = 0;
}

$count =$firstcount;
$query = "$query limit $firstcount,$displaypg";	  
$db->query($query);
$rows = $db->num_rows();
$arr_result = $db->getFiledData('');
$count = 0;
foreach ($arr_result as $key => $value) {
	     $count++;
	     $value['count']= $count;	
	     
	     $value['arrivemode'] = "T+".$value['arrivemode'];
	     
	     
	     $all_paymoney += $value['paymoney'];
	     $all_payfee += $value['payfee'];
	     $all_money += $value['money'];
	          
	     
		 switch($value["pymyltstate"]){
			case "0":
			  $value["pymyltstate"] = "代付款申请";
			  break;
			case "1":
			  $value["pymyltstate"] = "代付款审批";
			  break;
			case "2":
			  $value["pymyltstate"] = "代付款出账";
			  break;
			case "3":
			  $value["pymyltstate"] = "代付款核对";
			  break;
			case "9":
			  $value["pymyltstate"] = "完成";
			  break;
  		}
		 $t->set_var($value);
	     $t->parse("prolists", "prolist", true);
}

if($count == 0){
	 $t->set_var(array("agpm_id"         => ""   ,
		 		             "agpm_no"         => ""   ,
		 		             "bkntno"          => ""   ,
		 		             "paycardkey"      => ""   ,
		 		             "author"          => ""   ,
		 		             "paytype"         => ""   ,           
		 		             "paydate"         => ""   ,
		 		             "shoucardno"      => ""   ,
		 		             "shoucardbank"    => ""   ,
		 		             "shoucardman"     => ""   , 
		 		             "shoucardmobile"  => ""   , 
		 		             "current"         => ""   , 
		 		             "paymoney"        => ""   , 
		 		             "payfee"          => ""   , 
		 		             "money"           => ""   , 
		 		             "arrivemode"      => ""   , 
		 		             "arrivedate"      => ""   ,   
		 		             "count"           => ""   ,    
		 		             "memo"            => ""   ,  
							 "pymyltstate"     => ""   ,  
							 "pymyltdatetime"  => ""   , 		 		             
		 		   ));
  $t->parse("prolists", "prolist", true);
}

$querywhere = urlencode($querywhere);
$t->set_var("pagenav", $pagenav); //分页变量
$t->set_var("brows_rows", $brows_rows);

$arr_temp = array (
	"-请选择-",
	"单据编号",
	"银行交易流水号",
	"客户名称"
);
$arr_temp2 = array (
	"",
	"fd_agpm_no",
	"fd_agpm_bkntno",
	"fd_author_truename"
);
$condition = makeselect($arr_temp, $sel_condit, $arr_temp2);

$all_paymoney  = number_format($all_paymoney, 2, ".", "");
$all_payfee    = number_format($all_payfee, 2, ".", "");
$all_money     = number_format($all_money, 2, ".", "");

$t->set_var("pagenav", $pagenav); //分页变量

$t->set_var("conditions", $condition);
$t->set_var("txtCondit", $txtCondit);

$t->set_var("listid", $listid); //单据id 
$t->set_var("payorin", $payorin); //付款还是收款

$t->set_var("count", $count);
$t->set_var("all_paymoney", $all_paymoney);
$t->set_var("all_payfee"  , $all_payfee  );
$t->set_var("all_money"   , $all_money   );

$t->set_var("action", $action);
$t->set_var("gotourl", $gotourl); // 转用的地址
$t->set_var("error", $error);

$t->set_var("checkid", $checkid); //批量删除商品ID   

//生成选择菜单的函数
function makeselectmy($arritem){
  for($i=0;$i<count($arritem);$i++){
  		if($i!="4"){
       	 	$x .= "<option value='$i'>".$arritem[$i]."</option>";
		 }else{
		 	$x .= "<option value='9'>".$arritem[$i]."</option>";
		 }
   } 
   return $x ; 
}

// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var("skin", $loginskin);

$t->pparse("out", "agentpaymoneydate_query"); # 最后输出页面
?>

