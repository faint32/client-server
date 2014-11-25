<?php
class MSchart {
	 var $classname = "MSchart";
   var $database_class = "DB_test";     //调用数据库连接类
   var $template_class = "Template";
   
   var $x_name = array() ;     // 横坐标的标题
   var $x_value = array() ;    // 横坐标的值   
   var $seriesName = array() ;    //名称
   var $color = array() ;    // 颜色  
   var $plotBorderColor = array() ;    // 筐架颜色  

   var $caption = "";          //大标题
   var $subcaption="" ;        //小标题
   var $xAxisName="";          //横坐标标题
   var $yAxisName="" ;         //纵坐标标题
   var $yAxisMinValue="" ;     //纵坐标最大值，可以不输入。
   var $numberPrefix="" ;      //数字前面的字符
   var $charttype = "MSLine.swf";//图表类
   var $chart_skin = "";       //皮肤
   var $chart_width = "600";   //图表宽
   var $chart_height = "300";  //图表长
   var $showValues = "0";      //显示数值0代表不显示，1代表显示
   var $rotateValues ="0";     //数字的显示方式，0代表横显示，1代表大竖显示
   var $placeValuesInside ="0";//数字的位置显示，0放在顶部，1防在图形里面。
   
   function main() {
   	
   	   $strXML  = "<chart palette='2' caption='".$this->caption."' subcaption='".$this->subcaption."' xAxisName='".$this->xAxisName."' yAxisMinValue='".$this->yAxisMinValue."' yAxisName='".$this->yAxisName."' numberPrefix='".$this->numberPrefix."' showValues='".$this->showValues."' rotateValues='".$this->rotateValues."' placeValuesInside='".$this->placeValuesInside."'>";
       $strXML .="<categories>";
       //横坐标循环
       $str_label="<categories>";
       for($i=0;$i<count($this->x_name);$i++){   	  
           $strXML .="<category label='".$this->x_name[$i]."' />";
           
           $str_label .="<category label='".$this->x_name[$i]."' />";
       }
       $str_label .="</categories>";
       $strXML .="</categories>";
       for($i=0;$i<count($this->seriesName);$i++){
       	  //$strXML .="<dataset seriesName='".$this->seriesName[$i]."' color='".$this->color[$i]."' plotBorderColor='".$this->plotBorderColor[$i]."'>";
       	  $strXML .="<dataset seriesName='".$this->seriesName[$i]."'>";
       	  for($k=0;$k<count($this->x_value[$i]);$k++){
       	  	$strXML .="<set value='".$this->x_value[$i][$k]."' />";
         	}
         	$strXML .="</dataset>";
       }
       
       
       $strXML .= "<styles>";
       $strXML .= "<definition>";
	   $strXML .= "<style type='font' color='666666' name='CaptionFont' size='18' /><style type='font' name='xyfont' size='12' />";
       $strXML .= "<style name='Anim1' type='animation' param='_xscale' start='0' duration='1' />";
       $strXML .= "<style name='Anim2' type='animation' param='_alpha' start='0' duration='1' />";
       $strXML .= "<style name='DataShadow' type='Shadow' alpha='20'/>";
       $strXML .="</definition>";
       $strXML .= "<application><apply toObject='xAxisName' styles='xyfont' /><apply toObject='yAxisName' styles='xyfont' /><apply toObject='caption' styles='CaptionFont' /><apply toObject='DIVLINES' styles='Anim1' /><apply toObject='HGRID' styles='Anim2' /><apply toObject='DATALABELS' styles='DataShadow,Anim2' /></application>";
       $strXML .= "</styles>";
       $strXML .= "</chart>";
       
       $str_charttype = "../Charts/".$this->charttype;  //图形类型
       
       if($this->showValues==0){
       	  $ischecked = "";
       }else{
          $ischecked = "checked";
       }

       $this->t = new Template(".", "keep");    
       $this->t->set_file("MSchart","../include/MSchart.html"); 
       
       $this->fun_setvaldate();  //设置数据
       
       $this->t->set_var("charttype",$str_charttype);         //图形类型
       $this->t->set_var("strXML",$strXML);                   //内容
       $this->t->set_var("chart_width",$this->chart_width);	  // 图形宽
       $this->t->set_var("chart_height",$this->chart_height);	// 图形高
       $this->t->set_var("ischecked",$ischecked);	      // 是否已经显示数据
       
       $this->t->set_var("caption",$this->caption);	// 图形高
       $this->t->set_var("subcaption",$this->subcaption);	// 图形高
       $this->t->set_var("xAxisName",$this->xAxisName);	// 图形高
       $this->t->set_var("yAxisName",$this->yAxisName);	// 图形高
       $this->t->set_var("yAxisMinValue",$this->yAxisMinValue);	// 图形高
       $this->t->set_var("numberPrefix",$this->numberPrefix);	// 图形高
       $this->t->set_var("showValues",$this->showValues);	// 图形高
       $this->t->set_var("rotateValues",$this->rotateValues);	// 图形高
       $this->t->set_var("placeValuesInside",$this->placeValuesInside);	// 图形高
       
       $this->t->set_var("str_label",$str_label);	// 图形高
       
       $this->t->set_var("xnamecount",count($this->x_name));	// 图形高

       $this->t->set_var("skin",$this->browse_skin);	// 皮肤

       $this->t->pparse("out", "MSchart");    # 最后输出页面
   }
   
   function fun_setvaldate(){
   	   $this->t->set_block("MSchart", "TITLEBK", "titlebks");  // 列标题
   	   $this->t->set_block("MSchart", "DATESETBK", "datasetbks");  // 列标题
   	   $this->t->set_block("MSchart", "MAXBOXBK", "maxboxbks");  // 列标题
   	   for($i=0;$i<count($this->seriesName);$i++){
   	   	   $tmpdate = "";   
   	   	   $tmpdate = '"'.$this->seriesName[$i].'"';
   	   	   for($k=0;$k<count($this->x_value[$i]);$k++){
   	   	   	  $tmpdate .= ",".$this->x_value[$i][$k];	 
         	 }
         	 
         	 $this->t->set_var(array("datecount" => $i,
     		      	                   "datecontent" => $tmpdate
     		      	            )) ;
          $this->t->parse("titlebks", "TITLEBK", true);
          
          $maxboxname = "ischeckname".$i;
          
          $this->t->set_var(array("datenum"    => $i,
     		      	                  "maxboxname" => $maxboxname
     		      	            )) ;
          $this->t->parse("datasetbks", "DATESETBK", true);
          
          $this->t->set_var(array("maxboxlabel" => $this->seriesName[$i],
     		      	                  "maxboxname"  => $maxboxname
     		      	            )) ;
          $this->t->parse("maxboxbks", "MAXBOXBK", true);
       } 
       
   }
}

?>