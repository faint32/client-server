<?php
class MSchart {
	 var $classname = "MSchart";
   var $database_class = "DB_test";     //�������ݿ�������
   var $template_class = "Template";
   
   var $x_name = array() ;     // ������ı���
   var $x_value = array() ;    // �������ֵ   
   var $seriesName = array() ;    //����
   var $color = array() ;    // ��ɫ  
   var $plotBorderColor = array() ;    // �����ɫ  

   var $caption = "";          //�����
   var $subcaption="" ;        //С����
   var $xAxisName="";          //���������
   var $yAxisName="" ;         //���������
   var $yAxisMinValue="" ;     //���������ֵ�����Բ����롣
   var $numberPrefix="" ;      //����ǰ����ַ�
   var $charttype = "MSLine.swf";//ͼ����
   var $chart_skin = "";       //Ƥ��
   var $chart_width = "600";   //ͼ���
   var $chart_height = "300";  //ͼ��
   var $showValues = "0";      //��ʾ��ֵ0������ʾ��1������ʾ
   var $rotateValues ="0";     //���ֵ���ʾ��ʽ��0�������ʾ��1���������ʾ
   var $placeValuesInside ="0";//���ֵ�λ����ʾ��0���ڶ�����1����ͼ�����档
   
   function main() {
   	
   	   $strXML  = "<chart palette='2' caption='".$this->caption."' subcaption='".$this->subcaption."' xAxisName='".$this->xAxisName."' yAxisMinValue='".$this->yAxisMinValue."' yAxisName='".$this->yAxisName."' numberPrefix='".$this->numberPrefix."' showValues='".$this->showValues."' rotateValues='".$this->rotateValues."' placeValuesInside='".$this->placeValuesInside."'>";
       $strXML .="<categories>";
       //������ѭ��
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
       
       $str_charttype = "../Charts/".$this->charttype;  //ͼ������
       
       if($this->showValues==0){
       	  $ischecked = "";
       }else{
          $ischecked = "checked";
       }

       $this->t = new Template(".", "keep");    
       $this->t->set_file("MSchart","../include/MSchart.html"); 
       
       $this->fun_setvaldate();  //��������
       
       $this->t->set_var("charttype",$str_charttype);         //ͼ������
       $this->t->set_var("strXML",$strXML);                   //����
       $this->t->set_var("chart_width",$this->chart_width);	  // ͼ�ο�
       $this->t->set_var("chart_height",$this->chart_height);	// ͼ�θ�
       $this->t->set_var("ischecked",$ischecked);	      // �Ƿ��Ѿ���ʾ����
       
       $this->t->set_var("caption",$this->caption);	// ͼ�θ�
       $this->t->set_var("subcaption",$this->subcaption);	// ͼ�θ�
       $this->t->set_var("xAxisName",$this->xAxisName);	// ͼ�θ�
       $this->t->set_var("yAxisName",$this->yAxisName);	// ͼ�θ�
       $this->t->set_var("yAxisMinValue",$this->yAxisMinValue);	// ͼ�θ�
       $this->t->set_var("numberPrefix",$this->numberPrefix);	// ͼ�θ�
       $this->t->set_var("showValues",$this->showValues);	// ͼ�θ�
       $this->t->set_var("rotateValues",$this->rotateValues);	// ͼ�θ�
       $this->t->set_var("placeValuesInside",$this->placeValuesInside);	// ͼ�θ�
       
       $this->t->set_var("str_label",$str_label);	// ͼ�θ�
       
       $this->t->set_var("xnamecount",count($this->x_name));	// ͼ�θ�

       $this->t->set_var("skin",$this->browse_skin);	// Ƥ��

       $this->t->pparse("out", "MSchart");    # ������ҳ��
   }
   
   function fun_setvaldate(){
   	   $this->t->set_block("MSchart", "TITLEBK", "titlebks");  // �б���
   	   $this->t->set_block("MSchart", "DATESETBK", "datasetbks");  // �б���
   	   $this->t->set_block("MSchart", "MAXBOXBK", "maxboxbks");  // �б���
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