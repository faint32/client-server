<?
$thismenucode = "2k110";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse {
	//var $prgnoware = array ("���¹���", "���Ź���" );
	var $prgnoware = array ("��������", "�����˲���" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_dept_id";
	
	//var $browse_queryselect = "select * from tb_dept
	//                         ";
	

	var $browse_queryselect = "select tb_dept1.fd_dept_id               as fd_dept_id        ,
	                                   tb_dept1.fd_dept_no               as fd_dept_no        ,  
	                                   tb_dept1.fd_dept_name             as fd_dept_name      ,
	                                   tb_dept2.fd_dept_name             as fd_dept_name2     
	       
	                            from tb_dept  as tb_dept1  
	                            left join tb_dept   as tb_dept2 on tb_dept1.fd_dept_fid = tb_dept2.fd_dept_id
	                            ";
	
	var $browse_delsql = "delete from tb_dept where fd_dept_id = '%s'";
	var $browse_new = "dept.php";
	var $browse_edit = "dept.php?id=";
	//var $browse_outtoexcel ="excelwriter_dept.php";
	//var $browse_inputfile = "input_dept.php";
	

	var $browse_field = array ("fd_dept_no", "fd_dept_name", "fd_dept_name2" );
	var $browse_find = array (// ��ѯ����
"0" => array ("������", "fd_dept_name", "TXT" ) );
	
/*function dodelete(){	//  ɾ������.
	 	   $ishvaeshow = 0 ;
	 	   $valname ="";
  	   for($i=0;$i<count($this->browse_check);$i++){
  	   	  $ishvaeflage = 0 ;
  	   	  $query = "select * from tb_jobs where fd_jobs_deptid = '".$this->browse_check[$i]."'";
  	   	  $this->db->query($query);    //��ѯ��Ʒ��λ
  	   	  if($this->db->nf()){
  	   	  	$ishvaeflage = 1 ;
  	   	  	$ishvaeshow = 1 ;
  	   	  }
  	   	  
 
  	   	  
  	   	  $query = "select * from tb_dept where fd_dept_fid = '".$this->browse_check[$i]."'";
  	   	  $this->db->query($query);    //��ѯ��Ʒ��λ
  	   	  if($this->db->nf()){
  	   	  	$ishvaeflage = 1 ;
  	   	  	$ishvaeshow = 1 ;
  	   	  }
  	   	  
  	   	  $query = "select * from tb_staffer where fd_sta_deptid  = '".$this->browse_check[$i]."'";
  	   	  $this->db->query($query);    //��ѯ��Ʒ��λ
  	   	  if($this->db->nf()){
  	   	  	$ishvaeflage = 1 ;
  	   	  	$ishvaeshow = 1 ;
  	   	  }
  	   	  
  	   	  
  	   	  if($ishvaeflage==0){
  	   	    $query = sprintf($this->browse_delsql,$this->browse_check[$i]);
      	   	$this->db->query($query);  //ɾ������ļ�¼
  	   	  }else{
  	   	    $query = "select * from tb_dept where fd_dept_id = '".$this->browse_check[$i]."'";
  	   	  	$this->db->query($query);  //��ѯ����
  	   	    if($this->db->nf()){
  	   	    	$this->db->next_record();
  	   	    	$name = $this->db->f(fd_dept_name);
  	   	    	$valname .= $name."�� ";
  	   	    }
  	   	  }
       }
       if($ishvaeshow==1){
       	  echo "<script>alert('���²������Ѿ��ﶨԱ�����ϻ��߰�ְλ���߻��߰󶨸�λ���ߴ����������Ų���ɾ��: ".$valname."')</script>";
       }
   }*/
}

class fd_dept_no extends browsefield {
	var $bwfd_fdname = "fd_dept_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}

class fd_dept_name extends browsefield {
	var $bwfd_fdname = "fd_dept_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}

class fd_dept_name2 extends browsefield {
	var $bwfd_fdname = "fd_dept_name2"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_procatalog_b_bu = new tb_procatalog_b ( );
$tb_procatalog_b_bu->browse_skin = $loginskin;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_procatalog_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_procatalog_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


//$tb_procatalog_b_bu->browse_querywhere .=" tb_dept1.fd_dept_organid = '$loginorganid' ";


$tb_procatalog_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
