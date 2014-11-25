<?php 
$thismenucode = "sys";     
require("../include/common.inc.php");

$db=new db_test;

if(!empty($tempurl)){
  $gourl = "excelwriter_account.php" ;
  $gotourl = $gourl.$tempurl."&outputexceldate=".$outputexceldate ;
  Header("Location: $gotourl");
}

require_once '../global.php'; // 设定 PEAR 路径, 下面将会讲到 
require 'Spreadsheet/Excel/Writer.php'; // 包含 PEAR::Spreadsheet_Excel_Writer 类文件 
$workbook = new Spreadsheet_Excel_Writer(); // 实例化 PEAR::Spreadsheet_Excel_Writer 类 
$workbook->send('账户资料表.xls'); // 发送 Excel 文件名供下载 


$worksheet =& $workbook->addWorksheet('sheet-1'); // 加入一个工作表 sheet-1 


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

$arr_field=explode("@",$loginoutputfield);
for($i=0;$i<count($arr_field);$i++){
	$arr_temp = explode("^",$arr_field[$i]);
	$arr_fieldname[]  = $arr_temp[1];
	$arr_fieldvalue[] = $arr_temp[0];
}


$data_i=0;
for($k=0;$k<count($arr_fieldname);$k++){
	$data[$data_i][$k] = $arr_fieldname[$k];
}

$query = "select * from tb_account where fd_account_organid='$loginorganid'
	 			  $sqlwhere ";
$db->query($query);

if($db->nf()){
	while($db->next_record()){
		$data_i++;
		for($k=0;$k<count($arr_fieldvalue);$k++){
	    $data[$data_i][$k] = $db->f($arr_fieldvalue[$k]);
    }
	}
}


for ($row = 0; $row < count($data); $row ++) { 
    for ($col = 0; $col < count($data[0]); $col ++) { 
        $worksheet->writestring($row, $col, $data[$row][$col]); // 在 sheet-1 中写入数据 
    } 
}

$workbook->close(); // 完成下载


?>