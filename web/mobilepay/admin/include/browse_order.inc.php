<?php
/*
 browseҳ����
 */ 


 
class browse {
	 var $classname = "browse";
   var $database_class = "DB_test";     //�������ݿ�������
   var $template_class = "Template";
   
   var $prgnoware = array() ;  // ��������λ��
   var $prgnowareurl = array() ; // ��������λ��url   

   var $browse_rows = 25;  //ÿҳ��ʾ�ļ�¼����
   var $browse_pages ;     //�ܹ�ҳ��
   var $browse_nowpage ;   //��ǰҳ��
   var $browse_rowfrom ;   //��ҳ��ʾ�ļ�¼�ӵڼ���
   var $browse_rowto ;     //��ҳ��ʾ�ļ�¼���ڼ���
   var $browse_total ;     //�ܵļ�¼��
   var $browse_order = "";     //��ǰ��ѯ˳��
   var $browse_ordersn = "" ;  // ˳�������
   var $browse_defaultorder = "";  //Ĭ������
   var $browse_editname = "�޸�" ;
   var $browse_delname = "ɾ��";
   var $browse_isshowtitleeditname = "0" ;
   //var $browse_showpages = 10;     //ͬʱ��ʾ��ҳ��
  
   var $browse_query = "";        //��ȡ������Դ ��"select title,auth from book" 
   var $browse_queryselect = "";  //��ѯ���ı���**********
   var $browse_querywhere = "";   //��ѯ������������********
   var $browse_queryorder = "";   //��ʲô����ı���************
   var $browse_querygroupby  = "";   //��ʲô����ı���************
   var $browse_field = array();	// ��ʾ���ֶ�
   
   //var $browse_qx ;  //   ��ǰ�û���Ȩ��

   var $browse_key ;  //   ��¼Ψһ��ֵ
   var $browse_check = array();   // ��ѡ��ļ�¼
   var $browse_delsql = "" ;   // ɾ�����
   //var $browse_delcontsql = "";  //ɾ����$browse_delsql�����Ӧ�����
   var $browse_relatingdelsql=array();//ɾ����$browse_delsql�����Ӧ�����
   
   var $browse_collectquery ="";  //���ܲ�ѯ���
   var $browse_collectname = "";  //������Ӧ������
   var $browse_collectdata = "";  //��������
   
   var $browse_new = "" ; // �½�����
   var $browse_edit = "" ; // �༭����
   var $browse_alledit = "" ; // �༭����
   var $browse_inputfile  = "" ; // ������������
   var $browse_outtoexcel = "" ; // ����excle����
   var $browse_fieldname  = array() ; //�������ݵ��ֶ�����
   var $browse_fieldval  = array() ; // �������ݵ��ֶ�
   var $browse_ischeck  = array();   //�Ƿ�Ĭ�ϵ����Ѿ�ѡ��1����ѡ��0����û��ѡ��
   
   var $browse_outtoprint = "" ; // �г���ӡ������
   var $browse_printfieldname  = array() ; //�г���ӡ�����ݵ��ֶ�����
   var $browse_printfieldval  = array() ; // �г���ӡ�����ݵ��ֶ�
   var $browse_printischeck  = array();   //�Ƿ�Ĭ���г���ӡ���Ѿ�ѡ��1����ѡ��0����û��ѡ��
   
   var $backuppageurl = "";  //���ذ�ť��url
   
   var $browse_state = array();   //�����ȡ���ֶΡ������ж�״̬��
   var $arr_spilthfield = array(); //�洢�����ֶε�ֵ��
   
   var $browse_addqx = "";     //����Ȩ��
   var $browse_editqx = "";    //�༭Ȩ��
   var $browse_delqx = "";     //ɾ��Ȩ��
   
   var $browse_rowfield = array();  //����������ʾ
   
   var $browse_hidden = array() ; // ���ص�ֵ-----2005-10-31----------------

   var $browse_link = array() ; // ��չ����   
   var $browse_find = array() ; // ��ѯ�б�
   var $browse_sayfind = "" ; // ��ǰ��ѯ����˵��
   var $browse_showfind = array() ; // ��ѯ��ʾ��
   
   var $browse_seldofile = array() ; //����������--------2006-1-12-----------
   var $browse_seldofileval = array() ; //�����������ֵ--------2006-1-12-----------
   var $browse_haveselectvalue ="";   //������ѡ�е�ֵ--------2006-1-12-----------
   var $browse_firstval ="";   //�������һ����ʾ����--------2006-1-12-----------
   
   var $browse_operationflag = "0";  //-------��ʶ�������Ƿ���ǰ�滹�Ǻ���----------2008-6-28����--------------------
   var $browse_editplaceflag = "0";  //��ʶ�༭�Ƿ���ǰ�滹�Ǻ���----------2009-7-2����--------------------
   
   //var $browse_more = 0 ;  // �����0����ʾ������1����ʾ 

   //var $browse_tmpcatlog = "../include/" ;  //ģ���ļ�·��
   //var $browse_tmpname = "../include/browse.ihtml" ;  //ģ���ļ���   
   
   //var $browse_bakurl ;  //��������   
   var $browse_findnum = 5 ;  // ��ѯ����

   var $browse_skin = "" ;
   var $labelnum =0;   //
   var $allcondition=0;  //Ĭ�ϵĲ�ѯ��������
   
   //$action���¼�������$pagerows��һҳ��ʾ��С�С�checknot���Ҫɾ���ļ�¼����
   //$upordown���������
   function main($now,$action,$pagerows,$order,$upordown,$checknote,
  		           $prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) {
  		
  		$this->browse_nowpage = $now ;
      $this->browse_order = $order ; 
      $this->browse_ordersn = $upordown ;  
      $this->browse_check = $checknote ;  
      $this->browse_keyword = $keyword ;  
      //$this->browse_findtype = $findtype ;  
               	
  	  $this->browse_rows = $pagerows ;  //��ֵһҳ��ʾ��С��
      if($this->browse_rows == 0 ) $this->browse_rows = 25 ;	   //��������Ǿ�Ĭ��Ϊ25��
      
      //������������Ϊ���Ǿͱ�ʾ���ñ������з�����ʾ��
      if(!empty($this->browse_querygroupby)) $this->browse_querygroupby = "group by ".$this->browse_querygroupby;     
      
      //  �ó�������������   
      $urlcan = "?now=".$now."&pagerows=".$pagerows."&order=".$order."&upordown=".$upordown ;   	
  		$tempurl = rawurlencode($urlcan); //����ʽ���ִ������ URL ���ִ�ר�ø�ʽ
  		
  		$name = $this->database_class;   
      $this->db = new $name;        
      
      $prglist = "";	// �ó�ͷ��
      for($i=0;$i<count($this->prgnoware);$i++){
        if (empty($this->prglist)){
           $this->prglist .= "&nbsp;".$this->prgnoware[$i];
        }else{
        	 $this->prglist .= "&nbsp;-->&nbsp;".$this->prgnoware[$i]."\n";
           //$this->prglist .= "&nbsp;--><A href=\"".$this->prgnowareurl[$i]."\">&nbsp;".$this->prgnoware[$i]."</A>\n";
        }
      }  
      
      if($action=="delete") $this->dodelete();  //�����ɾ������͵���ɾ������*******  	
      
      // ���ֶ������
      $fdcount = count($this->browse_field) ;    //�ܹ��ж�СҪ��ʾ�ĸ��ֶ�
      for($i=0;$i<$fdcount;$i++){
       	$name = $this->browse_field[$i];
     	  $this->fd[$i] = new $name;
      }
      //-------------------2005-10-18-------------------------------
      $fdrowcount = count($this->browse_rowfield) ;    //�ܹ��ж�СҪ������ʾ�ĸ��ֶ�
      for($i=0;$i<$fdrowcount;$i++){
       	$name = $this->browse_rowfield[$i];
       	$sx_k = $fdcount+$i;
     	  $this->fd[$sx_k] = new $name;
      }
      //-------------------2005-10-18-------------------------------
      if(empty($this->browse_nowpage)){		//��ǰҳ
    	  $this->browse_nowpage = 1; 	 // �����һ�ν���Ͱ�nowpage��ֵΪ1
      }
      $this->makewhere($whatdofind,$howdofind,$findwhat,$allcondition);	// �ó���ѯ����
      $query = $this->makequery("all");	// ������
      
      if($action=="collect"){  //����ǻ�������ͼ��������*******  	
      	$this->docollect();  //���û��ܺ���
      	$isshowcollecttr = "";
      	
      }else{
        $isshowcollecttr = "none";
      }
      
      if(empty($this->browse_collectquery)){
      	$dis_collectdata = "none";
      }else{
        $dis_collectdata = "";
      }
      
      $this->db->query($query);
      $this->browse_total = $this->db->nf();	//�ܵļ�¼��
      
      $this->browse_pages = ceil($this->browse_total/$this->browse_rows); //�ܹ�ҳ��
      if($this->browse_nowpage > $this->browse_pages) $this->browse_nowpage = $this->browse_pages ; // if��ǰҳ>�ܹ�ҳ��,��ǰҳ=�ܹ�ҳ��
      $this->browse_rowfrom = ($this->browse_nowpage-1) * $this->browse_rows ; //��ҳ��ʾ�ļ�¼�ӵڼ���
      
      // �ó���ҳ��������
      //$this->browse_nowpage�ǵ�ǰҳ����$this->browse_pages�ܹ�ҳ����
      $showviewpage = $this->pagination($this->browse_nowpage,$this->browse_pages) ;
    
      // �ó��������
      if(!empty($this->browse_order)){
        $this->makeorder($fdcount);  
      }else{
        //�ó�Ĭ���������
        if(!empty($this->browse_defaultorder)){
          $this->browse_queryorder = " order by ".$this->browse_defaultorder ;
        }
      }
      
      // �ó���ҳ��¼
      $query = $this->makequery("now");	// ������
      $this->db->query($query);

      // �����������
      $lkcount = count($this->browse_link) ;
      for($i=0;$i<$lkcount;$i++){
     	   $name = $this->browse_link[$i];
     	   $this->lk[$i] = new $name;
      }
      
      $titlearr = $this->showtitle($fdcount,$lkcount);    // �ֶα���
      $TrColor = ""; 
      
      
      //��ʾ��ѯ����������
      while($this->db->next_record()) {
    	    $key = $this->db->f($this->browse_key) ;	// �ó�Ψһֵ
    	    for($i=0;$i<$fdcount;$i++){		// ���ֶ���ֵ
    	     	$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    	     	
    	     	$this->fd[$i]->bwfd_otherfieldval = $this->db->f($this->fd[$i]->bwfd_otherfieldname);  //2010-3-22�ռ��룬��ʾ�ֶεĻ�ȡ�����ֶε�ֵ
    	     	
    		    for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			     $this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		    }    		
    	    }
    	    //-----------2005-10-18------------------
    	    for($sx_j=0;$sx_j<$fdrowcount;$sx_j++){		// ���е��ֶ���ֵ
    	    	$i = $sx_j+$fdcount;
    	     	$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    		    for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			     $this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		    }    		
    	    }
    	    //-----------2005-10-18------------------
    	    
    	    //-----------2007-10-30------------------------------
    	    for($i=0;$i<count($this->browse_state);$i++){		//��ȡ�����ֶε�ֵ
    	    	if(!empty($this->browse_state[$i])){
    	       	$this->arr_spilthfield[$i] = $this->db->f($this->browse_state[$i]);   
    	      }		
    	    }
    	    //-----------2007-10-30-----------------------------
    	    
    	    for($i=0;$i<$lkcount;$i++){		// ��������ֵ
    		    for($j=0;$j<count($this->lk[$i]->bwlk_fdname);$j++){
    			     $this->lk[$i]->bwlk_fdname[$j][1] = $this->db->f($this->lk[$i]->bwlk_fdname[$j][0]);
    		    }
    	    }
    	   $this->labelnum++;  //�������
    	   if($this->browse_operationflag == 0){   //-----------------2008-6-28�޸�--------------------
    	   	 $showline  = "<label for=arrlabel[".$this->labelnum."] oncontextmenu = showMenu() ><TD class=listcellrow style='WIDTH: 10px;'><input type='checkbox' id=arrlabel[".$this->labelnum."] name='checknote[]' value='$key' ></td>";
    	     if($this->browse_editplaceflag == 1){
    	       $showline .= $this->showeditlink($lkcount,$key);	// �ó��༭����
    	       $showline .= $this->showline($fdcount,$key);	// �ó�����
      	     $showline .= $this->showlink($lkcount,$key);	// �ó���չ����
      	   }else{
    	       $showline .= $this->showline($fdcount,$key);	// �ó�����
    	       $showline .= $this->showeditlink($lkcount,$key);	// �ó��༭����
      	     $showline .= $this->showlink($lkcount,$key);	// �ó���չ����
      	   }
      	 }else{
      	   $showline  = "<label for=arrlabel[".$this->labelnum."] oncontextmenu = showMenu() ><TD class=listcellrow style='WIDTH: 10px;'><input type='checkbox' id=arrlabel[".$this->labelnum."] name='checknote[]' value='$key' ></td>";
      	   $showline .= $this->showeditlink($lkcount,$key);	// �ó��༭����
      	   $showline .= $this->showlink($lkcount,$key);	// �ó���չ����
      	   $showline .= $this->showline($fdcount,$key);	// �ó�����
      	 }
  	     $TrColor = $this->Trbgcolor($TrColor) ;  //��ɫ����
  	     
  	      //-----------2005-10-18-------------------
  	     $td_content="";
  	     for($tr_i=0;$tr_i< $fdrowcount;$tr_i++){
  	     	  $tr_k = $fdcount+$tr_i;
  	        $td_content .= $this->showtrfield($TrColor,$tr_k,$fdcount,$lkcount);  //��ʾһ�е�����
  	     }
  	     //-----------2005-10-18------------------
  	     
	       $linearr[] = array("bgcolor" => $TrColor,"xi" => $showline,"td_con" => $td_content,"labelnum" => $this->labelnum);
       }
       
       if( empty($this->browse_new) or $this->browse_addqx!=1){	// �����½�����
    	    $shownew = "" ;
    	    $showdisnew = "none" ;
       }else{
    	    $shownew = $this->makenew();
    	    $showdisnew = "" ;
       }

       if( empty($this->browse_edit) or $this->browse_editqx!=1 ){	// ���ɱ༭����
    	    $showalledit = "" ;
    	    $showalldisedit = "none" ;
       }else{
    	    $showalledit = $this->makealledit();
    	    if($this->browse_isshowtitleeditname=="1"){
    	      $showalldisedit = "none" ;
    	    }else{
    	      $showalldisedit = "" ;
    	    }
       }

       if(empty($this->browse_delsql) or $this->browse_delqx!=1){	// ����ɾ������
    	    $showdel = "" ;
    	    $showdisdel = "none" ;
       }else{
    	    $showdel = $this->makedel();
    	    $showdisdel = "" ;
       }
       
       if(empty($this->backuppageurl)){	// ���ɷ��ص����� ;
    	    $displaybackbutton = "none" ;
       }else{
    	    $displaybackbutton = "" ;
       }
       
       /*if($showalldisedit == "none" && $showdisdel == "none" ){
        $showcountforlist = "style='display:none'";	
       }*/
       
       if(empty($this->browse_inputfile)){	   //������������
           $inputfile = "" ;
           $disinputfile = "none";
       }else{
           $inputfile = $this->browse_inputfile;
           $disinputfile = "";
       }
       
       if(empty($this->browse_outtoexcel)){	   //����excle����
           $outtoexcle = "" ;
           $disoutexcle = "none";
       }else{
           $outtoexcle = $this->browse_outtoexcel;
           $disoutexcle = "";
       }
       
       if(empty($this->browse_outtoprint)){	   //��ӡ��������
           $outtoprint = "" ;
           $disoutprint = "none";
       }else{
           $outtoprint = $this->browse_outtoprint;
           $disoutprint = "";
       }
       
       switch($allcondition){
       	  case "0":
       	     $isallcheck ="";
       	     $iseithercheck ="checked";
       	     break;
       	  case "1":
       	     $isallcheck ="checked";
       	     $iseithercheck ="";
       	     break;
       	     default:
       	     $isallcheck ="checked";
       	     $iseithercheck ="";
       	     break;
       	     
       }
       
       $showhidden = $this->makehidden();  //-----2005-10-31-------
       
       
       //------------2006-1-12----------------------------
       $seldofile_num = count($this->browse_seldofile);
       $arr_seldofile = $this->browse_seldofile;
       $arr_seldofileval = $this->browse_seldofileval;
       $haveselectvalue  = $this->browse_haveselectvalue;     //������ѡ�е�ֵ
       $seldofile ="";
       $flagissel=0;
       
       //$seldofile = makeselectfile();
       for($i=0;$i<$seldofile_num;$i++){ 
       	    if($haveselectvalue ==  $arr_seldofileval[$i]){
       	    	  $seldofile .= "<option selected value='".$arr_seldofileval[$i]."'>".$arr_seldofile[$i]."</option>";
       	    }else{
       	        $seldofile .= "<option value='".$arr_seldofileval[$i]."'>".$arr_seldofile[$i]."</option>";
       	    }
       	    $flagissel=1;
       }
       if($flagissel==1){
       	  $displaysel ="";
       }else{
          $displaysel ="none";
       }
       
       $this->browse_rowto = $this->browse_rowfrom + $this->db->nf() ;  //��ҳ��ʾ�ļ�¼���ڼ���
             
                                 
       $this->showfind($whatdofind,$howdofind,$findwhat) ; // ���ɲ�ѯ

       $this->t = new Template(".", "keep");    
       //$this->t->set_file("browse",$this->browse_skin.$this->browse_tmpname); 
       $this->t->set_file("browse","../include/browse_order.html"); 
       $this->t->set_var("prglist",$this->prglist);
       $this->t->set_var("findsay",$this->browse_sayfind);

       $this->t->set_var("disnew",$showdisnew);
       $this->t->set_var("shownew",$shownew);
    
	     $this->t->set_var("disedit",$showalldisedit);
       $this->t->set_var("showedit",$showalledit);
	
       $this->t->set_var("disdel",$showdisdel);
       $this->t->set_var("showdel",$showdel);
       
       $this->t->set_var("inputfile",$inputfile);    //��������
       $this->t->set_var("disinputfile",$disinputfile);
       
       $this->t->set_var("outtoexcle",$outtoexcle);    //����excel
       $this->t->set_var("disoutexcle",$disoutexcle);
       
       $this->t->set_var("outtoprint",$outtoprint);    //��ʾ��ӡ������
       $this->t->set_var("disoutprint",$disoutprint);
       
       $this->t->set_var("browse_collectdata",$this->browse_collectdata);  //��������
       $this->t->set_var("isshowcollecttr",$isshowcollecttr);    //�Ƿ���ʾ
       $this->t->set_var("dis_collectdata",$dis_collectdata);    //�Ƿ���ʾ���ܰ�ť
       
       $this->t->set_var("backuppageurl",$this->backuppageurl);
       $this->t->set_var("displaybackbutton",$displaybackbutton);
       
       $this->t->set_var("showhidden",$showhidden);   //-----2005-10-31-------
       
       $this->t->set_var("editname",$this->browse_editname);   //-----2008-2-21-------
       $this->t->set_var("delname",$this->browse_delname);   //-----2010-3-21-------

       $this->t->set_var("showcountforlist",$showcountforlist);
    
       $this->t->set_var("total",$this->browse_total);
       $this->t->set_var("pages",$this->browse_pages);
       $this->t->set_var("nowpage",$this->browse_nowpage);    
       $this->t->set_var("showviewpage",$showviewpage);
       $this->t->set_var("rows",$this->browse_rows);
       $this->t->set_var("order",$this->browse_order);
       $this->t->set_var("upordown",$this->browse_ordersn);

       $this->t->set_var("recodeform",$this->browse_rowfrom);
       $this->t->set_var("recodeto",$this->browse_rowto);
       
       $this->t->set_var("seldofile",$seldofile);    //*********ѡ��������**************
       $this->t->set_var("displaysel",$displaysel);  //*********�Ƿ���ʾ������*************
       $this->t->set_var("firstval",$this->browse_firstval); //��������ʾ�ĵ�һ��ֵ
       
       $this->t->set_var("isallcheck",$isallcheck);
       $this->t->set_var("iseithercheck",$iseithercheck);
    
       $this->t->set_var("url",$tempurl);
       $this->t->set_var("skin",$this->browse_skin);	// Ƥ��
    
       $this->t->set_block("browse", "TITLEBK", "titlebks");  // �б���
       for($i=0;$i<count($titlearr);$i++){
          $this->t->set_var(array("title" => $titlearr[$i][title],
     		      	                  "titlecss" => $titlearr[$i][titlecss],
     		      	                  "cols" => $titlearr[$i][cols]
     		      	            )) ;
          $this->t->parse("titlebks", "TITLEBK", true);
       }

       $this->t->set_block("browse", "DATABK", "databks");  // ������
       $n = count($linearr);
       if($n>0){
          for($i=0;$i<$n;$i++){
             $this->t->set_var(array("bgcolor" => $linearr[$i][bgcolor],
         		      	                 "xi" => $linearr[$i][xi],
         		      	                 "td_content" => $linearr[$i][td_con],
         		      	                 "labelnum" => $linearr[$i][labelnum]
         		      	           )) ;
             $this->t->parse("databks", "DATABK", true);
          }
       }else{
          $this->t->set_var(array("bgcolor" => "",
         		      	              "xi" => "",
         		      	              "td_content" => ""
         		      	      )) ;
          $this->t->parse("databks", "DATABK", true);
       }

       $this->t->set_block("browse", "FINDBK", "findbks");  // ������
       for($i=0;$i<$this->browse_findnum;$i++){
            $this->t->set_var(array("whatdofind" => $this->browse_showfind[$i][0],
      			                       "howdofind" => $this->browse_showfind[$i][1],
      			                       "findwhat" => $this->browse_showfind[$i][2]
     		      	              )) ;
            		      	      
            $this->t->parse("findbks", "FINDBK", true);
       }
       
       //����excelѡ�����ԵĿ�2007��6��28�ռ���
       $this->t->set_block("browse", "OUTFIELD", "outfields");  // ������
       for($i=0;$i<count($this->browse_fieldval);$i++){
       	  if($this->browse_ischeck[$i]==1){
       	  	$ischeckfield = "checked";
       	  }else{
       	    $ischeckfield = "";
       	  }
       	  $this->t->set_var(array("fieldvalue"    => $this->browse_fieldval[$i],
      			                      "fieldname"     => $this->browse_fieldname[$i],
      			                      "ischeckfield"  => $ischeckfield
     		      	            )) ;
            		      	      
          $this->t->parse("outfields", "OUTFIELD", true);
       }
       
       //��ӡ�ļ�ѡ�����ԵĿ�2008��3��21�ռ���
       $this->t->set_block("browse", "PRINTFIELD", "printfields");  // ������
       for($i=0;$i<count($this->browse_printfieldval);$i++){
       	  if($this->browse_printischeck[$i]==1){
       	  	$ischeckfield = "checked";
       	  }else{
       	    $ischeckfield = "";
       	  }
       	  $this->t->set_var(array("printfieldvalue"    => $this->browse_printfieldval[$i],
      			                      "printfieldname"     => $this->browse_printfieldname[$i],
      			                      "printischeckfield"  => $ischeckfield
     		      	            )) ;
            		      	      
          $this->t->parse("printfields", "PRINTFIELD", true);
       }
       //-----------------------------------------------
       $this->t->pparse("out", "browse");    # ������ҳ��
   }
   //=============================================
   function makequery($isall) {	// �ó���ǰ��sql���
      if($isall=="all"){	//���м�¼
         $query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby ;
      }else{		// ��ǰҳ��¼
         if($this->browse_rowfrom<0){
         	   $this->browse_rowfrom=0;
         }
         $query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby." ".$this->browse_queryorder." limit ".$this->browse_rowfrom." , ".$this->browse_rows;
      }
      return $query ;
   }
   //============================================
   function pagination($nows,$all) {	// �ó�ҳ������ $nows��ǰҳ ,$all��ҳ��
	    $show = "" ;
	    $showend = "" ;

	    if($nows > 1){
	    	 $show .= "<input type='button' name='first_page' class='first_page' onclick='javascript:viewPage(1)'>\n" ;
	    }else{
	    	 $show .= "&nbsp;<input type='button' name='first_page_gray' class='first_page_gray' >\n" ;
	    }
	    if($nows < $all){
	    	$showend .= "<input type='button' name='last_page' class='last_page' onclick='javascript:viewPage($all)'>\n";
	    }else{
	    	$showend .= "&nbsp;<input type='button' name='last_page_gray' class='last_page_gray' >\n" ;
	    }
      
      $k=$nows+1;
      $j=$nows-1;
      if($j>0){
      	$show .= "<input type='button' name='front_page' class='front_page' onclick='javascript:viewPage($j)'>\n";
      }else{
      	$show .= "&nbsp;<input type='button' name='front_page_gray' class='front_page_gray' >\n";
      }
      if($k<= $all) {
      	$show .= "<input type='button' name='next_page' class='next_page' onclick='javascript:viewPage($k)'>\n";
      }else{
      	$show .= "&nbsp;<input type='button' name='next_page_gray' class='next_page_gray' >\n";
      }
	    return $show.$showend ;
    }
    //====================================================
    function showtitle($n,$m) {	// ���ɱ���
    	 if($this->browse_operationflag==0){//��ʶ�������Ƿ���ĩβ��
    	 	 if($this->browse_editplaceflag == 1){
  	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// ���ɲ���λ	
  	           $titlearr[] = array("title" => "�༭��","titlecss" => "listcelltitle","cols"=>"");
  	       }
  	     }
  	     for($i=0;$i<$n;$i++){
  		      $show = "<a class = title href=\"javascript:submit_order('".$this->browse_field[$i]."')\"> ".$this->fd[$i]->bwfd_title."</a>" ;
    		    $titlearr[] = array("title" => $show,
    		  		                   "titlecss" => $this->fd[$i]->bwfd_titlecss,
    		  		                   "cols"=>"");	
    	   }
    	   if($this->browse_editplaceflag == 0){
  	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// ���ɲ���λ	
  	           $m++ ;
  	       }
  	     }
  	     if($m > 0){
  	         $cols = "colspan='".$m."'";
	           $titlearr[] = array("title" => "������","titlecss" => "listcelltitle","cols"=>$cols);
	       }
	     }else{  //��ʶ�������Ƿ���ͷ��
	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// ���ɲ���λ	
  	         $m++ ;
  	     }
  	     if($m > 0){
  	         $cols = "colspan='".$m."'";
	           $titlearr[] = array("title" => "������","titlecss" => "listcelltitle","cols"=>$cols);
	       }
	       for($i=0;$i<$n;$i++){
  		      $show = "<a class = title href=\"javascript:submit_order('".$this->browse_field[$i]."')\"> ".$this->fd[$i]->bwfd_title."</a>" ;
    		    $titlearr[] = array("title" => $show,
    		  		                   "titlecss" => $this->fd[$i]->bwfd_titlecss,
    		  		                   "cols"=>"");	
    	   }
	     }
 	     return $titlearr ;
     }
    //===========================================================
    function showline($n,$key) {		// ����������
    	
    	/*if((empty($this->browse_edit) or $this->browse_editqx!=1) and (empty($this->browse_delsql) or $this->browse_delqx!=1)){
          $showcountforlist = "display:none";
      }*/
	    
   	   for($i=0;$i<$n;$i++){
    		  $fd_show = $this->fd[$i]->makeshow() ;
    		  $fd_align = $this->fd[$i]->bwfd_align ;
    		  $fd_nowrap = $this->fd[$i]->bwfd_nowrap ;
    		  $fd_link = $this->fd[$i]->bwfd_link;
    		  $fd_isneetkey = $this->fd[$i]->bwfd_isneetkey;
    		  if($fd_isneetkey==1){
    		  	$fd_link = $fd_link.$key;
    		  }
    		  if(!empty($fd_link)){
    		      $show .= "<TD class=listcellrow ".$fd_nowrap." align=$fd_align height='23'><a href='$fd_link' >$fd_show</a></TD> ";
    		  }else{
    		  	  $show .= "<TD class=listcellrow ".$fd_nowrap." align=$fd_align height='23'>$fd_show</TD> ";
    		  }
    	 } 
  	   return $show." ";
    }
    //===================================
    //-----------2005-10-18------------------
    function showtrfield($bgcolor,$tr_k,$fdcount,$lkcount){
    	$fd_show = $this->fd[$tr_k]->makeshow() ;
    	$fd_align = $this->fd[$tr_k]->bwfd_align ;
    	$fd_nowrap = $this->fd[$tr_k]->bwfd_nowrap ;
    	$colspan = $fdcount+$lkcount;
    	$fd_title = $this->fd[$tr_k]->bwfd_title;
    	if(!empty($this->browse_edit ) and $this->browse_editqx==1){
    		$colspan = 1+$colspan;
    	}
    	
    	$show = "<TR class=listrow1 onmouseover=this.style.backgroundColor='#DAE2ED'; onmouseout=this.style.backgroundColor='".$bgcolor."' bgcolor=".$bgcolor.">";
    	$show .= "<TD class=listcellrow ".$fd_nowrap." align=".$fd_align."></TD>";
    	//$show .= "<TD class=listcellrow ".$fd_nowrap." align=".$fd_align." width=15 ><nobr>".$fd_title."</nobr></TD> ";
    	$show .= "<TD colspan=".$colspan." class=listcellrow ".$fd_nowrap." align=".$fd_align.">".$fd_title."��".$fd_show."</TD> ";
    	return $show."</TR>";
    }
    //-----------2005-10-18------------------
  //========================================
    function showlink($n,$key) {	// ������չ����
  	   $show = "";
   	   for($i=0;$i<$n;$i++){
    		   $lk_show = $this->lk[$i]->makelink() ;
    		   $show .= "<TD class=listcellrow noWrap align=center>".$lk_show."</TD>";
    	 }	
    	 return $show ;
    }
    function showeditlink($n,$key) {	// ������չ����
  	   $show = "";
  	   if(!empty($this->browse_edit) and $this->browse_editqx==1){			 	
		       $tmpshow = $this->makeedit($key);  // �ó�edit
		       $show .= "<TD class=listcellrow noWrap align=center>".$tmpshow."</TD>";
	     }
    	 return $show ;
    }
    //====================================
    // ÿ����ɫ
    function Trbgcolor($TrColor) {
      if ($TrColor=="#FFFFFF") {
          $TrColor="#EEF2FF";
      } else {
          $TrColor="#FFFFFF";
      }
      return($TrColor);
    }
    //==============================
    function makeedit($key) {	// ���ɱ༭����
  	  $edit = "" ;
  	  if(!empty($this->browse_edit)){
  	     $edit = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")>".$this->browse_editname."</a>" ;
  	  }
  	  return $edit;	 // 
    }
    //====================
    function makenew() {	// �����½�����
  	  return $this->browse_new;	// $this->browse_new ����ʾ�õ�
    }
    //==================================
	  function makealledit() {	// ���ɱ༭����
  	  return $this->browse_edit;	// $this->browse_edit ����ʾ�õ�
    }
    //==================================
	
    function makedel() {	// ����ɾ������
  	    return $this->browse_del;	// $this->browse_del ����ʾ�õ�
    }
    //===============================
    function dodelete(){	//  ɾ������.
  	   for($i=0;$i<count($this->browse_check);$i++){
      		$query = sprintf($this->browse_delsql,$this->browse_check[$i]);
      		$this->db->query($query);  //ɾ������ļ�¼
      		
      		for($k=0;$k<count($this->browse_relatingdelsql);$k++){
      			  $query = sprintf($this->browse_relatingdelsql[$k],$this->browse_check[$i]);
      		    $this->db->query($query);   //ɾ�����Ե����¼��صļ�¼
      		}
      		/*if(!empty($this->browse_delcontsql)){
      		    $query = sprintf($this->browse_delcontsql,$this->browse_check[$i]);
      		    $this->db->query($query);   //ɾ�����Ե����¼��صļ�¼
      	  }*/
       }
    }
    function docollect(){
        $this->db->query($this->browse_collectquery);
      	if($this->db->nf()){
      		$this->db->next_record();
      		$collectmoney = $this->db->f(collectmoney);
      		$collectmoney = number_format($collectmoney, 2, ".", ",");
      	}else{
      	  $collectmoney = 0;
        }
        $this->browse_collectdata = $this->browse_collectname.$collectmoney ;
     }
    //===============================
    function makeorder($n) {		// ��������Ҫ��
	      $show = "";
      	for($i=0;$i<$n;$i++){
   		     if($this->browse_field[$i] == $this->browse_order){ // ��ǰ����������
   			       $show = " order by ".$this->fd[$i]->getorder($this->browse_ordersn) ; // ֪ͨ���ֶ�
		       }
    	  } 
  	    $this->browse_queryorder = $show ;
     }
     //=======================================
    function makehidden(){   //��������ֵ
    	$hiddenvalue = "";
    	for($j=0;$j<count($this->browse_hidden);$j++){
    		$hiddenname = $this->browse_hidden[$j][0];
    		$keyfield = $this->browse_hidden[$j][1];
    		$hiddenvalue .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
    	}
    	return $hiddenvalue;
    }
     //========================================
     function showfind($whatdofind,$howdofind,$findwhat) {		// ���ɲ�ѯ����
	      for($i=0;$i<$this->browse_findnum;$i++){
		       $findtitle = "";	// ��ѯ����
		       $findhow = "";
		       for($j=0;$j<count($this->browse_find);$j++){
			        if($whatdofind[$i]==$this->browse_find[$j][1]){
    				      $findtitle .= "<option selected value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>" ;
    			    }else{
    				      $findtitle .= "<option value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>" ;
    			    }
    		   }
    		   if($howdofind[$i] == "like"){
    		      $findhow .= "<option selected value='like'>LIKE ����</option>";
    		   }else{
    			    $findhow .= "<option value='like'>LIKE ����</option>";
    		   }
 		       if($howdofind[$i] == "not like"){
    		      $findhow .= "<option selected value='not like'>NOIN ������</option>";
    		   }else{
    			    $findhow .= "<option value='not like'>NOIN ������</option>";
    		   }    		                        
 		       if($howdofind[$i] == "<>"){
    		      $findhow .= "<option selected value='<>'><>&nbsp;&nbsp;&nbsp;������</option>";
    		   }else{
    			    $findhow .= "<option value='<>'><>&nbsp;&nbsp;&nbsp;������</option>";
    		   }
		       if($howdofind[$i] == ">"){
    		      $findhow .= "<option selected value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;����</option>";
    		   }else{
    			    $findhow .= "<option value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;����</option>";
    		   }
		       if($howdofind[$i] == ">="){
    		      $findhow .= "<option selected value='>='>>=&nbsp;&nbsp;&nbsp;���ڵ���</option>";
    		   }else{
    			    $findhow .= "<option value='>='>>=&nbsp;&nbsp;&nbsp;���ڵ���</option>";
    		   }
		       if($howdofind[$i] == "="){
    		      $findhow .= "<option selected value='='>=&nbsp;&nbsp;&nbsp;&nbsp;����</option>";
    		   }else{
    			    $findhow .= "<option value='='>=&nbsp;&nbsp;&nbsp;&nbsp;����</option>";
    		   }                        
		       if($howdofind[$i] == "<="){
    		      $findhow .= "<option selected value='<='><=&nbsp;&nbsp;&nbsp;С�ڵ���</option>";
    		   }else{
    			    $findhow .= "<option value='<='><=&nbsp;&nbsp;&nbsp;С�ڵ���</option>";
    		   }                         
		       if($howdofind[$i] == "<"){
    		      $findhow .= "<option selected value='<'><&nbsp;&nbsp;&nbsp;&nbsp;С��</option>";
    		   }else{
    			    $findhow .= "<option value='<'><&nbsp;&nbsp;&nbsp;&nbsp;С��</option>";
    		   }                         
 		                               
    		   $this->browse_showfind[$i][0] = $findtitle ;
    		   $this->browse_showfind[$i][1] = $findhow ;
    		   $this->browse_showfind[$i][2] = $findwhat[$i] ;
    	   } 
     }
     //=======================================
     function makewhere($whatdofind,$howdofind,$findwhat,$allcondition) {		// ���ɲ�ѯҪ��
      	$show = $this->browse_querywhere ;
      	$isquerywhere = $this->browse_querywhere ;
  	    $showsay = "";
  	    $whereflag=0;
  	    $contentflag=0;
  	    
       	for($i=0;$i<count($whatdofind);$i++){
       		
       		 $tmp = $this->makefindsql($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
       		
       		 if(!empty($isquerywhere) and $whereflag==0 and !empty($whatdofind[$i]) and !empty($tmp)){
  			       $show .= " and ( ";
  			       $whereflag = 1 ;
  			   }
  		     
  		     if(!empty($tmp)){
  			       $say = $this->makefindsay($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
  			       if(empty($show)){	// û�б������
 				           $show .= $tmp ;
 				           $showsay .= $say ;
 				           $contentflag = 1;
  			       }else{
  			       	  if($allcondition==1){
  			       	  	 if($contentflag==1){
	  			              $show .= " and ".$tmp;
	  			              $showsay .= " ���� ".$say ;
	  			           }else{
	  			           	  $show .= " ".$tmp;
	  			           	  $contentflag = 1;
	  			              $showsay .= " ".$say ;
	  			           }
	  			        }else{
	  			        	 if($contentflag==1){
	  			        	    $show .= " or ".$tmp;
	  			              $showsay .= " ���� ".$say ;
	  			           }else{
	  			           	  $show .= " ".$tmp;
	  			           	  $showsay .= " ".$say ;
	  			           	  $contentflag = 1 ;
	  			           }
	  			        }
  			       }
  			       
  		      }
	      }
	      if(!empty($isquerywhere) and $whereflag==1 ){
  			    $show .= " ) ";
  			}
  	    if(!empty($show)) $this->browse_querywhere = "where ".$show ;
  	    if(!empty($showsay)){
  		        $this->browse_sayfind = "<TABLE  cellSpacing=0 cellPadding=0 width='98%' align=center border=0><TBODY>" ;
  		        $this->browse_sayfind .= "<TR vAlign=center><TD  class=listtai2 align=left>";
		          $this->browse_sayfind .= "��ǰ��ѯ����:".$showsay ;
    		      $this->browse_sayfind .= "</TD></TR></TBODY></TABLE>" ;
	      }
      }
      //===============================================
      function makefindsql($whatdofind,$howdofind,$findwhat){
  	     $show = "";
  	     $findwhat =strval($findwhat);
  	     if((!empty($whatdofind))&&(!empty($howdofind))&&(!empty($findwhat) or $findwhat==0)) {
  	     	   if($howdofind=="like" or $howdofind=="not like"){
  		          $show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
  		       }else{
  		       	  $show = $whatdofind." ".$howdofind." '".$findwhat."'";
  		       }
  	     }
  	     
  	     return $show ;
      }
      //===============================================
      function makefindsay($whatdofind,$howdofind,$findwhat){	// ��ʾ��ǰ����
   	     for($i=0;$i<count($this->browse_find);$i++){
   		      if($this->browse_find[$i][1] == $whatdofind){
   			        $show = $this->browse_find[$i][0] ;
    			      switch ($howdofind){		
        			     case ">":
	   				         $show .= "&nbsp;����";
	  			        	 break;
        			     case ">=":
	   				         $show .= "&nbsp;���ڵ���";
	  			 	         break;  
        			     case "=":
	   				         $show .= "&nbsp;����";
	  			 	         break;  
        			     case "<=":
	   				         $show .= "&nbsp;С�ڵ���";
	  			 	         break;  
        			     case "<":
	   				         $show .= "&nbsp;С��";
	  			 	         break;  
        			     case "like":
	   				         $show .= "&nbsp;����";
	  			 	         break;
        			     case "not like":
	   				         $show .= "&nbsp;������";
	  			 	         break;	  			 			 		  			 		  			 		  			 	
        		     	 case "<>":
	   				         $show .= "&nbsp;������";
	  			 	         break;	  			 	             
				           default:
    	   			       break;
    		   	    }
    			   $show .= $findwhat." ";
   		      }	
   	    }
  	    return $show ;
     }
     //����ѡ��˵��ĺ���
     function makeselectfile($arritem,$hadselected,$arry){ 
     	  $x .= "<option value=''>��ѡ��</option>"  ;	
        for($i=0;$i<count($arritem);$i++){
           if ($hadselected ==  $arry[$i]) {
       	       $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
           }else{
       	       $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
           }
        } 
        return $x ; 
     }
}


//����������������������������������������������������������������������������������������
class browsefield {
   var $classname = "browsefield";
   var $bwfd_fdname ;	// ���ݿ����ֶ�����
   var $bwfd_title ;	// �ֶα���
   var $bwfd_titlecss = "listcelltitle";	// ����css��
   var $bwfd_need = array();	// ������ֵ
   var $bwfd_align = "left";	// �ֶζ���
   var $bwfd_fdcss = "fd";	// �ֶ�css��
   var $bwfd_order = "";	// �ֶ������sql
   var $bwfd_nowrap = "noWrap" ; // �ɷ����� 
   var $bwfd_format = "" ;    // ���ݸ�ʽ  
   var $bwfd_value ;	// ֵ
   var $bwfd_link;   //���ݵĳ�������
   var $bwfd_isneetkey ; //�Ƿ���Ҫ�ؼ��֣�0��ʾ����Ҫ��1��ʾ��Ҫ 
   
   var $bwfd_otherfieldname = "";// �ֶ�����
   var $bwfd_otherfieldval = "" ;	// �ֶ�ֵ
   
  function makeshow() {	// ��ֵתΪ��ʾֵ
      $showvalue = $this->bwfd_value ;
      if($this->bwfd_format == "num"){
  		  $showvalue = number_format($showvalue, 2, ".", ",");
  		  $this->bwfd_align  = "right";
      }
      return $showvalue ;
  }
  function getorder($done) {	// ��������
  	if($done=="asc"){
	  	$this->bwfd_title .= " <font face=webdings>5</font>";
  	}else{
  		$this->bwfd_title .= " <font face=webdings>6</font>";
	  }
  	return $this->bwfd_fdname." ".$done." " ;
  }
}

//����������������������������������������������������������������������������������������
class browselink {
   var $classname = "browselink";
   var $bwlk_fdname = array();	// �������ݿ����ֶ�����
   var $bwlk_title ;	// link����
   var $bwlk_linkcss = "listcelltitle";	// link����css��
   var $bwlk_text = "";	// link˵��
   var $bwlk_prgname = "";	// �ֶ����ӳ���
   var $bwlk_is_blank = 0;	// �Ƿ񵯳��´��� ------------2005-1-1-------------------
   
   var $bwlk_fdvalue ;	// �ֶ�ֵ

  function makeprg() {	// �������ӳ���
    if(!empty($this->bwlk_fdname[0][1])){
	    $prg = $this->bwlk_prgname.$this->bwlk_fdname[0][1];
    }else{
    	$prg = "" ;
    }
    return $prg;
  }
 
  function makelink() {	// ��������
    $linkurl = $this->makeprg();
    if(!empty($linkurl)){
    	//------------2005-1-1-------------------
    	if($this->bwlk_is_blank==0){
    	    $link  = "<a href=javascript:linkurl(\"".$linkurl."\")>".$this->bwlk_title."</a>";
      }else{
          $link  = "<a href=javascript:windowopen(\"".$linkurl."\")>".$this->bwlk_title."</a>";
      }
    }else{
    	$link = "" ;
    }
    return $link;
  }
}

session_register("session_analyseorganid"); 
session_register("session_begindate"); 
session_register("session_enddate"); 
if(empty($session_analyseorganid)){
	if(empty($begindate)){
	  $begindate = $session_begindate;
	  $enddate = $session_enddate;
	  $analyseorganid = "";
  }
}else{
  if(empty($analyseorganid)){
    $analyseorganid = $session_analyseorganid;
  }
}
$session_analyseorganid = $analyseorganid;
$session_begindate = $begindate;
$session_enddate = $enddate;

session_register("find_whatdofind"); 
session_register("find_howdofind");  
session_register("find_findwhat"); 
if(!empty($whatdofind[0]) and !empty($howdofind[0]) and !empty($findwhat[0])){
	  //$find_whatdofind = $whatdofind;
	  //$find_howdofind  = $howdofind;
	 // $find_findwhat   = $findwhat;
}else{
	  $whatdofind = $find_whatdofind;
	  $howdofind = $find_howdofind;
	  $findwhat = $find_findwhat ;
}
 session_unregister("selecteditvalue");
 
 
 
 unset($selecteditvalue);
 
?>