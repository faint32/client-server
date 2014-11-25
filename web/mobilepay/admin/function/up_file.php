<?php
class up_file {	
	function up_excel($name){	
		//读取文件
		require ("../include/config.inc.php");
		require ("../excel/PHPExcel.php");	
		//$db = new DB_test();		
		$filePath = "../upload/".$name;//$filePath = "./upload/kk.xlsx";
		$PHPExcel = new PHPExcel();
		$PHPReader = new PHPExcel_Reader_Excel2007();

		if(!$PHPReader->canRead($filePath)){
			$PHPReader = new PHPExcel_Reader_Excel5();
			if(!$PHPReader->canRead($filePath)){
				 echo "<script>alert('读取文件错误！');<script>"; return;
				//echo 'no Excel';return ;
			}
		}		
		$PHPExcel = $PHPReader->load($filePath);
		$currentSheet = $PHPExcel->getSheet(0);
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();
		$i = 0;
		for($currentRow = 2; $currentRow <= $allRow; $currentRow++){
			for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
				//$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();
				$val2 = $currentSheet->getCellByColumnAndRow($x-65,$currentRow);
				$val = $val2->getValue();
				if($currentColumn == 'A'){
					$a = iconv('utf-8','gb2312', $val);				
				} elseif($currentColumn == 'B') {
					$b = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'C') {
					$c = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'D') {
					$d = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'E') {
					$e = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'F') {
					$f = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'G') {
					$g = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'H') {
					$h = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'I') {
					$i = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'J') {
					$j = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'K') {
					$k = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'L') {
					$l = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'M') {
					$m = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'N') {
					$n = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'O') {
					$o = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'P') {
					$p = iconv('utf-8','gb2312', $val);
				} elseif($currentColumn == 'Q') {
					$q = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'R') {
					$r = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'S') {
					$s = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'T') {
					$t = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'U') {
					$u = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'V') {
					$v = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'W') {
					$w = iconv('utf-8','gb2312', $val);
				}elseif($currentColumn == 'X') {
					$x = iconv('utf-8','gb2312', $val);
				}
				
			}
			$value = array();
			//$value[$i] = " '$a', 	'$b', 	'$c', 	'$d', 	'$e', 	'$f', 	'$g', 	'$h', 	'$i', 	'$j', 	'$k', 	'$l', 	'$m', 	'$n', 	'$o', 	'$p', '$q', 	'$r', 	'$s', 	'$t', 	'$u', 	'$v', 	'$w', 	'$x'";
			//$i++;
			return "'$a', 	'$b', 	'$c', 	'$d', 	'$e', 	'$f', 	'$g', 	'$h', 	'$i', 	'$j', 	'$k', 	'$l', 	'$m', 	'$n', 	'$o', 	'$p', '$q', 	'$r', 	'$s', 	'$t', 	'$u', 	'$v', 	'$w', 	'$x'" ;	
		
				//插入数据
		//	return 	$sql = "INSERT INTO tb_uploadfile 	( td_uploadfile_khid,	td_uploadfile_bianhao, 	td_uploadfile_quchen, 	td_uploadfile_money, 	td_uploadfile_sale_dunshu, 	td_uploadfile_khxz, 	td_uploadfile_sfzh_zch, 	td_uploadfile_frman, 	td_uploadfile_zczb, 	td_uploadfile_clrq, 	td_uploadfile_zylxr, 	td_uploadfile_zhiwei, 	td_uploadfile_phone, 	td_uploadfile_tel1, 	td_uploadfile_tel2, 	td_uploadfile_fax,	td_uploadfile_address, 				td_uploadfile_jyzk, 	td_uploadfile_bankname, 	td_uploadfile_bankno,	td_uploadfile_sh, 	td_uploadfile_email, 				td_uploadfile_time, 	td_uploadfile_jg	)	VALUES				( 	'$a', 	'$b', 	'$c', 	'$d', 	'$e', 	'$f', 	'$g', 	'$h', 	'$i', 	'$j', 	'$k', 	'$l', 	'$m', 	'$n', 	'$o', 	'$p', '$q', 	'$r', 	'$s', 	'$t', 	'$u', 	'$v', 	'$w', 	'$x')"; 
				//$db->query($sql);
			
		}
			
			return $value ;	
	}

	function up_csv($localpath,$table_name){	
		return $csv = "load data infile '".$localpath."' into table ".$table_name." CHARACTER SET gb2312  fields terminated by ',' Enclosed By '\"' Escaped By '\"' ".'LINES TERMINATED BY "\r\n" IGNORE 1 LINES';
	}
	
	function up_txt($localpath,$table_name){
		//$localpath ="../upload/".$_FILES["file"]["name"];	
		return $csv = "load data infile '".$localpath."' into table ".$table_name." CHARACTER SET gb2312  fields terminated by '\t' ".'  LINES TERMINATED BY "\r\n" IGNORE 1 LINES ';
	}

	
	function dao_excel(){
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:filename=test.xls");
		$sql0 = 'select * from tb_uploadfile';	
		require ("../include/config.inc.php");
		$db = new DB_test;
		$db->query($sql0);
		if($db->nf()){
			while($db->next_record()){
				echo $db->f(td_uploadfile_bianhao)."\t";//
				echo $db->f(td_uploadfile_zhiwei)."\t\n";
			}

		}
	
	}
	
	
	
	
	
	function dao_csv($table_name,$path){		             	
		$put_csv ='select * from  '.$table_name." into outfile '".$path."'  fields terminated by ',' lines terminated by '\\n'";
		//OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' 
		return $put_csv; 
	/*
	
	  "  SELECT * FROM tb_uploadfile   
    INTO OUTFILE 'E:/APMServ5.2.6/www/htdocs/mssale/msadmin/upload/out/1370759747.csv'   
    FIELDS TERMINATED BY ','   LINES TERMINATED BY '\r\n' ";   
	*/
	}
	
	function dao_txt($table_name,$path){	
		$inf_sql = 'select * from '.$table_name ;	
		$db->query($inf_sql);
		$file = $filename = "test.txt";
		$file = fopen($filename,"w");
		if($db->nf()){
			while($db->next_record()){
				$td_uploadfile_bianhao = $db->f(td_uploadfile_bianhao);
				$td_uploadfile_zhiwei  = $db->f(td_uploadfile_zhiwei);
				fwrite($file,"$td_uploadfile_bianhao,$td_uploadfile_zhiwei");		
			}
			fwrite($file,"\n");
			fclose($file);
		}
		
		header("Content-type: text/plain");
		header("Accept-Ranges: bytes");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header("Pragma: no-cache" );
		header("Expires: 0" ); 
		readfile($filename);
	}
}
?>