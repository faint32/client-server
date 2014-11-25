<?
$thismenucode = "2k401";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_paycard_b extends browse {
	var $prgnoware = array (
		"ˢ��������"
	);
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_paycard_id";

	var $browse_queryselect = "select fd_paycard_id, fd_paycard_batches, fd_paycard_key, 
								fd_paycard_active,fd_paycard_activetime,fd_paycard_stockprice,
								fd_paycard_saleprice,fd_cus_name,fd_author_truename,
								concat_ws(',',fd_paycard_isnew,fd_paycard_posstate) as paycardstate,
								fd_product_suppname,
								fd_bank_name,fd_product_name,fd_paycard_scope,fd_paycard_state as paycardwhere
								from tb_paycard
								left join tb_bank on fd_bank_id= fd_paycard_bankid
								left join tb_author on fd_author_id=fd_paycard_authorid
								left join tb_product on fd_product_id=fd_paycard_product
								left join tb_customer on fd_cus_id=fd_paycard_cusid
								left join tb_paycardtype on fd_paycardtype_id=fd_paycard_paycardtypeid";

	var $browse_edit = "paycard.php?listid=";
	var $browse_editname = "�鿴";
	
	var $browse_defaultorder = " fd_paycard_activetime desc,fd_paycard_key asc ";

	//var $browse_new = "paycard.php";
	//var $browse_delsql = "delete from tb_paycard where fd_paycard_id = '%s'";

	var $browse_field = array (
		"fd_paycard_id",
		"paycardstate",
		"fd_paycard_batches",
		"fd_paycard_key",
		"fd_author_truename",
		"fd_product_name",		
		"paycardwhere",
		"fd_product_suppname",
		"fd_paycard_activetime",		
		"fd_cus_name",
		"fd_paycard_stockprice",
		"fd_paycard_saleprice",
		"fd_paycard_scope"
	);
	var $browse_find = array (// ��ѯ����
	"0" => array (
			"���",
			"fd_paycard_id",
			"TXT"
		),
//		"1" => array (
//			"�Ƿ񼤻�",
//			"fd_paycard_active",
//			"TXT"
//		),
		"1" => array (
			"����",
			"fd_paycard_batches",
			"TXT"
		),
		"2" => array (
			"�豸��",
			"fd_paycard_key",
			"TXT"
		),
		"3" => array (
			"��Ʒ��",
			"fd_product_name",
			"TXT"
		),

		"4" => array (
			"��Ӧ����",
			"fd_product_suppname",
			"TXT"
		),
		"5" => array (
			"������",
			"fd_cus_name",
			"TXT"
		),		
	);
}

class fd_paycard_id extends browsefield {
	var $bwfd_fdname = "fd_paycard_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}

class fd_paycard_batches extends browsefield {
	var $bwfd_fdname = "fd_paycard_batches"; // ���ݿ����ֶ�����
	var $bwfd_title = "����"; // �ֶα���
	var $bwfd_width = "15%"; // �ֶα���
}
class fd_paycard_key extends browsefield {
	var $bwfd_fdname = "fd_paycard_key"; // ���ݿ����ֶ�����
	var $bwfd_title = "�豸��"; // �ֶα���
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ն��̻�"; // �ֶα���
}

class paycardstate extends browsefield {
	var $bwfd_fdname = "paycardstate"; // ���ݿ����ֶ�����
	var $bwfd_title = "״̬"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
			$arr_data=explode(",",$this->bwfd_value);
			$data1 = $arr_data[0];
			$data2 = $arr_data[1];
			if($data1=="1")
			{
				$this->bwfd_show = "�¿�";
			}else{
			switch ($this->bwfd_value) {
				case "0":
					$this->bwfd_show = "ͣ��";
					break;
				case "1" :
					$this->bwfd_show = "����";
					break;
				case "2" :
					$this->bwfd_show = "����";
					break;
				case "3" :
					$this->bwfd_show = "����";
					break;
			}
		}
		return $this->bwfd_show;
	}
}

class paycardwhere extends browsefield {
	var $bwfd_fdname = "paycardwhere"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ��������"; // �ֶα���
	var $bwfd_width = "8%"; // �ֶα���
		function makeshow() { // ��ֵתΪ��ʾֵ
			switch($this->bwfd_value)
			{
				case '1':
				$this->bwfd_show="�ڿ��";
				break;
				case '2':
				$this->bwfd_show="������";
				break;
				case '-1':
				$this->bwfd_show="���˻�";
				break;
				case '-2':
				$this->bwfd_show="�����˻�";
				break;
			}
		return $this->bwfd_show;
	}
}

class fd_product_suppname extends browsefield {
	var $bwfd_fdname = "fd_product_suppname"; // ���ݿ����ֶ�����
	var $bwfd_title = "��Ӧ��"; // �ֶα���
}
class fd_paycard_activetime extends browsefield {
	var $bwfd_fdname = "fd_paycard_activetime"; // ���ݿ����ֶ�����
	var $bwfd_title = "����ʱ��"; // �ֶα���
	var $bwfd_width = "12%"; // �ֶα���
}

class fd_product_name extends browsefield {
	var $bwfd_fdname = "fd_product_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��Ʒ����"; // �ֶα���
}
class fd_cus_name extends browsefield {
	var $bwfd_fdname = "fd_cus_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
class fd_paycard_stockprice extends browsefield {
	var $bwfd_fdname = "fd_paycard_stockprice"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ɹ��۸�"; // �ֶα���
}

class fd_paycard_saleprice extends browsefield {
	var $bwfd_fdname = "fd_paycard_saleprice"; // ���ݿ����ֶ�����
	var $bwfd_title = "���ۼ۸�"; // �ֶα���
}
class fd_paycard_scope extends browsefield {
	var $bwfd_fdname = "fd_paycard_scope"; // ���ݿ����ֶ�����
	var $bwfd_title = "��ˢ������"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
		switch ($this->bwfd_value) {
			case "creditcard" :
				$this->bwfd_show = "���ÿ�";
				break;
			case "bankcard" :
				$this->bwfd_show = "��ǿ�";
				break;
		}
		return $this->bwfd_show;
	}
}



if (empty ($order)) {
	$order = "fd_paycard_id";
}

if (isset ($pagerows)) { // ��ʾ����
	$pagerows = min($pagerows, 100); // �����ʾ����������100
} else {
	$pagerows = 100;
}

$tb_paycard_b_bu = new tb_paycard_b();
$tb_paycard_b_bu->browse_skin = $loginskin;
$tb_paycard_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_paycard_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_paycard_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
//$tb_paycard_b_bu->browse_link  = array("lk_view0");

$tb_paycard_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>
