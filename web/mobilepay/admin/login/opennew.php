<?
session_start();
require ("../include/common.inc.php");
 

if($openmain == 1){
      $disbutton = "onClick='winopen()' style='CURSOR: hand'";  //�޸Ĳ���ʹ�� 
     
}else if($openmain == ""){
    $disbutton = "onClick='winopen()' style='CURSOR: hand'";
     
}
 

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("opennew","opennew.html"); 

$t->set_var("disbutton",$disbutton); 
$t->set_var("skin",$loginskin);  // ����Ƥ��
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ
$t->pparse("out", "opennew");    # ������ҳ��
?>