<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;
$dbshop=new db_shop;
//left join tb_shopkcquantity on fd_skqy_shopid=fd_shop_id and fd_skqy_procatalogid='$procaid'
$query="select fd_shop_province from tb_shop 
		
		where fd_shop_isstop='0' and fd_shop_state='5' and fd_shop_iswebhidden  = 0 ";

$dbshop->query($query);
if($dbshop->nf())
{
	while($dbshop->next_record()){
		$arr_provincescode[]=$dbshop->f(fd_shop_province);
	}
}

$provincescode=implode(",",$arr_provincescode);
$query = "select fd_provinces_code,fd_provinces_name from tb_provinces where fd_provinces_code in($provincescode)";
$db->query($query);
$i=1;
if($db->nf()){
	while($db->next_record()){
		$provincescode=$db->f(fd_provinces_code);
		$provincesname=$db->f(fd_provinces_name);
		
		$showcheck.='<font style="width:30px;"><input type="checkbox" title="'.$provincesname.'" name="arr_provinces[]" onclick="readmarketingparam(\''.$procaid.'\')" 
		value="'.$provincescode.'">'.$provincesname.'</font>';
		
		if(($i%12)==0)
		{	
			
			$showcheck .="<br/>";
		}
		$i++;
	}
}
echo $showcheck;


?>