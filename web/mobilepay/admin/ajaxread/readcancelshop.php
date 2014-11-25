<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$dbshop=new db_shop;
 $arr_shopid=explode(",", $shopid);
 foreach($arr_shopid as $value)
 {
	$query = "update tb_shop set fd_shop_phsid  ='' where fd_shop_id = '$value' ";
	$dbshop->query($query);   //修改单据资料
 }




?>