<?
require ("../include/common.inc.php");

$db  = new DB_test;
$db1 = new DB_test;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("hostsearch","hostsearch.html"); 
$t->set_block("hostsearch", "hostlist", "hostsearchs");
$gourl = "tb_showpro_b.php" ;
$gotourl = $gourl.$tempurl ;
switch ($action)
{
  case "edit":   // �޸ļ�¼
			
		  $fd_host_txt=trim(str_replace("��",",",$fd_host_txt) );
		 $fd_host_txt=explode(",",$fd_host_txt);
		 foreach($fd_host_txt as $value)
		 {
			if($value)
			{ 
			  $arr_date[]=$value;
			}
		 }
		 $fd_host_txt=implode(",",$arr_date);
		
         $query = "update web_conf_hostsearch set fd_host_txt ='$fd_host_txt' where fd_host_id = '$fd_host_id' and fd_csp_id='$id'";
		 $db->query($query);
	    $action ="";  
    	break;
}
	 // �༭
  $query = "select * from web_conf_hostsearch  left join web_conf_showpro on web_conf_hostsearch.fd_csp_id=web_conf_showpro.fd_csp_id where web_conf_hostsearch.fd_csp_id = '$id' " ;
  $db->query($query);
  if($db->nf())
  {                           //�жϲ�ѯ���ļ�¼�Ƿ�Ϊ��
     while($db->next_record()){                               //��ȡ��¼���� 
		 $fd_host_id=$db->f(fd_host_id);
		 $fd_csp_procaname=$db->f(fd_csp_procaname);
		 $fd_host_type=$db->f(fd_host_type); 
		 $fd_host_txt=$db->f(fd_host_txt);
		 
		 $t->set_var(array("fd_host_id"         => $fd_host_id         ,
    				      "fd_csp_procaname"       => $fd_csp_procaname       ,
    				      "fd_host_type"       => $fd_host_type       ,
    				      "fd_host_txt"    => $fd_host_txt        
    	                    ));
		 $t->parse("hostsearchs", "hostlist", true);	
	 }
	 $action="edit";	
	}
// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("action"            ,$action          );
$t->set_var("skin"              ,$loginskin       );
$t->set_var("gotourl"           ,$gotourl         );
$t->pparse("out", "hostsearch");    # ������ҳ��

?>