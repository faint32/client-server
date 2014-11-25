<?php
//include_once ("excelparser.php");    //调用excel类的函数

class inputfile{
	
	 var $classname = "browse";
   var $database_class = "DB_test";     //调用数据库连接类
   var $template_class = "Template";
   var $excel_class = "ExcelFileParser";    //调用读取excel数据的类
   var $step = "0";                //步骤数
   var $file_backurl="";           //返回url地址
   var $file_headname = array();   //数据字段中文名的数组
   var $file_headval = array();    //数据字段的数据
   var $file_inputhiden = array();    //固定插入数据字段
   var $file_inputhidenvalue = array(); //固定插入数据字段的值
   //var $file_listflag = array();   //是否为把文本转换为id的函数
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
   	   	  $showtable = $this->firststep();   //调用第一步
   	   	  break;
   	   }
   	  // $loginskin="../skin/skin0/";
   	   
   	   $this->t = new Template(".", "keep");    
       $this->t->set_file("inputfile","../inputfile/inputfile_basic.html"); 
       $this->t->set_var("skin",$this->file_skin);
       $this->t->set_var("showtable",$showtable);    
       $this->t->pparse("out", "inputfile");    # 最后输出页面
   }
   
   function firststep() {	// 生成扩展连接
  	   $show = "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
	     $show .= "<tr><td width=19% class=inputtitle align=center >导入文件</td><td width='81%'  class=inputtitleright>&nbsp;</td></tr>";
	     $show .= "<tr><td colspan='4' height='10'></td></tr>";
       $show .= "<form name='exc_upload' method='post' action='' enctype='multipart/form-data'>";
       $show .= "<tr><td class=form_label>选择文件:</td>";
       $show .= "<td><input type='file' size=30 name=excel_file id='excel_fileid'></td></tr>";
       $show .= "<tr><td class=form_label>选择导入文件的资料类型:</td>";
       $show .= "<td ><select name=selfiletype><option value='cus'>会员</option></td></tr>";
       $show .= "<tr><td class=form_label>选择导入文件格式:</td>";
       $show .= "<td ><select name=selfileformat><option value='csv'>csv</option></select></td></tr>";
       //$show .= "<tr><td class=form_label>导入第一行数据:</td>";
       //$show .= "<td ><input type=checkbox name=isuseheaders value=1></td></tr>";  
       $show .= "<tr><td class=form_label2 colspan=2>&nbsp;&nbsp;注意：txt格式和csv格式的文件，每一列的内容要用逗号分开的。
                 不能导入第一行文件数据。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文件字体需要为简体(GB2312)或英文,其他字体(编码)会出现错误提示。</td></tr>";
	     $show .= "<tr class=bottombotton><td colspan=2 align=center>";
       $show .= "<input type=hidden name=step value=1>";
       $show .= "<input type=button class=buttonsmall value='下一步' onClick='secondlystep()'></td></tr>";   
       $show .= "</form></table>";
    	 return $show ;
    }
    
    //导入txt和csv文件的第二步函数
    function secondlystep($selfileformat,$userfile,$userfile_name,$isuseheaders){
    	   //switch($selfiletype){ 
   	        	//case "cus":   	        	 
   	        	  $this->file_headname =  array("客户Id号","客户编号","客户全称","营业额","销售吨数","客户性质","身份证号/注册号","法人","注册资本","成立日期","主要联系人","职位","手机号码","电话1","电话2","传真","地址","经营状况","开户银行","帐号","税号","email","新增时间","所属机构");
	              $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	              $this->file_sqlname  =  "insert into tb_uploadfile  ";
   	        	 // break;
   	        	//case "sup":
   	          //  $this->file_headname =  array("编号"        ,"供应商简称"    ,"供应商全称"       ,"所属区域"        ,"省"               ,"市"            ,"县"              ,"供应商类型"          ,"地址"             ,"邮编"              ,"传真"         ,"电话1"           ,"电话2"           ,"email"          ,"联系人"           ,"联系人性别"   ,"联系人电话"        ,"开户银行"      ,"银行帐号"         ,"税号"         ,"主页"         ,"备注");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("职员名称","职员编号","职员职务","部门","机构名","入职时间","在职/离职");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	        	//break;
   	    //}
    	      	
    	  $source = $userfile;
	      $source_name=$userfile_name;
	      $dirpart="../inputfile/tempfile/".$source_name;   //保存文件的路径
        @copy($source,$dirpart);             //保存文件函数
        
        
        $newname = date("Y").date("m").date("d").date("H").date("i").date("s").".".$selfileformat;
        $oldfilename="../inputfile/tempfile/".$source_name;    //旧文件名称
        $newfilename="../inputfile/tempfile/".$newname;        //新文件名称
        
        @chmod($oldfilename,0777);                //修改文件的系统权限
        @rename($oldfilename,$newfilename);       //修改文件名称函数
        
        
        
        $contentarray = file($newfilename);      //读去文件
        if(!empty($contentarray[0])){
        	
        	  $show  = "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
            $show .= "<form name='exc_upload' method='post' action=''>";
            for($i=0;$i < count($this->file_inputhidenvalue);$i++){
            	$hiddenname = $this->file_inputhidenvalue[$i][0];
          		$keyfield = $this->file_inputhidenvalue[$i][1];
            	$show .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
            }
            
            $show .= "<tr><td class=form_label><table width=100% cellspacing=0 cellpadding=0 border=0 height=100>";
			      $show .= "<tr><td class=inputtitle align=center colspan=2 >导入文件</td></tr>";
                               
            $arr_colcunt = explode(",",$contentarray[0]);
            $colcount = count($arr_colcunt);    //文件的列数
            
            $arr_fieldname = $this->file_headname;
            for ($j = 0; $j <= $colcount; $j++) {
                $arr_date[] = $arr_colcunt[$j];   
                $arr_dateval[] = $j; 
            }
            for($i=0;$i < count($this->file_headname);$i++){
            	$cell = $this->makeselect($arr_date,"",$arr_dateval);
            	$show .= "<tr><td class=form_label2>&nbsp;属性值:<input type=checkbox name=fieldcheck[$i] value=1 checked >
		                    $arr_fieldname[$i]
		                    </td>
		                    <td class=form_label2>文件列数据:
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
            $show .= "<input type=button class=buttonsmall value='下一步' onClick='thirdstep()'></td></tr>";
            $show .= "</form></table>";
            $show .= "<DIV id=fadeinbox style='FILTER: progid:DXImageTransform.Microsoft.RandomDissolve(duration=1) progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135); moz-opacity: 0'>
                     <FIELDSET><LEGEND valign='top' align='center'><strong>提示</strong></LEGEND>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td height='10'></td></tr><tr><td>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在属性值前面的多选框中，如果已经打扣代表将要导入的系统属性。<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文件列数据是要导入的文件第一行的每一列的内容显示在下拉框里，把需要的列对应在到属性值里，导入数据的时候就会把该列的数据保存到该属性值里。
                     </td></tr><tr><td height='10'></td></tr></table></FIELDSET>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td width='100%' align='right'><span title=' 关 闭 窗 口 '  onclick='hidefadebox();return false' ><IMG height=16 hspace=2 src='../Images/CrossRed_path.gif' width=16 align=absMiddle border=0>Close</span></td>
                     </tr></table></DIV>";
        }else{
            $show ="文件没有内容";
        }
        
        return $show ;    	
    }
    
    //导入txt和csv文件的第三步函数
    function thirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname){
	      $this->file_headname =  array("客户Id号","客户编号","客户全称","营业额","销售吨数","客户性质","身份证号/注册号","法人","注册资本","成立日期","主要联系人","职位","手机号码","电话1","电话2","传真","地址","经营状况","开户银行","帐号","税号","email","新增时间","所属机构");
	      $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	      
	      $this->file_sqlname  =  "insert into tb_uploadfile  ";
    	  
    	  $contentarray = file($thirdfilename);      //读去文件

    	  $fieldname_arr = $this->file_headval;
    	  
    	  
    	  for($k=0;$k<count($contentarray);$k++){
    	  	 if($isuseheaders!=1 && $k==0){   //是否导入第一条数据
    	  	 	  //不导入第一行数据
    	  	 	  continue;
    	     }else{
    	  	    $sqlname = $this->file_sqlname."(" ;     //插入语句
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
    	  		            $isemptyval = "1";  //用来判断全部列数是否为空
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
    	  @chmod($thirdfilename,0777);     //修改文件在系统的权限
    	  @unlink($thirdfilename);         //删除该临时文件
    	  
    	  $show = "<div align=center><b>导入数据成功！.</b><br><br><a href='".$this->file_backurl."'>返回浏览界面</a></div>";
    	  return $show;
    }
    
    //调用只有在导入excel中用到的函数
    //require("includes.inc");   //---------------------------------------
    
    //导入excel文件的第二步函数
    function excelsecondstep($selfileformat,$userfile,$userfile_name,$isuseheaders,$selfiletype){     
    	  //switch($selfiletype){ 
   	        	//case "cus":   	        	 
   	        	  $this->file_headname =  array("客户Id号","客户编号","客户全称","营业额","销售吨数","客户性质","身份证号/注册号","法人","注册资本","成立日期","主要联系人","职位","手机号码","电话1","电话2","传真","地址","经营状况","开户银行","帐号","税号","email","新增时间","所属机构");
	              $this->file_headval  =  array("fd_uploadfile_khid&1","fd_uploadfile_bianhao&0","fd_uploadfile_quchen&0","fd_uploadfile_money&0","fd_uploadfile_sale_dunshu&0","fd_uploadfile_khxz&0","fd_uploadfile_sfzh_zch&0","fd_uploadfile_frman&0","fd_uploadfile_zczb&0","fd_uploadfile_clrq&0","fd_uploadfile_zylxr&0","fd_uploadfile_zhiwei&0","fd_uploadfile_phone&0","fd_uploadfile_tel1&0","fd_uploadfile_tel2&0","fd_uploadfile_fax&0","fd_uploadfile_address&1","fd_uploadfile_jyzk&0","fd_uploadfile_bankname&0","fd_uploadfile_bankno&0","fd_uploadfile_sh&0","fd_uploadfile_email&0","fd_uploadfile_time&0","fd_uploadfile_jg&0");
	      
	              $this->file_sqlname  =  "insert into tb_zlcustomer ";
   	        	//  break;
   	        	////case "sup":
   	          //  $this->file_headname =  array("编号"        ,"供应商简称"    ,"供应商全称"       ,"所属区域"        ,"省"               ,"市"            ,"县"              ,"供应商类型"          ,"地址"             ,"邮编"              ,"传真"         ,"电话1"           ,"电话2"           ,"email"          ,"联系人"           ,"联系人性别"   ,"联系人电话"        ,"开户银行"      ,"银行帐号"         ,"税号"         ,"主页"         ,"备注");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("职员名称","职员编号","职员职务","部门","机构名","入职时间","在职/离职");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	        	//break;
   	    //}
    	  
    	  $source = $userfile;
	      $source_name=$userfile_name;
	      $dirpart="../inputfile/tempfile/".$source_name;   //保存文件的路径
        @copy($source,$dirpart);             //保存文件函数
        
        
        $newname = date("Y").date("m").date("d").date("H").date("i").date("s").".".$selfileformat;
        $oldfilename="../inputfile/tempfile/".$source_name;    //旧文件名称
        $newfilename="../inputfile/tempfile/".$newname;        //新文件名称
        
        @chmod($oldfilename,0777);                //修改文件的系统权限
        @rename($oldfilename,$newfilename);       //修改文件名称函数
               
        require_once '../include/reader.php';
        // ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();
        
        
        // Set output Encoding.指定中文码
        $data->setOutputEncoding('gb2312');
        
        // 指定读取的excel文件
        $data->read($newfilename);
        
        error_reporting(E_ALL ^ E_NOTICE);
        //循环读取每一个单元值
         
			  $show  .= "<table width=70% class=InputFrameMain cellspacing=0 cellpadding=0 border=0 height=100>";
        $show .= "<form name='exc_upload' method='post' action=''>";
        for($i=0;$i < count($this->file_inputhidenvalue);$i++){
        	$hiddenname = $this->file_inputhidenvalue[$i][0];
        	$keyfield = $this->file_inputhidenvalue[$i][1];
        	$show .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
        }
       
				$show .= "<tr><td  class=inputtitle align=center >导入文件</td></tr>";
        $show .= "<tr><td class=form_label><table width=100% cellspacing=0 cellpadding=0 border=0 height=100>";
        
        $arr_fieldname = $this->file_headname;
        for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
            $arr_date[] = $data->sheets[0]['cells'][1][$j];   
            $arr_dateval[] = $j; 
        }
        for($i=0;$i < count($this->file_headname);$i++){
        	$cell = $this->makeselect($arr_date,"",$arr_dateval);
        	$show .= "<tr><td class=form_label2>&nbsp;属性值:<input type=checkbox name=fieldcheck[$i] value=1 checked >
		                $arr_fieldname[$i]
		                </td>
		                <td class=form_label2>文件列数据:
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
        $show .= "<input type=button class=buttonsmall value='下一步' onClick='thirdstep()'></td></tr>";
        $show .= "</form></table>";
        $show .= "<DIV id=fadeinbox style='FILTER: progid:DXImageTransform.Microsoft.RandomDissolve(duration=1) progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135); moz-opacity: 0'>
                     <FIELDSET><LEGEND valign='top' align='center'><strong>提示</strong></LEGEND>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td height='10'></td></tr><tr><td>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在属性值前面的多选框中，如果已经打扣代表将要导入的系统属性。<br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文件列数据是要导入的文件第一行的每一列的内容显示在下拉框里，把需要的列对应在到属性值里，导入数据的时候就会把该列的数据保存到该属性值里。
                     </td></tr><tr><td height='10'></td></tr></table></FIELDSET>
                     <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                     <tr><td width='100%' align='right'><span title=' 关 闭 窗 口 '  onclick='hidefadebox();return false' ><IMG height=16 hspace=2 src='../Images/CrossRed_path.gif' width=16 align=absMiddle border=0>Close</span></td>
                     </tr></table></DIV>";

	      return $show ;
    }
    
    function excelthirdstep($isuseheaders,$thirdfilename,$fieldcheck,$fieldname,$selfiletype){
    	  
    	  //switch($selfiletype){ 
   	        	//case "cus":
   	        	 
   	        	  $this->file_headname =  array("所属区域"       ,"省"                ,"市"           ,"县"             ,"编号"       ,"客户简称"     ,"客户全称"        ,"地址"            ,"邮编"             ,"传真"        ,"电话1"          ,"电话2"          ,"email"         ,"职位"             ,"联系人"          ,"联系人手机"       ,"联系人性别"  ,"开户银行"     ,"银行帐号"        ,"税号"        ,"主页"        ,"备注");
	              $this->file_headval  =  array("fd_cus_quyuid&1","fd_cus_provinces&0","fd_cus_city&0","fd_cus_county&0","fd_cus_no&0","fd_cus_name&0","fd_cus_allname&0","fd_cus_address&0","fd_cus_postcode&0","fd_cus_fax&0","fd_cus_phone1&0","fd_cus_phone2&0","fd_cus_email&0","fd_cus_position&0","fd_cus_linkman&0","fd_cus_manphone&0","fd_cus_sex&1","fd_cus_bank&0","fd_cus_account&0","fd_cus_tax&0","fd_cus_web&0","fd_cus_memo&0");
	              $this->file_sqlname  =  "insert into tb_uploadfile ";
   	        	  //break;
   	        	//case "sup":
   	          //  $this->file_headname =  array("编号"        ,"供应商简称"    ,"供应商全称"       ,"所属区域"        ,"省"               ,"市"            ,"县"              ,"供应商类型"          ,"地址"             ,"邮编"              ,"传真"         ,"电话1"           ,"电话2"           ,"email"          ,"联系人"           ,"联系人性别"   ,"联系人电话"        ,"开户银行"      ,"银行帐号"         ,"税号"         ,"主页"         ,"备注");
	            //  $this->file_headval  =  array("fd_supp_no&0","fd_supp_name&0","fd_supp_allname&0","fd_supp_quyuid&1","fd_supp_xingfen&0","fd_supp_city&0","fd_supp_county&0","fd_supp_supptypeid&2","fd_supp_address&0","fd_supp_postcode&0","fd_supp_fax&0","fd_supp_phone1&0","fd_supp_phone2&0","fd_supp_email&0","fd_supp_linkman&0","fd_supp_sex&3","fd_supp_manphone&0","fd_supp_bank&0","fd_supp_account&0","fd_supp_tax&0","fd_supp_web&0","fd_supp_memo&0");
	            //  $this->file_sqlname  =  "insert into tb_zlsupplier ";
   	        	//break;
   	        	//case "sta":
   	          //  $this->file_headname =  array("职员名称","职员编号","职员职务","部门","机构名","入职时间","在职/离职");
	            //  $this->file_headval  =  array("fd_sta_name&0","fd_sta_stano&0","fd_sta_duty&1","fd_sta_deptid&2","fd_sta_agencyid&3","fd_sta_beginjobtime&0","fd_sta_dimission&4");
	            //  $this->file_sqlname  =  "insert into tb_zlstaffer ";
   	        	//break;
   	        	//default:
   	   	 
   	   	        /*
   	   	        $this->file_headname =  array("所属机构","所属区域","编号","客户简称","客户全称","地址","邮编","传真","电话1","电话2","email","职位","联系人","联系人电话","开户银行","银行帐号","税号","主页","备注");
	              $this->file_headval  =  array("fd_cus_organid&1","fd_cus_quyuid&2","fd_cus_no&0","fd_cus_name&0","fd_cus_allname&0","fd_cus_address&0","fd_cus_postcode&0","fd_cus_fax&0","fd_cus_phone1&0","fd_cus_phone2&0","fd_cus_email&0","fd_cus_position&0","fd_cus_linkman&0","fd_cus_manphone&0","fd_cus_bank&0","fd_cus_account&0","fd_cus_tax&0","fd_cus_web&0","fd_cus_memo&0");
	              $this->file_sqlname  =  "insert into tb_customer ";
   	        	  */
   	        	//break;
   	    //}
    	  
    	  
    	  $newfilename = $thirdfilename;     //文件名称
        
        require_once '../include/reader.php';
        // ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();
        
        
        // Set output Encoding.指定中文码
        $data->setOutputEncoding('gb2312');
        
        // 指定读取的excel文件
        $data->read($newfilename);
        
        error_reporting(E_ALL ^ E_NOTICE);
        // 循环读取每一个单元值
        
        $fieldname_arr = $this->file_headval;
        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
        	  $sqlname = $this->file_sqlname."(" ;     //插入语句
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
    	  		            $isemptyval = "1";  //用来判断全部列数是否为空
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
	       @chmod($thirdfilename,0777);     //修改文件在系统的权限
    	   @unlink($thirdfilename);         //删除该临时文件
	       $show = "<div align=center><b>导入数据成功！.</b><br><br><a href='".$this->file_backurl."'>返回浏览界面</a></div>";
	       return $show ;
    }
    
     
    //生成选择菜单的函数
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