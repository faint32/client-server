<?   
require("../include/common.inc.php");
require("../include/json.php");
header('Content-Type:text/html;charset=utf-8'); 
$db=new db_test;

$prov =u2g(unescape($prov));

$query = "select fd_china_city as city  from tb_china  where fd_china_prov = '$prov'
                   group by fd_china_city order by fd_china_areacode asc";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
	     $arr_city_code = g2u($db->f(city));
   	     $arr_city_name= g2u($db->f(city));

         $select[] = array("id"=>$arr_city_code,"title"=>$arr_city_name); 
  }
}
if(empty($select))
{
	 $select[] = array("id"=>"","title"=>""); 
}
echo json_encode($select);

?>