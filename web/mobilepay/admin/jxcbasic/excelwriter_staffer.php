<?php 
$thismenucode = "2004";     
require("../include/common.inc.php");

$db=new db_test;

if(!empty($tempurl)){
  $gourl = "excelwriter_staffer.php" ;
  $gotourl = $gourl.$tempurl."&outputexceldate=".$outputexceldate ;
  Header("Location: $gotourl");
}

require_once '../global.php'; // �趨 PEAR ·��, ���潫�ὲ�� 
require 'Spreadsheet/Excel/Writer.php'; // ���� PEAR::Spreadsheet_Excel_Writer ���ļ� 
$workbook = new Spreadsheet_Excel_Writer(); // ʵ���� PEAR::Spreadsheet_Excel_Writer �� 
$workbook->send('ְԱ���ϱ�.xls'); // ���� Excel �ļ��������� 


$worksheet =& $workbook->addWorksheet('sheet-1'); // ����һ�������� sheet-1 


$beginrow = ($now-1) * $pagerows;
if($beginrow<0){
	  $beginrow=0;
}

if(empty($order)){
	$orderby = "";
}else{
  $orderby = " order by ".$order." ".$upordown ;
}

if($outputexceldate==0){
	$sqlwhere = "  ".$orderby ." limit ".$beginrow." , ".$pagerows;
}else{
  $sqlwhere = "";
}


$data_i=0;
$data[$data_i][0] = "���";
$data[$data_i][1] = "����";
$data[$data_i][2] = "ְλ";
$data[$data_i][3] = "��������";
$data[$data_i][4] = "���֤��";
$data[$data_i][5] = "�ֻ�����";
$data[$data_i][6] = "��ְʱ��";
$data[$data_i][7] = "��ַ";
$data[$data_i][8] = "��ע";
$data[$data_i][9] = "ID";
$query = "select * from tb_staffer
	      left join tb_dept on fd_dept_id = fd_sta_deptid 
	      left join tb_jobs on fd_jobs_id = fd_sta_duty
	 	  where fd_sta_agencyid = '$loginorganid' and fd_sta_dimission = 1 and fd_sta_type != 4";
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$data_i++;
		$data[$data_i][0] = $db->f(fd_sta_stano);
		$data[$data_i][1] = $db->f(fd_sta_name);
		$data[$data_i][2] = $db->f(fd_jobs_name);
		$data[$data_i][3] = $db->f(fd_dept_name);
		$data[$data_i][4] = $db->f(fd_sta_idcard);
		$data[$data_i][5] = $db->f(fd_sta_mobile);
		$data[$data_i][6] = $db->f(fd_sta_jobtime);
		$data[$data_i][7] = $db->f(fd_sta_address);
		$data[$data_i][8] = $db->f(fd_sta_memo);
		$data[$data_i][9] = $db->f(fd_sta_id);
	}
}


for ($row = 0; $row < count($data); $row ++) { 
    for ($col = 0; $col < count($data[0]); $col ++) { 
	    {
          $worksheet->writestring($row, $col, $data[$row][$col]); // �� sheet-1 ��д������ 
		 }
    } 
} 

$workbook->close(); // ������� 

?>