<?php
class chart {
	 var $classname = "chart";
   var $database_class = "DB_test";     //调用数据库连接类
   var $template_class = "Template";
   
   var $x_name = array() ;     // 横坐标的标题
   var $x_value = array() ;    // 横坐标的值   

   var $caption = "";          //大标题
   var $subcaption="" ;        //小标题
   var $xAxisName="";          //横坐标标题
   var $yAxisName="" ;         //纵坐标标题
   var $yAxisMinValue="" ;     //纵坐标最大值，可以不输入。
   var $numberPrefix="" ;      //数字前面的字符
   var $charttype = "Line.swf";//图表类
   var $chart_skin = "";       //皮肤
   var $chart_width = "600";   //图表宽
   var $chart_height = "300";  //图表长
   var $showValues = "0";      //显示数值0代表不显示，1代表显示
   
   function main() {
   	
   	   $strXML  = "<chart palette='2' caption='".$this->caption."' subcaption='".$this->subcaption."' xAxisName='".$this->xAxisName."' yAxisMinValue='".$this->yAxisMinValue."' yAxisName='".$this->yAxisName."' numberPrefix='".$this->numberPrefix."' showValues='".$this->showValues."'>";
       for($i=0;$i<count($this->x_name);$i++){
       	  $strXML .= "<set label='".$this->x_name[$i]."' value='".$this->x_value[$i]."' />";
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

       $this->t = new Template(".", "keep");    
       $this->t->set_file("chart","../include/chart.html"); 
       
       $this->t->set_var("charttype",$str_charttype);         //图形类型
       $this->t->set_var("strXML",$strXML);                   //内容
       $this->t->set_var("chart_width",$this->chart_width);	  // 图形宽
       $this->t->set_var("chart_height",$this->chart_height);	// 图形高

       $this->t->set_var("skin",$this->browse_skin);	// 皮肤

       $this->t->pparse("out", "chart");    # 最后输出页面
   }
}

?>