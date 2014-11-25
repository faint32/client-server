<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

$query = "select * from tb_county where left(fd_county_code,4) = '$city_code' order by fd_county_code asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $arr_county_code[] = $db->f(fd_county_code);
   	   $arr_county_name[] = $db->f(fd_county_name);
  }
}

$ss_county = makeselect($arr_county_name,$ss_county,$arr_county_code);

echo $ss_county;


?>