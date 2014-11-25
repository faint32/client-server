<?   
require("../include/common.inc.php");
$db=new db_test;

$ishvaeflage = 0 ;







if($ishvaeflage==0){
  $returnvalue = 0;
}else{
  $returnvalue = 1;
}
echo $returnvalue;
?>