<?php
/*
 findbrowseҳ����
 */ 
class findbrowse {
   var $classname = "findbrowse";
   var $database_class = "DB_test";
   var $template_class = "Template";
   
   var $prgname = "" ;  // ��������λ��

   var $brow_rows = 15;  //ÿҳ��ʾ�ļ�¼����
   var $brow_pages ;     //�ܹ�ҳ��
   var $brow_nowpage ;   //��ǰҳ��
   var $brow_rowfrom ;   //��ҳ��ʾ�ļ�¼�ӵڼ���
   var $brow_rowto ;     //��ҳ��ʾ�ļ�¼���ڼ���
   var $brow_total ;     //�ܵļ�¼��
   var $brow_order = "";     //��ǰ��ѯ˳��
   var $brow_orderud = "" ; // ˳�������
   var $brow_showpages = 10;     //ͬʱ��ʾ��ҳ��
   var $brow_otherpagename = "";  //��ȥ����ҳ�İ�Ŧ��
   var $brow_otherpageurl = "";   //��ȥ����ҳ��url

   var $t ;
   var $db ;
   var $fd ;
   var $debug = 0 ; // �����0����ʾ��䣬1����ʾ ������
   var $cansearch = 1 ; // �����0����ʾ������1����ʾ
   
   var $brow_changeurl ="";
    
   var $brow_query = "";  /* ��ȡ������Դ ��"select title,auth from book" */
   var $brow_queryselect = "";
   var $brow_querywhere = "";
   var $brow_queryorder = "";
   var $brow_querygroupby  = "";
   var $brow_field = array();	// ��ʾ���ֶ�
   
   var $brow_ischildquery = "" ;
   var $brow_ischildfiled = "" ;
   
   var $brow_key ;  //   ��¼Ψһ��ֵ
   var $brow_check = array();   // ��ѡ��ļ�¼
   
   var $brow_getvalue = array() ;  // ��Ҫ���ص���ֵ
   var $brow_lfh = "@@@";	// �ָ��ķ���
   	          
   var $brow_find = array() ; // ��ѯ�б�
   var $brow_sayfind = "" ; // ��ǰ��ѯ����˵��
   var $brow_showfind = array() ; // ��ѯ��ʾ��
   
   var $brow_more = 0 ;  // �����0����ʾ������1����ʾ 

   var $brow_tmpcatlog = "../include/" ;  //ģ���ļ�·��
   var $brow_tmpname = "levelfindbrowse.ihtml" ;  //ģ���ļ���   
   
   var $brow_bakurl ;  //��������   
   var $brow_findnum = 5 ;  // ��ѯ����
   var $brow_skin = "" ;
   
   var $brow_jumpurlname = array() ;  // ��ת����
   var $brow_jumpurl = array() ;  // ��ת��ַ
   
  function main($now,$act,$pagerows,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) {
    $this->brow_nowpage = $now ;
    $this->brow_order = $order ; 
    $this->brow_orderud = $upordown ;  
    $this->brow_check = $check ;  
    $this->brow_keyword = $keyword ;  
    $this->brow_findtype = $findtype ;  
    $this->brow_rows = $pagerows ;

    //  �ó�������������   
    $urlcan = "?now=".$now."&pagerows=".$pagerows."&order=".$order."&upordown=".$upordown ;
    $tempurl = rawurlencode($urlcan);
        
    $name = $this->database_class;
    $this->db = new $name;    
    $this->db1 = new $name;  
    
    //�г���ʾ��ת�ĳ���2008-4-15����
    $str_jumpurl = "";
    for($i=0;$i<count($this->brow_jumpurlname);$i++){
       if(!empty($this->brow_jumpurlname)){
       	 $str_jumpurl .= "&nbsp;&nbsp;<a href='".$this->brow_jumpurl[$i]."'>".$this->brow_jumpurlname[$i]."</a>";
       }
    } 
    
    $prglist = "";	// �ó�ͷ��
    for($i=0;$i<count($this->prgnoware);$i++){
       if (empty($this->prgnowareurl[$i])){
           $this->prglist .= "&gt;&gt;&nbsp;".$this->prgnoware[$i];
       }else{
           $this->prglist .= "&gt;&gt;<A href=\"".$this->prgnowareurl[$i]."\">&nbsp;".$this->prgnoware[$i]."</A>\n";
       }
    } 

    if($act=="delete") $this->donedelete();

    // ���ֶ������
    $fdcount = count($this->brow_field) ;
    for($i=0;$i<$fdcount;$i++){
     	$name = $this->brow_field[$i];
     	$this->fd[$i] = new $name;
    }

    if(empty($this->brow_nowpage)){		//��ǰҳ
    	 $this->brow_nowpage = 1; 	 // �����һ�Σ�����ҳ
    }

    $this->makewhere($whatdofind,$howdofind,$findwhat);	// �ó���ѯ����

    $query = $this->makequery("all");	// ������
    $this->db->query($query);
    $this->brow_total = $this->db->nf();	//�ܵļ�¼��
    if($this->brow_rows == 0) $this->brow_rows = 15 ;
    $this->brow_pages = ceil($this->brow_total/$this->brow_rows); //�ܹ�ҳ��
    
    if($this->brow_nowpage > $this->brow_pages) $this->brow_nowpage = $this->brow_pages ; // if��ǰҳ>�ܹ�ҳ��,��ǰҳ=�ܹ�ҳ��
    
    $this->brow_rowfrom = ($this->brow_nowpage-1) * $this->brow_rows ; //��ҳ��ʾ�ļ�¼�ӵڼ���
    
    // �ó���ѡҳ��
    $showviewpage = $this->makepages($this->brow_nowpage,$this->brow_pages,$this->brow_showpages) ;
       
    // �ó�����Ҫ��
    $this->makeorder($fdcount) ;

       
     // �ó���ҳ��¼
    $query = $this->makequery("now");	// ������
    $this->db->query($query);
    
    $titlearr = $this->showtitle($fdcount,0);    // �ֶα���
    $ColorStr = "";   
    
    $rowcount=0;  //**********************
    while($this->db->next_record()) {
    	for($i=0;$i<$fdcount;$i++){		// ���ֶ���ֵ
    		$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    		for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			$this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		}    		
    	}
    	
    	
    	
    	$key = "" ;
    	for($i=0;$i<count($this->brow_getvalue);$i++){		// ������ֵ
    		$key .= $this->db->f($this->brow_getvalue[$i]).$this->brow_lfh ; // �ó�����ֵ
    	}
    	
    	/*if(!empty($this->brow_ischildfiled)){   //�����Ϊ�վͲ�ѯ�Ƿ��������
    		$childkey = $this->db->f($this->brow_ischildfiled);
    		$ischildvalue = $this->ishavechile($childkey);     //�����ж��Ƿ������ࡣ
    		$key .= $rowcount.$this->brow_lfh;
    		$key .= $childkey.$this->brow_lfh;
    	}*/
    	
    	$showline = $this->showline($fdcount,$key);	// �ó�����
  	  $ColorStr = $this->TrBackColor($ColorStr) ;
  	
	    $linearr[] = array("bgcolor"      => $ColorStr,
	                       "xi"           => $showline,
	                       "rowcount"     => $rowcount);
	    $rowcount++;
    }
    
    if(empty($this->brow_otherpagename)){
    	$isshowotherpage = "none";
    }else{
      $isshowotherpage = "";
    }
    // �ó�����
      
     $this->brow_rowto = $this->brow_rowfrom + $this->db->nf() ;  //��ҳ��ʾ�ļ�¼���ڼ���
       
    $this->showfind($whatdofind,$howdofind,$findwhat) ; // ���ɲ�ѯ
    
    $this->t = new Template($this->brow_tmpcatlog, "keep");    
    $this->t->set_file("findbrowse",$this->brow_tmpname); 

    $this->t->set_var("prgname",$this->prgname);

    $this->t->set_var("findsay",$this->brow_sayfind);
    
    $this->t->set_var("allrowcount",$rowcount);
    
    $this->t->set_var("brow_changeurl",$this->brow_changeurl);  //ת����ַ
    
    $this->t->set_var("isshowotherpage",$isshowotherpage);  //�Ƿ���ʾת�밴ť
    $this->t->set_var("otherpagename",$this->brow_otherpagename);  //ת������ҳ��İ�Ŧ����
    $this->t->set_var("brow_otherpageurl",$this->brow_otherpageurl);  //ת������ҳ������ӵ�ַ

    $this->t->set_var("total",$this->brow_total);
    $this->t->set_var("pages",$this->brow_pages);
    $this->t->set_var("nowpage",$this->brow_nowpage);    
    $this->t->set_var("showviewpage",$showviewpage);
    $this->t->set_var("rows",$this->brow_rows);
    $this->t->set_var("order",$this->brow_order);
    $this->t->set_var("upordown",$this->brow_orderud);

    $this->t->set_var("recodeform",$this->brow_rowfrom);
    $this->t->set_var("recodeto",$this->brow_rowto);
    
    $this->t->set_var("jumpurl",$str_jumpurl);
    
    $this->t->set_var("url",$tempurl);
    $this->t->set_var("skin",$this->brow_skin);	// Ƥ��

    $this->t->set_block("findbrowse", "TITLEBK", "titlebks");  // �б���
    for($i=0;$i<count($titlearr);$i++){
      $this->t->set_var(array("title" => $titlearr[$i][title],
     		      	      "titlecss" => $titlearr[$i][titlecss],
     		      	      "cols" => $titlearr[$i][cols]
     		      	      )) ;
      $this->t->parse("titlebks", "TITLEBK", true);
    }

    $this->t->set_block("findbrowse", "DATABK", "databks");  // ������
    $n = count($linearr);
    if($n>0){
        for($i=0;$i<$n;$i++){
          $this->t->set_var(array("bgcolor" => $linearr[$i][bgcolor],
                          "rowcount"        => $linearr[$i][rowcount],
         		      	      "xi"              => $linearr[$i][xi],
         		      	      )) ;
          $this->t->parse("databks", "DATABK", true);
        }
     }else{
          $this->t->set_var(array("bgcolor"       => "" ,
         		      	              "xi"            => "" ,
         		      	              "rowcount"      => "" ,
         		      	      )) ;
          $this->t->parse("databks", "DATABK", true);
     }

    $this->t->set_block("findbrowse", "FINDBK", "findbks");  // ������
    for($i=0;$i<$this->brow_findnum;$i++){
      $this->t->set_var(array("whatdofind" => $this->brow_showfind[$i][0],
      			      "howdofind" => $this->brow_showfind[$i][1],
      			      "findwhat" => $this->brow_showfind[$i][2]
     		      	      )) ;
      $this->t->parse("findbks", "FINDBK", true);
    }

    $this->t->pparse("out", "findbrowse");    # ������ҳ��
  }
   
  function ishavechile($fatherno){  //��ѯ�Ƿ����ӿ�Ŀ
  	$query = sprintf($this->brow_ischildquery,$fatherno);
    $this->db1->query($query);  
    if($this->db1->nf()){
    	$returnflag = 0;
    }else{
      $returnflag = 1;
    }
  	return $returnflag;
  } 

  function makemore() {	// ����������
  }

  function showtitle($n,$m) {	// ���ɱ���
  	for($i=0;$i<$n;$i++){
  		$show = "<a class = title href=\"javascript:submit_order('".$this->brow_field[$i]."')\">".$this->fd[$i]->bwfd_title."</a>" ;
    		$titlearr[] = array("title" => $show,
    				    "titlecss" => $this->fd[$i]->bwfd_titlecss,
    				    "cols"=>"");	
    	}
  	if(!empty($this->brow_edit)){	// ���ɲ���λ	
  	     $m++ ;
  	}
  	if($m > 0){
  	     $cols = "colspan='".$m."'";
	     $titlearr[] = array("title" => "����","titlecss" => "listcelltitle","cols"=>$cols);
	}
 	return $titlearr ;
  }

  function showline($n,$key) {		// ����������
  

	$show = "<TD class=listcellrow style='WIDTH: 10px'><input type='radio' name='radiocheck' value='$key' ></td>";
   	for($i=0;$i<$n;$i++){
    		$fd_show = $this->fd[$i]->makeshow() ;
    		$fd_align = $this->fd[$i]->bwfd_align ;
    		$fd_nowrap = $this->fd[$i]->bwfd_nowrap ;
    		$show .= "<TD class=listcellrow ".$fd_nowrap." align=$fd_align>$fd_show</TD>";
    	} 
  	return $show;
  }

  function showfind($whatdofind,$howdofind,$findwhat) {		// ���ɲ�ѯ����
	for($i=0;$i<$this->brow_findnum;$i++){
		$findtitle = "";	// ��ѯ����
		$findhow = "";
		for($j=0;$j<count($this->brow_find);$j++){
			if($whatdofind[$i]==$this->brow_find[$j][1]){
    				$findtitle .= "<option selected value='".$this->brow_find[$j][1]."'>".$this->brow_find[$j][0]."</option>" ;
    			}else{
    				$findtitle .= "<option value='".$this->brow_find[$j][1]."'>".$this->brow_find[$j][0]."</option>" ;
    			}
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
    		$this->brow_showfind[$i][0] = $findtitle ;
    		$this->brow_showfind[$i][1] = $findhow ;
    		$this->brow_showfind[$i][2] = $findwhat[$i] ;
    	} 
  }

  function makepages($n,$all,$num) {	// �ó�ҳ������ $n��ǰҳ ,$all��ҳ��,$num ����ҳ�� ����$numֻ��Ϊ10
	$show = "" ;
	$showend = "" ;
  	if($all<=$num) $num = $all ;	// �����ҳ�����ڿ���ҳ��

	if($n > 1) $show .= "<a href='javascript:viewPage(1)' title=��ҳ><font face=webdings>9</font></a>\n" ;
	if($n > $num) $show .= "<a href='javascript:viewPage(".($n-$num).")' title=��".$num."ҳ><font face=webdings>7</font></a>\n" ;

	if($n < $all-$num) $showend .= "<a href='javascript:viewPage(".($n+$num).")' title=��".$num."ҳ><font face=webdings>8</font></a>\n" ;
	if($n < $all) $showend .= "<a href='javascript:viewPage($all)' title=βҳ><font face=webdings>:</font></a>\n" ;


	if($n <= 6){			// ǰ5ҳ����
		for($i=1;$i<=$num;$i++){
			if($i==$n){
			   $show .= "<font color=#ff0000>$i</font>\n";
			}else{
		           $show .= "<a href='javascript:viewPage($i)'>$i</a>\n";
			}
		}
		return $show.$showend ;
	}
	if($n > ($all - 4)){			// ��5ҳ����
		for($i=max($all-9,1);$i<=$all;$i++){
			if($i==$n){
			   $show .= "<font color=#ff0000>$i</font>\n";
			}else{
		           $show .= "<a href='javascript:viewPage($i)'>$i</a>\n";
			}
		}
		return $show.$showend ;
	}

        for($i=(max(($n-5),1));$i<=($n+4);$i++){
        	if($i==$n){
        	   $show .= "<font color=#ff0000>$i</font>\n";
        	}else{
                   $show .= "<a href='javascript:viewPage($i)'>$i</a>\n";
        	}
        }
	return $show.$showend ;
  }

  function makeorder($n) {		// ��������Ҫ��
	$show = "";
   	for($i=0;$i<$n;$i++){
   		if($this->brow_field[$i] == $this->brow_order){ // ��ǰ����������
   			$show = " order by ".$this->fd[$i]->getorder($this->brow_orderud) ; // ֪ͨ���ֶ�
		}
    	} 
  	$this->brow_queryorder = $show ;
  }
  
  function makewhere($whatdofind,$howdofind,$findwhat) {		// ���ɲ�ѯҪ��
  	$show = $this->brow_querywhere ;
  	$showsay = "";
  	for($i=0;$i<count($whatdofind);$i++){
  		$tmp = $this->makefindsql($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
  		if(!empty($tmp)){
  			$say = $this->makefindsay($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
  			if(empty($show)){	// û�б������
 				$show .= $tmp ;
 				$showsay .= $say ;
  			}else{
	  			$show .= " and ".$tmp;
	  			$showsay .= " ���� ".$say ;
  			}
  		}
	}
  	if(!empty($show)) $this->brow_querywhere = "where ".$show ;
  	if(!empty($showsay)){
  		$this->brow_sayfind = "<TABLE  cellSpacing=0 cellPadding=0 width='98%' align=center border=0><TBODY>" ;
  		$this->brow_sayfind .= "<TR vAlign=center><TD  class=listtai2 align=left>";
		$this->brow_sayfind .= "��ǰ��ѯ����:".$showsay ;
    		$this->brow_sayfind .= "</TD></TR></TBODY></TABLE>" ;
	}
  }

  function makefindsql($whatdofind,$howdofind,$findwhat){
  	$show = "";
  	if((!empty($whatdofind))&&(!empty($howdofind))&&(!empty($findwhat))) {
  		switch ($howdofind){		
      			case ">":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;
      			case ">=":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;  
      			case "=":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;  
      			case "<=":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;  
      			case "<":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;  
      			case "like":
	   			$show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
	  		 	break;
      			case "not like":
	   			$show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
	  		 	break;	  			 			 		  			 		  			 		  			 	
      			case "<>":
	   			$show = $whatdofind." ".$howdofind." '".$findwhat."'";
	  		 	break;	  			 	             
			   default:
    	 			break;
      }
  	}
  	return $show ;
  }

  function makefindsay($whatdofind,$howdofind,$findwhat){	// ��ʾ��ǰ����
   	for($i=0;$i<count($this->brow_find);$i++){
   		if($this->brow_find[$i][1] == $whatdofind){
   			$show = $this->brow_find[$i][0] ;
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

  function makequery($done) {	// �ó���ǰ��sql���
    if(!empty($this->this->brow_querywhere)) $this->brow_querygroupby = "group by ".$this->brow_querygroupby;
    if($done=="all"){	//���м�¼
        $query = $this->brow_queryselect." ".$this->brow_querywhere." ".$this->brow_querygroupby ;
    }else{		// ��ǰҳ��¼
        if($this->brow_rowfrom<0){
         	   $this->brow_rowfrom=0;
        }
        $query = $this->brow_queryselect." ".$this->brow_querywhere." ".$this->brow_querygroupby." ".$this->brow_queryorder." limit ".$this->brow_rowfrom." , ".$this->brow_rows;
    }
    return $query ;
  } 

  // ÿ����ɫ
  function TrBackColor($ColorStr) {
    if ($ColorStr=="#F1F1F1") {
      $ColorStr="#FFFFFF";
    } else {
      $ColorStr="#F1F1F1";
    }
    return($ColorStr);
  }   
}


// ----------------------------------------------
class findbrowsefield {
   var $classname = "findbrowsefield";
   var $bwfd_fdname ;	// ���ݿ����ֶ�����
   var $bwfd_title ;	// �ֶα���
   var $bwfd_titlecss = "listcelltitle";	// ����css��
   var $bwfd_need = array();	// ������ֵ
   var $bwfd_text = "" ;	// �ֶ�����˵��
   var $bwfd_align = "left";	// �ֶζ���
   var $bwfd_fdcss = "fd";	// �ֶ�css��
   var $bwfd_order = "";	// �ֶ������sql
   var $bwfd_nowrap = "noWrap" ; // �ɷ����� 
   
   var $bwfd_value ;	// ֵ
   
  function makeshow() {	// ��ֵתΪ��ʾֵ
      $showtxt = $this->maketext() ;
      $showvalue = $this->bwfd_value ;
      if((empty($showtxt))&&(empty($showlink))){
      	 $show = $this->bwfd_value;
      }else{
	 if(empty($showlink)) $showlink = "#";
	 $show = "<a href='$showlink' title=\"$showtxt\">$showvalue</a>";
      }
      return $show ;
  }

  function getorder($done) {	// ��������
  	if($done=="asc"){
	  	$this->bwfd_title .= " <font face=webdings>5</font>";
  	}else{
  		$this->bwfd_title .= " <font face=webdings>6</font>";
	}
  	return $this->bwfd_fdname." ".$done." " ;
  }
  
  function maketext() {	// ����˵��
  	return "";
  }  
  
  
}
?>