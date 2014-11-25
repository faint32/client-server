<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

//$showcheck ='<onclick="selectall();" id="selitmes">ȫѡ</a><br>';
if($param=="fd_provinces_id")
{
$query = "select * from tb_provinces order by fd_provinces_name ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$provincesid=$db->f(fd_provinces_id);
		$provincesname=$db->f(fd_provinces_name);
		
		$showcheck.='<font style="width:120px;"><input type="checkbox" title="'.$provincesid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$provincesname.'">'.$provincesname.'</font>';
	
	}
}
}else if($param=="fd_city_id")
{
$query = "select * from tb_city order by fd_city_name ";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$cityid=$db->f(fd_city_id);
		$cityname=$db->f(fd_city_name);
		
		$showcheck.='<font style="width:120px;"><input type="checkbox"  title="'.$cityid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$cityname.'">'.$cityname.'</font>';
	
	}
}
}else if($param=="fd_county_id")
{
$query = "select * from tb_county order by fd_county_name";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$countyid=$db->f(fd_county_id);
		$countyname=$db->f(fd_county_name);
		
		$showcheck.='<font style="width:80px;"><input type="checkbox" title="'.$countyid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$countyname.'">'.$countyname.'</font>';
	
	}
}
}
echo $showcheck;


?>