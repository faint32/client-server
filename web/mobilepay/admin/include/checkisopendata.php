<?

$dbcheckopen = new DB_test;
$query = "select * from tb_isopenaccount where fd_isot_organid = '$loginorganid'";	   
$dbcheckopen->query($query);
if($dbcheckopen->nf()){
}else{
  Header("Location: ../sys/nopoenerror.php");
}

?>