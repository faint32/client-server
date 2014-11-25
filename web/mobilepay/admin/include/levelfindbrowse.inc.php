<?php
/*
 findbrowse页面类
 */ 
class findbrowse {
   var $classname = "findbrowse";
   var $database_class = "DB_test";
   var $template_class = "Template";
   
   var $prgname = "" ;  // 功能现在位置

   var $brow_rows = 15;  //每页显示的记录条数
   var $brow_pages ;     //总共页数
   var $brow_nowpage ;   //当前页数
   var $brow_rowfrom ;   //本页显示的记录从第几条
   var $brow_rowto ;     //本页显示的记录到第几条
   var $brow_total ;     //总的记录数
   var $brow_order = "";     //当前查询顺序
   var $brow_orderud = "" ; // 顺序或逆序
   var $brow_showpages = 10;     //同时显示的页数
   var $brow_otherpagename = "";  //进去另外页的按纽名
   var $brow_otherpageurl = "";   //进去另外页的url

   var $t ;
   var $db ;
   var $fd ;
   var $debug = 0 ; // 如果是0则不显示语句，1则显示 调试用
   var $cansearch = 1 ; // 如果是0则不显示搜索，1则显示
   
   var $brow_changeurl ="";
    
   var $brow_query = "";  /* 所取数据来源 ："select title,auth from book" */
   var $brow_queryselect = "";
   var $brow_querywhere = "";
   var $brow_queryorder = "";
   var $brow_querygroupby  = "";
   var $brow_field = array();	// 显示的字段
   
   var $brow_ischildquery = "" ;
   var $brow_ischildfiled = "" ;
   
   var $brow_key ;  //   记录唯一键值
   var $brow_check = array();   // 所选择的记录
   
   var $brow_getvalue = array() ;  // 所要返回的数值
   var $brow_lfh = "@@@";	// 分隔的符号
   	          
   var $brow_find = array() ; // 查询列表
   var $brow_sayfind = "" ; // 当前查询条件说明
   var $brow_showfind = array() ; // 查询显示用
   
   var $brow_more = 0 ;  // 如果是0则不显示下拉框，1则显示 

   var $brow_tmpcatlog = "../include/" ;  //模板文件路径
   var $brow_tmpname = "levelfindbrowse.ihtml" ;  //模板文件名   
   
   var $brow_bakurl ;  //返回链接   
   var $brow_findnum = 5 ;  // 查询列数
   var $brow_skin = "" ;
   
   var $brow_jumpurlname = array() ;  // 跳转名称
   var $brow_jumpurl = array() ;  // 跳转地址
   
  function main($now,$act,$pagerows,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) {
    $this->brow_nowpage = $now ;
    $this->brow_order = $order ; 
    $this->brow_orderud = $upordown ;  
    $this->brow_check = $check ;  
    $this->brow_keyword = $keyword ;  
    $this->brow_findtype = $findtype ;  
    $this->brow_rows = $pagerows ;

    //  得出返回链接属性   
    $urlcan = "?now=".$now."&pagerows=".$pagerows."&order=".$order."&upordown=".$upordown ;
    $tempurl = rawurlencode($urlcan);
        
    $name = $this->database_class;
    $this->db = new $name;    
    $this->db1 = new $name;  
    
    //列出显示跳转的出来2008-4-15加上
    $str_jumpurl = "";
    for($i=0;$i<count($this->brow_jumpurlname);$i++){
       if(!empty($this->brow_jumpurlname)){
       	 $str_jumpurl .= "&nbsp;&nbsp;<a href='".$this->brow_jumpurl[$i]."'>".$this->brow_jumpurlname[$i]."</a>";
       }
    } 
    
    $prglist = "";	// 得出头部
    for($i=0;$i<count($this->prgnoware);$i++){
       if (empty($this->prgnowareurl[$i])){
           $this->prglist .= "&gt;&gt;&nbsp;".$this->prgnoware[$i];
       }else{
           $this->prglist .= "&gt;&gt;<A href=\"".$this->prgnowareurl[$i]."\">&nbsp;".$this->prgnoware[$i]."</A>\n";
       }
    } 

    if($act=="delete") $this->donedelete();

    // 将字段类产生
    $fdcount = count($this->brow_field) ;
    for($i=0;$i<$fdcount;$i++){
     	$name = $this->brow_field[$i];
     	$this->fd[$i] = new $name;
    }

    if(empty($this->brow_nowpage)){		//当前页
    	 $this->brow_nowpage = 1; 	 // 如果第一次，到首页
    }

    $this->makewhere($whatdofind,$howdofind,$findwhat);	// 得出查询条件

    $query = $this->makequery("all");	// 总数据
    $this->db->query($query);
    $this->brow_total = $this->db->nf();	//总的记录数
    if($this->brow_rows == 0) $this->brow_rows = 15 ;
    $this->brow_pages = ceil($this->brow_total/$this->brow_rows); //总共页数
    
    if($this->brow_nowpage > $this->brow_pages) $this->brow_nowpage = $this->brow_pages ; // if当前页>总共页数,当前页=总共页数
    
    $this->brow_rowfrom = ($this->brow_nowpage-1) * $this->brow_rows ; //本页显示的记录从第几条
    
    // 得出可选页次
    $showviewpage = $this->makepages($this->brow_nowpage,$this->brow_pages,$this->brow_showpages) ;
       
    // 得出排序要求
    $this->makeorder($fdcount) ;

       
     // 得出本页记录
    $query = $this->makequery("now");	// 总数据
    $this->db->query($query);
    
    $titlearr = $this->showtitle($fdcount,0);    // 字段标题
    $ColorStr = "";   
    
    $rowcount=0;  //**********************
    while($this->db->next_record()) {
    	for($i=0;$i<$fdcount;$i++){		// 给字段类值
    		$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    		for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			$this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		}    		
    	}
    	
    	
    	
    	$key = "" ;
    	for($i=0;$i<count($this->brow_getvalue);$i++){		// 给所得值
    		$key .= $this->db->f($this->brow_getvalue[$i]).$this->brow_lfh ; // 得出返回值
    	}
    	
    	/*if(!empty($this->brow_ischildfiled)){   //如果不为空就查询是否还有子类别
    		$childkey = $this->db->f($this->brow_ischildfiled);
    		$ischildvalue = $this->ishavechile($childkey);     //调用判断是否还有子类。
    		$key .= $rowcount.$this->brow_lfh;
    		$key .= $childkey.$this->brow_lfh;
    	}*/
    	
    	$showline = $this->showline($fdcount,$key);	// 得出内容
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
    // 得出链接
      
     $this->brow_rowto = $this->brow_rowfrom + $this->db->nf() ;  //本页显示的记录到第几条
       
    $this->showfind($whatdofind,$howdofind,$findwhat) ; // 生成查询
    
    $this->t = new Template($this->brow_tmpcatlog, "keep");    
    $this->t->set_file("findbrowse",$this->brow_tmpname); 

    $this->t->set_var("prgname",$this->prgname);

    $this->t->set_var("findsay",$this->brow_sayfind);
    
    $this->t->set_var("allrowcount",$rowcount);
    
    $this->t->set_var("brow_changeurl",$this->brow_changeurl);  //转换地址
    
    $this->t->set_var("isshowotherpage",$isshowotherpage);  //是否显示转入按钮
    $this->t->set_var("otherpagename",$this->brow_otherpagename);  //转到另外页面的按纽名称
    $this->t->set_var("brow_otherpageurl",$this->brow_otherpageurl);  //转到另外页面的连接地址

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
    $this->t->set_var("skin",$this->brow_skin);	// 皮肤

    $this->t->set_block("findbrowse", "TITLEBK", "titlebks");  // 列标题
    for($i=0;$i<count($titlearr);$i++){
      $this->t->set_var(array("title" => $titlearr[$i][title],
     		      	      "titlecss" => $titlearr[$i][titlecss],
     		      	      "cols" => $titlearr[$i][cols]
     		      	      )) ;
      $this->t->parse("titlebks", "TITLEBK", true);
    }

    $this->t->set_block("findbrowse", "DATABK", "databks");  // 行内容
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

    $this->t->set_block("findbrowse", "FINDBK", "findbks");  // 行内容
    for($i=0;$i<$this->brow_findnum;$i++){
      $this->t->set_var(array("whatdofind" => $this->brow_showfind[$i][0],
      			      "howdofind" => $this->brow_showfind[$i][1],
      			      "findwhat" => $this->brow_showfind[$i][2]
     		      	      )) ;
      $this->t->parse("findbks", "FINDBK", true);
    }

    $this->t->pparse("out", "findbrowse");    # 最后输出页面
  }
   
  function ishavechile($fatherno){  //查询是否还有子科目
  	$query = sprintf($this->brow_ischildquery,$fatherno);
    $this->db1->query($query);  
    if($this->db1->nf()){
    	$returnflag = 0;
    }else{
      $returnflag = 1;
    }
  	return $returnflag;
  } 

  function makemore() {	// 生成下拉框
  }

  function showtitle($n,$m) {	// 生成标题
  	for($i=0;$i<$n;$i++){
  		$show = "<a class = title href=\"javascript:submit_order('".$this->brow_field[$i]."')\">".$this->fd[$i]->bwfd_title."</a>" ;
    		$titlearr[] = array("title" => $show,
    				    "titlecss" => $this->fd[$i]->bwfd_titlecss,
    				    "cols"=>"");	
    	}
  	if(!empty($this->brow_edit)){	// 生成操作位	
  	     $m++ ;
  	}
  	if($m > 0){
  	     $cols = "colspan='".$m."'";
	     $titlearr[] = array("title" => "操作","titlecss" => "listcelltitle","cols"=>$cols);
	}
 	return $titlearr ;
  }

  function showline($n,$key) {		// 生成行内容
  

	$show = "<TD class=listcellrow style='WIDTH: 10px'><input type='radio' name='radiocheck' value='$key' ></td>";
   	for($i=0;$i<$n;$i++){
    		$fd_show = $this->fd[$i]->makeshow() ;
    		$fd_align = $this->fd[$i]->bwfd_align ;
    		$fd_nowrap = $this->fd[$i]->bwfd_nowrap ;
    		$show .= "<TD class=listcellrow ".$fd_nowrap." align=$fd_align>$fd_show</TD>";
    	} 
  	return $show;
  }

  function showfind($whatdofind,$howdofind,$findwhat) {		// 生成查询标题
	for($i=0;$i<$this->brow_findnum;$i++){
		$findtitle = "";	// 查询标题
		$findhow = "";
		for($j=0;$j<count($this->brow_find);$j++){
			if($whatdofind[$i]==$this->brow_find[$j][1]){
    				$findtitle .= "<option selected value='".$this->brow_find[$j][1]."'>".$this->brow_find[$j][0]."</option>" ;
    			}else{
    				$findtitle .= "<option value='".$this->brow_find[$j][1]."'>".$this->brow_find[$j][0]."</option>" ;
    			}
    		}
		if($howdofind[$i] == ">"){
    		        $findhow .= "<option selected value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;大于</option>";
    		}else{
    			$findhow .= "<option value='>'>>&nbsp;&nbsp;&nbsp;&nbsp;大于</option>";
    		}
		if($howdofind[$i] == ">="){
    		        $findhow .= "<option selected value='>='>>=&nbsp;&nbsp;&nbsp;大于等于</option>";
    		}else{
    			$findhow .= "<option value='>='>>=&nbsp;&nbsp;&nbsp;大于等于</option>";
    		}
		if($howdofind[$i] == "="){
    		        $findhow .= "<option selected value='='>=&nbsp;&nbsp;&nbsp;&nbsp;等于</option>";
    		}else{
    			$findhow .= "<option value='='>=&nbsp;&nbsp;&nbsp;&nbsp;等于</option>";
    		}                        
		if($howdofind[$i] == "<="){
    		        $findhow .= "<option selected value='<='><=&nbsp;&nbsp;&nbsp;小于等于</option>";
    		}else{
    			$findhow .= "<option value='<='><=&nbsp;&nbsp;&nbsp;小于等于</option>";
    		}                         
		if($howdofind[$i] == "<"){
    		        $findhow .= "<option selected value='<'><&nbsp;&nbsp;&nbsp;&nbsp;小于</option>";
    		}else{
    			$findhow .= "<option value='<'><&nbsp;&nbsp;&nbsp;&nbsp;小于</option>";
    		}                         
 		if($howdofind[$i] == "like"){
    		        $findhow .= "<option selected value='like'>LIKE 包含</option>";
    		}else{
    			$findhow .= "<option value='like'>LIKE 包含</option>";
    		}
 		if($howdofind[$i] == "not like"){
    		        $findhow .= "<option selected value='not like'>NOIN 不包含</option>";
    		}else{
    			$findhow .= "<option value='not like'>NOIN 不包含</option>";
    		}    		                        
 		if($howdofind[$i] == "<>"){
    		        $findhow .= "<option selected value='<>'><>&nbsp;&nbsp;&nbsp;不等于</option>";
    		}else{
    			$findhow .= "<option value='<>'><>&nbsp;&nbsp;&nbsp;不等于</option>";
    		}                        
    		$this->brow_showfind[$i][0] = $findtitle ;
    		$this->brow_showfind[$i][1] = $findhow ;
    		$this->brow_showfind[$i][2] = $findwhat[$i] ;
    	} 
  }

  function makepages($n,$all,$num) {	// 得出页数连接 $n当前页 ,$all总页数,$num 可显页数 现在$num只可为10
	$show = "" ;
	$showend = "" ;
  	if($all<=$num) $num = $all ;	// 如果总页数少于可显页数

	if($n > 1) $show .= "<a href='javascript:viewPage(1)' title=首页><font face=webdings>9</font></a>\n" ;
	if($n > $num) $show .= "<a href='javascript:viewPage(".($n-$num).")' title=上".$num."页><font face=webdings>7</font></a>\n" ;

	if($n < $all-$num) $showend .= "<a href='javascript:viewPage(".($n+$num).")' title=下".$num."页><font face=webdings>8</font></a>\n" ;
	if($n < $all) $showend .= "<a href='javascript:viewPage($all)' title=尾页><font face=webdings>:</font></a>\n" ;


	if($n <= 6){			// 前5页处理法
		for($i=1;$i<=$num;$i++){
			if($i==$n){
			   $show .= "<font color=#ff0000>$i</font>\n";
			}else{
		           $show .= "<a href='javascript:viewPage($i)'>$i</a>\n";
			}
		}
		return $show.$showend ;
	}
	if($n > ($all - 4)){			// 后5页处理法
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

  function makeorder($n) {		// 生成排序要求
	$show = "";
   	for($i=0;$i<$n;$i++){
   		if($this->brow_field[$i] == $this->brow_order){ // 当前所用排序组
   			$show = " order by ".$this->fd[$i]->getorder($this->brow_orderud) ; // 通知该字段
		}
    	} 
  	$this->brow_queryorder = $show ;
  }
  
  function makewhere($whatdofind,$howdofind,$findwhat) {		// 生成查询要求
  	$show = $this->brow_querywhere ;
  	$showsay = "";
  	for($i=0;$i<count($whatdofind);$i++){
  		$tmp = $this->makefindsql($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
  		if(!empty($tmp)){
  			$say = $this->makefindsay($whatdofind[$i],$howdofind[$i],$findwhat[$i]);
  			if(empty($show)){	// 没有别的条件
 				$show .= $tmp ;
 				$showsay .= $say ;
  			}else{
	  			$show .= " and ".$tmp;
	  			$showsay .= " 并且 ".$say ;
  			}
  		}
	}
  	if(!empty($show)) $this->brow_querywhere = "where ".$show ;
  	if(!empty($showsay)){
  		$this->brow_sayfind = "<TABLE  cellSpacing=0 cellPadding=0 width='98%' align=center border=0><TBODY>" ;
  		$this->brow_sayfind .= "<TR vAlign=center><TD  class=listtai2 align=left>";
		$this->brow_sayfind .= "当前查询条件:".$showsay ;
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

  function makefindsay($whatdofind,$howdofind,$findwhat){	// 显示当前条件
   	for($i=0;$i<count($this->brow_find);$i++){
   		if($this->brow_find[$i][1] == $whatdofind){
   			$show = $this->brow_find[$i][0] ;
    			switch ($howdofind){		
        			case ">":
	   				$show .= "&nbsp;大于";
	  			 	break;
        			case ">=":
	   				$show .= "&nbsp;大于等于";
	  			 	break;  
        			case "=":
	   				$show .= "&nbsp;等于";
	  			 	break;  
        			case "<=":
	   				$show .= "&nbsp;小于等于";
	  			 	break;  
        			case "<":
	   				$show .= "&nbsp;小于";
	  			 	break;  
        			case "like":
	   				$show .= "&nbsp;包含";
	  			 	break;
        			case "not like":
	   				$show .= "&nbsp;不包含";
	  			 	break;	  			 			 		  			 		  			 		  			 	
        			case "<>":
	   				$show .= "&nbsp;不等于";
	  			 	break;	  			 	             
				default:
    	   			break;
    			}
    			$show .= $findwhat." ";
   		}	
   	}
  	return $show ;
  }

  function makequery($done) {	// 得出当前的sql语句
    if(!empty($this->this->brow_querywhere)) $this->brow_querygroupby = "group by ".$this->brow_querygroupby;
    if($done=="all"){	//所有记录
        $query = $this->brow_queryselect." ".$this->brow_querywhere." ".$this->brow_querygroupby ;
    }else{		// 当前页记录
        if($this->brow_rowfrom<0){
         	   $this->brow_rowfrom=0;
        }
        $query = $this->brow_queryselect." ".$this->brow_querywhere." ".$this->brow_querygroupby." ".$this->brow_queryorder." limit ".$this->brow_rowfrom." , ".$this->brow_rows;
    }
    return $query ;
  } 

  // 每行颜色
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
   var $bwfd_fdname ;	// 数据库中字段名称
   var $bwfd_title ;	// 字段标题
   var $bwfd_titlecss = "listcelltitle";	// 标题css类
   var $bwfd_need = array();	// 所需数值
   var $bwfd_text = "" ;	// 字段内容说明
   var $bwfd_align = "left";	// 字段对齐
   var $bwfd_fdcss = "fd";	// 字段css类
   var $bwfd_order = "";	// 字段排序的sql
   var $bwfd_nowrap = "noWrap" ; // 可否折行 
   
   var $bwfd_value ;	// 值
   
  function makeshow() {	// 将值转为显示值
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

  function getorder($done) {	// 生成链接
  	if($done=="asc"){
	  	$this->bwfd_title .= " <font face=webdings>5</font>";
  	}else{
  		$this->bwfd_title .= " <font face=webdings>6</font>";
	}
  	return $this->bwfd_fdname." ".$done." " ;
  }
  
  function maketext() {	// 生成说明
  	return "";
  }  
  
  
}
?>