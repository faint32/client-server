<?
$thismenucode = "2k112";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");
require ("../third_api/readshopname.php");


$db = new DB_test;
$arr_year=array(2013,2014);
$arr_month=array(1,2,3,4,5,6,7,8,9,10,11,12);
foreach($arr_year as $year)
{
	foreach($arr_month as $month)
	{
		$begindate = date( "Y/m/d" ,mktime(0,0,0,$month,1,$year));
		$enddate = date( "Y/m/d" ,mktime(0,0,0,$month+1,0,$year));
		
		$arr_tmpbegindate = explode("/",$begindate);
		$begindatecount = mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2],$arr_tmpbegindate[0]);

		$arr_tmpenddate = explode("/",$enddate);
		$enddatecount = mktime("0","0","0",$arr_tmpenddate[1],$arr_tmpenddate[2],$arr_tmpenddate[0]);
		
		$daycount = date("z",$enddatecount-$begindatecount);  //两个日期相隔的天数。

		for($i=0;$i<=$daycount;$i++){
			$arr_listdate[$year][$month][] = date("Y-m-d",mktime("0","0","0",$arr_tmpbegindate[1],$arr_tmpbegindate[2]+$i,$arr_tmpbegindate[0]));
		}
	}
}

 foreach($arr_listdate  as $key=> $year )
{
	foreach($year as $month)
	{
		foreach($month as $nowdate)
		{	$data=str_replace($key."-","",$nowdate);
			if(date("w",strtotime($nowdate))=='6'){$name="星期六";}
			if(date("w",strtotime($nowdate))=='0'){$name="星期天";}
			if(date("w",strtotime($nowdate))=='6' or date("w",strtotime($nowdate))=='0')
			 {	
				  $query ="insert into tb_holiday (fd_holiday_name,fd_holiday_year,fd_holiday_date) value ('$name','$key','$data')";
				  $db->query($query);
			  }
		}
	}
     
} 
?>

