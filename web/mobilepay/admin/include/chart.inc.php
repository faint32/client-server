<?php
class chart {
	 var $classname = "chart";
   var $database_class = "DB_test";     //�������ݿ�������
   var $template_class = "Template";
   
   var $x_name = array() ;     // ������ı���
   var $x_value = array() ;    // �������ֵ   

   var $caption = "";          //�����
   var $subcaption="" ;        //С����
   var $xAxisName="";          //���������
   var $yAxisName="" ;         //���������
   var $yAxisMinValue="" ;     //���������ֵ�����Բ����롣
   var $numberPrefix="" ;      //����ǰ����ַ�
   var $charttype = "Line.swf";//ͼ����
   var $chart_skin = "";       //Ƥ��
   var $chart_width = "600";   //ͼ���
   var $chart_height = "300";  //ͼ��
   var $showValues = "0";      //��ʾ��ֵ0������ʾ��1������ʾ
   
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
       
       $str_charttype = "../Charts/".$this->charttype;  //ͼ������

       $this->t = new Template(".", "keep");    
       $this->t->set_file("chart","../include/chart.html"); 
       
       $this->t->set_var("charttype",$str_charttype);         //ͼ������
       $this->t->set_var("strXML",$strXML);                   //����
       $this->t->set_var("chart_width",$this->chart_width);	  // ͼ�ο�
       $this->t->set_var("chart_height",$this->chart_height);	// ͼ�θ�

       $this->t->set_var("skin",$this->browse_skin);	// Ƥ��

       $this->t->pparse("out", "chart");    # ������ҳ��
   }
}

?>