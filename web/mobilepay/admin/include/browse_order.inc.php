<?php
/*
 browse页面类
 */ 


 
class browse {
	 var $classname = "browse";
   var $database_class = "DB_test";     //调用数据库连接类
   var $template_class = "Template";
   
   var $prgnoware = array() ;  // 功能现在位置
   var $prgnowareurl = array() ; // 功能现在位置url   

   var $browse_rows = 25;  //每页显示的记录条数
   var $browse_pages ;     //总共页数
   var $browse_nowpage ;   //当前页数
   var $browse_rowfrom ;   //本页显示的记录从第几条
   var $browse_rowto ;     //本页显示的记录到第几条
   var $browse_total ;     //总的记录数
   var $browse_order = "";     //当前查询顺序
   var $browse_ordersn = "" ;  // 顺序或逆序
   var $browse_defaultorder = "";  //默认排序
   var $browse_editname = "修改" ;
   var $browse_delname = "删除";
   var $browse_isshowtitleeditname = "0" ;
   //var $browse_showpages = 10;     //同时显示的页数
  
   var $browse_query = "";        //所取数据来源 ："select title,auth from book" 
   var $browse_queryselect = "";  //查询语句的变量**********
   var $browse_querywhere = "";   //查询语句的条件变量********
   var $browse_queryorder = "";   //按什么排序的变量************
   var $browse_querygroupby  = "";   //按什么分组的变量************
   var $browse_field = array();	// 显示的字段
   
   //var $browse_qx ;  //   当前用户的权限

   var $browse_key ;  //   记录唯一键值
   var $browse_check = array();   // 所选择的记录
   var $browse_delsql = "" ;   // 删除语句
   //var $browse_delcontsql = "";  //删除和$browse_delsql有相对应的语句
   var $browse_relatingdelsql=array();//删除和$browse_delsql有相对应的语句
   
   var $browse_collectquery ="";  //汇总查询语句
   var $browse_collectname = "";  //汇总相应的名称
   var $browse_collectdata = "";  //汇总数据
   
   var $browse_new = "" ; // 新建连接
   var $browse_edit = "" ; // 编辑连接
   var $browse_alledit = "" ; // 编辑连接
   var $browse_inputfile  = "" ; // 导入数据连接
   var $browse_outtoexcel = "" ; // 导出excle连接
   var $browse_fieldname  = array() ; //导出数据的字段名称
   var $browse_fieldval  = array() ; // 导出数据的字段
   var $browse_ischeck  = array();   //是否默认导出已经选择，1代表选择、0代表没有选择
   
   var $browse_outtoprint = "" ; // 列出打印的数据
   var $browse_printfieldname  = array() ; //列出打印的数据的字段名称
   var $browse_printfieldval  = array() ; // 列出打印的数据的字段
   var $browse_printischeck  = array();   //是否默认列出打印的已经选择，1代表选择、0代表没有选择
   
   var $backuppageurl = "";  //返回按钮的url
   
   var $browse_state = array();   //多余获取的字段。用来判断状态。
   var $arr_spilthfield = array(); //存储多余字段的值。
   
   var $browse_addqx = "";     //新增权限
   var $browse_editqx = "";    //编辑权限
   var $browse_delqx = "";     //删除权限
   
   var $browse_rowfield = array();  //长框内容显示
   
   var $browse_hidden = array() ; // 隐藏的值-----2005-10-31----------------

   var $browse_link = array() ; // 扩展连接   
   var $browse_find = array() ; // 查询列表
   var $browse_sayfind = "" ; // 当前查询条件说明
   var $browse_showfind = array() ; // 查询显示用
   
   var $browse_seldofile = array() ; //下拉框数组--------2006-1-12-----------
   var $browse_seldofileval = array() ; //下拉框数组的值--------2006-1-12-----------
   var $browse_haveselectvalue ="";   //下拉框选中的值--------2006-1-12-----------
   var $browse_firstval ="";   //下拉框第一个显示的数--------2006-1-12-----------
   
   var $browse_operationflag = "0";  //-------标识操作区是放在前面还是后面----------2008-6-28加入--------------------
   var $browse_editplaceflag = "0";  //标识编辑是放在前面还是后面----------2009-7-2加入--------------------
   
   //var $browse_more = 0 ;  // 如果是0则不显示下拉框，1则显示 

   //var $browse_tmpcatlog = "../include/" ;  //模板文件路径
   //var $browse_tmpname = "../include/browse.ihtml" ;  //模板文件名   
   
   //var $browse_bakurl ;  //返回链接   
   var $browse_findnum = 5 ;  // 查询列数

   var $browse_skin = "" ;
   var $labelnum =0;   //
   var $allcondition=0;  //默认的查询条件类型
   
   //$action是事件变量。$pagerows是一页显示多小行。checknot点击要删除的记录的数
   //$upordown排序的条件
   function main($now,$action,$pagerows,$order,$upordown,$checknote,
  		           $prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) {
  		
  		$this->browse_nowpage = $now ;
      $this->browse_order = $order ; 
      $this->browse_ordersn = $upordown ;  
      $this->browse_check = $checknote ;  
      $this->browse_keyword = $keyword ;  
      //$this->browse_findtype = $findtype ;  
               	
  	  $this->browse_rows = $pagerows ;  //赋值一页显示多小行
      if($this->browse_rows == 0 ) $this->browse_rows = 25 ;	   //如果等于那就默认为25行
      
      //如果排序变量不为空那就表示按该变量进行分组显示。
      if(!empty($this->browse_querygroupby)) $this->browse_querygroupby = "group by ".$this->browse_querygroupby;     
      
      //  得出返回链接属性   
      $urlcan = "?now=".$now."&pagerows=".$pagerows."&order=".$order."&upordown=".$upordown ;   	
  		$tempurl = rawurlencode($urlcan); //本函式将字串编码成 URL 的字串专用格式
  		
  		$name = $this->database_class;   
      $this->db = new $name;        
      
      $prglist = "";	// 得出头部
      for($i=0;$i<count($this->prgnoware);$i++){
        if (empty($this->prglist)){
           $this->prglist .= "&nbsp;".$this->prgnoware[$i];
        }else{
        	 $this->prglist .= "&nbsp;-->&nbsp;".$this->prgnoware[$i]."\n";
           //$this->prglist .= "&nbsp;--><A href=\"".$this->prgnowareurl[$i]."\">&nbsp;".$this->prgnoware[$i]."</A>\n";
        }
      }  
      
      if($action=="delete") $this->dodelete();  //如果是删除命令就调用删除函数*******  	
      
      // 将字段类产生
      $fdcount = count($this->browse_field) ;    //总共有多小要显示的个字段
      for($i=0;$i<$fdcount;$i++){
       	$name = $this->browse_field[$i];
     	  $this->fd[$i] = new $name;
      }
      //-------------------2005-10-18-------------------------------
      $fdrowcount = count($this->browse_rowfield) ;    //总共有多小要底下显示的个字段
      for($i=0;$i<$fdrowcount;$i++){
       	$name = $this->browse_rowfield[$i];
       	$sx_k = $fdcount+$i;
     	  $this->fd[$sx_k] = new $name;
      }
      //-------------------2005-10-18-------------------------------
      if(empty($this->browse_nowpage)){		//当前页
    	  $this->browse_nowpage = 1; 	 // 如果第一次进入就把nowpage赋值为1
      }
      $this->makewhere($whatdofind,$howdofind,$findwhat,$allcondition);	// 得出查询条件
      $query = $this->makequery("all");	// 总数据
      
      if($action=="collect"){  //如果是汇总命令就计算汇总数*******  	
      	$this->docollect();  //调用汇总函数
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
      $this->browse_total = $this->db->nf();	//总的记录数
      
      $this->browse_pages = ceil($this->browse_total/$this->browse_rows); //总共页数
      if($this->browse_nowpage > $this->browse_pages) $this->browse_nowpage = $this->browse_pages ; // if当前页>总共页数,当前页=总共页数
      $this->browse_rowfrom = ($this->browse_nowpage-1) * $this->browse_rows ; //本页显示的记录从第几条
      
      // 得出分页操作功能
      //$this->browse_nowpage是当前页数，$this->browse_pages总共页数。
      $showviewpage = $this->pagination($this->browse_nowpage,$this->browse_pages) ;
    
      // 得出排序语句
      if(!empty($this->browse_order)){
        $this->makeorder($fdcount);  
      }else{
        //得出默认排序语句
        if(!empty($this->browse_defaultorder)){
          $this->browse_queryorder = " order by ".$this->browse_defaultorder ;
        }
      }
      
      // 得出本页记录
      $query = $this->makequery("now");	// 总数据
      $this->db->query($query);

      // 将链接类产生
      $lkcount = count($this->browse_link) ;
      for($i=0;$i<$lkcount;$i++){
     	   $name = $this->browse_link[$i];
     	   $this->lk[$i] = new $name;
      }
      
      $titlearr = $this->showtitle($fdcount,$lkcount);    // 字段标题
      $TrColor = ""; 
      
      
      //显示查询出来的数据
      while($this->db->next_record()) {
    	    $key = $this->db->f($this->browse_key) ;	// 得出唯一值
    	    for($i=0;$i<$fdcount;$i++){		// 给字段类值
    	     	$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    	     	
    	     	$this->fd[$i]->bwfd_otherfieldval = $this->db->f($this->fd[$i]->bwfd_otherfieldname);  //2010-3-22日加入，显示字段的获取其他字段的值
    	     	
    		    for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			     $this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		    }    		
    	    }
    	    //-----------2005-10-18------------------
    	    for($sx_j=0;$sx_j<$fdrowcount;$sx_j++){		// 给行的字段类值
    	    	$i = $sx_j+$fdcount;
    	     	$this->fd[$i]->bwfd_value = $this->db->f($this->fd[$i]->bwfd_fdname);
    		    for($j=0;$j<count($this->fd[$i]->bwfd_need);$j++){
    			     $this->fd[$i]->bwfd_need[$j][1] = $this->db->f($this->fd[$i]->bwfd_need[$j][0]);
    		    }    		
    	    }
    	    //-----------2005-10-18------------------
    	    
    	    //-----------2007-10-30------------------------------
    	    for($i=0;$i<count($this->browse_state);$i++){		//获取多余字段的值
    	    	if(!empty($this->browse_state[$i])){
    	       	$this->arr_spilthfield[$i] = $this->db->f($this->browse_state[$i]);   
    	      }		
    	    }
    	    //-----------2007-10-30-----------------------------
    	    
    	    for($i=0;$i<$lkcount;$i++){		// 给链接类值
    		    for($j=0;$j<count($this->lk[$i]->bwlk_fdname);$j++){
    			     $this->lk[$i]->bwlk_fdname[$j][1] = $this->db->f($this->lk[$i]->bwlk_fdname[$j][0]);
    		    }
    	    }
    	   $this->labelnum++;  //数字相加
    	   if($this->browse_operationflag == 0){   //-----------------2008-6-28修改--------------------
    	   	 $showline  = "<label for=arrlabel[".$this->labelnum."] oncontextmenu = showMenu() ><TD class=listcellrow style='WIDTH: 10px;'><input type='checkbox' id=arrlabel[".$this->labelnum."] name='checknote[]' value='$key' ></td>";
    	     if($this->browse_editplaceflag == 1){
    	       $showline .= $this->showeditlink($lkcount,$key);	// 得出编辑链接
    	       $showline .= $this->showline($fdcount,$key);	// 得出内容
      	     $showline .= $this->showlink($lkcount,$key);	// 得出扩展链接
      	   }else{
    	       $showline .= $this->showline($fdcount,$key);	// 得出内容
    	       $showline .= $this->showeditlink($lkcount,$key);	// 得出编辑链接
      	     $showline .= $this->showlink($lkcount,$key);	// 得出扩展链接
      	   }
      	 }else{
      	   $showline  = "<label for=arrlabel[".$this->labelnum."] oncontextmenu = showMenu() ><TD class=listcellrow style='WIDTH: 10px;'><input type='checkbox' id=arrlabel[".$this->labelnum."] name='checknote[]' value='$key' ></td>";
      	   $showline .= $this->showeditlink($lkcount,$key);	// 得出编辑链接
      	   $showline .= $this->showlink($lkcount,$key);	// 得出扩展链接
      	   $showline .= $this->showline($fdcount,$key);	// 得出内容
      	 }
  	     $TrColor = $this->Trbgcolor($TrColor) ;  //颜色代码
  	     
  	      //-----------2005-10-18-------------------
  	     $td_content="";
  	     for($tr_i=0;$tr_i< $fdrowcount;$tr_i++){
  	     	  $tr_k = $fdcount+$tr_i;
  	        $td_content .= $this->showtrfield($TrColor,$tr_k,$fdcount,$lkcount);  //显示一行的内容
  	     }
  	     //-----------2005-10-18------------------
  	     
	       $linearr[] = array("bgcolor" => $TrColor,"xi" => $showline,"td_con" => $td_content,"labelnum" => $this->labelnum);
       }
       
       if( empty($this->browse_new) or $this->browse_addqx!=1){	// 生成新建连接
    	    $shownew = "" ;
    	    $showdisnew = "none" ;
       }else{
    	    $shownew = $this->makenew();
    	    $showdisnew = "" ;
       }

       if( empty($this->browse_edit) or $this->browse_editqx!=1 ){	// 生成编辑连接
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

       if(empty($this->browse_delsql) or $this->browse_delqx!=1){	// 生成删除连接
    	    $showdel = "" ;
    	    $showdisdel = "none" ;
       }else{
    	    $showdel = $this->makedel();
    	    $showdisdel = "" ;
       }
       
       if(empty($this->backuppageurl)){	// 生成返回的连接 ;
    	    $displaybackbutton = "none" ;
       }else{
    	    $displaybackbutton = "" ;
       }
       
       /*if($showalldisedit == "none" && $showdisdel == "none" ){
        $showcountforlist = "style='display:none'";	
       }*/
       
       if(empty($this->browse_inputfile)){	   //导入数据连接
           $inputfile = "" ;
           $disinputfile = "none";
       }else{
           $inputfile = $this->browse_inputfile;
           $disinputfile = "";
       }
       
       if(empty($this->browse_outtoexcel)){	   //导出excle连接
           $outtoexcle = "" ;
           $disoutexcle = "none";
       }else{
           $outtoexcle = $this->browse_outtoexcel;
           $disoutexcle = "";
       }
       
       if(empty($this->browse_outtoprint)){	   //打印数据连接
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
       $haveselectvalue  = $this->browse_haveselectvalue;     //下拉框选中的值
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
       
       $this->browse_rowto = $this->browse_rowfrom + $this->db->nf() ;  //本页显示的记录到第几条
             
                                 
       $this->showfind($whatdofind,$howdofind,$findwhat) ; // 生成查询

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
       
       $this->t->set_var("inputfile",$inputfile);    //导入数据
       $this->t->set_var("disinputfile",$disinputfile);
       
       $this->t->set_var("outtoexcle",$outtoexcle);    //导出excel
       $this->t->set_var("disoutexcle",$disoutexcle);
       
       $this->t->set_var("outtoprint",$outtoprint);    //显示打印的数据
       $this->t->set_var("disoutprint",$disoutprint);
       
       $this->t->set_var("browse_collectdata",$this->browse_collectdata);  //汇总数据
       $this->t->set_var("isshowcollecttr",$isshowcollecttr);    //是否显示
       $this->t->set_var("dis_collectdata",$dis_collectdata);    //是否显示汇总按钮
       
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
       
       $this->t->set_var("seldofile",$seldofile);    //*********选择下拉框**************
       $this->t->set_var("displaysel",$displaysel);  //*********是否显示下拉框*************
       $this->t->set_var("firstval",$this->browse_firstval); //下拉框显示的第一个值
       
       $this->t->set_var("isallcheck",$isallcheck);
       $this->t->set_var("iseithercheck",$iseithercheck);
    
       $this->t->set_var("url",$tempurl);
       $this->t->set_var("skin",$this->browse_skin);	// 皮肤
    
       $this->t->set_block("browse", "TITLEBK", "titlebks");  // 列标题
       for($i=0;$i<count($titlearr);$i++){
          $this->t->set_var(array("title" => $titlearr[$i][title],
     		      	                  "titlecss" => $titlearr[$i][titlecss],
     		      	                  "cols" => $titlearr[$i][cols]
     		      	            )) ;
          $this->t->parse("titlebks", "TITLEBK", true);
       }

       $this->t->set_block("browse", "DATABK", "databks");  // 行内容
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

       $this->t->set_block("browse", "FINDBK", "findbks");  // 行内容
       for($i=0;$i<$this->browse_findnum;$i++){
            $this->t->set_var(array("whatdofind" => $this->browse_showfind[$i][0],
      			                       "howdofind" => $this->browse_showfind[$i][1],
      			                       "findwhat" => $this->browse_showfind[$i][2]
     		      	              )) ;
            		      	      
            $this->t->parse("findbks", "FINDBK", true);
       }
       
       //导出excel选择属性的块2007年6月28日加入
       $this->t->set_block("browse", "OUTFIELD", "outfields");  // 行内容
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
       
       //打印文件选择属性的块2008年3月21日加入
       $this->t->set_block("browse", "PRINTFIELD", "printfields");  // 行内容
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
       $this->t->pparse("out", "browse");    # 最后输出页面
   }
   //=============================================
   function makequery($isall) {	// 得出当前的sql语句
      if($isall=="all"){	//所有记录
         $query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby ;
      }else{		// 当前页记录
         if($this->browse_rowfrom<0){
         	   $this->browse_rowfrom=0;
         }
         $query = $this->browse_queryselect." ".$this->browse_querywhere." ".$this->browse_querygroupby." ".$this->browse_queryorder." limit ".$this->browse_rowfrom." , ".$this->browse_rows;
      }
      return $query ;
   }
   //============================================
   function pagination($nows,$all) {	// 得出页数连接 $nows当前页 ,$all总页数
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
    function showtitle($n,$m) {	// 生成标题
    	 if($this->browse_operationflag==0){//标识操作区是放在末尾的
    	 	 if($this->browse_editplaceflag == 1){
  	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// 生成操作位	
  	           $titlearr[] = array("title" => "编辑区","titlecss" => "listcelltitle","cols"=>"");
  	       }
  	     }
  	     for($i=0;$i<$n;$i++){
  		      $show = "<a class = title href=\"javascript:submit_order('".$this->browse_field[$i]."')\"> ".$this->fd[$i]->bwfd_title."</a>" ;
    		    $titlearr[] = array("title" => $show,
    		  		                   "titlecss" => $this->fd[$i]->bwfd_titlecss,
    		  		                   "cols"=>"");	
    	   }
    	   if($this->browse_editplaceflag == 0){
  	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// 生成操作位	
  	           $m++ ;
  	       }
  	     }
  	     if($m > 0){
  	         $cols = "colspan='".$m."'";
	           $titlearr[] = array("title" => "操作区","titlecss" => "listcelltitle","cols"=>$cols);
	       }
	     }else{  //标识操作区是放在头的
	       if(!empty($this->browse_edit ) and $this->browse_editqx==1){	// 生成操作位	
  	         $m++ ;
  	     }
  	     if($m > 0){
  	         $cols = "colspan='".$m."'";
	           $titlearr[] = array("title" => "操作区","titlecss" => "listcelltitle","cols"=>$cols);
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
    function showline($n,$key) {		// 生成行内容
    	
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
    	$show .= "<TD colspan=".$colspan." class=listcellrow ".$fd_nowrap." align=".$fd_align.">".$fd_title."：".$fd_show."</TD> ";
    	return $show."</TR>";
    }
    //-----------2005-10-18------------------
  //========================================
    function showlink($n,$key) {	// 生成扩展连接
  	   $show = "";
   	   for($i=0;$i<$n;$i++){
    		   $lk_show = $this->lk[$i]->makelink() ;
    		   $show .= "<TD class=listcellrow noWrap align=center>".$lk_show."</TD>";
    	 }	
    	 return $show ;
    }
    function showeditlink($n,$key) {	// 生成扩展连接
  	   $show = "";
  	   if(!empty($this->browse_edit) and $this->browse_editqx==1){			 	
		       $tmpshow = $this->makeedit($key);  // 得出edit
		       $show .= "<TD class=listcellrow noWrap align=center>".$tmpshow."</TD>";
	     }
    	 return $show ;
    }
    //====================================
    // 每行颜色
    function Trbgcolor($TrColor) {
      if ($TrColor=="#FFFFFF") {
          $TrColor="#EEF2FF";
      } else {
          $TrColor="#FFFFFF";
      }
      return($TrColor);
    }
    //==============================
    function makeedit($key) {	// 生成编辑连接
  	  $edit = "" ;
  	  if(!empty($this->browse_edit)){
  	     $edit = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")>".$this->browse_editname."</a>" ;
  	  }
  	  return $edit;	 // 
    }
    //====================
    function makenew() {	// 生成新建连接
  	  return $this->browse_new;	// $this->browse_new 是显示用的
    }
    //==================================
	  function makealledit() {	// 生成编辑连接
  	  return $this->browse_edit;	// $this->browse_edit 是显示用的
    }
    //==================================
	
    function makedel() {	// 生成删除连接
  	    return $this->browse_del;	// $this->browse_del 是显示用的
    }
    //===============================
    function dodelete(){	//  删除过程.
  	   for($i=0;$i<count($this->browse_check);$i++){
      		$query = sprintf($this->browse_delsql,$this->browse_check[$i]);
      		$this->db->query($query);  //删除点击的记录
      		
      		for($k=0;$k<count($this->browse_relatingdelsql);$k++){
      			  $query = sprintf($this->browse_relatingdelsql[$k],$this->browse_check[$i]);
      		    $this->db->query($query);   //删除和以点击记录相关的记录
      		}
      		/*if(!empty($this->browse_delcontsql)){
      		    $query = sprintf($this->browse_delcontsql,$this->browse_check[$i]);
      		    $this->db->query($query);   //删除和以点击记录相关的记录
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
    function makeorder($n) {		// 生成排序要求
	      $show = "";
      	for($i=0;$i<$n;$i++){
   		     if($this->browse_field[$i] == $this->browse_order){ // 当前所用排序组
   			       $show = " order by ".$this->fd[$i]->getorder($this->browse_ordersn) ; // 通知该字段
		       }
    	  } 
  	    $this->browse_queryorder = $show ;
     }
     //=======================================
    function makehidden(){   //生成隐藏值
    	$hiddenvalue = "";
    	for($j=0;$j<count($this->browse_hidden);$j++){
    		$hiddenname = $this->browse_hidden[$j][0];
    		$keyfield = $this->browse_hidden[$j][1];
    		$hiddenvalue .="<input type=hidden name='".$hiddenname."' value=".$keyfield." > ";
    	}
    	return $hiddenvalue;
    }
     //========================================
     function showfind($whatdofind,$howdofind,$findwhat) {		// 生成查询标题
	      for($i=0;$i<$this->browse_findnum;$i++){
		       $findtitle = "";	// 查询标题
		       $findhow = "";
		       for($j=0;$j<count($this->browse_find);$j++){
			        if($whatdofind[$i]==$this->browse_find[$j][1]){
    				      $findtitle .= "<option selected value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>" ;
    			    }else{
    				      $findtitle .= "<option value='".$this->browse_find[$j][1]."'>".$this->browse_find[$j][0]."</option>" ;
    			    }
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
 		                               
    		   $this->browse_showfind[$i][0] = $findtitle ;
    		   $this->browse_showfind[$i][1] = $findhow ;
    		   $this->browse_showfind[$i][2] = $findwhat[$i] ;
    	   } 
     }
     //=======================================
     function makewhere($whatdofind,$howdofind,$findwhat,$allcondition) {		// 生成查询要求
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
  			       if(empty($show)){	// 没有别的条件
 				           $show .= $tmp ;
 				           $showsay .= $say ;
 				           $contentflag = 1;
  			       }else{
  			       	  if($allcondition==1){
  			       	  	 if($contentflag==1){
	  			              $show .= " and ".$tmp;
	  			              $showsay .= " 并且 ".$say ;
	  			           }else{
	  			           	  $show .= " ".$tmp;
	  			           	  $contentflag = 1;
	  			              $showsay .= " ".$say ;
	  			           }
	  			        }else{
	  			        	 if($contentflag==1){
	  			        	    $show .= " or ".$tmp;
	  			              $showsay .= " 或者 ".$say ;
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
		          $this->browse_sayfind .= "当前查询条件:".$showsay ;
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
      function makefindsay($whatdofind,$howdofind,$findwhat){	// 显示当前条件
   	     for($i=0;$i<count($this->browse_find);$i++){
   		      if($this->browse_find[$i][1] == $whatdofind){
   			        $show = $this->browse_find[$i][0] ;
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
     //生成选择菜单的函数
     function makeselectfile($arritem,$hadselected,$arry){ 
     	  $x .= "<option value=''>请选择</option>"  ;	
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


//……………………………………………………………………………………………………………………
class browsefield {
   var $classname = "browsefield";
   var $bwfd_fdname ;	// 数据库中字段名称
   var $bwfd_title ;	// 字段标题
   var $bwfd_titlecss = "listcelltitle";	// 标题css类
   var $bwfd_need = array();	// 所需数值
   var $bwfd_align = "left";	// 字段对齐
   var $bwfd_fdcss = "fd";	// 字段css类
   var $bwfd_order = "";	// 字段排序的sql
   var $bwfd_nowrap = "noWrap" ; // 可否折行 
   var $bwfd_format = "" ;    // 数据格式  
   var $bwfd_value ;	// 值
   var $bwfd_link;   //内容的超级连接
   var $bwfd_isneetkey ; //是否需要关键字，0表示不需要，1表示需要 
   
   var $bwfd_otherfieldname = "";// 字段名字
   var $bwfd_otherfieldval = "" ;	// 字段值
   
  function makeshow() {	// 将值转为显示值
      $showvalue = $this->bwfd_value ;
      if($this->bwfd_format == "num"){
  		  $showvalue = number_format($showvalue, 2, ".", ",");
  		  $this->bwfd_align  = "right";
      }
      return $showvalue ;
  }
  function getorder($done) {	// 生成链接
  	if($done=="asc"){
	  	$this->bwfd_title .= " <font face=webdings>5</font>";
  	}else{
  		$this->bwfd_title .= " <font face=webdings>6</font>";
	  }
  	return $this->bwfd_fdname." ".$done." " ;
  }
}

//……………………………………………………………………………………………………………………
class browselink {
   var $classname = "browselink";
   var $bwlk_fdname = array();	// 所需数据库中字段名称
   var $bwlk_title ;	// link标题
   var $bwlk_linkcss = "listcelltitle";	// link标题css类
   var $bwlk_text = "";	// link说明
   var $bwlk_prgname = "";	// 字段链接程序
   var $bwlk_is_blank = 0;	// 是否弹出新窗口 ------------2005-1-1-------------------
   
   var $bwlk_fdvalue ;	// 字段值

  function makeprg() {	// 生成链接程序
    if(!empty($this->bwlk_fdname[0][1])){
	    $prg = $this->bwlk_prgname.$this->bwlk_fdname[0][1];
    }else{
    	$prg = "" ;
    }
    return $prg;
  }
 
  function makelink() {	// 生成链接
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