<?php
//include_once ("excelparser.php");    //����excel��ĺ���

class inputfile{
	
	 var $classname = "browse";
   var $database_class = "DB_test";     //�������ݿ�������
   var $template_class = "Template";
   var $excel_class = "ExcelFileParser";    //���ö�ȡexcel���ݵ���
   var $step = "0";                //������
   var $file_backurl="";           //����url��ַ
   var $file_headname = array();   //�����ֶ�������������
   var $file_headval = array();    //�����ֶε�����
   var $file_inputhiden = array();    //�̶����������ֶ�
   var $file_inputhidenvalue = array(); //�̶����������ֶε�ֵ
   //var $file_listflag = array();   //�Ƿ�Ϊ���ı�ת��Ϊid�ĺ���
   var $file_skin = "" ;
   var $file_sqlname ="";
   var $file_fieldname="";
   var $file_fieldname2="";
   
   function main($isuseheaders,$selfileformat,$step,$userfile,$userfile_name,$thirdfilename,$fieldcheck,$fieldname,$selfiletype) {
   	   $name = $this->database_class;   
       $this->db = new $name; 
   	   
   	   switch($step){
   	   	case 1:
   	   	  if($selfileformat=="xls"){
   	   	  	 $showtable = $this->excelsecondstep($selfileformat,$userfile,$userfile_name,$isuseheaders,$selfiletype);
   	   	  }else{
   	   	     $showtable = $this->secondlystep($selfileformat,$userfile,$userfile_name,$isuseheaders);
   	   	  }
   	   	  break;
   	   	case 2:
   	   	  if($selfileformat=="xls"){
   	   	      $showtable = $this->excelthirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname,$selfiletype);
   	   	  }else{
   	   	      $showtable = $this->thirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname,$selfiletype);
   	   	  }
   	     	break;
   	   	  default:
   	   	  $showtable = $this->firststep();   //���õ�һ��
   	   	  break;
   	   }
   	  // $loginskin="../skin/skin0/";
   	   
   	   $this->t = new Template(".", "keep");    
       $this->t->set_file("inputfile","../inputfile/inputfile_basic.html"); 
       $this->t->set_var("skin",$this->file_skin);
       $this->t->set_var("showtable",$showtable);    
       $this->t->pparse("out", "inputfile");    # ������ҳ��
   }
   
   function firststep() {	// ������չ����
  	   $show = "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
	     $show .= "<tr><td width=19% class=inputtitle align=center >�����ļ�</td><td width='81%'  class=inputtitleright>&nbsp;</td></tr>";
	     $show .= "<tr><td colspan='4' height='10'></td></tr>";
       $show .= "<form name='exc_upload' method='post' action='' enctype='multipart/form-data'>";
       $show .= "<tr><td class=form_label>ѡ���ļ�:</td>";
       $show .= "<td><input type='file' size=30 name=excel_file id='excel_fileid'></td></tr>";
       $show .= "<tr><td class=form_label>ѡ�����ļ�����������:</td>";
       $show .= "<td ><select name=selfiletype><option value='cus'>��Ա</option></td></tr>";
       $show .= "<tr><td class=form_label>ѡ�����ļ���ʽ:</td>";
       $show .= "<td ><select name=selfileformat><option value='csv'>csv</option></select></td></tr>";
       //$show .= "<tr><td class=form_label>�����һ������:</td>";
       //$show .= "<td ><input type=checkbox name=isuseheaders value=1></td></tr>";  
       $show .= "<tr><td class=form_label2 colspan=2>&nbsp;&nbsp;ע�⣺txt��ʽ��csv��ʽ���ļ���ÿһ�е�����Ҫ�ö��ŷֿ��ġ�
                 ���ܵ����һ���ļ����ݡ�<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�ļ�������ҪΪ����(GB2312)��Ӣ��,��������(����)����ִ�����ʾ��</td></tr>";
	     $show .= "<tr class=bottombotton><td colspan=2 align=center>";
       $show .= "<input type=hidden name=step value=1>";
       $show .= "<input type=button class=buttonsmall value='��һ��' onClick='secondlystep()'></td></tr>";   
       $show .= "</form></table>";
    	 return $show ;
    }
    
    //����txt��csv�ļ��ĵڶ�������
    function secondlystep($selfileformat,$userfile,$userfile_name,$isuseheaders){
    	   //switch($selfiletype){ 
   	        	//case "cus":   	        	 
   	        	  $this->file_headname =  array("�ͻ�Id��","�ͻ����","�ͻ�ȫ��","Ӫҵ��","���۶���","�ͻ�����","���֤��/ע���","����","ע���ʱ�","��������","��Ҫ��ϵ��","ְλ","�ֻ�����","�绰1","�绰2","����","��ַ","��Ӫ״��","��������","�ʺ�","˰��","email","����ʱ��","��������");
	              $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	              $this->file_sqlname  =  "insert into tb_uploadfile  ";
   	        	 // break;
   	        	//case "sup":
   	          //  $this->file_headname =  array("���"        ,"��Ӧ�̼��"    ,"��Ӧ��ȫ��"       ,"��������"        ,"ʡ"               ,"��"            ,"��"              ,"��Ӧ������"          ,"��ַ"             ,"�ʱ�"              ,"����"         ,"�绰1"           ,"�绰2"           ,"email"          ,"��ϵ��"           ,"��ϵ���Ա�"   ,"��ϵ�˵绰"        ,"��������"      ,"�����ʺ�"         ,"˰��"         ,"��ҳ"         ,"��ע");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("ְԱ����","ְԱ���","ְԱְ��","����","������","��ְʱ��","��ְ/��ְ");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	        	//break;
   	    //}
    	      	
    	  $source = $userfile;
	      $source_name=$userfile_name;
	      $dirpart="../inputfile/tempfile/".$source_name;   //�����ļ���·��
        @copy($source,$dirpart);             //�����ļ�����
        
        
        $newname = date("Y").date("m").date("d").date("H").date("i").date("s").".".$selfileformat;
        $oldfilename="../inputfile/tempfile/".$source_name;    //���ļ�����
        $newfilename="../inputfile/tempfile/".$newname;        //���ļ�����
        
        @chmod($oldfilename,0777);                //�޸��ļ���ϵͳȨ��
        @rename($oldfilename,$newfilename);       //�޸��ļ����ƺ���
        
        
        
        $contentarray = file($newfilename);      //��ȥ�ļ�
        if(!empty($contentarray[0])){
        	
        	  $show  = "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
            $show .= "<form name='exc_upload' method='post' action=''>";
            for($i=0;$i < count($this->file_inputhidenvalue);$i++){
            	$hiddenname = $this->file_inputhidenvalue[$i][0];
          		$keyfield = $this->file_inputhidenvalue[$i][1];
            	$show .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
            }
            
            $show .= "<tr><td class=form_label><table width=100% cellspacing=0 cellpadding=0 border=0 height=100>";
			      $show .= "<tr><td class=inputtitle align=center colspan=2 >�����ļ�</td></tr>";
                               
            $arr_colcunt = explode(",",$contentarray[0]);
            $colcount = count($arr_colcunt);    //�ļ�������
            
            $arr_fieldname = $this->file_headname;
            for ($j = 0; $j <= $colcount; $j++) {
                $arr_date[] = $arr_colcunt[$j];   
                $arr_dateval[] = $j; 
            }
            for($i=0;$i < count($this->file_headname);$i++){
            	$cell = $this->makeselect($arr_date,"",$arr_dateval);
            	$show .= "<tr><td class=form_label2>&nbsp;����ֵ:<input type=checkbox name=fieldcheck[$i] value=1 checked >
		                    $arr_fieldname[$i]
		                    </td>
		                    <td class=form_label2>�ļ�������:
		                    <select name='fieldname[]'>
		                    $cell
		                    </select></td></tr>";
            }        
            
            $show .= "</table></td></tr><tr class=bottombotton><td colspan=2 align=center>";
            $show .= "<input type=hidden name=step value=2>";
            $show .= "<input type=hidden name=isuseheaders vwidth=70% class=InputFrameMainalue='$isuseheaders'>";
            $show .= "<input type=hidden name=thirdfilename value='$newfilename'>";
            $show .= "<input type=hidden name=selfileformat value='$selfileformat'>";
            $show .= "<input type=hidden name=selfiletype value='$selfiletype'>";
            $show .= "<input type=button class=buttonsmall value='��һ��' onClick='thirdstep()'></td></tr>";
            $show .= "</form></table>";
            $show .= "<DIV id=fadeinbox style='FILTER: progid:DXImageTransform.Microsoft.RandomDissolve(duration=1) progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135); moz-opacity: 0'>
                     <FIELDSET><LEGEND valign='top' align='center'><strong>��ʾ</strong></LEGEND>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td height='10'></td></tr><tr><td>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ֵǰ��Ķ�ѡ���У�����Ѿ���۴���Ҫ�����ϵͳ���ԡ�<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�ļ���������Ҫ������ļ���һ�е�ÿһ�е�������ʾ�������������Ҫ���ж�Ӧ�ڵ�����ֵ��������ݵ�ʱ��ͻ�Ѹ��е����ݱ��浽������ֵ�
                     </td></tr><tr><td height='10'></td></tr></table></FIELDSET>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td width='100%' align='right'><span title=' �� �� �� �� '  onclick='hidefadebox();return false' ><IMG height=16 hspace=2 src='../Images/CrossRed_path.gif' width=16 align=absMiddle border=0>Close</span></td>
                     </tr></table></DIV>";
        }else{
            $show ="�ļ�û������";
        }
        
        return $show ;    	
    }
    
    //����txt��csv�ļ��ĵ���������
    function thirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname){
	      $this->file_headname =  array("�ͻ�Id��","�ͻ����","�ͻ�ȫ��","Ӫҵ��","���۶���","�ͻ�����","���֤��/ע���","����","ע���ʱ�","��������","��Ҫ��ϵ��","ְλ","�ֻ�����","�绰1","�绰2","����","��ַ","��Ӫ״��","��������","�ʺ�","˰��","email","����ʱ��","��������");
	      $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	      
	      $this->file_sqlname  =  "insert into tb_uploadfile  ";
    	  
    	  $contentarray = file($thirdfilename);      //��ȥ�ļ�

    	  $fieldname_arr = $this->file_headval;
    	  
    	  
    	  for($k=0;$k<count($contentarray);$k++){
    	  	 if($isuseheaders!=1 && $k==0){   //�Ƿ����һ������
    	  	 	  //�������һ������
    	  	 	  continue;
    	     }else{
    	  	    $sqlname = $this->file_sqlname."(" ;     //�������
    	  	    $sqlfile = "";
    	  	    $sqlval  = "";
    	  	    $isemptyval = "";
    	  	    $arr_tempcontent = explode(",",$contentarray[$k]);

    	  	    for($j=0;$j < count($this->file_inputhiden);$j++){
			        	if(empty($sqlfile)){
    	  		        $sqlfile = $this->file_inputhiden[$j];
    	  		        $sqlval  = "'".$this->file_inputhidenvalue[$j][1]."'";
    	  		        
    	  		    }else{
    	  		       $sqlfile = $this->file_inputhiden[$j];
    	  		       $sqlval  .= ",'".$this->file_inputhidenvalue[$j][1]."'";
    	  		    }
              }
              
                            
    	        for($j=0;$j < count($this->file_headname);$j++){
    	  	        
    	  	        if($fieldcheck[$j]==1){
    	  	        	  $datecol = $fieldname[$j];
	  	        	  
    	  	        	  if(trim($arr_tempcontent[$datecol])!=""){
    	  		            $isemptyval = "1";  //�����ж�ȫ�������Ƿ�Ϊ��
    	  		          }
    	  		          
    	  		          if(empty($sqlfile)){
    	  		             $arr_temp = explode("&",$fieldname_arr[$j]);
    	  		             $sqlfile = $arr_temp[0];
    	  		             
    	  		             if($arr_temp[1]!="0"){
    	  		             	  $sqlval  = "'".$this->makechangeid(trim($arr_tempcontent[$datecol]),$arr_temp[1],$selfiletype)."'";
    	  		             	  $tmpbackvalue = $this->makeaddfield(trim($arr_tempcontent[$datecol]),$arr_temp[1]);
    	  		             	  $tmpbackvalue2 = $this->makeaddfield2(trim($arr_tempcontent[$datecol]),$arr_temp[1]);
    	  		             }else{
    	  		                $sqlval  = "'".trim($arr_tempcontent[$datecol])."'";
    	  		             }
    	  		          }else{
    	  		             $arr_temp = explode("&",$fieldname_arr[$j]);
    	  		             $sqlfile .= ",".$arr_temp[0];
    	  		             if($arr_temp[1]!="0"){
    	  		                $sqlval  .= ",'".$this->makechangeid(trim($arr_tempcontent[$datecol]),$arr_temp[1],$selfiletype)."'";
    	  		                $tmpbackvalue = $this->makeaddfield(trim($arr_tempcontent[$datecol]),$arr_temp[1]);
    	  		                $tmpbackvalue2 = $this->makeaddfield2(trim($arr_tempcontent[$datecol]),$arr_temp[1]);
    	  		             }else{
    	  		             	  $sqlval  .= ",'".trim($arr_tempcontent[$datecol])."'";
    	  		             }
    	  		          }
    	  	        } 
    	        }
    	        if(!empty($this->file_fieldname)){
    	        	  if(empty($sqlfile)){
    	        	  	 $sqlfile = $this->file_fieldname;
    	  		         $sqlval  = "'".$tmpbackvalue."'";
    	  		      }else{
    	  		         $sqlfile .= ",".$this->file_fieldname;
    	  		         $sqlval  .= ",'".$tmpbackvalue."'";
 
    	  		      }
    	        }
    	        if(!empty($this->file_fieldname2)){
    	        	  if(empty($sqlfile)){
    	        	  	 $sqlfile = $this->file_fieldname2;
    	  		         $sqlval  = "'".$tmpbackvalue2."'";
    	  		      }else{
    	  		         $sqlfile .= ",".$this->file_fieldname2;
    	  		         $sqlval  .= ",'".$tmpbackvalue2."'";
    	  		      }
    	        }
              
              
              
    	        if(!empty($isemptyval)){
    	          $query = $sqlname.$sqlfile." )values(".$sqlval.")";
    	          $this->db->query($query);  
    	        }
    	     }
    	  }
    	  @chmod($thirdfilename,0777);     //�޸��ļ���ϵͳ��Ȩ��
    	  @unlink($thirdfilename);         //ɾ������ʱ�ļ�
    	  
    	  $show = "<div align=center><b>�������ݳɹ���.</b><br><br><a href='".$this->file_backurl."'>�����������</a></div>";
    	  return $show;
    }
    
    //����ֻ���ڵ���excel���õ��ĺ���
    //require("includes.inc");   //---------------------------------------
    
    //����excel�ļ��ĵڶ�������
    function excelsecondstep($selfileformat,$userfile,$userfile_name,$isuseheaders,$selfiletype){     
    	  //switch($selfiletype){ 
   	        	//case "cus":   	        	 
   	        	  $this->file_headname =  array("�ͻ�Id��","�ͻ����","�ͻ�ȫ��","Ӫҵ��","���۶���","�ͻ�����","���֤��/ע���","����","ע���ʱ�","��������","��Ҫ��ϵ��","ְλ","�ֻ�����","�绰1","�绰2","����","��ַ","��Ӫ״��","��������","�ʺ�","˰��","email","����ʱ��","��������");
	              $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	      
	              $this->file_sqlname  =  "insert into tb_zlcustomer ";
   	        	//  break;
   	        	////case "sup":
   	          //  $this->file_headname =  array("���"        ,"��Ӧ�̼��"    ,"��Ӧ��ȫ��"       ,"��������"        ,"ʡ"               ,"��"            ,"��"              ,"��Ӧ������"          ,"��ַ"             ,"�ʱ�"              ,"����"         ,"�绰1"           ,"�绰2"           ,"email"          ,"��ϵ��"           ,"��ϵ���Ա�"   ,"��ϵ�˵绰"        ,"��������"      ,"�����ʺ�"         ,"˰��"         ,"��ҳ"         ,"��ע");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("ְԱ����","ְԱ���","ְԱְ��","����","������","��ְʱ��","��ְ/��ְ");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	        	//break;
   	    //}
    	  
    	  $source = $userfile;
	      $source_name=$userfile_name;
	      $dirpart="../inputfile/tempfile/".$source_name;   //�����ļ���·��
        @copy($source,$dirpart);             //�����ļ�����
        
        
        $newname = date("Y").date("m").date("d").date("H").date("i").date("s").".".$selfileformat;
        $oldfilename="../inputfile/tempfile/".$source_name;    //���ļ�����
        $newfilename="../inputfile/tempfile/".$newname;        //���ļ�����
        
        @chmod($oldfilename,0777);                //�޸��ļ���ϵͳȨ��
        @rename($oldfilename,$newfilename);       //�޸��ļ����ƺ���
               
        require_once '../include/reader.php';
        // ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();
        
        
        // Set output Encoding.ָ��������
        $data->setOutputEncoding('gb2312');
        
        // ָ����ȡ��excel�ļ�
        $data->read($newfilename);
        
        error_reporting(E_ALL ^ E_NOTICE);
        //ѭ����ȡÿһ����Ԫֵ
         
			  $show  .= "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
        $show .= "<form name='exc_upload' method='post' action=''>";
        for($i=0;$i < count($this->file_inputhidenvalue);$i++){
        	$hiddenname = $this->file_inputhidenvalue[$i][0];
        	$keyfield = $this->file_inputhidenvalue[$i][1];
        	$show .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
        }
       
				$show .= "<tr><td  class=inputtitle align=center >�����ļ�</td></tr>";
        $show .= "<tr><td class=form_label><table width=100% cellspacing=0 cellpadding=0 border=0 height=100>";
        
        $arr_fieldname = $this->file_headname;
        for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
            $arr_date[] = $data->sheets[0]['cells'][1][$j];   
            $arr_dateval[] = $j; 
        }
        for($i=0;$i < count($this->file_headname);$i++){
        	$cell = $this->makeselect($arr_date,"",$arr_dateval);
        	$show .= "<tr><td class=form_label2>&nbsp;����ֵ:<input type=checkbox name=fieldcheck[$i] value=1 checked >
		                $arr_fieldname[$i]
		                </td>
		                <td class=form_label2>�ļ�������:
		                <select name='fieldname[]'>
		                $cell
		                </select></td></tr>";
        }
        $show .= "</table></td></tr><tr class=bottombotton><td colspan=2 align=center>";
        $show .= "<input type=hidden name=step value=2>";
        $show .= "<input type=hidden name=isuseheaders value='$isuseheaders'>";
        $show .= "<input type=hidden name=thirdfilename value='$newfilename'>";
        $show .= "<input type=hidden name=selfileformat value='$selfileformat'>";
        $show .= "<input type=hidden name=selfiletype value='$selfiletype'>";
        $show .= "<input type=button class=buttonsmall value='��һ��' onClick='thirdstep()'></td></tr>";
        $show .= "</form></table>";
        $show .= "<DIV id=fadeinbox style='FILTER: progid:DXImageTransform.Microsoft.RandomDissolve(duration=1) progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135); moz-opacity: 0'>
                     <FIELDSET><LEGEND valign='top' align='center'><strong>��ʾ</strong></LEGEND>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td height='10'></td></tr><tr><td>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ֵǰ��Ķ�ѡ���У�����Ѿ���۴���Ҫ�����ϵͳ���ԡ�<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�ļ���������Ҫ������ļ���һ�е�ÿһ�е�������ʾ�������������Ҫ���ж�Ӧ�ڵ�����ֵ��������ݵ�ʱ��ͻ�Ѹ��е����ݱ��浽������ֵ�
                     </td></tr><tr><td height='10'></td></tr></table></FIELDSET>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td width='100%' align='right'><span title=' �� �� �� �� '  onclick='hidefadebox();return false' ><IMG height=16 hspace=2 src='../Images/CrossRed_path.gif' width=16 align=absMiddle border=0>Close</span></td>
                     </tr></table></DIV>";

	      return $show ;
    }
    
    function excelthirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname,$selfiletype){
    	  
    	  //switch($selfiletype){ 
   	        	//case "cus":
   	        	 
   	        	  $this->file_headname =  array("��������"       ,"ʡ"                ,"��"           ,"��"             ,"���"       ,"�ͻ����"     ,"�ͻ�ȫ��"        ,"��ַ"            ,"�ʱ�"             ,"����"        ,"�绰1"          ,"�绰2"          ,"email"         ,"ְλ"             ,"��ϵ��"          ,"��ϵ���ֻ�"       ,"��ϵ���Ա�"  ,"��������"     ,"�����ʺ�"        ,"˰��"        ,"��ҳ"        ,"��ע");
	              $this->file_headval  =  array("fd_cus_quyuid&1","fd_cus_provinces&0","fd_cus_city&0","fd_cus_county&0","fd_cus_no&0","fd_cus_name&0","fd_cus_allname&0","fd_cus_address&0","fd_cus_postcode&0","fd_cus_fax&0","fd_cus_phone1&0","fd_cus_phone2&0","fd_cus_email&0","fd_cus_position&0","fd_cus_linkman&0","fd_cus_manphone&0","fd_cus_sex&1","fd_cus_bank&0","fd_cus_account&0","fd_cus_tax&0","fd_cus_web&0","fd_cus_memo&0");
	              $this->file_sqlname  =  "insert into tb_uploadfile ";
   	        	  //break;
   	        	//case "sup":
   	          //  $this->file_headname =  array("���"        ,"��Ӧ�̼��"    ,"��Ӧ��ȫ��"       ,"��������"        ,"ʡ"               ,"��"            ,"��"              ,"��Ӧ������"          ,"��ַ"             ,"�ʱ�"              ,"����"         ,"�绰1"           ,"�绰2"           ,"email"          ,"��ϵ��"           ,"��ϵ���Ա�"   ,"��ϵ�˵绰"        ,"��������"      ,"�����ʺ�"         ,"˰��"         ,"��ҳ"         ,"��ע");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("ְԱ����","ְԱ���","ְԱְ��","����","������","��ְʱ��","��ְ/��ְ");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	   	 
   	   	        /*
   	   	        $this->file_headname =  array("��������","��������","���","�ͻ����","�ͻ�ȫ��","��ַ","�ʱ�","����","�绰1","�绰2","email","ְλ","��ϵ��","��ϵ�˵绰","��������","�����ʺ�","˰��","��ҳ","��ע");
	              $this->file_headval  =  array("fd_cus_organid&1","fd_cus_quyuid&2","fd_cus_no&0","fd_cus_name&0","fd_cus_allname&0","fd_cus_address&0","fd_cus_postcode&0","fd_cus_fax&0","fd_cus_phone1&0","fd_cus_phone2&0","fd_cus_email&0","fd_cus_position&0","fd_cus_linkman&0","fd_cus_manphone&0","fd_cus_bank&0","fd_cus_account&0","fd_cus_tax&0","fd_cus_web&0","fd_cus_memo&0");
	              $this->file_sqlname  =  "insert into tb_customer ";
   	        	  */
   	        	//break;
   	    //}
    	  
    	  
    	  $newfilename = $thirdfilename;     //�ļ�����
        
        require_once '../include/reader.php';
        // ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();
        
        
        // Set output Encoding.ָ��������
        $data->setOutputEncoding('gb2312');
        
        // ָ����ȡ��excel�ļ�
        $data->read($newfilename);
        
        error_reporting(E_ALL ^ E_NOTICE);
        // ѭ����ȡÿһ����Ԫֵ
        
        $fieldname_arr = $this->file_headval;
        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
        	  $sqlname = $this->file_sqlname."(" ;     //�������
    	  	  $sqlfile = "";
    	  	  $sqlval  = "";
    	  	  $isemptyval = "";
		        if ( $i == 1 && $isuseheaders!=1 )
			           continue;
			      
			      for($j=0;$j < count($this->file_inputhiden);$j++){
			      	if(empty($sqlfile)){
    	  		      $sqlfile = $this->file_inputhiden[$j];
    	  		      $sqlval  = "'".$this->file_inputhidenvalue[$j][1]."'";
    	  		      
    	  		  }else{
    	  		     $sqlfile = $this->file_inputhiden[$j];
    	  		     $sqlval  .= ",'".$this->file_inputhidenvalue[$j][1]."'";
    	  		  }
            }
		        for($j=0;$j < count($this->file_headname);$j++){
			           if ( $fieldcheck[$j]==1) {
			               $datecol = $fieldname[$j];
			               
			               if( trim(addslashes($data->sheets[0]['cells'][$i][$datecol]))!=""){
    	  		            $isemptyval = "1";  //�����ж�ȫ�������Ƿ�Ϊ��
    	  		         }
			               
			           	   if(empty($sqlfile)){
    	  		             $arr_temp = explode("&",$fieldname_arr[$j]);
    	  		             $sqlfile = $arr_temp[0];
    	  		             
    	  		             if($arr_temp[1]!="0"){
    	  		             	  $sqlval  = "'".$this->makechangeid(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1],$selfiletype)."'";
    	  		             	  $tmpbackvalue = $this->makeaddfield(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1],$selfiletype);
    	  		             	  $tmpbackvalue2 = $this->makeaddfield2(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1]);
    	  		             }else{
    	  		                $sqlval  = "'".trim(addslashes($data->sheets[0]['cells'][$i][$datecol]))."'";
    	  		             }
    	  		         }else{
    	  		            
    	  		             $arr_temp = explode("&",$fieldname_arr[$j]);
    	  		             $sqlfile .= ",".$arr_temp[0];
    	  		             if($arr_temp[1]!="0"){
    	  		                $sqlval  .= ",'".$this->makechangeid(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1],$selfiletype)."'";
    	  		                $tmpbackvalue = $this->makeaddfield(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1],$selfiletype);
    	  		             	  $tmpbackvalue2 = $this->makeaddfield2(trim(addslashes($data->sheets[0]['cells'][$i][$datecol])),$arr_temp[1]);
    	  		             }else{
    	  		             	  $sqlval  .= ",'".trim(addslashes($data->sheets[0]['cells'][$i][$datecol]))."'";
    	  		             }
    	  		         }
			           }
            }
            
            if(!empty($this->file_fieldname)){
    	        	  if(empty($sqlfile)){
    	        	  	 $sqlfile = $this->file_fieldname;
    	  		         $sqlval  = "'".$tmpbackvalue."'";
    	  		      }else{
    	  		         $sqlfile .= ",".$this->file_fieldname;
    	  		         $sqlval  .= ",'".$tmpbackvalue."'";
 
    	  		      }
    	        }
    	        if(!empty($this->file_fieldname2)){
    	        	  if(empty($sqlfile)){
    	        	  	 $sqlfile = $this->file_fieldname2;
    	  		         $sqlval  = "'".$tmpbackvalue2."'";
    	  		      }else{
    	  		         $sqlfile .= ",".$this->file_fieldname2;
    	  		         $sqlval  .= ",'".$tmpbackvalue2."'";
    	  		      }
    	        }
    	      if(!empty($isemptyval)){
    	      	$query = $sqlname.$sqlfile." )values(".$sqlval.")";
    	        $this->db->query($query);
    	      }
            
         }
	       @chmod($thirdfilename,0777);     //�޸��ļ���ϵͳ��Ȩ��
    	   @unlink($thirdfilename);         //ɾ������ʱ�ļ�
	       $show = "<div align=center><b>�������ݳɹ���.</b><br><br><a href='".$this->file_backurl."'>�����������</a></div>";
	       return $show ;
    }
    
     
    //����ѡ��˵��ĺ���
     function makeselect($arritem,$hadselected,$arry){ 
         for($i=0;$i<count($arritem);$i++){
             if ($hadselected ==  $arry[$i]) {
       	        $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
             }else{
       	        $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
             }
        } 
        return $x ; 
     }
     
    
    function makechangeid($txt_content,$typeid,$selfiletype){
    	return $txt_content;
    }
    
    function makeaddfield($txt_content,$typeid){
    	return $txt_content;
    }
    
    function makeaddfield2($txt_content,$typeid){
    	return $txt_content;
    }
	
}













?>