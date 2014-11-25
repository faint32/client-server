<?
require ("../include/common.inc.php");
require ("../inputfile/inputfile.inc.php");

class tb_paycard extends inputfile 
{
	 var $file_headname =  array("刷卡器设备号","所属银行");
	 var $file_headval  =  array("fd_paycard_no&0","fd_paycard_bankid&1");
	 var $file_sqlname  =  "insert into tb_paycard ";
	 
	 //var $file_inputhiden = array("fd_salecard_storageid");  //固定插入数据字段
	 
	 function makechangeid($txt_content,$typeid) {	// 将值转为显示值
   	  switch($typeid){
   	  	case "1":
   	        $query = "select * from tb_bank where fd_bank_name like '%$txt_content%'";
            $this->db->query($query);		    
            if($this->db->nf()){
            	$this->db->next_record();
            	$returnid = $this->db->f(fd_bank_id);
            }else{
            	$returnid ="0";
            }
           break;

		  }
		  return  $returnid;
   }
}

$tb_paycard_in = new tb_paycard ;
$tb_paycard_in->file_skin = $loginskin;
$tb_paycard_in->file_backurl  =  "../saler/paycard.php";
//$tb_paycard_in->file_inputhidenvalue = array("0" => array("storageid", $storageid),"1" => array("organid", $loginorganid));  //固定插入数据字段的值
$tb_paycard_in->main($isuseheaders,$selfileformat,$step,$excel_file,$excel_file_name,$thirdfilename,$fieldcheck,$fieldname) ;

?>


                                     
                                            
                                        
                                                   
                                                
                                               
                                                   
                                                
                                           
   
