<?   
require("../include/common.inc.php");
header('Content-Type:text/html;charset=GB2312');
 
$orderno=makebatches($suppno,$listid);   
echo $orderno;
?>