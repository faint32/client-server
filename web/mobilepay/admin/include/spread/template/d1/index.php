<?
require_once ("../include/common.inc.php");
$db = new DB_test;
$query = "select * from tb_emaillist";
$db->query($query);
$db->next_record();
$uuuu = $db->f(fd_emaillist_title);
?>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=gb2312">

<title>ϵͳ��½</title>


</head>

<body>

�ԶԶԵضԵص���<?php echo $uuuu; ?>

</body>

</html>

