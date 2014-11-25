<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_shop;


$provincescode=explode(",",$provincescode);
$arr_ihotshop=explode(",",$ihotshop);
foreach($provincescode as $value)
{
	if($value){	
		$arr_provincescode[]=$value;
	} 
}

// left join tb_shopkcquantity on fd_skqy_shopid=fd_shop_id and fd_skqy_procatalogid='$procaid'
$provincescode=implode(",",$arr_provincescode);
$query = "select fd_shop_id,fd_shop_name from tb_shop 
		
		where fd_shop_isstop='0' and fd_shop_state='5' and fd_shop_iswebhidden  = 0 and fd_shop_province in($provincescode)   group by fd_shop_id order by fd_shop_province";

$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$checked="";
		$shopid=$db->f(fd_shop_id);
		$shopname=$db->f(fd_shop_name);
		foreach($arr_ihotshop as $value)
		{
			if($value==$shopid)
			{
				$checked="checked='true'";
			}
		}
		
		$showcheck.='<div style="width:200px;float:left;"><input type="checkbox" '.$checked.' title="'.$shopid.'" name="arr_content[]" onclick="addPreItem()" 
		value="'.$shopname.'">'.$shopname.'</div>';

	}
}
$showcheck .="<div style='clear:both;'></div>";
echo $showcheck;


?>