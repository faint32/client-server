<?
function listnumber_view($listtype){
	//global $loginorganno ;
	$db  = new DB_test;

	$query = "select * from tb_listnumber where fd_ltnr_typeid = '$listtype'";
	$db->query($query);
	
	if($db->nf()){
		$db->next_record();
		$tmp_listnumber = $db->f(fd_ltnr_design);
		$isdateauto     = $db->f(fd_ltnr_isdateauto);  //是否自动生成
		$datetype       = $db->f(fd_ltnr_datetype);    //日期格式
		//echo $query;
		$year  = date( "Y", mktime()) ;
	  $month = date( "m", mktime()) ;
	  $day   = date( "d", mktime()) ;
	  
	  if($isdateauto==1){
	  	$sqlwhere = " and fd_ltct_date ='$year-$month-$day' ";
	  }else{
	    $sqlwhere = " ";
	  }
	   
	  $ltctcount=1;
	  $query = "select * from tb_listcount where fd_ltct_ltnrtypeid = '$listtype' $sqlwhere ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$ltctcount = $db->f(fd_ltct_count)+1;
	  	$ltctid    = $db->f(fd_ltct_id);
	  	
	  }
	  
	  if($ltctcount<10){
	  	$endltctcount = "00".$ltctcount;
	  }elseif($ltctcount<100 and $ltctcount>=10){
	    $endltctcount = "0".$ltctcount;
	  }else{
	    $endltctcount = $ltctcount;
	  }

	  if($datetype==1){  //全年份得日期格式
	     $tmp_listnumber = str_replace("{listno_date}", $year.$month.$day , $tmp_listnumber);

	  }else{
	     $endyear = substr($year,2,2);
	     $tmp_listnumber = str_replace("{listno_date}", $endyear.$month.$day , $tmp_listnumber);
    }
    
    $listno = str_replace("{list_number}", $endltctcount , $tmp_listnumber);
	}
	return $listno;
}


function listnumber_update($listtype){
	//global $loginorganno ;
	$db  = new DB_test;

	$query = "select * from tb_listnumber where fd_ltnr_typeid = '$listtype'";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$tmp_listnumber = $db->f(fd_ltnr_design);
		$isdateauto    = $db->f(fd_ltnr_isdateauto);  //是否自动生成
		$datetype  = $db->f(fd_ltnr_datetype);    //日期格式
		
		$year  = date( "Y", mktime()) ;
	  $month = date( "m", mktime()) ;
	  $day   = date( "d", mktime()) ;
	  
	  if($isdateauto==1){
	  	$sqlwhere = " and fd_ltct_date = '$year-$month-$day' ";
	  }else{
	    $sqlwhere = " ";
	  }
	   
	  $ltctcount=1;
	  $query = "select * from tb_listcount where fd_ltct_ltnrtypeid = '$listtype' $sqlwhere ";
	  $db->query($query);
	  if($db->nf()){
	  	$db->next_record();
	  	$ltctcount = $db->f(fd_ltct_count)+1;
	  	$ltctid    = $db->f(fd_ltct_id);
	  	
	  	
	  	$query = "update tb_listcount set
	              fd_ltct_count = fd_ltct_count+1
	              where fd_ltct_id = '$ltctid' ";
	    $db->query($query);
	  }else{
	    $query = "insert into tb_listcount(
	              fd_ltct_count , fd_ltct_date , fd_ltct_ltnrtypeid
	              )values(
	              1             , now()        , '$listtype'
	              )";
	    $db->query($query);
	  }
	  
	  if($ltctcount<10){
	  	$endltctcount = "00".$ltctcount;
	  }elseif($ltctcount<100 and $ltctcount>=10){
	    $endltctcount = "0".$ltctcount;
	  }else{
	    $endltctcount = $ltctcount;
	  }
	  
	  if($datetype==1){  //全年份得日期格式
	     $tmp_listnumber = str_replace("{listno_date}", $year.$month.$day , $tmp_listnumber);
	  }else{
	     $endyear = substr($year,2,2);
	     $tmp_listnumber = str_replace("{listno_date}", $endyear.$month.$day , $tmp_listnumber);
    }
    
    $listno = str_replace("{list_number}", $endltctcount , $tmp_listnumber);
	}
	return $listno;
}

?>