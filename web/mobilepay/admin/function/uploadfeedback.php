<?
require ("../include/common.inc.php"); 
if($toact=="close")
{
	    // echo $getvalid;
	     $thumrul	= str_replace("../",$g_showpic,$return);
		if($thumrul<>"" or $act=='delete')  //������ɾ�����ϴ��ɹ�����ִ�и�����
		{
		session_start();
		unset($_SESSION['session_thumrul']);
        $_SESSION['session_thumrul'] = $thumrul;
		echo ("<script>parent.$('#".$getvalid."').attr('value','$thumrul');	</script>");
		}
		echo ("<script>parent.$.fn.colorbox.close();</script>");
		exit;
		 
}
