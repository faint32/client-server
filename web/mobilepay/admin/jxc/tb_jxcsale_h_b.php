<?php
$thismenucode = "2k206";
require ("../include/common.inc.php");
//require ("../include/browse.inc.php");
require ("../include/have_collect_browse.inc.php");

class tb_salelist_b extends browse {
  var $prgnoware    = array("ˢ��������","������ʷ");
  var $prgnowareurl =  array("","",);

  var $browse_key = "fd_selt_id";

  var $browse_queryselect = "select fd_selt_id ,fd_selt_state, fd_selt_no , fd_selt_cusno , fd_selt_cusname ,fd_selt_allquantity, fd_selt_date ,fd_selt_ldr,fd_selt_dealwithman, fd_selt_skfs , fd_selt_allmoney , fd_selt_allcost from tb_salelist";

  var $browse_defaultorder = "  fd_selt_no desc";

  function docollect(){  //���ܺ���
  $str_querysql = $this->browse_collectquery.$this->browse_querywhere;
  $this->db->query($str_querysql);
  if($this->db->nf())
  {
    $this->db->next_record();
    $collectmoney = $this->db->f(collectmoney);
    $collectcost = $this->db->f(collectcost);
    $collectprofits =$collectmoney-$collectcost;
    $collectmoney = number_format($collectmoney, 2, ".", ",");
    $collectcost = number_format($collectcost, 2, ".", ",");
    $collectprofits = number_format($collectprofits, 2, ".", ",");
  }
  else
  {
    $collectmoney = 0;
    $collectcost=0;
  }
  $this->browse_collectdata = "�����ܽ��Ϊ��".$collectmoney."&nbsp;&nbsp;&nbsp;�����ܳɱ�Ϊ��".$collectcost."&nbsp;&nbsp;&nbsp;����������Ϊ��".$collectprofits;
  }

  var $browse_find = array(		// ��ѯ����
    "0" => array("���ݱ��"      ,   "fd_selt_no"          ,"TXT"),
    "1" => array("�ͻ����"      ,   "fd_selt_cusno"       ,"TXT"),
    "2" => array("�ͻ�����"      ,   "fd_selt_cusname"     ,"TXT"),
    "3" => array("��������"      ,   "fd_selt_date"        ,"TXT"),
  );
}

class fd_selt_no extends browsefield {
        var $bwfd_fdname = "fd_selt_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ݱ��";	// �ֶα���
}

class fd_selt_cusno extends browsefield {
        var $bwfd_fdname = "fd_selt_cusno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ͻ����";	// �ֶα���
}

class fd_selt_cusname extends browsefield {
        var $bwfd_fdname = "fd_selt_cusname";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ͻ�����";	// �ֶα���
}

class fd_selt_allquantity extends browsefield {
        var $bwfd_fdname = "fd_selt_allquantity";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_selt_allmoney extends browsefield {
        var $bwfd_fdname = "fd_selt_allmoney";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܶ�";	// �ֶα���
}

class fd_selt_allcost extends browsefield {
        var $bwfd_fdname = "fd_selt_allcost";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܳɱ�";	// �ֶα���
}

class fd_selt_date extends browsefield {
        var $bwfd_fdname = "fd_selt_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_selt_ldr extends browsefield {
        var $bwfd_fdname = "fd_selt_ldr";	// ���ݿ����ֶ�����
        var $bwfd_title = "¼����";	// �ֶα���
}
class fd_selt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_selt_dealwithman";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
}
class fd_selt_state extends browsefield {
    var $bwfd_fdname = "fd_selt_state";	// ���ݿ����ֶ�����
    var $bwfd_title = "״̬";	// �ֶα���

    function makeshow() {	// ��ֵתΪ��ʾֵ
        switch($this->bwfd_value){
            case "2":
                $this->bwfd_show = "�ѷ���";
                break;
            case "9":
                $this->bwfd_show = "���ջ�";
                break;

            default:
                $this->bwfd_show = "";
                break;
        }
        return $this->bwfd_show;
    }
}

class fd_selt_trafficmodel extends browsefield {
        var $bwfd_fdname = "fd_selt_trafficmodel";	// ���ݿ����ֶ�����
        var $bwfd_title = "���䷽ʽ";	// �ֶα���
        
        function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch($this->bwfd_value){
        		case "1":
        		  $this->bwfd_show = "�ͻ�";
        		  break;
        		case "2":
        		  $this->bwfd_show = "����";
        		  break;
        		case "3":
        		  $this->bwfd_show = "��������";
        		  break;
        		default:
        		  $this->bwfd_show = "";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

class fd_selt_skfs extends browsefield {
  var $bwfd_fdname = "fd_selt_skfs";	// ���ݿ����ֶ�����
  var $bwfd_title = "���ʽ";	// �ֶα���

  function makeshow() {	// ��ֵתΪ��ʾֵ
    switch($this->bwfd_value){
      case "1":
        $this->bwfd_show = "�ֽ�";
        break;
      case "2":
        $this->bwfd_show = "֧Ʊ";
        break;
      case "3":
        $this->bwfd_show = "���";
        break;
      case "4":
        $this->bwfd_show = "�ж�";
        break;
        case "5":
            $this->bwfd_show = "����֧��";
            break;
      }
    return $this->bwfd_show;
  }
}



// ���Ӷ���
class lk_view0 extends browselink {
  var $bwlk_fdname = array(			// �������ݿ����ֶ�����
    "0" => array("fd_selt_id") 
  );
  var $bwlk_prgname = "salelistview.php?listid=";
  var $bwlk_title ="��ϸ��";  
}

class lk_view1 extends browselink {
  var $bwlk_fdname = array(      // �������ݿ����ֶ�����
    "0" => array("fd_selt_id") 
  );
 var $bwlk_prgname = "salebackprint.php?listid=";
  var $bwlk_title ="��ӡ";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}


$db = new DB_test;  
$query = "select * from web_teller where fd_tel_id = '$loginuser' " ;
$db->query($query);
if($db->nf()){
  $db->next_record();
  $logincostqx  = $db->f(fd_tel_costqx)  ;
}

$tb_salelist_bu = new tb_salelist_b ;


  $tb_salelist_bu->browse_field = array("fd_selt_no","fd_selt_cusno","fd_selt_cusname","fd_selt_date","fd_selt_ldr","fd_selt_dealwithman","fd_selt_skfs","fd_selt_allquantity","fd_selt_allmoney","fd_selt_state","fd_selt_allcost");


$tb_salelist_bu->browse_skin = $loginskin ;
$tb_salelist_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_salelist_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_salelist_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_salelist_bu->browse_querywhere .= " fd_selt_state = 9  ";

if($thismenuqx[2])
{
  $tb_salelist_bu->browse_link  = array("lk_view0","lk_view1");
}





$tb_salelist_bu->browse_collectquery = "select sum(fd_selt_allmoney) as collectmoney,sum(fd_selt_allcost) as collectcost  from tb_salelist";
$tb_salelist_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
