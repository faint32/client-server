<?
$thismenucode = "1c602";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("��������","��������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_helpset_id";
	 
	 var $browse_queryselect = "select * from web_helpset 
	                            ";
	// var $browse_delsql = "delete from web_helpset where fd_helpset_id = '%s'" ;
	// var $browse_new    = "helpset.php" ;
	 var $browse_edit   = "helpset.php?id=" ;
	 //var $browse_outtoexcel ="excelwriter_procatalog.php";
	 //var $browse_inputfile = "input_procatalog.php";

	 var $browse_field = array("fd_helpset_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("��������" , "fd_helpset_name","TXT"),

				);
	 
	 function dodelete(){	//  ɾ������.
	 	   $ishvaeshow = 0 ;
	 	   $valname ="";
  	   for($i=0;$i<count($this->browse_check);$i++){
  	   	  $ishvaeflage = 0 ;
  	   	  $query = "select * from web_help where fd_help_type = '".$this->browse_check[$i]."'";
  	   	  $this->db->query($query);    //��ѯ��Ʒ��λ
  	   	  if($this->db->nf()){
  	   	  	$ishvaeflage = 1 ;
  	   	  	$ishvaeshow = 1 ;
  	   	  }
  	   	  if($ishvaeflage==0){
  	   	    $query = sprintf($this->browse_delsql,$this->browse_check[$i]);
      	   	$this->db->query($query);  //ɾ������ļ�¼
  	   	  }else{
  	   	    $query = "select * from web_helpset where fd_helpset_id = '".$this->browse_check[$i]."'";
  	   	  	$this->db->query($query);  //��ѯ���
  	   	    if($this->db->nf()){
  	   	    	$this->db->next_record();
  	   	    	$name = $this->db->f(fd_helpset_name);
  	   	    	$valname .= $name."�� ";
  	   	    }
  	   	  }
       }
       if($ishvaeshow==1){
       	  echo "<script>alert('���°����������Ѿ��󶨰����˲���ɾ��: ".$valname."')</script>";
       }
    }

}

class fd_helpset_name extends browsefield {
        var $bwfd_fdname = "fd_helpset_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}



if(empty($order)){
	$order = "fd_helpset_name";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
