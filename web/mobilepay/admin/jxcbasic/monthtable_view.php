<?
$thismenucode = "2k112";
require ("../include/common.inc.php");

$db = new DB_test ;
$dbtj = new DB_test ;
$gourl = "tb_holiday_b.php";
$gotourl = $gourl . $tempurl;
$t = new Template('.', "keep");
$t->set_file("yeartable_view","monthtable_view.html");
$t->set_block("yeartable_view", "BXBK", "bxbks");

if(empty($year))
{
	$year=date("Y",time());
}


$query = "select* from tb_holiday 
          where fd_holiday_year='$year' and fd_holiday_active='1'
           group by fd_holiday_date"; 
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		
		$listdate   = $db->f(fd_holiday_date);        //日期
		$arr_tmpdate = explode("-",$listdate);
		$listdate=$arr_tmpdate[0]+0;
		$arr_holiday_name[$listdate][] = $db->f(fd_holiday_name);
		$arr_holiday_date[$listdate][] = $db->f(fd_holiday_date);
		$arr_holiday_id[$listdate][] = $db->f(fd_holiday_id);
		
  }
}


//print_r($arr_holiday_date);




$count=0;
for($i=1;$i<=12;$i++){		
  $listdate = $year."年".$i."月";
  $tmpdate = $i;
  
      
   
	$holiday_date     = $arr_holiday_date[$tmpdate]; 	
   
  
    foreach($holiday_date as $key => $value)
	{
		 $holiday_id     = $arr_holiday_id[$tmpdate][$key]; 
		 $holiday_name    = $arr_holiday_name[$tmpdate][$key];  
		$content .='&nbsp; '.$holiday_name.' &nbsp; <a href="#"  onClick="isstop(\''.$holiday_id.'\')" >'.$value.'</a>&nbsp;&nbsp;';
	   
	}
	
	$showcontent=$content;
  	if ($bgcolor=="#FFFFFF") {
        $bgcolor="#F1F4F9";
    }else{
        $bgcolor="#FFFFFF";
    }
	$count++;
    
	$content="";
  	$t->set_var(array("listdate"	   => $listdate     ,
  			           "bgcolor"       => $bgcolor      ,
  			            "tdcount"      =>$count         ,
  			            "showcontent"  =>$showcontent           
  			   ));
		   
  	$t->parse("bxbks", "BXBK", true);
}

if($count==0){

	$t->parse("bxbks", "", true);
}



$t->set_var("condition",$condition);


$t->set_var("year",$year);
$t->set_var("month",$month);
$t->set_var("gotourl",$gotourl);

$t->set_var("count",$count);
$t->set_var("error",$error);

$t->set_var("skin",$loginskin);
$t->pparse("out", "yeartable_view");    # 最后输出页面

?>