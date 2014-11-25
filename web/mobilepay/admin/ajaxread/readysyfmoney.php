<?   
require("../include/common.inc.php");
$db = new db_test;
$db1= new db_test;

header('Content-Type:text/html;charset=GB2312'); 

$query = "select fd_ysyfm_money from tb_ysyfmoney 
         where fd_ysyfm_type ='$companytype' and fd_ysyfm_companyid = '$companyid'";
$db->query($query);
if($db->nf()){
	 $db->next_record();
	 $yfk_show = $db->f(fd_ysyfm_money)+0;
}else{
   $yfk_show ="";
}

echo $yfk_show;

?>