<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

$query = "select * from tb_city where left(fd_city_code,2) = '$provinces_code' order by fd_city_code asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $arr_city_code[] = $db->f(fd_city_code);
   	   $arr_city_name[] = $db->f(fd_city_name);
  }
}

$ss_city = makeselect($arr_city_name,$ss_city,$arr_city_code);

echo $ss_city;


?>