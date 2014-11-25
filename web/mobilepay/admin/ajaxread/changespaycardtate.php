<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312'); 
$db=new db_test;

$query="update tb_paycard set fd_paycard_posstate='0' where fd_paycard_id='$paycardid'";
$db->query($query);

echo "1";


?>