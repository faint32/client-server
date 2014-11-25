<?
require("../include/common.inc.php");

$db = new DB_test ;
$query = "update web_teller set fd_tel_outtime = now(),fd_tel_isin = 0
			 where fd_tel_id ='$loginuser'";
$db->query($query);
session_destroy();

/*
$gotourl ="../index.php";  //返回
Header("Location: $gotourl"); */
?>
<HTML><HEAD><TITLE>用户退出</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<BODY  leftMargin=0 topMargin=0 onLoad="window.opener=null;window.close();">
<center>
<table width="450" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="450" height="150">&nbsp;
	</td>
  </tr>
</table>
</center>
</BODY></HTML>
