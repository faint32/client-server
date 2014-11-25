<?PHP
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require ("../include/common.inc.php");


$db  = new DB_test;	
$t = new Template(".","keep");
$t->set_file("demo"       , "deluser.html");   //���
$gotourl = "deluser.php";
$userpassword = md5($userpassword);
switch($action)
{
 case "delete":
        $query = "select fd_author_id,fd_author_paypassword from tb_author where " .
        		"fd_author_username  = '$username' and fd_author_password = '$userpassword'";
		$db->query($query);
		if ($db->nf()) {
			$db->next_record();
			$authorid = $db->f(fd_author_id);
			$query = "delete from tb_author where fd_author_id='$authorid'";
			$db->query($query);
			$query = "update tb_paycard set fd_paycard_authorid = '' where  fd_paycard_authorid= '$authorid'";
			$db->query($query);
			echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
	  
		}else
		{
			  $query = "select fd_author_id,fd_author_paypassword from tb_author where " .
        		"fd_author_username  = '$username'";
		     $db->query($query);
		    if (!$db->nf()) {
		    	echo ("<script>alert('删除失败，您输入的用户名不存在!');location.href='$gotourl'</script>");
		    }
			echo ("<script>alert('删除失败，您输入的用户名或者密码有误!');location.href='$gotourl'</script>");
	  
		}
		
	
		break;
 $action="";
 break;

} 



$t->set_var('checkenc',$checkenc);
$t->set_var('checkdec',$checkdec);
$t->set_var('interface_id',$interface_id);
$t->set_var('resultcontent',$resultcontent);
$t->set_var('apiname',$apiname);
$t->set_var('shopinvoice_kdno',$shopinvoice_kdno);
$t->set_var('shopinvoice_kdname',$shopinvoice_kdname);
$t->set_var('sel_checkbox',$sel_checkbox);
$t->pparse("out", "demo");    # ������ҳ��

?>